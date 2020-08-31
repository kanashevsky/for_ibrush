<?php

use \Bitrix\Main\Localization\Loc,
	\Bitrix\Main\Application,
	\Bitrix\Main\SystemException,
	\Pwd\Entity\CoursesTable,
	\Pwd\Entity\CoursesPropSimpleTable,
	\Bitrix\Main\DB\SqlExpression,
	\Bitrix\Main\Engine\ActionFilter,
	\Pwd\Helpers\UserHelper,
	\Pwd\Helpers\CIBlockElementRightsHelper,
	\Bitrix\Main\Engine\Contract\Controllerable;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class CoursesDetailComponent extends CBitrixComponent implements Controllerable
{
	public function configureActions()
	{
		// Сбрасываем фильтры по-умолчанию (ActionFilter\Authentication и ActionFilter\HttpMethod)
		// Предустановленные фильтры находятся в папке /bitrix/modules/main/lib/engine/actionfilter/
		return [
			'showFile' =>
				'prefilters' => [
					new ActionFilter\Authentication,
					new ActionFilter\HttpMethod([
						ActionFilter\HttpMethod::METHOD_POST
					])
				],
			],
		];
	}

	public function showFileAction($iFileID, $iElementID)
	{
		global $APPLICATION;
		$this->arResult['FILE_PATH'] = \CFile::GetPath($iFileID);

		if (!UserHelper::isListner()) return;

		$this->changeStatistic($iElementID, $iFileID);

		$APPLICATION->RestartBuffer();
		ob_start();
		$this->includeComponentTemplate('ajax');

		$template = ob_get_contents();

		ob_end_clean();

		$this->arResult['TEMPLATE'] = $template;

		return $this->arResult;

	}

	// Показываем pdf в Госзадании
	public function showFileStateTaskAction($iFileID)
	{
		global $APPLICATION;
		if ($iFileID == 1) {
			$this->arResult['FILE_PATH'] = '/local/templates/sopdu/docs/otchet_nauch_obespech.pdf';
		} else {
			$this->arResult['FILE_PATH'] = \CFile::GetPath($iFileID);
		}

		if (!UserHelper::isInstituteControl() && !UserHelper::isInstitute()) return;

		$APPLICATION->RestartBuffer();
		ob_start();
		$this->includeComponentTemplate('ajax');

		$template = ob_get_contents();

		ob_end_clean();

		$this->arResult['TEMPLATE'] = $template;

		return $this->arResult;
	}

	private function changeStatistic($iElementID, $iFileID)
	{

		global $USER;

		$iUserID = $USER->GetID();

		$arResult = \CIBlockElement::GetList(
			[],
			[
				'IBLOCK_ID' => CoursesTable::getIblockId(),
				'ID' => $iElementID
			],
			false,
			false,
			['ID', 'PROPERTY_STATISTICS']
		)->fetch();

		$arStatistic = $arResult['PROPERTY_STATISTICS_VALUE'] ? json_decode($arResult['PROPERTY_STATISTICS_VALUE']['TEXT'], true) : [];


		$iCount = isset($arStatistic[$iUserID]) && isset($arStatistic[$iUserID]['FILES'][$iFileID]) ? $arStatistic[$iUserID]['FILES'][$iFileID] : 0;

		$arStatistic[$iUserID]['FILES'][$iFileID] = ++$iCount;
		$arStatistic[$iUserID]['NAME'] = $USER->GetFullName();
		$arStatistic[$iUserID]['LOGIN'] = $USER->GetLogin();

		\CIBlockElement::SetPropertyValuesEx(
			$iElementID,
			CoursesTable::getIblockId(),
			[
				'STATISTICS' => ['VALUE' => ['TYPE' => 'TEXT', 'TEXT' => json_encode($arStatistic)]]
			]
		);

	}

	// Подключает языковые файлы

	public function onIncludeComponentLang()
	{
		$this->includeComponentLang(basename(__FILE__));
		Loc::loadMessages(__FILE__);
	}

	// Обработка входных параметров

	public function onPrepareComponentParams($arParams)
	{
		$arParams['ELEMENT_ID'] = isset($arParams['ELEMENT_ID']) && $arParams['ELEMENT_ID'] > 0 ? intval($arParams['ELEMENT_ID']) : false;
		if (
			$arParams['ELEMENT_ID'] &&
			!in_array($arParams['ELEMENT_ID'], CIBlockElementRightsHelper::GetListIDs(CoursesTable::getIblockId()))
			&& !in_array($arParams['ELEMENT_ID'], CIBlockElementRightsHelper::GetListCoursesTeacherIDs())
		) {
			LocalRedirect('/personal/courses/');
		}
		return $arParams;
	}

	private function getResult()
	{
		$arResult = CoursesTable::GetList([
			'select' => [
				'ID',
				'NAME',
				'DETAIL_TEXT',
				'FILES'
			],
			'filter' => [
				'ID' => $this->arParams['ELEMENT_ID']
			],
			'runtime' => [
				"FILES" => array(
					"data_type" => "string",
					"expression" => array(
						"GROUP_CONCAT(%s)",
						"PROPERTY_MULTIPLE_FILES.VALUE"
					)
				),
			]

		])->fetch();
		if (!$arResult) {
			throw new SystemException(Loc::getMessage('NOT_FOUND'));
		}
		$arResult['FILES'] = explode(',', $arResult['FILES']);

		$arFiles = [];
		foreach ($arResult['FILES'] as $iFiledID) {
			$arFiles[] = \CFile::GetByID($iFiledID)->fetch();
		};
		$arResult['FILES'] = $arFiles;

		$this->arResult['COURSE'] = $arResult;
	}

	// Выполняет логику работы компонента

	public function executeComponent()
	{
		global $APPLICATION;

		try {

			$this->getResult();
			$APPLICATION->AddChainItem($this->arResult["COURSE"]["NAME"]);

			$this->includeComponentTemplate();
		} catch (Exception $e) {
			ShowError($e->getMessage());
		}
	}
}

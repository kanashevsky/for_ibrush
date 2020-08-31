<?php

use \Bitrix\Main\Localization\Loc,
    \Bitrix\Main\Loader,
    \Pwd\Helpers\CIBlockElementRightsHelper,
    \Pwd\Entity\CoursesTable,
    \Bitrix\Main\UI\PageNavigation,
    \PhpOffice\PhpSpreadsheet\Spreadsheet,
    \PhpOffice\PhpSpreadsheet\Writer\Xlsx,
    \Bitrix\Main\DB\SqlExpression,
    \Pwd\Helpers\UserHelper;
use Pwd\Entity\QuestionsToCoursesTable;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class ClinicCatalogComponent extends CBitrixComponent
{

    //Подключает языковые файлы
    public function onIncludeComponentLang()
    {
        $this->includeComponentLang(basename(__FILE__));
        Loc::loadMessages(__FILE__);
    }

    //Входные параметры
    public function onPrepareComponentParams($arParams)
    {
        return $arParams;
    }

    //Получаем список
    protected function getResult()
    {
        Loader::includeModule('iblock');
        $arResult = [];

        global $USER;
        $arGroupCode = [];
        $arGroups = $USER->GetUserGroupArray();

        foreach ($arGroups as $iGroupID) {
            $arGroupCode[] = 'G' . $iGroupID;
        }

        if ($this->arParams["TEACHER"] && UserHelper::isCoursesTeacher()) {
            $arIDs = CIBlockElementRightsHelper::GetListCoursesTeacherIDs();
        } else {
            $arIDs = CIBlockElementRightsHelper::GetListIDs(CoursesTable::getIblockId());
        }

        $nav = new PageNavigation('page');
        $nav->allowAllRecords(false)
            ->setPageSize(20)
            ->initFromUri();

        $rsResult = CoursesTable::getList([
            'filter' => [
                'ACTIVE' => 'Y',
                'ID' => $arIDs
            ],
            'select' => [
                'ID',
                'ELEMENT_ID' => 'ID',
                'NAME',
                'PREVIEW_TEXT',
                'DETAIL_PAGE_URL',
            ],
            'limit' => $nav->getLimit(),
            'offset' => $nav->getOffset(),
            'count_total' => true,
        ]);

        $nav->setRecordCount($rsResult->getCount());

        while ($arRow = $rsResult->Fetch()) {
            $arRow['DETAIL_PAGE_URL'] = \CIBlock::ReplaceDetailUrl($arRow['DETAIL_PAGE_URL'], $arRow);
            if (UserHelper::isCoursesTeacher()) {
                $arRow['DETAIL_PAGE_URL'] = str_replace('courses', 'courses-teacher', $arRow['DETAIL_PAGE_URL']);
            }
            $course['NOT_PUBLIC'] = 0;
            $course['PUBLIC'] = 0;
            $course['NOT_ANSWER'] = 0;
            $course['WITH_ANSWER'] = 0;
            $course['ALL'] = 0;
            $arRow['QUESTIONS'] = $course;

            $arResult[$arRow['ID']] = $arRow;
			$idList[] = $arRow['ID'];
        }
		if(empty($idList)){
			$idsResultArray = [];
		} else{
			$idsResultArray = array_intersect_assoc($idList, $arIDs);
		}

        $rsQuestionsResult = QuestionsToCoursesTable::getList([
            'select' => [
                'ID',
                'ELEMENT_ID' => 'ID',
                'NAME',
                'PREVIEW_TEXT',
                'DETAIL_TEXT',
                'ACTIVE',
                'PROPERTY_SIMPLE.COURSE'
            ],
            'order' => [
                'DATE_CREATE' => 'DESC',
                'ACTIVE' => 'ASC',
                'DETAIL_TEXT' => 'ASC',
                'ACTIVE_FROM' => 'DESC',
                'SORT' => 'ASC',
            ],
            'filter' => [
                'PROPERTY_SIMPLE.COURSE' => $idsResultArray
            ],
            'limit' => $nav->getLimit(),
            'offset' => $nav->getOffset(),
            'count_total' => true
        ]);


        while ($arRow = $rsQuestionsResult->Fetch()) {
            if ($arRow["ACTIVE"] == "N") {
                $this->arResult["QUESTIONS"][$arRow['PWD_ENTITY_QUESTIONS_TO_COURSES_PROPERTY_SIMPLE_course']]['NOT_PUBLIC'][] = $arRow;
            } elseif ($arRow["ACTIVE"] == "Y") {
                $this->arResult["QUESTIONS"][$arRow['PWD_ENTITY_QUESTIONS_TO_COURSES_PROPERTY_SIMPLE_course']]['PUBLIC'][] = $arRow;
            }
            if (empty($arRow["DETAIL_TEXT"])) {
                $this->arResult["QUESTIONS"][$arRow['PWD_ENTITY_QUESTIONS_TO_COURSES_PROPERTY_SIMPLE_course']]['NOT_ANSWER'][] = $arRow;
            } else {
                $this->arResult["QUESTIONS"][$arRow['PWD_ENTITY_QUESTIONS_TO_COURSES_PROPERTY_SIMPLE_course']]['WITH_ANSWER'][] = $arRow;
            }
            $this->arResult["QUESTIONS"][$arRow['PWD_ENTITY_QUESTIONS_TO_COURSES_PROPERTY_SIMPLE_course']]['ALL'][] = $arRow;
        }
        foreach ($this->arResult["QUESTIONS"] as $id => &$course) {
            $course['NOT_PUBLIC'] = count($course['NOT_PUBLIC']);
            $course['PUBLIC'] = count($course['PUBLIC']);
            $course['NOT_ANSWER'] = count($course['NOT_ANSWER']);
            $course['WITH_ANSWER'] = count($course['WITH_ANSWER']);
            $course['ALL'] = count($course['ALL']);
            $arResult[$id]['QUESTIONS'] = $course;
        }

        $this->arResult['NAV'] = $nav;
        $this->arResult['ITEMS'] = $arResult;

    }

    protected function downloadStatistic()
    {

        if (!\Pwd\Helpers\UserHelper::isAdmin()) {
            LocalRedirect('/personal/courses/');
            exit();
        }
        global $APPLICATION;
        $arResult = \CIBlockElement::GetList(
            [],
            [
                'IBLOCK_ID' => CoursesTable::getIblockId(),
                'ID' => $this->request->get('download')
            ],
            false,
            false,
            ['ID', 'NAME', 'PROPERTY_STATISTICS', 'PROPERTY_FILES']
        )->fetch();
        $arStatistics = $arResult['PROPERTY_STATISTICS_VALUE'] ? json_decode($arResult['PROPERTY_STATISTICS_VALUE']['TEXT'], true) : [];

        $iRow = 1;
        $arFiles = [];
        foreach ($arResult['PROPERTY_FILES_VALUE'] as $iFileID) {
            $arFiles[$iFileID] = \CFile::GetByID($iFileID)->fetch();
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);

        $sheet->setCellValue('A1', 'ID Пользователя');
        $sheet->setCellValue('B1', 'Логин');
        $sheet->setCellValue('C1', 'ФИО');
        $sheet->setCellValue('D1', 'Файл');
        $sheet->setCellValue('E1', 'Количество просмотров');

        foreach ($arStatistics as $iUserID => $arStatistic) {
            foreach ($arStatistic['FILES'] as $iFileID => $iCount) {
                $iRow++;
                $sheet->setCellValue('A' . $iRow, $iUserID);
                $sheet->setCellValue('B' . $iRow, $arStatistic['LOGIN']);
                $sheet->setCellValue('C' . $iRow, $arStatistic['NAME']);
                $sheet->setCellValue('D' . $iRow, $arFiles[$iFileID]['ORIGINAL_NAME']);
                $sheet->setCellValue('E' . $iRow, $iCount);
            }
        }

        try {
            $APPLICATION->RestartBuffer();
            header('Content-Type: application/vnd.ms-excel');
            $file_name = Loc::getMessage('SUF_DOC') . $arResult['NAME'] . '_' . date("Y-m-d_H:i:s") . ".xlsx";
            header("Content-Disposition: attachment; filename=$file_name");

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit();

        } catch (PhpOffice\PhpSpreadsheet\Writer\Exception $e) {
            echo $e->getMessage();
        }
    }

    //Основная логика
    public function executeComponent()
    {
        global $APPLICATION;

        try {
            if ($this->request->get('download')) {
                $this->downloadStatistic();
            } else {
                if ($this->StartResultCache($this->arParams["CACHE_TIME"])) {
                    $this->getResult();
                    $this->includeComponentTemplate($this->page);
                }
            }

        } catch (Exception $e) {
            ShowError($e->getMessage());
        }
    }
}

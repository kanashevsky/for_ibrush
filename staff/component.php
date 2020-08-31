<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
global $USER;
$nGroup = ($arParams['ACCESS_GROUP'] ? $arParams['ACCESS_GROUP'] : CONST_GROUP_ID_OTD_KADR);
if($USER->IsAuthorized() && in_array($nGroup, $USER->GetUserGroupArray()) && CModule::IncludeModule("iblock")){
	function getUserData($user_id){
		$row = CUser::GetByID($user_id)->Fetch();
		$result = [
			"ID"            =>  $row["ID"],
			"LAST_NAME"     =>  $row["LAST_NAME"],
			"NAME"          =>  $row["NAME"],
			"SECOND_NAME"   =>  $row["SECOND_NAME"],
			"WORK_POSITION" =>  $row["WORK_POSITION"],
			"WORK_PHONE"    =>  $row["WORK_PHONE"],
			"EMAIL"         =>  $row["EMAIL"]
		];
		return $result;
	}

	//Сортировка по ФИО
	function sortUsers($arra, $arrb) {
		if ($arra['USER_NAME'] == $arrb['USER_NAME']) {
			return 0;
		}
		return ($arra['USER_NAME'] < $arrb['USER_NAME']) ? -1 : 1;
	}

	// Свойство ординатуры для загрузок
	$rsLoadTrainee = CIBlockPropertyEnum::GetList([], ["IBLOCK_ID" => CONST_IBLOCK_ID_EMPLOYEE_DOCUMENTS, "CODE" => "TRAINEESHIP", "XML_ID" => "Y"]);
	$mLoadTraineeship = false;
	if($arLoadTrainee = $rsLoadTrainee->GetNext()){
		$mLoadTraineeship = $arLoadTrainee['ID'];
	}

	if(!empty($_GET["USER_SD"])) {
		if(!empty($_GET["doc"])){
			CIBlockElement::SetPropertyValuesEx($_GET["doc"], false, array("kadriok" => 'Y'));
		}
		$arDocFilter = [
			"ACTIVE"        => 'Y',
			"IBLOCK_ID"     => CONST_IBLOCK_ID_EMPLOYEE_DOCUMENTS,
			"PROPERTY_user" =>  $_GET["USER_SD"],
			"PROPERTY_TRAINEESHIP" => ($arParams['TRAINEESHIP'] != 'Y' ? false : $mLoadTraineeship)
		];
		$zapros = CIBlockElement::GetList(
			["DATE_CREATE"  =>   'asc'],
			$arDocFilter,
			false,
			false,
			[
				"ID",
				"IBLOCK_ID",
				"CODE",
				"NAME",
				"DATE_CREATE",
				"PROPERTY_document",
				"PROPERTY_kadriok",
				"PROPERTY_TRAINEESHIP"
			]
		);
		while ($row = $zapros->Fetch()){
			$count_tmp[$row["PROPERTY_KADRIOK_VALUE"]][$row["ID"]] = $row["ID"];
			$arResult["type"] = 'user';

			$arResult["doc"][$row["PROPERTY_KADRIOK_VALUE"]][$row["ID"]] = [
				"NAME" => $row["NAME"],
				"DATE_CREATE" => $row["DATE_CREATE"],
				"PROPERTY_DOCUMENT_VALUE" => $row["PROPERTY_DOCUMENT_VALUE"],
				"ID" => $row["ID"]
			];
			$arResult["cont"] = [
				"Y" => count($count_tmp["Y"]),
				"N" => count($count_tmp["N"])
			];
			$arResult["user"] = getUserData($_GET["USER_SD"]);
		}
	} else {
		$zapros = CIBlockElement::GetList(
			["DATE_CREATE"  =>   'asc'],
			[
				"ACTIVE" => 'Y',
				"IBLOCK_ID" => CONST_IBLOCK_ID_EMPLOYEE_DOCUMENTS,
				"PROPERTY_TRAINEESHIP" => ($arParams['TRAINEESHIP'] != 'Y' ? false : $mLoadTraineeship)
			],
			false,
			false,
			[
				"ID",
				"IBLOCK_ID",
				"NAME",
				"DATE_CREATE",
				"PROPERTY_user",
				"PROPERTY_document",
				"PROPERTY_kadriok"
			]
		);
		while ($row = $zapros->Fetch()) {
			$count_tmp[$row["PROPERTY_KADRIOK_VALUE"]][$row["ID"]] = $row["ID"];
			$arResult["type"] = 'list';
			$arResult[$row["PROPERTY_KADRIOK_VALUE"]][$row["PROPERTY_USER_VALUE"]]["doc"][$row["ID"]] = [
				"NAME" => $row["NAME"],
				"DATE_CREATE" => $row["DATE_CREATE"],
			];
			$arUser = getUserData($row["PROPERTY_USER_VALUE"]);
			$arResult[$row["PROPERTY_KADRIOK_VALUE"]][$row["PROPERTY_USER_VALUE"]]["user"] = $arUser;
			$arResult[$row["PROPERTY_KADRIOK_VALUE"]][$row["PROPERTY_USER_VALUE"]]['USER_NAME'] = $arUser['LAST_NAME'].' '.$arUser['NAME'].' '.$arUser['SECOND_NAME'];
			$arResult["count"] = [
				"Y" => count($count_tmp["Y"]),
				"N" => count($count_tmp["N"])
			];
		}
	}

	// сортируем списки по алфавиту
	uasort($arResult['Y'], 'sortUsers');
	uasort($arResult['N'], 'sortUsers');

		$this->IncludeComponentTemplate();
} else {
	echo '<span style="color: red">'.GetMessage("myDocsNoKadry").'</span>';
}

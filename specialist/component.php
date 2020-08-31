<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if($arParams["specialistActive"] == 'Y') {
	$arResult = [
		"NAME"  =>  $arParams["specialistName"],
		"STAFF" =>  $arParams["specialistStaff"],
		"PHOTO" =>  $arParams["specialistPhoto"],
		"URL"   =>  $arParams["specialistURL"],
		"TEL"   =>  $arParams["specialistTEL"],
		"MAIL"   =>  $arParams["specialistMAIL"],
	];
	$this->IncludeComponentTemplate();
}

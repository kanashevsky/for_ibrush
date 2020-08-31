<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if($arParams["specialistContacActive"] == 'Y'){
	function phone($phone){
		$result = preg_replace('/[^0-9]/', '', $phone);
		return 'tel:+'.$result;
	}
	if(!empty($arParams["specialistContacPhone"])){
		$arResult["phone"] = $arParams["specialistContacPhone"];
		$arResult["phoneLink"] = phone($arParams["specialistContacPhone"]);
		if(!empty($arParams["specialistContacDopPhone"])){
			$arResult["dopPhone"] = $arParams["specialistContacDopPhone"];
		}
	}
	if(!empty($arParams["specialistContacEmail"])){
		$arResult["email"] = $arParams["specialistContacEmail"];
	}
	$this->IncludeComponentTemplate();
}

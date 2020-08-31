<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentDescription = array(
	"NAME"          =>  GetMessage("spesialistContact_Name"),
	"DESCRIPTION"   =>  GetMessage("spesialistContact_Descritrion"),
	"CACHE_PATH"    =>  'Y',
	"PATH"          =>  array(
		"ID"        =>  "sopdu",
		"NAME"      =>  GetMessage("spesialistContact_Developer")
	)
);
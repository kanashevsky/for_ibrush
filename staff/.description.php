<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentDescription = [
	"NAME"          =>  GetMessage("staffName"),
	"DESCRIPTION"   =>  GetMessage("staffDescription"),
	"CACHE_PATH"    =>  'Y',
	"PATH"          =>  [
		"ID"        =>  'sopdu',
		"NAME"      =>  GetMessage("staffDeveloper")
	]
];
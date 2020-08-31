<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
$arComponentParameters = array(
	"GROUPS" =>  array(
		"specialistContactParam" => array("NAME" => GetMessage("specialistContactParamGroup"))
	),
	"PARAMETERS" => array(
		"specialistContacPhone" =>  array(
			"PARENT"    =>  'specialistContactParam',
			"NAME"      =>  GetMessage("specialistContacPhone"),
			"REFRESH"   =>  'N',
			"TYPE"      =>  'STRING'
		),
		"specialistContacDopPhone" =>  array(
			"PARENT"    =>  'specialistContactParam',
			"NAME"      =>  GetMessage("specialistContacDopPhone"),
			"REFRESH"   =>  'N',
			"TYPE"      =>  'STRING'
		),
		"specialistContacEmail" =>  array(
			"PARENT"    =>  'specialistContactParam',
			"NAME"      =>  GetMessage("specialistContacEmail"),
			"REFRESH"   =>  'N',
			"TYPE"      =>  'STRING'
		),
		"specialistContacActive"  =>  array(
			"PARENT"    =>  'specialistContactParam',
			"NAME"      =>  GetMessage("specialistContacActive"),
			"TYPE"      =>  'CHECKBOX',
			"DEFAULT"   =>  'Y'
		)
	)
);

<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
$fileType = 'jpg, jpeg, png';
$arComponentParameters = array(
	"GROUPS" =>  array(
		"specialistParam"  =>  array("NAME" => 'Параметры компонента')
	),
	"PARAMETERS"    =>  array(
		"specialistName"     =>  array(
			"PARENT"    =>  'specialistParam',
			"NAME"      =>  GetMessage("specialistName"),
			"REFRESH"   =>  'N',
			"TYPE"      =>  'STRING'
		),
		"specialistStaff"   =>  array(
			"PARENT"    =>  'specialistParam',
			"NAME"      =>  GetMessage("specialistStaff"),
			"REFRESH"   =>  'N',
			"TYPE"      =>  'STRING'
		),
		"specialistPhoto"    =>  array(
			"PARENT"    =>  'specialistParam',
			"NAME"      =>  GetMessage("specialistPhoto"),
			"TYPE"      =>  'FILE',
			"FD_EXT"    =>  $fileType,
			"FD_UPLOAD" =>  true,
			"FD_USE_MEDIALIB" => true
		),
		"specialistURL"     =>  array(
			"PARENT"    =>  'specialistParam',
			"NAME"      =>  GetMessage("specialistURL"),
			"REFRESH"   =>  'N',
			"TYPE"      =>  'FILE'
		),
		"specialistActive"  =>  array(
			"PARENT"    =>  'specialistParam',
			"NAME"      =>  GetMessage("specialistActive"),
			"TYPE"      =>  'CHECKBOX',
			"DEFAULT"   =>  'Y'
		)
	)
);
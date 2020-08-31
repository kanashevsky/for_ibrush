<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$APPLICATION->IncludeComponent(
    "sopdu:courses.detail",
    "",
    [
        "ELEMENT_ID" => $arParams["VARIABLES"]["ELEMENT_ID"]
    ],
    $component
)

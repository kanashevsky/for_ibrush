<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$APPLICATION->IncludeComponent(
    "sopdu:courses.list",
    "",
    [
        "TEACHER" => $arParams["TEACHER"]
    ],
    $component
)
?>
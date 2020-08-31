<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$APPLICATION->IncludeComponent(
    "pwd:courses.questions",
    "",
    Array(
        "ELEMENT_ID" => $arResult['COURSE']['ID']
    )
);
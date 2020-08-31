<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$APPLICATION->IncludeComponent(
    "bitrix:pdf.viewer",
    "",
    Array(
        "HEIGHT" => "600",
        "IFRAME" => "N",
        "PATH" => $arResult['FILE_PATH'],
        "PRINT" => "N",
        "PRINT_URL" => "",
        "TITLE" => "",
        "VIEWER_ID" => "",
        "WIDTH" => "auto"
    )
);
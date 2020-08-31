<?php

use Bitrix\Main\Loader,
    \Pwd\Helpers\UserHelper,
    \Bitrix\Main\Localization\Loc;

class CoursesComponent extends CBitrixComponent
{
    private static $arDefaultUrlTemplates404 = [
        'list' => 'index.php',
        'detail' => '#ELEMENT_ID#/',
    ];
    public function onPrepareComponentParams($arParams)
    {
        return parent::onPrepareComponentParams($arParams);
    }
    //подключает языковые файлы
    public function onIncludeComponentLang()
    {
        $this->includeComponentLang(basename(__FILE__));
        Loc::loadMessages(__FILE__);
    }
    private static $arDefaultVariableAliases = [
        "ELEMENT_ID" => "ELEMENT_ID"
    ];

    public function executeComponent()
    {
        try {
            if(!UserHelper::isListner() && !UserHelper::isCoursesTeacher() ){
                LocalRedirect('/personal/');
            }

            $arVariables = [];
            $arUrlTemplates = CComponentEngine::MakeComponentUrlTemplates(
                static::$arDefaultUrlTemplates404,
                $this->arParams["SEF_URL_TEMPLATES"]
            );
            $obComponentEngine = new CComponentEngine($this);
            $componentPage = $obComponentEngine->guessComponentPath(
                $this->arParams["SEF_FOLDER"],
                $arUrlTemplates,
                $arVariables
            );
            $this->arParams["VARIABLES"] = $arVariables;

            $this->includeComponentTemplate($componentPage);
        } catch (Exception $e) {
            ShowError($e->getMessage());
        }
    }
}

<?php

use Bitrix\Main\Loader,
    \Pwd\Entity\LinksPagesTable,
    \Bitrix\Main\Localization\Loc;

class PagesNewsComponent extends CBitrixComponent
{
    //Проверяем наличие записи и при наличии достаем данные
    private function getResult(){
        global $APPLICATION;
        $sCurURL = $APPLICATION->GetCurPage(false);

        $arResult = LinksPagesTable::getList([
            'select' => [
                'CODE',
                'THEME',
            ],
            'filter' => [
                '=CODE' => $sCurURL
            ],
            'runtime'     => [
                'THEME'       => array(
                    'data_type'  => 'string',
                    'expression' => array(
                        'GROUP_CONCAT(%s)',
                        'PROPERTY_MULTIPLE_LINKS.VALUE'
                    )
                )
            ]
        ])->fetch();

        if(!$arResult || empty($arResult['THEME'])){
            return;
        }

        $this->arResult['THEME'] = explode(',', $arResult['THEME']);

    }
    //Выполняем компонент
    public function executeComponent()
    {
        try {
            $this->getResult();
            $this->includeComponentTemplate();
        } catch (Exception $e) {
            ShowError($e->getMessage());
        }
    }
}

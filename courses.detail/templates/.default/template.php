<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
?>

<div class="events-header">
    <h1><strong><?=$arResult['COURSE']['NAME'];?></strong></h1>
</div>

<div class="js-courses">

    <div class="detail-text"><?=$arResult['COURSE']['DETAIL_TEXT'];?></div>


    <div class="row" id="events-list">
        <?if (!empty($arResult['COURSE']['FILES'])):?>
            <?foreach ($arResult['COURSE']['FILES'] as $arFile):?>
                <div class="col s12 events-item" data-id="<?=$arFile['ID']?>" data-element="<?=$arParams['ELEMENT_ID'];?>">
                    <ul class="collapsible collapsible-style">
                        <li>
                            <div class="collapsible-header">
                                <h4><?=$arFile['ORIGINAL_NAME']?></h4>
                            </div>
                            <div class="collapsible-body">

                            </div>
                        </li>
                    </ul>
                </div>
            <?endforeach;?>
        <?endif;?>
    </div>

</div>

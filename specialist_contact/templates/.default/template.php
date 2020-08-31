<?php
	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<hr />
<?if(!empty($arResult["phone"])):?>
	<p>
        <a href="<?=$arResult["phoneLink"]?>" class="link-icon"><i class="fas fa-phone"></i><?=$arResult["phone"]?></a>
        <?if(!empty($arParams["specialistContacDopPhone"])):?>
            (доб. <?=$arParams["specialistContacDopPhone"]?>)
        <?endif;?>
    </p>
<?endif;?>
<?if(!empty($arResult["email"])):?>
	<p><a href="mailto:<?=$arResult["email"]?>" class="link-icon"><i class="fas fa-envelope"></i><?=$arResult["email"]?></a></p>
<?endif;?>
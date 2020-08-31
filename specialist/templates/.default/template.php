<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<div class="expert">
	<a href="<?= $arResult["URL"] ?>">
		<img src="<?= $arResult["PHOTO"] ?>" alt="<?= $arResult["NAME"] ?>"
			 class="responsive-img z-depth-1 mb-4"/>
	</a>
	<div class="mb-3 readable">
		<p class="h4">
			<a href="<?= $arResult["URL"] ?>"><b><?= $arResult["NAME"] ?></b></a>
		</p>
		<? if (strlen($arResult["STAFF"]) > 0) { ?>
			<p>
				<?= $arResult["STAFF"] ?>
			</p>
		<? } ?>
		<? if (strlen($arResult["TEL"]) > 0) { ?>
			<p>
				<a href="tel:<?= $arResult["TEL"] ?>" class="link-icon"><i
						class="fas fa-phone"></i><?= $arResult["TEL"] ?></a>
			</p>
		<? } ?>
		<? if (strlen($arResult["MAIL"]) > 0) { ?>
			<p>
				<a href="mailto:<?= $arResult["MAIL"] ?>" class="link-icon"><i
						class="fas fa-envelope"></i><?= $arResult["MAIL"] ?></a>
			</p>
		<? } ?>
	</div>
</div>

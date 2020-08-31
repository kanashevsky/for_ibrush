<?php
	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<?if($arResult["type"] == 'list'):?>
	<?if($arResult["count"]["N"] != 0 || !empty($arResult["count"]["N"])):?>
		<ul class="collapsible init-collapse angle-collapse mb-3">
			<li class="">
				<div class="collapsible-header" tabindex="0">
					<h4><strong><?=GetMessage("staff_neprin_doc".($arParams['TRAINEESHIP'] != 'Y' ? '' : '_traineeship'))?> (<?=$arResult["count"]["N"]?>)</strong></h4>
				</div>
				<div class="collapsible-body" style="">
					<table border="0">
						<?foreach ($arResult["N"] as $arItem):?>
							<tr>
								<td width="25%"><a href="?USER_SD=<?=$arItem["user"]["ID"]?>"><?=$arItem["user"]["LAST_NAME"]?> <?=$arItem["user"]["NAME"]?> <?=$arItem["user"]["SECOND_NAME"]?></a></td>
								<td width="25%"><?=$arItem["user"]["WORK_POSITION"]?></td>
								<td width="25%">
									<?=$arItem["user"]["WORK_PHONE"]?>
								</td>
								<td>
									<a href="?USER_SD=<?=$arItem["user"]["ID"]?>"><?=$arItem["user"]["EMAIL"]?></a>
								</td>
							</tr>
						<?endforeach;?>
					</table>
				</div>
			</li>
		</ul>
	<?endif;?>
	<?if($arResult["count"]["Y"] != 0 || !empty($arResult["count"]["Y"])):?>
		<ul class="collapsible init-collapse angle-collapse mb-3">
			<li class="">
				<div class="collapsible-header" tabindex="0">
					<h4><strong><?=GetMessage("staff_prin_doc".($arParams['TRAINEESHIP'] != 'Y' ? '' : '_traineeship'))?> (<?=$arResult["count"]["Y"]?>)</strong></h4>
				</div>
				<div class="collapsible-body" style="">
					<table border="0">
						<?foreach ($arResult["Y"] as $arItem):?>
							<tr>
								<td width="25%"><a href="?USER_SD=<?=$arItem["user"]["ID"]?>"><?=$arItem["user"]["LAST_NAME"]?> <?=$arItem["user"]["NAME"]?> <?=$arItem["user"]["SECOND_NAME"]?></a></td>
								<td width="25%"><?=$arItem["user"]["WORK_POSITION"]?></td>
								<td width="25%">
									<?=$arItem["user"]["WORK_PHONE"]?>
								</td>
								<td>
									<a href="?USER_SD=<?=$arItem["user"]["ID"]?>"><?=$arItem["user"]["EMAIL"]?></a>
								</td>
							</tr>
						<?endforeach;?>
					</table>
				</div>
			</li>
		</ul>
	<?endif;?>
<?endif;?>
<?if($arResult["type"] == 'user'):?>
	<p><a href="<?=$_SERVER["PHP_SELF"]?>"><?=GetMessage("staff_return_link")?></a></p>
	<p>
		<?=GetMessage("staff_LAST_NAME")?><i><?=$arResult["user"]["LAST_NAME"]?></i><br />
		<?=GetMessage("staff_NAME")?><i><?=$arResult["user"]["NAME"]?></i><br />
		<?=GetMessage("staff_WORK_POSITION")?><i><?=$arResult["user"]["WORK_POSITION"]?></i><br />
		<?=GetMessage("staff_EMAIL")?><i><?=$arResult["user"]["EMAIL"]?></i><br />
		<?=GetMessage("staff_PHONE")?><i><?=$arResult["user"]["WORK_PHONE"]?></i>
	</p>
	<hr />
	<?if(!empty($arResult["doc"]["N"])):?>
		<h3><?=GetMessage("staff_neprin_doc")?></h3>
		<table border="0">
			<?foreach ($arResult["doc"]["N"] as $doc):?>
				<tr>
					<td>
						<?
							$exp = explode(" ", $doc["DATE_CREATE"]);
							echo $exp[0];
						?>
					</td>
					<td>
						<a href="<?=CFile::GetPath($doc["PROPERTY_DOCUMENT_VALUE"])?>"><?=$doc["NAME"]?></a>
					</td>
					<td>
						<a href="?USER_SD=<?=$arResult["user"]["ID"]?>&doc=<?=$doc["ID"]?>" class="btn btn-primary"><?=GetMessage("staff_APPLY")?></a>
					</td>
				</tr>
			<?endforeach;?>
		</table>
	<?endif;?>
	<?if(!empty($arResult["doc"]["Y"])):?>
		<h3><?=GetMessage("staff_prin_doc")?></h3>
		<table border="0">
			<?foreach ($arResult["doc"]["Y"] as $doc):?>
				<tr>
					<td>
						<?
							$exp = explode(" ", $doc["DATE_CREATE"]);
							echo $exp[0];
						?>
					</td>
					<td>
						<a href="<?=CFile::GetPath($doc["PROPERTY_DOCUMENT_VALUE"])?>"><?=$doc["NAME"]?></a>
					</td>
				</tr>
			<?endforeach;?>
		</table>
	<?endif;?>
	<p><a href="<?=$_SERVER["PHP_SELF"]?>"><?=GetMessage("staff_return_link")?></a></p>
<?endif;?>
<?if(empty($arResult)):?>
    <p style="color: red"><?=GetMessage("staff_NODOC")?></p>
<?endif;?>

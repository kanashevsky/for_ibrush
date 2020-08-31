<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc,
    \Pwd\Helpers\UserHelper;

?>
    <div class="events-header">
        <h1><strong><?= Loc::getMessage('COURSE'); ?></strong></h1>
    </div>

    <div class="row">
        <? if (!empty($arResult['ITEMS'])): ?>
            <? foreach ($arResult['ITEMS'] as $iCourseID => $arCourse):
                ?>
                <div class="col s12 events-item">
                    <ul class="collapsible collapsible-style">
                        <li>
                            <a href="<?= $arCourse['DETAIL_PAGE_URL']; ?>">
                                <div class="collapsible-header">
                                    <h4><?= $arCourse['NAME'] ?></h4>
                                    <?= $arCourse['TEXT_PREVIEW']; ?>
                                </div>
                                <? if (UserHelper::isCoursesTeacher()) {
                                    ?>
                                    <div>
                                        <table>
                                            <tr>
                                                <th>Вопросов без ответа</th>
                                                <th>Вопросов неопубликованных</th>
                                                <th>Вопросов опубликованных</th>
                                                <th>Вопросов с ответом</th>
                                                <th>Всего вопросов</th>
                                            </tr>
                                            <tr>
                                                <td><?= $arCourse['QUESTIONS']['NOT_ANSWER'] ?></td>
                                                <td><?= $arCourse['QUESTIONS']['NOT_PUBLIC'] ?></td>
                                                <td><?= $arCourse['QUESTIONS']['PUBLIC'] ?></td>
                                                <td><?= $arCourse['QUESTIONS']['WITH_ANSWER'] ?></td>
                                                <td><?= $arCourse['QUESTIONS']['ALL'] ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                <? } ?>
                            </a>
                        </li>
                    </ul>
                    <? if (UserHelper::isAdmin()): ?>
                        <div class="right-align">
                            <a target="_blank" href="?download=<?= $iCourseID; ?>"
                               class=" waves-effect waves-light btn  btn-small"><?= Loc::getMessage("DOWNLOAD") ?></a>
                        </div>
                    <? endif; ?>
                </div>
            <? endforeach; ?>
        <? endif; ?>

    </div>
<?
$APPLICATION->IncludeComponent(
    "bitrix:main.pagenavigation",
    "nii",
    array(
        "NAV_OBJECT" => $arResult['NAV'],
        "SEF_MODE" => "N",
    ),
    false
);
?>
<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?
if (!$this->__component->__parent || empty($this->__component->__parent->__name)):
	$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/components/bitrix/forum/templates/.default/style.css');
	$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/components/bitrix/forum/templates/.default/themes/blue/style.css');
	$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/components/bitrix/forum/templates/.default/styles/additional.css');
endif;
$arSort = array(
	"NUM_POSTS" => array("NAME" => GetMessage("LU_FILTER_SORT_NUM_POSTS")), 
	"SHOW_ABC" => array("NAME" => GetMessage("LU_FILTER_SORT_NAME")), 
);
$arSort["DATE_REGISTER"] = array("NAME" => GetMessage("LU_FILTER_SORT_DATE_REGISTER"));
$arSort["LAST_VISIT"] = array("NAME" => GetMessage("LU_FILTER_SORT_LAST_VISIT"));
$arFields = array(
	array(
		"NAME" => "PAGE_NAME",
		"TYPE" => "HIDDEN",
		"VALUE" => "user_list"),
	array(
		"TITLE" => GetMessage("LU_FILTER_USER_NAME"),
		"NAME" => "user_name",
		"TYPE" => "TEXT",
		"VALUE" => $_REQUEST["user_name"]),
	array(
		"TITLE" => GetMessage("LU_FILTER_LAST_VISIT"),
		"NAME" => "date_last_visit1",
		"NAME_TO" => "date_last_visit2",
		"TYPE" => "PERIOD",
		"VALUE" => $_REQUEST["date_last_visit1"],
		"VALUE_TO" => $_REQUEST["date_last_visit2"]), 
	array(
		"TITLE" => GetMessage("LU_FILTER_AVATAR"),
		"NAME" => "avatar",
		"TYPE" => "CHECKBOX",
		"VALUE" => "Y", 
		"ACTIVE" => $_REQUEST["avatar"], 
		"LABEL" => GetMessage("LU_FILTER_AVATAR_TITLE")));

$arFields[] = array(
		"TITLE" => GetMessage("LU_FILTER_SORT"),
		"NAME" => "sort",
		"TYPE" => "SELECT",
		"VALUE" => $arSort,
		"ACTIVE" => $_REQUEST["sort"]);
?>

<br/>
<?
if (!empty($arResult["ERROR_MESSAGE"])):
?>
<div class="forum-note-box forum-note-error">
	<div class="forum-note-box-text"><?=ShowError($arResult["ERROR_MESSAGE"], "forum-note-error");?></div>
</div>
<?
endif;
?>
<div class="forum-header-box">
	<div class="forum-header-title"><span><?=GetMessage("LU_TITLE_USER")?></span></div>
</div>
<div class="forum-block-container">
	<div class="forum-block-outer">
		<div class="mydiv">
			<a class="mylink"  href="?export=csv">Выгрузить всех пользователией csv</a>
			<a class="mylink"  href="?export=xml">Выгрузить всех пользователией xml</a>
		</div>
		<div class="forum-block-inner">
			<table cellspacing="0" class="forum-table forum-users">
			<thead>
				<tr>
					<th class="forum-first-column forum-column-username"><span><?=GetMessage("ID")?></span></th>
					<th class="forum-column"><span><?=GetMessage("LOGIN")?></span></th>
					<th class="forum-column"><span><?=GetMessage("NAME")?></span></th>
					<th class="forum-last-column"><span><?=GetMessage("EMAIL")?></span></th>
				</tr>
			</thead>
			<tbody id="body">
				<?if ($_REQUEST['AJAX_MODE'] == 'Y'): ?>
				    <?$APPLICATION->RestartBuffer(); ob_start();?>
				<?endif?>
<?
$iCount = 0;
foreach ($arResult["USERS"] as $res):
	$iCount++;
?>
				<tr class="<?=($iCount == 1 ? "forum-row-first " : ($iCount == count($arResult["USERS"]) ? "forum-row-last " : ""))?><?=($iCount%2 == 1 ? "forum-row-odd" : "forum-row-even")?>">
					<td class="forum-first-column forum-column-username"><div class="forum-user-name"><?=$res['ID']?></div></td>
					<td class="forum-column"><?=$res['LOGIN']?></td>
					<td class="forum-column"><?=$res['NAME']."  ".$res['LAST_NAME']?></td>
					<td class="forum-last-column"><?=$res['EMAIL']?></td>

					</td>
				</tr>
<?
endforeach;
?>
<?if ($_REQUEST['AJAX_MODE'] == 'Y' ) : ?>
    <?
    $CONTENT = ob_get_contents();
    ob_end_clean();
    echo json_encode(
        array(
            'CONTENT' => $CONTENT,
            'NAVIGATE' => $arResult["NAV_STRING"],
        )
    ); die();?>
<?endif;?>
				</tbody>
				
			</table>
		</div>
	</div>
</div>
<?
if ($arResult["NAV_RESULT"]->NavPageCount > 0):
?>
<div class="forum-navigation-box forum-navigation-bottom">
	<div class="forum-page-navigation" id="navigation">
		<?=$arResult["NAV_STRING"]?>
	</div>
	<div class="forum-clear-float"></div>
</div>
<?
endif;
?>
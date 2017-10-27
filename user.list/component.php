<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/********************************************************************
				Input params
********************************************************************/
/***************** BASE ********************************************/
	$arParams["SHOW_USER_STATUS"] = ($arParams["SHOW_USER_STATUS"] == "Y" ? "Y" : "N");
/***************** Sorting *****************************************/
	InitSorting($GLOBALS["APPLICATION"]->GetCurPage()."?PAGE_NAME=user_list");
	global $by, $order;
/***************** ADDITIONAL **************************************/
	// Page elements
	$arParams["USERS_PER_PAGE"] = (intVal($arParams["USERS_PER_PAGE"]) > 0 ? intVal($arParams["USERS_PER_PAGE"]) : 20);
	// Data and data-time format
	$arParams["DATE_FORMAT"] = trim(empty($arParams["DATE_FORMAT"]) ? $DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")) : $arParams["DATE_FORMAT"]);
	$arParams["DATE_TIME_FORMAT"] = trim(empty($arParams["DATE_TIME_FORMAT"]) ? $DB->DateFormatToPHP(CSite::GetDateFormat("FULL")) : $arParams["DATE_TIME_FORMAT"]);
	$arParams["NAME_TEMPLATE"] = (!empty($arParams["NAME_TEMPLATE"]) ? $arParams["NAME_TEMPLATE"] : false);
	$arParams["PAGE_NAVIGATION_TEMPLATE"] = trim($arParams["PAGE_NAVIGATION_TEMPLATE"]);
	$arParams["WORD_LENGTH"] = intVal($arParams["WORD_LENGTH"]);
/***************** STANDART ****************************************/
	$arParams["SET_TITLE"] = ($arParams["SET_TITLE"] == "N" ? "N" : "Y");
	$arParams["SET_NAVIGATION"] = ($arParams["SET_NAVIGATION"] == "N" ? "N" : "Y");

/********************************************************************
				/Input params
********************************************************************/
	$arParams["PAGER_TEMPLATE"] = "down_paginig-review" ; 
/********************************************************************
				Default params
********************************************************************/
$arResult["SHOW_RESULT"] = "N";
$arResult["SHOW_MAIL"] = "Y";
$arResult["USERS"] = array();
/********************************************************************
				/Default params
********************************************************************/

/******************************************************************/
$arResult["ERROR_MESSAGE"] = $strError;
CPageOption::SetOptionString("main", "nav_page_in_session", "N");
$order = array('sort' => 'asc');
$tmp = 'sort'; // параметр проигнорируется методом, но обязан быть
$arParams["NAV_PARAMS"] = array("bDescPageNumbering" => false,
								"nPageSize"=>$arParams["USERS_PER_PAGE"],
								"bShowAll" => false,
								"sNameTemplate" => $arParams["NAME_TEMPLATE"]);
$db_res = CUser::getList($order, $tmp, array(), $arParams); 
$arParams["SHOW_USER_STATUS"] = "Y";
$arResult['FIELDS'] = [
    'ID', 'LOGIN', 'NAME', 'LAST_NAME', 'EMAIL',
];
if($db_res)
{
	$db_res->NavStart($arParams["USERS_PER_PAGE"], false);
	$arResult["NAV_STRING"] = $db_res->GetPageNavStringEx($navComponentObject, GetMessage("LU_TITLE_USER"), $arParams["PAGE_NAVIGATION_TEMPLATE"]);
	$arResult["NAV_RESULT"] = $db_res;
	$arResult["SHOW_RESULT"] = "Y";
	$arResult["SortingEx"]["SHOW_ABC"] = SortingEx("SHOW_ABC", $APPLICATION->GetCurPageParam());
	$arResult["SortingEx"]["NUM_POSTS"] = SortingEx("NUM_POSTS", $APPLICATION->GetCurPageParam());
	$arResult["SortingEx"]["POINTS"] = SortingEx("POINTS", $APPLICATION->GetCurPageParam());
	$arResult["SortingEx"]["DATE_REGISTER"] = SortingEx("DATE_REGISTER", $APPLICATION->GetCurPageParam());
	$arResult["SortingEx"]["LAST_VISIT"] = SortingEx("LAST_VISIT", $APPLICATION->GetCurPageParam());

	if ($res = $db_res->GetNext())
	{
		do
		{
			$arResult["USERS"][] = $res;
		}while($res = $db_res->GetNext());
	}
}
$arResult['TOTAL'] = $db_res->result->num_rows;
/********************************************************************
				/Data
********************************************************************/
/********************************************************************
				EXPOTING
********************************************************************/
if ($_GET['export']){
	$export = new Export($APPLICATION);
	$export->export($arResult,$_GET['export'],',');
}
$this->IncludeComponentTemplate();
if ($arParams["SET_NAVIGATION"] != "N")
	$APPLICATION->AddChainItem(GetMessage("LU_TITLE_USER"));
if ($arParams["SET_TITLE"] != "N")
	$APPLICATION->SetTitle(GetMessage("LU_TITLE_USER"));
/******************************************************************/
?>
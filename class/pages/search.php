<?php

require_once DATA_PATH."class/amazon.php";
require_once DATA_PATH."class/util.php";
require_once DATA_PATH."class/view.php";

require_once DATA_PATH."class/pages/page.php";

/*
	ASIN収集ページのクラス
*/
class SearchPage extends Page {

	/* 
   * プロセス
   * 
	*/
	function process() {

		$view = new SiteView();

		if( $_POST["mode"] == "output" ){

			$keyword = $_POST["keyword"];
			$countury = $_POST["countury"];

			//! クラス宣言
			$amazonClass = new AmazonClass();
			$utilClass	= new AmazonUtil();

			//! 検索してasinを収集する。
			$asinArray = $amazonClass->search( $keyword , $countury );

			//! データベースに格納する。
			$items_value = $amazonClass->getItemStatus( $asinArray , $countury );

			$view->assign("items",$items_value);
			$view->assign("countury",$countury);
			$view->assign("countury_name",$utilClass->getCounturyName($countury));
			$view->assign("asinArray",implode(",",$asinArray));
			$view->assign("currencyUnitStr",$utilClass->getCurrencyStr($countury)); 
			$view->assign("title","アマゾンASIN収集(結果)");

			$view->assign("tpl","search/output.tpl");

		}else{

			$view->assign("tpl","search/index.tpl");
			$view->assign("title","アマゾンASIN収集");
		}

		$view->display('frame.tpl');
	}
}




?>
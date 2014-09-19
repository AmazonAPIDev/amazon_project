<?php

require_once DATA_PATH."class/amazon.php";
require_once DATA_PATH."class/pages/page.php";
require_once DATA_PATH."class/view.php";

//! ソートのための関数
//! 在庫数で昇順ソート
function sort_by_totalnew( $a , $b )
{
	return $a['count'] > $b['count'];
}

/*
	在庫チェックページのクラス
*/
class StockPage extends Page {


	/* 
   * プロセス
   * 
	*/
	function process() {

		$view = new SiteView();

		if( $_POST["mode"] == "output" ){

			//! POSTで投げられた値を取得する。
			$asinStr = $_POST["asin"];

			//! カンマ区切りを配列に変換する。
			$asinArray = explode(',',$asinStr);

			//! クラス宣言
			$amazonClass = new AmazonClass();

			if( count($asinArray) != 0 ){
				$items = $amazonClass->getItemStatus( $asinArray , "jp" );
				usort( $items , sort_by_totalnew );
			}

			$view->assign("items",$items);
			$view->assign("tpl","stock/output.tpl");
			$view->assign("title","アマゾンジャパン在庫チェック(結果)");

		}else{

			//! クッキーを読み込み
			$asinStr = $_COOKIE["asin"];

			//! POSTで投げられた値があれば設定する。
			if( isset( $_POST["asin"] ) ){
				$asinStr = $_POST["asin"];
			}

			$view->assign("asinStr",$asinStr);
			$view->assign("tpl","stock/index.tpl");
			$view->assign("title","アマゾンジャパン在庫チェック");
		}

		$view->display('frame.tpl');
	}
}




?>
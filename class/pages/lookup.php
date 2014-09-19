<?php

require_once DATA_PATH."class/amazon.php";
require_once DATA_PATH."class/util.php";
require_once DATA_PATH."class/view.php";

require_once DATA_PATH."class/pages/page.php";

//! ソート関数
//! 価格差で降順ソート
function sort_by_price( $a , $b )
{
	return ( $a["diff"] > $b["diff"] ) ? -1 : 1;
}

/*
	価格差チェックページのクラス
*/
class LookupPage extends Page {

	/* 
   * プロセス
   * 
	*/	
	function process() {

		$view = new SiteView();

		if( $_POST["mode"] == "output" ){

			//! POSTで投げられた値を取得する。
			$rate = $_POST["rate"];
			$keyword = $_POST["keyword"];
			$countury = $_POST["countury"];
			$asinStr = $_POST["asin"];

			//! クラス宣言
			$amazonClass = new AmazonClass();
			$utilClass	= new AmazonUtil();

			//$items = $amazonClass->search( $keyword , $countury );

			//! カンマ区切りを配列に変換する。
			$asinArray = explode(',',$asinStr);

			//! データベースから情報を抽出する。
			$items_value = $this->getItems( $amazonClass , $asinArray , $countury , $rate );

			usort( $items_value , "sort_by_price" );

			$view->assign("rate",$rate);
			$view->assign("items",$items_value);
			$view->assign("countury",$countury);
			$view->assign("countury_name",$utilClass->getCounturyName($countury));
			$view->assign("currencyUnitStr",$utilClass->getCurrencyStr($countury)); 
			$view->assign("title","アマゾン価格差チェック(結果)");

			$view->assign("tpl","lookup/output.tpl");

		}else{

			//! クッキーを読み込み
			$asinStr = $_COOKIE["asin"];

			//! POSTで投げられた値があれば設定する。
			if( isset( $_POST["asin"] ) ){
				$asinStr = $_POST["asin"];
			}

			$view->assign("asinStr",$asinStr);
			$view->assign("tpl","lookup/index.tpl");
			$view->assign("title","アマゾン価格差チェック");

		}

		$view->display('frame.tpl');

	}

	/*
		ASINからアイテム情報を取得する。
		$amazonClass アマゾンクラスのインスタンス
		$asinArray   ASIN（複数）
		$countury		 国
　　　"japan","us","fr"
		$rate				 為替レート
	*/
	function getItems( $amazonClass, $asinArray , $countury , $rate )
	{
		$items_us = $amazonClass->getItemStatus( $asinArray , $countury );
		$items_jp = $amazonClass->getItemStatus( $asinArray , "jp" );

		//! 再度データベースから取ってくる。
		$size1 = count($items_us);
		$size2 = count($items_jp);

		for( $idx1 = 0 ; $idx1 < $size1 ; $idx1++ ){

			$value;

			$value["us"] = $items_us[$idx1];
			if( !isset( $value["us"] ) ) continue;

			$asin = $items_us[$idx1]["asin"];
			if( $asin == "" ) continue;

			//! アメリカの価格がゼロなら飛ばす。（在庫切れ)
			if( $value["us"]["price"] == 0 ) continue;

			for( $idx2 = 0 ; $idx2 < $size2 ; $idx2++ ){
				if( $asin == $items_jp[$idx2]["asin"] ){
					break;
				}
			}

			//! 最後まで見つからなければ、飛ばす。
			if( idx2 == $size2 ) continue;

			$value["ja"] = $items_jp[$idx2];
			if( !isset( $value["ja"] ) ) continue;

			//! 日本の価格がゼロなら飛ばす。（在庫切れ)
			if( $value["ja"]["price"] == 0 ) continue;

			$value["diff"] = $value['us']['price']/100*$rate - $value['ja']['price'];

			//! 価格差率が10%以上
			if( ( $value["diff"] * 100 / $value["ja"]["price"] )  > 10.0 &&
					( $value["us"]["rank"] < 30000 ) ){
				$value["mark"] = "1";
			}else{
				$value["mark"] = "0";
			}

			$ret[] = $value;
		}

		return $ret;
	}
}

?>
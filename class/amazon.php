<?php

include "mysql.php";

class AmazonClass
{
	// Access Key ID と Secret Access Key は必須です

	//! 一回で検索できるアイテム数(AmazonAPIの仕様に依存)
	private $_item_count = 25;

	//! 検索で使用可能な最大ページ数(AmazonAPIの仕様に依存)
	private $_page_max = 5;

	//! コンストラクタ
	function __construct(){

		//! データベースに接続
		MySqlConnect::makeConnect();
	}

	//! デストラクタ
	function __destruct(){

		MySqlConnect::disConnect();
	}

	// RFC3986 形式で URL エンコードする関数
	private function _urlencode_rfc3986($str)
	{
	    return str_replace('%7E', '~', rawurlencode($str));
	}

	//! シグネチャーを作成する。
	private function _remakeURI( $baseurl, $params )
	{
		// Timestamp パラメータを追加します
		// - 時間の表記は ISO8601 形式、タイムゾーンは UTC(GMT)
		$params['Timestamp'] = gmdate('Y-m-d\TH:i:s\Z');

		// パラメータの順序を昇順に並び替えます
		ksort($params);

		// canonical string を作成します
		$canonical_string = '';
		foreach ($params as $k => $v) {
		    $canonical_string .= '&'.$this->_urlencode_rfc3986($k).'='.$this->_urlencode_rfc3986($v);
		}
		$canonical_string = substr($canonical_string, 1);

		// 署名を作成します
		// - 規定の文字列フォーマットを作成
		// - HMAC-SHA256 を計算
		// - BASE64 エンコード
		$parsed_url = parse_url($baseurl);
		$string_to_sign = "GET\n{$parsed_url['host']}\n{$parsed_url['path']}\n{$canonical_string}";
		$signature = base64_encode(hash_hmac('sha256', $string_to_sign, SECRET_KEY , true));

		// URL を作成します
		// - リクエストの末尾に署名を追加
		$url = $baseurl.'?'.$canonical_string.'&Signature='.$this->_urlencode_rfc3986($signature);
	
		return $url;
	}

	/*
		指定した国のデータベースを取得する。
		$countury  国
　　　"jp","us","fr"
	*/
	private function _getDB( $countury )
	{
		if( $countury == "us" ){
			return "items_us";
		}else if( $countury == "fr" ){
			return "items_fr";
		}
		return "items_jp";
	}

	/*
		指定した国のアクセスURLを取得する。
		$countury  国
　　　"jp","us","fr"
	*/
	private function _getBaseUrl( $countury )
	{
		if( $countury == "us" ){
 			return 'http://ecs.amazonaws.com/onca/xml';
		}else if( $countury == "fr" ){
			return 'http://ecs.amazonaws.fr/onca/xml';
		}
		return 'http://ecs.amazonaws.jp/onca/xml';
	}

	/*
		キーワードで検索して商品情報を取得する。
		$keyword   検索キーワード
		$countury  国
　　　"jp","us","fr"
		$sort			 ソート種類
　　　"salesrank","price","-price","titlerank","-titlerank","release-date","-release-date"
	*/
	private function _searchBySort( $keyword , $countury , $sort )
	{
		$asinArray;

		//! アマゾンより指定キーワードのアイテムのリストを取得する。
		$itemList = $this->_reqItemSearch($keyword, $countury , 1 , $sort );
		$totalPages = $itemList['Items']['TotalPages'];
		$totalResults = $itemList['Items']['TotalResults'];

		//! 現状のAmazon APIの使用だと10ページしか取得できない。
		$totalPages > $this->_page_max ? $max = $this->_page_max : $max = $totalPages;

		for( $ee = 1 ; $ee <= $max ; $ee++ ){

			if( $ee != 1 ){
				//! アマゾンより指定キーワードのアイテムのリストを取得する。
				$itemList = $this->_reqItemSearch($keyword, $countury , $ee , $sort );
			}

			//! まずはデフォルトのアイテム個数
			$count = $this->_item_count;

			if( $totalPages == $ee ){
				$count = $totalResults % $this->_item_count;
			}

			if( $count == 1 ){
				$asinArray[] = $itemList['Items']['Item']["ASIN"];

				//! データベースに格納
				$this->_insertItem( $itemList['Items']['Item'] , $countury );
			}else{
				for( $ii = 0 ; $ii < $count ; $ii++ ){
					$asinArray[] = $itemList['Items']['Item'][$ii]["ASIN"];

					//! データベースに格納
					$this->_insertItem( $itemList['Items']['Item'][$ii] , $countury );
				}
			}
		}

		return $asinArray;
	}

	/*
		データベースに商品情報を格納する
		$value     商品情報
		$countury  国
　　　"jp","us","fr"
	*/
	private function _insertItem( $value , $countury )
	{
		if( !isset( $value['ASIN'] ) ) return;
		$sql = "DELETE FROM ".$this->_getDB($countury)." WHERE asin='".$value['ASIN']."'";
		$result_flag = mysql_query($sql);

		$sql = "INSERT INTO ".$this->_getDB($countury)." (id, asin, title, price, count, rank , weight) VALUES ( 'null','%s','%s','%d','%d','%d','%d')";
//		$sql = sprintf( $sql, $value['ASIN'], mysql_real_escape_string($value['ItemAttributes']['Title']), $value['OfferSummary']['LowestNewPrice']['Amount'] ,$value['OfferSummary']['TotalNew'] ,$value['SalesRank'],$value['ItemAttributes']['PackageDimensions']['Weight'] );
		$sql = sprintf( $sql, $value['ASIN'], mysql_real_escape_string($value['ItemAttributes']['Title']), $value['Offers']['Offer']['OfferListing']['Price']['Amount'] ,$value['OfferSummary']['TotalNew'] ,$value['SalesRank'],$value['ItemAttributes']['PackageDimensions']['Weight'] );
		$result_flag = mysql_query($sql);
		if (!$result_flag) {
		    die('INSERTクエリーが失敗しました。'.mysql_error());
		}
	}

	/*
		データベースに格納されている情報を取得する。
		$asin      ASIN(単数)
		$countury  国
　　　"jp","us","fr"
	*/
  private function _getItemInfo( $asin , $countury )
  {
		$sqlQuery = 'SELECT * FROM '.$this->_getDB($countury).' where asin="'.$asin.'"';

		$result = mysql_query($sqlQuery);
		while ($row = mysql_fetch_assoc($result)) {
				return $row;
		}
  }

	/*
		データベースにアクセスしてリクエスト可能か調べる。
	  Return
		　true 	リクエスト可能
			false リクエスト不可能
	*/
	private function _isAccessUrlSub()
	{
		$date = date("Y-m-d H:i:s",time()-1);

		//! データベースに問い合わせる。
		$sqlQuery = "SELECT time from time_table where time >='".$date."'";
		$result = mysql_query($sqlQuery);

		//! 1秒以内に登録された項目がなければアクセス可能
		if( mysql_num_rows($result) == 0 ){
			return true;
		}

		return false;
	}

	/*
		データベースにアクセスしてリクエスト可能な状態まで待つ。
	*/
	private function _isAccessUrl()
	{
		for( ;; ){
			if( $this->_isAccessUrlSub() ){
				break;
			}
			sleep(1);
		}
		//! データベースに現在の時刻を登録する。
		$sqlQuery = "INSERT INTO time_table (time) values (CURRENT_TIMESTAMP)"; 
		$result = mysql_query($sqlQuery);

	}

	/*
		検索してリクエストを返す
		$keyword	 検索キーワード
		$countury  国
　　　"jp","us","fr"
		$page			 ページ番号
		$sort			 ソート種類
　　　"salesrank","price","-price","titlerank","-titlerank","release-date","-release-date"
	  Return
		　商品情報の配列
	*/
	private function _reqItemSearch( $keyword , $countury , $page , $sort )
	{
		//echo "req<br>";

		// 基本的なリクエストを作成します
		// - この部分は今まで通り
		$baseurl = $this->_getBaseUrl( $countury );

		$params = array();
		$params['Service']        = 'AWSECommerceService';
		$params['AWSAccessKeyId'] =  ACCESS_KEY;
		$params['Operation']      = 'ItemSearch'; // ← ItemSearch オペレーションの例
		//$params['ResponseGroup']  = 'Small';
		$params['SearchIndex']    = 'Toys';
		$params['Keywords']       = $keyword;     // ← 文字コードは UTF-8
		$params['Condition']			= 'New';
		$params['Count']					= $this->_item_count;
		$params['Sort']						= $sort;
    //$params['MinimumPrice']		= '15';
    //$params['MaximumPrice']		= '300';
		$params['ResponseGroup']  = 'ItemAttributes,SalesRank,Images,OfferFull';
		//$params['Operation']			= 'ItemLookup';
		//$params['ItemId'] 				=  $assinArray;
		$params['ItemPage']				= $page;
		$params['ContentType']    = 'text/xml';
		$params['AssociateTag']   = 'libary01-22';

		$url = $this->_remakeURI($baseurl,$params);

		//echo $url."<br>";

		$this->_isAccessUrl();
		$xmlContents = file_get_contents($url);

		if( $xmlCountents === FALSE ){
			return;
		}

		$xml = new SimpleXMLElement($xmlContents);
		$obj =  $xml->children("http://webservices.amazon.com/AWSECommerceService/2011-08-01");

		return json_decode(json_encode($obj), true);
	}

	/*
		検索してリクエストを返す
		$asin			 ASIN(複数) 最大10個
		$countury  国
　　　"jp","us","fr"
	  Return
		　商品情報の配列
	*/
	private function _getItemStatusSub( $assinArray , $countury )
	{
		//echo "status<br>";

		// 基本的なリクエストを作成します
		// - この部分は今まで通り
		$baseurl = $this->_getBaseUrl( $countury );

		$params = array();
		$params['Service']        = 'AWSECommerceService';
		$params['AWSAccessKeyId'] = ACCESS_KEY;
		//$params['Operation']      = 'ItemSearch'; // ← ItemSearch オペレーションの例
		//$params['ResponseGroup']  = 'Small';
		//$params['SearchIndex']    = 'Books';
		//$params['Keywords']       = 'Java';     // ← 文字コードは UTF-8

		$params['ResponseGroup']  = 'ItemAttributes,SalesRank,Images,OfferFull';
		$params['Operation']			= 'ItemLookup';
		$params['ItemId'] 				=  $assinArray;
		$params['ContentType']    = 'text/xml';
		$params['AssociateTag']   = 'libary01-22';

		$url = $this->_remakeURI($baseurl,$params);

		//echo $url."<br>";

		$this->_isAccessUrl();
		$xmlContents = file_get_contents($url);

		if( $xmlCountents === FALSE ){
			return;
		}

		$xml = new SimpleXMLElement($xmlContents);
		$obj =  $xml->children("http://webservices.amazon.com/AWSECommerceService/2011-08-01");

		return json_decode(json_encode($obj), true);
	}

	/*
		キーワードで検索して商品情報を取得する。
		$keyword   検索キーワード
		$countury  国
　　　"jp","us","fr"
		$sort			 ソート種類
　　　"salesrank","price","-price","titlerank","-titlerank","release-date","-release-date"
	*/
	public function search( $keyword , $countury )
	{
		$items = $this->_searchBySort( $keyword , $countury , "salesrank" );

/*
		$search_items = $this->_searchBySort( $keyword , $countury , "price" );
		if( count($search_items) > 0 ){
			$items = array_merge( $items , $search_items );
		}

		$search_items = $this->_searchBySort( $keyword , $countury , "-price" );
		if( count($seach_items) > 0 ){
			$items = array_merge( $items , $search_items );
		}
	
		$search_items = $this->_searchBySort( $keyword , $countury , "titlerank" );
		if( count($search_items) > 0 ){
			$items = array_merge( $items , $search_items );
		}

		$search_items = $this->_searchBySort( $keyword , $countury , "-titlerank" );
		if( count($search_items) > 0 ){
			$items = array_merge( $items , $search_items );
		}

		$search_items = $this->_searchBySort( $keyword , $countury , "release-date" );
		if( count($search_items) > 0 ){
			$items = array_merge( $items , $search_items );
		}

		$search_items = $this->_searchBySort( $keyword , $countury , "-release-date" );
		if( count($search_items) > 0 ){
			$items = array_merge( $items , $search_items );
		}
*/

		return $items;
	}

	/*
		指定したASINの情報を取得する。
		DBに格納されていない場合は、AWSでリクエストしてDBに格納してから取得する。
		$asin			 ASIN(複数)
		$countury  国
　　　"jp","us","fr"
		Return
			DBから取得した商品情報
	*/
	public function getItemStatus( $asinArray , $countury )
	{
		$size = count($asinArray);
		$lookUpItems = array();
		for( $idx = 0 ; $idx < $size ; $idx++ ){

				$sqlQuery = 'SELECT * FROM '.$this->_getDB($countury).' where asin="'.$asinArray[$idx].'"';

				$result = mysql_query($sqlQuery);

				if( mysql_num_rows($result) == 0 ){
					$lookUpItems[] = $asinArray[$idx];
				}else{
					//! データベースに在っても情報が古い場合は捨てる。
					while ($row = mysql_fetch_assoc($result)) {
						if( time() - strtotime($row["time"]) > 60*60*24 ){
							$sqlQuery = 'DELETE FROM '.$this->_getDB($countury).' where asin="'.$asinArray[$idx].'"';
							$result2 = mysql_query($sqlQuery);
							$lookUpItems[] = $asinArray[$idx];
						}
					}
				}

				//! 10個の区切りか最後の時は、リクエストに行く
				if( ( count($lookUpItems ) != 10 ) && ( $idx != $size-1 ) ){
					continue;
				}

				//! 0個じゃない場合は、
				if( count($lookUpItems) != 0 ){

					$asinString = implode(",",array_slice($lookUpItems,0));

					$sample = $this->_getItemStatusSub($asinString,$countury);

					$size1 = count($lookUpItems);

					if( $size1 == 1 ){
							$value = $sample['Items']['Item'];
							$this->_insertItem( $value , $countury );
					}else{
						//! データベースに格納する。
						for( $ee = 0 ; $ee < $size1 ; $ee++ ){
							$value = $sample['Items']['Item'][$ee];
							$this->_insertItem( $value , $countury );
						}
					}
					
					//! 配列を初期化
					$lookUpItems = array();					
				}
		}

		$size = count($asinArray);
		for( $idx = 0 ; $idx < $size ; $idx++ ){
			$value = $this->_getItemInfo( $asinArray[$idx] , $countury );
			if( isset( $value ) ){
				$items_value[] = $value;
			}
		}

		return $items_value;
	}

}

?>
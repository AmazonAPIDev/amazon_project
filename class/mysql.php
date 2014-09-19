<?php

	require_once "setting.php";

	/*
		MySqlの接続クラス
	*/
	class MySqlConnect
	{
		//! 接続
		var $link;

		//! コネクション
		function makeConnect(){

			if( !$this->link ){

					$link = mysql_connect(DB_SERVER,DB_USER,DB_PASSWORD);
					if (!$link) {
					    die('接続失敗です。'.mysql_error());
					}
					//print('<p>接続に成功しました。</p>');

					$db = mysql_select_db(DB_NAME,$link);
					if (!$db){
					    die('データベース選択失敗です。'.mysql_error());
					}

					$this->link = $link;

					mysql_set_charset('utf8');
			}

			return $this->link;
		}

		//! コンストラクタ
		function __construct(){

		}

		//! デストラクタ
		function __destruct(){
		}

		//! 接続を切る
		function disConnect(){
			$close_flag = mysql_close($this->link);
			if (!$close_flag){
			    print('<p>切断に失敗しました。</p>');
			}
		}
	}
?>
<?php

	/*
		ユーティリティ関数
	*/
	class AmazonUtil {

		function __construct(){
		}

		function __destruct(){
		}

		function getCounturyName( $countury ){
			if( $countury == "fr" ){
				return "フランス";
			}else if( $countury == "us" ){
				return "アメリカ";
			}
			return "日本";
		}

		function getCurrencyStr( $countury ){
			if( $countury == "fr" ){
				return "&euro;";
			}else if( $countury == "us" ){
				return "$";
			}
			return "&yen;";
		}
	}
?>
<?php

require_once DATA_PATH."class/amazon.php";
require_once DATA_PATH."class/pages/page.php";
require_once DATA_PATH."class/view.php";

/*
	トップページのクラス
*/
class indexPage extends Page {

	
	/* 
   * プロセス
   * 
	*/
	function process() {

		$view = new SiteView();
		$view->assign("tpl","index.tpl");
		$view->assign("title","アマゾン便利ツールサイト");

		$view->display('frame.tpl');
	}


}




?>
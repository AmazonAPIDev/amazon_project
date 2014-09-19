<?php

	include "require.php";
	require_once DATA_PATH."class/pages/stock.php";

	$page = new StockPage();
	$page->process();

?>

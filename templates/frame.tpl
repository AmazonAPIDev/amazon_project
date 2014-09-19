<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><!--{$title}--></title>
		<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

<!--{if $smarty.const.ANALYZE == "1"}-->
		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		  ga('create', 'UA-11961133-12', 'auto');
		  ga('send', 'pageview');

		</script>
<!--{/if}-->

  </head>
  <body>
		<div id="header" class="container">
			<div class="row">
	<h1><!--{$title}--></h1>
	本サイトは、amazonから提供されているAPIを使用して作成されています。<br />
<!--{if false}-->
	<p>本サイトは、amazonアソシエイトプログラムに参加予定しています。<br />
	本サイトの購入リンクから購入された場合は、サイト運営者田中に紹介料がアマゾンより支払われます。<br />
  ご了承の上、ご使用ください。<br />
<!--{/if}-->
  </p>
			</div>
		</div>

		<div class="container">
			<div class="row">

			<!--{include file=$tpl}-->
		
			</div>
		</div>

		<div id="footer" style="margin-bottom: 50px;" class="container" >
			<a href="<!--{$smarty.const.SITE_URL}-->">トップに戻る</a><br>
			Copyright (C) 2014 Yohei Tanaka. All Rights Reserved.<br>
			お問い合わせは、コチラからお願いします。<a href="mailto:info@wqwq.info">info@wqwq.info</a>
		</div>

    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
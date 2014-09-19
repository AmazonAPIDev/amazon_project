  <script type="text/javascript" src="js/jquery.cookie.js"></script>
	<script type="text/javascript">
	$(function(){
		$("#submit").click(function(){
			$.cookie("asin",$('#asin').val(),{ expires: 7 });
			location.href="index.php"
		})
	})
	</script>

  <p>本サイトは、amazon.co.jpの在庫数を調べるサイトです。<br />

	<h2>使い方</h2>
	<p>
	下のボックスにASINをカンマ区切りで記入してください。<br />
	次回再度入力する手間をなくすために、入力情報をクッキーに7日間保存しています。<br />
	</p>

<a href="<!--{$smarty.const.SITE_URL}-->">トップに戻る</a><br />
<a href="javascript:window.history.back();">前のページに戻る</a><br /><br />

	<form method="post" action="stock.php" class="form-horizontal">
		<input type="hidden" name="mode" value="output">
		<div class="form-group">
			<div class="col-xs-12">
				<textarea class="form-control" name="asin" id="asin" rows="8"><!--{$asinStr}--></textarea>
			</div>
		</div>
	  <div class="form-group">
	    <div class="col-sm-offset-1 col-sm-11">
	      <button type="submit" id="submit" class="btn btn-primary">送信</button>
	    </div>
	  </div>
	</form>

<!--
<textarea cols=100 rows=50 id="debug"></textarea>
-->

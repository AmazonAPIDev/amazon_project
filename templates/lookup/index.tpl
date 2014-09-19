  <script type="text/javascript" src="js/jquery.cookie.js"></script>
	<script type="text/javascript">
	$(function(){

		$('#rate').val($.cookie("rate"));

		$("#submit").click(function(){
			$.cookie("asin",$('#asin').val(),{ expires: 7 });
			$.cookie("rate",$('#rate').val(),{ expires: 7 });
			location.href="lookup.php"
		})
	})
	</script>

  <p>本サイトは、amazon.comからキーワードで検索しamazon.co.jpとの価格差を収集するサイトです。<br />

	<h2>使い方</h2>
	<p>
	価格比較対象国を選択してください<br />
	現地通貨の現在の為替レートを入力してください。<br />
	海外のアマゾンと比較する対象のASINをボックスに入力してください。<br />
	</p>

<a href="<!--{$smarty.const.SITE_URL}-->">トップに戻る</a><br />
<a href="javascript:window.history.back();">前のページに戻る</a><br /><br />

	<div class="row">

	<form method="post" action="lookup.php" class="form-horizontal">
    <input type="hidden" id="mode" name="mode" value="output" />
		<div class="form-group">
			<label class="control-label col-xs-2">比較対象国</label>
			<div class="col-xs-8">
				<label class="radio-inline">
				 <input type="radio" name="countury" id="countury" value="us" checked>アメリカ
				</label>
				<label class="radio-inline">
				 <input type="radio" name="countury" id="countury" value="fr">フランス
				</label>
			</div>
		</div>

	  <div class="clearfix"></div>

		<div class="form-group">
			<label class="control-label col-xs-2">為替レート</label>
			<div class="col-xs-4">
	   			<div class="input-group">
					<input type="text" class="form-control" name="rate" id="rate">
					<div class="input-group-addon">円</div>
				</div>
			</div>
		</div>

	  <div class="clearfix"></div>

		<div class="form-group">
			<div class="col-xs-offset-1 col-xs-9">
				<textarea class="form-control" name="asin" id="asin" rows="8"><!--{$asinStr}--></textarea>
			</div>
		</div>

<!--
		<div class="radio">
			<label class="checkbox-inline">
			 <input type="radio" name="mode" id="mode" value="output" checked>ウェブ表示
			</label>
			<label class="checkbox-inline">
			 <input type="radio" name="mode" id="mode" value="csv">Csv出力
			</label>
		</div><br />
-->
	  <div class="form-group">
	    <div class="col-sm-offset-1 col-sm-11">
	      <button type="submit" id="submit" class="btn btn-primary">送信</button>
	    </div>
	  </div>
	</form>

	</div>

  <script type="text/javascript" src="js/jquery.cookie.js"></script>
	<script type="text/javascript">
	$(function(){

		$('#keyword').val($.cookie("keyword"));

		$("#submit").click(function(){
			$.cookie("keyword",$('#keyword').val(),{ expires: 7 });
			location.href="lookup.php"
		})
	})
	</script>

  <p>本サイトは、amazon.co.jpのASINを収集するサイトです。<br />

	<h2>使い方</h2>
	<p>
	下のボックスに検索ワードを入力してください<br />
	</p>

<a href="<!--{$smarty.const.SITE_URL}-->">トップに戻る</a><br />
<a href="javascript:window.history.back();">前のページに戻る</a><br /><br />

	<form method="post" action="search.php" class="form-horizontal">
		<input type="hidden" name="mode" value="output">
		<div class="form-group">
			<label class="control-label col-xs-2">検索対象国</label>
			<div class="col-xs-8">
				<label class="radio-inline">
				 <input type="radio" name="countury" id="countury" value="jp" checked>日本
				</label>
				<label class="radio-inline">
				 <input type="radio" name="countury" id="countury" value="us">アメリカ
				</label>
				<label class="radio-inline">
				 <input type="radio" name="countury" id="countury" value="fr">フランス
				</label>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-xs-2">キーワード</label>
			<div class="col-xs-8">
				<input type="text" class="form-control" name="keyword" id="keyword">
			</div>
		</div>
	  <div class="form-group">
	    <div class="col-sm-offset-1 col-sm-11">
	      <button type="submit" id="submit" class="btn btn-primary">送信</button>
	    </div>
	  </div>
	</form>

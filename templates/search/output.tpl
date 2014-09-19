
<script Language="JavaScript">
<!-- 
function send1() {
 document.form1.submit(); 
}
function send2() {
 document.form2.submit(); 
} 
// --></script>

<h2>結果の見方</h2>
<p>
ASINをクリックするとamazonの購入サイトにジャンプします。<br />
</p>

<div class="row">

	<div class="col-xs-4">
		<a href="<!--{$smarty.const.SITE_URL}-->">トップに戻る</a><br />
		<a href="javascript:window.history.back();">前のページに戻る</a><br /><br />
	</div>
	<div class="col-xs-offset-4 col-xs-4">
		<form name="form1" method="post" action="stock.php" class="form-horizontal">
			<input type="hidden" name="asin" value="<!--{$asinArray}-->" />
			<a href="javaScript:send1()">在庫チェック</a>
		</form>
		<form name="form2" method="post" action="lookup.php" class="form-horizontal">
			<input type="hidden" name="asin" value="<!--{$asinArray}-->" />
			<a href="javaScript:send2()">価格差チェック</a>
		</form>
	</div>

	<br />

	<div class="clearfix"></div>

	<div class="col-xs-12">
		アマゾン<!--{$countury_name}-->で検索しました。<!--{$items|@count}-->件あります。
	</div>
</div>

<table class="table table-striped table-bordered table-hover table->condensed">
	<thead>
		<tr><th>ASIN</th><th>タイトル</th><th>金額</th><th>重さ</th><th>更新時間</th>
	</thead>
	<tbody>
		<!--{foreach from=$items key=k item=var name=loopname}-->
				<tr>
					<td>
<a href="http://px.a8.net/svt/ejp?a8mat=1NWEUR+987YNE+249K+BWGDT&a8ejpredirect=http%3A%2F%2Fwww.amazon.co.jp%2Fdp%2F<!--{$var['asin']}-->%2F%3Ftag%3Da8-affi-202787-22" target="_blank">
<!--{$var['asin']}-->
</a>
					</td>
					<td><!--{$var['title']}--></td>
					<td>
					<!--{$currencyUnitStr}-->
					<!--{if $countury == "us" || $countury == "fr"}-->
						<!--{$var['price']/100|escape|number_format:2}-->
					<!--{else}-->
						<!--{$var['price']}-->
					<!--{/if}-->
					</td>
					<td><!--{($var['weight']*10*0.454)|escape|number_format:0}-->g</td>
					<td><!--{$var['time']}--></td>
<!--
					<td>
<a href="http://www.amazon.co.jp/gp/product/<!--{$var['asin']}-->/ref=as_li_tf_tl?ie=UTF8&camp=247&creative=1211&creativeASIN={$var['asin']}&linkCode=as2&tag=libary01-22">購入</a><img src="http://ir-jp.amazon-adsystem.com/e/ir?t=libary01-22&l=as2&o=9&a=<!--{$var['asin']}-->" width="1" height="1" border="0" alt="" style="border:none !important; margin:0px !important;" />
					</td>
-->
				</tr>
		<!--{/foreach}-->
	</tbody>
</table>

<p><a href="#" onClick="history.back(); return false;">前のページにもどる</a></p[
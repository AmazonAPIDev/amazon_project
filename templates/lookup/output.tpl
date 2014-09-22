
<p>
価格差で降順ソートされます。<br />
価格差率が１５%以上かつアマゾン<!--{$countury_name}-->でのランキングが１０万位以内の行全体が赤くなります。
</p>

<a href="<!--{$smarty.const.SITE_URL}-->">トップに戻る</a><br />
<a href="javascript:window.history.back();">前のページに戻る</a><br /><br />

<!--{$items|@count}-->件あります。

<table class="table table-striped table-bordered table-hover table->condensed">
	<thead>
		<tr><th>ASIN</th><th>タイトル</th><th colspan="2">最安値</th><th>価格差</th><th>ランキング</th><th>在庫数</th>
	</thead>
	<tbody>
		<!--{foreach from=$items key=k item=var name=loopname}-->
			<!--{if $var["mark"] == "1" }-->
				<tr class="danger">
			<!--{else}-->
				<tr>
			<!--{/if}-->
					<td>
<!--{if $countury == "fr" }-->
<a href="http://px.a8.net/svt/ejp?a8mat=1NWEUR+987YNE+249K+BWGDT&a8ejpredirect=http%3A%2F%2Fwww.amazon.fr%2Fdp%2F<!--{$var['us']['asin']}-->%2F%3Ftag%3Da8-affi-202787-22" target="_blank">
<!--{$var['us']['asin']}-->(fr)
</a>
<!--{else}-->
<a href="http://px.a8.net/svt/ejp?a8mat=1NWEUR+987YNE+249K+BWGDT&a8ejpredirect=http%3A%2F%2Fwww.amazon.com%2Fdp%2F<!--{$var['us']['asin']}-->%2F%3Ftag%3Da8-affi-202787-22" target="_blank">
<!--{$var['us']['asin']}-->(en)
</a>
<!--{/if}-->
					</td>
					<td><!--{$var['us']['title']}--></td>
					<td>
						<!--{$currencyUnitStr}--><!--{$var['us']['price']/100}-->
					</td>
					<td>&yen;<!--{($var['us']['price']/100*$rate)|floor|number_format}--></td>
					<td>
					&yen;<!--{($var['us']['price']/100*$rate-$var['ja']['price'])|floor|number_format}-->
					(<!--{(($var['us']['price']/100*$rate-$var['ja']['price'])*100/$var['ja']['price'])|escape|number_format:2}-->%)
					</td>
					<td><!--{$var['us']['rank']}--></td>
					<td><!--{$var['us']['count']}--></td>
	<!--
		
					<td><a href="<!--{$var->DetailPageURL}-->">購入</a></td>
					<td>購入</td>
	-->
				</tr>
			<!--{if $var["mark"] == "1" }-->
				<tr class="danger">
			<!--{else}-->
				<tr>
			<!--{/if}-->
					<td>
<a href="http://px.a8.net/svt/ejp?a8mat=1NWEUR+987YNE+249K+BWGDT&a8ejpredirect=http%3A%2F%2Fwww.amazon.co.jp%2Fdp%2F<!--{$var['ja']['asin']}-->%2F%3Ftag%3Da8-affi-202787-22" target="_blank">
<!--{$var['ja']['asin']}-->(jp)
</a>
					</td>
					<td><!--{$var['ja']['title']}--></td>
					<td>--</td>
					<td>&yen;<!--{$var['ja']['price']}--></td>
					<td>--</td>
					<td><!--{$var['ja']['rank']}--></td>
					<td><!--{$var['ja']['count']}--></td>
				</tr>
		<!--{/foreach}-->
	</tbody>
</table>

<p><a href="#" onClick="history.back(); return false;">前のページにもどる</a></p[

<h2>結果の見方</h2>
<p>
在庫数で昇順ソートされます。<br />
在庫が2個以下のアイテムは、行全体が赤くなります。<br />
ASINをクリックするとamazonの購入サイトにジャンプします。<br />
</p>

<a href="<!--{$smarty.const.SITE_URL}-->">トップに戻る</a><br />
<a href="javascript:window.history.back();">前のページに戻る</a><br /><br />

<table class="table table-striped table-bordered table-hover table->condensed">
	<thead>
		<tr><th>ASIN</th><th>タイトル</th><th>金額</th><th>重さ</th><th>在庫数</th><th>更新時間</th>
	</thead>
	<tbody>
		<!--{foreach from=$items key=k item=var name=loopname}-->
			<!--{if $var['count'] < 3 }-->
				<tr class="danger">
			<!--{else}-->
				<tr>
			<!--{/if}-->
					<td>
<a href="http://px.a8.net/svt/ejp?a8mat=1NWEUR+987YNE+249K+BWGDT&a8ejpredirect=http%3A%2F%2Fwww.amazon.co.jp%2Fdp%2F<!--{$var['asin']}-->%2F%3Ftag%3Da8-affi-202787-22" target="_blank">
<!--{$var['asin']}-->
</a>
					</td>
					<td><!--{$var['title']}--></td>
					<td><!--{$var['price']}-->円</td>
					<td><!--{($var['weight']*10*0.454)|escape|number_format:0}-->g</td>
					<td><!--{$var['count']}--></td>
					<td><!--{$var['time']}--></td>
<!--
					<td>
<a href="http://www.amazon.co.jp/gp/product/{$var['asin']}/ref=as_li_tf_tl?ie=UTF8&camp=247&creative=1211&creativeASIN={$var['asin']}&linkCode=as2&tag=libary01-22">購入</a><img src="http://ir-jp.amazon-adsystem.com/e/ir?t=libary01-22&l=as2&o=9&a={$var['asin']}" width="1" height="1" border="0" alt="" style="border:none !important; margin:0px !important;" />
					</td>
-->
				</tr>
		<!--{/foreach}-->
	</tbody>
</table>

<p><a href="#" onClick="history.back(); return false;">前のページにもどる</a></p[
<!--{extends file='userPage.tpl'}-->
<!--{block name=title}-->
<title>测试数据查询</title>
<!--{/block}-->
<!--{block name=style}-->
<!--{/block}-->
<!--{block name=subScript}-->
<script>
	$(document).ready(function()
	{
		$(".detail").click(function(e)
		{
			e.preventDefault();
			var tmpStr = $(this).attr('href');
			var tmpIndex = tmpStr.substring((tmpStr.lastIndexOf('/') + 1));
			$("#testItemDiv").load("{site_url('cssj/getTestItemResult')}" + "/" + tmpIndex, function(responseText, textStatus, XMLHttpRequest)
			{
				if (textStatus == 'success')
				{
				}
			});
		});
	}); 
</script>
<!--{/block}-->
<!--{block name=subBody}-->
<div class="prepend-1 span-63 last">
	<form method="post" action="{site_url('sckb')}">
		<input type="submit" value="查找">
	</form>
</div>
<div class="prepend-1 span-63 last">
	测试数据查询
</div>
<div class="prepend-1 span-31">
	<div class="span-31">
		<div class="span-7">
			时间
		</div>
		<div class="span-5">
			测试站
		</div>
		<div class="span-5">
			工号
		</div>
		<div class="span-5">
			型号
		</div>
		<div class="span-4">
			序列号
		</div>
		<div class="span-3">
			测试结果
		</div>
		<div class="span-2">
			详情
		</div>
	</div>
	{foreach $productTestList as $productTest}
	<div class="span-31">
		<div class="span-7">
			{$productTest['testTime']}
		</div>
		<div class="span-5">
			{$productTest['testStationName']}
		</div>
		<div class="span-5">
			{$productTest['employeeId']}
		</div>
		<div class="span-5">
			{$productTest['productType']}
		</div>
		<div class="span-4">
			{$productTest['sn']}
		</div>
		<div class="span-3">
			{$productTest['result']}
		</div>
		<div class="span-2">
			<a class="detail" href="{$productTest['id']}">详情</a>
		</div>
	</div>
	{/foreach}
</div>
<div id="testItemDiv" class="prepend-1 span-31 last">
</div>
<!--{/block}-->
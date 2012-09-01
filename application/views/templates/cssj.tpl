<!--{extends file='userPage.tpl'}-->
<!--{block name=title}-->
<title>测试数据查询</title>
<!--{/block}-->
<!--{block name=style}-->
<style type="text/css" media="screen">
	.testImg {
		width: 300px;
		height: 200px;
	}
</style>
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
<div class="prepend-1 span-31">
	<form method="post" action="{site_url('cssj')}">
		<div class="span-5">
			时间:
		</div>
		<input type="text" name="timeFrom" />
		至
		<input type="text" name="timeTo" />
		<br>
		<div class="span-5">
			测试结果:
		</div>
		<input type="text" name="testResult" />
		<br>
		<div class="span-5">
			测试站:
		</div>
		<input type="text" name="testStationName" />
		<br>
		<div class="span-5">
			型号:
		</div>
		<input type="text" name="productTypeName" />
		<br>
		<div class="span-5">
			工号:
		</div>
		<input type="text" name="employeeId" />
		<br>
		<div class="span-5">
			序列号:
		</div>
		<input type="text" name="sn" />
		<br>
		<input type="submit" value="查找">
	</form>
</div>
<div id="imgDiv" class="prepend-1 span-31 last">
	<img class="testImg" src="" />
</div>
<div class="prepend-1 span-63 last">
	测试数据查询
</div>
<div class="prepend-1 span-31">
	<div class="span-31">
		<div class="span-2">
			序号
		</div>
		<div class="span-6">
			时间
		</div>
		<div class="span-5">
			测试站
		</div>
		<div class="span-4">
			工号
		</div>
		<div class="span-4">
			型号
		</div>
		<div class="span-4">
			序列号
		</div>
		<div class="span-4">
			测试结果
		</div>
		<div class="span-2">
			详情
		</div>
	</div>
	{foreach $productTestList as $productTest}
	<div class="span-31">
		<div class="span-2">
			{$productTest['id']}
		</div>
		<div class="span-6">
			{$productTest['testTime']}
		</div>
		<div class="span-5">
			{$productTest['testStationName']}
		</div>
		<div class="span-4">
			{$productTest['employeeId']}
		</div>
		<div class="span-4">
			{$productTest['productType']}
		</div>
		<div class="span-4">
			{$productTest['sn']}
		</div>
		<div class="span-4">
			{$productTest['result']}
		</div>
		<div class="span-2">
			<a class="detail" href="{$productTest['id']}">详情</a>
		</div>
	</div>
	{/foreach}
	{$CI->pagination->create_links()}
</div>
<div id="testItemDiv" class="prepend-1 span-31 last">
</div>
<!--{/block}-->
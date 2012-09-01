<script>
	$(".imgPath").click(function(e)
	{
		e.preventDefault();
		$("#imgDiv > img").attr('src', $(this).attr("path"));
	}); 
</script>
<div class="span-31 last">
	{$productTestInfo}
</div>
{foreach $testItemResultList as $item}
<div class="span-31 last">
	<div class="span-5">
		<a href="#" class="imgPath" path="{$item['img']}">{$item['testItemName']}</a>
	</div>
	{foreach $item['testItemMarkValueArray'] as $mark}
	<div class="span-6">
		({$mark['value']}|{$mark['markF']}|{$mark['markT']})
	</div>
	{/foreach}
</div>
{/foreach} 
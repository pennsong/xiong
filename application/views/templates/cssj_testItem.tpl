<div class="span-31 last">
	{$productTestInfo}
</div>
{foreach $testItemResultList as $item}
<div class="span-31 last">
	<div class="span-5">
		{$item['testItemName']}
	</div>
	<div class="span-5">
		<img src="{$item['img']}" />
	</div>
	{foreach $item['testItemMarkValueArray'] as $mark}
	<div class="span-5">
		{$mark['value']}|{$mark['markF']}|{$mark['markT']}
	</div>
	{/foreach}
</div>
{/foreach} 
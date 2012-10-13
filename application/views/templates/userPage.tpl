<!--{extends file='defaultPage.tpl'}-->
<!--{block name=style}-->
<!--{/block}-->
<!--{block name=script}-->
<!--{block name=subScript}-->
<!--{/block}-->
<!--{/block}-->
<!--{block name=body}-->
<div class="span-64 last">
	<div class="span-64 last">
		<span class="cldnH1">信息维护</span>
	</div>
	<div class="span-64 last">
		<div class="span-12">
			<span class="cldnH2">产品</span>
		</div>
		<div class="prepend-1 span-6">
			<span><a style="line-height: 39px" class="{if $title=='产品管理'} currentMenu {else} normal {/if}" href="{site_url('firstPage/producttype')}">产品管理</a></span>
		</div>
	</div>
	<hr>
	<div class="span-64 last">
		<div class="span-12">
			<span class="cldnH2">测试</span>
		</div>
		<div class="prepend-1 span-6">
			<span><a style="line-height: 39px" class="{if $title=='测试项'} currentMenu {else} normal {/if}" href="{site_url('firstPage/testitem')}">测试项</a></span>
		</div>
		<div class="prepend-1 span-6">
			<span><a style="line-height: 39px" class="{if $title=='测试站点'} currentMenu {else} normal {/if}" href="{site_url('firstPage/teststation')}">测试站点</a></span>
		</div>
		<div class="prepend-1 span-6">
			<span><a style="line-height: 39px" class="{if $title=='产品测试方案'} currentMenu {else} normal {/if}" href="{site_url('firstPage/producttypetestcase')}">产品测试方案</a></span>
		</div>
	</div>
	<hr>
	<div class="span-64 last">
		<div class="span-12">
			<span class="cldnH2">组织与人员</span>
		</div>
		<div class="prepend-1 span-6">
			<span><a style="line-height: 39px" class="{if $title=='部门'} currentMenu {else} normal {/if}" href="{site_url('firstPage/department')}">部门管理</a></span>
		</div>
		<div class="prepend-1 span-6">
			<span><a style="line-height: 39px" class="{if $title=='技能水平'} currentMenu {else} normal {/if}" href="{site_url('firstPage/skilllevel')}">技能水平</a></span>
		</div>
		<div class="prepend-1 span-6">
			<span><a style="line-height: 39px" class="{if $title=='测试员'} currentMenu {else} normal {/if}" href="{site_url('firstPage/tester')}">测试员</a></span>
		</div>
		<div class="prepend-1 span-6">
			<span><a style="line-height: 39px" class="{if $title=='测试员权限'} currentMenu {else} normal {/if}" href="{site_url('firstPage/testright')}">测试员权限</a></span>
		</div>
	</div>
	<hr>
	<div class="span-64 last">
		<div class="span-12">
			<span class="cldnH2">系统用户管理</span>
		</div>
		<div class="prepend-1 span-6">
			<span><a style="line-height: 39px" class="{if $title=='系统用户'} currentMenu {else} normal {/if}" href="{site_url('firstPage/user')}">系统用户管理</a></span>
		</div>
		<div class="prepend-1 span-6">
			<span><a style="line-height: 39px" class="{if $title=='用户组'} currentMenu {else} normal {/if}" href="{site_url('firstPage/team')}">用户组</a></span>
		</div>
		<div class="prepend-1 span-6">
			<span><a style="line-height: 39px" class="{if $title=='首页通知'} currentMenu {else} normal {/if}" href="{site_url('firstPage/firstpagenotice')}">首页通知</a></span>
		</div>
	</div>
	<hr>
	<div class="span-64 last">
		<div class="span-12">
			<span class="cldnH2">功能模块</span>
		</div>
		<div class="prepend-1 span-6">
			<span><a style="line-height: 39px" class="{if $title=='生产看板'} currentMenu {else} normal {/if}" href="{site_url('sckb')}">生产看板</a></span>
		</div>
		<div class="prepend-1 span-6">
			<span><a style="line-height: 39px" class="{if $title=='测试数据查询'} currentMenu {else} normal {/if}" href="{site_url('cssj')}">测试数据查询</a></span>
		</div>
		<div class="prepend-1 span-9">
			<span><a style="line-height: 39px" class="{if $title=='PIM测试数据查询'} currentMenu {else} normal {/if}" href="#">PIM测试数据查询</a></span>
		</div>
	</div>
</div>
<div class="prepend-top span-64 last">
	<!--{block name=subBody}-->
	<!--{/block}-->
</div>
<!--{/block}-->

<!--{extends file='defaultPage.tpl'}-->
<!--{block name=style}-->
<!--{/block}-->
<!--{block name=script}-->
<!--{block name=subScript}-->
<!--{/block}-->
<!--{/block}-->
<!--{block name=body}-->
<div class="span-64 last pageTop">
	<div class="span-45">
		<img class="logo" src="{base_url()}resource/img/logo.png"/>
	</div>
	<div class="span-16">
		欢迎您:{$CI->session->userdata('username')}
	</div>
	<div class="span-3 last">
		<a href="{site_url('login/logout')}">退出</a>
	</div>
</div>
<div class="span-64 last">
	<div class="span-64 last">
		<span class="cldnH1">基础信息维护</span>
	</div>
	<div class="span-64 last">
		<a class="{if $title=='部门'} currentMenu {else} normal {/if}" href="{site_url('firstPage/department')}">部门管理</a>|<a class="{if $title=='产品类型'} currentMenu {else} normal {/if}" href="{site_url('firstPage/productType')}">产品管理</a>|<a class="{if $title=='测试技能水平'} currentMenu {else} normal {/if}" href="{site_url('firstPage/skillLevel')}">技能水平管理</a>|<a class="{if $title=='组'} currentMenu {else} normal {/if}" href="{site_url('firstPage/team')}">组管理</a>|<a class="{if $title=='测试人员'} currentMenu {else} normal {/if}" href="{site_url('firstPage/tester')}">测试员管理</a>|<a class="{if $title=='测试项目'} currentMenu {else} normal {/if}" href="{site_url('firstPage/testItem')}">测试项目管理</a>|<a class="{if $title=='测试权限'} currentMenu {else} normal {/if}" href="{site_url('firstPage/testRight')}">测试权限管理</a>|<a class="{if $title=='测试站点'} currentMenu {else} normal {/if}" href="{site_url('firstPage/testStation')}">测试站点管理</a>|<a class="{if $title=='系统用户'} currentMenu {else} normal {/if}" href="{site_url('firstPage/user')}">系统用户管理</a>|<a class="{if $title=='产品测试案例'} currentMenu {else} normal {/if}" href="{site_url('firstPage/productTypeTestCase')}">产品测试管理</a>
	</div>
	<div class="span-64 last">
		<span class="cldnH1">功能模块</span>
	</div>
	<div class="span-64 last">
		<a class="{if $title=='生产看板'} currentMenu {else} normal {/if}" href="{site_url('sckb')}">生产看板</a>|<a class="{if $title=='测试数据查询'} currentMenu {else} normal {/if}" href="{site_url('cssj')}">测试数据查询</a>
	</div>
</div>
<div class="prepend-top span-64 last">
	<!--{block name=subBody}-->
	<!--{/block}-->
</div>
<!--{/block}-->

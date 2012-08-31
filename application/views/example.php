<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<?php
		foreach($css_files as $file):
		?>
		<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
		<?php endforeach; ?>
		<?php foreach($js_files as $file):
		?>
		<script src="<?php echo $file; ?>"></script>
		<?php endforeach; ?>
		<style type='text/css'>
			body {
				font-family: Arial;
				font-size: 14px;
			}
			a {
				color: blue;
				text-decoration: none;
				font-size: 14px;
			}
			a:hover {
				text-decoration: underline;
			}
		</style>
	</head>
	<body>
		<div>
			<h1>基础信息维护</h1>
		</div>
		<div>
			<a href='<?php echo site_url('firstPage/department')?>'>部门管理</a>
		</div>
		<div>
			<a href='<?php echo site_url('firstPage/productType')?>'>产品管理</a>
		</div>
		<div>
			<a href='<?php echo site_url('firstPage/skillLevel')?>'>技能水平管理</a>
		</div>
		<div>
			<a href='<?php echo site_url('firstPage/team')?>'>组管理</a>
		</div>
		<div>
			<a href='<?php echo site_url('firstPage/tester')?>'>测试员管理</a>
		</div>
		<div>
			<a href='<?php echo site_url('firstPage/testItem')?>'>测试项目管理</a>
		</div>
		<div>
			<a href='<?php echo site_url('firstPage/testRight')?>'>测试权限管理</a>
		</div>
		<div>
			<a href='<?php echo site_url('firstPage/testStation')?>'>测试站点管理</a>
		</div>
		<div>
			<a href='<?php echo site_url('firstPage/user')?>'>系统用户管理</a>
		</div>
		<div>
			<a href='<?php echo site_url('firstPage/productTypeTestCase')?>'>产品测试管理</a>
		</div>
		<div>
			<h1>功能模块</h1>
		</div>
		<div>
			<a href='#'>生产看板</a>
		</div>
		<div>
			<a href='#'>生产任务管理</a>
		</div>
		<div>
			<a href='#'>测试数据查询</a>
		</div>
		<div style='height:20px;'>
		</div>
		<div>
			<?php echo $output; ?>
		</div>
	</body>
</html>

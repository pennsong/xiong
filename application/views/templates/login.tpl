<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<!--{$commonHead}-->
		<!--{$jqueryHead}-->
		<!--{$validationEngineHead}-->
		<title>登录</title>
		<style type="text/css" media="screen">
			div.locUserNameDefaultStr {
				left: 4px;
			}
			div.locGeneralErrorInfo {
				padding-top: 1px;
				padding-bottom: 1px;
			}
			div.locUserType {
				height: 19px;
			}
		</style>
		<script>
			$(document).ready(function()
			{
				$(".locDefaultStr").click(function()
				{
					$(this).prev(".locDefaultStrContainer").focus();
				});
				$(".locDefaultStrContainer").focus(function()
				{
					$(this).next(".locDefaultStr").hide();
				});
				$(".locDefaultStrContainer").blur(function()
				{
					if ($(this).val() == "")
					{
						$(this).next(".locDefaultStr").show();
					}
				});
				$(".locDefaultStrContainer").blur();
				$("#locLoginForm").validationEngine('attach',
				{
					promptPosition : "centerRight",
					autoPositionUpdate : "true"
				});
			});
			function checkUserName(field, rules, i, options)
			{
				var err = new Array();
				var reg1 = /^[_\.].*/;
				var reg2 = /.*[_\.]$/;
				var str = field.val();
				if (reg1.test(str) || reg2.test(str))
				{
					err.push('* 不能以下划线或点开始或结束！');
				}
				if ((countOccurrences(str, '.') + countOccurrences(str, '_')) > 1)
				{
					err.push('* 一个用户名仅允许包含一个下划线或一个点！');
				}
				if (err.length > 0)
				{
					return err.join("<br>");
				}
			}

			function countOccurrences(str, character)
			{
				var i = 0;
				var count = 0;
				for ( i = 0; i < str.length; i++)
				{
					if (str.charAt(i) == character)
					{
						count++;
					}
				}
				return count;
			}
		</script>
	</head>
	<body class="cldn">
		<div class="container">
			<div class="span-64 last">
				  <img class="logo" src="{base_url()}resource/img/logo.png"/>
			</div>
			<div class="clear prepend-19 last append-bottom20">
				<div>
					<span class="cldnH1">欢迎来到Xiong系统</span>
				</div>
			</div>
			<form id="locLoginForm" action="{site_url('login/validateLogin')}" method="post">
				<div class="clear prepend-19 append-bottom5">
					<div class="label1">
						用户名
					</div>
				</div>
				<div class="clear prepend-19 span-11 inline append-bottom10">
					<div class="relative">
						<input id="userName" name="userName" class="locDefaultStrContainer input1 validate[required, custom[onlyLetterNumberUnderLineDot], minSize[6], maxSize[15], funcCall[checkUserName]]" value="{$smarty.post.userName|default:''}" type="text" />
						<div class="locDefaultStr defaultStr1 locUserNameDefaultStr">
							请输入用户名
						</div>
					</div>
				</div>
				<div class="clear prepend-19 append-bottom5">
					<div class="label1">
						密码
					</div>
				</div>
				<div class="clear prepend-19 span-11 inline append-bottom20">
					<div class="relative">
						<input id="password" name="password" class="locDefaultStrContainer input1 validate[required, custom[onlyLetterNumber], minSize[6], maxSize[20]]" type="password" />
						<div class="locDefaultStr defaultStr1 locUserNameDefaultStr">
							请输入密码
						</div>
					</div>
				</div>
				<div class="clear prepend-19">
					<div class="inline span-3">
						<button id="loginButton" class="button1" type="submit">
							登录
						</button>
					</div>
					<div class="span-10 locGeneralErrorInfo">
						<span class="error1">{$loginErrorInfo|default:''}</span>
					</div>
				</div>
			</form>
		</div>
	</body>
</html>
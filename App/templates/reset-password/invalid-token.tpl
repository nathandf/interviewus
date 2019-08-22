{extends file="layouts/core.tpl"}

{block name="head"}
	<title>InterviewUs - Reset Password</title>
{/block}

{block name="body"}
	{include file="includes/navigation/main-menu.tpl"}
	<div class="con-cnt-med-plus push-t-lrg push-b-lrg pad-med bg-white border-std">
		<p class="sub-title" style="margin: 0;">This password reset token is either expired or invalid</p>
		<div class="push-t-med text-center">
			<a href="{$HOME}reset-password">Reset Password</a>
		</div>
	</div>
{/block}

{block name="footer"}
	{include file="includes/snippets/footer.tpl"}
{/block}

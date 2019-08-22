{extends file="layouts/core.tpl"}

{block name="head"}
	<title>InterviewUs - Reset Password</title>
{/block}

{block name="body"}
	{include file="includes/navigation/main-menu.tpl"}
	<div class="con-cnt-med-plus push-t-lrg push-b-lrg pad-med bg-white border-std">
		<p class="sub-title" style="margin: 0;">Create your new password</p>
		<div class="push-t-med"></div>
		{include file="includes/snippets/flash-messages.tpl"}
		{if !empty($error_messages.reset_password)}
			{foreach from=$error_messages.reset_password item=message}
				<div class="con-message-error mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		<form action="" method="post">
			<input type="hidden" name="token" value="{$csrf_token}">
			<input type="hidden" name="reset_password" value="{$csrf_token}">
			{include file="includes/snippets/form-components/password-show.tpl"}
			<button type="submit" class="button theme-primary push-t-med">Reset Password</button>
		</form>
	</div>
{/block}

{block name="footer"}
	{include file="includes/snippets/footer.tpl"}
{/block}

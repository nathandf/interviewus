{extends file="layouts/core.tpl"}

{block name="head"}
	<title>InterviewUs</title>
{/block}

{block name="body"}
	{include file="includes/navigation/main-menu.tpl"}
	<div class="con-cnt-med-plus push-t-lrg push-b-lrg pad-med bg-white border-std">
		<p class="sub-title" style="margin: 0;">Reset Password</p>
		<div>
			<img class="img-med" style="margin: 0 auto; display: block;" src="{$HOME}public/static/img/robo-logo.jpg" alt="">
		</div>
		{include file="includes/snippets/flash-messages.tpl"}
		{if !empty($error_messages.sign_in)}
			{foreach from=$error_messages.sign_in item=message}
				<div class="con-message-error mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		<form action="" method="post">
			<input type="hidden" name="token" value="{$csrf_token}">
			<input type="hidden" name="send_reset_link" value="{$csrf_token}">
			<p class="label">Email</p>
			<input type="email" name="email" class="inp inp-full push-b-sml" required="required">
			<button type="submit" class="button theme-primary push-t-med">Send Reset Link</button>
		</form>
	</div>
{/block}

{block name="footer"}
	{include file="includes/snippets/footer.tpl"}
{/block}

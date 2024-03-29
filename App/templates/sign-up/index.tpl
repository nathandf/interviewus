{extends file="layouts/core.tpl"}

{block name="head"}
	<title>InterviewUs</title>
	<script src="{$HOME}{$JS_SCRIPTS}sign-up/sign-up.js"></script>
{/block}

{block name="body"}
	{include file="includes/navigation/main-menu.tpl"}
	<p class="title title-h2 push-t-med">Create Account</p>
	<div class="con-cnt-med-plus-plus pad-med border-std bg-white push-t-med push-b-lrg">
		{if !empty($error_messages.create_account)}
			{foreach from=$error_messages.create_account item=message}
				<div class="con-message-error mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		<form action="" method="post" id="create-account">
			<input type="hidden" name="token" value="{$csrf_token}">
			<input type="hidden" name="create_account" value="{$csrf_token}">
			<p class="label">Name</p>
			<input type="text" class="inp inp-full" id="first_name" name="name" value="{$fields.create_account.name|default:null}" required="required"/>
			<div class="clear push-t-med"></div>
			<p class="label">Email</p>
			<input type="email" class="inp inp-full" id="email" name="email" value="{$fields.create_account.email|default:null}" required="required"/>
			<div class="clear push-t-med"></div>
			{include file="includes/snippets/form-components/password-show.tpl"}
			<div class="clear push-t-med"></div>
			<input type="hidden" name="terms_conditions_agreement" value="true"><label class="text-sml">By pressing "Create Account", you accept and agree to the<br><a target="_blank" href="{$HOME}terms-and-conditions">Terms and Conditions</a> and <a target="_blank" href="{$HOME}privacy-policy">Privacy Policy</a></label>
			<div class="clear last"></div>
			<input type="submit" class="button theme-primary push-t-med" name="button" value="Create Account"/>
		</form>
	</div>
{/block}

{block name="footer"}
	{include file='includes/snippets/footer.tpl'}
{/block}

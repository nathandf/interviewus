{extends file="layouts/core.tpl"}

{block name="head"}
	<title>InterviewUs</title>
{/block}

{block name="body"}
	{include file="includes/navigation/main-menu.tpl"}
	<div class="con-cnt-med-plus push-t-lrg push-b-lrg inner-pad-med bg-white border-std">
		<p class="push-b-med sub-title" style="margin-top: 0;">Sign In</p>
		<form action="{$HOME}sign-in">
			<input type="hidden" name="token" value="{$csrf_token}">
			<p class="label">Email</p>
			<input type="text" name="email" class="inp inp-full push-b-sml">
			<p class="label">Password</p>
			<input type="password" name="password" class="inp inp-full push-b-sml">
			<button type="submit" class="button tc-white push-t-med">Sign In</button>
		</form>
	</div>
{/block}

{block name="footer"}
	{include file="includes/snippets/footer.tpl"}
{/block}

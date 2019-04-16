{extends file="layouts/core.tpl"}

{block name="head"}
	<title>InterviewUs</title>
{/block}

{block name="body"}
	{include file="includes/navigation/main-menu.tpl"}
	<div class="con-cnt-med-plus push-t-lrg push-b-lrg pad-med bg-white border-std">
		<p class="sub-title" style="margin: 0;">Sign In</p>
		<div>
			<img class="img-med" style="margin: 0 auto; display: block;" src="{$HOME}public/static/img/robo-logo.jpg" alt="">
		</div>
		<form action="{$HOME}sign-in">
			<input type="hidden" name="token" value="{$csrf_token}">
			<p class="label">Email</p>
			<input type="text" name="email" class="inp inp-full push-b-sml">
			<p class="label">Password</p>
			<input type="password" name="password" class="inp inp-full push-b-sml">
			<button type="submit" class="button tc-white push-t-med">Sign In</button>
			<p class="text-med push-t-sml"><a class="link text-med" href="{$HOME}sign-up/">Sign up</a></p>
		</form>
	</div>
{/block}

{block name="footer"}
	{include file="includes/snippets/footer.tpl"}
{/block}

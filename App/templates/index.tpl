{extends file="layouts/core.tpl"}

{block name="head"}
	<title>InterviewUs</title>
{/block}

{block name="body"}
	{include file="includes/navigation/main-menu.tpl"}
	<div class="con-cnt-lrg inner-pad-med push-t-lrg push-b-lrg border-std bg-white">
		<div class="con-cnt-med-plus-plus">
			<p class="title" style="margin: 0;">Automate Your Interviews</p>
			<div class="push-t-med"></div>
			<img class="img-lrg" src="{$HOME}public/static/img/robo-logo.jpg" style="display: block; margin: 0 auto;" alt="">
			<p class="sub-title push-t-med">Your HR workflow on Autopilot.</p>
			<a href="{$HOME}sign-up/" class="button-link tc-white">Create your first interview</a>
		</div>
	</div>
{/block}

{block name="footer"}
	{include file="includes/snippets/footer.tpl"}
{/block}

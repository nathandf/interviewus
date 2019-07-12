{extends file="layouts/core.tpl"}

{block name="head"}
	<title>InterviewUs</title>
	<script src="{$HOME}{$JS_SCRIPTS}home.js"></script>
{/block}

{block name="body"}
	{include file="includes/navigation/main-menu.tpl"}
	<div class="theme-tertiary-light pad-med" style="overflow: hidden;">
		<div id="welcome" class="con-cnt-lrg pad-med push-t-med push-b-med bg-white mat-box-shadow">
			<div class="con-cnt-med-plus-plus">
				<p class="title" style="margin: 0;">Automated Text Message Interviews</p>
				<div class="push-t-med"></div>
				<img class="img-lrg" src="{$HOME}public/static/img/robo-logo.jpg" style="display: block; margin: 0 auto;" alt="">
				<p class="sub-title push-t-med">Interviews on Autopilot</p>
				<a id="create-account" href="{$HOME}sign-up/" class="button-link theme-secondary">Create your first interview</a>
			</div>
		</div>
	</div>
{/block}

{block name="footer"}
	{include file="includes/snippets/footer.tpl"}
{/block}

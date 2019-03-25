{extends file="layouts/core.tpl"}

{block name="head"}
	<title>InterviewUs</title>
{/block}

{block name="body"}
	{include file="includes/navigation/main-menu.tpl"}
	<div class="con-cnt-lrg inner-pad-med push-t-med push-b-lrg">
		<p class="title">Automate Your Interviews</p>
		<div class="con-cnt-med-plus-plus">
			<p class="sub-title push-t-lrg" style="margin-bottom: 0;">Create. Deploy. Sit back and relax.</p>
			<p class="sub-title" style="margin-top: 0;">Your HR workflow is now on Autopilot.</p>
			<a href="{$HOME}sign-up/" class="button-link tc-white push-t-lrg">Create your first interview</a>
		</div>
	</div>
{/block}

{block name="footer"}
	{include file="includes/snippets/footer.tpl"}
{/block}

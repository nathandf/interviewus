{extends file="layouts/core.tpl"}

{block name="head"}
	<title>InterviewUs</title>
{/block}

{block name="body"}
	{include file="includes/navigation/main-menu.tpl"}
	<div class="con-cnt-lrg pad-med push-t-lrg push-b-lrg bg-white border-std">
		<p class="title title-h2">Interview Complete!</p>
		<p class="title"><i class="fas fa-check-square tc-green"></i></p>
		<div class="pad-sml"></div>
	</div>
{/block}

{block name="footer"}
	{include file="includes/snippets/footer.tpl"}
{/block}

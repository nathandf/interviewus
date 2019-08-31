{extends file="layouts/core.tpl"}

{block name="head"}
	<title>InterviewUs</title>
{/block}

{block name="body"}
	{include file="includes/navigation/main-menu.tpl"}
	<div class="pad-sml-mob-pos">
		<div class="con-cnt-lrg pad-med push-t-lrg push-b-lrg bg-white border-std">
			<p class="title title-h2">Your SMS Interview has started!</p>
			<p class="title"><i class="fas fa-check-square tc-green"></i></p>
			<p class="text-center text-xlrg-heavy">You should recieve you first text message shortly</p>
			<div class="pad-med"></div>
		</div>
	</div>
{/block}

{block name="footer"}
	{include file="includes/snippets/footer.tpl"}
{/block}

{extends file="layouts/core.tpl"}

{block name="head"}
	<title>InterviewUs</title>
	<script src="{$HOME}{$JS_SCRIPTS}home.js"></script>
	<link rel="stylesheet" href="{$HOME}public/css/home.css">
{/block}

{block name="body"}
	<div class="con-cnt-med pad-sml">
		<form action="{$HOME}webhooks/twilio/PNb3e9c12b31f5a9923eb9befb32bcef32/incoming/sms" method="post">
			<p class="label">Message</p>
			<input type="hidden" name="From" value="+18122763172">
			<input type="text" name="Body" class="inp inp-full">
			<button>Send</button>
		</form>
	</div>
{/block}

{block name="footer"}
	{include file="includes/snippets/footer.tpl"}
{/block}

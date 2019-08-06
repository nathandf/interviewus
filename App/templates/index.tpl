{extends file="layouts/core.tpl"}

{block name="head"}
	<title>InterviewUs</title>
	<script src="{$HOME}{$JS_SCRIPTS}home.js"></script>
	<link rel="stylesheet" href="{$HOME}public/css/home.css">
{/block}

{block name="body"}
	<div id="hero">
		<div id="hero-overlay" class="theme-primary-gradient-transparent">
			{include file="includes/navigation/main-menu.tpl"}
			<div class="con-cnt-lrg pad-med push-t-med push-b-med">
				<div class="con-cnt-med-plus-plus">
					<!-- <div>
						<img src="{$HOME}public/static/img/robo-logo.png" class="img-lrg" style="display: block;margin: 0 auto;">
					</div> -->
					<p class="title" style="margin: 0; color: #FFFFFF;">Automated Text Message Interviews</p>
					<div class="push-t-med">
						<a href="{$HOME}sign-up/" class="button-link bg-real-gold tc-white demo-button"><i class="fas fa-comment-dots push-r-sml"></i>Start Interview Demo</a>
					</div>
				</div>
			</div>
		</div>
	</div>
{/block}

{block name="footer"}
	{include file="includes/snippets/footer.tpl"}
{/block}

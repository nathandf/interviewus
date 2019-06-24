{extends file="layouts/core.tpl"}

{block name="head"}
	<link rel="stylesheet" href="{$HOME}public/css/profile/profile.css">
	<script type="application/javascript" src="{$HOME}{$JS_SCRIPTS}profile/profile.js"></script>
	{block name="profile-head"}{/block}
{/block}

{block name="body"}
	{include file="includes/navigation/profile/login-menu.tpl"}
	{include file="includes/navigation/profile/main-menu.tpl"}
	{include file="includes/modals/profile/user-modal.tpl"}
	{include file="includes/modals/profile/settings-modal.tpl"}
	{include file="includes/modals/user-feedback.tpl"}
	{block name="profile-body"}{/block}
	<div class="section-seperator"></div>
	<div class="feedback-trigger-container">
		<div id="user-feedback" class="user-feedback-trigger floatright bg-teal tc-white mat-box-shadow --modal-trigger">
			<p class="floatleft">Feedback</p>
			<p class="floatright"><i class="far fa-comment-dots"></i></p>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	</div>
{/block}

{block name="footer"}
	{block name="profile-footer"}{/block}
{/block}

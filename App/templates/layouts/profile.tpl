{extends file="layouts/core.tpl"}

{block name="head"}
	<link rel="stylesheet" href="{$HOME}public/css/profile/profile.css">
	<script type="application/javascript" src="{$HOME}{$JS_SCRIPTS}profile/profile.js"></script>
	{block name="profile-head"}{/block}
{/block}

{block name="body"}
	{include file="includes/navigation/profile/login-menu.tpl"}
	{include file="includes/navigation/profile/main-menu.tpl"}
	{include file="includes/modals/profile/settings-modal.tpl"}
	{include file="includes/modals/profile/organization-modal.tpl"}
	{block name="profile-body"}{/block}
	<div class="section-seperator"></div>
{/block}

{block name="footer"}
	{block name="profile-footer"}{/block}
{/block}

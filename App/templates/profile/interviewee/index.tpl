{extends file="layouts/profile-with-sidebar.tpl"}

{block name="profile-head"}
	<link rel="stylesheet" href="{$HOME}public/css/profile/interviewee.css">
{/block}

{block name="profile-body"}
	{include file="includes/modals/profile/interview-deployment-modal.tpl"}
	{include file="includes/modals/profile/interviewee/interviewee-details.tpl"}
	<div class="pad-med-mob-neg">
		<div class="pad-sml-mob-pos">
			<a href="{$HOME}profile/" class="btn btn-inline theme-primary push-r-sml"><i aria-hidden="true" class="fas fa-home"></i></a>
			<a href="{$HOME}profile/interviewees/" class="btn btn-inline theme-primary "><i aria-hidden="true" class="push-r-sml fas fa-caret-left"></i>Interviewees</a>
		</div>
		<div class="pad-sml-mob-neg"></div>
		{include file="includes/snippets/flash-messages.tpl"}
		{if !empty($error_messages.deploy_interview)}
			{foreach from=$error_messages.deploy_interview item=message}
				<div class="con-message-error mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		{if !empty($error_messages.update_interviewee)}
			{foreach from=$error_messages.update_interviewee item=message}
				<div class="con-message-error mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		<div class="content pad-sml-mob-pos floatleft">
			{assign var="interviews" value=$interviewee->interviews}
			{include file="includes/widgets/interviews.tpl"}
		</div>
		<div class="clear"></div>
	</div>
{/block}

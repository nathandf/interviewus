{extends file="layouts/profile.tpl"}

{block name="profile-head"}
{/block}

{block name="profile-body"}
	{include file="includes/modals/profile/interview-template-modal.tpl"}
	<div class="con-cnt-xxlrg inner-pad-med">
		<button id="interview-template" class="btn btn-inline theme-secondary --modal-trigger"><i aria-hidden="true" class="push-r-sml fas fa-plus"></i>Interview Template</button>
		<a href="{$HOME}profile/interview-templates/browse" class="btn btn-inline theme-primary"><i aria-hidden="true" class="push-r-sml fas fa-search"></i>Browse</a>
	</div>
	{if !empty($error_messages.new_interview_template)}
		{foreach from=$error_messages.new_interview_template item=message}
			<div class="con-message-failure mat-hov cursor-pt --c-hide">
				<p class="user-message-body">{$message}</p>
			</div>
		{/foreach}
	{/if}
{/block}

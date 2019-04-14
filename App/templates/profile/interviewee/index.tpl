{extends file="layouts/profile.tpl"}

{block name="profile-head"}
{/block}

{block name="profile-body"}
	{include file="includes/modals/profile/interview-deployment-modal.tpl"}
	<div class="con-cnt-xxlrg inner-pad-med">
		<a href="{$HOME}profile/interviewees/" class="btn btn-inline theme-primary "><i aria-hidden="true" class="push-r-sml fas fa-caret-left"></i>Interviewees</a>
		<button id="interview-deployment" class="btn btn-inline theme-secondary-dark --modal-trigger"><i aria-hidden="true" class="push-r-sml fas fa-rocket"></i>Deploy Interview</button>
		{if !empty($error_messages.new_interviewee)}
			{foreach from=$error_messages.new_interviewee item=message}
				<div class="con-message-failure mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		<div class="theme-secondary inner-pad-sml push-t-med">
			<p class="tc-black">{$interviewee->getFullName()}</p>
		</div>
		<div class="theme-secondary-light">

		</div>
	</div>
	<div class="section-seperator"></div>
{/block}

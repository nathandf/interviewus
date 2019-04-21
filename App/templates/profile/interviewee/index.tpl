{extends file="layouts/profile.tpl"}

{block name="profile-head"}
{/block}

{block name="profile-body"}
	{include file="includes/modals/profile/interview-deployment-modal.tpl"}
	<div class="con-cnt-xxlrg pad-med-mob-neg">
		<div class="pad-sml-mob-pos">
			<a href="{$HOME}profile/interviewees/" class="btn btn-inline theme-primary "><i aria-hidden="true" class="push-r-sml fas fa-caret-left"></i>Interviewees</a>
			<button id="interview-deployment" class="btn btn-inline theme-secondary-dark --modal-trigger"><i aria-hidden="true" class="push-r-sml fas fa-rocket"></i>Deploy Interview</button>
		</div>
		<div class="pad-sml-mob-neg"></div>
		{if !empty($error_messages.new_interviewee)}
			{foreach from=$error_messages.new_interviewee item=message}
				<div class="con-message-failure mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		<div class="theme-secondary pad-sml">
			<p class="tc-black">{$interviewee->getFullName()}</p>
		</div>
		<div class="theme-secondary-light">

		</div>
	</div>
{/block}

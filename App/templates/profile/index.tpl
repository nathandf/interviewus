{extends file="layouts/profile.tpl"}

{block name="profile-head"}
{/block}

{block name="profile-body"}
	{include file="includes/modals/profile/interviewee-modal.tpl"}
	{include file="includes/modals/profile/interview-template-modal.tpl"}
	{include file="includes/modals/profile/interview-deployment-modal.tpl"}
	<div class="con-cnt-xxlrg inner-pad-med">
		<button id="interview-deployment" class="btn btn-inline theme-secondary-dark --modal-trigger"><i aria-hidden="true" class="push-r-sml fas fa-rocket"></i>Deploy Interview</button>
		<button id="interview-template" class="btn btn-inline theme-secondary --modal-trigger"><i aria-hidden="true" class="push-r-sml fa fa-plus"></i>Interview Template</button>
		<button id="interviewee" class="btn btn-inline theme-secondary-light --modal-trigger"><i aria-hidden="true" class="push-r-sml fa fa-plus"></i>Interviewee</button>
		{if !empty($error_messages.new_interviewee)}
			{foreach from=$error_messages.new_interviewee item=message}
				<div class="con-message-failure mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		<div class="push-t-med">
			<div class="theme-primary inner-pad-sml push-t-med">
				<p class="tc-white">Interviews</p>
			</div>
			<div class="theme-tertiary">
				{foreach from=$interviews item=interview}
				<div class="inner-pad-med tc-white">
					<p>{$interview->interviewee->getFullName()}</p>
				</div>
				{foreachelse}
				<div class="inner-pad-sml">
					<p>You don't have any interviews yet!</p>
					{if count( $interviewTemplates ) > 0}
					<button id="interview-deployment" class="btn btn-inline theme-secondary-dark --modal-trigger push-t-sml floatright"><i aria-hidden="true" class="fas fa-rocket push-r-sml"></i>Deploy your first interview</button>
					{else}
					<button id="interview-template" class="btn btn-inline theme-secondary-light --modal-trigger push-t-sml floatright"><i aria-hidden="true" class="fas fa-plus push-r-sml"></i>Create your first interview</button>
					{/if}
					<div class="clear"></div>
				</div>
				{/foreach}
			</div>
		</div>
	</div>

{/block}

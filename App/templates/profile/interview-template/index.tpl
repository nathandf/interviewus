{extends file="layouts/profile.tpl"}

{block name="profile-head"}
{/block}

{block name="profile-body"}
	<div class="con-cnt-xxlrg inner-pad-med">
		<a href="{$HOME}profile/interview-templates/" class="btn btn-inline theme-primary "><i aria-hidden="true" class="push-r-sml fas fa-caret-left"></i>Interview Templates</a>
		<button id="interviews" class="btn btn-inline theme-secondary-dark --modal-trigger"><i aria-hidden="true" class="push-r-sml fas fa-rocket"></i>Deploy Interview</button>
		{if !empty($error_messages.new_interview_template)}
			{foreach from=$error_messages.new_interview_template item=message}
				<div class="con-message-failure mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		<div class="theme-secondary inner-pad-sml push-t-med">
			<p class="tc-black">{$interviewTemplate->name}</p>
		</div>
		<div class="theme-secondary-light">

		</div>
	</div>
	<div class="section-seperator"></div>
{/block}

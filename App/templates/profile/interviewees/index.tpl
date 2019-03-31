{extends file="layouts/profile.tpl"}

{block name="profile-head"}
{/block}

{block name="profile-body"}
	{include file="includes/modals/profile/interviewee-modal.tpl"}
	<div class="con-cnt-xxlrg inner-pad-med">
		<button id="interviewee" class="btn btn-inline theme-secondary --modal-trigger"><i aria-hidden="true" class="push-r-sml fa fa-plus"></i>Interviewee</button>
		{if !empty($error_messages.new_interviewee)}
			{foreach from=$error_messages.new_interviewee item=message}
				<div class="con-message-failure mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		<div class="inner-pad-sml theme-secondary push-t-med">
			<p>Interviewees</p>
		</div>
		{foreach from=$interviewees item=interviewee}
		<div class="inner-pad-med {cycle values='theme-secondary-light,theme-secondary-dark'}">
			{$interviewee->getFullName()}
		</div>
		{foreachelse}
		<div class="inner-pad-med"></div>
		{/foreach}
	</div>
{/block}

{extends file="layouts/profile.tpl"}

{block name="profile-head"}
{/block}

{block name="profile-body"}
	{include file="includes/modals/profile/interviewee-modal.tpl"}
	<div class="con-cnt-xxlrg inner-pad-med">
		<button id="interviewee" class="btn btn-inline theme-secondary-light --modal-trigger"><i aria-hidden="true" class="push-r-sml fa fa-plus"></i>Interviewee</button>
		{if !empty($error_messages.new_interviewee)}
			{foreach from=$error_messages.new_interviewee item=message}
				<div class="con-message-failure mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		<div class="push-t-med">
			<div class="inner-pad-sml theme-secondary-light">
				<p>Interviewees</p>
			</div>
			{foreach from=$interviewees item=interviewee name=interviewee_loop}
			<a href="{$HOME}profile/interviewee/{$interviewee->id}/" class="link-overlay">
				<div class="inner-pad-med {cycle values='bg-light-grey,bg-grey'}">
					<p>{$interviewee->getFullName()}</p>
				</div>
				<div class="clear"></div>
			</a>
			{foreachelse}
			<div class="inner-pad-med"></div>
			{/foreach}
		</div>
	</div>
{/block}

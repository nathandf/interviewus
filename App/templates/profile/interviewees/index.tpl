{extends file="layouts/profile.tpl"}

{block name="profile-head"}
{/block}

{block name="profile-body"}
	{include file="includes/modals/profile/interviewee-modal.tpl"}
	<div class="con-cnt-xxlrg pad-med-mob-neg">
		<div class="pad-sml-mob-pos">
			<button id="interviewee" class="btn btn-inline theme-secondary-light --modal-trigger"><i aria-hidden="true" class="push-r-sml fa fa-plus"></i>Interviewee</button>
		</div>
		<div class="pad-sml-mob-neg"></div>
		{if !empty($error_messages.new_interviewee)}
			{foreach from=$error_messages.new_interviewee item=message}
				<div class="con-message-failure mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		<div class="pad-sml theme-secondary-light">
			<p>Interviewees</p>
		</div>
		{foreach from=$interviewees item=interviewee name=interviewee_loop}
		<a href="{$HOME}profile/interviewee/{$interviewee->id}/" class="link-container">
			<div class="pad-med shade-on-hover {cycle values='bg-light-grey,bg-grey'}">
				<p>{$interviewee->getFullName()}</p>
			</div>
			<div class="clear"></div>
		</a>
		{foreachelse}
		<div class="pad-med"></div>
		{/foreach}
	</div>
{/block}

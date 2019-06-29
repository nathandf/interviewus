{extends file="layouts/profile.tpl"}

{block name="profile-head"}
	<link rel="stylesheet" href="{$HOME}public/css/profile/interviewees.css">
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
		<div class="pad-sml-mob-pos">
			{foreach from=$interviewees item=interviewee name=interviewee_loop}
			<div class="card">
				<div class="pad-sml">
					<p class="thumbnail-med theme-primary-dark floatleft push-r-sml"><i class="fas fa-user"></i></p>
					<div class="floatleft header">
						<a class="header tc-black" href="{$HOME}profile/interviewee/{$interviewee->id}/">{$interviewee->getFullName()}</a>
						<p class="sub-header">{$interviewee->email|truncate:"35"}</p>
					</div>
					<div class="clear"></div>
				</div>
				<div class="divider"></div>
				<div class="pad-xsml">
					<a href="{$HOME}profile/interviewee/{$interviewee->id}/" class="button-text-only action tc-deep-purple">VIEW</a>
				</div>
			</div>
			<div class="pad-sml"></div>
			{foreachelse}
			<div class="interviewee-tag pad-sml border-std bg-white">No Interviewees</div>
			{/foreach}
		</div>
	</div>
{/block}

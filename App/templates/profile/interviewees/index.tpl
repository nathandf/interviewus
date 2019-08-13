{extends file="layouts/profile-with-sidebar.tpl"}

{block name="profile-head"}
	<link rel="stylesheet" href="{$HOME}public/css/profile/interviewees.css">
{/block}

{block name="profile-body"}
	<div class="pad-med-mob-neg">
		<div class="pad-sml-mob-pos">
			<a href="{$HOME}profile/" class="btn btn-inline theme-primary push-r-sml"><i aria-hidden="true" class="fas fa-home"></i></a>
			<button id="interviewee" class="btn btn-inline theme-primary-dark --modal-trigger"><i aria-hidden="true" class="push-r-sml fa fa-plus"></i>Interviewee</button>
		</div>
		<div class="pad-sml-mob-neg"></div>
		{if !empty($error_messages.new_interviewee)}
			{foreach from=$error_messages.new_interviewee item=message}
				<div class="con-message-error mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		<div class="con-cnt-lrg pad-sml-mob-pos floatleft">
			{foreach from=$interviewees item=interviewee name=interviewee_loop}
			<div class="card">
				<div class="pad-sml">
					<p class="thumbnail-med theme-primary floatleft push-r-sml"><i class="fas fa-user"></i></p>
					<div class="floatleft header">
						<a class="header tc-black" href="{$HOME}profile/interviewee/{$interviewee->id}/">{$interviewee->getFullName()}</a>
						<p class="sub-header">{$interviewee->email|truncate:"35"}</p>
						<p class="sub-header">{$interviewee->phone->getNiceNumber()}</p>
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
			<p>No interviewees to show</p>
			{/foreach}
		</div>
		<div class="clear"></div>
	</div>
{/block}

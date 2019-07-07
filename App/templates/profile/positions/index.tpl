{extends file="layouts/profile.tpl"}

{block name="profile-head"}
{/block}

{block name="profile-body"}
	{include file="includes/modals/profile/position-modal.tpl"}
	<div class="con-cnt-xxlrg pad-med-mob-neg">
		<div class="pad-sml-mob-pos">
			<button id="position" class="btn btn-inline theme-secondary --modal-trigger"><i aria-hidden="true" class="push-r-sml fas fa-plus"></i>Position</button>
		</div>
		<div class="pad-sml-mob-neg"></div>
		{if !empty($error_messages.new_position)}
			{foreach from=$error_messages.new_position item=message}
				<div class="con-message-failure mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		<div class="con-cnt-lrg pad-sml-mob-pos floatleft">
		{foreach from=$positions item=position name=positions_loop}
			<div class="card">
				<div class="pad-sml">
					<p class="thumbnail-med theme-secondary-light floatleft push-r-sml"><i class="fas fa-user-tie"></i></p>
					<div class="floatleft header">
						<a class="header tc-black" href="{$HOME}profile/position/{$position->id}/">{$position->name|truncate:"27"}</a>
						<p class="sub-header">{$position->description|truncate:"40"}</p>
					</div>
					<div class="clear"></div>
				</div>
				<div class="divider"></div>
				<div class="pad-xsml">
					<a href="{$HOME}profile/position/{$position->id}/" class="button-text-only action tc-deep-purple">EDIT</a>
				</div>
			</div>
			<div class="pad-sml"></div>
		{/foreach}
		</div>
		<div class="clear"></div>
	</div>
{/block}

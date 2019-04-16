{extends file="layouts/profile.tpl"}

{block name="profile-head"}
{/block}

{block name="profile-body"}
	{include file="includes/modals/profile/position-modal.tpl"}
	<div class="con-cnt-xxlrg pad-med">
		<button id="position" class="btn btn-inline theme-primary-light --modal-trigger"><i aria-hidden="true" class="push-r-sml fa fa-plus"></i>Position</button>
		{if !empty($error_messages.new_position)}
			{foreach from=$error_messages.new_position item=message}
				<div class="con-message-failure mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		<div class="push-t-med">
			<div class="pad-sml theme-primary-light">
				<p>Positions</p>
			</div>
			{foreach from=$positions item=position name=position_loop}
			<a href="{$HOME}profile/position/{$position->id}/" class="link-container">
				<div class="pad-med shade-on-hover {cycle values='bg-light-grey,bg-grey'}">
					<p class="text-med-heavy">{$position->name}</p>
					{if !is_null( $position->description )}
					<p class="text-med">{$position->description}</p>
					{/if}
				</div>
				<div class="clear"></div>
			</a>
			{foreachelse}
			<div class="pad-med"></div>
			{/foreach}
		</div>
	</div>
{/block}

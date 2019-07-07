{extends file="layouts/profile.tpl"}

{block name="profile-head"}
	<script src="{$HOME}{$JS_SCRIPTS}profile/position.js"></script>
{/block}

{block name="profile-body"}
	<div class="con-cnt-xxlrg pad-med-mob-neg">
		<div class="pad-sml-mob-pos">
			<a href="{$HOME}profile/positions/" class="btn btn-inline theme-secondary-light"><i aria-hidden="true" class="push-r-sml fas fa-caret-left"></i>Positions</a>
		</div>
		<div class="pad-sml-mob-neg"></div>
		{if !empty($error_messages.update_position)}
			{foreach from=$error_messages.update_position item=message}
				<div class="con-message-failure mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		{include file="includes/snippets/flash-messages.tpl"}
		<div class="con-cnt-lrg pad-sml-mob-pos floatleft">
			<form action="" method="post">
				<input type="hidden" name="token" value="{$csrf_token}">
				<input type="hidden" name="update_position" value="{$csrf_token}">
				<p class="label">Name:</p>
				<input type="text" class="inp inp-full property" name="name" value="{$position->name}">
				<p class="label">Description:</p>
				<textarea name="description" class="inp textarea inp-full property">{$position->description|default:null}</textarea>
				<div class="hr-full"></div>
				<div class="pad-sml-mob-pos">
					<button type="submit" class="btn btn-inline --update-position-button theme-secondary floatleft" disabled="disabled">Update</button>
					<div class="clear"></div>
				</div>
			</form>
		</div>
		<div class="clear"></div>
	</div>
{/block}

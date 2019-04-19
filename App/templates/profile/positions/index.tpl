{extends file="layouts/profile.tpl"}

{block name="profile-head"}
{/block}

{block name="profile-body"}
	{include file="includes/modals/profile/position-modal.tpl"}
	<div class="con-cnt-xxlrg pad-med-mob-neg">
		<div class="pad-sml-mob-pos">
			<button id="position" class="btn btn-inline theme-primary-light --modal-trigger"><i aria-hidden="true" class="push-r-sml fa fa-plus"></i>Position</button>
		</div>
		<div class="pad-sml-mob-neg"></div>
		{if !empty($error_messages.new_position)}
			{foreach from=$error_messages.new_position item=message}
				<div class="con-message-failure mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		<table class="col-100 text-center mat-box-shadow" style="border-collapse: separate; table-layout: auto;">
			<th class="theme-primary-light pad-sml" colspan="2">Positions</th>
			<tr>
				<td class="theme-primary pad-sml text-sml-heavy">Name</td>
				<td class="theme-primary pad-sml text-sml-heavy">Description</td>
			</tr>
			{foreach from=$positions item=position name=position_loop}
			<tr class="bg-white shade-on-hover row-link" data-href="{$HOME}profile/position/{$position->id}/">
				<td class="pad-sml text-med-heavy text-left">{$position->name}</td>
				<td class="pad-sml text-med-heavy text-left">{$position->description|default:"<i>No description</i>"|truncate:"300":"..."}</td>
			</tr>
			{foreachelse}
			<tr>
				<td class="pad-sml text-med-heavy">No Positions</td>
			</tr>
			{/foreach}
		</table>
	</div>
{/block}

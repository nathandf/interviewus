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
		<table class="col-100 text-center mat-box-shadow" style="border-collapse: separate; table-layout: auto;">
			<th class="theme-secondary pad-sml" colspan="1">Interviewees</th>
			<tr>
				<td class="theme-secondary-light pad-sml text-sml-heavy">Name</td>
			</tr>
			{foreach from=$interviewees item=interviewee name=interviewee_loop}
			<tr class="bg-white shade-on-hover row-link" data-href="{$HOME}profile/interviewee/{$interviewee->id}/">
				<td class="pad-sml text-med-heavy text-left">{$interviewee->getFullName()}</td>
			</tr>
			{foreachelse}
			<tr>
				<td class="pad-sml text-med-heavy"><i>No Interviewees</i></td>
			</tr>
			{/foreach}
		</table>
	</div>
{/block}

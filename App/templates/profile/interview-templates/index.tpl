{extends file="layouts/profile.tpl"}

{block name="profile-head"}
{/block}

{block name="profile-body"}
	{include file="includes/modals/profile/interview-template-modal.tpl"}
	<div class="con-cnt-xxlrg pad-med-mob-neg">
		<div class="pad-sml-mob-pos">
			<button id="interview-template" class="btn btn-inline theme-secondary --modal-trigger"><i aria-hidden="true" class="push-r-sml fas fa-plus"></i>Interview Template</button>
			<a href="{$HOME}profile/interview-templates/browse" class="btn btn-inline theme-primary"><i aria-hidden="true" class="push-r-sml fas fa-search"></i>Browse</a>
		</div>
		<div class="pad-sml-mob-neg"></div>
		{if !empty($error_messages.new_interview_template)}
			{foreach from=$error_messages.new_interview_template item=message}
				<div class="con-message-failure mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		<table class="col-100 text-center mat-box-shadow" style="border-collapse: separate; table-layout: auto;">
			<th class="theme-primary pad-sml" colspan="2">Interview Templates</th>
			<tr>
				<td class="theme-primary-light pad-sml text-sml-heavy">Name</td>
				<td class="theme-primary-light pad-sml text-sml-heavy">Description</td>
			</tr>
			{foreach from=$interviewTemplates item=interviewTemplate name=interview_templates_loop}
			<tr class="bg-white shade-on-hover row-link" data-href="{$HOME}profile/interview-template/{$interviewTemplate->id}/">
				<td class="pad-sml text-med-heavy text-left">{$interviewTemplate->name}</td>
				<td class="pad-sml text-med-heavy text-left">{$interviewTemplate->description|default:"<i>No description</i>"|truncate:"300":"..."}</td>
			</tr>
			{foreachelse}
			<tr>
				<td class="pad-sml text-med-heavy"><i>No Interview Templates</i></td>
			</tr>
			{/foreach}
		</table>
	</div>
{/block}

{extends file="layouts/profile.tpl"}

{block name="profile-head"}
{/block}

{block name="profile-body"}
	{include file="includes/modals/profile/interviewee-modal.tpl"}
	{include file="includes/modals/profile/interview-template-modal.tpl"}
	{include file="includes/modals/profile/interview-deployment-modal.tpl"}
	<div class="con-cnt-xxlrg pad-med-mob-neg">
		<div class="pad-sml-mob-pos">
			<button id="interview-deployment" class="btn btn-inline theme-secondary-dark --modal-trigger push-t-sml"><i aria-hidden="true" class="push-r-sml fas fa-rocket"></i>Deploy Interview</button>
			<button id="interview-template" class="btn btn-inline theme-secondary --modal-trigger push-t-sml"><i aria-hidden="true" class="push-r-sml fa fa-plus"></i>Interview Template</button>
			<button id="interviewee" class="btn btn-inline theme-secondary-light --modal-trigger push-t-sml"><i aria-hidden="true" class="push-r-sml fa fa-plus"></i>Interviewee</button>
		</div>
		<div class="pad-sml-mob-neg"></div>
		{if !empty($error_messages.deploy_interview)}
			{foreach from=$error_messages.deploy_interview item=message}
				<div class="con-message-failure mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		{if !empty($error_messages.new_interviewee)}
			{foreach from=$error_messages.new_interviewee item=message}
				<div class="con-message-failure mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		{foreach from=$interviews item=interview name="fe_interviews"}
		{if $smarty.foreach.fe_interviews.first}
		<table class="col-100 text-center mat-box-shadow" style="border-collapse: separate; table-layout: auto;">
			<th class="theme-primary" colspan="4">Interviews</th>
			<tr style="border-bottom: 1px solid #DDDDDD;">
				<td class="text-sml-heavy theme-primary-light">Interviewee</td>
				<td class="text-sml-heavy theme-primary-light">Type</td>
				<td class="text-sml-heavy theme-primary-light">Status</td>
				<td class="text-sml-heavy theme-primary-light"></td>
			</tr>
		{/if}
			<tr class="cursor-pt bg-white shade-on-hover" style="border-bottom: 1px solid #DDDDDD;">
				<td class="bg-white text-med-heavy">{$interview->interviewee->getFullName()}</td>
				<td class="bg-white text-med-heavy">{if $interview->deployment_type_id == 1}SMS{else}Web{/if}</td>
				<td class="bg-white text-med-heavy">{ucfirst( $interview->status )}</td>
				<td class="bg-white text-med-heavy shade-on-hover"><div class="pad-xsml"><i class="fas fa-chevron-right"></i></div></td>
			</tr>
		{if $smarty.foreach.fe_interviews.last}
		</table>
		{/if}
		{foreachelse}
		<div class="theme-primary pad-sml">
			<p class="tc-white">Interviews</p>
		</div>
		<div class="pad-sml">
			<p>You don't have any interviews yet!</p>
			{if count( $interviewTemplates ) > 0}
			<button id="interview-deployment" class="btn btn-inline theme-secondary-dark --modal-trigger push-t-sml floatright"><i aria-hidden="true" class="fas fa-rocket push-r-sml"></i>Deploy your first interview</button>
			{else}
			<button id="interview-template" class="btn btn-inline theme-secondary-light --modal-trigger push-t-sml floatright"><i aria-hidden="true" class="fas fa-plus push-r-sml"></i>Create your first interview</button>
			{/if}
			<div class="clear"></div>
		</div>
		{/foreach}
		<div class="section-seperator"></div>
	</div>
{/block}

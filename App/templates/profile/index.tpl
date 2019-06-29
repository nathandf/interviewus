{extends file="layouts/profile.tpl"}

{block name="profile-head"}
{/block}

{block name="profile-body"}
	{include file="includes/modals/profile/interviewee-modal.tpl"}
	{include file="includes/modals/profile/interview-template-modal.tpl"}
	{include file="includes/modals/profile/interview-deployment-modal.tpl"}
	<div class="con-cnt-xxlrg pad-med-mob-neg">
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
		<div class="pad-sml-mob-pos">
			<div class="floatleft push-r-xsml">
				<button id="interview-deployment" class="btn btn-inline theme-secondary-dark --modal-trigger"><i class="push-r-sml fas fa-rocket"></i>Deploy Interview</button>
				<div class="pad-xxsml-mob-pos"></div>
				<div class="clear"></div>
			</div>
			<div class="floatleft push-r-xsml">
				<button id="interview-template" class="btn btn-inline theme-secondary --modal-trigger"><i aria-hidden="true" class="push-r-sml fa fa-plus"></i>Template</button>
				<div class="pad-xxsml-mob-pos"></div>
				<div class="clear"></div>
			</div>
			<div class="floatleft">
				<button id="interviewee" class="btn btn-inline theme-primary-dark --modal-trigger"><i class="push-r-sml fa fa-plus"></i>Interviewee</button>
				<div class="pad-xxsml-mob-pos"></div>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
		</div>
		{include file="includes/snippets/flash-messages.tpl"}
		<div class="pad-sml-mob-pos">
			<div class="pad-sml-mob-neg"></div>
			<div class="account-details-inner-container">
				<div class="account-details-tag adt-first">
					<div class="account-details-icon tc-teal">
						<i class="fas fa-2x fa-sms"></i>
					</div>
					<div class="account-details">
						<p class="text-lrg-heavy text-center">{$account->sms_interviews}</p>
						<p class="text-sml text-center">SMS interviews</p>
					</div>
					<div class="clear"></div>
				</div>
				<div class="account-details-tag adt-last">
					<div class="account-details-icon tc-teal">
						<i class="fas fa-2x fa-globe"></i>
					</div>
					<div class="account-details">
						<p class="text-lrg-heavy text-center">{if $account->web_interviews < 0}Unlimited{else}{$account->web_interviews}{/if}</p>
						<p class="text-sml text-center">Web interviews</p>
					</div>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</div>
		</div>
		<div class="pad-sml-mob-neg"></div>
		<table class="col-100 text-center mat-box-shadow interviews-table" style="border-collapse: separate; table-layout: auto;">
			<th class="theme-primary pad-sml" colspan="3">Interviews</th>
			<tr>
				<td class="text-sml-heavy theme-primary-light pad-sml">Interviewee</td>
				<td class="text-sml-heavy theme-primary-light pad-sml">Type</td>
				<td class="text-sml-heavy theme-primary-light pad-sml">Status</td>
			</tr>
			{foreach from=$interviews item=interview name="fe_interviews"}
			<tr id="interview-{$interview->id}" data-id="{$interview->id}" class="bg-white shade-on-hover cursor-pt --c-interview-details">
				<td class="text-med-heavy">{$interview->interviewee->getFullName()}</td>
				<td class="text-med-heavy pad-sml">{if $interview->deployment_type_id == 1}SMS{else}Web{/if}</td>
				<td class="pad-sml" style="max-width: 100px;">
					<div class="status-indicator status-{$interview->status}">
						<p>{ucfirst( $interview->status )}</p>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<div id="interview-details-{$interview->id}" class="interview-details" style="display: none;">
						<table class="col-100 text-center" style="border-collapse: separate; table-layout: auto;">
							<th class="theme-secondary pad-sml" colspan="3">Interview Details</th>
							<tr>
								<td class="theme-secondary-light pad-sml text-sml-heavy">Question</td>
								<td class="theme-secondary-light pad-sml text-sml-heavy">Status</td>
								<td class="theme-secondary-light pad-sml text-sml-heavy">Answer</td>
							</tr>
							{foreach from=$interview->questions item=question name=fe_questions}
							<tr class="bg-white">
								<td class="text-left pad-sml text-med-heavy"><p class="text-med-heavy">{$smarty.foreach.fe_questions.iteration}.<span class="push-l-sml">{$question->body}</span></p></td>
								<td class="pad-sml text-med"><i>{$question->sms_status|default:"pending"}</i></p></td></td>
								<td class="text-left pad-sml text-med-heavy text-breakable"><i>{$question->answer->body|default:"Not Answered"}</i></p></td></td>
							</tr>
							{foreachelse}
							<tr class="bg-white">
								<td class="text-left pad-sml text-med-heavy">No Questions</td>
								<td></td>
							</tr>
							{/foreach}
						</table>
						<table class="col-100 text-center" style="border-collapse: separate; table-layout: auto;">
							<th class="theme-secondary text-left pad-sml" colspan="3">Interview URL</th>
							<tr>
								<td colspan="3" class="pad-sml text-sml text-left bg-white"><span class="text-breakable">https://www.interviewus.net/i/{$interview->token}/</span></td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
			{foreachelse}
			<tr class="bg-white">
				<td class="text-med-heavy">No Interviews</td>
				<td class="text-med-heavy pad-sml">--</td>
				<td class="pad-sml">--</td>
			</tr>
			{/foreach}
		<table>
	</div>
{/block}

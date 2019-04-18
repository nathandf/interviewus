{extends file="layouts/profile.tpl"}

{block name="profile-head"}
{/block}

{block name="profile-body"}
	{include file="includes/modals/profile/interviewee-modal.tpl"}
	{include file="includes/modals/profile/interview-template-modal.tpl"}
	{include file="includes/modals/profile/interview-deployment-modal.tpl"}
	<div class="con-cnt-xxlrg pad-med-mob-neg">
		<div class="pad-sml-mob-pos">
			<div class="floatleft push-r-xsml">
				<button id="interview-deployment" class="btn btn-inline theme-secondary-dark --modal-trigger"><i class="push-r-sml fas fa-rocket"></i>Deploy Interview</button>
				<div class="pad-xxsml-mob-pos"></div>
				<div class="clear"></div>
			</div>
			<div class="floatleft push-r-xsml">
				<button id="interview-template" class="btn btn-inline theme-secondary --modal-trigger"><i aria-hidden="true" class="push-r-sml fa fa-plus"></i>Interview Template</button>
				<div class="pad-xxsml-mob-pos"></div>
				<div class="clear"></div>
			</div>
			<div class="floatleft">
				<button id="interviewee" class="btn btn-inline theme-secondary-light --modal-trigger"><i class="push-r-sml fa fa-plus"></i>Interviewee</button>
				<div class="pad-xxsml-mob-pos"></div>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
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
		<div id="interview-details-{$interview->id}" class="interview-details slideable" style="display: none;">
			<table class="col-100 text-center mat-box-shadow" style="border-collapse: separate; table-layout: auto;">
				<th class="theme-secondary text-left" colspan="3"><a href="#interview-{$interview->id}" data-id="{$interview->id}" class="cursor-pt --c-interview-table"><i class="fas fa-chevron-left no-deco push-r-med tc-black pad-sml"></i></a><span>{$interview->interviewee->getFullName()}</span></th>
				<tr>
					<td class="theme-secondary-light pad-sml text-sml-heavy">Postion</td>
					<td class="theme-secondary-light pad-sml text-sml-heavy">Type</td>
					<td class="theme-secondary-light pad-sml text-sml-heavy">Status</td>
				</tr>
				<tr>
					<td class="bg-white pad-sml text-sml">{$interview->position->name}</td>
					<td class="bg-white pad-sml text-sml">{if $interview->deployment_type_id == 1}SMS{else}Web{/if}</td>
					<td class="bg-white pad-sml text-sml">{ucfirst( $interview->status )}</td>
				</tr>
			</table>
			<table class="col-100 text-center mat-box-shadow push-t-med" style="border-collapse: separate; table-layout: auto;">
				<th class="theme-primary pad-sml" colspan="1">Questions</th>
				{foreach from=$interview->questions item=question name=fe_questions}
				<tr>
					<td class="text-left">
						<div class="pad-sml bg-white">
							<p class="text-lrg-heavy">Q{$smarty.foreach.fe_questions.iteration}.<span class="push-l-sml">{$question->body}</span></p>
							<div class="push-t-sml push-b-sml"></div>
							<p class=""><span class="text-lrg-heavy">A: </span><i>{$question->answer->body|default:"Not Answered"}</i></p>
						</div>
					</td>
				</tr>
				{/foreach}
			</table>
		</div>
		{/foreach}
		{foreach from=$interviews item=interview name="fe_interviews"}
		{if $smarty.foreach.fe_interviews.first}
		<table class="col-100 text-center mat-box-shadow interviews-table slideable" style="border-collapse: separate; table-layout: auto;">
			<th class="theme-primary pad-sml" colspan="4">Interviews</th>
			<tr>
				<td class="text-sml-heavy theme-primary-light pad-sml">Interviewee</td>
				<td class="text-sml-heavy theme-primary-light pad-sml">Type</td>
				<td class="text-sml-heavy theme-primary-light pad-sml">Status</td>
				<td class="text-sml-heavy theme-primary-light pad-sml"></td>
			</tr>
		{/if}
			<tr id="interview-{$interview->id}" data-id="{$interview->id}" class="bg-white shade-on-hover cursor-pt --c-interview-details">
				<td class="text-med-heavy">{$interview->interviewee->getFullName()}</td>
				<td class="text-med-heavy pad-sml">{if $interview->deployment_type_id == 1}SMS{else}Web{/if}</td>
				<td class="text-med-heavy pad-sml">{ucfirst( $interview->status )}</td>
				<td class="text-med-heavy">
					<div class="tc-black link">
						<div class="pad-sml"><i class="fas fa-chevron-right"></i></div>
					</div>
				</td>
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
	</div>
{/block}

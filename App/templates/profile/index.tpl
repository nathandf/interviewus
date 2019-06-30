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
		<div class="con-cnt-lrg pad-sml-mob-pos floatleft">
			<table class="col-100 text-center interviews-table" style="border-collapse: separate; table-layout: auto;">
				<th class="theme-primary pad-sml" colspan="3">Interviews</th>
			</table>
			{foreach from=$interviews item=interview}
			<div class="card interview-card push-t-sml">
				<div class="pad-sml">
					<div class="floatleft push-r-med">
						{if $interview->deployment_type_id == 1}
						<div class="thumbnail-med theme-primary-dark">
							<i class="far fa-comment"></i>
						</div>
						{else}
						<div class="thumbnail-med theme-primary">
							<i class="fa fa-globe"></i>
						</div>
						{/if}
						<div class="clear"></div>
						<div class="pad-xsml">
							<p class="text-center text-xsml-heavy">{strtoupper( $interview->deploymentType->name )}</p>
						</div>
					</div>
					<div class="floatleft">
						<a href="{$HOME}profile/interviewee/{$interview->interviewee->id}/" class="header push-r-sml">{$interview->interviewee->getFullName()|truncate:"30"}</a>
						<p class="sub-header">{$interview->position->name}</p>
						<div class="progress-bar">
							{foreach from=$interview->questions item=question name=questions_loop}
							<div class="progress-increment floatleft{if !is_null( $question->answer )} status-complete{/if}" style="width: {(1/count($interview->questions))*100}%;">

							</div>
							{/foreach}
							<div class="clear"></div>
						</div>
						<div class="clear"></div>
					</div>
					<div class="status-indicator status-{$interview->status} floatright">
						<p>{ucfirst( $interview->status )}</p>
					</div>
					<div class="clear"></div>
				</div>
				<div class="interview-details-{$interview->id} expandable-content" style="display: none;">
					<div class="divider"></div>
					<div class="pad-sml">
						{foreach from=$interview->questions item=question name=questions_loop}
						<div>
							<p class="label" style="color: #222222;">Question {$smarty.foreach.questions_loop.iteration}:</p>
							<p class="text-lrg">{$question->body}</p>
							<p class="label push-t-sml" style="color: #222222;">Answer:</p>
							<p class="text-lrg">{$question->answer->body|default:"Not answered"}</p>
						</div>
						{if !$smarty.foreach.questions_loop.last}
						<div class="hr-full"></div>
						{/if}
						{foreachelse}
						<p>There are not questions for this interview</p>
						{/foreach}
					</div>
				</div>
				<div class="divider"></div>
				<div class="pad-xsml">
					<button data-interview_id="{$interview->id}" class="button-text-only action tc-deep-purple --expand">EXPAND</button>
					<button class="button-text-only action icon floatright"><i class="fas fa-envelope"></i></button>
					<button class="button-text-only action icon floatright"><i class="fas fa-share-alt"></i></button>
					<div class="clear"></div>
				</div>
			</div>
			{foreachelse}
			<p>No interviews yet.</p>
			{/foreach}
		</div>
		<div class="clear"></div>
	</div>
{/block}

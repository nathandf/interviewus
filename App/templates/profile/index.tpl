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
			<div class="theme-primary pad-sml">
				<p class="text-center text-xlrg-heavy">Interviews</p>
			</div>
			{foreach from=$interviews item=interview}
			<div class="card interview-card push-t-sml">
				<div class="pad-sml">
					<div class="floatleft push-r-med">
						{if $interview->deployment_type_id == 1}
						<div class="thumbnail-med sms-theme">
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
						<div class="progress-container">
							<div class="progress-bar floatleft push-r-sml">
								{assign var="total_answers" value=0}
								{foreach from=$interview->questions item=question name=qa_progress_bar}
									{assign var="width" value=(1/count($interview->questions))*100}
									{if !is_null( $question->answer ) && $smarty.foreach.qa_progress_bar.last}{assign var="width" value=( 1/count( $interview->questions ) ) * 100}{/if}

									<div class="progress-increment {if !is_null( $question->answer )}{assign var='total_answers' value=$total_answers + 1 } status-complete floatleft{else}floatright{/if}" style='width: {$width}%;'></div>
								{/foreach}
								<div class="clear"></div>
							</div>
							<div class="question-count floatleft">
								<p class="sub-header">{$total_answers}/{count( $interview->questions )}</p>
							</div>
							<div class="clear"></div>
						</div>
					</div>
					<div class="status-indicator status-{$interview->status} floatright">
						<p>{ucfirst( $interview->status )}</p>
					</div>
					<div class="clear"></div>
				</div>
				<div class="interview-details-{$interview->id} expandable-content" style="display: none;">
					<div class="divider"></div>
					<div class="theme-secondary pad-sml">
						<p class="text-center text-xlrg-heavy">Interviews Details</p>
					</div>
					<div class="pad-sml">
						<p class="label text-breakable">URL:&nbsp;https://www.interviewus.net/i/{$interview->token}/</p>
					</div>
					<div class="divider"></div>
					<div class="pad-sml">
						{foreach from=$interview->questions item=question name=questions_loop}
						<div class="qa-container">
							<div class="question-answer pad-xsml">
								<p class="label" style="color: #222222;">Question {$smarty.foreach.questions_loop.iteration}:</p>
								<p class="text-lrg">{$question->body}</p>
							</div>
							<div class="question-answer pad-xsml">
								<p class="label" style="color: #222222;">Answer:</p>
								<p class="text-lrg">{$question->answer->body|default:"<i>Not answered</i>"}</p>
							</div>
							<div class="clear"></div>
						</div>
						{if !$smarty.foreach.questions_loop.last}
						<div class="hr-full"></div>
						{/if}
						{foreachelse}
						<p>There are no questions for this interview</p>
						{/foreach}
					</div>
				</div>
				<div class="divider"></div>
				<div class="pad-xsml">
					<button data-interview_id="{$interview->id}" class="button-text-only action tc-deep-purple --expand">EXPAND</button>
					<button class="button-text-only action icon floatright tooltip-icon" title="Send interview via email"><i class="fas fa-envelope"></i></button>
					<button class="button-text-only action icon floatright tooltip-icon" title="Share interview"><i class="fas fa-share-alt"></i></button>
					<button class="button-text-only action icon floatright tooltip-icon" title="Archive interview"><i class="fas fa-archive"></i></button>
					<div class="floatright">
						<form action="{$HOME}downloads/interviewCSV" method="post">
							<input type="hidden" name="token" value="{$csrf_token}">
							<input type="hidden" name="account_id" value="{$account->id}">
							<input type="hidden" name="user_id" value="{$user->id}">
							<input type="hidden" name="organization_id" value="{$organization->id}">
							<input type="hidden" name="interview_id" value="{$interview->id}">
							<button type="submit" class="button-text-only action icon tooltip-icon" title="Download CSV File"><i class="fas fa-download"></i></button>
						</form>
					</div>
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

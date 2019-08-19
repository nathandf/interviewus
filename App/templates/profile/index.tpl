{extends file="layouts/profile-with-sidebar.tpl"}

{block name="profile-head"}
{/block}

{block name="profile-body"}
	<div class="pad-med-mob-neg">
		{if !empty($error_messages.change_organization)}
			{foreach from=$error_messages.change_organization item=message}
				<div class="con-message-error mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		{if !empty($error_messages.deploy_interview)}
			{foreach from=$error_messages.deploy_interview item=message}
				<div class="con-message-error mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		{if !empty($error_messages.new_interviewee)}
			{foreach from=$error_messages.new_interviewee item=message}
				<div class="con-message-error mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		<div class="pad-sml-mob-pos">
			<div class="floatleft push-r-xsml">
				<button id="interview-deployment" class="btn btn-inline theme-secondary-dark --modal-trigger"><i class="fas fa-rocket"></i></button>
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
					<div class="account-details-icon sms">
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
		<div class="pad-sml-mob-pos content">
			<div class="theme-primary pad-sml">
				<p class="text-center text-xlrg-heavy">Interviews</p>
			</div>
			{foreach from=$interviews item=interview}
			<div id="interview-{$interview->id}" class="card interview-card push-t-sml">
				<div class="pad-sml">
					<div class="floatleft push-r-med">
						{if $interview->deployment_type_id == 1}
						<div class="thumbnail-med sms-theme">
							<i class="far fa-comment"></i>
						</div>
						{else}
						<div class="thumbnail-med tc-white bg-teal">
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
					<div class="theme-secondary pad-sml"></div>
					<div class="pad-sml">
						<p class="label text-breakable text-sml">URL:&nbsp;https://www.interviewus.net/i/{$interview->token}/</p>
					</div>
					<div class="divider"></div>
					<div class="pad-sml">
						{foreach from=$interview->questions item=question name=questions_loop}
						<div class="qa-container">
							<div class="question theme-secondary floatleft">
								<p >{$question->body}</p>
							</div>
							<div class="clear"></div>
							<div class="answer theme-primary floatright">
								<p>{$question->answer->body|default:"<i>Not answered</i>"}</p>
							</div>
							<div class="clear"></div>
						</div>
						{foreachelse}
						<p>There are no questions for this interview</p>
						{/foreach}
					</div>
				</div>
				<div class="divider"></div>
				<div class="pad-xsml">
					<button data-interview_id="{$interview->id}" class="button-text-only action tc-deep-purple --expand">EXPAND</button>
					<button id="share-interview" data-interview_id="{$interview->id}" class="share-interview-button button-text-only action icon floatright tooltip-icon --modal-trigger" title="Share"><i class="fas fa-share-alt"></i></button>
					<div class="floatright">
						<form class="archive-form" action="archive" method="post">
							<input type="hidden" name="token" value="{$csrf_token}">
							<input type="hidden" name="interview_id" value="{$interview->id}">
							<button type="submit" class="button-text-only action icon tooltip-icon" title="Archive"><i class="fas fa-archive"></i></button>
						</form>
					</div>
					<div class="floatright">
						<form action="{$HOME}downloads/interviewCSV" method="post">
							<input type="hidden" name="token" value="{$csrf_token}">
							<input type="hidden" name="account_id" value="{$account->id}">
							<input type="hidden" name="user_id" value="{$user->id}">
							<input type="hidden" name="organization_id" value="{$organization->id}">
							<input type="hidden" name="interview_id" value="{$interview->id}">
							<button type="submit" class="button-text-only action icon tooltip-icon" title="Download as CSV"><i class="fas fa-download"></i></button>
						</form>
					</div>
					<div class="clear"></div>
				</div>
			</div>
			{foreachelse}
			<div class="card interview-card push-t-sml">
				<div class="pad-sml">
					<p class="header">No interviews to show</p>
				</div>
				<div class="divider"></div>
				<div class="pad-xsml">
					<button id="interview-deployment" class="button-text-only icon floatright --modal-trigger action"><i class="fas fa-rocket push-r-sml"></i>Deploy an interview</button>
					<div class="clear"></div>
				</div>
			</div>
			{/foreach}
		</div>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
{/block}

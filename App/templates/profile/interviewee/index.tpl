{extends file="layouts/profile-with-sidebar.tpl"}

{block name="profile-head"}
	<link rel="stylesheet" href="{$HOME}public/css/profile/interviewee.css">
{/block}

{block name="profile-body"}
	{include file="includes/modals/profile/interview-deployment-modal.tpl"}
	{include file="includes/modals/profile/interviewee/interviewee-details.tpl"}
	<div class="pad-med-mob-neg">
		<div class="pad-sml-mob-pos">
			<a href="{$HOME}profile/" class="btn btn-inline theme-primary push-r-sml"><i aria-hidden="true" class="fas fa-home"></i></a>
			<a href="{$HOME}profile/interviewees/" class="btn btn-inline theme-primary "><i aria-hidden="true" class="push-r-sml fas fa-caret-left"></i>Interviewees</a>
		</div>
		<div class="pad-sml-mob-neg"></div>
		{include file="includes/snippets/flash-messages.tpl"}
		{if !empty($error_messages.deploy_interview)}
			{foreach from=$error_messages.deploy_interview item=message}
				<div class="con-message-error mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		{if !empty($error_messages.update_interviewee)}
			{foreach from=$error_messages.update_interviewee item=message}
				<div class="con-message-error mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		<div class="con-cnt-lrg pad-sml-mob-pos floatleft">
			<div class="interviewee-header card">
				<div class="pad-sml">
					<div class="thumbnail-lrg floatleft theme-primary push-r-med"><i class="fas fa-user"></i></div>
					<div class="floatleft details">
						<p class="name">{$interviewee->getFullName()|truncate:"30"}</p>
						<p class="sub-header">{$interviewee->email|truncate:"30"}</p>
						<p class="sub-header">{$interviewee->phone->getNiceNumber()}</p>
					</div>
					<div class="clear"></div>
				</div>
				<div class="divider"></div>
				<div class="pad-xsml">
					<button id="interviewee-details" class="button-text-only tc-deep-purple --modal-trigger action">UPDATE</button>
					<button id="interview-deployment" class="button-text-only icon floatright tc-deep-purple --modal-trigger action"><i class="fas fa-rocket"></i></button>
					<div class="clear"></div>
				</div>
			</div>
			<div class="theme-primary pad-sml push-t-med">
				<p class="text-center text-xlrg-heavy">Interviews</p>
			</div>
			<div class="interviews">
				{foreach from=$interviewee->interviews item=interview}
				<div id="interview-{$interview->id}" class="card interview-card push-t-sml">
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
								{if $interview->mode == "archived"}<p class="text-center"><i class="fas text-sml fa-archive tc-dark-grey tooltip" title="This interview has been archived"></i></p>{/if}
							</div>
						</div>
						<div class="floatleft">
							<a href="{$HOME}profile/interviewee/{$interviewee->id}/" class="header push-r-sml">{$interview->position->name|truncate:"30"}</a>
							<p class="sub-header"><b class="tc-black text-med push-r-sml">Start:</b> {$interview->start_time|default:"Not started"}</p>
							<p class="sub-header"><b class="tc-black text-med push-r-sml">End:</b> {$interview->end_time|default:"<i>pending</i>"}</p>
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
						<button id="share-interview" data-interview_id="{$interview->id}" class="share-interview-button button-text-only action icon floatright tooltip-icon --modal-trigger" title="Share"><i class="fas fa-share-alt"></i></button>
						<div class="floatright">
							<form class="archive-form" action="{$HOME}profile/archive" method="post">
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
	</div>
{/block}

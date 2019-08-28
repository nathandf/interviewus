<div class="theme-primary pad-sml">
	<p class="text-center text-xlrg-heavy">Interviews</p>
</div>
{if isset( $interviews )}
	{foreach from=$interviews item=interview}
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
				{if isset( $interview->context )}
					{if $interview->context == "interview"}
					<a href="{$HOME}profile/interviewee/{$interview->interviewee->id}/" class="header push-r-sml">{$interview->interviewee->getFullName()|truncate:"30"}</a>
					<p class="sub-header">{$interview->position->name}</p>
					{elseif $interview->context == "position"}
					<a href="{$HOME}profile/interviewee/{$interview->interviewee->id}/" class="header push-r-sml">{$interview->interviewee->getFullName()|truncate:"30"}</a>
					<p class="sub-header">{$interview->position->name}</p>
					{elseif $interview->context == "interviewee"}
					<a href="{$HOME}profile/position/{$interview->position->id}/" class="header push-r-sml">{$interview->position->name|truncate:"30"}</a>
					<p class="sub-header"><b class="tc-black text-med push-r-sml">Start:</b> {$interview->start_time|default:"Not started"}</p>
					<p class="sub-header"><b class="tc-black text-med push-r-sml">End:</b> {$interview->end_time|default:"<i>pending</i>"}</p>
					{else}
					<a href="{$HOME}profile/interviewee/{$interview->interviewee->id}/" class="header push-r-sml">{$interview->interviewee->getFullName()|truncate:"30"}</a>
					<p class="sub-header">{$interview->position->name}</p>
					{/if}
				{else}
					<a href="{$HOME}profile/interviewee/{$interview->interviewee->id}/" class="header push-r-sml">{$interview->interviewee->getFullName()|truncate:"30"}</a>
					<p class="sub-header">{$interview->position->name}</p>
				{/if}
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
			<div class="pad-sml">
				<p class="label text-breakable text-sml"><i class="far fa-bookmark push-r-sml"></i>&nbsp;https://www.interviewus.net/i/{$interview->token}/</p>
			</div>
			<div class="divider"></div>
			<div class="conversation-container">
				{if isset( $interview ) == true}
					{foreach from=$interview->questions item=question name=questions_loop}
					<div class="qa-container">
						{if $interview->deployment_type_id == 1}
							{if $question->dispatched == 1}
							<div class="question theme-secondary floatleft">
								<p >{$question->body}</p>
							</div>
							<div class="clear"></div>
							{else}
								{if $smarty.foreach.questions_loop.first}
									<p>Interview pending</p>
								{/if}
							{/if}
							{if !is_null( $question->answer )}
							<div class="answer theme-primary floatright">
								<p>{$question->answer->body|default:"<i>Not answered</i>"}</p>
							</div>
							<div class="clear"></div>
							{/if}
						{else}
							{if $interview->status == "complete"}
								<div class="question theme-secondary floatleft">
									<p >{$question->body}</p>
								</div>
								<div class="clear"></div>
								<div class="answer theme-primary floatright">
									<p>{$question->answer->body|default:"<i>Not answered</i>"}</p>
								</div>
								<div class="clear"></div>
							{else}
								{if $smarty.foreach.questions_loop.first}
								<p>Interview pending</p>
								{/if}
							{/if}
						{/if}
					</div>
					{foreachelse}
					<p>There are no questions for this interview</p>
					{/foreach}
				{else}
					<p>An error has occured</p>
				{/if}
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
	</div>
	{/foreach}
{else}
<div class="con-message-error">
	<p>There was an error loading these interviews</p>
</div>
{/if}

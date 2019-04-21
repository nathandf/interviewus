{extends file="layouts/core.tpl"}

{block name="head"}
	<title>InterviewUs</title>
	<script src="{$HOME}{$JS_SCRIPTS}i/interview.js"></script>
{/block}

{block name="body"}
	{include file="includes/navigation/main-menu.tpl"}
	<div class="con-cnt-lrg push-t-lrg pad-sml-mob-neg">
		{if !empty($error_messages.web_interview)}
			{foreach from=$error_messages.web_interview item=message}
				<div class="con-message-failure mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		<div class="pad-sml-mob-pos">
			<p class="label">Interviewer: {$organization->name}</p>
		</div>
		<div class="con-cnt-lrg pad-med push-b-lrg bg-white border-std">
			<div id="interview-intro">
				{if $interview->deployment_type_id == 1}
				<p class="title">Text Message Interview</p>
				{elseif $interview->deployment_type_id == 2}
				<p class="title" style="margin-top: 0;">Start your interview</p>
				<div class="con-cnt-med-plus">
					<button type="button" id="start-interview" class="button-link tc-white push-t-lrg"><i aria-hidden="true" class="fas fa-play push-r-med"></i>Start</button>
				</div>
				{/if}
			</div>
			<div id="interview" style="display: none;">
				<form action="" method="post">
					<input type="hidden" name="web_interview" value="{$csrf_token}">
					<input type="hidden" name="token" value="{$csrf_token}">
					{foreach from=$interview->questions item=question name=fe_questions}
					<p class="label">{$smarty.foreach.fe_questions.iteration}. {$question->body}</p>
					<textarea name="interviewee_answers[{$question->id}]" class="inp textarea inp-full" required="required">{$question->answer->body|default:null}</textarea>
					<div class="push-t-med"></div>
					{/foreach}
					<div class="con-cnt-med-plus">
						<button class="button" type="submit"><i aria-hidden="true" class="fas fa-paper-plane push-r-med"></i>Submit answers</button>
					</div>
				</form>
			</div>
		</div>
	</div>
{/block}

{block name="footer"}

{/block}

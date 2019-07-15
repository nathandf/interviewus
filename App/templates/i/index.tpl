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
				<div class="con-message-error mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		<div class="pad-sml-mob-pos">
			<p class="label">Interviewer: {$organization->name}</p>
		</div>
		{if $interview->status == "scheduled"}
			<div class="bg-white border-std pad-med">
				<p class="title title-h2" style="margin: 0;">This interview is scheduled for {$interview->scheduled_time}</p>
			</div>
		{else}
			{if $interview->deployment_type_id == 1}
			<div class="bg-white border-std">
				<p class="title title-h2">Start your text message interview</p>
				<form action="" method="post">
					<input type="hidden" name="start_interview" value="{$csrf_token}">
					<input type="hidden" name="token" value="{$csrf_token}">
					<div class="con-cnt-med-plus push-t-sml">
						<button class="button theme-primary" type="submit"><i aria-hidden="true" class="fas fa-play push-r-med"></i>Start interview</button>
					</div>
				</form>
				<div class="pad-sml"></div>
			</div>
			{else}
			<div class="con-cnt-lrg pad-med push-b-lrg bg-white border-std">
				<div id="interview" style="">
					<form action="" method="post">
						<input type="hidden" name="web_interview" value="{$csrf_token}">
						<input type="hidden" name="token" value="{$csrf_token}">
						{foreach from=$interview->questions item=question name=fe_questions}
						<p class="label">{$smarty.foreach.fe_questions.iteration}. {$question->body}</p>
						<textarea name="interviewee_answers[{$question->id}]" class="inp textarea inp-full" required="required">{$question->answer->body|default:null}</textarea>
						<div class="push-t-med"></div>
						{/foreach}
						<div class="con-cnt-med-plus">
							<button class="button theme-primary" type="submit"><i aria-hidden="true" class="fas fa-paper-plane push-r-med"></i>Submit answers</button>
						</div>
					</form>
				</div>
			</div>
			{/if}
		{/if}
	</div>
{/block}

{block name="footer"}

{/block}

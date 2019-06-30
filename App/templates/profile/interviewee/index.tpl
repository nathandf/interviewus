{extends file="layouts/profile.tpl"}

{block name="profile-head"}
	<link rel="stylesheet" href="{$HOME}public/css/profile/interviewee.css">
	<script src="{$HOME}{$JS_SCRIPTS}profile/interviewee.js"></script>
{/block}

{block name="profile-body"}
	{include file="includes/modals/profile/interview-deployment-modal.tpl"}
	{include file="includes/modals/profile/interviewee/interviewee-details.tpl"}
	<div class="con-cnt-xxlrg pad-med-mob-neg">
		<div class="pad-sml-mob-pos">
			<a href="{$HOME}profile/interviewees/" class="btn btn-inline theme-primary "><i aria-hidden="true" class="push-r-sml fas fa-caret-left"></i>Interviewees</a>
		</div>
		<div class="pad-sml-mob-neg"></div>
		{if !empty($error_messages.deploy_interview)}
			{foreach from=$error_messages.deploy_interview item=message}
				<div class="con-message-failure mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		{if !empty($error_messages.update_interviewee)}
			{foreach from=$error_messages.update_interviewee item=message}
				<div class="con-message-failure mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		<div class="pad-sml-mob-pos">
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
			<h2 class="push-t-med">Interviews:</h2>
			<div class="hr-full"></div>
			<div class="interviews con-cnt-med-plus-plus floatleft">
				{foreach from=$interviewee->interviews item=interview}
				<div class="card interview-card push-t-sml">
					<div class="pad-sml">
						<div class="thumbnail-med theme-tertiary floatleft push-r-med">
							{if $interview->deployment_type_id == 1}
							<i class="far fa-comment"></i>
							{else}
							<i class="fa fa-globe"></i>
							{/if}
						</div>
						<div class="floatleft">
							<p class="header">{$interview->position->name}</p>
							<p class="sub-header">Started: {$interview->start_time}</p>
							<p class="sub-header">Completed at: {$interview->end_time}</p>
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
						<div class="clear"></div>
					</div>
				</div>
				{foreachelse}
				<p>No interviews for this person</p>
				{/foreach}
			</div>
			<div class="clear"></div>
		</div>
	</div>
{/block}

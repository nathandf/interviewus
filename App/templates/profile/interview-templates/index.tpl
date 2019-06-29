{extends file="layouts/profile.tpl"}

{block name="profile-head"}
{/block}

{block name="profile-body"}
	{include file="includes/modals/profile/interview-template-modal.tpl"}
	<div class="con-cnt-xxlrg pad-med-mob-neg">
		<div class="pad-sml-mob-pos">
			<button id="interview-template" class="btn btn-inline theme-secondary --modal-trigger"><i aria-hidden="true" class="push-r-sml fas fa-plus"></i>Interview Template</button>
			<a href="{$HOME}profile/interview-templates/browse" class="btn btn-inline theme-primary"><i aria-hidden="true" class="push-r-sml fas fa-search"></i>Browse</a>
		</div>
		<div class="pad-sml-mob-neg"></div>
		{if !empty($error_messages.new_interview_template)}
			{foreach from=$error_messages.new_interview_template item=message}
				<div class="con-message-failure mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		<div class="pad-sml-mob-pos">
		{foreach from=$interviewTemplates item=interviewTemplate name=interview_templates_loop}
			<div class="card">
				<div class="pad-sml">
					<p class="thumbnail-med theme-secondary-dark floatleft push-r-sml"><i class="fas fa-scroll"></i></p>
					<div class="floatleft header">
						<a class="header tc-black" href="{$HOME}profile/interview-template/{$interviewTemplate->id}/">{$interviewTemplate->name|truncate:"27"}</a>
						<p class="sub-header">{$interviewTemplate->description|truncate:"40"}</p>
					</div>
					<div class="clear"></div>
				</div>
				<div class="divider"></div>
				<div class="pad-xsml">
					<a href="{$HOME}profile/interview-template/{$interviewTemplate->id}/" class="button-text-only action tc-deep-purple">EDIT</a>
				</div>
			</div>
			<div class="pad-sml"></div>
		{/foreach}
		</div>
	</div>
{/block}

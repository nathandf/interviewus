{extends file="layouts/profile-with-sidebar.tpl"}

{block name="profile-head"}
	<script src="{$HOME}public/js/profile/interviewee.js"></script>
{/block}

{block name="profile-body"}
	{include file="includes/modals/profile/interview-deployment-modal.tpl"}
	{include file="includes/modals/profile/interviewee/interviewee-details.tpl"}
	{include file="includes/modals/profile/interviewee/image.tpl"}
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
		<div class="content pad-sml-mob-pos floatleft">
			<div class="push-b-med bg-white pad-med box-shadow-light" style="max-width: 800px;">
				<div class="floatleft push-r-med">
					{if !is_null( $interviewee->image )}
					<img  id="interviewee-image" class="thumbnail-image-lrg cursor-pt --modal-trigger" src="{$HOME}public/img/uploads/{$interviewee->image->filename}" alt="{$interviewee->getFullName()}'s image">
					{else}
					<p id="interviewee-image" class="thumbnail-lrg theme-secondary-light cursor-pt --modal-trigger">{$interviewee->getFirstName()|substr:0:1}{$interviewee->getLastName()|substr:0:1|default:null}</p>
					{/if}
				</div>
				<div class="floatleft">
					<a id="interviewee-details" class="cursor-pt --modal-trigger text-xlrg-heavy">{$interviewee->getFullName()}<i class="fas fa-pencil-alt text-sml push-l-sml"></i></a>
					<p class="tc-dark-grey"><i class="fas fa-phone push-r-sml"></i>{$interviewee->phone->getNiceNumber()}</p>
					<p class="tc-dark-grey"><i class="fas fa-envelope push-r-sml"></i>{$interviewee->email}</p>
				</div>
				<div class="clear"></div>
			</div>
			{assign var="interviews" value=$interviewee->interviews}
			{include file="includes/widgets/interviews.tpl"}
		</div>
		<div class="clear"></div>
	</div>
{/block}

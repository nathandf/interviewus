{extends file="layouts/core.tpl"}

{block name="head"}
	<link rel="stylesheet" href="{$HOME}public/css/profile/profile.css">
	<script src="http://malsup.github.com/jquery.form.js"></script>
	<script type="application/javascript" src="{$HOME}{$JS_SCRIPTS}feedback.js"></script>
	<script type="application/javascript" src="{$HOME}{$JS_SCRIPTS}profile/profile.js"></script>
	{block name="profile-head"}{/block}
{/block}

{block name="body"}
	{include file="includes/modals/profile/user-modal.tpl"}
	{include file="includes/modals/profile/current-workspace-modal.tpl"}
	{include file="includes/modals/user-feedback.tpl"}
	{include file="includes/modals/profile/share-interview-modal.tpl"}
	{include file="includes/modals/profile/interviewee-modal.tpl"}
	{include file="includes/modals/profile/interview-template-modal.tpl"}
	{include file="includes/modals/profile/position-modal.tpl"}
	{include file="includes/modals/profile/new-workspace-modal.tpl"}
	{include file="includes/modals/profile/interview-deployment-modal.tpl"}
	<div id="sidebar" class="sidebar scrollbar mat-box-shadow floatleft">
		<div class="sidebar-content">
			{include file="includes/snippets/profile-sidebar-top.tpl"}
			<div class="horizontal-rule"></div>
			<div id="interviewees" class="cursor-pt --sidebar-expand sidebar-header">
				<p class="pad-sml floatleft sidebar-header-title">Interviewees</p>
				<p id="interviewees-sidebar-caret" class="pad-sml floatright"><i class="fas fa-angle-down"></i></p>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
			<div id="interviewees-container" class="sidebar-section">
				{foreach from=$interviewees item=interviewee}
				<a class="text-sml" title="{$interviewee->getFullName()}" href="{$HOME}profile/interviewee/{$interviewee->id}/" style="color: #DDDDDD;">
					<div class="list-item pad-sml">
						{if !is_null( $interviewee->image )}
						<p class="list-item-text-with-image text-overflow-ellipsis"><img src="{$HOME}public/img/uploads/{$interviewee->image->filename}" class="list-item-thumbnail-image push-r-sml">{$interviewee->getFullName()}</p>
						{else}
						<p class="list-item-text text-overflow-ellipsis"><span class="list-item-thumbnail theme-secondary-light push-r-sml">{$interviewee->getFirstName()|substr:0:1}{$interviewee->getLastName()|substr:0:1|default:null}</span>{$interviewee->getFullName()}</p>
						{/if}
					</div>
				</a>
				{foreachelse}
				<a id="interviewee" class="text-sml new-button --modal-trigger">
					<div class="list-item pad-sml">
						<p class="list-item-text text-overflow-ellipsis"><span class="list-item-thumbnail bg-none push-r-sml"><i class="fas fa-plus"></i></span>New Interviewee</p>
					</div>
				</a>
				{/foreach}
			</div>

			<div id="positions" class="cursor-pt --sidebar-expand sidebar-header sidebar-header-border">
				<p class="pad-sml floatleft sidebar-header-title">Positions</p>
				<p id="positions-sidebar-caret" class="pad-sml floatright"><i class="fas fa-angle-up"></i></p>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
			<div id="positions-container" class="sidebar-section" style="display: none;">
				{foreach from=$positions item=position}
				<a class="text-sml" title="{$position->name}" href="{$HOME}profile/position/{$position->id}/" style="color: #DDDDDD;">
					<div class="list-item pad-sml">
						<p class="list-item-text text-overflow-ellipsis"><span class="list-item-thumbnail bg-none push-r-sml"><i class="fas fa-briefcase"></i></span>{$position->name}</p>
					</div>
				</a>
				{foreachelse}
				<a id="position" class="text-sml new-button --modal-trigger">
					<div class="list-item pad-sml">
						<p class="list-item-text text-overflow-ellipsis"><span class="list-item-thumbnail bg-none push-r-sml"><i class="fas fa-plus"></i></span>New Position</p>
					</div>
				</a>
				{/foreach}
			</div>
			<div id="templates" class="cursor-pt --sidebar-expand sidebar-header sidebar-header-border">
				<p class="pad-sml floatleft sidebar-header-title">Templates</p>
				<p id="templates-sidebar-caret" class="pad-sml floatright"><i class="fas fa-angle-up"></i></p>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
			<div id="templates-container" class="sidebar-section" style="display: none;">
				{foreach from=$interviewTemplates item=interviewTemplate}
				<a class="text-sml" title="{$interviewTemplate->name}" href="{$HOME}profile/interview-template/{$interviewTemplate->id}/" style="color: #DDDDDD;">
					<div class="list-item pad-sml">
						<p class="list-item-text text-overflow-ellipsis"><span class="list-item-thumbnail bg-none push-r-sml"><i class="far fa-copy"></i></span>{$interviewTemplate->name}</p>
					</div>
				</a>
				{foreachelse}
				<a id="interview-template" class="text-sml new-button --modal-trigger">
					<div class="list-item pad-sml">
						<p class="list-item-text text-overflow-ellipsis"><span class="list-item-thumbnail bg-none push-r-sml"><i class="fas fa-plus"></i></span>New Template</p>
					</div>
				</a>
				{/foreach}
			</div>
			<div class="pad-lrg"></div>
		</div>
	</div>
	<div class="main-content scrollbar">
		{include file="includes/navigation/profile/main-menu.tpl"}
		{block name="profile-body"}{/block}
	</div>
	<div id="user-feedback" class="user-feedback-trigger tc-white mat-box-shadow --modal-trigger">
		<p class="text-med"><i class="far fa-comment-dots push-r-sml"></i>Feedback</p>
	</div>
	<div class="clear"></div>
{/block}

{block name="footer"}
	{block name="profile-footer"}{/block}
{/block}

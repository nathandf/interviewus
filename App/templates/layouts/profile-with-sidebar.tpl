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
	{include file="includes/modals/profile/settings-modal.tpl"}
	{include file="includes/modals/user-feedback.tpl"}
	{include file="includes/modals/profile/share-interview-modal.tpl"}
	<div class="sidebar scrollbar mat-box-shadow floatleft">
		<div class="sidebar-content">
			<a href="{$HOME}">
				<div class="pad-sml">
					<img src="{$HOME}public/static/img/typography.jpg" class="cursor-pt sidebar-logo" alt="Main Logo">
				</div>
			</a>
			<div class="clear"></div>
			<div class="sidebar-menu pad-sml">
				<a class="sidebar-header menu-item" href="{$HOME}profile/">
					<table>
						<tr>
							<td class="icon"><i class="fas fa-home"></i></td>
							<td>Home</td>
						</tr>
					</table>
				</a>
				<div class="clear"></div>
				<a class="sidebar-header menu-item" href="{$HOME}profile/interviewees/">
					<table>
						<tr>
							<td class="icon"><i class="fas fa-user"></i></td>
							<td>Interviewees</td>
						</tr>
					</table>
				</a>
				<div class="clear"></div>
				<a class="sidebar-header menu-item" href="{$HOME}profile/interview-templates/">
					<table>
						<tr>
							<td class="icon"><i class="fas fa-scroll"></i></td>
							<td>Templates</td>
						</tr>
					</table>
				</a>
				<div class="clear"></div>
				<a class="sidebar-header menu-item" href="{$HOME}profile/positions/">
					<table>
						<tr>
							<td class="icon"><i class="fas fa-user-tie"></i></td>
							<td>Positions</td>
						</tr>
					</table>
				</a>
				<div class="clear"></div>
			</div>
			<div class="horizontal-rule"></div>
			<div id="interviewees" class="cursor-pt --sidebar-expand sidebar-header">
				<p class="pad-sml floatleft sidebar-header-title">Interviewees</p>
				<p id="interviewees-sidebar-caret" class="pad-sml floatright"><i class="fas fa-caret-down"></i></p>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
			<div id="interviewees-container" class="sidebar-section">
				{foreach from=$interviewees item=interviewee}
				<a class="text-sml" href="{$HOME}profile/interviewee/{$interviewee->id}/" style="color: #DDDDDD;">
					<div class="list-item pad-sml">
						<p class="list-item-thumbnail theme-secondary-light floatleft push-r-sml">{$interviewee->getFirstName()|substr:0:1}{$interviewee->getLastName()|substr:0:1|default:null}</p>
						<p class="floatleft list-item-text">{$interviewee->getFullName()|truncate:"20":"..."}</p>
						<div class="clear"></div>
					</div>
				</a>
				{foreachelse}
				<p>No interviewees to show</p>
				{/foreach}
			</div>

			<div id="positions" class="cursor-pt --sidebar-expand sidebar-header sidebar-header-border">
				<p class="pad-sml floatleft sidebar-header-title">Positions</p>
				<p id="positions-sidebar-caret" class="pad-sml floatright"><i class="fas fa-caret-up"></i></p>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
			<div id="positions-container" class="sidebar-section" style="display: none;">
				{foreach from=$positions item=position}
				<a class="text-sml" href="{$HOME}profile/position/{$position->id}/" style="color: #DDDDDD;">
					<div class="list-item pad-sml">
						<p class="list-item-thumbnail bg-none floatleft push-r-sml"><i class="fas fa-user-tie"></i></p>
						<p class="floatleft list-item-text">{$position->name|truncate:"20":"..."}</p>
						<div class="clear"></div>
					</div>
				</a>
				{foreachelse}
				<p>No interviewees to show</p>
				{/foreach}
			</div>
			<div id="templates" class="cursor-pt --sidebar-expand sidebar-header sidebar-header-border">
				<p class="pad-sml floatleft sidebar-header-title">Templates</p>
				<p id="templates-sidebar-caret" class="pad-sml floatright"><i class="fas fa-caret-up"></i></p>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
			<div id="templates-container" class="sidebar-section" style="display: none;">
				{foreach from=$interviewTemplates item=interviewTemplate}
				<a class="text-sml" href="{$HOME}profile/interview-template/{$interviewTemplate->id}/" style="color: #DDDDDD;">
					<div class="list-item pad-sml">
						<p class="list-item-thumbnail bg-none floatleft push-r-sml"><i class="fas fa-scroll"></i></p>
						<p class="floatleft list-item-text">{$interviewTemplate->name|truncate:"20":"..."}</p>
						<div class="clear"></div>
					</div>
				</a>
				{foreachelse}
				<p>No interviewees to show</p>
				{/foreach}
			</div>
		</div>
	</div>
	<div class="floatleft main-content scrollbar">
		{include file="includes/navigation/profile/login-menu-theme-secondary.tpl"}
		{block name="profile-body"}{/block}
	</div>
	<div class="section-seperator"></div>
	<div id="user-feedback" class="user-feedback-trigger tc-white mat-box-shadow --modal-trigger">
		<p class="text-med"><i class="far fa-comment-dots push-r-sml"></i>Feedback</p>
	</div>
	<div class="clear"></div>
{/block}

{block name="footer"}
	{block name="profile-footer"}{/block}
{/block}

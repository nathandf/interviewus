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
				<button id="interview-deployment" class="btn btn-inline theme-secondary-dark --modal-trigger"><i class="fas fa-rocket push-r-sml"></i>Launch</button>
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
			{include file="includes/widgets/interviews.tpl"}
		</div>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
{/block}

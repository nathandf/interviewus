{extends file="layouts/profile.tpl"}

{block name="profile-head"}
	<link rel="stylesheet" href="{$HOME}public/css/profile/billing.css">
	<script src="{$HOME}{$JS_SCRIPTS}profile/billing/billing.js"></script>
	<script src="https://js.braintreegateway.com/web/dropin/1.18.0/js/dropin.min.js"></script>
{/block}

{block name="profile-body"}
	<div class="con-cnt-xlrg pad-med push-t-med">
		{if !empty($error_messages.update_organization)}
			{foreach from=$error_messages.update_organization item=message}
				<div class="con-message-failure mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		{include file="includes/snippets/flash-messages.tpl"}
		<p class="sub-heading">Organization</p>
		<div class="hr-full"></div>
		<div class="con-cnt-med-plus-plus floatleft">
			<form action="" method="post">
				<input type="hidden" name="token" value="{$csrf_token}">
				<input type="hidden" name="update_organization" value="{$csrf_token}">
				<p class="label">Organization name</p>
				<input type="text" name="organization" required="required" class="inp inp-full" value="{$organization->name}">
				<p class="label">Industry</p>
				<select name="industry_id" class="inp inp-full" required="required">
					{if !is_null( $industry )}
					<option value="{$industry->id}" selected="selected" hidden="hidden">{$industry->name}</option>
					{else}
					<option value="" selected="selected" hidden="hidden">Choose an industry</option>
					{/if}
					{if isset($industries)}
					{foreach from=$industries item=industry}
					<option value="{$industry->id}">{$industry->name}</option>
					{/foreach}
					{/if}
				</select>
				<button type="submit" class="btn btn-inline push-t-med">Update Profile</button>
			</form>
		</div>
		<div class="clear"></div>
		<p class="sub-heading">Billing</p>
		<div class="hr-full"></div>
		{if !empty($error_messages.cancel_subscription)}
			{foreach from=$error_messages.cancel_subscription item=message}
				<div class="con-message-failure mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		<div class="con-cnt-med-plus-plus floatleft">
			<p class="label">Current Plan: {ucfirst( $plan->name )}</p>
			<a href="{$HOME}pricing/" class="btn btn-inline tc-white bg-good-green push-t-sml floatleft push-r-sml"><i class="far fa-arrow-alt-circle-up push-r-sml"></i>Upgrade Account</a>
			<button id="view-plan-details" class="btn btn-line bg-salmon tc-white push-t-sml floatleft"><i class="fas fa-eye push-r-sml"></i>View Plan Details</button>
			<div class="clear"></div>
			<div id="plan-container" class="plan-container" style="display: none;">
				<div class="plan-component-detail">
					<p class="text-sml-heavy">SMS Interviews</p>
					<p>{$plan->details->sms_interviews}</p>
				</div>
				<div class="plan-component-detail">
					<p class="text-sml-heavy">Web Interviews</p>
					<p> {if $plan->details->web_interviews < 0}Unlimited{else}{$plan->details->web_interviews}{/if}</p>
				</div>
				<div class="plan-component-detail">
					<p class="text-sml-heavy">Users</p>
					<p>{if $plan->details->users < 0}Unlimited{else}{$plan->details->users}{/if}</p>
				</div>
				<div class="plan-component-detail">
					<p class="text-sml-heavy">Max questions / interview</p>
					<p>{$plan->details->max_questions}</p>
				</div>
				<div class="plan-component-detail">
					<p class="text-sml-heavy">Storage</p>
					<p>{$plan->details->storage}</p>
				</div>
				<div class="plan-component-detail">
					<p class="text-sml-heavy">Unlimited template imports</p>
					<div class="circle-icon {$plan->name} push-t-sml"><i class="fas fa-check"></i></div>
				</div>
				<div class="plan-component-detail">
					<p class="text-sml-heavy">Email/Phone Support</p>
					<div class="circle-icon {$plan->name} push-t-sml"><i class="fas fa-check"></i></div>
				</div>
				<div class="plan-component-detail plan-component-detail-last">
					<p class="text-sml-heavy">Self-serve knowledge base</p>
					<div class="circle-icon {$plan->name} push-t-sml"><i class="fas fa-check"></i></div>
				</div>
				<div class="clear"></div>
			</div>
		</div>
		<div class="clear"></div>
		{if !is_null( $subscription ) && $subscription->status != "Canceled"}
		<div class="hr-full"></div>
		<div class="con-cnt-med-plus-plus floatleft">
			<form action="" method="post">
				<input type="hidden" name="token" value="{$csrf_token}">
				<input type="hidden" name="cancel_subscription" value="{$csrf_token}">
				<button type="submit" class="tc-red text-lrg-heavy button-text-only --c-cancel-confirm">CANCEL SUBSCRIPTION</a>
			</form>
		</div>
		<div class="clear"></div>
		{/if}
	</div>
{/block}

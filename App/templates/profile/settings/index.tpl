{extends file="layouts/profile.tpl"}

{block name="profile-head"}
	<link rel="stylesheet" href="{$HOME}public/css/profile/billing.css">
	<script src="{$HOME}{$JS_SCRIPTS}profile/settings/settings.js"></script>
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
		<p class="sub-heading"><i class="fas fa-building push-r-sml"></i>Organization</p>
		<div class="hr-full"></div>
		<div class="con-cnt-med-plus-plus bg-white border-std pad-med floatleft">
			<form action="" method="post">
				<input type="hidden" name="token" value="{$csrf_token}">
				<input type="hidden" name="update_organization" value="{$csrf_token}">
				<p class="label">Organization name</p>
				<input type="text" name="organization" required="required" class="inp inp-full" value="{$organization->name}">
				<p class="label">Industry</p>
				<select name="industry_id" class="inp inp-full cursor-pt" required="required">
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
				<button type="submit" class="btn btn-inline theme-primary push-t-med">Update Profile</button>
			</form>
		</div>
		<div class="clear"></div>
		<p class="sub-heading"><i class="far fa-credit-card push-r-sml"></i>Billing</p>
		<div class="hr-full"></div>
		{if !empty($error_messages.update_default_payment_method)}
			{foreach from=$error_messages.update_default_payment_method item=message}
				<div class="con-message-failure mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		{if !empty($error_messages.remove_payment_method)}
			{foreach from=$error_messages.remove_payment_method item=message}
				<div class="con-message-failure mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		<div class="con-cnt-med-plus-plus border-std bg-white pad-med floatleft">
			<div id="payment-methods">
				<p class="label">Payment Methods</p>
				{foreach from=$paymentMethods item=paymentMethod name="pm_loop"}
					<div class="bg-white border-std pad-sml push-b-sml">
						<img src="{$paymentMethod->braintreePaymentMethod->imageUrl}" class="floatleft push-r-sml">
						<div class="floatleft">
							<p class="text-left text-xlrg-heavy">Ending in {$paymentMethod->braintreePaymentMethod->last4}</p>
							<p class="text-left text-sml" style="margin-top: -3px">{$paymentMethod->braintreePaymentMethod->cardType}</p>
						</div>
						{if !$paymentMethod->braintreePaymentMethod->default}
						<div class="floatright" style="display: table;">
							<div class="floatright">
								<form action="" method="post">
									<input type="hidden" name="token" value="{$csrf_token}">
									<input type="hidden" name="braintree_payment_method_token" value="{$paymentMethod->braintree_payment_method_token}">
									<button type="submit" name="remove_payment_method" value="{$csrf_token}" class="btn btn-inline tc-white bg-red --c-confirm"><i class="fas fa-trash"></i></button>
								</form>
							</div>
							<div class="floatright">
								<form action="" method="post">
									<input type="hidden" name="update_default_payment_method" value="{$csrf_token}">
									<input type="hidden" name="token" value="{$csrf_token}">
									<button type="submit" name="braintree_payment_method_token" value="{$paymentMethod->braintree_payment_method_token}" class="btn btn-inline theme-primary text-sml push-r-sml">Make default</button>
								</form>
							</div>
							<div class="clear"></div>
						</div>
						{else}
						<div class="floatright">
							<p class="tc-good-green" style="margin-top: 5px;"><i class="fas fa-2x fa-check"></i></p>
						</div>
						{/if}
						<div class="clear"></div>
					</div>
				{foreachelse}
					<p>No payment methods on this account</p>
				{/foreach}
			</div>
			<div id="new-payment-method" style="display: none;">
				<button id="hide" class="btn btn-inline bg-red floatright" style="display: none;"><i class="fas fa-times-circle"></i></button>
				<div class="clear"></div>
				<div id="braintree-dropin-container"></div>
				<button id="submit-button" class="button tc-white text-xlrg bg-good-green push-t-med">Add Payment Method</button>
				{literal}
				<script>
					var button = document.querySelector( '#submit-button' );
					braintree.dropin.create({
						authorization: '{/literal}{$client_token}{literal}',
						container: '#braintree-dropin-container'
					}, function (createErr, instance) {
						button.addEventListener('click', function () {
							instance.requestPaymentMethod(function (err, payload) {
								var payment_processing_url = "{/literal}{$HOME}{literal}profile/settings/?add_payment_method=true&token={/literal}{$csrf_token}{literal}&payment_method_nonce=" + payload.nonce;
								window.location.replace( payment_processing_url );
							} );
						} );
					});
				</script>
				{/literal}
			</div>
			<div class="clear"></div>
			<button id="add-payment-method" class="btn btn-inline theme-primary push-t-med"><i class="fas fa-plus push-r-sml"></i>Add payment method</button>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
		<p class="sub-heading"><i class="fas fa-shopping-cart push-r-sml"></i>Subscription</p>
		<div class="hr-full"></div>
		{if !empty($error_messages.cancel_subscription)}
			{foreach from=$error_messages.cancel_subscription item=message}
				<div class="con-message-failure mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		<div class="con-cnt-med-plus-plus border-std bg-white pad-med floatleft">
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

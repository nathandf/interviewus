{extends file="layouts/core.tpl"}

{block name="head"}
	<title>Pricing | InterviewUs</title>
    <script src="http://malsup.github.com/jquery.form.js"></script>
	<script src="{$HOME}{$JS_SCRIPTS}pricing/pricing.js"></script>
	<link rel="stylesheet" href="{$HOME}public/css/pricing/pricing.css">
{/block}

{block name="body"}
	{include file="includes/navigation/main-menu.tpl"}
	{include file="includes/modals/pricing/checkout-modal.tpl"}
	<div class="con-cnt-lrg">
		{if isset( $account ) == false}
		<div class="pad-med">
			<p class="title">Try it out for <span class="basic-text">free</span></p>
			<p class="sub-title">No credit card needed</p>
			<div class="con-cnt-sml">
				<a href="{$HOME}sign-up/" class="button-link theme-primary">Get started</a>
			</div>
		</div>
		{/if}
		{if !empty($error_messages.add_to_cart)}
			{foreach from=$error_messages.add_to_cart item=message}
				<div class="con-message-failure mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		<div class="pad-sml">
			<form id="pricing-form" action="" method="post">
				<input id="yearly" type="radio" name="billing_frequency" class="--billing-frequency" value="2" required="required" checked="checked" style="display: none;">
				<label data-multiple="1" data-frequency_text="annually" data-radio="yearly" for="yearly" class="pad-sml radio-label btn btn-inline push-r-sml --c-billing-frequency-label --c-annually">Yearly Save > 25%</label>
				<input id="monthly" type="radio" name="billing_frequency" class="--billing-frequency" value="1" required="required" style="display: none;">
				<label data-frequency_text="monthly" data-radio="monthly" for="monthly" class="pad-sml radio-label btn btn-inline --c-billing-frequency-label --c-monthly">Monthly</label>
			</form>
		</div>
	</div>
	<div class="outer-container">
		<div class="clear"></div>
		<div class="pricing-container">
			{foreach from=$plans item=plan name=fe_plans}
			{if $plan->id != 11}
			<div class="pricing-component{if $smarty.foreach.fe_plans.last} pricing-component-last{/if} {if $plan->id < 6}annual-plan{else}monthly-plan{/if}"{if $plan->id > 5} style="display: none;"{/if}>
				<div class="pricing-component-header {$plan->name}">{ucfirst( $plan->name )}</div>
				<div class="pricing-component-content {if $plan->featured} {$plan->name}-border pricing-component-featured{/if}">
					<div class="pricing-component-popularity {$plan->name}-text">{if $plan->featured}Featured{else}&nbsp;{/if}</div>
					<div class="pricing-component-price {$plan->name}-text">
						<p data-base_price="{$plan->price}" class="plan-price">${$plan->price}</p>
						<p class="text-sml-heavy">USD / month</p>
						<p class="text-sml-heavy tc-gun-metal">Billed <span class="billing-frequency-text">annually</span></p>
					</div>
					{if isset( $account )}
						{if $account->plan_id == $plan->id}
						<div class="pricing-cta-container">
							<button type="button" class="current-plan" disabled="disabled">Current Plan</button>
						</div>
						{else}
						<div class="pricing-cta-container">
							<button id="payment" data-plan_name="{ucfirst( $plan->name )}" data-base_price="{$plan->price}" data-plan_id="{$plan->id}" type="button" class="pricing-cta cursor-pt --c-plan-id --modal-trigger">Change Plan</button>
						</div>
						{/if}
					{else}
					<div class="pricing-cta-container">
						<button id="payment" data-plan_name="{ucfirst( $plan->name )}" data-base_price="{$plan->price}" data-plan_id="{$plan->id}" type="button" class="pricing-cta cursor-pt --c-plan-id --modal-trigger">Get Started</button>
					</div>
					{/if}
					<div class="pricing-component-details">
						<div class="pricing-component-detail">
							<p class="text-sml-heavy">SMS Interviews</p>
							<p>{$plan->details->sms_interviews}</p>
						</div>
						<div class="pricing-component-detail">
							<p class="text-sml-heavy">Web Interviews</p>
							<p> {if $plan->details->web_interviews < 0}Unlimited{else}{$plan->details->web_interviews}{/if}</p>
						</div>
						<div class="pricing-component-detail">
							<p class="text-sml-heavy">Users</p>
							<p>{if $plan->details->users < 0}Unlimited{else}{$plan->details->users}{/if}</p>
						</div>
						<div class="pricing-component-detail">
							<p class="text-sml-heavy">Max questions / interview</p>
							<p>{$plan->details->max_questions}</p>
						</div>
						<div class="pricing-component-detail">
							<p class="text-sml-heavy">Storage</p>
							<p>{$plan->details->storage}</p>
						</div>
						<div class="pricing-component-detail">
							<p class="text-sml-heavy">Unlimited template imports</p>
							<div class="thumbnail {$plan->name} push-t-sml"><i class="fas fa-check"></i></div>
						</div>
						<div class="pricing-component-detail">
							<p class="text-sml-heavy">Email/Phone Support</p>
							<div class="thumbnail {$plan->name} push-t-sml"><i class="fas fa-check"></i></div>
						</div>
						<div class="pricing-component-detail pricing-component-detail-last">
							<p class="text-sml-heavy">Self-serve knowledge base</p>
							<div class="thumbnail {$plan->name} push-t-sml"><i class="fas fa-check"></i></div>
						</div>
					</div>
				</div>
			</div>
			{/if}
			{foreachelse}
			<p>Something went wrong!</p>
			{/foreach}
			<div class="clear"></div>
		</div>
	</div>
	<div class="pad-lrg"></div>
{/block}

{block name="footer"}
	{include file="includes/snippets/footer.tpl"}
{/block}

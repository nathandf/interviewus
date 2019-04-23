{extends file="layouts/core.tpl"}

{block name="head"}
	<title>Pricing | InterviewUs</title>
	<link rel="stylesheet" href="{$HOME}public/css/pricing/pricing.css">
{/block}

{block name="body"}
	{include file="includes/navigation/main-menu.tpl"}
	<div class="con-cnt-lrg">
		<div class="pad-med">
			<p class="title">Try it out for <span class="basic-text">free</span></p>
			<p class="sub-title">No credit card needed</p>
			<div class="con-cnt-sml">
				<a href="{$HOME}sign-up/" class="button-link">Get started</a>
			</div>
		</div>
		<div class="pad-sml">
			<input id="yearly" type="radio" name="billing_interval" value="2" required="required" checked="checked" style="display: none;">
			<label for="yearly" class="pad-sml radio-label btn btn-inline push-r-sml">Yearly Save 25%</label>
			<input id="monthly" type="radio" name="billing_interval" value="1" required="required" style="display: none;">
			<label for="monthly" class="pad-sml radio-label btn btn-inline">Monthly</label>
		</div>
	</div>
	<div class="outer-container">
		<div class="clear"></div>
		<div class="pricing-container">
			{foreach from=$plans item=plan name=fe_plans}
			<div class="pricing-component{if $smarty.foreach.fe_plans.last} pricing-component-last{/if}">
				<div class="pricing-component-header {$plan->name}">{ucfirst( $plan->name )}</div>
				<div class="pricing-component-content {if $plan->featured} {$plan->name}-border pricing-component-featured{/if}">
					<div class="pricing-component-popularity {$plan->name}-text">{if $plan->featured}Featured{else}&nbsp;{/if}</div>
					<div class="pricing-component-price {$plan->name}-text">
						<p>${$plan->price}</p>
						<p class="text-sml-heavy">USD / month</p>
						<p class="text-sml-heavy tc-gun-metal">Billed annually</p>
					</div>
					<div class="pricing-cta-container">
						<button type="button" class="pricing-cta cursor-pt">Get Started</button>
					</div>
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
						<div class="pricing-component-detail pricing-component-detail-last">
							<p class="text-sml-heavy">Storage</p>
							<p>{$plan->details->storage}</p>
						</div>
					</div>
				</div>
			</div>
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

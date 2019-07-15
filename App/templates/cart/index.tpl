{extends file="layouts/core.tpl"}

{block name="head"}
	<title>InterviewUs | Cart</title>
	<script src="{$HOME}{$JS_SCRIPTS}/cart/cart.js"></script>
	<script src="https://js.braintreegateway.com/web/dropin/1.18.0/js/dropin.min.js"></script>
{/block}

{block name="body"}
	{include file="includes/navigation/main-menu.tpl"}
	<div class="pad-med"></div>
	<div class="con-cnt-med-plus-plus push-b-lrg pad-med border-std bg-white">
		{if !empty($error_messages.purchase)}
			{foreach from=$error_messages.purchase item=message}
				<div class="con-message-error mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		{foreach from=$cart->products item=product name=product_loop}
		<h2><i class="fas fa-shopping-cart push-r-sml"></i>Cart</h2>
		<p class="label">Plan: {ucfirst( $product->plan->name )} - {$product->plan->braintree_plan_id}</p>
		<p class="label">${$product->plan->price} / month ( billed {$product->billing_frequency} )</p>
		<div class="hr-full"></div>
		<p class="title floatleft push-t-sml push-b-sml">Total:</p><p class="title floatright push-t-sml push-b-sml">${if $product->billing_frequency == "annually"}{$product->plan->price * 12}{else}{$product->plan->price}{/if}</p>
		<div class="clear"></div>
		<div class="hr-full"></div>
		{if $smarty.foreach.product_loop.last}
		<div id="braintree-dropin-container"></div>
		<button id="submit-button" class="button tc-white text-xlrg bg-good-green push-t-med">Complete Purchase</button>
		<p class="text-sml push-t-med">By clicking "Complete Purchase" you agree to InterviewUs's <a target="_blank" href="{$HOME}terms-and-conditions">Terms and Conditions</a> and <a target="_blank" href="{$HOME}privacy-policy">Privacy Policy</a>, and consent to enroll your product(s) in our automatic renewal service, which can be canceled at any time. Automatic renewals are billed to your default payment method until canceled.</p>
		{literal}
		<script>
			var button = document.querySelector( '#submit-button' );
			braintree.dropin.create({
				authorization: '{/literal}{$client_token}{literal}',
				container: '#braintree-dropin-container'
			}, function (createErr, instance) {
				button.addEventListener('click', function () {
					instance.requestPaymentMethod(function (err, payload) {
						var payment_processing_url = "{/literal}{$HOME}{literal}cart/?purchase=true&token={/literal}{$csrf_token}{literal}&payment_method_nonce=" + payload.nonce;
						window.location.replace( payment_processing_url );
					} );
				} );
			});
		</script>
		{/literal}
		{/if}
		{foreachelse}
		<p>Cart Empty</p>
		{/foreach}
	</div>
{/block}

{block name="footer"}
	{include file="includes/snippets/footer.tpl"}
{/block}

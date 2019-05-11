{extends file="layouts/core.tpl"}

{block name="head"}
	<title>InterviewUs | Cart</title>
{/block}

{block name="body"}
	{include file="includes/navigation/main-menu.tpl"}
	<div class="pad-med"></div>
	<div class="con-cnt-med-plus-plus push-b-lrg pad-med border-std bg-white">
		<form action="" method="post">
			<input type="hidden" name="token" value="{$csrf_token}">
			<input type="hidden" name="purchase" value="{$csrf_token}">
			{foreach from=$cart->products item=product name=product_loop}
			<h2><i class="fas fa-shopping-cart push-r-sml"></i>Cart</h2>
			<p class="label">Plan: {ucfirst( $product->plan->name )}</p>
			<p class="label">${if $product->billing_frequency == "monthly"}{($product->plan->price * 1.25)|ceil }{else}{$product->plan->price}{/if} / month ( billed {$product->billing_frequency} )</p>
			<div class="hr-full"></div>
			<p class="text-xlrg-heavy text-center">Total: ${if $product->billing_frequency == "annually"}{($product->plan->price * 12)|floor}{else}{($product->plan->price * 1.25)|ceil}{/if}</p>
			{foreachelse}
			<p>Cart Empty</p>
			{/foreach}
			<button class="button push-t-med"><i class="fas fa-dollar-sign push-r-sml"></i> Purchase</button>
		</form>
	</div>
{/block}

{block name="footer"}
	{include file="includes/snippets/footer.tpl"}
{/block}

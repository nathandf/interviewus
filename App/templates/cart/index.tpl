{extends file="layouts/core.tpl"}

{block name="head"}
	<title>InterviewUs | Cart</title>
{/block}

{block name="body"}
	{include file="includes/navigation/main-menu.tpl"}
	<div class="con-cnt-xlrg push-t-lrg push-b-lrg pad-med">
		{foreach from=$cart->products item=product}
		<p>Plan: {ucfirst( $product->plan->name )}</p>
		<p>${$product->plan->price} / month</p>
		<p>Billed: {$product->billing_frequency}</p>
		{foreachelse}
		<p>Cart Empty</p>
		{/foreach}
	</div>
{/block}

{block name="footer"}
	{include file="includes/snippets/footer.tpl"}
{/block}

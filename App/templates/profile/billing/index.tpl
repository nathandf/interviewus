{extends file="layouts/profile.tpl"}

{block name="profile-head"}
{/block}

{block name="profile-body"}
	<div class="con-cnt-xlrg pad-med push-t-med">
		<div class="con-cnt-med-plus-plus floatleft">
			<p class="sub-title" style="text-align: left;">Payment Methods</p>
			{foreach from=$creditCards item=creditCard}
			<div class="tag pad-sml border-std bg-white cursor-pt mat-hov">
				<div class="floatleft push-r-sml">
					<img src="{$creditCard->imageUrl}" style="vertical-align: bottom;">
				</div>
				<div class="floatleft">
					<p class="label" style="line-height: 8px;">Ending in {$creditCard->last4}</p>
				</div>
				<div class="clear"></div>
			</div>
			{foreachelse}
			<p class="sub-title">No Payment Methods attatched to this account.</p>
			<button class="button button-med">Add Payment Method</button>
			{/foreach}
		</div>
	</div>
{/block}

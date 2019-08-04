{extends file="layouts/core.tpl"}

{block name="head"}
<script src="{$HOME}{$JS_SCRIPTS}quick-boi.js"></script>
{/block}

{block name="body"}
<div class="con-cnt-xlrg pad-med">
	<div class="push-t-med">
		<a id="entity" href="{$HOME}quick-boi/entities" class="btn theme-primary-dark floatleft push-r"><i class="fas fa-plus push-r-sml"></i>Entity</a>
		<a id="service" href="{$HOME}quick-boi/services" class="btn theme-primary floatleft push-r"><i class="fas fa-plus push-r-sml"></i>Service</a>
		<a id="package" href="{$HOME}quick-boi/packages" class="btn theme-primary-light floatleft push-r"><i class="fas fa-plus push-r-sml"></i>Package</a>
		<a id="alias" href="{$HOME}quick-boi/aliases" class="btn theme-secondary-dark floatleft push-r"><i class="fas fa-plus push-r-sml"></i>Alias</a>
		<a id="controller" href="{$HOME}quick-boi/controllers" class="btn theme-secondary floatleft push-r"><i class="fas fa-plus push-r-sml"></i>Controller</a>
		<a id="model" href="{$HOME}quick-boi/models" class="btn theme-secondary-light floatleft push-r"><i class="fas fa-plus push-r-sml"></i>Model</a>
		<a id="view" href="{$HOME}quick-boi/views" class="btn theme-tertiary-dark floatleft push-r"><i class="fas fa-plus push-r-sml"></i>View</a>
		<div class="clear"></div>
	</div>
	<div class="pad-sml"></div>
	<div id="alias-container">
		<h3>Service Aliases</h3>
		<p class="text-sml-heavy tc-dark-grey">Hides the implementation of a service</p>
		<div class="push-t-med">
			<p class="label">Existing Aliases:</p>
			<table>
				{foreach from=$aliases key=abstraction item=implementation}
				<tr>
					<td style="text-align: right;"><p class="push-r">{$abstraction}</p></td>
					<td><b class="push-r">alias for</b></td>
					<td><p class="push-r">{$implementation}</p></td>
				</tr>
				{/foreach}
			</table>
		</div>
		<form action="">
			<button class="btn theme-secondary-dark push-t-med">Create Alias</button>
		</form>
	</div>
</div>
{/block}

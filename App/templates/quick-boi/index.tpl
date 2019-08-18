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
</div>
{/block}

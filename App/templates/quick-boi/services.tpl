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
		<a id="model" href="{$HOME}quick-boi/model" class="btn theme-secondary-light floatleft push-r"><i class="fas fa-plus push-r-sml"></i>Model</a>
		<a id="view" href="{$HOME}quick-boi/view" class="btn theme-tertiary-dark floatleft push-r"><i class="fas fa-plus push-r-sml"></i>View</a>
		<div class="clear"></div>
	</div>
	<div class="pad-sml"></div>
	<div id="service-container">
		<h3>Services</h3>
		<form action="" method="post">
			<input type="hidden" name="token" value="{$csrf_token}">
			<input type="hidden" name="create_service" value="{$csrf_token}">
			<div class="push-t-med">
				<p class="label">Service Name</p>
				<input type="text" name="id_string" class="inp inp-med-plus --id-string">
				<div class="hr-full"></div>
				<button class="btn theme-primary">Build Service</button>
				<div class="hr-full"></div>
				<p class="label push-t-med">Add Dependencies</p>
				<div class="clear"></div>
				{foreach from=$services key=namespace item=namespaced_services}
					<div class="floatleft push-r-sml">
						<p class="label">{$namespace}</p>
						{foreach from=$namespaced_services key=index item=service name=i}
						{if $smarty.foreach.i.first && $namespace == "\\Core\\"}
						<input id="{$service}-service" type="checkbox" name="dependencies[]" value="{$namespace} container" class="checkbox push-r"><label class="label push-r">container</label>
						<div class="clear"></div>
						{/if}
						{if is_numeric( $index )}
						<input id="{$service}-service" type="checkbox" name="dependencies[]" value="{$namespace} {$service}" class="checkbox push-r"><label class="label push-r">{$service}</label>
						{else}
						<input id="{$index}-service" type="checkbox" name="dependencies[]" value="{$namespace} {$index}" class="checkbox push-r"><label class="label push-r">{$index}</label>
						{/if}
						<div class="clear"></div>
						{/foreach}
					</div>
				{/foreach}
				<div class="clear"></div>
			</div>
			<div class="hr-full"></div>
			<button class="btn theme-primary">Build Service</button>
			<div class="hr-full"></div>
		</form>
	</div>
</div>
{/block}

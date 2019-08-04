--id-string{extends file="layouts/core.tpl"}

{block name="head"}
<script src="{$HOME}{$JS_SCRIPTS}quick-boi.js"></script>
{/block}

{block name="body"}
<div class="con-cnt-xlrg pad-med">
	<div class="push-t-med">
		<button id="entity" class="btn theme-primary-dark floatleft push-r --level-trigger"><i class="fas fa-plus push-r-sml"></i>Entity</button>
		<button id="service" class="btn theme-primary floatleft push-r --level-trigger"><i class="fas fa-plus push-r-sml"></i>Service</button>
		<button id="package" class="btn theme-primary-light floatleft push-r --level-trigger"><i class="fas fa-plus push-r-sml"></i>Package</button>
		<button id="alias" class="btn theme-secondary-dark floatleft push-r --level-trigger"><i class="fas fa-plus push-r-sml"></i>Alias</button>
		<button id="controller" class="btn theme-secondary floatleft push-r --level-trigger"><i class="fas fa-plus push-r-sml"></i>Controller</button>
		<button id="model" class="btn theme-secondary-light floatleft push-r --level-trigger"><i class="fas fa-plus push-r-sml"></i>Model</button>
		<button id="view" class="btn theme-tertiary-dark floatleft push-r --level-trigger"><i class="fas fa-plus push-r-sml"></i>View</button>
		<div class="clear"></div>
	</div>
	<div class="pad-sml"></div>
	<div id="entity-container" class="--level">
		<h3>Entities</h3>
		<p class="text-sml-heavy tc-dark-grey">Creates Entity, Repository, and Mapper files.</p>
		{if !empty($error_messages.quick_boi)}
			{foreach from=$error_messages.quick_boi item=message}
				<div class="con-message-error mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		{include file="includes/snippets/flash-messages.tpl"}
		<div class="push-t-med">
			<form action="" method="post">
				<input type="hidden" name="token" value="{$csrf_token}">
				<input type="hidden" name="create_entity" value="{$csrf_token}">
				<p class="label">Model name:</p>
				<input id="model-name" type="text" name="model_name" class="inp inp-med-plus --id-string" required="required">
				<div class="hr"></div>
				<p class="label">Entity Properties</p>
				<button id="add-property" type="button" class="btn theme-primary-dark push-t-med">+ Property</button>
				<table id="property-table" class="col-100 push-t-med">
					<th class="theme-primary-dark text-sml pad-sml" style="border: 1px solid #CCC;">Name</th>
					<th class="theme-primary-dark text-sml pad-sml" style="border: 1px solid #CCC;">Data Type</th>
					<th class="theme-primary-dark text-sml pad-sml" style="border: 1px solid #CCC;">Values/Length</th>
					<th class="theme-primary-dark text-sml pad-sml" style="border: 1px solid #CCC;">Null</th>
					<th class="theme-primary-dark text-sml pad-sml" style="border: 1px solid #CCC;">Primary</th>
					<th class="theme-primary-dark text-sml pad-sml" style="border: 1px solid #CCC;">AI</th>
					<th class="theme-primary-dark text-sml pad-sml" style="border: 1px solid #CCC;"></th>
				</table>
				<div class="hr"></div>
				<p class="text-sml">Engine:</p>
				<select class="inp inp-med cursor-pt" name="engine" id="">
					<option value="InnoDB" selected="selected">InnoDB</option>
				</select>
				<div class="hr"></div>
				<input type="submit" class="btn theme-primary-dark push-t-sml" value="Create Model">
			</form>
		</div>
	</div>
	<div id="service-container" class="--level" style="display: none;">
		<h3>Services</h3>
		<form action="" method="post">
			<input type="hidden" name="token" value="{$csrf_token}">
			<input type="hidden" name="create_service" value="{$csrf_token}">
			<div class="push-t-med">
				<p class="label">Service Name</p>
				<input type="text" name="service_name" class="inp inp-med --id-string">
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
	<div id="package-container" class="--level" style="display: none;">
		<h3>Packages</h3>
		<div class="push-t-med">
			{foreach from=$packages key=package_prefix item=namespaced_services}
				<p>{$package_prefix}</p>
			{/foreach}
		</div>
		<form action="">
			<button class="btn theme-primary-light push-t-med">Build package</button>
		</form>
	</div>
	<div id="alias-container" class="--level" style="display: none;">
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
	<div id="controller-container" class="--level" style="display: none;">
		<h3>Controllers</h3>
	</div>
	<div id="model-container" class="--level" style="display: none;">
		<h3>Models</h3>
	</div>
	<div id="view-container" class="--level" style="display: none;">
		<h3>Views</h3>
	</div>
</div>
{/block}

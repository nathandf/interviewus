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
	<div id="entity-container">
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
</div>
{/block}

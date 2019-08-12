{extends file="layouts/profile-with-sidebar.tpl"}

{block name="profile-head"}
{/block}

{block name="profile-body"}
	<div class="pad-med-mob-neg">
		{include file="includes/snippets/flash-messages.tpl"}
		<p class="sub-heading"><i class="fas fa-building push-r-sml"></i>Workspace</p>
		<div class="hr-full"></div>
		<div class="con-cnt-med-plus-plus bg-white border-std pad-med floatleft">
			<form action="" method="post">
				<input type="hidden" name="token" value="{$csrf_token}">
				<input type="hidden" name="update_organization" value="{$csrf_token}">
				<p class="label">Workspace name</p>
				<input type="text" name="organization" required="required" class="inp inp-full" value="{$organization->name}">
				<button type="submit" class="btn btn-inline theme-primary push-t-med">Update</button>
			</form>
		</div>
		<div class="clear"></div>
	</div>
{/block}

<div id="current-workspace-modal" style="display: none; overflow-y: scroll;" class="lightbox pad-med-mob-neg">
	<div class="pad-sml lightbox-close">
		<i class="fa fa-2x fa-times" aria-hidden="true"></i>
	</div>
	<div class="clear"></div>
	<div class="con-cnt-med-plus-plus bg-white --modal-content">
		<div class="theme-primary pad-med">
			<p>Current Workspace</p>
		</div>
		<div class="pad-med">
			<form action="" method="post">
				<input type="hidden" name="token" value="{$csrf_token}">
				<input type="hidden" name="update_organization" value="{$csrf_token}">
				<p class="label">Workspace name</p>
				<input type="text" name="name" required="required" class="inp inp-full" value="{$organization->name}">
				<button type="submit" class="btn btn-inline theme-primary push-t-med">Update</button>
			</form>
		</div>
	</div>
</div>

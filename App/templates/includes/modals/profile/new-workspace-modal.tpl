<div id="new-workspace-modal" style="display: none; overflow-y: scroll;" class="lightbox pad-med-mob-neg">
	<div class="pad-sml lightbox-close">
		<i class="fa fa-2x fa-times" aria-hidden="true"></i>
	</div>
	<div class="clear"></div>
	<div class="con-cnt-med-plus-plus bg-white --modal-content">
		<div class="theme-primary pad-med">
			<p>New Workspace</p>
		</div>
		<div class="pad-med">
			<form action="{$HOME}profile/" method="post">
				<input type="hidden" name="token" value="{$csrf_token}">
				<input type="hidden" name="new_organization" value="{$csrf_token}">
				<p class="label">Name</p>
				<input type="text" name="name" required="required" class="inp inp-full">
				<button type="submit" class="button theme-primary push-t-med">Create Workspace</button>
			</form>
		</div>
	</div>
</div>

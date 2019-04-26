<div id="organization-modal" style="display: none; overflow-y: scroll;" class="lightbox pad-med-mob-neg">
	<div class="pad-sml lightbox-close">
		<i class="fa fa-2x fa-times" aria-hidden="true"></i>
	</div>
	<div class="clear"></div>
	<div class="con-cnt-med-plus-plus theme-tertiary-light --modal-content">
		<div class="theme-primary pad-med">
			<p>Organziation</p>
		</div>
		<div class="pad-med">
			<form action="{$HOME}profile/" method="post">
				<input type="hidden" name="token" value="{$csrf_token}">
				<input type="hidden" name="udpate_organization" value="{$csrf_token}">
				<p class="label">Organization name</p>
				<input type="text" name="name" required="required" class="inp inp-full" value="{$organization->name}">
				<button type="submit" class="button push-t-med">Update Profile</button>
			</form>
		</div>
	</div>
</div>

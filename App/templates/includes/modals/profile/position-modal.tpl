<div id="position-modal" style="display: none; overflow-y: scroll;" class="lightbox pad-med-mob-neg">
	<div class="pad-sml lightbox-close">
		<i class="fa fa-2x fa-times" aria-hidden="true"></i>
	</div>
	<div class="clear"></div>
	<div class="con-cnt-med-plus-plus theme-tertiary-light --modal-content">
		<div class="theme-primary pad-med">
			<p>New Position</p>
		</div>
		<div class="pad-med">
			<form action="" method="post">
				<input type="hidden" name="token" value="{$csrf_token}">
				<input type="hidden" name="new_position" value="{$csrf_token}">
				<p class="label">Name</p>
				<input type="text" name="name" required="required" class="inp inp-full">
				<p class="label">Description</p>
				<textarea name="description" class="inp textarea inp-full"></textarea>
				<button type="submit" class="button push-t-med">Create Position</button>
			</form>
		</div>
	</div>
</div>

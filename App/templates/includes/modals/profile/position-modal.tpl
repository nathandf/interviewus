<div id="position-modal" style="display: none; overflow-y: scroll;" class="lightbox inner-pad-med">
	<p class="lightbox-close"><i class="fa fa-2x fa-times" aria-hidden="true"></i></p>
	<div class="con-cnt-med-plus-plus bg-white push-t-lrg --modal-content">
		<div class="theme-primary inner-pad-med">
			<p>New Position</p>
		</div>
		<div class="inner-pad-med">
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

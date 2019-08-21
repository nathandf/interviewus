<div id="interviewee-image-modal" style="display: none; overflow-y: scroll;" class="lightbox pad-med-mob-neg">
	<div class="pad-sml lightbox-close">
		<i class="fa fa-2x fa-times" aria-hidden="true"></i>
	</div>
	<div class="clear"></div>
	<div class="con-cnt-med-plus-plus bg-white --modal-content">
		<div class="theme-primary pad-med">
			<p>Upload an image</p>
		</div>
		<div class="pad-med">
			<form action="" method="post" enctype="multipart/form-data">
				<input type="hidden" name="token" value="{$csrf_token}">
				<input type="hidden" name="upload_image" value="{$csrf_token}">
				{if !is_null( $interviewee->image )}
				<img id="uploaded-image" class="uploaded-image" src="{$HOME}public/img/uploads/{$interviewee->image->filename}"/>
				{else}
				<img id="uploaded-image" class="uploaded-image" src="http://placehold.it/200x200&text=No+Attachment!" style="display: none;"/>
				{/if}
				<div class="clear push-t-med"></div>
				<label for="image-uploader">Select Image</label>
				<input id="image-uploader" name="image" type="file">
				<div class="clear"></div>
				<div class="file-upload-container" style="display: none;">
					<input class="btn push-t-med file-upload-button theme-secondary" type="submit" value="Upload Image" name="upload_image" size="25" style="display: none;"/>
				</div>
			</form>
			<div class="clear"></div>
		</div>
	</div>
</div>

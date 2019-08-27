<div id="add-question-modal" style="display: none; overflow-y: scroll;" class="lightbox pad-med-mob-neg">
	<div class="pad-sml lightbox-close">
		<i class="fa fa-2x fa-times" aria-hidden="true"></i>
	</div>
	<div class="clear"></div>
	<div class="con-cnt-med-plus-plus bg-white --modal-content">
		<div class="theme-primary pad-med">
			<p>New Question</p>
		</div>
		<div class="pad-med">
			<form action="" method="post">
				<input type="hidden" name="token" value="{$csrf_token}">
				<input type="hidden" name="new_question" value="{$csrf_token}">
				<p class="label">Question</p>
				<textarea name="body" class="inp textarea inp-full"></textarea>
				<button type="submit" class="button theme-primary push-t-med">Add Question</button>
			</form>
		</div>
	</div>
</div>

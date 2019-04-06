<div id="add-question-modal" style="display: none; overflow-y: scroll;" class="lightbox inner-pad-med">
	<p class="lightbox-close"><i class="fa fa-2x fa-times" aria-hidden="true"></i></p>
	<div class="con-cnt-med-plus-plus bg-white push-t-lrg">
		<div class="theme-primary inner-pad-med">
			<p>New Question</p>
		</div>
		<div class="inner-pad-med">
			<form action="" method="post">
				<input type="hidden" name="token" value="{$csrf_token}">
				<input type="hidden" name="new_question" value="{$csrf_token}">
				<p class="label">Question</p>
				<textarea name="body" class="inp textarea inp-full"></textarea>
				<button type="submit" class="button push-t-med">Add Question</button>
			</form>
		</div>
	</div>
</div>

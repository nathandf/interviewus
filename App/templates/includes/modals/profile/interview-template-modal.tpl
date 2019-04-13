<div id="interview-template-modal" style="display: none; overflow-y: scroll;" class="lightbox inner-pad-med">
	<p class="lightbox-close"><i class="fa fa-2x fa-times" aria-hidden="true"></i></p>
	<div class="con-cnt-med-plus-plus theme-tertiary-light push-t-lrg --modal-content">
		<div class="theme-primary inner-pad-med">
			<p>New Interview Template</p>
		</div>
			<form action="" method="post">
			<input type="hidden" name="token" value={$csrf_token}>
			<input type="hidden" name="new_interview_template" value="{$csrf_token}">
		<div class="theme-tertiary inner-pad-sml">
			<p>1. Name it something memorable</p>
		</div>
		<div class="inner-pad-med">
			<p class="label">Name</p>
			<input type="text" name="name" class="inp inp-full" required="required">
			<p class="label">Description</p>
			<textarea name="description" class="inp textarea inp-full"></textarea>
		</div>
			<div class="theme-tertiary inner-pad-sml">
				<p>2. Add questions</p>
			</div>
			<div class="inner-pad-med">
				<div id="questions-container">
					<p class="label">Question 1</p>
					<textarea name="questions[]" id="question-textarea-1" class="inp textarea inp-full" required="required"></textarea>
				</div>
				<button id="add-question" type="button" class="btn btn-inline theme-secondary push-t-med floatright"><i aria-hidden="true" class="fas fa-plus push-r-sml"></i>Add question</button>
				<div class="clear"></div>
			</div>
			<div class="theme-tertiary inner-pad-sml">
				<p>3. Save Interview Template</p>
			</div>
			<div class="inner-pad-med">
				<button type="submit" class="button">Complete</button>
			</div>
			</form>
	</div>
	<div class="section-seperator"></div>
</div>

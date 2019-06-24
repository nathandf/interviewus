<div id="user-feedback-modal" style="display: none; overflow-y: scroll;" class="lightbox pad-med-mob-neg">
	<div class="pad-sml lightbox-close">
		<i class="fa fa-2x fa-times" aria-hidden="true"></i>
	</div>
	<div class="clear"></div>
	<div class="con-cnt-med-plus-plus bg-white --modal-content">
		<div class="bg-teal tc-white pad-med">
			<p>How can we help you!?</p>
		</div>
		<div class="pad-med">
			<form action="">
				<input type="hidden" name="token" value="">
				<p class="label">What do you think about InterviewUs?</p>
				<select name="subject" class="inp inp-full cursor-pt">
					<option value="" selected="selected" hidden="hidden"></option>
					<option value="1">It sucks. I hate it.</option>
					<option value="2">Meh. Needs improvement</option>
					<option value="3">It's OK</option>
					<option value="4">I like it</option>
					<option value="5">I love it!</option>
				</select>
				<p class="label push-t-med">Please select a subject</p>
				<select name="subject" class="inp inp-full cursor-pt" required="required">
					<option value="" selected="selected" hidden="hidden"></option>
					<option value="feature">Request a feature</option>
					<option value="bug">Report a bug</option>
					<option value="help">Help with an existing feature</option>
					<option value="general">General feedback</option>
					<option value="other">Other</option>
				</select>
				<p class="label push-t-med">What would you like to share with us?</p>
				<textarea name="message" class="inp textarea inp-full"></textarea>
				<button type="submit" class="button theme-secondary push-t-med"><i class="far fa-comment-dots push-r-sml"></i>Submit my feedback</button>
			</form>
		</div>
	</div>
</div>

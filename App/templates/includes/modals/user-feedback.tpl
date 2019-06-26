<div id="user-feedback-modal" style="display: none; overflow-y: scroll;" class="lightbox pad-med-mob-neg">
	<div class="pad-sml lightbox-close">
		<i class="fa fa-2x fa-times" aria-hidden="true"></i>
	</div>
	<div class="clear"></div>
	<div class="con-cnt-med-plus-plus bg-white --modal-content">
		<div class="bg-teal tc-white pad-med">
			<p>We're excited to hear your feedback!</p>
		</div>
		<div class="pad-med">
			<form action="{$HOME}feedback/" method="post">
				<input type="hidden" name="token" value="{$csrf_token}">
				<p class="label">What do you think about InterviewUs?</p>
				<select name="opinion" class="inp inp-full cursor-pt" required="required">
					<option value="" selected="selected" hidden="hidden"></option>
					<option value="It sucks. I hate it.">It sucks. I hate it.</option>
					<option value="Meh. Needs improvement">Meh. Needs improvement</option>
					<option value="It's OK">It's OK</option>
					<option value="I like it">I like it</option>
					<option value="I love it!">I love it!</option>
				</select>
				<p class="label push-t-med">Please select a subject</p>
				<select name="subject" class="inp inp-full cursor-pt" required="required">
					<option value="" selected="selected" hidden="hidden"></option>
					<option value="feature">Request a feature</option>
					<option value="help">I need help</option>
					<option value="complaint">I have a complaint</option>
					<option value="bug">Report a bug</option>
					<option value="general">General feedback</option>
					<option value="other">Other</option>
				</select>
				<p class="label push-t-med">What would you like to share with us?</p>
				<textarea name="message" class="inp textarea inp-full" required="required"></textarea>
				<p class="label push-t-med">How likely are you to suggest us to a friend?</p>
				<div>
					<!-- If those opening and closing comment tags look like a hack, it's because they are
					--><input id="r1" type="radio" name="recommendation" value="1" style="display: none;" required="required"><!--
					--><label for="r1" class="radio-label recommendation">1</label><!--
					--><input id="r2" type="radio" name="recommendation" value="2" style="display: none;" required="required"><!--
					--><label for="r2" class="radio-label recommendation">2</label><!--
					--><input id="r3" type="radio" name="recommendation" value="3" style="display: none;" required="required"><!--
					--><label for="r3" class="radio-label recommendation">3</label><!--
					--><input id="r4" type="radio" name="recommendation" value="4" style="display: none;" required="required"><!--
					--><label for="r4" class="radio-label recommendation">4</label><!--
					--><input id="r5" type="radio" name="recommendation" value="5" style="display: none;" required="required"><!--
					--><label for="r5" class="radio-label recommendation">5</label><!--
					--><input id="r6" type="radio" name="recommendation" value="6" style="display: none;" required="required"><!--
					--><label for="r6" class="radio-label recommendation">6</label><!--
					--><input id="r7" type="radio" name="recommendation" value="7" style="display: none;" required="required"><!--
					--><label for="r7" class="radio-label recommendation">7</label><!--
					--><input id="r8" type="radio" name="recommendation" value="8" style="display: none;" required="required"><!--
					--><label for="r8" class="radio-label recommendation">8</label><!--
					--><input id="r9" type="radio" name="recommendation" value="9" style="display: none;" required="required"><!--
					--><label for="r9" class="radio-label recommendation">9</label><!--
					--><input id="r10" type="radio" name="recommendation" value="10" style="display: none;" required="required"><!--
					--><label for="r10" class="radio-label recommendation">10</label>
					<div class="clear"></div>
					<p class="floatleft text-sml-heavy tc-gun-metal">Least likely</p>
					<p class="floatright text-sml-heavy tc-gun-metal">Most likely</p>
					<div class="clear"></div>
				</div>
				<button type="submit" class="button theme-secondary push-t-med"><i class="far fa-comment-dots push-r-sml"></i>Submit my feedback</button>
			</form>
		</div>
	</div>
</div>

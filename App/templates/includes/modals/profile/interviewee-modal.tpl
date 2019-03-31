<div id="interviewee-modal" style="display: none; overflow-y: scroll;" class="lightbox inner-pad-med">
	<p class="lightbox-close"><i class="fa fa-2x fa-times" aria-hidden="true"></i></p>
	<div class="con-cnt-med-plus-plus bg-white push-t-lrg">
		<div class="theme-primary inner-pad-med">
			<p>New Interviewee</p>
		</div>
		<div class="inner-pad-med">
			<form action="" method="post">
				<input type="hidden" name="token" value="{$csrf_token}">
				<p class="label push-t-sml">Name</p>
				<input type="text" name="name" required="required" class="inp inp-full">
				<p class="label push-t-sml">Email</p>
				<input type="text" name="email" required="required" class="inp inp-full">
				<p class="label push-t-sml">Phone number</p>
				<input type="text" name="phone" required="required" class="inp inp-full">
				<button type="submit" class="button push-t-med">Create Interviewee</button>
			</form>
		</div>
	</div>
</div>

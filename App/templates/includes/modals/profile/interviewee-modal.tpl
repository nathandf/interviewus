<div id="interviewee-modal" style="display: none; overflow-y: scroll;" class="lightbox inner-pad-med">
	<p class="lightbox-close"><i class="fa fa-2x fa-times" aria-hidden="true"></i></p>
	<div class="con-cnt-med-plus-plus bg-white push-t-lrg --modal-content">
		<div class="theme-primary inner-pad-med">
			<p>New Interviewee</p>
		</div>
		<div class="inner-pad-med">
			<form action="" method="post">
				<input type="hidden" name="token" value="{$csrf_token}">
				<input type="hidden" name="new_interviewee" value="{$csrf_token}">
				<p class="label">Name</p>
				<input type="text" name="name" required="required" class="inp inp-full">
				<p class="label push-t-sml">Email</p>
				<input type="email" name="email" required="required" class="inp inp-full">
				{include file="includes/snippets/form-components/full-phone.tpl"}
				<button type="submit" class="button push-t-med">Create Interviewee</button>
			</form>
		</div>
	</div>
</div>

<div id="share-interview-modal" style="display: none; overflow-y: scroll;" class="lightbox pad-med-mob-neg">
	<div class="pad-sml lightbox-close">
		<i class="fa fa-2x fa-times" aria-hidden="true"></i>
	</div>
	<div class="clear"></div>
	<div class="con-cnt-med-plus-plus bg-white --modal-content">
		<div class="theme-primary pad-med">
			<p><i class="fas fa-share-alt push-r-sml"></i>Share interview via Email</p>
		</div>
		<div class="pad-med">
			<form id="share-interview-form" action="{$HOME}profile/share-interview" method="post">
				<input type="hidden" name="token" value="{$csrf_token}">
				<input id="interview-id-field" type="hidden" name="interview_id" value="" required="required">
				<p class="label">Email Recipients: <span class="text-sml-heavy">(Limit: 5)</span></p>
				<p class="label text-med"><i class="fas fa-exclamation-triangle tc-mango push-r-sml"></i>Separate each email address by a comma.</p>
				<textarea type="text" name="recipients" required="required" class="inp textarea inp-full text-med"></textarea>
				<button type="submit" class="button theme-primary push-t-med">Share interview</button>
			</form>
		</div>
	</div>
</div>

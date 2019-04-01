<div id="interview-template-modal" style="display: none; overflow-y: scroll;" class="lightbox inner-pad-med">
	<p class="lightbox-close"><i class="fa fa-2x fa-times" aria-hidden="true"></i></p>
	<div class="con-cnt-med-plus-plus bg-white push-t-lrg">
		<div class="theme-primary inner-pad-med">
			<p>New Interview Template</p>
		</div>
		<div class="inner-pad-med">
			<form action="" method="post">
				<div class="bg-grey inner-pad-sml">
					<p>Name it something memorable!</p>
				</div>
				<div class="push-t-med">
					<p class="label">Name</p>
					<input type="text" name="name" class="inp inp-full">
					<p class="label">Description</p>
					<textarea name="" id="" cols="30" rows="10" class="inp textarea inp-full"></textarea>
				</div>
				<div class="section-seperator"></div>
				<div class="bg-grey inner-pad-sml">
					<p>What positions is this interview template related to?</p>
				</div>
				<div class="push-t-med">
					{foreach from=$positions item=position}
						<label for="position-{$position->id}">{$position->name}</label>
						<input id="position-{$position->id}" type="checkbox" style="display: none;">
					{foreachelse}
						<p>No positions have added to your organization</p>
					{/foreach}
				</div>
				<div class="section-seperator"></div>
				<div class="bg-grey inner-pad-sml">
					<p>Add questions</p>
				</div>
				<div class="push-t-med">
					<p class="label">Question 1</p>
					<textarea name="" id="" cols="30" rows="10" class="inp textarea inp-full"></textarea>
					<button type="button" class="btn btn-inline theme-secondary push-t-med"><i aria-hidden="true" class="fas fa-plus push-r-sml"></i>Add question</button>
				</div>
				<div class="section-seperator"></div>
				<button type="submit" class="button push-t-med">Create Interview Template</button>
			</form>
		</div>
	</div>
</div>

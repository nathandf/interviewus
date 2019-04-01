<div id="interviews-modal" style="display: none; overflow-y: scroll;" class="lightbox inner-pad-med">
	<p class="lightbox-close"><i class="fa fa-2x fa-times" aria-hidden="true"></i></p>
	<div class="con-cnt-med-plus-plus bg-white push-t-lrg">
		<div class="theme-primary inner-pad-med">
			Choose an interview template
		</div>
		<div class="inner-pad-med">
			{foreach from=$interviewTemplates item=interviewTemplate}
			<div class="interview-snippet tc-white">
				<p>{$interviewTemplate->name}</p>
				<p>{$interviewTemplate->description}</p>
			</div>
			{foreachelse}
			<div class="interview-snippet">
				<p>You don't have any interview templates!</p>
				<a href="{$HOME}profile/interview-template/new" class="link tc-deep-purple">Create your first</a>
			</div>
			{/foreach}
		</div>
	</div>
</div>

<div id="interviews-modal" style="display: none; overflow-y: scroll;" class="lightbox inner-pad-med">
	<p class="lightbox-close"><i class="fa fa-2x fa-times" aria-hidden="true"></i></p>
	<div class="con-cnt-med-plus-plus bg-white push-t-lrg">
		<div class="theme-primary inner-pad-med">
			Choose an interview template
		</div>
		<div class="inner-pad-med">
			{foreach from=$interviewTemplates item=interviewTemplate name=interview_template_loop}
			{if !$smarty.foreach.interview_template_loop.first}
			<div class="push-t-sml"></div>
			{/if}
			<div class="border-std inner-pad-sml cursor-pt">
				<p>{$interviewTemplate->name}</p>
				<p>{$interviewTemplate->description}</p>
			</div>
			{foreachelse}
			<div class="">
				<p>You don't have any interview templates!</p>
				<a href="{$HOME}profile/interview-templates/" class="link tc-deep-purple">Create your first</a>
			</div>
			{/foreach}
		</div>
	</div>
</div>

<div id="interview-deployment-modal" style="display: none; overflow-y: scroll;" class="lightbox inner-pad-med">
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
			<div class="border-std inner-pad-sml cursor-pt shade-on-hover">
				<p class="text-med-heavy">{$interviewTemplate->name}</p>
				<p class="text-sml">{$interviewTemplate->description}</p>
			</div>
			{foreachelse}
			<div class="">
				<p>You don't have any interview templates!</p>
				<a href="{$HOME}profile/interview-templates/" class="link tc-deep-purple">Create your first</a>
			</div>
			{/foreach}
		</div>
		<div class="inner-pad-med">
			<div class="bg-grey inner-pad-sml">
				<p>What position will they be interviewing for?</p>
			</div>
			<div class="push-t-med">
				{foreach from=$positions item=position name=position_loop}
					{if $smarty.foreach.position_loop.first}
					<select name="position_id" class="inp inp-full cursor-pt">
					{/if}
						<option value="{$position->id}">{$position->name}</option>
					{if $smarty.foreach.position_loop.last}
					</select>
					{/if}
				{foreachelse}
					<p class="label">Position</p>
					<input type="text" class="inp inp-full">
				{/foreach}
			</div>
		</div>
	</div>
</div>

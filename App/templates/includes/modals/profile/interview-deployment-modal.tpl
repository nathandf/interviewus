<div id="interview-deployment-modal" style="display: none; overflow-y: scroll;" class="lightbox inner-pad-med">
	<p class="lightbox-close"><i class="fa fa-2x fa-times" aria-hidden="true"></i></p>
	<div class="con-cnt-med-plus-plus bg-white push-t-lrg --modal-content">
		<div class="theme-primary inner-pad-med">
			<p>Deploy an interview</p>
		</div>
		<div class="bg-grey inner-pad-sml">
			<p>1. Choose an interviewee</p>
		</div>
		<div class="inner-pad-med deployment-widget-scrollable-section">
			{if isset( $interviewee ) == false}
				{foreach from=$interviewees item=interviewee name=interviewee_loop}
				{if !$smarty.foreach.interviewee_loop.first}
				<div class="push-t-sml"></div>
				{/if}
				<input type="radio" name="interviewee_id" value="{$interviewee->id}" id="interviewee-radio-{$interviewee->id}" style="display: none;">
				<div id="interviewee-tag-{$interviewee->id}" data-interviewee_id="{$interviewee->id}" class="border-std inner-pad-sml cursor-pt deployment-interviewee-tag">
					<p class="text-med-heavy">{$interviewee->getFullName()}</p>
				</div>
				{foreachelse}
				<div class="">
					<a href="{$HOME}profile/interviewees/" class="btn btn-inline tc-deep-purple"><i aria-hidden="true" class="fas fa-plus push-r-sml"></i>Add an inteviewee</a>
				</div>
				{/foreach}
			{else}
				<input type="hidden" name="interviewee_id" value="{$interviewee->id}">
				<div class="border-std inner-pad-sml cursor-pt selected-tag">
					<p class="text-med-heavy">{$interviewee->getFullName()}</p>
				</div>
			{/if}
		</div>
		<div class="bg-grey inner-pad-sml">
			<p>2. What position are they interviewing for?</p>
		</div>
		<div class="inner-pad-med">
			<div class="">
				{foreach from=$positions item=position name=position_loop}
					{if $smarty.foreach.position_loop.first}
					<select name="position_id" class="inp inp-full cursor-pt">
					{/if}
						<option value="{$position->id}">{$position->name}</option>
					{if $smarty.foreach.position_loop.last}
					</select>
					{/if}
				{foreachelse}
					<p class="label" style="margin-top: 0;">Position</p>
					<input type="text" class="inp inp-full">
				{/foreach}
			</div>
		</div>
		<div class="bg-grey inner-pad-sml">
			<p>3. Choose an interview to deploy</p>
		</div>
		<div class="inner-pad-med deployment-widget-scrollable-section">
			{foreach from=$interviewTemplates item=interviewTemplate name=interview_template_loop}
			{if !$smarty.foreach.interview_template_loop.first}
			<div class="push-t-sml"></div>
			{/if}
			<input type="radio" name="interview_template_id" value="{$interviewTemplate->id}" id="interview-template-radio-{$interviewTemplate->id}" style="display: none;">
			<div id="interview-template-tag-{$interviewTemplate->id}" data-interview_template_id="{$interviewTemplate->id}" class="border-std inner-pad-sml cursor-pt deployment-interview-template-tag">
				<p class="text-med-heavy">{$interviewTemplate->name}</p>
				<p class="text-sml">{$interviewTemplate->description|truncate:"300":"..."}</p>
			</div>
			{foreachelse}
			<div class="">
				<p>You don't have any interview templates!</p>
				<a href="{$HOME}profile/interview-templates/" class="link tc-deep-purple">Create your first</a>
			</div>
			{/foreach}
		</div>
		<div class="bg-grey inner-pad-sml">
			<p>4. Launch interview</p>
		</div>
		<div class="inner-pad-med">
			<button type="submit" class="button theme-secondary-dark" disabled="disabled"><i aria-hidden="true" class="fas fa-rocket push-r-sml"></i>Deploy</button>
		</div>
	</div>
	<div class="section-seperator"></div>
</div>

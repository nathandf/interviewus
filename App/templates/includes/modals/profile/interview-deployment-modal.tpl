<div id="interview-deployment-modal" style="display: none; overflow-y: scroll;" class="lightbox inner-pad-med">
	<p class="lightbox-close"><i class="fa fa-2x fa-times" aria-hidden="true"></i></p>
	<div class="con-cnt-med-plus-plus bg-white push-t-lrg --modal-content theme-tertiary-light">
		<form action="" method="post">
			<input type="hidden" name="token" value="{$csrf_token}">
			<input type="hidden" name="deploy-interview" value="{$csrf_token}">
			<div class="theme-primary inner-pad-med">
				<p class="">Deploy an interview</p>
			</div>

			<div class="theme-tertiary-dark inner-pad-sml">
				<p>1. Choose a deployment type</p>
			</div>
			<div class="inner-pad-med">
				<div class="inner-pad-sml floatleft col-50">
					<input id="sms-deployment" type="radio" name="deployment_type" value="1" style="display: none;" class="--c-deployment-requirement deployment-type-radio" required="required">
					<label for="sms-deployment" class="button button-label radio-label"><i aria-hidden="true" class="far fa-comment push-r-sml"></i>SMS</label>
				</div>
				<div class="inner-pad-sml floatleft col-50">
					<input id="web-deployment" type="radio" name="deployment_type" value="2" style="display: none;"class="--c-deployment-requirement deployment-type-radio" required="required">
					<label for="web-deployment" class="button button-label radio-label"><i aria-hidden="true" class="fa fa-globe push-r-sml"></i>Web</lable>
				</div>
				<div class="clear"></div>
			</div>

			<div class="theme-tertiary-dark inner-pad-sml">
				<p>2. Choose an interviewee</p>
			</div>
			<div class="inner-pad-med deployment-widget-scrollable-section">
				{if isset( $interviewee ) == false}
					{foreach from=$interviewees item=interviewee name=interviewee_loop}
					{if !$smarty.foreach.interviewee_loop.first}
					<div class="push-t-sml"></div>
					{/if}
					<input type="radio" name="interviewee_id" value="{$interviewee->id}" class="interviewee-radio --c-deployment-requirement" id="interviewee-radio-{$interviewee->id}" style="display: none;">
					<label for="interviewee-radio-{$interviewee->id}" id="interviewee-tag-{$interviewee->id}" class="deployment-tag inner-pad-sml cursor-pt radio-label" style="display: block;">{$interviewee->getFullName()}</label>
					{foreachelse}
					<div class="">
						<a href="{$HOME}profile/interviewees/" class="btn btn-inline tc-deep-purple"><i aria-hidden="true" class="fas fa-plus push-r-sml"></i>Add an inteviewee</a>
					</div>
					{/foreach}
				{else}
					<input type="radio" name="interviewee_id" value="{$interviewee->id}" class="interviewee-radio" checked="checked" style="display: none;">
					<div class="deployment-tag inner-pad-sml cursor-pt selected-tag">
						<p class="text-med-heavy">{$interviewee->getFullName()}</p>
					</div>
				{/if}
			</div>

			<div class="theme-tertiary-dark inner-pad-sml">
				<p>3. What position are they interviewing for?</p>
			</div>
			<div class="inner-pad-med deployment-widget-scrollable-section">
				{if isset( $position ) == false}
					{foreach from=$positions item=position name=position_loop}
						{if !$smarty.foreach.position_loop.first}
						<div class="push-t-sml"></div>
						{/if}
						<input type="radio" name="position_id" value="{$position->id}" class="position-radio --c-deployment-requirement" id="position-radio-{$position->id}" style="display: none;">
						<label for="position-radio-{$position->id}" id="position-tag-{$position->id}" class="deployment-tag inner-pad-sml cursor-pt radio-label" style="display: block;">{$position->name}</label>
					{foreachelse}
						<p class="label" style="margin-top: 0;">Position</p>
						<input type="text" class="inp inp-full position-input --c-deployment-requirement" name="position" required="required">
					{/foreach}
				{else}
					<input type="radio" name="position_id" value="{$position->id}" class="position-radio" checked="checked" style="display: none;">
					<div class="deployment-tag inner-pad-sml cursor-pt selected-tag">
						<p class="text-med-heavy">{$position->name}</p>
					</div>
				{/if}
			</div>

			<div class="theme-tertiary-dark inner-pad-sml deployment-instruction">
				<p>4. Choose an interview to deploy</p>
			</div>
			<div class="inner-pad-med deployment-widget-scrollable-section">
				{foreach from=$interviewTemplates item=interviewTemplate name=interview_template_loop}
				{if !$smarty.foreach.interview_template_loop.first}
				<div class="push-t-sml"></div>
				{/if}
				<input type="radio" name="interview_template_id" value="{$interviewTemplate->id}" id="interview-template-radio-{$interviewTemplate->id}" class="interview-template-radio --c-deployment-requirement" required="required" style="display: none;">
				<label for="interview-template-radio-{$interviewTemplate->id}" class="radio-label inner-pad-sml cursor-pt deployment-tag" style="display: block;">
					<p class="text-med-heavy">{$interviewTemplate->name}</p>
					<p class="text-sml">{$interviewTemplate->description|truncate:"300":"..."}</p>
				</label>
				{foreachelse}
				<div class="">
					<a href="{$HOME}profile/interview-templates/" class="btn btn-inline tc-deep-purple"><i aria-hidden="true" class="fas fa-plus push-r-sml"></i>Add an inteview template</a>
				</div>
				{/foreach}
			</div>

			<div class="theme-tertiary-dark inner-pad-sml">
				<p>5. Schedule Interview</p>
			</div>
			<div class="inner-pad-med">
				<div class="inner-pad-sml floatleft col-50">
					<input id="immediate" type="radio" name="schedule_type" value="1" style="display: none;" class="schedule-type-radio --c-deployment-requirement" required="required">
					<label for="immediate" class="button button-label radio-label schedule-button"><i aria-hidden="true" class="far fa-clock push-r-sml"></i>Deploy<span class="immediately"> Immediately</span></label>
				</div>
				<div class="inner-pad-sml floatleft col-50">
					<input id="schedule" type="radio" name="schedule_type" value="2" style="display: none;"class="schedule-type-radio --c-deployment-requirement" required="required">
					<label for="schedule" class="button button-label radio-label schedule-button"><i aria-hidden="true" class="fa fa-calendar push-r-sml"></i>Schedule</lable>
				</div>
				<div class="clear"></div>
				<div id="date-time-picker-container" class="inner-pad-sml" style="display: none;">
					<p class="label">Date:</p>
					<input type="text" name="date" id="datepicker" class="inp inp-full scheduled-time-input --c-deployment-requirement" autocomplete="off">
					<p class="label">Time:</p>
					{html_select_time class="inp inp-ful time-picker-input cursor-pt push-b-sml" minute_interval=15 display_seconds=false use_24_hours=false prefix=false}
				</div>
				<div class="clear"></div>
			</div>
			<div class="theme-tertiary-dark inner-pad-sml">
			</div>
			<div class="inner-pad-med">
				<button id="deploy-interview-button" type="submit" class="button theme-secondary-dark" disabled="disabled"><i aria-hidden="true" class="fas fa-rocket push-r-sml"></i>Deploy</button>
			</div>
		</form>
	</div>
	<div class="section-seperator"></div>
</div>

<div id="interview-deployment-modal" style="display: none; overflow-y: scroll;" class="lightbox pad-med-mob-neg">
	<div class="pad-sml lightbox-close">
		<i class="fa fa-2x fa-times" aria-hidden="true"></i>
	</div>
	<div class="clear"></div>
	<div class="con-cnt-med-plus-plus bg-white --modal-content bg-white">
		<form action="" method="post">
			<input type="hidden" name="token" value="{$csrf_token}">
			<input type="hidden" name="deploy-interview" value="{$csrf_token}">
			<div class="theme-primary pad-med">
				<p class="">Deploy an interview</p>
			</div>

			<div class="theme-tertiary-dark pad-sml">
				<p>1. Choose a deployment type</p>
			</div>
			<div class="pad-med">
				<div class="pad-sml floatleft col-50">
					<input id="sms-deployment" type="radio" name="deployment_type_id" value="1" style="display: none;" class="--c-deployment-requirement deployment-type-radio" required="required">
					<label for="sms-deployment" class="button button-label radio-label"><i class="far fa-comment push-r-sml"></i>SMS</label>
				</div>
				<div class="pad-sml floatleft col-50">
					<input id="web-deployment" type="radio" name="deployment_type_id" value="2" style="display: none;"class="--c-deployment-requirement deployment-type-radio" required="required">
					<label for="web-deployment" class="button button-label radio-label"><i class="fa fa-globe push-r-sml"></i>Web</lable>
				</div>
				<div class="clear"></div>
			</div>

			<div class="theme-tertiary-dark pad-sml">
				<p>2. Choose an interviewee</p>
			</div>
			<div class="pad-med deployment-widget-scrollable-section">
				{if isset( $selected_interviewee ) === false}
					{foreach from=$interviewees item=interviewee name=interviewee_loop}
					{if !$smarty.foreach.interviewee_loop.first}
					<div class="push-t-sml"></div>
					{/if}
					<input type="radio" name="interviewee_id" value="{$interviewee->id}" class="interviewee-radio --c-deployment-requirement" id="interviewee-radio-{$interviewee->id}" style="display: none;">
					<label for="interviewee-radio-{$interviewee->id}" id="interviewee-tag-{$interviewee->id}" class="deployment-tag pad-sml cursor-pt radio-label" style="display: block;"><i aria-hidden="true" class="fas fa-user push-r-sml"></i>{$interviewee->getFullName()}</label>
					{foreachelse}
					<div class="">
						<a href="{$HOME}profile/interviewees/" class="btn btn-inline theme-primary"><i aria-hidden="true" class="fas fa-plus push-r-sml"></i>Add an inteviewee</a>
					</div>
					{/foreach}
				{else}
					<input type="radio" name="interviewee_id" value="{$selected_interviewee->id}" class="interviewee-radio" checked="checked" style="display: none;">
					<div class="deployment-tag pad-sml cursor-pt selected-tag">
						<p class="text-med-heavy">{$selected_interviewee->getFullName()}</p>
					</div>
				{/if}
			</div>

			<div class="theme-tertiary-dark pad-sml">
				<p>3. Choose a position</p>
			</div>
			<div class="pad-med deployment-widget-scrollable-section">
				{if isset( $selected_position ) === false}
					{foreach from=$positions item=position name=position_loop}
						{if !$smarty.foreach.position_loop.first}
						<div class="push-t-sml"></div>
						{/if}
						<input type="radio" name="position_id" value="{$position->id}" class="position-radio --c-deployment-requirement" id="position-radio-{$position->id}" style="display: none;">
						<label for="position-radio-{$position->id}" id="position-tag-{$position->id}" class="deployment-tag pad-sml cursor-pt radio-label" style="display: block;"><i aria-hidden="true" class="fas fa-briefcase push-r-sml"></i>{$position->name}</label>
					{foreachelse}
						<p class="label" style="margin-top: 0;">Position</p>
						<input type="text" class="inp inp-full position-input --c-deployment-requirement" name="position" required="required">
					{/foreach}
				{else}
					<input type="radio" name="position_id" value="{$selected_position->id}" class="selected_position-radio" checked="checked" style="display: none;">
					<div class="deployment-tag pad-sml cursor-pt selected-tag">
						<p class="text-med-heavy"><i aria-hidden="true" class="fas fa-briefcase push-r-sml"></i>{$selected_position->name}</p>
					</div>
				{/if}
			</div>

			<div class="theme-tertiary-dark pad-sml deployment-instruction">
				<p>4. Choose an interview to deploy</p>
			</div>
			<div class="pad-med deployment-widget-scrollable-section">
				{foreach from=$interviewTemplates item=interviewTemplate name=interview_template_loop}
				{if !$smarty.foreach.interview_template_loop.first}
				<div class="push-t-sml"></div>
				{/if}
				<input type="radio" name="interview_template_id" value="{$interviewTemplate->id}" id="interview-template-radio-{$interviewTemplate->id}" class="interview-template-radio --c-deployment-requirement" required="required" style="display: none;">
				<label for="interview-template-radio-{$interviewTemplate->id}" class="radio-label pad-sml cursor-pt deployment-tag" style="display: block;">
					<p class="text-med-heavy"><i aria-hidden="true" class="far fa-copy push-r-sml"></i>{$interviewTemplate->name}</p>
					{if $interviewTemplate->description != "" && $interviewTemplate->description != null}
					<div class="hr-std push-t-sml"></div>
					<p class="text-sml push-t-sml">{$interviewTemplate->description|truncate:"300":"..."}</p>
					{/if}
				</label>
				{foreachelse}
				<div class="">
					<a href="{$HOME}profile/interview-templates/" class="btn btn-inline theme-primary"><i aria-hidden="true" class="fas fa-plus push-r-sml"></i>Add an inteview template</a>
				</div>
				{/foreach}
			</div>

			<div class="theme-tertiary-dark pad-sml">
				<p>5. Schedule Interview</p>
			</div>
			<div class="pad-med">
				<div class="pad-sml floatleft col-50">
					<input id="immediate" type="radio" name="schedule_type" value="1" style="display: none;" class="schedule-type-radio --c-deployment-requirement" required="required">
					<label for="immediate" class="button button-label radio-label schedule-button"><i aria-hidden="true" class="far fa-clock push-r-sml"></i>Deploy<span class="immediately"> Immediately</span></label>
				</div>
				<div class="pad-sml floatleft col-50">
					<input id="schedule" type="radio" name="schedule_type" value="2" style="display: none;"class="schedule-type-radio --c-deployment-requirement" required="required">
					<label for="schedule" class="button button-label radio-label schedule-button"><i aria-hidden="true" class="fa fa-calendar push-r-sml"></i>Schedule</lable>
				</div>
				<div class="clear"></div>
				<div id="date-time-picker-container" class="pad-sml" style="display: none;">
					<p class="label push-r-sml">Date: <span class="text-sml tc-gun-metal">MM/DD/YYYY</span></p>
					<input type="text" name="date" id="datepicker" class="inp inp-full push-b-sml scheduled-time-input --c-deployment-requirement" autocomplete="off">
					<p class="label">Time:</p>
					{html_select_time class="inp time-picker-input cursor-pt push-b-sml" minute_interval=15 display_seconds=false use_24_hours=false prefix=false}
				</div>
				<div class="clear"></div>
			</div>
			<div class="theme-tertiary-dark pad-sml">
			</div>
			<div class="pad-med">
				<button id="deploy-interview-button" type="submit" class="button theme-secondary-dark" disabled="disabled"><i aria-hidden="true" class="fas fa-rocket push-r-sml"></i>Deploy</button>
			</div>
		</form>
	</div>
	<div class="section-seperator"></div>
</div>

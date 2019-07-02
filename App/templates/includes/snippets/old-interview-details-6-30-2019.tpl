{foreach from=$interviews item=interview name="fe_interviews"}
<tr id="interview-{$interview->id}" data-id="{$interview->id}" class="bg-white shade-on-hover cursor-pt --c-interview-details">
	<td class="text-med-heavy">{$interview->interviewee->getFullName()}</td>
	<td class="text-med-heavy pad-sml">{if $interview->deployment_type_id == 1}SMS{else}Web{/if}</td>
	<td class="pad-sml" style="max-width: 100px;">
		<div class="status-indicator status-{$interview->status}">
			<p>{ucfirst( $interview->status )}</p>
		</div>
	</td>
</tr>
<tr>
	<td colspan="3">
		<div id="interview-details-{$interview->id}" class="interview-details" style="display: none;">
			<table class="col-100 text-center" style="border-collapse: separate; table-layout: auto;">
				<th class="theme-secondary pad-sml" colspan="3">Interview Details</th>
				<tr>
					<td class="theme-secondary-light pad-sml text-sml-heavy">Question</td>
					<td class="theme-secondary-light pad-sml text-sml-heavy">Status</td>
					<td class="theme-secondary-light pad-sml text-sml-heavy">Answer</td>
				</tr>
				{foreach from=$interview->questions item=question name=fe_questions}
				<tr class="bg-white">
					<td class="text-left pad-sml text-med-heavy"><p class="text-med-heavy">{$smarty.foreach.fe_questions.iteration}.<span class="push-l-sml">{$question->body}</span></p></td>
					<td class="pad-sml text-med"><i>{$question->sms_status|default:"pending"}</i></p></td></td>
					<td class="text-left pad-sml text-med-heavy text-breakable"><i>{$question->answer->body|default:"Not Answered"}</i></p></td></td>
				</tr>
				{foreachelse}
				<tr class="bg-white">
					<td class="text-left pad-sml text-med-heavy">No Questions</td>
					<td></td>
				</tr>
				{/foreach}
			</table>
			<table class="col-100 text-center" style="border-collapse: separate; table-layout: auto;">
				<th class="theme-secondary text-left pad-sml" colspan="3">Interview URL</th>
				<tr>
					<td colspan="3" class="pad-sml text-sml text-left bg-white"><span class="text-breakable">https://www.interviewus.net/i/{$interview->token}/</span></td>
				</tr>
			</table>
		</div>
	</td>
</tr>
{foreachelse}
<tr class="bg-white">
	<td class="text-med-heavy">No Interviews</td>
	<td class="text-med-heavy pad-sml"></td>
	<td class="pad-sml"></td>
</tr>
{/foreach}

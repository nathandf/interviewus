{extends file="layouts/profile.tpl"}

{block name="profile-head"}
	<script src="{$HOME}{$JS_SCRIPTS}profile/interview-template.js"></script>
{/block}

{block name="profile-body"}
	{include file="includes/modals/profile/add-question-modal.tpl"}
	<div class="con-cnt-xxlrg pad-med">
		<a href="{$HOME}profile/interview-templates/" class="btn btn-inline theme-primary "><i aria-hidden="true" class="push-r-sml fas fa-caret-left"></i>Interview Templates</a>
		{include file="includes/snippets/flash-messages.tpl"}
		<div class="bg-grey pad-sml push-t-med">
			<p class="text-med-heavy">{$interviewTemplate->name}</p>
			<p class="text-med">{$interviewTemplate->description|default:null}</p>
		</div>
		<div class="hr-full"></div>
		<div>
			<form action="" method="post">
				<input id="update-existing-questions-input" type="hidden" name="update_existing_questions">
				<button type="submit" class="btn btn-inline theme-primary --update-questions-button floatleft" disabled="disabled">Update</button>
				<button type="button" id="add-question" class="btn btn-inline theme-secondary --modal-trigger floatright"><i aria-hidden="true" class="push-r-sml fas fa-plus"></i>Add question</button>
				<div class="clear"></div>
				<div class="hr-full"></div>
				<div class="sortable-container">
					<div class="sortable">
						{foreach from=$interviewTemplate->questions item=question name=existing_question_loop}
						<div id="question-{$question->id}" class="border-std-thin push-t-sml push-b-sml bg-white">
							<input type="hidden" id="existing-question-{$question->id}" name="existing_question[{$question->id}]" value="{$question->body}">
							<div class="drag-handle hover-grab pad-med floatleft"><span class="tc-grey push-r-sml">{$smarty.foreach.existing_question_loop.iteration}</span><i aria-hidden="true" class="fas fa-grip-horizontal"></i></div>
							<div data-id="{$question->id}" contenteditable="true" class="pad-med --existing-question">{$question->body}</div>
							<div class="clear"></div>
						</div>
						{/foreach}
					</div>
				</div>
				{if count( $interviewTemplate->questions ) >= 3}
					<div class="hr-full"></div>
					<button type="submit" class="btn btn-inline theme-primary --update-questions-button floatleft" disabled="disabled">Update</button>
					<button type="button" id="add-question" class="btn btn-inline theme-secondary --modal-trigger floatright"><i aria-hidden="true" class="push-r-sml fas fa-plus"></i>Add question</button>
					<div class="clear"></div>
					<div class="hr-full"></div>
				{/if}
			</form>
		</div>
	</div>
	<div class="section-seperator"></div>
{/block}

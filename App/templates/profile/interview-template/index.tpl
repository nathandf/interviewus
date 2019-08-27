{extends file="layouts/profile-with-sidebar.tpl"}

{block name="profile-head"}
	<script src="{$HOME}{$JS_SCRIPTS}profile/interview-template.js"></script>
{/block}

{block name="profile-body"}
	{include file="includes/modals/profile/add-question-modal.tpl"}
	<div class="pad-med-mob-neg">
		<div class="pad-sml-mob-pos">
			<a href="{$HOME}profile/" class="btn btn-inline theme-primary push-r-sml"><i aria-hidden="true" class="fas fa-home"></i></a>
			<a href="{$HOME}profile/interview-templates/" class="btn btn-inline theme-primary "><i aria-hidden="true" class="push-r-sml fas fa-caret-left"></i>Interview Templates</a>
		</div>
		<div class="pad-sml-mob-neg"></div>
		{include file="includes/snippets/flash-messages.tpl"}
		<div class="content pad-sml-mob-pos floatleft">
			<form action="" method="post">
				<input type="hidden" name="token" value="{$csrf_token}">
				<input type="hidden" name="update_template" value="{$csrf_token}">
				<p class="label">Name:</p>
				<input type="text" class="inp inp-full template-property" name="name" value="{$interviewTemplate->name}">
				<p class="label">Description:</p>
				<textarea name="description" class="inp textarea inp-full template-property">{$interviewTemplate->description|default:null}</textarea>
				<div class="hr-full"></div>
				<input id="update-existing-questions-input" type="hidden" name="update_existing_questions">
				<div class="pad-sml-mob-pos">
					<button type="submit" class="btn btn-inline theme-primary --update-questions-button floatleft" disabled="disabled">Update</button>
					<button type="button" id="add-question" class="btn btn-inline theme-secondary --modal-trigger floatright"><i aria-hidden="true" class="push-r-sml fas fa-plus"></i>Add question</button>
					<div class="clear"></div>
				</div>
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
					<div class="pad-sml-mob-pos">
						<button type="submit" class="btn btn-inline theme-primary --update-questions-button floatleft" disabled="disabled">Update</button>
						<button type="button" id="add-question" class="btn btn-inline theme-secondary --modal-trigger floatright"><i aria-hidden="true" class="push-r-sml fas fa-plus"></i>Add question</button>
						<div class="clear"></div>
					</div>
					<div class="hr-full"></div>
				{/if}
			</form>
		</div>
		<div class="clear"></div>
	</div>
{/block}

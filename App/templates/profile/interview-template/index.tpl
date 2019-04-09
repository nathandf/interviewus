{extends file="layouts/profile.tpl"}

{block name="profile-head"}
	<script src="{$HOME}{$JS_SCRIPTS}profile/interview-template.js"></script>
{/block}

{block name="profile-body"}
	{include file="includes/modals/profile/add-question-modal.tpl"}
	<div class="con-cnt-xxlrg inner-pad-med">
		<a href="{$HOME}profile/interview-templates/" class="btn btn-inline theme-primary "><i aria-hidden="true" class="push-r-sml fas fa-caret-left"></i>Interview Templates</a>
		<button id="add-question" class="btn btn-inline theme-secondary --modal-trigger"><i aria-hidden="true" class="push-r-sml fas fa-plus"></i>Add question</button>
		{include file="includes/snippets/flash-messages.tpl"}
		<div class="theme-secondary inner-pad-sml push-t-med">
			<p class="text-med-heavy">{$interviewTemplate->name}</p>
			<p class="text-med">{$interviewTemplate->description|default:null}</p>
		</div>
		<div>
			<form action="" method="post">
				<input id="update-existing-questions-input" type="hidden" name="update_existing_questions">
				<div class="sortable-container">
					<div class="sortable">
						{foreach from=$interviewTemplate->questions item=question name=existing_question_loop}
						<div class="draggable-y border-std push-t-sml push-b-sml bg-white">
							<input type="hidden" id="existing-question-{$question->id}" name="existing_question[{$question->id}]" value="">
							<div class="drag-handle hover-grab inner-pad-med floatleft"><i aria-hidden="true" class="fas fa-bars"></i></div>
							<div data-id="{$question->id}" contenteditable="true" class="inner-pad-med --existing-question">{$question->body}</div>
							<div class="clear"></div>
						</div>
						{/foreach}
					</div>
				</div>
				<div class="con-cnt-sml floatleft">
					<button type="submit" class="button --update-questions-button" disabled="disabled">Update</button>
				</div>
			</form>
		</div>
	</div>
	<div class="section-seperator"></div>
{/block}

{extends file="layouts/profile.tpl"}

{block name="profile-head"}
{/block}

{block name="profile-body"}
	{include file="includes/modals/profile/interview-template-modal.tpl"}
	<div class="con-cnt-xxlrg inner-pad-med">
		<button id="interview-template" class="btn btn-inline theme-secondary --modal-trigger"><i aria-hidden="true" class="push-r-sml fas fa-plus"></i>Interview Template</button>
		<a href="{$HOME}profile/interview-templates/browse" class="btn btn-inline theme-primary"><i aria-hidden="true" class="push-r-sml fas fa-search"></i>Browse</a>
		<div class="push-t-med">
			<div class="inner-pad-sml theme-secondary">
				<p>Interview Templates</p>
			</div>
			{foreach from=$interviewTemplates item=interviewTemplate name=interview_templates_loop}
			<a href="{$HOME}profile/interview-template/{$interviewTemplate->id}/" class="link-overlay">
				<div class="inner-pad-med {cycle values='bg-light-grey,bg-grey'}">
					<p>{$interviewTemplate->name}</p>
					{if !is_null( $interviewTemplate->description )}
					<p class="text-med">{$interviewTemplate->description}</p>
					{/if}
				</div>
				<div class="clear"></div>
			</a>
			{foreachelse}
			<div class="inner-pad-med"></div>
			{/foreach}
		</div>
	</div>
{/block}

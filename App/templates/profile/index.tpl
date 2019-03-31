{extends file="layouts/profile.tpl"}

{block name="profile-head"}
{/block}

{block name="profile-body"}
	{include file="includes/modals/profile/interviewee-modal.tpl"}
	{include file="includes/modals/profile/interviews-modal.tpl"}
	<div class="con-cnt-xxlrg inner-pad-med">
		<button id="interviews" class="btn btn-inline theme-secondary-dark --modal-trigger"><i aria-hidden="true" class="push-r-sml fa fa-plus"></i>Deploy Interview</button>
		<a href="{$HOME}profile/interview-template/new" class="btn btn-inline theme-secondary --modal-trigger"><i aria-hidden="true" class="push-r-sml fa fa-plus"></i>Interview Template</a>
		<button id="interviewee" class="btn btn-inline theme-secondary-light --modal-trigger"><i aria-hidden="true" class="push-r-sml fa fa-plus"></i>Interviewee</button>
		<div class="theme-primary inner-pad-sml push-t-med">
			<p class="tc-white">Interviews</p>
		</div>
		<div class="theme-primary-light">
			{foreach from=$interviews item=interview}
			<div class="interview-snippet tc-white">
				<p>{$interview->interviewee->getFullName()}</p>
			</div>
			{foreachelse}
			<div class="interview-snippet">
				<a href="{$HOME}profile/interview/new" class="link tc-white">Create your first interview!</a>
			</div>
			{/foreach}
		</div>
	</div>
	<div class="section-seperator"></div>
{/block}

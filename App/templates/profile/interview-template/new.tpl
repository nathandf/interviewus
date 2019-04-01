{extends file="layouts/profile.tpl"}

{block name="profile-head"}
{/block}

{block name="profile-body"}
	<div class="con-cnt-xxlrg inner-pad-med">
		<a href="{$HOME}profile/interview-templates/" class="btn btn-inline theme-primary"><i aria-hidden="true" class="push-r-sml fas fa-caret-left"></i>My Interview Templates</a>
		{if !empty($error_messages.new_interview_template)}
			{foreach from=$error_messages.new_interview_template item=message}
				<div class="con-message-failure mat-hov cursor-pt --c-hide">
					<p class="user-message-body">{$message}</p>
				</div>
			{/foreach}
		{/if}
		<div class="clear"></div>
		<div class="push-t-med con-cnt-med-plus-plus floatleft">
			<div class="theme-secondary inner-pad-med">
				<p class="">New Interview Template</p>
			</div>
			<form action="" method="post">
				<div class="bg-grey inner-pad-sml">
					<p>Name it something memorable!</p>
				</div>
				<div class="push-t-med">
					<p class="label">Name</p>
					<input type="text" name="name" class="inp inp-full">
					<p class="label">Description</p>
					<textarea name="" id="" cols="30" rows="10" class="inp textarea inp-full"></textarea>
				</div>
				<div class="section-seperator"></div>
				<div class="bg-grey inner-pad-sml">
					<p>What positions is this interview template related to?</p>
				</div>
				<div class="push-t-med">
					{foreach from=$positions item=position}
						<label for="position-{$position->id}">{$position->name}</label>
						<input id="position-{$position->id}" type="checkbox" style="display: none;">
					{foreachelse}
						<p>No positions have added to your organization</p>
					{/foreach}
				</div>
				<div class="section-seperator"></div>
				<div class="bg-grey inner-pad-sml">
					<p>Add questions</p>
				</div>
				<div class="push-t-med">
					<p class="label">Question 1</p>
					<textarea name="" id="" cols="30" rows="10" class="inp textarea inp-full"></textarea>
					<button type="button" class="btn btn-inline theme-secondary push-t-med"><i aria-hidden="true" class="fas fa-plus push-r-sml"></i>Add question</button>
				</div>
				<div class="section-seperator"></div>
				<button type="submit" class="button push-t-med">Create Interview Template</button>
			</form>
		</div>
	</div>
	<div class="clear"></div>
	<div class="section-seperator"></div>
{/block}

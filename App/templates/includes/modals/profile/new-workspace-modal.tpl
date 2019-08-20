<div id="new-workspace-modal" style="display: none; overflow-y: scroll;" class="lightbox pad-med-mob-neg">
	<div class="pad-sml lightbox-close">
		<i class="fa fa-2x fa-times" aria-hidden="true"></i>
	</div>
	<div class="clear"></div>
	<div class="con-cnt-med-plus-plus bg-white --modal-content">
		<div class="theme-primary pad-med">
			<p>New Workspace</p>
		</div>
		<div class="pad-med">
			<form action="{$HOME}profile/" method="post">
				<input type="hidden" name="token" value="{$csrf_token}">
				<input type="hidden" name="new_organization" value="{$csrf_token}">
				<p class="label">Name</p>
				<input type="text" name="name" required="required" class="inp inp-full">

				<p class="label push-t-sml">Duplicate the current workspace's:</p>
				<input id="template-duplication" type="checkbox" class="checkbox" name="duplications[]" value="templates"><label for="template-duplication">Interview Templates</label>
				<div class="clear"></div>
				<input id="position-duplication" type="checkbox" class="checkbox" name="duplications[]" value="positions"><label for="position-duplication">Positions</label>
				<div class="clear"></div>
				<input id="interviewee-duplication" type="checkbox" class="checkbox" name="duplications[]" value="interviewees"><label for="interviewee-duplication">Interviewees</label>
				<div class="push-t-med">
					<div id="change-timezone-container">
						<input type="checkbox" class="cursor-pt" id="change-timezone">
						<label class="label cursor-pt" for="change-timezone">Change Timezone: <span id="timezone-indicator" class="text-sml tc-gun-metal"></span></label>
					</div>
					<div id="timezone-container" style="display: none;">
						<p class="label">Timezone:</p>
						<select name="timezone" class="inp cursor-pt" id="timezone-select">
							{if isset( $timezones )}
							<option value="{$account->timezone|default:'America/Chicago'}" selected="selected" hidden="hidden">{$account->timezone|default:'America/Chicago'}</option>
							{foreach from=$timezones item=timezone}
							<option value="{$timezone->timezone}">UTC/GMT {$timezone->gmt_offset|floor} ({$timezone->abbr} - {$timezone->timezone})</option>
							{/foreach}
							{else}
							<option value="{$account->timezone|default:'America/Chicago'}" selected="selected">{$account->timezone|default:'America/Chicago'}</option>
							{/if}
						</select>
					</div>
				</div>
				<button type="submit" class="button theme-primary push-t-med">Create Workspace</button>
			</form>
		</div>
	</div>
</div>

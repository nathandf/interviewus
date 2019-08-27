<div id="user-modal" style="display: none; overflow-y: scroll;" class="lightbox pad-med-mob-neg">
	<div class="pad-sml lightbox-close">
		<i class="fa fa-2x fa-times" aria-hidden="true"></i>
	</div>
	<div class="clear"></div>
	<div class="con-cnt-med-plus bg-white --modal-content border-std">
		<div class="theme-primary pad-sml">
			<p class="text-center">Workspaces</p>
		</div>
		{foreach from=$organizations item=organization}
		<form action="{$HOME}profile/" method="post">
			<input type="hidden" name="change_organization" value="{$csrf_token}">
			<input type="hidden" name="token" value="{$csrf_token}">
			<input type="hidden" name="organization_id" value="{$organization->id}">
			<button class="link col-100" style="text-decoration: none;">
				<div class="pad-sml text-center bg-white tc-black text-med shade-on-hover" style="border-bottom: 1px solid #000000;">
					<p>{if $user->current_organization_id == $organization->id}<i class="fas fa-check push-r-sml"></i>{/if} {$organization->name}</p>
				</div>
			</button>
		</form>
		{/foreach}
		<a id="new-workspace" class="link --modal-trigger" style="text-decoration: none;">
			<div class="pad-sml text-center bg-white tc-black text-med shade-on-hover">
				<p><i class="fas fa-plus push-r-sml"></i>New Workspace</p>
			</div>
		</a>
		<div class="theme-primary pad-sml">
			<p class="text-center">Account</p>
		</div>
		<a class="link" href="{$HOME}reset-password" style="text-decoration: none;">
			<div class="pad-sml text-center bg-white tc-black text-med shade-on-hover" style="border-bottom: 1px solid #000000;">
				<p>Reset Password</p>
			</div>
		</a>
		<a class="link" href="{$HOME}profile/settings/" style="text-decoration: none;">
			<div class="pad-sml text-center bg-white tc-black text-med shade-on-hover" style="border-bottom: 1px solid #000000;">
				<p>Subscription & Billing</p>
			</div>
		</a>
		<a class="link" href="{$HOME}profile/logout" style="text-decoration: none;">
			<div class="pad-sml text-center bg-white tc-black text-med shade-on-hover">
				<p>Logout</p>
			</div>
		</a>
	</div>
</div>

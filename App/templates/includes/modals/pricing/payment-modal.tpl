<div id="payment-modal" style="display: none; overflow-y: scroll;" class="lightbox pad-med-mob-neg">
	<div class="pad-sml lightbox-close">
		<i class="fa fa-2x fa-times" aria-hidden="true"></i>
	</div>
	<div class="clear"></div>
	<div class="con-cnt-med-plus-plus theme-tertiary-light --modal-content">
		<div class="theme-primary pad-med">
			<p>Payment</p>
		</div>
		<div id="payment-content" class="pad-med">
			{if !is_null( $organization ) && !is_null( $user )}
			<p class="text-sml"><span class="text-sml-heavy">{$organization->name}</span> - {$user->getFullName()}</p>
			<div class="hr-full"></div>
			{/if}
			<div class="col-50 pad-xsml floatleft">
				<p class="label">Selected plan:</p>
				<p><span class="text-lrg-heavy" id="plan-name">Basic</span> - $<span id="price">19</span> / month</p>
			</div>
			<div class="col-50 pad-xsml floatleft">
				<p class="label">Billing frequency:</p>
				<p><span id="billing-interval-text">Yearly</span></p>
			</div>
			<div class="clear"></div>
			<div class="hr-full"></div>
			<p class="label">Total:</p>
			<p class="text-xlrg-heavy">$<span id="total">19</span>.<sup class="text-sml-heavy">00<sup></p>
			{if is_null( $organization ) || is_null( $user )}
			<div class="hr-full"></div>
			<div id="account-options">
				<button class="button theme-primary --sign-in"><i class="fas fa-sign-in-alt push-r-sml"></i>Sign In</button>
				<p class="fancy-line-text push-t-sml tc-gun-metal"><span class="push-r-sml">Or</span></p>
				<button class="button theme-secondary-dark push-t-sml --create-account"><span class="text-lrg-heavy"><i class="fas fa-robot push-r-sml"></i>Create account</span></button>
			</div>
			<div id="create-account-container" style="display: none;">
				<p class="sub-title">Create a new account</p>
				<form action="" method="post">
					<input type="hidden" name="create_account" value="{$csrf_token}">
					<p class="label">Name</p>
					<input type="text" name="name" class="inp inp-full" required="required">
					<p class="label">Email</p>
					<input type="email" name="email" class="inp inp-full" required="required">
					<p class="label">Password</p>
					<input type="password" name="password" class="inp inp-full" required="required">
					<button type="submit" class="button push-t-med theme-secondary-dark"><span class="text-lrg-heavy">Continue</span></button>
				</form>
				<div class="clear push-t-med"></div>
				<a class="--sign-in link tc-deep-blue">Sign in</a>
			</div>
			<div id="sign-in-container" style="display: none;">
				<p class="sub-title">Sign in</p>
				<form action="" method="post">
					<input type="hidden" name="sign-in" value="{$csrf_token}">
					<p class="label">Email</p>
					<input type="email" name="email" class="inp inp-full" required="required">
					<p class="label">Password</p>
					<input type="password" name="password" class="inp inp-full" required="required">
					<button type="submit" class="button push-t-med">Sign in</button>
				</form>
				<div class="clear push-t-med"></div>
				<a class="--create-account link tc-deep-blue">Create new account</a>
			</div>
			{/if}
			<form action="" method="post">
				<input type="hidden"  name="token" value="{$csrf_token}">
				<input type="hidden" name="choose_plan" value="{$csrf_token}">
				<input type="hidden" name="plan_id" value="" required="required">
				<input id="billing-interval" type="hidden" name="billing-interval" value="annually">
			</form>
		</div>
	</div>
</div>

<div id="payment-modal" style="display: none; overflow-y: scroll;" class="lightbox pad-med-mob-neg">
	<div class="pad-sml lightbox-close">
		<i class="fa fa-2x fa-times" aria-hidden="true"></i>
	</div>
	<div class="clear"></div>
	<div class="con-cnt-med-plus-plus bg-white --modal-content">
		<div class="theme-primary pad-med">
			<p>Checkout</p>
		</div>
		<div id="payment-content" class="pad-med">
			<div id="user-name-container" style="display: none;">
				<p id="user-name" class="text-med-heavy"></p>
				<div class="hr-full"></div>
			</div>
			{if !is_null( $organization ) && !is_null( $user )}
			<p class="text-sml"><span class="text-sml-heavy">{$organization->name}</span> - {$user->getFullName()}</p>
			<div class="hr-full"></div>
			{/if}
			<div class="col-50 pad-xsml floatleft">
				<p class="label">Selected plan:</p>
				<p><span class="text-lrg-heavy" id="plan-name">Basic</span> - $<span class="price">19</span> / month</p>
			</div>
			<div class="col-50 pad-xsml floatleft">
				<p class="label">Billing frequency:</p>
				<p><span class="billing-frequency-text">annually</span></p>
			</div>
			<div class="clear"></div>
			<div class="hr-full"></div>
			<p class="label">Total:</p>
			<p class="text-xlrg-heavy">$<span id="total">19</span>.<sup class="text-sml-heavy">00<sup></p>
			<div class="hr-full"></div>
			{if is_null( $organization ) || is_null( $user )}
			<div id="account-options">
				<button class="button theme-primary --sign-in"><i class="fas fa-sign-in-alt push-r-sml"></i>Sign In</button>
				<p class="fancy-line-text push-t-med tc-gun-metal"><span class="push-r-sml">Or</span></p>
				<div class="col-100 text-center push-t-med">
					<a class="link tc-deep-blue --create-account" style="display: block; margin: 0 auto;"><i class="fas fa-robot push-r-sml"></i>Create account</a>
				</div>
			</div>
			<div id="create-account-container" style="display: none;">
				<p class="sub-title">Create a new account</p>
				<form id="create-account-form" action="" method="post">
					<input type="hidden" name="create_account" value="{$csrf_token}">
					<p class="label">Name</p>
					<input type="text" name="name" class="inp inp-full" required="required">
					<p class="label">Email</p>
					<input type="email" name="email" class="inp inp-full" required="required">
					<p class="label">Password</p>
					<input type="password" name="password" class="inp inp-full" required="required">
					<button id="create-account-submit" type="submit" class="button push-t-med theme-secondary-dark"><span class="text-lrg-heavy">Continue</span></button>
				</form>
				<div class="clear push-t-med"></div>
				<a class="--sign-in link tc-deep-blue">Sign in</a>
			</div>
			<div id="sign-in-container" style="display: none;">
				<p class="sub-title">Sign in</p>
				<form id="sign-in-form" action="" method="post">
					<input type="hidden" name="sign_in" value="{$csrf_token}">
					<p class="label">Email</p>
					<input type="email" name="email"  autocomplete="username" class="inp inp-full" required="required">
					<p class="label">Password</p>
					<input type="password" name="password" autocomplete="current-password" class="inp inp-full" required="required">
					<button id="sign-in-submit" type="submit" class="button push-t-med">Sign in</button>
				</form>
				<div class="clear push-t-med"></div>
				<a class="--create-account link tc-deep-blue">Create new account</a>
			</div>
			{/if}
			<form id="checkout-form" action="" method="post">
				<input type="hidden"  name="token" value="{$csrf_token}">
				<input type="hidden" name="add_to_cart" value="{$csrf_token}">
				<input type="hidden" name="plan_id" value="" required="required">
				<input id="billing-frequency" type="hidden" name="billing_frequency" value="annually">
				<div id="checkout-button-container" {if is_null( $organization ) || is_null( $user )}style="display: none;"{/if}>
					<button id="checkout-button" type="submit" class="button theme-secondary-dark"><i class="fas fa-shopping-cart push-r-sml"></i>Checkout</button>
				</div>
			</form>
		</div>
	</div>
</div>

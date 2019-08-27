<div id="interviewee-details-modal" style="display: none; overflow-y: scroll;" class="lightbox pad-med-mob-neg">
	<div class="pad-sml lightbox-close">
		<i class="fa fa-2x fa-times" aria-hidden="true"></i>
	</div>
	<div class="clear"></div>
	<div class="con-cnt-med-plus-plus bg-white --modal-content">
		<div class="theme-primary pad-med">
			<p>Update Interviewee Details</p>
		</div>
		<div class="pad-med">
			<form action="" method="post">
				<input type="hidden" name="token" value="{$csrf_token}">
				<input type="hidden" name="update_interviewee" value="{$csrf_token}">
				<p class="label">First Name:</p>
				<input type="text" class="inp inp-full" name="first_name" value="{$interviewee->first_name}" required="required">
				<p class="label">Last Name:</p>
				<input type="text" class="inp inp-full" name="last_name" value="{$interviewee->last_name}">
				<p class="label">Email:</p>
				<input type="email" class="inp inp-full" name="email" value="{$interviewee->email}" required="required">
				<div class="container-full-phone push-t-sml">
					<div class="container-country-code">
						<p class="label">Code</p>
						<select name="country_code" class="inp inp-full cursor-pt" required="required">
							<option value="{$interviewee->phone->country_code}" selected="selected" hidden="hidden">+{$interviewee->phone->country_code}</option>
							<option value="1">+1</option>
							{if isset($countries)}
								{foreach from=$countries item=country}
								<option value="{$country->phonecode}">+{$country->phonecode} {if $country->iso3 == ""}{$country->iso}{else}{$country->iso3}{/if}</option>
								{/foreach}
							{/if}
						</select>
					</div>
					<div class="container-national-number">
						<p class="label">Phone number</p>
						<input type="tel" class="inp inp-full" name="national_number" value="{$interviewee->phone->national_number}" required="required">
					</div>
					<div class="clear"></div>
				</div>
				<button type="submit" class="button theme-secondary push-t-med">Update</button>
			</form>
		</div>
	</div>
</div>

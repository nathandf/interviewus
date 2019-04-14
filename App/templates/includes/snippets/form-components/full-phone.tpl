<div class="container-full-phone push-t-sml">
	<div class="container-country-code">
		<p class="label">Code</p>
		<select name="country_code" class="inp inp-full" required="required">
			<option value="1" selected="selected" hidden="hidden">+1</option>
			{if isset($countries)}
				{foreach from=$countries item=country}
				<option value="{$country->phonecode}">+{$country->phonecode} {if $country->iso3 == ""}{$country->iso}{else}{$country->iso3}{/if}</option>
				{/foreach}
			{/if}
		</select>
	</div>
	<div class="container-national-number">
		<p class="label">Phone number</p>
		<input type="text" class="inp inp-full" name="national_number" required="required">
	</div>
	<div class="clear"></div>
</div>

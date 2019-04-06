{extends file="layouts/profile.tpl"}

{block name="profile-head"}
{/block}

{block name="profile-body"}
	<div class="con-cnt-xxlrg inner-pad-med">
		<a href="{$HOME}profile/positions/" class="btn btn-inline theme-primary "><i aria-hidden="true" class="push-r-sml fas fa-caret-left"></i>Positions</a>
		<div class="theme-primary-light inner-pad-sml push-t-med">
			<p class="text-med-heavy">{$position->name}</p>
			<p class="text-med">{$position->description|default:null}</p>
		</div>
	</div>
	<div class="section-seperator"></div>
{/block}

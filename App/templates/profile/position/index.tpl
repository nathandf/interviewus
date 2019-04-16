{extends file="layouts/profile.tpl"}

{block name="profile-head"}
{/block}

{block name="profile-body"}
	<div class="con-cnt-xxlrg pad-med">
		<a href="{$HOME}profile/positions/" class="btn btn-inline theme-primary "><i aria-hidden="true" class="push-r-sml fas fa-caret-left"></i>Positions</a>
		<div class="theme-primary-light pad-sml push-t-med">
			<p>{$position->name}</p>
		</div>
		<div class="theme-primary pad-sml">
			<p>{$position->description|default:null}</p>
		</div>

	</div>
	<div class="section-seperator"></div>
{/block}

{extends file="layouts/profile.tpl"}

{block name="profile-head"}
{/block}

{block name="profile-body"}
	<div class="con-cnt-xxlrg pad-med-mob-neg">
		<div class="pad-sml-mob-pos">
			<a href="{$HOME}profile/positions/" class="btn btn-inline theme-primary "><i aria-hidden="true" class="push-r-sml fas fa-caret-left"></i>Positions</a>
		</div>
		<div class="pad-sml-mob-neg"></div>
		<div class="theme-primary-light pad-sml">
			<p>{$position->name}</p>
		</div>
		<div class="theme-primary pad-sml">
			<p>{$position->description|default:null}</p>
		</div>

	</div>
{/block}

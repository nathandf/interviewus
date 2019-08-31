<div class="pad-med bg-white box-shadow-light">
    <div id="nav-sidebar-toggle" class="floatleft push-r-med" >
        <i class="nav-sidebar-toggle fas fa-bars tc-black cursor-pt --sidebar-toggle"></i>
    </div>
    <a id="current-workspace" class="floatleft organization-name cursor-pt --modal-trigger">{$organization->name}</a>
	<a id="user" class="thumbnail-sml cursor-pt theme-primary --modal-trigger floatright">{$user->getFirstName()|substr:0:1}{$user->getLastName()|substr:0:1|default:null}</a>
	<a class="upgrade-button floatright bg-real-gold tc-white push-r-sml text-sml-heavy" href="{$HOME}pricing/"><i class="fas fa-certificate push-r-sml"></i>Upgrade</a>
	<div class="clear"></div>
</div>

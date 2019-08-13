<div class="pad-med bg-white box-shadow-light">
    <div id="nav-sidebar-toggle" class="floatleft push-r-med" style="display: none;">
        <i class="nav-sidebar-toggle fas fa-bars tc-black cursor-pt --sidebar-toggle"></i>
    </div>
    <a id="current-workspace" class="floatleft organization-name cursor-pt --modal-trigger">{$organization->name}</a>
    <a id="user" class="thumbnail-sml cursor-pt theme-primary --modal-trigger floatright">{$user->getFirstName()|substr:0:1}{$user->getLastName()|substr:0:1|default:null}</a>
    <div class="clear"></div>
</div>

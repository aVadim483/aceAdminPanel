<ul class="nav nav-list well well-small">
    <li class="nav-header">{$oLang->_adm_menu_panel}</li>
    <li {if $sEvent=='' OR $sEvent=='info'}class="active"{/if}>
        <a href="{router page='admin'}info/">{$oLang->_adm_menu_info}</a>
    </li>
    <li {if $sMenuSubItemSelect=='params'}class="active"{/if}>
        <a href="{router page='admin'}params/">{$oLang->adm_menu_params}</a>
    </li>

    <li class="nav-header">{$oLang->_adm_menu_config}</li>
    <li {if $sMenuSubItemSelect=='settings'}class="active"{/if}>
        <a href="{router page='admin'}site/settings/">{$oLang->_adm_menu_settings}</a>
    </li>
    <li {if $sMenuSubItemSelect=='reset'}class="active"{/if}>
        <a href="{router page='admin'}site/reset/">{$oLang->_adm_menu_reset}</a>
    </li>
    <li {if $sEvent=='plugins'}class="active"{/if}>
        <a href="{router page=admin}plugins/">{$oLang->_adm_menu_plugins}</a>
    </li>

    <li class="nav-header">{$oLang->_adm_menu_site}</li>
    <li {if $sEvent=='users'}class="active"{/if}>
        <a href="{router page=admin}users/">{$oLang->_adm_menu_users}
        {if $oUserProfile}<i class="icon icon-arrow-right"></i>{/if}
        </a>
    </li>
    <li {if $sEvent=='blogs'}class="active"{/if}>
        <a href="{router page=admin}blogs/">{$oLang->_adm_menu_blogs}</a>
    </li>
    <li {if $sEvent=='pages'}class="active"{/if}>
        <a href="{router page=admin}pages/">{$oLang->_adm_menu_pages}</a>
    </li>
{hook run='admin_action_item'}
</ul>

{hook run='admin_action'}

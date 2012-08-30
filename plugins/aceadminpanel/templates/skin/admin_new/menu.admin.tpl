<ul class="menu">

    <li {if $sMenuItemSelect=='info'}class="active"{/if}>
        <a href="{router page='admin'}">{$oLang->adm_title}</a>
    {if $sMenuItemSelect=='info'}
        <ul class="sub-menu">
            <li {if $sMenuSubItemSelect=='about' || $sMenuSubItemSelect==''}class="active"{/if}>
                <div><a href="{router page='admin'}info/about/">{$oLang->adm_menu_about}</a></div>
            </li>
            <li {if $sMenuSubItemSelect=='params'}class="active"{/if}>
                <div><a href="{router page='admin'}info/params/">{$oLang->adm_menu_params}</a></div>
            </li>
            <li {if $sMenuSubItemSelect=='phpinfo'}class="active"{/if}>
                <div><a href="{router page='admin'}info/phpinfo/">PHP Info</a></div>
            </li>
        </ul>
    {/if}
    </li>

{if !$bNeedUpgrade}
    <li {if $sMenuItemSelect=='site'}class="active"{/if}>
        <a href="{router page='admin'}site/">{$oLang->adm_menu_site}</a>
        {if $sMenuItemSelect=='site'}
            <ul class="sub-menu">
                <li {if $sMenuSubItemSelect=='settings'}class="active"{/if}>
                    <div><a href="{router page='admin'}site/settings/">{$oLang->adm_menu_settings}</a></div>
                </li>
                <li {if $sMenuSubItemSelect=='reset'}class="active"{/if}>
                    <div><a href="{router page='admin'}site/reset/">{$oLang->adm_menu_reset}</a></div>
                </li>
            </ul>
        {/if}
    </li>

    {if $aPluginActive.page}
        <li {if $sMenuItemSelect=='pages'}class="active"{/if}>
            <a href="{router page='admin'}pages/">{$oLang->adm_menu_pages}</a>
            {if $sMenuItemSelect=='pages'}
                <ul class="sub-menu">
                    <li {if $sMenuSubItemSelect=='list'}class="active"{/if}>
                        <div><a href="{router page='admin'}pages/list/">{$oLang->adm_menu_pages_list}</a></div>
                    </li>
                    <li {if $sMenuSubItemSelect=='new'}class="active"{/if}>
                        <div><a href="{router page='admin'}pages/new/">{$oLang->adm_menu_pages_new}</a></div>
                    </li>
                    <li {if $sMenuSubItemSelect=='options'}class="active"{/if}>
                        <div><a href="{router page='admin'}pages/options/">{$oLang->adm_menu_pages_options}</a></div>
                    </li>
                </ul>
            {/if}
        </li>
    {/if}

    {if $aPluginActive.aceblogextender AND $oConfig->GetValue('plugin.aceblogextender.category.enable')}
        <li {if $sMenuItemSelect=='categories'}class="active"{/if}>
            <a href="{router page='admin'}categories/">{$oLang->mblog_categories}</a>
            {if $sMenuItemSelect=='categories'}
                <ul class="sub-menu">
                    <li {if $sMenuSubItemSelect=='list'}class="active"{/if}>
                        <div><a href="{router page='admin'}categories/list/">{$oLang->adm_menu_pages_list}</a></div>
                    </li>
                    <li {if $sMenuSubItemSelect=='new'}class="active"{/if}>
                        <div><a href="{router page='admin'}categories/new/">{$oLang->adm_menu_pages_new}</a></div>
                    </li>
                </ul>
            {/if}
        </li>
    {/if}

    <li {if $sMenuItemSelect=='blogs'}class="active"{/if}>
        <a href="{router page='admin'}blogs/">{$oLang->adm_menu_blogs}</a>
        {if $sMenuItemSelect=='blogs'}
            <ul class="sub-menu">
                <li {if $sMenuSubItemSelect=='list'}class="active"{/if}>
                    <div><a href="{router page='admin'}blogs/list/">{$oLang->adm_menu_blogs_list}</a></div>
                </li>
            </ul>
        {/if}
    </li>

    <li {if $sMenuItemSelect=='users'}class="active"{/if}>
        <a href="{router page='admin'}users/">{$oLang->adm_menu_users}</a>
        {if $sMenuItemSelect=='users'}
            <ul class="sub-menu">
                {if $sMenuSubItemSelect=='profile'}
                    <li {if $sMenuSubItemSelect=='profile'}class="active"{/if}>
                        <div>
                            <a href="{router page='admin'}users/profile/{$oUserProfile->getLogin()}/">{$oLang->adm_menu_users_profile}</a>
                        </div>
                    </li>{/if}
                <li {if $sMenuSubItemSelect=='list'}class="active"{/if}>
                    <div><a href="{router page='admin'}users/list/">{$oLang->adm_menu_users_list}</a></div>
                </li>
                <li {if $sMenuSubItemSelect=='banlist'}class="active"{/if}>
                    <div><a href="{router page='admin'}users/banlist/">{$oLang->adm_menu_users_banlist}</a></div>
                </li>
                <li {if $sMenuSubItemSelect=='invites'}class="active"{/if}>
                    <div><a href="{router page='admin'}users/invites/">{$oLang->settings_menu_invite}</a></div>
                </li>
                <li {if $sMenuSubItemSelect=='fields'}class="active"{/if}>
                    <div><a href="{router page='admin'}users/fields/">{$oLang->adm_menu_users_fields}</a></div>
                </li>
            </ul>
        {/if}
    </li>

    <li>|</li>

    <li {if $sMenuItemSelect=='plugins'}class="active"{/if}>
        <a href="{router page='admin'}plugins/">{$oLang->adm_menu_plugins}</a>
        {if $sMenuItemSelect=='plugins'}
            <ul class="sub-menu">
                <li {if $sMenuSubItemSelect=='list'}class="active"{/if}>
                    <div><a href="{router page='admin'}plugins/list/">{$oLang->adm_menu_list}</a></div>
                </li>
                {if $sMenuSubItemSelect=='config'}
                    <li class="active">
                        <div><a href="#">{$oLang->adm_menu_settings}</a></div>
                    </li>
                {/if}
            </ul>
        {/if}
    </li>

    {if $aLang.hookets_menu_hookets}
    <li><a href="{router page='hookets'}">{$aLang.hookets_menu_hookets}</a></li>
    {/if}

    <!--li {if $sMenuItemSelect=='tools'}class="active"{/if}>
        <a href="{router page='admin'}tools/">{$oLang->adm_menu_tools}</a>
    {if $sMenuItemSelect=='tools'}
        <ul class="sub-menu" >
            <li {if $sMenuSubItemSelect=='comments'}class="active"{/if}><div><a href="{router page='admin'}tools/comments/">{$oLang->adm_tools_comments}</a></div></li>
        </ul>
    {/if}
    </li -->

{/if}

{hook run='menu_admin'}

</ul>

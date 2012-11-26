{extends file="./users.tpl"}

{block name="content"}

<h3>{$oLang->_adm_menu_users_profile}: {$oUserProfile->getLogin()} (ID {$oUserProfile->getId()})</h3>

<div class="user-profile">
    <ul class="nav nav-tabs">
        <li {if $sMode=='info'}class="active"{/if}>
            <a href="{router page='admin'}users/profile/{$oUserProfile->getLogin()}/info/">Info</a>
        </li>
        <li {if $sMode=='blogs'}class="active"{/if}>
            <a href="{router page='admin'}users/profile/{$oUserProfile->getLogin()}/blogs/">Blogs</a>
        </li>
        <li {if $sMode=='topics'}class="active"{/if}>
            <a href="{router page='admin'}users/profile/{$oUserProfile->getLogin()}/topics/">Topics</a>
        </li>
        <li {if $sMode=='comments'}class="active"{/if}
                ><a href="{router page='admin'}users/profile/{$oUserProfile->getLogin()}/comments/">Comments</a>
        </li>
        <li {if $sMode=='voted'}class="active"{/if}>
            <a href="{router page='admin'}users/profile/{$oUserProfile->getLogin()}/voted/">Voted</a>
        </li>
        <li {if $sMode=='votes'}class="active"{/if}>
            <a href="{router page='admin'}users/profile/{$oUserProfile->getLogin()}/votes/">Votes</a>
        </li>
        <!-- li {if $sMode=='ips'}class="active"{/if}>
            <a href="{router page='admin'}users/profile/{$oUserProfile->getLogin()}/ips/">IPs</a>
        </li -->
    </ul>

    {if $sMode=='topics'}
        {include file="$sTemplatePathAction/users_profile_topics.tpl"}
    {elseif $sMode=='blogs'}
        {include file="$sTemplatePathAction/users_profile_blogs.tpl"}
    {elseif $sMode=='comments'}
        {include file="$sTemplatePathAction/users_profile_comments.tpl"}
    {elseif $sMode=='voted'}
        {include file="$sTemplatePathAction/users_profile_voted.tpl"}
    {elseif $sMode=='votes'}
        {include file="$sTemplatePathAction/users_profile_votes.tpl"}
    {else}
        {include file="$sTemplatePathAction/users_profile_info.tpl"}
    {/if}

</div>

{/block}

{block name="sidebar" prepend}
{include file="$sTemplatePath/blocks/block.admin_user.tpl"}
{/block}

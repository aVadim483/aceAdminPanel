<table class="table table-striped table-bordered table-condensed users-list">
    <thead>
    <tr>
        <th>
            <input type="checkbox" id="id_0" onclick="aceAdmin.selectAllUsers(this);"/>
        </th>
        <th>
        {if $sUserListSort=='id'}
            <a href="#" onclick="aceAdmin.sortToggle('id', '{$sUserListOrder}'); return false;"><b> id </b></a>
            {if $sUserListOrder==1}
                <div class="adm_sort_asc"></div>{else}
                <div class="adm_sort_desc"></div>{/if}
            {else}
            <div class="adm_sort_none"></div>
            <a href="#" onclick="aceAdmin.sortToggle('id'); return false;"> id </a>
        {/if}
        </th>
        <th>
        {if $sUserListSort=='login'}
            <a href="#"
               onclick="aceAdmin.sortToggle('login', '{$sUserListOrder}'); return false;"><b> {$oLang->user}</b></a>
            {if $sUserListOrder==1}
                <div class="adm_sort_asc"></div>{else}
                <div class="adm_sort_desc"></div>{/if}
            {else}
            <div class="adm_sort_none"></div>
            <a href="#" onclick="aceAdmin.sortToggle('login'); return false;"> {$oLang->user} </a>
        {/if}
        </th>
        <th>
        {if $sUserListSort=='regdate'}
            <a href="#"
               onclick="aceAdmin.sortToggle('regdate', '{$sUserListOrder}'); return false;"><b> {$oLang->adm_users_date_reg} </b></a>
            {if $sUserListOrder==1}
                <div class="adm_sort_asc"></div>{else}
                <div class="adm_sort_desc"></div>{/if}
            {else}
            <div class="adm_sort_none"></div>
            <a href="#"
               onclick="aceAdmin.sortToggle('regdate'); return false;"> {$oLang->adm_users_date_reg} </a>
        {/if}
        </th>
        <th>
        {if $sUserListSort=='reg_ip'}
            <a href="#"
               onclick="aceAdmin.sortToggle('reg_ip', '{$sUserListOrder}'); return false;"><b> {$oLang->adm_users_ip_reg} </b></a>
            {if $sUserListOrder==1}
                <div class="adm_sort_asc"></div>{else}
                <div class="adm_sort_desc"></div>{/if}
            {else}
            <div class="adm_sort_none"></div>
            <a href="#" onclick="aceAdmin.sortToggle('reg_ip'); return false;"> {$oLang->adm_users_ip_reg} </a>
        {/if}
        </th>
        <th>
        {if $sUserListSort=='email'}
            <a href="#" onclick="aceAdmin.sortToggle('email', '{$sUserListOrder}'); return false;"><b>
                E-mail </b></a>
            {if $sUserListOrder==1}
                <div class="adm_sort_asc"></div>{else}
                <div class="adm_sort_desc"></div>{/if}
            {else}
            <div class="adm_sort_none"></div>
            <a href="#" onclick="aceAdmin.sortToggle('email'); return false;"> E-mail </a>
        {/if}
        </th>
    {if $oConfig->GetValue('general.reg.activation')}
        <th>
            {if $sUserListSort=='activated'}
                <a href="#"
                   onclick="aceAdmin.sortToggle('activated', '{$sUserListOrder}'); return false;"><b> {$oLang->adm_users_activated} </b></a>
                {if $sUserListOrder==1}
                    <div class="adm_sort_asc"></div>{else}
                    <div class="adm_sort_desc"></div>{/if}
                {else}
                <div class="adm_sort_none"></div>
                <a href="#"
                   onclick="aceAdmin.sortToggle('activated'); return false;"> {$oLang->adm_users_activated} </a>
            {/if}
        </th>
    {/if}
        <th>
        {if $sUserListSort=='last_date'}
            <a href="#"
               onclick="aceAdmin.sortToggle('last_date', '{$sUserListOrder}'); return false;"><b> {$oLang->adm_users_last_activity} </b></a>
            {if $sUserListOrder==1}
                <div class="adm_sort_asc"></div>{else}
                <div class="adm_sort_desc"></div>{/if}
            {else}
            <div class="adm_sort_none"></div>
            <a href="#"
               onclick="aceAdmin.sortToggle('last_date'); return false;"> {$oLang->adm_users_last_activity} </a>
        {/if}
        </th>
        <th>
        {if $sUserListSort=='last_ip'}
            <a href="#" onclick="aceAdmin.sortToggle('last_ip', '{$sUserListOrder}'); return false;"><b> Last
                IP </b></a>
            {if $sUserListOrder==1}
                <div class="adm_sort_asc"></div>{else}
                <div class="adm_sort_desc"></div>{/if}
            {else}
            <div class="adm_sort_none"></div>
            <a href="#" onclick="aceAdmin.sortToggle('last_ip'); return false;"> Last IP </a>
        {/if}
        </th>
        <th>{if $sMode!='admins'}{$oLang->adm_users_banned}{else}&nbsp;{/if}</th>
    </tr>
    </thead>

    <tbody>
    {foreach $aUserList as $oUser}
        {if $oConfig->GetValue('general.reg.activation') AND !$oUser->getDateActivate()}
            {assign var=classIcon value='icon-gray'}
            {elseif $oUser->IsBannedByLogin()}
            {assign var=classIcon value='icon-red'}
            {elseif $oUser->isAdministrator()}
            {assign var=classIcon value='icon-green'}
            {else}
            {assign var=classIcon value=''}
        {/if}
    <tr class="selectable">
        <td class="checkbox">
            {if $oUserCurrent->GetId()!=$oUser->getId()}
                <input type="checkbox" id="login_{$oUser->GetLogin()}" onclick="aceAdmin.user.select()"/>
                {else}
                &nbsp;
            {/if}
        </td>
        <td class="number"> {$oUser->getId()} &nbsp;</td>
        <td {if $oUserCurrent->GetId()==$oUser->getId()}style="font-weight:bold;"{/if}>
            <i class="icon-user {$classIcon}"></i>
            <a href="{router page='admin'}users/profile/{$oUser->getLogin()}/"
               class="link">{$oUser->getLogin()}</a></td>
        <td class="center">{$oUser->getDateRegister()}</td>
        <td class="center ip-split">
            {$oUser->getIpRegister()}
        </td>
        <td>{$oUser->getUserMail()}</td>
        {if $oConfig->GetValue('general.reg.activation')}
            <td>&nbsp;
                {if $oUser->getDateActivate()}{$oUser->getDateActivate()}
                    {else}<a
                        href="{router page='admin'}users/activate/{$oUser->getLogin()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}">{$oLang->adm_users_activate}</a>{/if}
            </td>
        {/if}
        <td class="center">
            {assign var="oSession" value=$oUser->getSession()}
            {if $oSession}{$oSession->getDateLast()}{/if}
        </td>
        <td class="center ip-split">
            {if $oSession}{$oSession->getIpLast()}{/if}
        </td>
        {if $sMode=='admins'}
            <td class="center">
                {if $oUser->GetLogin()!='admin'}
                    <a href="{router page='admin'}users/admins/del/?user_login={$oUser->getLogin()}&security_ls_key={$LIVESTREET_SECURITY_KEY}"
                       class="link">{$oLang->adm_exclude}</a>&nbsp;
                {/if}
            </td>
            {else}
            <td class="center">{if $oUser->isBanned()}{if $oUser->getBanLine()}{$oUser->getBanLine()}{else}
                unlim{/if}{/if}</td>
        {/if}
    </tr>
    {/foreach}
    </tbody>
</table>

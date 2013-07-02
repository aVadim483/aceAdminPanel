{if $aUserList}
<table class="table table-striped table-bordered table-condensed users-list">
    <thead>
    <tr>
        <th>ID</th>
        <th>{$oLang->user}</th>
        <th>{$oLang->_adm_users_ip_reg}</th>
        <th>Last IP</th>
        <th>{$oLang->_adm_ban_upto}</th>
        <th>{$oLang->_adm_ban_comment}</th>
        <th>&nbsp;</th>
    </tr>
    </thead>

    <tbody>
        {foreach $aUserList as $oUser}
        <tr>
            <td class="number"> {$oUser->getId()} &nbsp;</td>
            <td {if $oUserCurrent->GetId()==$oUser->getId()}style="font-weight:bold;"{/if}>
                <i class="icon-user icon-red"></i>
                <a href="{router page='admin'}users/profile/{$oUser->getLogin()}/"
                   class="link">{$oUser->getLogin()}</a></td>
            <td class="center ip-split">
                {$oUser->getIpRegister()}
            </td>
            <td class="center ip-split">
                {assign var="oSession" value=$oUser->getSession()}
            {if $oSession}{$oSession->getIpLast()}{/if}
            </td>
            <td class="center">{if $oUser->getBanLine()}{$oUser->getBanLine()}{else}unlim{/if}</td>
            <td>{$oUser->getBanComment()}</td>
            <td class="center">
                <a href="{router page='admin'}banlist/users/del/{$oUser->getLogin()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}"
                   class="btn btn-mini" title="{$oLang->adm_exclude}"><i class="icon-thumbs-up"></i></a>
            </td>
        </tr>
        {/foreach}
    </tbody>
</table>
{else}
    {$oLang->user_empty}
{/if}
{if $aIpList}
{include file='paging.tpl'}
<table class="table table-striped table-bordered table-condensed ips-list">
    <thead>
    <tr>
        <th>&nbsp;</th>
        <th>IP</th>
        <th>{$oLang->_adm_users_banned}</th>
        <th>{$oLang->_adm_ban_upto}</th>
        <th>{$oLang->_adm_ban_comment}</th>
        <th>&nbsp;</th>
    </tr>
    </thead>

    <tbody>
    {foreach from=$aIpList item=aIp name=ip}
    <tr>
        <td class="number"> {$smarty.foreach.ip.iteration}&nbsp; </td>
        <td class="center">{$aIp.ip1} - {$aIp.ip2}</td>
        <td class="center">{$aIp.bandate}</td>
        <td class="center">{if $aIp.banunlim}unlim{else}{$aIp.banline}{/if}</td>
        <td class="center">{$aIp.bancomment}</td>
        <td class="center">
            <a href="{router page='admin'}banlist/ips/del/{$aIp.id}/?security_ls_key={$LIVESTREET_SECURITY_KEY}"
               class="btn btn-mini" title="{$oLang->adm_exclude}"><i class="icon-thumbs-up"></i></a>
        </td>
    </tr>
    {/foreach}
    </tbody>
</table>
{include file='paging.tpl'}
{else}
    {$oLang->user_empty}
{/if}

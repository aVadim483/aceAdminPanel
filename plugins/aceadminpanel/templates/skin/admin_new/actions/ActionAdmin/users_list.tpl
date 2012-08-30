{extends file="$sTemplatePath/actions/ActionAdmin/users.tpl"}

{block name="content"}
{literal}
<script type="text/javascript">
function AdminSort(sort, order) {
  var i, el;
  if (document.getElementById) {
    if ((el=document.getElementById('user_list_sort'))) el.value=sort;
    if ((el=document.getElementById('user_list_order'))) el.value=order;
    if ((el=document.getElementById('admin_form_seek'))) el.submit();
  }
}

function AdminSortToggle(sort, order) {
    AdminSort(sort, (order==1)?2:1);
}

function AdminSelectUser(checked, id_login) {
    var i, el, pos, len;
    var login=id_login.substr(6, 255);
    if (document.getElementById) {
        if ((el=document.getElementById('users_list'))) {
            pos=el.value.indexOf(login+', ');
            if (pos==0) {len=login.length+2;}
            else {
                pos=el.value.indexOf(', '+login+', ');
                if (pos>0) {pos+=1; len=login.length+2;}
            }
            if (checked && pos==-1) {
                el.value+=login+', ';
            } else if (!checked && pos!=-1) {
                if (pos==0) el.value=el.value.substr(len, 255);
                else el.value=el.value.substr(0, pos)+el.value.substr(pos+len, 255);
            }
            $('users_list_view').set('text', el.value);
            return;
        }
    }
}

function AdminSelectAll(checked) {
    var i, el;
    if (document.getElementsByTagName) {
        var list=document.getElementsByTagName("input");
        for (i=0; i<list.length; i++) {
            el = list[i];
            if (el.id && el.id.substr(0, 6)=='login_') {
                if ((el.checked=checked)) AdminSelectUser(checked, el.id)
            }
        }
        if (!checked && (el=$('users_list'))) {
            el.value='';
            $('users_list_view').set('text', '');
        }
    }
}
</script>
{/literal}


<h3>{$oLang->adm_users_list} <span class="badge">{$aStat.count_all}</span></h3>

<ul class="nav nav-tabs">
    <li {if $sMode=='all' || $sMode==''}class="active"{/if}><a href="{router page='admin'}users/list/">All users</a></li>
    <li {if $sMode=='admins'}class="active"{/if}><a href="{router page='admin'}users/admins/">Admins</a></li>
</ul>

{if $aUserList}
{include file="$sTemplatePath/inc.paging.tpl"}
<table class="table table-striped table-bordered table-condensed users-list">
    <thead>
    <tr>
        <th><input type="checkbox" id="id_0" onclick="AdminSelectAll(this.checked)" /></th>
        <th>
            {if $sUserListSort=='id'}
            <a href="#" onclick="AdminSortToggle('id', '{$sUserListOrder}'); return false;"><b> id </b></a>
            {if $sUserListOrder==1}<div class="adm_sort_asc"></div>{else}<div class="adm_sort_desc"></div>{/if}
            {else}
            <div class="adm_sort_none"></div>
            <a href="#" onclick="AdminSortToggle('id'); return false;"> id </a>
            {/if}
        </th>
        <th>
            {if $sUserListSort=='login'}
            <a href="#" onclick="AdminSortToggle('login', '{$sUserListOrder}'); return false;"><b> {$oLang->user}</b></a>
            {if $sUserListOrder==1}<div class="adm_sort_asc"></div>{else}<div class="adm_sort_desc"></div>{/if}
            {else}
            <div class="adm_sort_none"></div>
            <a href="#" onclick="AdminSortToggle('login'); return false;"> {$oLang->user} </a>
            {/if}
        </th>
        <th>
            {if $sUserListSort=='regdate'}
            <a href="#" onclick="AdminSortToggle('regdate', '{$sUserListOrder}'); return false;"><b> {$oLang->adm_users_date_reg} </b></a>
            {if $sUserListOrder==1}<div class="adm_sort_asc"></div>{else}<div class="adm_sort_desc"></div>{/if}
            {else}
            <div class="adm_sort_none"></div>
            <a href="#" onclick="AdminSortToggle('regdate'); return false;"> {$oLang->adm_users_date_reg} </a>
            {/if}
        </th>
        <th>
            {if $sUserListSort=='reg_ip'}
            <a href="#" onclick="AdminSortToggle('reg_ip', '{$sUserListOrder}'); return false;"><b> {$oLang->adm_users_ip_reg} </b></a>
            {if $sUserListOrder==1}<div class="adm_sort_asc"></div>{else}<div class="adm_sort_desc"></div>{/if}
            {else}
            <div class="adm_sort_none"></div>
            <a href="#" onclick="AdminSortToggle('reg_ip'); return false;"> {$oLang->adm_users_ip_reg} </a>
            {/if}
        </th>
	{if $oConfig->GetValue('general.reg.activation')}
        <th>
            {if $sUserListSort=='activated'}
            <a href="#" onclick="AdminSortToggle('activated', '{$sUserListOrder}'); return false;"><b> {$oLang->adm_users_activated} </b></a>
            {if $sUserListOrder==1}<div class="adm_sort_asc"></div>{else}<div class="adm_sort_desc"></div>{/if}
            {else}
            <div class="adm_sort_none"></div>
            <a href="#" onclick="AdminSortToggle('activated'); return false;"> {$oLang->adm_users_activated} </a>
            {/if}
        </th>
	{/if}
        <th>
            {if $sUserListSort=='last_date'}
            <a href="#" onclick="AdminSortToggle('last_date', '{$sUserListOrder}'); return false;"><b> {$oLang->adm_users_last_activity} </b></a>
            {if $sUserListOrder==1}<div class="adm_sort_asc"></div>{else}<div class="adm_sort_desc"></div>{/if}
            {else}
            <div class="adm_sort_none"></div>
            <a href="#" onclick="AdminSortToggle('last_date'); return false;"> {$oLang->adm_users_last_activity} </a>
            {/if}
        </th>
        <th>
            {if $sUserListSort=='last_ip'}
            <a href="#" onclick="AdminSortToggle('last_ip', '{$sUserListOrder}'); return false;"><b> Last IP </b></a>
            {if $sUserListOrder==1}<div class="adm_sort_asc"></div>{else}<div class="adm_sort_desc"></div>{/if}
            {else}
            <div class="adm_sort_none"></div>
            <a href="#" onclick="AdminSortToggle('last_ip'); return false;"> Last IP </a>
            {/if}
        </th>
        <th>{if $sMode!='admins'}{$oLang->adm_users_banned}{else}&nbsp;{/if}</th>
    </tr>
    </thead>

    <tbody>
    {foreach from=$aUserList item=oUser name=el2}
    {if $smarty.foreach.el2.iteration % 2  == 0}
         {assign var=className value=''}
    {else}
         {assign var=className value='colored'}
    {/if}
    {if $oConfig->GetValue('general.reg.activation') AND !$oUser->getDateActivate()}
        {assign var=classIcon value='icon-gray'}
    {elseif $oUser->IsBannedByLogin()}
        {assign var=classIcon value='icon-red'}
    {elseif $oUser->isAdministrator()}
        {assign var=classIcon value='icon-green'}
    {else}
        {assign var=classIcon value=''}
    {/if}
    <tr class="{$className}">
        <td class="center">
            {if $oUserCurrent->GetId()!=$oUser->getId()}
            <input type="checkbox" id="login_{$oUser->GetLogin()}" onclick="AdminSelectUser(this.checked, this.id)" />
            {else}
            &nbsp;
            {/if}
        </td>
        <td class="number"> {$oUser->getId()} &nbsp;</td>
        <td {if $oUserCurrent->GetId()==$oUser->getId()}style="font-weight:bold;"{/if}>
            <i class="icon-user {$classIcon}"></i>
            <a href="{router page='admin'}users/profile/{$oUser->getLogin()}/" class="link">{$oUser->getLogin()}</a> </td>
        <td class="center">{$oUser->getDateRegister()}</td>
        <td class="center">{$oUser->getIpRegister()}</td>
	{if $oConfig->GetValue('general.reg.activation')}
        <td>&nbsp;
            {if $oUser->getDateActivate()}{$oUser->getDateActivate()}
            {else}<a href="{router page='admin'}users/activate/{$oUser->getLogin()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}">{$oLang->adm_users_activate}</a>{/if}
        </td>
        {/if}
        <td class="center">
            {assign var="oSession" value=$oUser->getSession()}
            {if $oSession}{date_format date=$oSession->getDateLast()}{/if}
        </td>
        <td class="center">
            {if $oSession}{$oSession->getIpLast()}{/if}
        </td>
	{if $sMode=='admins'}
        <td class="center">
            {if $oUser->GetLogin()!='admin'}
            <a href="{router page='admin'}users/admins/del/?user_login={$oUser->getLogin()}&security_ls_key={$LIVESTREET_SECURITY_KEY}" class="link">{$oLang->adm_exclude}</a>&nbsp;
            {/if}
        </td>
	{else}
        <td class="center">{if $oUser->isBanned()}{if $oUser->getBanLine()}{$oUser->getBanLine()}{else}unlim{/if}{/if}</td>
	{/if}
    </tr>
    {/foreach}
    </tbody>
</table>
{include file="$sTemplatePath/inc.paging.tpl"}
{else}
    {$oLang->user_empty}
{/if}
{/block}
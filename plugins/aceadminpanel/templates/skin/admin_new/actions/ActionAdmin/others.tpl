{extends file='index.tpl'}

{block name="content"}
<h3>{$oLang->_adm_menu_additional_item}</h3>

<ul class="nav nav-list">
    <li><a href="{router page="admin"}userfields/">{$aLang.admin_list_userfields}</a></li>
    <li><a href="{router page="admin"}restorecomment/?security_ls_key={$LIVESTREET_SECURITY_KEY}">{$aLang.admin_list_restorecomment}</a></li>
    <li><a href="{router page="admin"}recalcfavourite/?security_ls_key={$LIVESTREET_SECURITY_KEY}">{$aLang.admin_list_recalcfavourite}</a></li>
    <li><a href="{router page="admin"}recalcvote/?security_ls_key={$LIVESTREET_SECURITY_KEY}">{$aLang.admin_list_recalcvote}</a></li>
    <li><a href="{router page="admin"}recalctopic/?security_ls_key={$LIVESTREET_SECURITY_KEY}">{$aLang.admin_list_recalctopic}</a></li>
    {hook run='admin_action_item'}
</ul>
<br/>
<div class="nav nav-list">
    {hook run='admin_action'}
</div>
{/block}


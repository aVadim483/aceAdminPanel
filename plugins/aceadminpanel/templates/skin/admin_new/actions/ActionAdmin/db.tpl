{extends file="index.tpl"}

{block name="content"}
<h3>{$oLang->_adm_menu_db}</h3>

<ul class="nav nav-list">
    <li><a href="{router page="admin"}db/blogs/">{$oLang->_adm_db_check_deleted_blogs}</a></li>
    {hook run='admin_action_db_item'}
</ul>
{/block}


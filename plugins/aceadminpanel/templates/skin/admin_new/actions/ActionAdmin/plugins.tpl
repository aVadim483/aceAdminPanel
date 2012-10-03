{extends file='index.tpl'}

{block name="content"}

<h3>
    {$oLang->_adm_menu_categories}
</h3>

{if $include_tpl}
    {include file="$include_tpl"}
{/if}

{/block}

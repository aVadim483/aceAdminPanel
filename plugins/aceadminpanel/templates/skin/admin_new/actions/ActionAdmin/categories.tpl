{extends file='index.tpl'}

{block name="content"}

<h3>
{$oLang->_mblog_categories}
{if $aParams.0=='new'}
    &rarr; {$oLang->adm_pages_new}
    {elseif $aParams.0=='edit'}
    &rarr; {$oLang->page_edit} "{$oPageEdit->getTitle()}"
    {elseif $aParams.0=='options'}
    &rarr; {$oLang->adm_pages_options}
{/if}
</h3>

{if $include_tpl}
    {include file="$include_tpl"}
{else}
    {include file="$sTemplatePathAction/table_pages.tpl"}
    {include file="$sTemplatePath/inc.paging.tpl"}
{/if}


{/block}

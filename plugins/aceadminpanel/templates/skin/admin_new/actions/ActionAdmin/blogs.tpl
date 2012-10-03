{extends file='index.tpl'}

{block name="content"}

<h3>{$oLang->adm_menu_blogs}</h3>
<div class="topic">

    <ul class="nav nav-tabs">
        <li class="nav-tabs-add">
            <span><i class="icon-plus-sign icon-disabled"></i></span>
        </li>
        <li {if $sMode=='all' || $sMode==''}class="active"{/if}><a href="{router page='admin'}blogs/list/">all <span class="badge">{$iBlogsTotal}</span></a></li>
        {foreach $aBlogTypes as $aBlogType}
        <li {if $sMode==$aBlogType.blog_type}class="active"{/if}>
            <a href="{router page='admin'}blogs/list/{$aBlogType.blog_type}/">{$aBlogType.blog_type} <span class="badge">{$aBlogType.blog_cnt}</span></a>
        </li>
        {/foreach}
    </ul>

    {include file="$sTemplatePathAction/table_blogs.tpl"}
    {include file="$sTemplatePath/inc.paging.tpl"}
</div>

{/block}
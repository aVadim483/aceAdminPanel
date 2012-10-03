<ul class="nav nav-tabs">
    <li class="nav-tabs-add">
        <a href="{router page='admin'}pages/new/" title="{$oLang->_page_admin_action_edit}">
            <i class="icon-plus-sign"></i>
        </a>
    </li>
    <li class="active nav-tab-empty"><a href="#">&nbsp;</a></li>
</ul>

<table class="table table-striped table-bordered table-condensed">
    <thead>
    <tr>
        <th></th>
        <th>ID</th>
        <th>{$oLang->page_admin_title}</th>
        <th style="width:250px;">{$oLang->page_admin_url}</th>
        <th>{$oLang->page_admin_main}</th>
        <th></th>
    </tr>
    </thead>

    <tbody>
{foreach from=$aPages item=oPage name=el2}
    <tr>
        <td align="center">
            <a href="{router page='admin'}pages/edit/{$oPage->getId()}/" title="{$oLang->_page_admin_action_edit}">
                <i class="icon-pencil"></i></a>
            <a href="{router page='admin'}pages/delete/?page_id={$oPage->getId()}&security_ls_key={$LIVESTREET_SECURITY_KEY}"
               onclick="return confirm('«{$oPage->getTitle()}»: {$oLang->page_admin_action_delete_confirm}');"
               title="{$oLang->page_admin_action_delete}"><i class="icon-remove"></i>
            </a>
            {if $smarty.foreach.el2.first}
                <i class="icon-chevron-up icon-disabled"></i>
                {else}
                <a href="{router page='admin'}pages/sort/{$oPage->getId()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}" title="{$aLang.page_admin_sort_up} ({$oPage->getSort()})">
                    <i class="icon-chevron-up"></i>
                </a>
            {/if}
            {if $smarty.foreach.el2.last}
                <i class="icon-chevron-down icon-disabled"></i>
                {else}
                <a href="{router page='admin'}pages/sort/{$oPage->getId()}/down/?security_ls_key={$LIVESTREET_SECURITY_KEY}" title="{$aLang.page_admin_sort_down} ({$oPage->getSort()})">
                    <i class="icon-chevron-down"></i>
                </a>
            {/if}
        </td>
        <td class="number">
            {$oPage->getId()}
        </td>
        <td class="name">
            <div class="{if $oPage->getActive()}active{else}unactive{/if}"></div>
            {if $oPage->getLevel()==0}
                <i class="icon-folder-open"></i>
            {else}
                <i class="icon-file" style="margin-left: {$oPage->getLevel()*20}px;"></i>
            {/if}

            {if $oPage->getActive()}<a
                    href="{router page='page'}{$oPage->getUrlFull()}/">{/if}{$oPage->getTitle()}{if $oPage->getActive()}</a>{/if}
        </td>
        <td>
            /{$oPage->getUrlFull()}/
        </td>
        <td class="center">
            {if $oPage->getMain()}
                    {$aLang.page_admin_active_yes}
                {else}
                    {$aLang.page_admin_active_no}
                {/if}
        </td>
        <td class="{if $oPage->getActive()}deactivate{else}activate{/if}">
            <strong>
                {if $oPage->getActive()}
                    <a href="{router page='admin'}pages/?page_id={$oPage->getId()}&action=deactivate&security_ls_key={$LIVESTREET_SECURITY_KEY}">{$aLang.adm_act_deactivate}</a>
                    {else}
                    <a href="{router page='admin'}pages/?page_id={$oPage->getId()}&action=activate&security_ls_key={$LIVESTREET_SECURITY_KEY}">{$aLang.adm_act_activate}</a>
                {/if}
            </strong>
        </td>
    </tr>
{/foreach}
    </tbody>

</table>

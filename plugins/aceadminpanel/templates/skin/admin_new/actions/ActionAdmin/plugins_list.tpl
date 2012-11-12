{extends file='index.tpl'}

{block name="content"}

{literal}
<script type="text/javascript">
function adminPluginUp(plugin)     {
    var row = $(plugin+'_row');
    var priority = $(plugin+'_priority').value;
    var prev = row.getPrevious();
    if (prev) {
        var prev_priority = $(prev.get('id').replace('_row', '_priority'));
        row.inject(prev, 'before');
        $(plugin+'_priority').value=prev_priority.value;
        prev_priority.value=priority;
    }
}

function adminPluginDown(plugin)     {
    var priority = $(plugin+'_priority').value;
    var row = $(plugin+'_row');
    var next = row.getNext();
    if (next) {
        var next_priority = $(next.get('id').replace('_row', '_priority'));
        row.inject(next, 'after');
        $(plugin+'_priority').value=next_priority.value;
        next_priority.value=priority;
    }
}

function adminPluginSave() {
   return true;
}
</script>
{/literal}

<h3>{$oLang->adm_plugins_title}</h3>
    <ul class="nav nav-tabs">
        <li class="nav-tabs-add">
            <span><i class="icon-plus-sign icon-disabled"></i></span>
        </li>
        <li {if $sMode=='all' || $sMode==''}class="active"{/if}><a href="{router page='admin'}plugins/list/all/">{$oLang->_adm_all_plugins}</a></li>
        <li {if $sMode=='active'}class="active"{/if}><a href="{router page='admin'}plugins/list/active/">{$oLang->_adm_active_plugins}</a></li>
        <li {if $sMode=='inactive'}class="active"{/if}><a href="{router page='admin'}plugins/list/inactive/">{$oLang->_adm_inactive_plugins}</a></li>
    </ul>

    <form action="{router page='admin'}plugins/" method="post" id="form_plugins_list">
        <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />
        <table class="table table-striped table-bordered table-condensed plugins-list">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" name="" onclick="aceAdmin.selectAllRows(this);" />
                    </th>
                    <th class="name">{$oLang->plugins_plugin_name}</th>
                    <th class="version">{$oLang->plugins_plugin_version}</th>
                    <th class="author">{$oLang->plugins_plugin_author}</th>
                    <th class="action">{$oLang->plugins_plugin_action}</th>
                    <th class="">{$oLang->adm_menu_settings}</th>
                </tr>
            </thead>

            <tbody id="plugin_list">
            {foreach from=$aPluginList item=oPlugin}
                <tr id="{$oPlugin->GetId()}_row" class="{if $oPlugin->IsActive()}active{else}inactive{/if} selectable">
                    <td class="checkbox">
                        <input type="checkbox" name="plugin_del[{$oPlugin->GetId()}]" class="form_plugins_checkbox" />
                    </td>
                    <td class="name">
                        <div class="{if $oPlugin->IsActive()}active{else}inactive{/if}"></div>
                        <div class="title">{$oPlugin->GetName()|escape:'html'}</div>
                        <div class="description">
                        <b>{$oPlugin->GetCode()}</b> - {$oPlugin->GetDescription()}
                        </div>
                        {if ($oPlugin->GetHomepage()>'')}
                        <div class="url">
                        Homepage: {$oPlugin->GetHomepage()}
                        </div>
                        {/if}
                    </td>
                    <td class="version">{$oPlugin->GetVersion()|escape:'html'}</td>
                    <td class="author">{$oPlugin->GetAuthor()|escape:'html'}</td>
                    <td class="action {if $oPlugin->IsActive()}deactivate{else}activate{/if}">
                            {if $oPlugin->isActive()}
                                <div class="btn-group btn-switch-on" rel="tooltip" title="{$oLang->adm_act_deactivate}" onclick="aceAdmin.plugin.turnOff('{$oPlugin->GetId()}'); return false;">
                                    <button class="btn btn-outset"></button>
                                    <button class="btn btn-inset">ON</button>
                                </div>
                            {else}
                                <div class="btn-group btn-switch-off" rel="tooltip" title="{$oLang->adm_act_activate}" onclick="aceAdmin.plugin.turnOn('{$oPlugin->GetId()}'); return false;">
                                    <button class="btn btn-inset">OFF</button>
                                    <button class="btn btn-outset"></button>
                                </div>
                            {/if}
                    </td>
                    <td class="center">
                        {if $oPlugin->isActive() AND $oPlugin->GetProperty('settings') != ''}
                                <a href="{$oPlugin->GetProperty('settings')}">{$aLang.plugins_plugin_settings}</a>
                        {/if}
                    </td>
                </tr>
        {/foreach}
            </tbody>
        </table>
        <!-- <br/> {$oLang->adm_plugin_priority_notice} -->
        <div class="form-actions fix-on-container">
            <div class="navbar fix-on-bottom">
                <div class="navbar-inner">
                    <div class="container">
                        <button type="submit" name="submit_plugins_del" class="btn btn-primary pull-right"
                                onclick="return ($$('.form_plugins_checkbox:checked').length==0)?false:confirm('{$aLang.plugins_delete_confirm}');">
                            {$aLang.plugins_submit_delete}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- <input type="submit" name="submit_plugins_save" value="{$aLang.adm_save}" onclick="adminPluginSave();" /> -->
    </form>

{/block}
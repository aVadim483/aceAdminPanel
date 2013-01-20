{extends file='index.tpl'}

{block name="content"}

<h3>{$oLang->_adm_menu_banlist}</h3>

<div class="topic">

    <ul class="nav nav-tabs">
        <li class="nav-tabs-add">
            <span><i class="icon-plus-sign icon-disabled"></i></span>
        </li>
        <li {if $sMode=='ids' || $sMode==''}class="active"{/if}><a href="{router page='admin'}banlist/users/">{$oLang->_adm_banlist_ids}</a></li>
        <li {if $sMode=='ips'}class="active"{/if}><a href="{router page='admin'}banlist/ips/">{$oLang->_adm_banlist_ips}</a></li>
    </ul>

    {if $sMode=='ips'}
        {include file="$sTemplatePathAction/banlist_ips.tpl"}
    {else}
        {include file="$sTemplatePathAction/banlist_ids.tpl"}
    {/if}
    {include file="$sTemplatePath/inc.paging.tpl"}
</div>

{/block}

{block name="sidebar"}

<div class="accordion-group no-border">
    <div class="accordion-heading">
        <button class="btn-block btn left" data-target="#admin_form_ban" data-toggle="collapse"
                data-parent="#user-comands-switch">
        {if $aFilter}<i class="icon-filter icon-green pull-right"></i>{/if}
            <i class="icon-ban-circle"></i>
        {$oLang->_adm_banlist_add}
        </button>
    </div>

    <div class="accordion-body collapse collapse-save" id="admin_form_ban">
        <form method="post" action="" class="well well-small">
            <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}"/>

            <div class="row control-group {if $sUserFilterLogin}success{/if}">
                <label for="user_filter_login">{$oLang->_adm_user_login}</label>

                <div class="input-prepend">
                    <span class="add-on"><i class="icon-user"></i></span><input type="text" name="user_filter_login"
                                                                                id="user_filter_login"
                                                                                value="{$sUserFilterLogin}"
                                                                                class="wide"/>
                </div>
            </div>

            <div class="row control-group {if $sUserFilterIp}success{/if}">
                <label for="user_filter_ip1">{$oLang->_adm_user_ip}</label>
                <input type="text" name="user_filter_ip1" id="user_filter_ip1" value="{$aUserFilterIp.0}"
                       maxlength="3"
                       class="ip-part" placeholder="*"/> .
                <input type="text" name="user_filter_ip2" id="user_filter_ip2" value="{$aUserFilterIp.1}"
                       maxlength="3"
                       class="ip-part" placeholder="*"/> .
                <input type="text" name="user_filter_ip3" id="user_filter_ip3" value="{$aUserFilterIp.2}"
                       maxlength="3"
                       class="ip-part" placeholder="*"/> .
                <input type="text" name="user_filter_ip4" id="user_filter_ip4" value="{$aUserFilterIp.3}"
                       maxlength="3"
                       class="ip-part" placeholder="*"/>
                <span class="help-block">{$oLang->_adm_user_filter_ip_notice}</span>
            </div>

            <label>{$oLang->_adm_ban_period}</label>
            <label class="radio">
                <input type="radio" name="ban_period" value="days" />
                {$oLang->_adm_ban_for}
                <input type="text" name="ban_days" id="ban_days" class="num1"/> {$oLang->adm_ban_days}
            </label>

            <label class="radio">
                <input type="radio" name="ban_period" value="unlim" checked />
                {$oLang->_adm_ban_unlim}
            </label>

            <label for="ban_comment">{$oLang->_adm_ban_comment}</label>
            <input type="text" name="ban_comment" id="ban_comment" maxlength="255"/>

            <input type="hidden" name="user_list_sort" id="user_list_sort" value="{$sUserListSort}"/>
            <input type="hidden" name="user_list_order" id="user_list_order" value="{$sUserListOrder}"/>
            <input type="hidden" name="adm_user_ref" value="{$sPageRef}"/>
            <input type="hidden" name="adm_user_action" value="adm_user_ban"/>
            <button type="submit" name="adm_action_submit" class="btn btn-danger">{$oLang->_adm_users_ban}</button>
        </form>
    </div>
</div>
{/block}

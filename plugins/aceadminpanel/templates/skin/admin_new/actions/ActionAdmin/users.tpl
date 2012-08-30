{extends file='index.tpl'}

{block name="sidebar"}
<script type="text/javascript">
    aceAdmin.messageSubmit = function (msg) {
        var i, el;
        if ((el = $('users_list'))) {
            if (!el.value) {
                alert(msg[0]);
                return false;
            }
        }
        if ((el = $('talk_title'))) {
            if (el.value.length < 2 || el.value.length > 200) {
                alert(msg[1]);
                return false;
            }
        }
        if ((el = $('talk_text'))) {
            if (el.value.length < 2 || el.value.length > 3000) {
                alert(msg[2]);
                return false;
            }
        }
        return true;
    }

    aceAdmin.filterReset = function (button) {
        var form = $(button).parent('form');
        form.find('input[type=text]').each(function () {
            $(this).val('');
        });
        var parent = form.parents('.row.users-form:first');
        if (parent) $.cookie(parent.attr('id'), null);
        form.submit();
    }


</script>

<div class="row users-form" id="admin_form_seek">
    <button class="btn-block btn left users-form" onclick="aceAdmin.formToggle('admin_form_seek', true);return false;">
        {if $aFilter}<i class="icon-filter icon-green pull-right"></i>{/if}
        <i class="icon-search"></i>
        {$oLang->adm_seek_users}
    </button>

    <form method="post" action="{router page='admin'}users/" class="well" style="display:none;">
        <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}"/>

        <div class="row control-group {if $sUserFilterLogin}success{/if}">
            <label for="user_filter_login">{$oLang->adm_user_login}</label>

            <div class="input-prepend">
                <span class="add-on"><i class="icon-user"></i></span><input type="text" name="user_filter_login"
                                                                            id="user_filter_login"
                                                                            value="{$sUserFilterLogin}" class="wide"/>
            </div>
        </div>

        <div class="row control-group {if $sUserFilterIp}success{/if}">
            <label for="user_filter_ip1">{$oLang->_adm_user_ip}</label>
            <input type="text" name="user_filter_ip1" id="user_filter_ip1" value="{$aUserFilterIp.0}" maxlength="3"
                   class="ip-part" placeholder="*"/> .
            <input type="text" name="user_filter_ip2" id="user_filter_ip2" value="{$aUserFilterIp.1}" maxlength="3"
                   class="ip-part" placeholder="*"/> .
            <input type="text" name="user_filter_ip3" id="user_filter_ip3" value="{$aUserFilterIp.2}" maxlength="3"
                   class="ip-part" placeholder="*"/> .
            <input type="text" name="user_filter_ip4" id="user_filter_ip4" value="{$aUserFilterIp.3}" maxlength="3"
                   class="ip-part" placeholder="*"/>
            <span class="help-block">{$oLang->_adm_user_filter_ip_notice}</span>
        </div>

        <div class="row control-group {if $aFilter.regdate}success{/if}">
            <label for="user_filter_regdate">{$oLang->_adm_users_date_reg}</label>

            <div class="input-prepend">
                <span class="add-on"><i class="icon-calendar"></i></span><input type="text" name="user_filter_regdate"
                                                                                id="user_filter_regdate"
                                                                                value="{$aFilter.regdate}"
                                                                                class="wide"/>
            </div>
            <span class="help-block">{$oLang->_adm_user_filter_regdate_notice}</span>
        </div>

        <div class="row control-group {if $aFilter.email}success{/if}"">
            <label for="user_filter_email">{$oLang->_adm_user_email}</label>

            <div class="input-prepend">
                <span class="add-on">@</span><input type="text" name="user_filter_email" id="user_filter_email"
                                                    value="{$aFilter.email}" maxlength="10"
                                                    class="wide"/>
            </div>
            <span class="help-block">{$oLang->_adm_user_filter_email_notice}</span>
        </div>

        <input type="hidden" name="user_list_sort" id="user_list_sort" value="{$sUserListSort}"/>
        <input type="hidden" name="user_list_order" id="user_list_order" value="{$sUserListOrder}"/>
        <input type="hidden" name="adm_user_ref" value="{$sPageRef}"/>
        <input type="hidden" name="adm_user_action" value="adm_user_seek"/>
        <button type="submit" name="adm_action_submit" class="btn btn-primary">{$oLang->_adm_seek}</button>
        <button type="reset" name="adm_action_reset" class="btn"
                onclick="aceAdmin.filterReset(this);return false;">{$oLang->_adm_reset}</button>
    </form>
</div>

<div class="row users-form" id="admin_form_send">
    <button class="btn-block btn left users-form" onclick="aceAdmin.formToggle('admin_form_send', true);return false;">
        <i class="icon-envelope"></i>
        {$oLang->user_write_prvmsg}
    </button>

    <form method="post" action="" class="well" style="display:none;">
        <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}"/>

        <div class="row">
            <label for="users_list">{$oLang->talk_create_users}:</label>
            <span id="users_list_view"></span>
            <input type="hidden" name="users_list" id="users_list"/>
        </div>

        <div class="row">
            <label>
                <input type="radio" name="send_common_message" id="send_common_message_yes" value="yes"
                       onclick="AdminMessageSeparate(this.checked)"/>
                {$oLang->_adm_send_common_message}
            </label>

            <label>
                <input type="radio" name="send_common_message" id="send_common_message_no" value="no" checked
                       onclick="AdminMessageSeparate(!this.checked)"/>
                {$oLang->_adm_send_separate_messages}
            </label>
            <span id="send_common_notice" class="help-block"
                  style="display:none;">{$oLang->_adm_send_common_notice}</span>
            <span id="send_separate_notice" class="help-block">{$oLang->_adm_send_separate_notice}</span>
        </div>

        <div class="row">
            <label for="talk_inbox_list">{$oLang->talk_menu_inbox_list}</label>
            <select name="talk_inbox_list" id="talk_inbox_list" onchange="AdminMessageSelect();">
                <option value="0">-- {$oLang->talk_menu_inbox_create} --</option>
                {if $aTalks}
                    {foreach from=$aTalks item=oTalk}
                        <option value="{$oTalk->getId()}">{$oTalk->getTitle()|escape:'html'}</option>
                    {/foreach}
                {/if}
            </select>
        </div>

        <div class="row">
            <label for="talk_title">{$oLang->talk_create_title}:</label>
            <input type="text" name="talk_title" id="talk_title" maxlength="30" class="wide"/>
        </div>

        <div class="row">
            <label for="talk_text">{$oLang->talk_create_text}:</label><br/>
            <textarea name="talk_text" id="talk_text" cols="80" rows="12" class="wide"></textarea>
        </div>


        <div class="row">
            <input type="checkbox" name="send_copy_self" id="send_copy_self" checked/>
            <label for="send_copy_self">{$oLang->_adm_send_copy_self}</label>
        </div>

        <input type="hidden" name="adm_user_ref" value="{$sPageRef}"/>
        <input type="hidden" name="adm_user_action" value="adm_user_message"/>
        <input type="submit" name="adm_action_submit" value="{$oLang->talk_create_submit}"
               onclick="return aceAdmin.messageSubmit(['{$oLang->talk_create_users_error}', '{$oLang->talk_create_title_error}', '{$oLang->talk_create_text_error}'])"/>

    </form>
</div>

<script type="text/javascript">
    $(function () {
        $('input.ip-part').focus(function () {
            $(this).select();
        });
        var forms = $('.row.users-form');
        forms.each(function () {
            var id = $(this).attr('id');
            if (id && id.indexOf('admin_form_') == 0) {
                if ($.cookie(id)) aceAdmin.formToggle(id);
            }
        });
    });
</script>

{/block}

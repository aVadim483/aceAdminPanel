{literal}
<script type="text/javascript">
js_admin["block_admin"] = {
    "domready":
        function() {
            new Autocompleter.Request.HTML(
                $('user_login_seek'),
                DIR_WEB_ROOT+'/include/ajax/userAutocompleter.php?security_ls_key='+LIVESTREET_SECURITY_KEY,
                {
                    'indicatorClass': 'autocompleter-loading', // class added to the input during request
                    'minLength': 1, // We need at least 1 character
                    'selectMode': 'pick', // Instant completion
                    'multiple': false // Tag support, by default comma separated
                }
            );
            new Autocompleter.Request.HTML(
                $('user_login_admin'),
                DIR_WEB_ROOT+'/include/ajax/userAutocompleter.php?security_ls_key='+LIVESTREET_SECURITY_KEY,
                {
                    'indicatorClass': 'autocompleter-loading', // class added to the input during request
                    'minLength': 1, // We need at least 1 character
                    'selectMode': 'pick', // Instant completion
                    'multiple': false // Tag support, by default comma separated
                }
            );
        }
}

document.addEvent('domready', js_admin["block_admin"].domready);

function AdminAction(n) {
    var i, el;
    for(i=1;i<=3;i++) {
        if (i==n) {
            if ((el=$('admin-block-a'+i))) el.style.display='none';
            if ((el=$('admin-block-t'+i))) el.style.display='';
            if ((el=$('admin-block-d'+i))) el.style.display='';
            if ((n==1) && (el=$('admin_ip1_1'))) el.focus();
        } else {
            if ((el=$('admin-block-a'+i))) el.style.display='';
            if ((el=$('admin-block-t'+i))) el.style.display='none';
            if ((el=$('admin-block-d'+i))) el.style.display='none';
        }
    }
}

function AdminReset() {
    var i, el;
    if ((el=$('user_login_seek'))) el.value='';
    if ((el=$('user_ip1_seek'))) el.value='*';
    if ((el=$('user_ip2_seek'))) el.value='*';
    if ((el=$('user_ip3_seek'))) el.value='*';
    if ((el=$('user_ip4_seek'))) el.value='*';
    if ((el=$('user_regdate_seek'))) el.value='*';
    if ((el=$('admin_form_seek'))) el.submit();
}

function AdminSelect(id) {
    var i, el;
    if ((el=document.getElementById(id))) el.select();
}

function AdminMessageSelect() {
    var params = new Hash();
    params['talk_id'] = $('talk_inbox_list').value;
    params['security_ls_key'] = LIVESTREET_SECURITY_KEY;

    new Request.JSON({
        url: aRouter['ajax'] + 'admin/gettalk/',
        noCache: true,
        data: params,
        onSuccess: function(result) {
            if (!result) {
                msgErrorBox.alert('Error', 'Error: no result. Please try again later');
            }
            if (result.bStateError) {
                msgErrorBox.alert(result.sTitle?result.sTitle:'Error', result.sText?result.sText:'Please try again later');
            } else {
                if ($('talk_title')) {$('talk_title').value=result.sTitle;}
                if ($('talk_text')) {$('talk_text').value=result.sText;}
            }
        },
        onFailure: function() {
            msgErrorBox.alert('Error', 'Failure. Please try again later');
        }
    }).send();
}

function AdminMessageSeparate(param) {
    if (param) {
        $('send_separate_notice').style.display='none';
        $('send_common_notice').style.display='';
    } else {
        $('send_separate_notice').style.display='';
        $('send_common_notice').style.display='none';
    }
}

function AdminMessageSubmit(msg) {
    var i, el;
    if ((el=$('users_list'))) {
        if (!el.value) {alert(msg[0]); return false;}
    }
    if ((el=$('talk_title'))) {
        if (el.value.length<2 || el.value.length>200) {alert(msg[1]); return false;}
    }
    if ((el=$('talk_text'))) {
        if (el.value.length<2 || el.value.length>3000) {alert(msg[2]); return false;}
    }
    return true;
}

</script>
{/literal}

<div class="block white">
    <div class="tl"><div class="tr"></div></div>
    <div class="cl"><div class="cr">
            <h2>{$oLang->_adm_users_action} &darr;</h2>
            <div>

                <h3><span id="admin-block-a1"><a href="#" onclick="AdminAction(1); return false;">{$oLang->_adm_seek}</a></span><span id="admin-block-t1" style="display:none;">{$oLang->_adm_seek}</span></h3>
                <form method="post" action="{router page='admin'}users/" id="admin_form_seek">
                    <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />
                    <div id="admin-block-d1" style="margin-left:20px;display:none;">
                        <p>
                            <label for="user_login_seek">{$oLang->_adm_user_login}</label><br />
                            <input type="text" name="user_login_seek" id="user_login_seek" value="{$sUserLoginSeek}" maxlength="30" style="width:250px;" /><br />
                        </p>
                        <p>
                            <label for="user_ip1_seek">{$oLang->_adm_user_ip}</label><br />
                            <input type="text" name="user_ip1_seek" id="user_ip1_seek" value="{$aUserIp.0}" maxlength="3" style="width:30px;text-align:center;" onfocus="AdminSelect('user_ip1_seek')" /> .
                            <input type="text" name="user_ip2_seek" id="user_ip2_seek" value="{$aUserIp.1}" maxlength="3" style="width:30px;text-align:center;" onfocus="AdminSelect('user_ip2_seek')" /> .
                            <input type="text" name="user_ip3_seek" id="user_ip3_seek" value="{$aUserIp.2}" maxlength="3" style="width:30px;text-align:center;" onfocus="AdminSelect('user_ip3_seek')" /> .
                            <input type="text" name="user_ip4_seek" id="user_ip4_seek" value="{$aUserIp.3}" maxlength="3" style="width:30px;text-align:center;" onfocus="AdminSelect('user_ip4_seek')" />
                            <br />
                            <span class="help-block">{$oLang->_adm_user_ip_seek_notice}</span>
                        </p>
                        <p>
                            <label for="user_regdate_seek">{$oLang->_adm_users_date_reg}</label><br />
                            <input type="text" name="user_regdate_seek" id="user_regdate_seek" value="{$aFilter.regdate}" maxlength="10" style="width:250px;" /><br />
                            <span class="help-block">{$oLang->_adm_user_regdate_seek_notice}</span>
                        </p>
                        <!-- p>
                        <label for="user_mail_seek">{$oLang->user_mail}</label><br />
					<input type="text" name="user_mail_seek" id="user_mail_seek" value="{$aFilter.email}" maxlength="10" style="width:250px;" /><br />
					<span class="help-block">{$oLang->_adm_user_mail_seek_notice}</span>
                        </p -->
                        <p>
                            <input type="hidden" name="user_list_sort" id="user_list_sort" value="{$sUserListSort}" />
                            <input type="hidden" name="user_list_order" id="user_list_order" value="{$sUserListOrder}" />
                            <input type="hidden" name="adm_user_ref" value="{$sPageRef}" />
                            <input type="hidden" name="adm_user_action" value="adm_user_seek" />
                            <input type="submit" name="adm_action_submit" value="{$oLang->_adm_seek}" />
                            <input type="reset" name="adm_action_reset" value="{$oLang->_adm_reset}" onclick="AdminReset()" />
                        </p>
                    </div>
                </form>

                <div {if $sMode!='admins'}style="display:none;"{/if}>
                    <h3><span id="admin-block-a2"><a href="#" onclick="AdminAction(2); return false;">{$oLang->_adm_include}</a></span><span id="admin-block-t2" style="display:none;">{$oLang->_adm_include}</span></h3>
                    <form method="post" action="">
                        <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />
                        <div id="admin-block-d2" style="margin-left:20px;display:none;">
                            <p>
                                <label for="user_login_admin">{$oLang->_adm_user_login}</label><input type="text" name="user_login_admin" id="user_login_admin" maxlength="30" style="width:250px;" /><br />
                            </p>
                            <p>
                                <input type="hidden" name="adm_user_ref" value="{$sPageRef}" />
                                <input type="hidden" name="adm_user_action" value="adm_user_setadmin" />
                                <input type="submit" name="adm_action_submit" value="{$oLang->_adm_include}" />
                            </p>
                        </div>
                    </form>
                    <br />
                </div>

                <div>
                    <h3><span id="admin-block-a3"><a href="#" onclick="AdminAction(3); return false;">{$oLang->user_write_prvmsg}</a></span><span id="admin-block-t3" style="display:none;">{$oLang->user_write_prvmsg}</span></h3>
                    <form method="post" action="">
                        <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />
                        <div id="admin-block-d3" style="margin-left:20px;display:none;">
                            <p>
                                <label for="users_list">{$oLang->talk_create_users}:</label><br />
                                <span id="users_list_view"></span>
                                <input type="hidden" name="users_list" id="users_list" /><br />
                            </p>
                            <p>
                                <input type="radio" name="send_common_message" id="send_common_message_yes" value="yes" onclick="AdminMessageSeparate(this.checked)" />
                                <label for="send_common_message_yes">{$oLang->_adm_send_common_message}</label><br />
                                <input type="radio" name="send_common_message" id="send_common_message_no" value="no" checked onclick="AdminMessageSeparate(!this.checked)" />
                                <label for="send_common_message_no">{$oLang->_adm_send_separate_messages}</label><br />
                                <span id="send_common_notice" class="form_note" style="display:none;">{$oLang->_adm_send_common_notice}</span>
                                <span id="send_separate_notice" class="form_note">{$oLang->_adm_send_separate_notice}</span>
                            </p>
                            <p>
                                <label for="talk_inbox_list">{$oLang->talk_menu_inbox_list}</label><br />
                                <select name="talk_inbox_list" id="talk_inbox_list" onchange="AdminMessageSelect();">
                                    <option value="0">-- {$oLang->talk_menu_inbox_create} --</option>
                                    {if $aTalks}
                                    {foreach from=$aTalks item=oTalk}
                                    <option value="{$oTalk->getId()}">{$oTalk->getTitle()|escape:'html'}</option>
                                    {/foreach}
                                    {/if}
                                </select>
                                <br />
                            </p>
                            <p>
                                <label for="talk_title">{$oLang->talk_create_title}:</label><br />
                                <input type="text" name="talk_title" id="talk_title" maxlength="30" style="width:250px;" /><br />
                            </p>
                            <p><label for="talk_text">{$oLang->talk_create_text}:</label><br />
                                <textarea name="talk_text" id="talk_text" cols="80" rows="12"></textarea>
                            </p>
                            <p>
                            <p>
                                <input type="checkbox" name="send_copy_self" id="send_copy_self" checked />
                                <label for="send_copy_self">{$oLang->_adm_send_copy_self}</label>
                            </p>
                            <input type="hidden" name="adm_user_ref" value="{$sPageRef}" />
                            <input type="hidden" name="adm_user_action" value="adm_user_message" />
                            <input type="submit" name="adm_action_submit" value="{$oLang->talk_create_submit}"
                                   onclick="return AdminMessageSubmit(['{$oLang->talk_create_users_error}', '{$oLang->talk_create_title_error}', '{$oLang->talk_create_text_error}'])" />
                            </p>
                        </div>
                    </form>
                    <br />
                </div>

            </div>
        </div></div>
    <div class="bl"><div class="br"></div></div>
</div>

{if $aFilter}
{literal}
<script type="text/javascript">
AdminAction(1);
</script>
{/literal}
{/if}

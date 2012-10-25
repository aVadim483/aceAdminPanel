{extends file='index.tpl'}

{block name="sidebar"}
<script type="text/javascript">
    if (!aceAdmin.user) aceAdmin.user = { };

    aceAdmin.user.messageSubmit = function (msg) {
        var i, el;
        if ((el = $('#users_list'))) {
            if (!el.val()) {
                alert(msg[0]);
                return false;
            }
        }
        if ((el = $('#talk_title'))) {
            if (el.val().length < 2 || el.value.length > 200) {
                alert(msg[1]);
                return false;
            }
        }
        if ((el = $('#talk_text'))) {
            if (el.val().length < 2 || el.value.length > 3000) {
                alert(msg[2]);
                return false;
            }
        }
        return true;
    }

    aceAdmin.user.filterReset = function (button) {
        var form = $(button).parent('form');
        form.find('input[type=text]').each(function () {
            $(this).val('');
        });
        var parent = form.parents('.row.users-form:first');
        if (parent) $.cookie(parent.attr('id'), null);
        form.submit();
    }


    aceAdmin.user.deleteConfirm = function (confirm) {
        if (!$('form .users_list').val()) {
            ls.msg.error('Error', '{$oLang->_adm_users_not_selected}');
        } else {
            $('#adm_users_del_confirm').modal();
        }
        return false;
    }

    aceAdmin.user.deleteSubmit = function (confirm) {
        $('#adm_users_del_confirm').modal('hide');
        if (confirm === true) {
            var form = $('#admin_user_del form');
            form.find('input[name=adm_del_login]').val($('form .users_list').val());
            form.submit();
        }
        return false;
    }

    aceAdmin.user.select = function (list) {
        //console.log(list);
        if (aceAdmin.isEmpty(list)) list = [];
        else if (typeof list == 'string') list = [list];

        $('tr.selectable td.checkbox input[type=checkbox]:checked').each(function () {
            var id = $(this).prop('id');
            if (id.indexOf('login_') === 0) {
                list.push(id.substr(6, 255));
            }
        });

        var view = '';
        $.each(list, function (index, item) {
            if (view) view += ', ';
            view += '<span class="popup-user">' + item + '</span>';
        });
        $('form .users_list').val(list.join(', '));
        $('form .users_list_view').html(view);
    }

</script>

<div class="accordion" id="user-comands-switch">
    {if $oUserProfile}
        {if $oUserProfile->IsBannedByLogin()}
        <div class="accordion-group no-border">
            <div class="accordion-heading">
                <button class="btn-block btn left" data-target="#admin_user_unban" data-toggle="collapse"
                        data-parent="#user-comands-switch">
                    <i class="icon-thumbs-up"></i>
                    {$oLang->_adm_users_unban}
                </button>
            </div>

            <div class="accordion-body collapse" id="admin_user_unban">
                <form method="post" action="{$sPageRef}" class="well well-small">
                    <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}"/>

                    {if $oUserProfile->getBanLine()}
                        {$oLang->adm_ban_upto} {$oUserProfile->getBanLine()} <br/>
                        {else}
                        {$oLang->adm_ban_unlim} <br/>
                    {/if}
                    {$oLang->_adm_ban_comment}: {$oUserProfile->getBanComment()}<br/>
                    <br/>
                    <input type="hidden" name="ban_login" value="{$oUserProfile->getLogin()}"/>
                    <input type="hidden" name="adm_user_ref" value="{$sPageRef}"/>
                    <input type="hidden" name="adm_user_action" value="adm_unban_user"/>

                    <div class="form-actions">
                        <button type="submit" name="adm_action_submit" class="btn btn-primary">
                            {$oLang->adm_users_unban}
                        </button>
                    </div>
                </form>
            </div>
        </div>
            {else}
        <div class="accordion-group no-border">
            <div class="accordion-heading">
                <button class="btn-block btn left" data-target="#admin_user_ban" data-toggle="collapse"
                        data-parent="#user-comands-switch">
                    <i class="icon-ban-circle"></i>
                    {$oLang->_adm_users_ban}
                </button>
            </div>

            <div class="accordion-body collapse" id="admin_user_ban">
                <form method="post" action="{$sPageRef}" class="well well-small">
                    <br/>
                    <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}"/>

                    <input type="hidden" name="ban_login" value="{$oUserProfile->getLogin()}"/>

                    <label class="radio">
                        <input type="radio" name="ban_period" value="days" checked/>
                        {$oLang->adm_ban_for}
                        <input type="text" name="ban_days" id="ban_days" class="num1"/> {$oLang->adm_ban_days}
                    </label>

                    <label class="radio">
                        <input type="radio" name="ban_period" value="unlim"/>
                        {$oLang->adm_ban_unlim}
                    </label>

                    <label for="ban_comment">{$oLang->adm_ban_comment}</label>
                    <input type="text" name="ban_comment" id="ban_comment" maxlength="255"/>


                    <input type="hidden" name="adm_user_ref" value="{$sPageRef}"/>
                    <input type="hidden" name="adm_user_action" value="adm_ban_user"/>
                    <button type="submit" name="adm_action_submit"
                            class="btn btn-primary">{$oLang->_adm_users_ban}</button>
                </form>
            </div>
        </div>
        {/if}
    {/if}

    {if !$oUserProfile}
    <div class="accordion-group no-border">
        <div class="accordion-heading">
            <button class="btn-block btn left" data-target="#admin_form_seek" data-toggle="collapse"
                    data-parent="#user-comands-switch">
                {if $aFilter}<i class="icon-filter icon-green pull-right"></i>{/if}
                <i class="icon-search"></i>
                {$oLang->_adm_seek_users}
            </button>
        </div>

        <div class="accordion-body collapse collapse-save" id="admin_form_seek">
            <form method="post" action="{router page='admin'}users/" class="well well-small">
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

                <div class="row control-group {if $aFilter.email}success{/if}">
                    <label for="user_filter_email">{$oLang->_adm_user_email}</label>

                    <div class="input-prepend">
                        <span class="add-on">@</span><input type="text" name="user_filter_email" id="user_filter_email"
                                                            value="{$aFilter.email}" maxlength="10"
                                                            class="wide"/>
                    </div>
                    <span class="help-block">{$oLang->_adm_user_filter_email_notice}</span>
                </div>

                <div class="row control-group {if $aFilter.regdate}success{/if}">
                    <label for="user_filter_regdate">{$oLang->_adm_users_date_reg}</label>

                    <div class="input-prepend">
                        <span class="add-on"><i class="icon-calendar"></i></span><input type="text"
                                                                                        name="user_filter_regdate"
                                                                                        id="user_filter_regdate"
                                                                                        value="{$aFilter.regdate}"
                                                                                        class="wide"/>
                    </div>
                    <span class="help-block">{$oLang->_adm_user_filter_regdate_notice}</span>
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

                <input type="hidden" name="user_list_sort" id="user_list_sort" value="{$sUserListSort}"/>
                <input type="hidden" name="user_list_order" id="user_list_order" value="{$sUserListOrder}"/>
                <input type="hidden" name="adm_user_ref" value="{$sPageRef}"/>
                <input type="hidden" name="adm_user_action" value="adm_user_seek"/>
                <button type="submit" name="adm_action_submit" class="btn btn-primary">{$oLang->_adm_seek}</button>
                <button type="reset" name="adm_action_reset" class="btn"
                        onclick="$ace.user.filterReset(this);return false;">{$oLang->_adm_reset}</button>
            </form>
        </div>
    </div>

    <div class="accordion-group no-border">
        <div class="accordion-heading">
            <button class="btn-block btn left" data-target="#admin_form_send" data-toggle="collapse"
                    data-parent="#user-comands-switch">
                <i class="icon-envelope"></i>
                {$oLang->_user_write_prvmsg}
            </button>
        </div>

        <div class="accordion-body collapse" id="admin_form_send">
            <form method="post" action="" class="well well-small">
                <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}"/>

                <div class="row">
                    <label for="users_list">{$oLang->talk_create_users}:</label>
                    <span class="users_list_view"></span>
                    <input type="hidden" name="users_list" id="users_list" class="users_list"/>
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
                        <option value="0">-- {$oLang->_talk_menu_inbox_create} --</option>
                        {if $aTalks}
                            {foreach from=$aTalks item=oTalk}
                                <option value="{$oTalk->getId()}">{$oTalk->getTitle()|escape:'html'}</option>
                            {/foreach}
                        {/if}
                    </select>
                </div>

                <div class="row">
                    <label for="talk_title">{$oLang->_talk_create_title}:</label>
                    <input type="text" name="talk_title" id="talk_title" maxlength="30" class="wide"/>
                </div>

                <div class="row">
                    <label for="talk_text">{$oLang->_talk_create_text}:</label>
                    <textarea name="talk_text" id="talk_text" cols="80" rows="12" class="wide"></textarea>
                </div>


                <div class="row">
                    <label for="send_copy_self" class="checkbox">
                        <input type="checkbox" name="send_copy_self" id="send_copy_self" checked/>
                        {$oLang->_adm_send_copy_self}
                    </label>
                </div>

                <input type="hidden" name="adm_user_ref" value="{$sPageRef}"/>
                <input type="hidden" name="adm_user_action" value="adm_user_message"/>

                <button type="submit" name="adm_action_submit" class="btn btn-primary"
                        onclick="return $ace.user.messageSubmit(['{$oLang->_talk_create_users_error}', '{$oLang->_talk_create_title_error}', '{$oLang->talk_create_text_error}'])">
                    {$oLang->_talk_create_submit}
                </button>

            </form>
        </div>
    </div>
    {/if}

<div class="accordion-group no-border">
    <div class="accordion-heading">
        <button class="btn-block btn left" data-target="#admin_user_setadmin" data-toggle="collapse"
                data-parent="#user-comands-switch">
            <i class="icon-user"></i>
            {$oLang->_adm_include_admin}
        </button>
    </div>

    <div class="accordion-body collapse" id="admin_user_setadmin">
        <form method="post" action="{router page='admin'}users/">

            <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}"/>

            <div class="well well-small">
                <div class="row control-group">
                    <label for="user_login_admin">{$oLang->_adm_user_login}</label>

                    <div class="input-prepend">
                        <span class="add-on"><i class="icon-user"></i></span><input type="text" name="user_login_admin"
                                                                                    id="user_login_admin"
                                                                                    class="wide users_list autocomplete-users-sep"/>
                    </div>
                </div>

                <div class="form-actions">
                    <input type="hidden" name="adm_user_ref" value="{$sPageRef}"/>
                    <input type="hidden" name="adm_user_action" value="adm_user_setadmin"/>
                    <button type="submit" name="adm_action_submit" class="btn btn-primary">
                        {$oLang->_adm_include}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="accordion-group no-border">
    <div class="accordion-heading">
        <button class="btn-block btn left" data-target="#admin_user_del" data-toggle="collapse"
                data-parent="#user-comands-switch">
            <i class="icon-remove"></i>
            {$oLang->_adm_users_del}
        </button>
    </div>

    <div class="accordion-body collapse" id="admin_user_del">
        <form method="post" action="{router page='admin'}users/">

            <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}"/>

            <div class="alert alert-block">
                {$oLang->_adm_users_del_warning}
            </div>

            <div class="well well-small">
                <input type="hidden" name="adm_del_login" value=""/>
                {$oLang->_adm_users_del_confirm}

                <div class="form-actions">
                    <input type="hidden" name="adm_user_ref" value="{$sPageRef}"/>
                    <input type="hidden" name="adm_user_action" value="adm_del_user"/>
                    <input type="hidden" name="adm_user_del_confirm" value="1"/>
                    <button type="submit" name="adm_action_submit" class="btn btn-primary"
                            onclick="return $ace.user.deleteConfirm();">
                        {$oLang->_adm_users_del}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
</div>

<script type="text/javascript">
    $(function () {
        $('input.ip-part').focus(function () {
            $(this).select();
        });
        {if $oUserProfile}
            $ace.user.select('{$oUserProfile->getLogin()}');
        {/if}
    });
</script>

<div id="adm_users_del_confirm" class="modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>{$oLang->_adm_users_del_confirm}</h3>
    </div>
    <div class="modal-body alert-danger">
        <p>
            {$oLang->_adm_users_del_warning}
        </p>
    </div>
    <div class="modal-body">
        <p>

        <form>
            {$oLang->_adm_selected_users}: <span class="users_list_view"></span>
            <input type="hidden" name="users_list" class="users_list"/>
        </form>
        </p>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" onclick="return $ace.user.deleteSubmit(false);">{$oLang->_adm_no}</a>
        <a href="#" class="btn btn-danger" onclick="return $ace.user.deleteSubmit(true);">{$oLang->_adm_users_del}</a>
    </div>
</div>

{/block}


{if $sMode=='ids'}				
{literal}
<script type="text/javascript">
js_admin["block_ban"] = {
    "domready":
        function() {
        new Autocompleter.Request.HTML($('user_login_seek'), DIR_WEB_ROOT+'/include/ajax/userAutocompleter.php?security_ls_key='+LIVESTREET_SECURITY_KEY, {
                'indicatorClass': 'autocompleter-loading', // class added to the input during request
                'minLength': 1, // We need at least 1 character
                'selectMode': 'pick', // Instant completion
                'multiple': false // Tag support, by default comma separated
        });
        new Autocompleter.Request.HTML($('ban_login'), DIR_WEB_ROOT+'/include/ajax/userAutocompleter.php?security_ls_key='+LIVESTREET_SECURITY_KEY, {
                'indicatorClass': 'autocompleter-loading', // class added to the input during request
                'minLength': 1, // We need at least 1 character
                'selectMode': 'pick', // Instant completion
                'multiple': false // Tag support, by default comma separated
        });
        }
}

document.addEvent('domready', js_admin["block_ban"].domready);

</script>
{/literal}
{/if}

{literal}
<script type="text/javascript">
function AdminBanAction(n) {
  var i, el;
  if (document.getElementById) {
    for(i=1;i<3;i++) {
      if (i==n) {
        if (el=document.getElementById('a'+i)) el.style.display='none';
        if (el=document.getElementById('t'+i)) el.style.display='';
        if (el=document.getElementById('d'+i)) el.style.display='';
        if ((n==1) && (el=document.getElementById('admin_ip1_1'))) el.focus();
      } else {
        if (el=document.getElementById('a'+i)) el.style.display='';
        if (el=document.getElementById('t'+i)) el.style.display='none';
        if (el=document.getElementById('d'+i)) el.style.display='none';
      }
    }
  }
}

function AdminBanCheckIpPart(s, n, i) {
  var result=true;
  var val=parseInt(s);

  if (i==1 && val>0 && val<255) {
    var el=document.getElementById('admin_ip2_'+i);
    if (el && el.value=='') el.value=val;
  } else if (i>1 && n==1 && (s=='' || s=='*')) {
    document.getElementById('admin_ip1_'+i).value='0';
  } else if (i>1 && val>=0 && val<=255) {
    if (el && el.value=='') el.value=val;
  } else if (i>1 && n==2 && s=='') {
    document.getElementById('admin_ip2_'+i).value='255';
  } else {
    result=false;
  }
  return result;
}

function AdminBanIpSubmit() {
  var el1, el2;
  if (document.getElementById) {
    for (var n=1;n<=2;n++) {
      for (var i=1;i<=4;i++) {
        if (el1=document.getElementById('admin_ip'+n+'_'+i)) {
          if (n==1) {el2=document.getElementById('admin_ip2_'+i);} else {el2=null;}
          if (!AdminBanCheckIpPart(el1.value, n, i)) {
            msgErrorBox.alert('Error', 'Wrong IP address');
            el1.focus(); 
            return false;
          }
        }
      }
    }
  }  
  return true;
}

function AdminReset() {
  var i, el;
  if (document.getElementById) {
    if (el=document.getElementById('user_login_seek')) el.value='';
    if (el=document.getElementById('user_ip1_seek')) el.value='*';
    if (el=document.getElementById('user_ip2_seek')) el.value='*';
    if (el=document.getElementById('user_ip3_seek')) el.value='*';
    if (el=document.getElementById('user_ip4_seek')) el.value='*';
    if (el=document.getElementById('user_regdate_seek')) el.value='*';
    if (el=document.getElementById('admin_form_seek')) el.submit();
  }
}

</script>
{/literal}

<div class="block white">
    <div class="tl"><div class="tr"></div></div>
    <div class="cl"><div class="cr">
            <h1>{$oLang->adm_users_action} &darr;</h1>
            <div style="margin-left:20px;">
                {if $sMode=='ips'}
                <form method="post" action="">
                    <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />
                    <input type="hidden" name="adm_user_ref" value="{$sPageRef}" />
                    <h4><span id="a1"><a href="#" onclick="AdminBanAction(1); return false;">{$oLang->adm_include}</a></span><span id="t1" style="display:none;">{$oLang->adm_include}</span></h4>
                    <div id="d1" style="margin-left:20px;display:none;">
                        <br />
                        <table>
                            <tr><td align="right">IP from </td><td>
                                    <input type="text" name="adm_ip1_1" id="admin_ip1_1" maxlength="3" style="width:25px;text-align:center;" /> .
                                    <input type="text" name="adm_ip1_2" id="admin_ip1_2" maxlength="3" style="width:25px;text-align:center;" /> .
                                    <input type="text" name="adm_ip1_3" id="admin_ip1_3" maxlength="3" style="width:25px;text-align:center;" /> .
                                    <input type="text" name="adm_ip1_4" id="admin_ip1_4" maxlength="3" style="width:25px;text-align:center;" /></td></tr>
                            <tr><td align="right">to</td><td>
                                    <input type="text" name="adm_ip2_1" id="admin_ip2_1" maxlength="3" style="width:25px;text-align:center;" /> .
                                    <input type="text" name="adm_ip2_2" id="admin_ip2_2" maxlength="3" style="width:25px;text-align:center;" /> .
                                    <input type="text" name="adm_ip2_3" id="admin_ip2_3" maxlength="3" style="width:25px;text-align:center;" /> .
                                    <input type="text" name="adm_ip2_4" id="admin_ip2_4" maxlength="3" style="width:25px;text-align:center;" /></td></tr>
                        </table><br />
                        <input type="radio" name="ban_period" value="days" checked />{$oLang->adm_ban_for} <input type="text" name="ban_days" id="ban_days" style="width:25px;padding:0;text-align:right;" /> {$oLang->adm_ban_days}<br />
                        <input type="radio" name="ban_period" value="unlim" />{$oLang->adm_ban_unlim} <br /><br />
                        {$oLang->adm_ban_comment} <input type="text" name="ban_comment" maxlength="255" style="width:200px;" /><br />
                        <br />
                        <input type="hidden" name="adm_user_action" value="adm_ban_ip" />
                        <input type="submit" name="adm_action_submit" value="{$oLang->adm_include}" onclick="return AdminBanIpSubmit();" />
                    </div>
                </form>
                {else}
                <h4><span id="a1"><a href="#" onclick="AdminBanAction(1); return false;">{$oLang->adm_seek}</a></span><span id="t1" style="display:none;">{$oLang->adm_seek}</span></h4>
                <form method="post" action="" id="admin_form_seek">
                    <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />
                    <div id="d1" style="margin-left:20px;display:none;">
                        <p>
                            <label for="user_login_seek">{$oLang->adm_user_login}</label><br />
                            <input type="text" name="user_login_seek" id="user_login_seek" value="{$sUserLoginSeek}" maxlength="30" style="width:250px;" /><br />
                        </p>
                        <p>
                            <label for="user_ip1_seek">{$oLang->adm_user_ip}</label><br />
                            <input type="text" name="user_ip1_seek" id="user_ip1_seek" value="{$aUserIp.0}" maxlength="3" style="width:30px;text-align:center;" onfocus="AdminSelect('user_ip1_seek')" /> .
                            <input type="text" name="user_ip2_seek" id="user_ip2_seek" value="{$aUserIp.1}" maxlength="3" style="width:30px;text-align:center;" onfocus="AdminSelect('user_ip2_seek')" /> .
                            <input type="text" name="user_ip3_seek" id="user_ip3_seek" value="{$aUserIp.2}" maxlength="3" style="width:30px;text-align:center;" onfocus="AdminSelect('user_ip3_seek')" /> .
                            <input type="text" name="user_ip4_seek" id="user_ip4_seek" value="{$aUserIp.3}" maxlength="3" style="width:30px;text-align:center;" onfocus="AdminSelect('user_ip4_seek')" />
                            <br />
                            <span class="form_note">{$oLang->adm_user_ip_seek_notice}</span>
                        </p>
                        <p>
                            <label for="user_regdate_seek">{$oLang->adm_users_date_reg}</label><br />
                            <input type="text" name="user_regdate_seek" id="user_regdate_seek" value="{$aFilter.regdate}" maxlength="10" style="width:250px;" /><br />
                            <span class="form_note">{$oLang->adm_user_regdate_seek_notice}</span>
                        </p>
                        <p>
                            <input type="hidden" name="user_list_sort" id="user_list_sort" value="{$sUserListSort}" />
                            <input type="hidden" name="user_list_order" id="user_list_order" value="{$sUserListOrder}" />
                            <input type="hidden" name="adm_user_ref" value="{$sPageRef}" />
                            <input type="hidden" name="adm_user_action" value="adm_user_seek" />
                            <input type="submit" name="adm_action_submit" value="{$oLang->adm_seek}" />
                            <input type="reset" name="adm_action_reset" value="{$oLang->adm_reset}" onclick="AdminReset()" />
                        </p>
                    </div>
                </form>

                <h4><span id="a2"><a href="#" onclick="AdminBanAction(2); return false;">{$oLang->adm_include}</a></span><span id="t2" style="display:none;">{$oLang->adm_include}</span></h4>
                <div id="d2" style="margin-left:20px;display:none;">
                    <form method="post" action="">
                        <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />
                        <input type="hidden" name="adm_user_ref" value="{$sPageRef}" />
                        <p>
                            <label for="ban_login">{$oLang->adm_user_login}</label><input type="text" name="ban_login" id="ban_login" maxlength="30" style="width:250px;" /><br />
                        </p>
                        <input type="radio" name="ban_period" value="days" checked />{$oLang->adm_ban_for} <input type="text" name="ban_days" id="ban_days" style="width:25px;padding:0;text-align:right;" /> {$oLang->adm_ban_days}<br />
                        <input type="radio" name="ban_period" value="unlim" />{$oLang->adm_ban_unlim} <br /><br />
                        {$oLang->adm_ban_comment} <input type="text" name="ban_comment" maxlength="255" style="width:200px;" /><br />
                        <br />
                        <input type="hidden" name="adm_user_action" value="adm_ban_user" />
                        <input type="submit" name="adm_action_submit" value="{$oLang->adm_include}" />
                    </form>
                </div>
                {/if}
            </div>
        </div></div>
    <div class="bl"><div class="br"></div></div>
</div>

{if $sMode=='ids' && $aFilter}
{literal}
<script type="text/javascript">
AdminBanAction(1);
</script>
{/literal}
{/if}

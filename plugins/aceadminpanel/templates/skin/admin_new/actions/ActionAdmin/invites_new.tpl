
<script type="text/javascript">
var aceAdmin = aceAdmin || { }

aceAdmin.inviteMode = function (mode) {
  if (mode=='mail') {
    $('#div_invite_mail').show();
    $('#div_invite_text').hide();
  } else {
    $('#div_invite_mail').hide();
    $('#div_invite_text').show();
  }
}

aceAdmin.inviteSubmit = function (msg1, msg2) {
  if ($('#adm_invite_mode_mail').prop("checked")) {
    if (!$('#invite_mail').val()) {
      alert(msg1);
      return false;
    }
  }
  if ($('#adm_invite_mode_text').prop("checked")) {
    if (parseInt($('#invite_count').val())<=0) {
      alert(msg2);
      return false;
    }
  }
  return true;
}
</script>


{if !$aNewInviteList}
<form action="" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />
    <p>
    {$oLang->settings_invite_available}: <strong>{if $iCountInviteAvailable==-1}{$aLang.settings_invite_many}{else}{$iCountInviteAvailable}{/if}</strong><br />
    {$oLang->settings_invite_used}: <strong>{$iCountInviteUsed}</strong>
    </p>
    <p>
        <label class="radio">
        <input type="radio" name="adm_invite_mode" id="adm_invite_mode_mail" value="mail" {if $sInviteMode=='mail'}checked{/if} onclick="aceAdmin.inviteMode('mail');" />
         {$oLang->adm_invite_mode_mail}</label>

        <label class="radio">
        <input type="radio" name="adm_invite_mode" id="adm_invite_mode_text" value="text" {if $sInviteMode=='text'}checked{/if} onclick="aceAdmin.inviteMode('text');" />
        {$oLang->adm_invite_mode_text}</label>
    </p>
    <div id="div_invite_mail" {if $sInviteMode=='text'}style="display:none;"{/if}>
         <label for="invite_mail">{$oLang->adm_send_invite_mail}:</label><br />
        <textarea name="invite_mail" id="invite_mail" class="w300"></textarea><br />
    </div>
    <div id="div_invite_text" {if $sInviteMode=='mail'}style="display:none;"{/if}>
         <label for="invite_count">{$oLang->adm_make_invite_text}:</label><br />
        <input type="text" name="invite_count" id="invite_count" class="w100" style="text-align:right;" value="{$iInviteCount}" /><br />
    </div>
    <br/>
    <input type="submit" value="{$oLang->adm_invite_submit}" name="adm_invite_submit"
           class="btn btn-primary"
           onclick="return aceAdmin.inviteSubmit('{$oLang->adm_invaite_mail_empty}', '{$oLang->adm_invaite_text_empty}');" />
</form>
{else}
{foreach from=$aNewInviteList key=key item=item}
{$key} : {$item}<br/>
{/foreach}
<form action="" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />
    <input type="hidden" name="adm_invite_mode" value="{$sInviteMode}" />
    <input type="hidden" name="invite_count" value="{$iInviteCount}" /><br />
    <input type="submit" value="{$oLang->adm_continue}" class="btn btn-primary" />
</form>
{/if}
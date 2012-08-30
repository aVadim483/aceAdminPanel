{literal}

<script type="text/javascript">
    aceAdmin.editField = function (name, button, type) {
        var view
        if (view = $('#' + name + '_view')) {
            $('.popover').remove();
            $(button).popover({
                trigger:'manual',
                content:function () {
                    if (type == 'text') {
                        return '<textarea class="data">' + view.html() + '</textarea>';
                    } else {
                        if (type == 'url' || type == 'email') {
                            var txt = view.find('a').first().text();
                            //txt = txt.replace(/^[\s\xA0]+|[\s\xA0]+$/g, '');
                            //txt = txt.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
                        } else {
                            var txt = view.text();
                        }
                        return '<input type="text" class="data" value="' + txt.replace(/^[\s\xA0]+|[\s\xA0]+$/g, '') + '" />';
                    }
                },
                title:function () {
                    var label = $('#' + name + '_label');
                    return ''
                            + '<button class="btn btn-mini btn-danger pull-right popover-close"><i class="icon-remove icon-white"></i></button>'
                            + '<button class="btn btn-mini btn-success pull-right popover-save"><i class="icon-ok icon-white"></i></button>'
                            + (label.length ? label.text() : '');
                }
            });
            $(button).popover('show');
            var popover = $('.popover');
            popover.find('input[type=text], textarea').focus();
            popover.find('.popover-close').click(function () {
                $(button).popover('hide');
            });
            popover.find('.popover-save').click(function () {
                aceAdmin.saveData(name, popover.find('.data').val(), type)
                $(button).addClass('btn-primary').find('.icon-pencil').addClass('icon-white');
                $(button).popover('hide');
            });
            popover.width($(button).parents('td').first().width());
            $('#edit_submit').prop('disabled', null);
            return false;
        }
    }

    aceAdmin.saveData = function (name, value, type) {
        var view;
        if (view = $('#' + name + '_view')) {
            if (!value) {
                view.text('');
            } else {
                if (type == 'url') {
                    if (value.substr(0, 7) != 'http://') value = 'http://' + value;
                    view.html('<a href="' + value + '">' + value + '</a>');
                } else if (type == 'email') {
                    view.html('<a href="mailto:' + value + '">' + value + '</a>');
                } else {
                    view.html(value);
                }
            }
            $('#edit_submit').prop('disabled', null);
            return false;
        }
    }

    aceAdmin.submitData = function () {
        var params = {};
        var more;

        $('#edit_submit').progressOn();
        params['user_id'] = $('#user_id').val();
        params['profile_about'] = $('#user_profile_about_view').html();
        params['profile_site'] = $('#user_profile_site_view a').text();
        //params['profile_site_name'] = $('profile_site_name').value;
        params['profile_email'] = $('#user_profile_email_view a').text();

        ls.ajax('admin/setprofile/', params, function (result) {
            if (!result) {
                ls.msg.error('Error', 'Please try again later');
            }
            if (result.bStateError) {
                ls.msg.error(result.sMsgTitle || 'Error', result.sMsg || 'Please try again later');
            } else {
                ls.msg.notice(result.sMsgTitle || 'Notice', result.sMsg || 'Operation completed');
                $('.adm_field .btn').removeClass('btn-primary').find('.icon-pencil').removeClass('icon-white');
            }
            $('#edit_submit').progressOff();
        }, more);
        return;
    }

</script>
{/literal}

{assign var="oSession" value=$oUserProfile->getSession()}
{assign var="oVote" value=$oUserProfile->getVote()}

<div class="profile-user -box">

<h4 class="title">{$oLang->profile_privat}</h4>
<table class="table">
{if $oUserProfile->getProfileSex()!='other'}
    <tr>
        <td class="adm_var">{$oLang->profile_sex}:</td>
        <td>
            {if $oUserProfile->getProfileSex()=='man'}
                {$oLang->profile_sex_man}
                {else}
                {$oLang->profile_sex_woman}
                {/if}
        </td>
    </tr>
{/if}

    <tr>
        <td class="adm_var">{$oLang->profile_birthday}:</td>
        <td>{$oUserProfile->getProfileBirthday()}</td>
    </tr>

    <tr>
        <td class="adm_var">{$oLang->profile_place}:</td>
        <td>
        {if $oUserProfile->getProfileCountry()}
            <a href="{router page='people'}country/{$oUserProfile->getProfileCountry()|escape:'html'}/">{$oUserProfile->getProfileCountry()|escape:'html'}</a>{if $oUserProfile->getProfileCity()}
            ,{/if}
        {/if}
        {if $oUserProfile->getProfileCity()}
            <a href="{router page='people'}city/{$oUserProfile->getProfileCity()|escape:'html'}/">{$oUserProfile->getProfileCity()|escape:'html'}</a>
        {/if}
        </td>
    </tr>

    <tr>
        <td class="adm_var" id="user_profile_about_label">{$oLang->profile_about}:</td>
        <td class="adm_field">
            <button class="btn btn-mini"
                    onclick="aceAdmin.editField('user_profile_about', this, 'text'); return false;">
                <i class="icon-pencil"></i>
            </button>
            <span id="user_profile_about_view"
                  class="adm_field_value">{$oUserProfile->getProfileAbout()|escape:'html'}</span>
        </td>
    </tr>

    <tr>
        <td class="adm_var" id="user_profile_site_label">{$oLang->profile_site}:</td>
        <td class="adm_field">
            <button class="btn btn-mini" onclick="aceAdmin.editField('user_profile_site', this, 'url'); return false;">
                <i class="icon-pencil"></i>
            </button>
            <span id="user_profile_site_view" class="adm_field_value">
                <a href="{$oUserProfile->getProfileSite(true)|escape:'hex'}">
                {$oUserProfile->getProfileSite(true)|escape:'html'}
                </a>
            </span>

        </td>
    </tr>

    <tr>
        <td class="adm_var" id="user_profile_email_label">{$oLang->settings_profile_mail}:</td>
        <td class="adm_field">
            <button class="btn btn-mini"
                    onclick="aceAdmin.editField('user_profile_email', this, 'email'); return false;">
                <i class="icon-pencil"></i>
            </button>

            <span id="user_profile_email_view">
                <a href="mailto:{$oUserProfile->getMail()|escape:'hex'}">
                {$oUserProfile->getMail()|escape:'html'}
                </a>
            </span>
        </td>
    </tr>

</table>
<input type="hidden" id="user_id" value="{$oUserProfile->getId()}"/>

<div class="form-actions fix-on-container">
    <div class="navbar fix-on-bottom">
        <div class="navbar-inner">
            <div class="container">
                <button type="submit" id="edit_submit" name="edit_submit"
                        class="btn btn-primary pull-right" onclick="aceAdmin.submitData();" disabled="disabled">
                {$oLang->_adm_save}
                </button>
            </div>
        </div>
    </div>
</div>

<h4 class="title">{$oLang->profile_activity}</h4>
<table class="table">
    <tr>
        <td class="adm_var">{$oLang->profile_date_registration}:</td>
        <td>{date_format date=$oUserProfile->getDateRegister()} (ip:{$oUserProfile->getIpRegister()})</td>
    </tr>
    <tr>
        <td class="adm_var">{$oLang->profile_date_last}:</td>
        <td>
        {if $oSession}
            {date_format date=$oSession->getDateLast()} (ip:{$oSession->getIpLast()})
        {/if}
        </td>
    </tr>

    <tr>
        <td class="adm_var">{$oLang->profile_friends}:</td>
        <td class="friends">
        {if $aUsersFrend}
            {foreach from=$aUsersFrend item=oUserFrend}
                <a href="{$oUserFrend->getUserWebPath()}">{$oUserFrend->getLogin()}</a>&nbsp;
            {/foreach}
        {/if}
        </td>
    </tr>

    <tr>
        <td class="adm_var">{$oLang->profile_friends_self}:</td>
        <td class="friends">
        {if $aUsersSelfFrend}
            {foreach from=$aUsersSelfFrend item=oUserFrend}
                <a href="{$oUserFrend->getUserWebPath()}">{$oUserFrend->getLogin()}</a>&nbsp;
            {/foreach}
        {/if}
        </td>
    </tr>

{if $USER_USE_INVITE and $oUserInviteFrom}
    <tr>
        <td class="adm_var">{$oLang->profile_invite_from}:</td>
        <td class="friends">
            <a href="{$oUserInviteFrom->getUserWebPath()}">{$oUserInviteFrom->getLogin()}</a>&nbsp;
        </td>
    </tr>
{/if}

{if $USER_USE_INVITE and $aUsersInvite}
    <tr>
        <td class="adm_var">{$oLang->profile_invite_to}:</td>
        <td class="friends">
            {foreach from=$aUsersInvite item=oUserInvite}
                <a href="{$oUserInvite->getUserWebPath()}">{$oUserInvite->getLogin()}</a>&nbsp;
            {/foreach}
        </td>
    </tr>
{/if}

    <tr>
        <td class="adm_var">{$oLang->profile_blogs_self}:</td>
        <td>
        {if $aBlogsOwner}
            {foreach from=$aBlogsOwner item=oBlog name=blog_owner}
                <a href="{router page='blog'}{$oBlog->getUrl()}/">{$oBlog->getTitle()|escape:'html'}</a>{if !$smarty.foreach.blog_owner.last}
                , {/if}
            {/foreach}
            {else}
            {$oLang->adm_no}
        {/if}
        </td>
    </tr>

    <tr>
        <td class="adm_var">{$oLang->profile_blogs_administration}:</td>
        <td>
        {if $aBlogsAdministration}
            {foreach from=$aBlogsAdministration item=oBlogUser name=blog_user}
                {assign var="oBlog" value=$oBlogUser->getBlog()}
                <a href="{$oBlog->getUrlFull()}">{$oBlog->getBlogTitle()|escape:'html'}</a>{if !$smarty.foreach.blog_user.last}
                , {/if}
            {/foreach}
            {else}
            {$oLang->adm_no}
        {/if}
        </td>
    </tr>

    <tr>
        <td class="adm_var">{$oLang->profile_blogs_moderation}:</td>
        <td>
        {if $aBlogsModeration}
            {foreach from=$aBlogsModeration item=oBlogUser name=blog_user}
                {assign var="oBlog" value=$oBlogUser->getBlog()}
                <a href="{$oBlog->getUrlFull()}">{$oBlog->getBlogTitle()|escape:'html'}</a>{if !$smarty.foreach.blog_user.last}
                , {/if}
            {/foreach}
            {else}
            {$oLang->adm_no}
        {/if}
        </td>
    </tr>

    <tr>
        <td class="adm_var">{$oLang->profile_blogs_join}:</td>
        <td>
        {if $aBlogsUser}
            {foreach from=$aBlogsUser item=oBlogUser name=blog_user}
                {assign var="oBlog" value=$oBlogUser->getBlog()}
                <a href="{$oBlog->getUrlFull()}">{$oBlog->getBlogTitle()|escape:'html'}</a>{if !$smarty.foreach.blog_user.last}
                , {/if}
            {/foreach}
            {else}
            {$oLang->adm_no}
        {/if}
        </td>
    </tr>

    <tr>
        <td class="adm_var">{$oLang->adm_user_wrote_topics}:</td>
        <td>
        {if $oUserProfile->GetCountTopics()}{$oUserProfile->GetCountTopics()}{else}0{/if}
        {if $aLastTopicList}
            (
            {foreach from=$aLastTopicList item=oTopic name=topic_user}
                <a href="{router page='blog'}{$oTopic->getId()}.html">{$oTopic->getTitle()|escape:'html'}</a>{if !$smarty.foreach.topic_user.last}
                , {/if}
            {/foreach}
            )
        {/if}
        </td>
    </tr>

    <tr>
        <td class="adm_var">{$oLang->adm_user_wrote_comments}:</td>
        <td>
        {if $oUserProfile->GetCountComments()}{$oUserProfile->GetCountComments()}{else}0{/if}
        </td>
    </tr>

</table>
</div>

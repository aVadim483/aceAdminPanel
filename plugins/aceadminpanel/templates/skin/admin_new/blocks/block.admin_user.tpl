<script type="text/javascript">
    aceAdmin.formVote = function (button, value) {

        button = $(button);
        value = parseInt(value);

        var options = {
            trigger:'manual',
            content:function () {
                var result = '';
                if (value < 0) {
                    result += '<input type="hidden" name="sign" value="-1" />';
                    result += '<i class="icon-minus icon-red adm_vote_sign"></i>';
                } else {
                    result += '<input type="hidden" name="sign" value="1" />';
                    result += '<i class="icon-plus icon-green adm_vote_sign"></i>';
                }
                result += '<input type="text" name="value" value="' + Math.abs(value) + '" class="adm_vote_value" />';
                result += '<button class="btn btn-mini btn-danger pull-right cancel"><i class="icon-remove icon-white"></i></button>';
                result += '<button class="btn btn-mini btn-success pull-right confirm"><i class="icon-ok icon-white"></i></button>'
                return result;
            },
            title:false,
            attr:{
                'class':'adm_vote'
            },
            onConfirm:function (event, element) {
                $(event.currentTarget).progressOn();
                var val = parseInt($(element).find('input[name=value]').val() * $(element).find('input[name=sign]').val());
                if (val) {
                    var views = {
                        skill:$('.sidebar .strength .total'),
                        rating:$('.sidebar .voting .total'),
                        voteCount:$('.sidebar .voting .count')
                    };
                    aceAdmin.vote('user', '{$oUserProfile->getId()}', val, views, function () {
                        $(event.currentTarget).progressOff();
                    });
                }
            }
        };

        var popup = aceAdmin.pointup(button, options);
        button.popover('show');
        return false;
    }

</script>

{assign var="oSession" value=$oUserProfile->getSession()}
{assign var="oVote" value=$oUserProfile->getVote()}


<div class="user-profile">
    <div class="name">
        <img src="{$oUserProfile->getProfileAvatarPath(100)}" alt="avatar" class="avatar img-polaroid"/>

        <div class="-box">
            <div class="nickname">ID: {$oUserProfile->getId()}</div>

            <div class="nickname">{$oUserProfile->getLogin()}</div>
        {if $oUserProfile->getProfileName()}
            <div class="realname">{$oUserProfile->getProfileName()|escape:'html'}</div>
        {/if}
        </div>

        <div class="row-fluid">
            <div class="strength span4">
            {$oLang->user_skill}
                <div class="total" id="user_skill_{$oUserProfile->getId()}">{$oUserProfile->getSkill()}</div>
            </div>

            <div class="voting  span6">
            {$oLang->user_rating}

                <div style="display: inline-block; margin: auto;">
                    <i class="icon-arrow-up icon-green adm_vote_plus"
                       onclick="aceAdmin.formVote(this, '{$nParamVoteValue}'); return false;"></i>

                    <a href="#" class="adm_plus"
                       onclick="AdminVote({$oUserProfile->getId()}, this, {$nParamVoteValue}, 'user'); return false;"
                       title="+{$nParamVoteValue}"></a>
                    <a href="#" class="plus"
                       onclick="AdminVote({$oUserProfile->getId()}, this, 1,'user'); return false;" title="+1"></a>

                    <div class="total {if $oUserProfile->getRating()>=0}positive{else}negative{/if}"
                         style="display: inline-block;">{if $oUserProfile->getRating()>0}
                        +{/if}{$oUserProfile->getRating()}</div>

                    <a href="#" class="minus"
                       onclick="AdminVote({$oUserProfile->getId()}, this, -1,'user'); return false;" title="-1"></a>
                    <a href="#" class="adm_minus"
                       onclick="AdminVote({$oUserProfile->getId()}, this, -{$nParamVoteValue}, 'user'); return false;"
                       title="-{$nParamVoteValue}"></a>

                    <i class="icon-arrow-down icon-red adm_vote_minus"
                       onclick="aceAdmin.formVote(this, '-{$nParamVoteValue}'); return false;"></i>
                </div>
            </div>

            <div class="voting  span4">
            {$oLang->user_vote_count}
                <div class="count">{$oUserProfile->getCountVote()}</div>
            </div>
        </div>

    </div>

    <table class="table table-condensed vote-stat">
        <tr>
            <th colspan="3">{$oLang->adm_user_voted} (cnt/sum)</th>
        </tr>
        <tr>
            <td class="lable">{$oLang->adm_user_voted_topics}</td>
            <td class="plus">
            {if $aUserVoteStat.cnt_topics_p}
                {$aUserVoteStat.cnt_topics_p} / {$aUserVoteStat.sum_topics_p}
            {/if}
            </td>
            <td class="minus">
            {if $aUserVoteStat.cnt_topics_m}
                {$aUserVoteStat.cnt_topics_m} /{$aUserVoteStat.sum_topics_m}
            {/if}
            </td>
        </tr>
        <tr>
            <td class="lable">{$oLang->adm_user_voted_users}</td>
            <td class="plus">
            {if $aUserVoteStat.cnt_users_p}
                {$aUserVoteStat.cnt_users_p} / {$aUserVoteStat.sum_users_p}
            {/if}
            </td>
            <td class="minus">
            {if $aUserVoteStat.cnt_users_m}
                {$aUserVoteStat.cnt_users_m} /{$aUserVoteStat.sum_users_m}
            {/if}
            </td>
        </tr>
        <tr>
            <td class="lable">{$oLang->adm_user_voted_comments}</td>
            <td class="plus">
            {if $aUserVoteStat.cnt_comments_p}
                {$aUserVoteStat.cnt_comments_p} /{$aUserVoteStat.sum_comments_p}
            {/if}
            </td>
            <td class="minus">
            {if $aUserVoteStat.cnt_comments_m}
                {$aUserVoteStat.cnt_comments_m} /{$aUserVoteStat.sum_comments_m}
            {/if}
            </td>
        </tr>
    </table>
</div>

{if $oUserProfile->IsBannedByLogin()}
<div class="alert alert-block">
    {$oLang->adm_ban_upto}
    : {if $oUserProfile->getBanLine()}{$oUserProfile->getBanLine()}{else}{$oLang->adm_ban_unlim}{/if}
    <br/>
    <strong>{$oUserProfile->getBanComment()}</strong>
</div>
{/if}
<hr/>


<div class="switch-form-group">

</div>


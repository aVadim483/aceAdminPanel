<h4>{$oLang->_adm_user_voted_users}</h4>
<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th width="130px">Date</th>
        <th>User</th>
        <th>Name</th>
        <th width="60px">Vote</th>
    </tr>
    </thead>

    <tbody>
{foreach $aVoted.users as $aData}
    <tr>
        <td>&nbsp;{$aData.vote_date}&nbsp;</td>
        <td>&nbsp;{$aData.user_login}&nbsp;</td>
        <td>&nbsp;{$aData.title}&nbsp;</td>
        <td class="number {if $aData.vote_value>0}plus{/if}{if $aData.vote_value<0}minus{/if}">
            {$aData.vote_value}
        </td>
    </tr>
{/foreach}
    </tbody>

</table>

<h4>{$oLang->_adm_user_voted_blogs}</h4>
<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th width="130px">Date</th>
        <th>User</th>
        <th>Blog</th>
        <th width="60px">Vote</th>
    </tr>
    </thead>

    <tbody>
{foreach $aVoted.blogs as $aData}
    <tr>
        <td>&nbsp;{$aData.vote_date}&nbsp;</td>
        <td>&nbsp;{$aData.user_login}&nbsp;</td>
        <td>&nbsp;{$aData.title}&nbsp;</td>
        <td class="number {if $aData.vote_value>0}plus{/if}{if $aData.vote_value<0}minus{/if}">
            {$aData.vote_value}
        </td>
    </tr>
{/foreach}
    </tbody>

</table>

<h4>{$oLang->_adm_user_voted_topics}</h4>
<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th width="130px">Date</th>
        <th>User</th>
        <th>Topic</th>
        <th width="60px">Vote</th>
    </tr>
    </thead>

    <tbody>
{foreach $aVoted.topics as $aData}
    <tr>
        <td>&nbsp;{$aData.vote_date}&nbsp;</td>
        <td>&nbsp;{$aData.user_login}&nbsp;</td>
        <td>&nbsp;{$aData.title}&nbsp;</td>
        <td class="number {if $aData.vote_value>0}plus{/if}{if $aData.vote_value<0}minus{/if}">
            {$aData.vote_value}
        </td>
    </tr>
{/foreach}
    </tbody>

</table>

<h4>{$oLang->_adm_user_voted_comments}</h4>
<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th width="130px">Date</th>
        <th>User</th>
        <th>Comments</th>
        <th width="60px">Vote</th>
    </tr>
    </thead>

    <tbody>
{foreach $aVoted.comments as $aData}
    <tr>
        <td>&nbsp;{$aData.vote_date}&nbsp;</td>
        <td>&nbsp;{$aData.user_login}&nbsp;</td>
        <td>&nbsp;{$aData.title}&nbsp;</td>
        <td class="number {if $aData.vote_value>0}plus{/if}{if $aData.vote_value<0}minus{/if}">
            {$aData.vote_value}
        </td>
    </tr>
{/foreach}
    </tbody>

</table>

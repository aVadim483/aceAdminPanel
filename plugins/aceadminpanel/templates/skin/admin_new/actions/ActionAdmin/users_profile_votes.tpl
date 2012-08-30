<h4>{$oLang->adm_user_voted_users}</h4>
<table class="table table-bordered table-striped">
    <tr>
        <th width="130px">Date</th>
        <th>User</th>
        <th>Name</th>
        <th width="60px">Vote</th>
    </tr>

{foreach $aVotes.users as $aData}
    <tr>
        <td>&nbsp;{$aData.vote_date}&nbsp;</td>
        <td>&nbsp;{$aData.user_login}&nbsp;</td>
        <td>&nbsp;{$aData.title}&nbsp;</td>
        <td class="number {if $aData.vote_value>0}plus{/if}{if $aData.vote_value<0}minus{/if}">
            {$aData.vote_value}
        </td>
    </tr>
{/foreach}

</table>

<h4>{$oLang->adm_user_voted_blogs}</h4>
<table class="table table-bordered table-striped">
    <tr>
        <th width="130px">Date</th>
        <th>User</th>
        <th>Blog</th>
        <th width="60px">Vote</th>
    </tr>

{foreach $aVotes.blogs as $aData}
    <tr>
        <td>&nbsp;{$aData.vote_date}&nbsp;</td>
        <td>&nbsp;{$aData.user_login}&nbsp;</td>
        <td>&nbsp;{$aData.title}&nbsp;</td>
        <td class="number {if $aData.vote_value>0}plus{/if}{if $aData.vote_value<0}minus{/if}">
            {$aData.vote_value}
        </td>
    </tr>
{/foreach}

</table>

<h4>{$oLang->adm_user_voted_topics}</h4>
<table class="table table-bordered table-striped">
    <tr>
        <th width="130px">Date</th>
        <th>User</th>
        <th>Topic</th>
        <th width="60px">Vote</th>
    </tr>

{foreach $aVotes.topics as $aData}
    <tr>
        <td>&nbsp;{$aData.vote_date}&nbsp;</td>
        <td>&nbsp;{$aData.user_login}&nbsp;</td>
        <td>&nbsp;{$aData.title}&nbsp;</td>
        <td class="number {if $aData.vote_value>0}plus{/if}{if $aData.vote_value<0}minus{/if}">
            {$aData.vote_value}
        </td>
    </tr>
{/foreach}

</table>

<h4>{$oLang->adm_user_voted_comments}</h4>
<table class="table table-bordered table-striped">
    <tr>
        <th width="130px">Date</th>
        <th>User</th>
        <th>Comments</th>
        <th width="60px">Vote</th>
    </tr>

{foreach $aVotes.comments as $aData}
    <tr>
        <td>&nbsp;{$aData.vote_date}&nbsp;</td>
        <td>&nbsp;{$aData.user_login}&nbsp;</td>
        <td>&nbsp;{$aData.title}&nbsp;</td>
        <td class="number {if $aData.vote_value>0}plus{/if}{if $aData.vote_value<0}minus{/if}">
            {$aData.vote_value}
        </td>
    </tr>
{/foreach}

</table>

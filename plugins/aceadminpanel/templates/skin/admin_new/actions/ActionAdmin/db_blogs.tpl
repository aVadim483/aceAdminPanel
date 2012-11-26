{extends file="./db.tpl"}

{block name="content"}
<h3>{$oLang->_adm_db_check_deleted_blogs}</h3>

<h4>{$oLang->_adm_db_check_blogs_joined}</h4>
<table class="table table-striped table-bordered table-condensed">
    <thead>
        <tr>
            <th>Deleted blog ID</th>
            <th>Joined users</th>
        </tr>
    </thead>
    <tbody>
    {foreach $aJoinedBlogs as $nBlogId=>$aData}
        <tr>
            <td>{$nBlogId}</td>
            <td>
                {foreach $aData as $aUser}
                {$aUser.user_login}
                {/foreach}
            </td>
        </tr>
    {/foreach}
    </tbody>
</table>
<form method="post">
    <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}"/>
    <input type="hidden" name="do_action" value="clear_blogs_joined"/>
    <button class="btn {if $aJoinedBlogs}btn-primary{else} disabled{/if}">{$oLang->_adm_db_clear_unlinked_blogs}</button>
</form>

<h4>{$oLang->_adm_db_check_blogs_comments_online}</h4>
<table class="table table-striped table-bordered table-condensed">
    <thead>
    <tr>
        <th>Deleted blog ID</th>
        <th>Linked comments ID</th>
    </tr>
    </thead>
    <tbody>
        {foreach $aCommentsOnlineBlogs as $nBlogId=>$aData}
        <tr>
            <td>{$nBlogId}</td>
            <td>
                {foreach $aData as $aUser}
                {$aUser.comment_id}
                {/foreach}
            </td>
        </tr>
        {/foreach}
    </tbody>
</table>
<form method="post">
    <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}"/>
    <input type="hidden" name="do_action" value="clear_blogs_co"/>
    <button class="btn {if $aCommentsOnlineBlogs}btn-primary{else} disabled{/if}">{$oLang->_adm_db_clear_unlinked_blogs}</button>
</form>

{/block}


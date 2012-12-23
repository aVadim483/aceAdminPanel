<table class="table table-striped table-bordered table-condensed">
    <thead>
    <tr>
        <th width="40px">&nbsp;</th>
        <th width="50px">Blog ID</th>
        <th>User</th>
        <th>Title</th>
        <th>Date</th>
        <th>Type</th>
        <th>Users</th>
        <th>Votes</th>
        <th>Rating</th>
        <th></th>
    </tr>
    </thead>

    <tbody>
    {foreach $aBlogs as $aBlog}
        {assign var=sBlogTitle value=$aBlog.blog_title|escape:'html'}
    <tr>
        <td class="name">
            {if $aBlog.blog_type=='personal'}
                <i class="icon-pencil icon-gray opacity50"></i>
            {else}
                <a href="{router page='blog'}edit/{$aBlog.blog_id}/" title="{$oLang->_adm_blog_edit}">
                    <i class="icon-pencil"></i></a>
            {/if}
            <a href="#" title="{$oLang->_adm_blog_delete}" onclick="$ace.blog.del('{$oLang->Get('adm_blog_del_confirm', "blog=>$sBlogTitle")}','{$sBlogTitle}','{$aBlog.blog_id}'); return false;">
                <i class="icon-remove"></i></a>
        </td>
        <td class="number">{$aBlog.blog_id}</td>
        <td>
            <a href="{router page='admin'}users/profile/{$aBlog.user_login}">{$aBlog.user_login}</a>
        </td>
        <td class="name">
            <a href="{$aBlog.blog_url_full}">{$sBlogTitle}</a>
        </td>
        <td class="center">{$aBlog.blog_date_add}</td>
        <td class="center">{if $aBlog.blog_type!='personal'}<b>{/if}{$aBlog.blog_type}{if $aBlog.blog_type!='personal'}</b>{/if}</td>
        <td class="number">{$aBlog.blog_count_user}</td>
        <td class="number">{$aBlog.blog_count_vote}</td>
        <td class="number">{$aBlog.blog_rating}</td>
        <td>
            {if $ls.plugin.aceblogextender}
            {if $aBlog.blog_premoderation}
                <i class="icon icon-warning-sign" title="{$oLang->_mblog_premoderation}" rel="tooltip"></i>
            {else}
                <i class="icon icon-empty"></i>
            {/if}
            {if $aBlog.attach_allow}<i class="icon icon-tags" title="{$oLang->_mblog_attach_allow_notice}" rel="tooltip">{/if}
            {/if}
        </td>
    </tr>
    {/foreach}
    </tbody>
</table>

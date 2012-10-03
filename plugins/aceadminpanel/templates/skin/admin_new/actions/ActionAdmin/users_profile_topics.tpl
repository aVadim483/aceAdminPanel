<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th align="center" width="40px">&nbsp;</th>
        <th align="center" width="50px">Topic ID</th>
        <th align="center">Title</th>
        <th align="center">Date Add</th>
        <th align="center">Comments</th>
        <th align="center">Votes</th>
        <th align="center">Rating</th>
    </tr>
    </thead>

    <tbody>
{foreach from=$aTopics item=oTopic}
    <tr>
        <td align="right">
            <a href="{router page='topic'}edit/{$oTopic->getId()}/" title="{$oLang->adm_topic_edit}">
                <i class="icon-pencil"></i></a>
            &nbsp;
            <a href="#" title="{$oLang->adm_topic_delete}"
               onclick="AdminTopicDelete('{$oLang->adm_topic_del_confirm}','{$oTopic->getTitle()}',{$oTopic->getId()}); return false;">
                <i class="icon-remove"></i></a>
        </td>
        <td class="number">
            {$oTopic->getId()}&nbsp;
        </td>
        <td class="title">
            <a href="{$oTopic->getUrl()}">{$oTopic->getTitle()}</a>
        </td>
        <td class="center">
            {$oTopic->getDateAdd()}
        </td>
        <td class="number">
            {$oTopic->getCountComment()}
        </td>
        <td class="number">
            {$oTopic->getCountVote()}
        </td>
        <td class="number">
            {$oTopic->getRating()}
        </td>
    </tr>
{/foreach}
    </tbody>

</table>

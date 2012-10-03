<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th align="center" width="40px">&nbsp;</th>
        <th align="center" width="50px">ID</th>
        <th align="center" width="50px">Date</th>
        <th align="center" width="50px">ip</th>
        <th align="center">Text</th>
        <th align="center" width="200px">Topic</th>
        <th align="center" width="40px">Votes</th>
        <th align="center" width="40px">Rating</th>
    </tr>
    </thead>

    <tbody>
    {foreach $aComments as $oComment}
    {assign var=oTopic value=$oComment->getTarget()}
    <tr>
        <td>
            <!-- a href="{router page='admin'}edit/comment/{$oComment->getId()}/">
                <img src="{$sWebPluginSkin}/images/edit.gif" alt="{$oLang->page_admin_action_edit}" title="{$oLang->page_admin_action_edit}" />
            </a -->
            <i class="icon-pencil icon-gray opacity50"></i>
        </td>
        <td class="number">
            {$oComment->getId()}&nbsp;
        </td>
        <td class="center">
            {$oComment->getDate()}
        </td>
        <td class="center">
            {$oComment->getUserIp()}
        </td>
        <td class="title">
            <a href="{$oTopic->getUrl()}#comment{$oComment->getId()}">{$oComment->getText()}</a>
        </td>
        <td class="title">
            <a href="{$oTopic->getUrl()}">{$oTopic->getTitle()}</a>
        </td>
        <td class="number">
            {$oComment->getCountVote()}
        </td>
        <td class="number">
            {$oComment->getRating()}
        </td>
    </tr>
    {/foreach}
    </tbody>

</table>

{if !$noShowSystemMessage}
    {if $aMsgError}
        {foreach from=$aMsgError item=aMsg}
        <div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">×</button>
            {if $aMsg.title!=''}
                <h4 class="alert-heading">{$aMsg.title}:</h4>
            {/if}
            {$aMsg.msg}
        </div>
        {/foreach}
    {/if}


    {if $aMsgNotice}
        {foreach from=$aMsgNotice item=aMsg}
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>
            {if $aMsg.title!=''}
                <h4 class="alert-heading">{$aMsg.title}:</h4>
            {/if}
            {$aMsg.msg}
        </div>
        {/foreach}
    {/if}
{/if}
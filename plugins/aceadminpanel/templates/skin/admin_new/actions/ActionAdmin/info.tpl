{extends file='index.tpl'}

{block name="content"}

<form action="" method="post" target="_blank" class="adm-report">
    <div class="row-fluid">
        {foreach $aCommonInfo as $sSectionKey=>$aSection}
            <div class="span8 well" {if $aSection@iteration is odd}style="margin-left:0;clear:left;"{/if}>
                <h3>{$aSection.label}</h3>
                {foreach $aSection.data as $sKey=>$aItem}
                    <p>
                        {if ($aItem.label)}
                            {$aItem.label}:
                        {/if}
                        <span class="adm_info_value">{$aItem.value}</span> {if ($aItem['.html'])}{$aItem['.html']}{/if}
                    </p>
                {/foreach}
                <hr/>
                <div class="adm_info_input">
                    <label class="checkbox">
                        <input type="checkbox" id="adm_report_{$sSectionKey}" name="adm_report_{$sSectionKey}"
                               checked="checked"/>
                        {$oLang->_adm_button_checkin}
                    </label>
                </div>
            </div>
        {/foreach}

    </div>
    <div class="form-actions form-horizontal fix-on-container">
        <div class="control-group">
            <label class="control-label">{$oLang->_adm_button_report}</label>

            <div class="controls">
                <label class="radio">
                    <input type="radio" name="report" id="reportTxt" value="TXT" checked="checked">
                    TXT
                </label>
                <label class="radio">
                    <input type="radio" name="report" id="reportXml" value="XML">
                    XML
                </label>
            </div>
        </div>

        <div class="navbar fix-on-bottom">
            <div class="navbar-inner">
                <div class="container">
                    <input type="submit" id="butAdmReport" value="{$oLang->_adm_button_report}"
                           class="btn btn-primary pull-right"/>
                </div>
            </div>
        </div>
        <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}"/>
    </div>
</form>

{/block}
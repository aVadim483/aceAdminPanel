{extends file='index.tpl'}

{block name="content"}
    {if !$submit_cache_save}

    <form method="post" action="">
        <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}"/>

        <h3>{$oLang->_adm_menu_reset_cache}</h3>

        <div class="offset1">
            <label class="checkbox">
            <input type="checkbox" id="adm_cache_clear_data" name="adm_cache_clear_data" checked/>
            {$oLang->_adm_cache_clear_data}</label>
            <span class="help-block">{$oLang->_adm_cache_clear_data_notice}</span>
        </div>

        <div class="offset1">
        <label class="checkbox">
            <input type="checkbox" id="adm_cache_clear_headfiles" name="adm_cache_clear_headfiles" checked/>
            {$oLang->_adm_cache_clear_headfiles}</label>
            <span class="help-block">{$oLang->_adm_cache_clear_headfiles_notice}</span>
        </div>

        <div class="offset1">
        <label class="checkbox">
            <input type="checkbox" id="adm_cache_clear_smarty" name="adm_cache_clear_smarty" checked/>
            {$oLang->_adm_cache_clear_smarty}</label>
            <span class="help-block">{$oLang->_adm_cache_clear_smarty_notice}</span>
        </div>

        <h3>{$oLang->_adm_menu_reset_config}</h3>

        <div class="offset1">
        <label class="checkbox">
            <input type="checkbox" id="adm_reset_config_data" name="adm_reset_config_data"/>
            {$oLang->_adm_reset_config_data}</label>
            <span class="help-block">{$oLang->_adm_reset_config_data_notice}</span>
        </div>

        <div class="form-actions fix-on-container">
            <div class="navbar fix-on-bottom">
                <div class="navbar-inner">
                    <div class="container">
                        <input type="submit" name="adm_reset_submit" value="{$oLang->_adm_execute}" class="btn btn-primary pull-right"/>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {else}

    <form method="post" action="">
        <input type="submit" name="admin_continue" value="{$oLang->_adm_continue}"/>
    </form>

    {/if}

{/block}
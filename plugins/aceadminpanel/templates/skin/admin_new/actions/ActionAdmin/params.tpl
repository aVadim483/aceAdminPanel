{extends file='index.tpl'}

{block name="content"}

<h3>{$oLang->adm_params_title}</h3>
<div class=topic>

    <form action="" method="POST">
        <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />
        <p>
            <label for="param_reserved_urls">{$oLang->adm_page_options_urls}:</label>
            <input type="text" id="param_reserved_urls" name="param_reserved_urls" value="{$sParamPageUrlReserved}"  class="w100p" /><br />
            <span class="help-block">{$oLang->adm_page_options_urls_notice}</span>
        </p>

        <p>
            <label for="param_items_per_page">{$oLang->adm_param_items_per_page}:</label>
            <input type="text" id="param_items_per_page" name="param_items_per_page" value="{$sParamItemsPerPage}"  class="w50" /><br />
            <span class="help-block">{$oLang->adm_param_items_per_page_notice}</span>
        </p>

        <!-- p>
            <label for="param_votes_per_page">{$oLang->adm_param_votes_per_page}:</label>
            <input type="text" id="param_votes_per_page" name="param_votes_per_page" value="{$sParamVotesPerPage}"  class="w100p" /><br />
            <span class="help-block">{$oLang->adm_param_votes_per_page_notice}</span>
        </p -->

        <!-- p>
            <label for="param_edit_footer">{$oLang->adm_param_edit_footer}:</label>
            <input type="text" id="param_edit_footer" name="param_edit_footer" value="{$sParamEditFooter}"  class="w100p" /><br />
            <span class="help-block">{$oLang->adm_param_edit_footer_notice}</span>
        </p -->

        <p>
            <label for="param_vote_value">{$oLang->adm_param_vote_value}:</label>
            <input type="text" id="param_vote_value" name="param_vote_value" value="{$nParamVoteValue}"  class="w50" /><br />
            <span class="help-block">{$oLang->adm_param_vote_value_notice}</span>
        </p>

        <p>
            <label class="checkbox">{$oLang->adm_param_check_password}:
                <input type="checkbox" id="param_check_password" name="param_check_password" value="1" {if ($bParamCheckPassword)}checked{/if} /><br />
            </label>
            <span class="help-block">{$oLang->adm_param_check_password_notice}</span>
        </p>

        <div class="form-actions fix-on-container">
            <div class="navbar fix-on-bottom">
                <div class="navbar-inner">
                    <div class="container">
                        <input type="submit" name="submit_options_save" value="{$oLang->adm_save}" class="btn btn-primary pull-right" />&nbsp;
                    </div>
                </div>
            </div>
        </div>

    </form>

</div>

{/block}
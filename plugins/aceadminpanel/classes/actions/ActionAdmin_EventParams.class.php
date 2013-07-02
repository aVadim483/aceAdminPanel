<?php
    /*---------------------------------------------------------------------------
 * @Plugin Name: aceAdminPanel
 * @Plugin Id: aceadminpanel
 * @Plugin URI: 
 * @Description: Advanced Administrator's Panel for LiveStreet/ACE
 * @Version: 2.0.382
 * @Author: Vadim Shemarov (aka aVadim)
 * @Author URI: 
 * @LiveStreet Version: 1.0.1
 * @File Name: %%filename%%
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *----------------------------------------------------------------------------
 */

class PluginAceadminpanel_ActionAdmin_EventParams extends PluginAceadminpanel_Inherit_ActionAdmin_EventParams
{
    protected function RegisterEvent()
    {
        parent::RegisterEvent();
        $this->AddEvent('params', 'EventParams');
    }

    public function EventParams()
    {
        $this->sMenuSubItemSelect = 'params';

        if ($this->_getRequestCheck('submit_options_save')) {
            if ($this->EventParamsSubmit()) {
                $this->_MessageNotice($this->Lang_Get('adm_saved_ok'), 'params');
            } else {
                $this->_MessageError($this->Lang_Get('adm_saved_err'), 'params');
            }
        }

        $this->Viewer_Assign('sParamPageUrlReserved', implode(',', $this->aConfig['reserverd_urls']));
        $this->Viewer_Assign('sParamItemsPerPage', $this->aConfig['items_per_page']);
        $this->Viewer_Assign('sParamVotesPerPage', $this->aConfig['votes_per_page']);
        $this->Viewer_Assign('sParamEditFooter', htmlspecialchars($this->aConfig['edit_footer_text']));

        $this->Viewer_Assign('nParamVoteValue', $this->aConfig['vote_value']);

        $this->Viewer_Assign('bParamCheckPassword', $this->aConfig['check_password']);
        $this->_PluginSetTemplate('params');
    }

    protected function EventParamsSubmit()
    {
        $bOk = true;
        if (isset($_POST['param_reserved_urls'])) {
            $aReservedUrls = explode(',', preg_replace("/\s+/", '', getRequest('param_reserved_urls')));
            $aNewReservedUrls = Array();
            foreach ($aReservedUrls as $sUrl) {
                if (func_check($sUrl, 'login', 1, 50)) $aNewReservedUrls[] = $sUrl;
            }
            $this->aConfig['reserverd_urls'] = $aNewReservedUrls;
            $sReservedUrls = implode(',', $aNewReservedUrls);
            $result = $this->PluginAceadminpanel_Admin_SetValue('param_reserved_urls', $sReservedUrls);
            $bOk = $bOk AND $result['result'];
        }
        if (isset($_POST['param_items_per_page'])) {
            $result = $this->PluginAceadminpanel_Admin_SetValue('param_items_per_page', intval(getRequest('param_items_per_page')));
            $bOk = $bOk AND $result['result'];
        }
        if (isset($_POST['param_votes_per_page'])) {
            $result = $this->PluginAceadminpanel_Admin_SetValue('param_votes_per_page', intval(getRequest('param_votes_per_page')));
            $bOk = $bOk AND $result['result'];
        }
        if (isset($_POST['param_edit_footer'])) {
            $result = $this->PluginAceadminpanel_Admin_SetValue('param_edit_footer', getRequest('param_edit_footer'));
            $bOk = $bOk AND $result['result'];
        }
        if (isset($_POST['param_vote_value'])) {
            $result = $this->PluginAceadminpanel_Admin_SetValue('param_vote_value', intval(getRequest('param_vote_value')));
            $bOk = $bOk AND $result['result'];
        }
        if (isset($_POST['param_check_password'])) {
            $param = intval(getRequest('param_check_password'));
        } else {
            $param = 0;
        }
        $result = $this->PluginAceadminpanel_Admin_SetValue('param_check_password', $param);
        $bOk = $bOk AND $result['result'];
        if ($bOk) $this->_InitParams();

        return $bOk;
    }

}

// EOF
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

class PluginAceadminpanel_ActionAdmin_EventInvites extends PluginAceadminpanel_Inherit_ActionAdmin_EventInvites
{
    protected function RegisterEvent()
    {
        parent::RegisterEvent();
        $this->AddEvent('invites', 'EventInvites');
    }

    protected function EventInvites()
    {
        if ($this->GetParam(0) == 'new') {
            $sMode = 'new';
            $this->_inviteNew();
        } else {
            $sMode = 'list';
            $this->_inviteList();
        }

        $this->Viewer_Assign('sMode', $sMode);

        if ($this->oUserCurrent->isAdministrator()) {
            $iCountInviteAvailable = -1;
        } else {
            $iCountInviteAvailable = $this->User_GetCountInviteAvailable($this->oUserCurrent);
        }
        $this->Viewer_Assign('iCountInviteAvailable', $iCountInviteAvailable);
        $this->Viewer_Assign('iCountInviteUsed', $this->User_GetCountInviteUsed($this->oUserCurrent->getId()));
        $this->Viewer_Assign('USER_USE_INVITE', Config::Get('general.reg.invite'));
        $this->Viewer_Assign('sInviteOrder', getRequest('invite_order'));
        $this->Viewer_Assign('sInviteSort', getRequest('invite_sort'));

        //$this->Viewer_Assign('include_tpl', Plugin::GetTemplatePath($this->sPlugin) . '/actions/ActionAdmin/users_invites.tpl');
        $this->_PluginSetTemplate('invites');
    }

    protected function _inviteNew()
    {
        $sInviteMode = $this->_getRequestCheck('adm_invite_mode');
        if (!$sInviteMode) $sInviteMode = 'mail';
        $iInviteCount = 0 + intval(getRequest('invite_count'));
        $aNewInviteList = array();

        if ($this->_getRequestCheck('adm_invite_submit')) {
            if ($sInviteMode == 'text') {
                if ($iInviteCount <= 0) {
                    $this->_MessageError($this->Lang_Get('adm_invaite_text_empty'));
                } else {
                    for ($i = 0; $i < $iInviteCount; $i++) {
                        $oInvite = $this->User_GenerateInvite($this->oUserCurrent);
                        $aNewInviteList[$i + 1] = $oInvite->GetCode();
                    }
                    $this->_MessageNotice($this->Lang_Get('adm_invaite_text_done', array('num' => $iInviteCount)));
                }
            } else {
                $sEmails = str_replace("\n", ' ', getRequest('invite_mail'));
                $sEmails = str_replace(';', ' ', $sEmails);
                $sEmails = str_replace(',', ' ', $sEmails);
                $sEmails = preg_replace('/\s{2,}/', ' ', $sEmails);
                $aEmails = explode(' ', $sEmails);
                $iInviteCount = 0;
                foreach ($aEmails as $sEmail) {
                    if ($sEmail) {
                        if (func_check($sEmail, 'mail')) {
                            $oInvite = $this->User_GenerateInvite($this->oUserCurrent);
                            $this->Notify_SendInvite($this->oUserCurrent, $sEmail, $oInvite);
                            $aNewInviteList[$sEmail] = $oInvite->GetCode();
                            $iInviteCount += 1;
                        } else {
                            $aNewInviteList[$sEmail] = '### ' . $this->Lang_Get('settings_invite_mail_error') . ' ###';
                        }
                    }
                }
                if ($iInviteCount) {
                    $this->_MessageNotice($this->Lang_Get('adm_invaite_mail_done', array('num' => $iInviteCount)));
                }
            }
        }
        $this->Viewer_Assign('sInviteMode', $sInviteMode);
        $this->Viewer_Assign('iInviteCount', $iInviteCount);
        $this->Viewer_Assign('aNewInviteList', $aNewInviteList);
    }

    protected function _inviteList()
    {
        if (getRequest('action', null, 'post') == 'delete') {
            $this->_inviteDelete();
        }
        $sInviteOrder = getRequest('invite_order');
        $sInviteSort = getRequest('invite_sort');

        // Передан ли номер страницы
        if (preg_match("/^page(\d+)$/i", $this->getParam(0), $aMatch)) {
            $iPage = $aMatch[1];
        } else {
            $iPage = 1;
        }

        $aParam = array();
        if ($sInviteSort AND
            in_array($sInviteSort, array('id', 'code', 'user_from', 'date_add', 'user_to', 'date_used'))
        ) {
            $aParam['sort'] = $sInviteSort;
        }
        if ($sInviteOrder) $aParam['order'] = intval($sInviteOrder);
        // Получаем список инвайтов
        $iCount = 0;
        $aResult = $this->PluginAceadminpanel_Admin_GetInvites($iCount, $iPage, $this->aConfig['items_per_page'], $aParam);
        $aInvites = $aResult['collection'];

        // Формируем постраничность
        $aPaging = $this->Viewer_MakePaging($aResult['count'], $iPage, $this->aConfig['items_per_page'], 4, Router::GetPath('admin') . 'invites');
        if ($aPaging) {
            $this->Viewer_Assign('aPaging', $aPaging);
        }
        $this->Viewer_Assign('aInvites', $aInvites);
        $this->Viewer_Assign('iCount', $aResult['count']);

    }

    protected function _inviteDelete()
    {
        $this->Security_ValidateSendForm();

        $aIds = array();
        foreach($_POST as $sKey=>$sVal) {
            if ((substr($sKey, 0, 7) == 'invite_') AND ($nId = intval(substr($sKey, 7)))){
                $aIds[] = $nId;
            }
        }
        if ($aIds) {
            $nResult = $this->PluginAceadminpanel_Admin_DelInvites($aIds);
            $this->_MessageNotice($this->Lang_Get('adm_invaite_deleted', array('num' => $nResult)));
        }
    }
}

// EOF
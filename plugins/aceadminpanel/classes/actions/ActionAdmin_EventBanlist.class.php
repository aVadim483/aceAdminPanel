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

class PluginAceadminpanel_ActionAdmin_EventBanlist extends PluginAceadminpanel_Inherit_ActionAdmin_EventBanlist
{
    protected function RegisterEvent()
    {
        parent::RegisterEvent();
        $this->AddEvent('banlist', 'EventBanlist');
    }

    public function EventBanlist()
    {
        $sMode = $this->GetParam(0);
        if (!$sMode) $sMode = 'users';

        if ($this->_GetRequestCheck('adm_user_action') == 'adm_user_ban') {
            $sMode = $this->_doBan($sMode);
        }
        if ($sMode == 'ips') {
            if ($this->getParam(1) == 'del') {
                $this->_banlistIpDel();
            }
            return $this->_banlistIps();
        } else {
            if ($this->getParam(1) == 'del') {
                $this->_banlistUserDel();
            }
            return $this->_banlistUsers();
        }
        $this->Viewer_Assign('sMode', $sMode);
    }

    protected function _banlistUsers()
    {
        if (($sData = $this->Session_Get('adm_userlist_filter'))) {
            $aFilter = unserialize($sData);
        } else {
            $aFilter = array();
        }
        if (($sData = $this->Session_Get('adm_userlist_sort'))) {
            $aSort = unserialize($sData);
        } else {
            $aSort = array();
        }
        if (isset($aFilter['admin'])) unset($aFilter['admin']);

        // Передан ли номер страницы
        if (preg_match("/^page(\d+)$/i", $this->getParam(1), $aMatch)) {
            $iPage = $aMatch[1];
        } else {
            $iPage = 1;
        }

        // Получаем список забаненных юзеров
        $iCount = 0;
        $aResult = $this->PluginAceadminpanel_Admin_GetBanList($iCount, $iPage, $this->aConfig['items_per_page'], $aFilter, $aSort);
        $aUserList = $aResult['collection'];

        // Формируем постраничность
        $aPaging = $this->Viewer_MakePaging(
            $aResult['count'], $iPage, $this->aConfig['items_per_page'], 4, Router::GetPath('admin') . 'banlist/users/'
        );
        if ($aPaging) {
            $this->Viewer_Assign('aPaging', $aPaging);
        }

        //if (isset($aFilter['login']) AND $aFilter['login']) $sUserFilterLogin = $aFilter['login'];
        //elseif (isset($aFilter['like']) AND $aFilter['like']) $sUserFilterLogin = $aFilter['like'];
        //else $sUserFilterLogin = '';

        $this->Viewer_Assign('aUserList', $aUserList);
        $this->Viewer_Assign('aFilter', $aFilter);
        $this->Viewer_Assign('aSort', $aSort);
        $this->Viewer_Assign('USER_USE_ACTIVATION', Config::Get('general.reg.activation'));
    }

    /**
     * Список забаненных IP-адресов
     */
    protected function _banlistIps()
    {
        $sMode = 'ips';
        if ($this->GetParam(2) == 'del') {
            $nId = $this->GetParam(3);
            $this->EventUsersUnBanIp($nId);
        }

        // Передан ли номер страницы
        if (preg_match('/^page(\d+)$/i', $this->getParam(1), $aMatch)) {
            $iPage = $aMatch[1];
        } else {
            $iPage = 1;
        }

        // Получаем список забаненных ip-адресов
        $iCount = 0;
        $aResult = $this->PluginAceadminpanel_Admin_GetBanListIp($iCount, $iPage, $this->aConfig['items_per_page']);
        $aIpList = $aResult['collection'];

        // Формируем постраничность
        $aPaging = $this->Viewer_MakePaging(
            $aResult['count'], $iPage, $this->aConfig['items_per_page'], 4, Router::GetPath('admin') . 'banlist/ips/'
        );
        if ($aPaging) {
            $this->Viewer_Assign('aPaging', $aPaging);
        }
        $this->Viewer_Assign('aIpList', $aIpList);
        $this->Viewer_Assign('sMode', $sMode);
    }

    protected function _getIpMask($sField)
    {
        $sNum = getRequest($sField, '*');
        if (!$sNum) $sNum = '*';
        elseif ($sNum != '*') $sNum = intval($sNum);
        return $sNum;
    }
    protected function _doBan($sMode)
    {
        $aIpMask = array(
            $this->_getIpMask('user_filter_ip1'),
            $this->_getIpMask('user_filter_ip2'),
            $this->_getIpMask('user_filter_ip3'),
            $this->_getIpMask('user_filter_ip4'),
        );
        $sIp1 = $sIp2 = $aIpMask[0] . '.' . $aIpMask[1] . '.' . $aIpMask[2] . '.' . $aIpMask[3];

        if ($sIp1 != '*.*.*.*') {
            if (strpos($sIp1, '*') !== false) {
                $sIp1 = str_replace('*', '0', $sIp1);
                $sIp2 = str_replace('*', '255', $sIp2);
            }
            if ($this->_doBanByIp($sIp1, $sIp2)) {
                return 'ips';
            }
        }

        if ($sLogin = getRequest('user_filter_login')) {
            if ($this->_doBanByLogin($sLogin)) {
                return 'users';
            }
        }
        return $sMode;
    }

    protected function _doBanByIp($sIp1, $sIp2)
    {
        $sComment = getRequest('ban_comment');

        if (getRequest('ban_period') == 'days') {
            $nDays = intval(getRequest('ban_days'));
        } else {
            $nDays = null;
        }
        if ($this->PluginAceadminpanel_Admin_SetBanIp($sIp1, $sIp2, $nDays, $sComment)) {
            $this->_MessageNotice($this->Lang_Get('adm_saved_ok'), 'banip:add');
            return true;
        } else {
            $this->_MessageError($this->Lang_Get('adm_saved_err'), 'banip:add');
        }
    }

    protected function _doBanByLogin($sUserLogin)
    {
        $bOk = false;
        if ($sUserLogin == $this->oUserCurrent->GetLogin()) {
            $this->_MessageError($this->Lang_Get('adm_cannot_ban_self'), 'users:ban');
            return false;
        }
        if (getRequest('ban_period') == 'days') {
            $nDays = intval(getRequest('ban_days'));
        } else {
            $nDays = null;
        }
        $sComment = getRequest('ban_comment');
        if ($sUserLogin AND ($oUser = $this->PluginAceadminpanel_Admin_GetUserByLogin($sUserLogin))) {
            if (mb_strtolower($sUserLogin) == 'admin') {
                $this->_MessageError($this->Lang_Get('adm_cannot_with_admin'), 'users:ban');
            } elseif ($oUser->IsAdministrator()) {
                $this->_MessageError($this->Lang_Get('adm_cannot_ban_admin'), 'users:ban');
            } else {
                $this->PluginAceadminpanel_Admin_SetUserBan($oUser->GetId(), $nDays, $sComment);
                $this->_MessageNotice($this->Lang_Get('adm_saved_ok'), 'users:ban');
                $bOk = true;
            }
        } else {
            $this->_MessageError($this->Lang_Get('adm_user_not_found', Array('user' => $sUserLogin)), 'users:ban');
        }
        return $bOk;
    }

    protected function _banlistUserDel()
    {
        $this->Security_ValidateSendForm();

        $sUserLogin = $this->GetParam(2);
        if ($sUserLogin AND ($nUserId = $this->PluginAceadminpanel_Admin_GetUserId($sUserLogin))) {
            if ($this->PluginAceadminpanel_Admin_ClearUserBan($nUserId)) {
                $this->_messageNotice($this->Lang_Get('adm_saved_ok'), 'users:unban');
            } else {
                $this->_messageError($this->Lang_Get('adm_saved_err'), 'users:unban');
            }
        }
        ACE::HeaderLocation(Router::GetPath('admin') . 'banlist/users/');
    }

    protected function _banlistIpDel()
    {
        $this->Security_ValidateSendForm();

        $nId = $this->GetParam(2);
        if ($this->PluginAceadminpanel_Admin_ClearBanIp($nId)) {
            $this->_messageNotice($this->Lang_Get('adm_saved_ok'), 'banip:delete');
        } else {
            $this->_messageError($this->Lang_Get('adm_saved_err'), 'banip:delete');
        }
        ACE::HeaderLocation(Router::GetPath('admin') . 'banlist/ips/');
    }


}

// EOF
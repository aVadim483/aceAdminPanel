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

class PluginAceadminpanel_ModuleUser_EntityUser extends PluginAceadminpanel_Inherit_ModuleUser_EntityUser
{
    public function SetProperty($sProp, $xData)
    {
        $this->_aData[$sProp] = $xData;
    }

    public function GetProperty($sProp)
    {
        if (isset($this->_aData[$sProp])) return $this->_aData[$sProp];
        else return null;
    }

    public function GetBanLine()
    {
        return $this->GetProperty('banline');
    }

    public function IsBannedUnlim()
    {
        return ((bool)$this->GetProperty('banunlim'));
    }

    public function GetBanComment()
    {
        return $this->GetProperty('bancomment');
    }

    public function IsBannedByLogin()
    {
        $dBanline = $this->getBanLine();
        return ($this->IsBannedUnlim()
            OR ($dBanline AND ($dBanline > date('Y-m-d H:i:s')) AND $this->GetProperty('banactive')));
    }

    public function IsBannedByIp()
    {
        return ($this->GetProperty('ban_ip'));
    }

    public function IsBanned()
    {
        return ($this->IsBannedByLogin() OR $this->IsBannedByIp());
    }

    public function GetCountComments()
    {
        $nResult = parent::GetCountComments();
        if (is_null($nResult)) $nResult = intval($this->GetProperty('comments_count'));
        return $nResult;
    }

    public function GetCountTopics()
    {
        $nResult = parent::GetCountTopics();
        if (is_null($nResult)) $nResult = intval($this->GetProperty('topics_count'));
        return $nResult;
    }

}

// EOF
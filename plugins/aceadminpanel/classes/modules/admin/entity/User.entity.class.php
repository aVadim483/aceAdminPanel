<?php
/*---------------------------------------------------------------------------
 * @Plugin Name: aceAdminPanel
 * @Plugin Id: aceadminpanel
 * @Plugin URI: 
 * @Description: Advanced Administrator's Panel for LiveStreet/ACE
 * @Version: 1.5.210
 * @Author: Vadim Shemarov (aka aVadim)
 * @Author URI: 
 * @LiveStreet Version: 0.5
 * @File Name: User.entity.class.php
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *----------------------------------------------------------------------------
 */

class PluginAceadminpanel_ModuleAdmin_EntityUser extends ModuleUser_EntityUser
{
    public function GetProperty($prop) {
        if (isset($this->_aData[$prop])) return $this->_aData[$prop];
        else return null;
    }

    public function GetBanLine() {
        return $this->GetProperty('banline');
    }

    public function IsBannedUnlim() {
        return ($this->GetProperty('banunlim'));
    }

    public function GetBanComment() {
        return $this->GetProperty('bancomment');
    }

    public function IsBannedByLogin() {
        $dBanline=$this->getBanLine();
        return ($this->IsBannedUnlim() || ($dBanline && ($dBanline > date("Y-m-d H:i:s")) && $this->GetProperty('banactive')));
    }

    public function IsBannedByIp() {
        return ($this->GetProperty('ban_ip'));
    }

    public function IsBanned() {
        return ($this->IsBannedByLogin() || $this->IsBannedByIp());
    }

    public function GetCountComments() {
        return ($this->GetProperty('comments_count'));
    }

    public function GetCountTopics() {
        return ($this->GetProperty('tpoics_count'));
    }

}

// EOF
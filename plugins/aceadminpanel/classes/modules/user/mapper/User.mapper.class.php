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

class PluginAceadminpanel_ModuleUser_MapperUser extends PluginAceadminpanel_Inherit_ModuleUser_MapperUser
{
    public function GetCountAdministrators()
    {
        $sql = "SELECT count(*) as count FROM " . Config::Get('db.table.user_administrator') . "";
        $result = $this->oDb->selectCell($sql);
        return $result;
    }

}

// EOF
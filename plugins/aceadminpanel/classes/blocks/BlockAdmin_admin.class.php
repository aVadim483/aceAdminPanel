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

class PluginAceadminpanel_BlockAdmin_Admin extends Block
{
    public function Exec()
    {
        $nAdminMsgCount = Config::Get('module.admin.options.admin_msg_count');
        if (!$nAdminMsgCount) $nAdminMsgCount = $this->PluginAceadminpanel_Admin_GetValue('param_items_per_page', 15);
        $oUserCurrent = $this->User_GetUserCurrent();
        if ($oUserCurrent) {
            $aTalkList = $this->Talk_GetTalksByUserId($oUserCurrent->getId(), 1, $nAdminMsgCount);
            $this->Viewer_Assign('aTalks', $aTalkList['collection']);
        }
    }
}

// EOF
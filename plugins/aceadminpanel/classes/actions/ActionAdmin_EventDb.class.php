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

class PluginAceadminpanel_ActionAdmin_EventDb extends PluginAceadminpanel_Inherit_ActionAdmin_EventDb
{
    protected function RegisterEvent()
    {
        parent::RegisterEvent();
        $this->AddEvent('db', 'EventDb');
    }

    public function EventDb()
    {
        if ($this->getParam(0) == 'blogs') {
            $this->_eventDbBlogs();
        } else {
            $this->_PluginSetTemplate('db');
        }
    }

    protected function _eventDbBlogs()
    {
        $sDoAction = getRequest('do_action');
        if ($sDoAction == 'clear_blogs_joined') {
            $aJoinedBlogs = $this->PluginAceadminpanel_Admin_GetUnlinkedBlogsForUsers();
            if ($aJoinedBlogs) {
                $this->PluginAceadminpanel_Admin_DelUnlinkedBlogsForUsers(array_keys($aJoinedBlogs));
            }
        }
        elseif ($sDoAction == 'clear_blogs_co') {
            $aCommentsOnlineBlogs = $this->PluginAceadminpanel_Admin_GetUnlinkedBlogsForCommentsOnline();
            if ($aCommentsOnlineBlogs) {
                $this->PluginAceadminpanel_Admin_DelUnlinkedBlogsForCommentsOnline(array_keys($aCommentsOnlineBlogs));
            }
        }
        $aJoinedBlogs = $this->PluginAceadminpanel_Admin_GetUnlinkedBlogsForUsers();
        $aCommentsOnlineBlogs = $this->PluginAceadminpanel_Admin_GetUnlinkedBlogsForCommentsOnline();
        $this->Viewer_Assign('aJoinedBlogs', $aJoinedBlogs);
        $this->Viewer_Assign('aCommentsOnlineBlogs', $aCommentsOnlineBlogs);
        $this->_PluginSetTemplate('db_blogs');
    }

}

// EOF
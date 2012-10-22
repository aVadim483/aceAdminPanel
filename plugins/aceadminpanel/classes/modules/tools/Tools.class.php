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
 * @File Name: Tools.class.php
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *----------------------------------------------------------------------------
 */

class PluginAceadminpanel_ModuleTools extends Module {

    public function Init() {
        $this->oMapper = Engine::GetMapper(__CLASS__);
    }

    public function ClearComments() {
        if ($this->oMapper->ClearComments()) {
            $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array("comment_update"));
            return true;
        }
        return false;
    }
}

// EOF
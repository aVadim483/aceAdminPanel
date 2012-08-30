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
 * @File Name: Vote.class.php
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *----------------------------------------------------------------------------
 */

class PluginAceadminpanel_ModuleVote extends PluginAceadminpanel_Inherit_ModuleVote {

    public function Init() {
        //$this->oMapper = HelperPlugin::GetMapper();
        $this->oMapper = Engine::GetMapper(__CLASS__);
    }

    public function UpdateVote($oVote) {
        if ($this->oMapper->UpdateVote($oVote)) {
            $this->Cache_Delete("vote_{$oVote->getTargetType()}_{$oVote->getTargetId()}_{$oVote->getVoterId()}");
            $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("vote_update_{$oVote->getTargetType()}_{$oVote->getVoterId()}"));
            return true;
        }
        return false;
    }
}

// EOF
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

class PluginAceadminpanel_ModuleVote_MapperVote extends ModuleVote_MapperVote {

    public function UpdateVote($oVote) {
        $sql = "UPDATE ".Config::Get('db.table.vote')."
                    SET vote_direction=?d, vote_value=?f, vote_date=?
                    WHERE target_id=?d and target_type=? and user_voter_id=?d
		";
        if (false!==$this->oDb->query($sql, $oVote->getDirection(), $oVote->getValue(), $oVote->getDate(), $oVote->getTargetId(),$oVote->getTargetType(),$oVote->getVoterId())) {
            return true;
        }
        return false;
    }
}

// EOF
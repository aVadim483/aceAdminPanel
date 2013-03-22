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

class PluginAceadminpanel_ModuleTopic_MapperTopic extends PluginAceadminpanel_Inherit_ModuleTopic_MapperTopic
{

    public function DeleteTopic($sTopicId)
    {
        $sql =
                "
            DELETE FROM " . Config::Get('db.table.topic') . "
            WHERE
                topic_id = ?d
            ";
        $this->oDb->query($sql, $sTopicId);
        $sql =
                "
                DELETE FROM " . Config::Get('db.table.topic_content') . "
                WHERE
                    topic_id = ?d
                ";
        $this->oDb->query($sql, $sTopicId);
        return true;
    }

    public function ClearStreamByTopic($aTopicsId)
    {
        if (!is_array($aTopicsId)) $aTopicsId = array($aTopicsId);
        else $aTopicsId = array_unique($aTopicsId);

        $sql = "
            DELETE FROM " . Config::Get('db.table.stream_event') . "
            WHERE event_type LIKE '%_topic' AND target_id IN (?a)
        ";

        $this->oDb->query($sql, $aTopicsId);
        return true;
    }

}

// EOF
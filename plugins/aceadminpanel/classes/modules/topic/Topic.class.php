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

/**
 * Модуль для работы с топиками
 *
 */
class PluginAceadminpanel_ModuleTopic extends PluginAceadminpanel_Inherit_ModuleTopic
{
    protected $oMapper;

    public function Init()
    {
        parent::Init();
        $this->oMapper = Engine::GetMapper(__CLASS__);
    }

    /**
     * Удаляет топик, а заодно и всё связи по топику (комменты, голосования, избранное и проч.)
     *
     * @param   obj|int     $oTopicId
     * @return  bool
     */
    public function DeleteTopic($oTopicId)
    {
        parent::DeleteTopic($oTopicId);
        if ($oTopicId instanceof ModuleTopic_EntityTopic) {
            $nTopicId = $oTopicId->getId();
        } else {
            $nTopicId = $oTopicId;
        }

        $this->DeleteTopicAdditionalData($nTopicId);

        $this->Topic_DeleteTopicAdditionalData($nTopicId);
        $this->Topic_DeleteTopicTagsByTopicId($nTopicId);
        $this->Topic_DeleteTopicReadByArrayId($nTopicId);

        $this->Comment_DeleteCommentByTargetId($nTopicId, 'topic');

        $this->ClearStreamByTopic($nTopicId);

        // * Чистим зависимые кеши ПОСЛЕ удаления топика
        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('topic_update'));
        $this->Cache_Delete('topic_' . $nTopicId);
        return true;
    }

    /**
     * Чистка ленты по ID топика
     *
     * @param   int|obj $oTopicId
     * @return  bool
     */
    public function ClearStreamByTopic($oTopicId)
    {
        if (is_object($oTopicId)) {
            $nTopicId = $oTopicId->getId();
        } else {
            $nTopicId = (int)$oTopicId;
        }
        return $this->oMapper->ClearStreamByTopic($nTopicId);
    }

}

// EOF
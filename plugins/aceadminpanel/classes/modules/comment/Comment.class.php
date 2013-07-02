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
class PluginAceadminpanel_CommentTopic extends PluginAceadminpanel_Inherit_ModuleComment
{
    public function DeleteCommentByTargetId($nTargetId, $sTarget)
    {
        $aComments = parent::GetCommentByTargetId($nTargetId, $sTarget);
        parent::DeleteCommentByTargetId($nTargetId, $sTarget);
        parent::DeleteCommentOnlineByTargetId($nTargetId, $sTarget);
        if ($aComments) {
            $this->ClearStreamByComment($aComments);
        }

    }

    /**
     * Чистка ленты по ID коммента
     *
     * @param   int|object $oCommentId
     * @return  bool
     */
    public function ClearStreamByComment($oCommentId)
    {
        if (is_object($oCommentId)) {
            $aCommentsId = array($oCommentId->getId());
        } elseif (is_array($oCommentId)) {
            $aCommentsId = array();
            foreach ($oCommentId as $xComment) {
                if (is_object($xComment)) {
                    $aCommentsId[] = $xComment->getId();
                } else {
                    $aCommentsId[] = (int)$xComment;
                }
            }
        } else {
            $aCommentsId = array((int)$oCommentId);
        }
        return $this->oMapper->ClearStreamByComment($aCommentsId);
    }

}

// EOF
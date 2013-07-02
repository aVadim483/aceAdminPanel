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
 * Обработка УРЛа вида /topic/ - управление своими топиками
 *
 */
class PluginAceadminpanel_ActionAjax extends PluginAceadminpanel_Inherit_ActionAjax
{
    private $sPlugin = 'aceadminpanel';

    protected function RegisterEvent()
    {
        parent::RegisterEvent();
        $this->AddEventPreg('/^admin$/i', '/^vote$/i', '/^user$/i', 'EventAdminVoteUser');
        $this->AddEventPreg('/^admin$/i', '/^userfields$/i', 'EventAdminUserFields');
        $this->AddEventPreg('/^admin$/i', '/^setprofile$/i', 'EventAdminSetprofile');
        $this->AddEventPreg('/^admin$/i', '/^gettalk$/i', 'EventAdminGettalk');
    }

    protected function EventAdminVoteUser()
    {
        if (!$this->oUserCurrent OR !$this->oUserCurrent->isAdministrator()) {
            return parent::EventVoteUser();
        }

        if (!($oUser = $this->User_GetUserById(getRequest('idUser', null, 'post')))) {
            $this->Message_AddErrorSingle($this->Lang_Get('user_not_found'), $this->Lang_Get('error'));
            return;
        }

        $iValue = getRequest('value', null, 'post');

        if ($this->oUserCurrent AND $this->oUserCurrent->isAdministrator()) {
            // первичное голосование
            if (!($oUserVote = $this->Vote_GetVote($oUser->getId(), 'user', $this->oUserCurrent->getId()))) {
                $oUserVote = Engine::GetEntity('Vote');
                $oUserVote->setTargetId($oUser->getId());
                $oUserVote->setTargetType('user');
                $oUserVote->setVoterId($this->oUserCurrent->getId());
                $oUserVote->setDirection($iValue);
                $oUserVote->setDate(date('Y-m-d H:i:s'));
                $iVal = (float)$this->Rating_VoteUser($this->oUserCurrent, $oUser, $iValue);
                $oUserVote->setValue($iVal);
                $oUser->setCountVote($oUser->getCountVote() + 1);
                if ($this->Vote_AddVote($oUserVote) AND $this->User_Update($oUser)) {
                    $this->Message_AddNoticeSingle($this->Lang_Get('user_vote_ok'), $this->Lang_Get('attention'));
                    $this->Viewer_AssignAjax('iRating', $oUser->getRating());
                    $this->Viewer_AssignAjax('iSkill', $oUser->getSkill());
                    $this->Viewer_AssignAjax('iCountVote', $oUser->getCountVote());
                    // * Добавляем событие в ленту
                    $this->Stream_write($oUserVote->getVoterId(), 'vote_user', $oUser->getId());
                } else {
                    $this->Message_AddErrorSingle($this->Lang_Get('adm_vote_error'), $this->Lang_Get('error'));
                }
            }
                // повторное голосование
            else {
                if (Config::Get('plugin.aceadminpanel.admin_many_votes')) {
                    // * Повторное голосование админа
                    $iNewValue = $oUserVote->getValue() + $iValue;
                    $oUserVote->setDirection($iNewValue);
                    $oUserVote->setDate(date('Y-m-d H:i:s'));
                    $iVal = (float)$this->Rating_VoteUser($this->oUserCurrent, $oUser, $iValue);
                    $oUserVote->setValue($oUserVote->getValue() + $iVal);
                    $oUser->setCountVote($oUser->getCountVote() + 1);
                    if ($this->Vote_UpdateVote($oUserVote) AND $this->User_Update($oUser)) {
                        $this->Message_AddNoticeSingle($this->Lang_Get('user_vote_ok'), $this->Lang_Get('attention'));
                        $this->Viewer_AssignAjax('iRating', $oUser->getRating());
                        $this->Viewer_AssignAjax('iSkill', $oUser->getSkill());
                        $this->Viewer_AssignAjax('iCountVote', $oUser->getCountVote());
                    } else {
                        $this->Message_AddErrorSingle($this->Lang_Get('adm_repeat_vote_error'), $this->Lang_Get('error'));
                    }
                } else {
                    $this->Message_AddErrorSingle($this->Lang_Get('user_vote_error_already'), $this->Lang_Get('attention'));
                }
            }
        } else {
            $this->Message_AddErrorSingle($this->Lang_Get('adm_action_for_admin_only'), $this->Lang_Get('attention'));
        }

    }

    protected function EventAdminSetprofile()
    {
        $nUserId = intval(getRequest('user_id'));
        if (($oUser = $this->User_GetUserById($nUserId))) {
            if (isset($_REQUEST['profile_about'])) $oUser->setProfileAbout(getRequest('profile_about'));
            if (isset($_REQUEST['profile_site'])) $oUser->setUserProfileSite(trim(getRequest('profile_site')));
            //if (isset($_REQUEST['profile_site_name'])) $oUser->setUserProfileSiteName(trim(getRequest('profile_site_name')));
            if (isset($_REQUEST['profile_email'])) $oUser->setMail(trim(getRequest('profile_email')));
            /* контроль не работает!!!
            if ($this->User_Update($oUser) !== false) {
                $this->Message_AddNoticeSingle($this->Lang_Get('adm_saved_ok'), 'aceAdminPanel');
            } else {
                $this->Message_AddErrorSingle($this->Lang_Get('adm_saved_err'), 'aceAdminPanel');
            }
            */
            $this->User_Update($oUser); // контроль не работает, поэтому пока так
            $this->Message_AddNoticeSingle($this->Lang_Get('adm_saved_ok'), 'aceAdminPanel');
        } else {
            $this->Message_AddErrorSingle($this->Lang_Get('user_not_found'), $this->Lang_Get('error'));
        }
    }

    protected function EventAdminGettalk()
    {
        $sTalkId = getRequest('talk_id');
        if ($oTalk = $this->Talk_GetTalkById($sTalkId)) {
            $bStateError = false;
            $sTitle = $oTalk->GetTitle();
            $sText = $oTalk->GetText();
            if ((substr($sText, 0, 4) == 'To: ') && $n = strpos($sText, 'Msg: ')) {
                $sText = trim(substr($sText, $n + 5));
            }
            $this->Viewer_AssignAjax('sTitle', $sTitle);
            $this->Viewer_AssignAjax('sText', $sText);
        } else {
            $this->Viewer_AssignAjax('sTitle', $this->Lang_Get('error'));
            $this->Viewer_AssignAjax('sText', 'Message not found');
        }
        $this->Viewer_AssignAjax('bStateError', $bStateError);
    }

    public function EventAdminUserFields()
    {
        switch (getRequest('action')) {
            // * Создание нового поля
            case 'add':
                if (!$this->_checkUserField()) {
                    return;
                }
                $oField = Engine::GetEntity('User_Field');
                $oField->setName(getRequest('name'));
                $oField->setTitle(getRequest('title'));
                $oField->setPattern(getRequest('pattern'));

                $iId = $this->User_addUserField($oField);
                if (!$iId) {
                    $this->Message_AddError($this->Lang_Get('system_error'), $this->Lang_Get('error'));
                    return;
                }
                // * Прогружаем переменные в ajax ответ
                $this->Viewer_AssignAjax('id', $iId);
                $this->Viewer_AssignAjax('lang_delete', $this->Lang_Get('user_field_delete'));
                $this->Viewer_AssignAjax('lang_edit', $this->Lang_Get('user_field_update'));
                $this->Message_AddNotice($this->Lang_Get('user_field_added'), $this->Lang_Get('attention'));
                break;
            // * Удаление поля
            case 'delete':
                if (!getRequest('id')) {
                    $this->Message_AddError($this->Lang_Get('system_error'), $this->Lang_Get('error'));
                    return;
                }
                $this->User_deleteUserField(getRequest('id'));
                $this->Message_AddNotice($this->Lang_Get('user_field_deleted'), $this->Lang_Get('attention'));
                break;
            // * Изменение поля
            case 'update':
                if (!getRequest('id')) {
                    $this->Message_AddError($this->Lang_Get('system_error'), $this->Lang_Get('error'));
                    return;
                }
                if (!$this->User_userFieldExistsById(getRequest('id'))) {
                    $this->Message_AddError($this->Lang_Get('system_error'), $this->Lang_Get('error'));
                    return false;
                }
                if (!$this->_checkUserField()) {
                    return;
                }
                $oField = Engine::GetEntity('User_Field');
                $oField->setId(getRequest('id'));
                $oField->setName(getRequest('name'));
                $oField->setTitle(getRequest('title'));
                $oField->setPattern(getRequest('pattern'));

                if ($this->User_updateUserField($oField)) {
                    $this->Message_AddError($this->Lang_Get('system_error'), $this->Lang_Get('error'));
                    return;
                }
                $this->Message_AddNotice($this->Lang_Get('user_field_updated'), $this->Lang_Get('attention'));
                break;
        }
    }

    /**
     * Проверка поля на корректность
     *
     * @return unknown
     */
    protected function _checkUserField()
    {
        if (!getRequest('title')) {
            $this->Message_AddError($this->Lang_Get('user_field_error_add_no_title'), $this->Lang_Get('error'));
            return false;
        }
        if (!getRequest('name')) {
            $this->Message_AddError($this->Lang_Get('user_field_error_add_no_name'), $this->Lang_Get('error'));
            return false;
        }
        // * Не допускаем дубликатов по имени
        if ($this->User_userFieldExistsByName(getRequest('name'), getRequest('id'))) {
            $this->Message_AddError($this->Lang_Get('user_field_error_name_exists'), $this->Lang_Get('error'));
            return false;
        }
        return true;
    }

}

// EOF
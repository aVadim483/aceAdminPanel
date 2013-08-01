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

class PluginAceadminpanel_ActionAdmin_EventUsers extends PluginAceadminpanel_Inherit_ActionAdmin_EventUsers
{
    protected function RegisterEvent()
    {
        parent::RegisterEvent();
        $this->AddEvent('users', 'EventUsers');
    }

    protected function EventUsers()
    {
        $this->sMenuSubItemSelect = 'list';

        if (($sAdminAction = $this->_getRequestCheck('adm_user_action'))) {
            if ($sAdminAction == 'adm_ban_user') {
                $this->EventUsersBan();
            } elseif ($sAdminAction == 'adm_unban_user') {
                $this->EventUsersUnBan();
            } elseif ($sAdminAction == 'adm_user_setadmin') {
                $this->EventUsersAddAdministrator();
            } elseif ($sAdminAction == 'adm_del_user') {
                $this->EventUsersDelete();
            } elseif ($sAdminAction == 'adm_user_message') {
                $this->EventUsersMessage();
            }
        }
        if ($this->GetParam(0) == 'activate') { // активация юзера
            $this->EventUsersActivate();
        } elseif ($this->GetParam(0) == 'profile') { // профиль юзера
            //$this->_AddBlock('right', 'admin_user');
            return $this->EventUsersProfile();
        } elseif ($this->GetParam(0) == 'fields') { // кастомные поля пользователей
            $this->sMenuSubItemSelect = 'fields';
            return $this->EventUsersFields();
        } elseif ($this->GetParam(0) == 'invites') { // инвайты
            $this->sMenuSubItemSelect = 'invites';
            return $this->EventUsersInvites();
        } elseif ($this->GetParam(0) == 'admins' AND $this->GetParam(1) == 'del') {
            $this->EventUsersDelAdministrator();
        } else {
            $this->EventUsersList();
        }
    }

    protected function EventUserfields()
    {
        $xResult = parent::EventUserFields();
        //return $this->EventUsersFields();
        return $xResult;
    }

    protected function EventUsersBan($sUserLogin = null)
    {
        $bOk = false;
        if (!$sUserLogin) $sUserLogin = getRequest('ban_login');
        if ($sUserLogin == $this->oUserCurrent->GetLogin()) {
            $this->_messageError($this->Lang_Get('adm_cannot_ban_self'), 'users:ban');
            return false;
        }
        if (getRequest('ban_period') == 'days') {
            $nDays = intval(getRequest('ban_days'));
        } else {
            $nDays = null;
        }
        $sComment = getRequest('ban_comment');
        if ($sUserLogin AND ($oUser = $this->PluginAceadminpanel_Admin_GetUserByLogin($sUserLogin))) {
            if (mb_strtolower($sUserLogin) == 'admin') {
                $this->_messageError($this->Lang_Get('adm_cannot_with_admin'), 'users:ban');
            } elseif ($oUser->IsAdministrator()) {
                $this->_messageError($this->Lang_Get('adm_cannot_ban_admin'), 'users:ban');
            } else {
                $this->PluginAceadminpanel_Admin_SetUserBan($oUser->GetId(), $nDays, $sComment);
                $this->_MessageNotice($this->Lang_Get('adm_saved_ok'), 'users:ban');
                $bOk = true;
            }
        } else {
            $this->_messageError($this->Lang_Get('adm_user_not_found', Array('user' => $sUserLogin)), 'users:ban');
        }

        //if (getRequest('adm_user_ref')) ACE::HeaderLocation(getRequest('adm_user_ref'));
        return $bOk;
    }

    protected function EventUsersUnBan($sUserLogin = null)
    {
        if (!$sUserLogin) $sUserLogin = getRequest('ban_login');
        if ($sUserLogin AND ($nUserId = $this->PluginAceadminpanel_Admin_GetUserId($sUserLogin))) {
            if ($this->PluginAceadminpanel_Admin_ClearUserBan($nUserId)) {
                $this->_messageNotice($this->Lang_Get('adm_saved_ok'), 'users:unban');
            } else {
                $this->_messageError($this->Lang_Get('adm_saved_err'), 'users:unban');
            }
        }
    }

    protected function EventUsersAddAdministrator()
    {
        $aUserLogins = ACE::Str2Array($this->_getRequestCheck('user_login_admin'), ',', true);
        if ($aUserLogins)
            foreach ($aUserLogins as $sUserLogin) {
                if (!$sUserLogin OR !($oUser = $this->PluginAceadminpanel_Admin_GetUserByLogin($sUserLogin))) {
                    $this->_messageError($this->Lang_Get('adm_user_not_found', $sUserLogin), 'admins:add');
                } elseif ($oUser->IsBanned()) {
                    $this->_messageError($this->Lang_Get('adm_cannot_be_banned'), 'admins:add');
                } elseif ($oUser->IsAdministrator()) {
                    $this->_messageError($this->Lang_Get('adm_already_added'), 'admins:add');
                } else {
                    if ($this->PluginAceadminpanel_Admin_AddAdministrator($oUser->GetId())) {
                        $this->_messageNotice($this->Lang_Get('adm_saved_ok'), 'admins:add');
                    } else {
                        $this->_messageError($this->Lang_Get('adm_saved_err'), 'admins:add');
                    }
                }
            }
        if (getRequest('adm_user_ref')) ACE::HeaderLocation(getRequest('adm_user_ref'));
    }

    protected function EventUsersDelAdministrator()
    {
        $sUserLogin = $this->_getRequestCheck('user_login');
        if (!$sUserLogin OR !($oUser = $this->PluginAceadminpanel_Admin_GetUserByLogin($sUserLogin))) {
            $this->_messageError($this->Lang_Get('adm_user_not_found', $sUserLogin), 'admins:delete');
        } else {
            if (mb_strtolower($sUserLogin) == 'admin') {
                $this->_messageError($this->Lang_Get('adm_cannot_with_admin'), 'admins:delete');
            } elseif ($this->PluginAceadminpanel_Admin_DelAdministrator($oUser->GetId())) {
                $this->_messageNotice($this->Lang_Get('adm_saved_ok'), 'admins:delete');
            } else {
                $this->_messageError($this->Lang_Get('adm_saved_err'), 'admins:delete');
            }
        }
        if (getRequest('adm_user_ref')) ACE::HeaderLocation(getRequest('adm_user_ref'));
        else ACE::HeaderLocation(Config::Get('path.root.web') . '/' . ROUTE_PAGE_ADMIN . '/users/admins/');
    }

    protected function EventUsersBanIp()
    {
        $ip1_1 = getRequest('adm_ip1_1');
        $ip1_2 = getRequest('adm_ip1_2');
        $ip1_3 = getRequest('adm_ip1_3');
        $ip1_4 = getRequest('adm_ip1_4');

        $ip2_1 = getRequest('adm_ip2_1');
        $ip2_2 = getRequest('adm_ip2_2');
        $ip2_3 = getRequest('adm_ip2_3');
        $ip2_4 = getRequest('adm_ip2_4');

        $sComment = getRequest('ban_comment');

        $ip1 = $ip1_1 . '.' . $ip1_2 . '.' . $ip1_3 . '.' . $ip1_4;
        $ip2 = $ip2_1 . '.' . $ip2_2 . '.' . $ip2_3 . '.' . $ip2_4;
        if (getRequest('ban_period') == 'days') {
            $nDays = intVal(getRequest('ban_days'));
        } else {
            $nDays = null;
        }
        if ($this->PluginAceadminpanel_Admin_SetBanIp($ip1, $ip2, $nDays, $sComment)) {
            $this->_messageNotice($this->Lang_Get('adm_saved_ok'), 'banip:add');
        } else {
            $this->_messageError($this->Lang_Get('adm_saved_err'), 'banip:add');
        }
        if (getRequest('adm_user_ref')) ACE::HeaderLocation(getRequest('adm_user_ref'));
    }

    protected function EventUsersMessageSeparate()
    {
        $bOk = true;

        $sTitle = getRequest('talk_title');
        // if (substr($sTitle, 0, 1)!='*') $sTitle='*'.$sTitle;
        $sText = $this->Text_Parser(getRequest('talk_text'));
        $sDate = date('Y-m-d H:i:s');
        $sIp = func_getIp();

        if (($sUsers = getRequest('users_list'))) {
            $aUsers = explode(',', str_replace(' ', '', $sUsers));
        } else {
            $aUsers = array();
        }

        if ($aUsers) {
            // Если указано, то шлем самому себе со списком получателей
            if (getRequest('send_copy_self')) {
                $oSelfTalk = Engine::GetEntity('Talk_Talk');
                $oSelfTalk->setUserId($this->oUserCurrent->getId());
                $oSelfTalk->setUserIdLast($this->oUserCurrent->getId());
                $oSelfTalk->setTitle($sTitle);
                $oSelfTalk->setText($this->Text_Parser('To: <i>' . $sUsers . '</i>' . "\n\n" . 'Msg: ' . getRequest('talk_text')));
                $oSelfTalk->setDate($sDate);
                $oSelfTalk->setDateLast($sDate);
                $oSelfTalk->setUserIp($sIp);
                if (($oSelfTalk = $this->Talk_AddTalk($oSelfTalk))) {
                    $oTalkUser = Engine::GetEntity('Talk_TalkUser');
                    $oTalkUser->setTalkId($oSelfTalk->getId());
                    $oTalkUser->setUserId($this->oUserCurrent->getId());
                    $oTalkUser->setDateLast($sDate);
                    $this->Talk_AddTalkUser($oTalkUser);

                    // уведомление по e-mail
                    $oUserToMail = $this->oUserCurrent;
                    $this->Notify_SendTalkNew($oUserToMail, $this->oUserCurrent, $oSelfTalk);
                } else {
                    $bOk = false;
                }
            }

            if ($bOk) {
                // теперь рассылаем остальным - каждому отдельное сообщение
                foreach ($aUsers as $sUserLogin) {
                    if ($sUserLogin AND $sUserLogin != $this->oUserCurrent->getLogin() AND ($iUserId = $this->PluginAceadminpanel_Admin_GetUserId($sUserLogin))) {
                        $oTalk = Engine::GetEntity('Talk_Talk');
                        $oTalk->setUserId($this->oUserCurrent->getId());
                        $oTalk->setUserIdLast($this->oUserCurrent->getId());
                        $oTalk->setTitle($sTitle);
                        $oTalk->setText($sText);
                        $oTalk->setDate($sDate);
                        $oTalk->setDateLast($sDate);
                        $oTalk->setUserIp($sIp);
                        if (($oTalk = $this->Talk_AddTalk($oTalk))) {
                            $oTalkUser = Engine::GetEntity('Talk_TalkUser');
                            $oTalkUser->setTalkId($oTalk->getId());
                            $oTalkUser->setUserId($iUserId);
                            $oTalkUser->setDateLast(null);
                            $this->Talk_AddTalkUser($oTalkUser);

                            // Отправка самому себе, чтобы можно было читать ответ
                            $oTalkUser = Engine::GetEntity('Talk_TalkUser');
                            $oTalkUser->setTalkId($oTalk->getId());
                            $oTalkUser->setUserId($this->oUserCurrent->getId());
                            $oTalkUser->setDateLast($sDate);
                            $this->Talk_AddTalkUser($oTalkUser);

                            // Отправляем уведомления
                            $oUserToMail = $this->User_GetUserById($iUserId);
                            $this->Notify_SendTalkNew($oUserToMail, $this->oUserCurrent, $oTalk);
                        } else {
                            $bOk = false;
                            break;
                        }
                    }
                }
            }
        }

        if ($bOk) {
            $this->_messageNotice($this->Lang_Get('adm_msg_sent_ok'));
        } else {
            $this->_messageError($this->Lang_Get('system_error'));
        }
    }

    protected function EventUsersMessageCommon()
    {
        $bOk = true;

        $sTitle = getRequest('talk_title');
        $sText = $this->Text_Parser(getRequest('talk_text'));
        $sDate = date('Y-m-d H:i:s');
        $sIp = func_getIp();

        if (($sUsers = getRequest('users_list'))) {
            $aUsers = explode(',', str_replace(' ', '', $sUsers));
        } else {
            $aUsers = array();
        }

        if ($aUsers) {
            if ($bOk AND $aUsers) {
                $oTalk = Engine::GetEntity('Talk_Talk');
                $oTalk->setUserId($this->oUserCurrent->getId());
                $oTalk->setUserIdLast($this->oUserCurrent->getId());
                $oTalk->setTitle($sTitle);
                $oTalk->setText($sText);
                $oTalk->setDate($sDate);
                $oTalk->setDateLast($sDate);
                $oTalk->setUserIp($sIp);
                $oTalk = $this->Talk_AddTalk($oTalk);

                // добавляем себя в общий список
                $aUsers[] = $this->oUserCurrent->getLogin();
                // теперь рассылаем остальным
                foreach ($aUsers as $sUserLogin) {
                    if ($sUserLogin AND ($iUserId = $this->PluginAceadminpanel_Admin_GetUserId($sUserLogin))) {
                        $oTalkUser = Engine::GetEntity('Talk_TalkUser');
                        $oTalkUser->setTalkId($oTalk->getId());
                        $oTalkUser->setUserId($iUserId);
                        if ($sUserLogin != $this->oUserCurrent->getLogin()) {
                            $oTalkUser->setDateLast(null);
                        } else {
                            $oTalkUser->setDateLast($sDate);
                        }
                        $this->Talk_AddTalkUser($oTalkUser);

                        // Отправляем уведомления
                        if ($sUserLogin != $this->oUserCurrent->getLogin() OR getRequest('send_copy_self')) {
                            $oUserToMail = $this->User_GetUserById($iUserId);
                            $this->Notify_SendTalkNew($oUserToMail, $this->oUserCurrent, $oTalk);
                        }
                    }
                }
            }
        }

        if ($bOk) {
            $this->_messageNotice($this->Lang_Get('adm_msg_sent_ok'));
        } else {
            $this->_messageError($this->Lang_Get('system_error'));
        }
    }

    protected function EventUsersMessage()
    {
        if ($this->_getRequestCheck('send_common_message') == 'yes') {
            $this->EventUsersMessageCommon();
        } else {
            $this->EventUsersMessageSeparate();
        }
    }

    protected function EventUsersProfile()
    {
        $sUserLogin = $this->GetParam(1);
        $oUserProfile = $this->PluginAceadminpanel_Admin_GetUserByLogin($sUserLogin);
        if (!$oUserProfile) {
            return parent::EventNotFound();
        }

        $this->sMenuSubItemSelect = 'profile';
        $sMode = $this->GetParam(2);
        $aUserVoteStat = $this->PluginAceadminpanel_Admin_GetUserVoteStat($oUserProfile->getId());

        if ($sMode == 'topics') {
            $this->EventUsersProfileTopics($oUserProfile);
        } elseif ($sMode == 'blogs') {
            $this->EventUsersProfileBlogs($oUserProfile);
        } elseif ($sMode == 'comments') {
            $this->EventUsersProfileComments($oUserProfile);
        } elseif ($sMode == 'voted') {
            $this->EventUsersProfileVotedBy($oUserProfile);
        } elseif ($sMode == 'votes') {
            $this->EventUsersProfileVotesFor($oUserProfile);
        } elseif ($sMode == 'ips') {
            $this->EventUsersProfileIps($oUserProfile);
        } else {
            $sMode = 'info';
            $this->EventUsersProfileInfo($oUserProfile);
        }

        $this->Viewer_Assign('sMode', $sMode);
        $this->Viewer_Assign('oUserProfile', $oUserProfile);
        $this->Viewer_Assign('aUserVoteStat', $aUserVoteStat);
        $this->Viewer_Assign('nParamVoteValue', $this->aConfig['vote_value']);

        $this->_PluginSetTemplate('users_profile');
    }

    protected function EventUsersProfileInfo($oUserProfile)
    {
        // * Получаем список друзей
        $aUsersFriend = $this->User_GetUsersFriend($oUserProfile->getId());

        if (Config::Get('general.reg.invite')) {
            // * Получаем список тех кого пригласил юзер
            $aUsersInvite = $this->User_GetUsersInvite($oUserProfile->getId());
            $this->Viewer_Assign('aUsersInvite', $aUsersInvite);
            // * Получаем того юзера, кто пригласил текущего
            $oUserInviteFrom = $this->User_GetUserInviteFrom($oUserProfile->getId());
            $this->Viewer_Assign('oUserInviteFrom', $oUserInviteFrom);
        }
        // * Получаем список блогов в которых состоит юзер
        $aBlogUsers = $this->Blog_GetBlogUsersByUserId($oUserProfile->getId(), ModuleBlog::BLOG_USER_ROLE_USER);
        $aBlogModerators = $this->Blog_GetBlogUsersByUserId($oUserProfile->getId(), ModuleBlog::BLOG_USER_ROLE_MODERATOR);
        $aBlogAdministrators = $this->Blog_GetBlogUsersByUserId($oUserProfile->getId(), ModuleBlog::BLOG_USER_ROLE_ADMINISTRATOR);

        // * Получаем список блогов которые создал юзер
        $aBlogsOwner = $this->Blog_GetBlogsByOwnerId($oUserProfile->getId());

        $nUserTopicCount = 0;
        $aLastTopics = $this->Topic_GetTopicsPersonalByUser($oUserProfile->getId(), 1, $nUserTopicCount, 1, 5);

        // * Загружаем переменные в шаблон
        $this->Viewer_Assign('aBlogsUser', $aBlogUsers);
        $this->Viewer_Assign('aBlogsModeration', $aBlogModerators);
        $this->Viewer_Assign('aBlogsAdministration', $aBlogAdministrators);
        $this->Viewer_Assign('aBlogsOwner', $aBlogsOwner);
        $this->Viewer_Assign('aUsersFriend', $aUsersFriend);
        $this->Viewer_Assign('aLastTopicList', $aLastTopics['collection']);
    }

    protected function EventUsersProfileTopics($oUserProfile)
    {
        $sMode = 'topics';

        if (preg_match("/^page(\d+)$/i", $this->getParam(0), $aMatch)) {
            $iPage = $aMatch[1];
        } else {
            $iPage = 1;
        }

        $aResult = $this->Topic_GetTopicsPersonalByUser($oUserProfile->getId(), 1, $iPage, $this->aConfig['items_per_page']);
        $aTopics = $aResult['collection'];
        $aPaging = $this->Viewer_MakePaging($aResult['count'], $iPage, $this->aConfig['items_per_page'], 4,
            Config::Get('path.root.web') . '/' . ROUTE_PAGE_ADMIN . '/profile/' . $oUserProfile->getLogin() . '/topics/');

        $this->Viewer_Assign('aTopics', $aTopics);
        $this->Viewer_Assign('aPaging', $aPaging);

    }

    protected function EventUsersProfileBlogs($oUserProfile)
    {
        $sMode = 'blogs';

        $aBlogs = $this->PluginAceadminpanel_Admin_GetBlogsByUserId($oUserProfile->GetId());
        $this->Viewer_Assign('aBlogs', $aBlogs);
    }

    protected function EventUsersProfileComments($oUserProfile)
    {
        $sMode = 'comments';

        if (preg_match("/^page(\d+)$/i", $this->getParam(0), $aMatch)) {
            $iPage = $aMatch[1];
        } else {
            $iPage = 1;
        }

        $aResult = $this->Comment_GetCommentsByUserId($oUserProfile->getId(), 'topic', $iPage, $this->aConfig['items_per_page']);
        $aComments = $aResult['collection'];
        $aPaging = $this->Viewer_MakePaging($aResult['count'], $iPage, $this->aConfig['items_per_page'], 4, Config::Get('path.root.web') . '/' . ROUTE_PAGE_ADMIN . '/profile/' . $oUserProfile->getLogin() . '/comments/');

        $this->Viewer_Assign('aComments', $aComments);
        $this->Viewer_Assign('aPaging', $aPaging);
    }

    protected function EventUsersProfileVotedBy($oUserProfile)
    {
        $aVotes = $this->PluginAceadminpanel_Admin_GetVotedByUser($oUserProfile->getId(), $this->aConfig['votes_per_page']);

        $this->Viewer_Assign('aVoted', $aVotes);
    }

    protected function EventUsersProfileVotesFor($oUserProfile)
    {
        $aVotes = $this->PluginAceadminpanel_Admin_GetVotesForUser($oUserProfile->getId(), $this->aConfig['votes_per_page']);

        $this->Viewer_Assign('aVotes', $aVotes);
    }

    protected function EventUsersProfileIps($oUserProfile)
    {
        $aIps = $this->PluginAceadminpanel_Admin_GetUserIps($oUserProfile->getId());

        $this->Viewer_Assign('aIps', $aIps);
    }

    protected function EventUsersActivate()
    {
        $this->Security_ValidateSendForm();
        $sUserLogin = $this->GetParam(1);
        $oUser = $this->User_GetUserByLogin($sUserLogin);
        $oUser->setActivate(1);
        $oUser->setDateActivate(date('Y-m-d H:i:s'));
        $this->User_Update($oUser);
        if ($this->sPageRef) {
            ACE::HeaderLocation($this->sPageRef);
        }
    }

    protected function EventUsersList()
    {
        $nParam = 0;
        if (($sData = $this->Session_Get('adm_userlist_filter'))) {
            $aFilter = unserialize($sData);
        } else {
            $aFilter = array();
        }

        if (($sData = $this->Session_Get('adm_userlist_sort'))) {
            $aSort = unserialize($sData);
        } else {
            $aSort = array();
        }

        if ($this->getParam($nParam) == 'admins') {
            $sMode = 'admins';
            $nParam += 1;
            $aFilter['admin'] = 1;
        } elseif ($this->getParam($nParam) == 'all') {
            $sMode = 'all';
            $nParam += 1;
            $aFilter['admin'] = null;
        } else {
            $sMode = 'all';
            $aFilter['admin'] = null;
        }

        $sUserFilterIp = '*.*.*.*';
        $sUserRegDate = '';
        if ($this->_getRequestCheck('adm_user_action') == 'adm_user_seek') {
            if (($sUserLogin = getRequest('user_filter_login'))) {
                if ($this->PluginAceadminpanel_Admin_GetUserId($sUserLogin)) {
                    $aFilter['login'] = $sUserLogin;
                    $aFilter['like'] = null;
                } else {
                    $aFilter['login'] = null;
                    $aFilter['like'] = $sUserLogin;
                }
            } else {
                $aFilter['login'] = $aFilter['like'] = null;
            }

            if (($sUserEmail = getRequest('user_filter_email'))) {
                $aFilter['email'] = $sUserEmail;
            } else {
                $aFilter['email'] = null;
            }

            if (preg_match('/^\d+$/', getRequest('user_filter_ip1')) && getRequest('user_filter_ip1') < 256) {
                $aUserFilterIp[0] = getRequest('user_filter_ip1');
            } else {
                $aUserFilterIp[0] = '*';
            }
            if (preg_match('/^\d+$/', getRequest('user_filter_ip2')) && getRequest('user_filter_ip2') < 256) {
                $aUserFilterIp[1] = getRequest('user_filter_ip2');
            } else {
                $aUserFilterIp[1] = '*';
            }
            if (preg_match('/^\d+$/', getRequest('user_filter_ip3')) && getRequest('user_filter_ip3') < 256) {
                $aUserFilterIp[2] = getRequest('user_filter_ip3');
            } else {
                $aUserFilterIp[2] = '*';
            }
            if (preg_match('/^\d+$/', getRequest('user_filter_ip4')) && getRequest('user_filter_ip4') < 256) {
                $aUserFilterIp[3] = getRequest('user_filter_ip4');
            } else {
                $aUserFilterIp[3] = '*';
            }

            $sUserFilterIp = $aUserFilterIp[0] . '.' . $aUserFilterIp[1] . '.' . $aUserFilterIp[2] . '.' . $aUserFilterIp[3];
            if ($sUserFilterIp != '*.*.*.*') {
                $aFilter['ip'] = $sUserFilterIp;
            } else {
                $aFilter['ip'] = null;
            }

            if (($s = getRequest('user_filter_regdate'))) {
                if (preg_match('/(\d{4})(\-(\d{1,2})){0,1}(\-(\d{1,2})){0,1}/', $s, $aMatch)) {
                    if (isset($aMatch[1])) {
                        $sUserRegDate = $aMatch[1];
                        if (isset($aMatch[3])) {
                            $sUserRegDate .= '-' . sprintf('%02d', $aMatch[3]);
                            if (isset($aMatch[5])) {
                                $sUserRegDate .= '-' . sprintf('%02d', $aMatch[5]);
                            }
                        }
                    }
                }
                if ($sUserRegDate) {
                    $aFilter['regdate'] = $sUserRegDate;
                } else {
                    $aFilter['regdate'] = null;
                }
            } else {
                $aFilter['regdate'] = null;
            }
            if (($s = getRequest('user_list_sort'))) {
                if (in_array($s, array('id', 'login', 'regdate', 'reg_ip', 'activated', 'last_date', 'last_ip'))) {
                    $aSort = array(); // так надо на будущее
                    $sUserListSort = $s;
                    $sUserListOrder = getRequest('user_list_order');
                    $aSort[$sUserListSort] = $sUserListOrder;
                }
            } else {
                $aSort = array();
            }
        }

        // Передан ли номер страницы
        if (preg_match("/^page(\d+)$/i", $this->getParam($nParam), $aMatch)) {
            $iPage = $aMatch[1];
        } else {
            $iPage = 1;
        }

        foreach ($aFilter as $key => $val) {
            if ($val === null) unset($aFilter[$key]);
        }
        $sUserListSort = $sUserListOrder = '';
        foreach ($aSort as $key => $val) {
            if ($val !== null) {
                $sUserListSort = $key;
                $sUserListOrder = $val;
            }
        }
        $this->Session_Set('adm_userlist_filter', serialize($aFilter));
        $this->Session_Set('adm_userlist_sort', serialize($aSort));
        // Получаем список юзеров
        $iCount = 0;
        $aResult = $this->PluginAceadminpanel_Admin_GetUserList($iCount, $iPage, $this->aConfig['items_per_page'], $aFilter, $aSort);
        if (($iPage > 1) AND ($iPage > $aResult['count'] / $this->aConfig['items_per_page'])) {
            $iPage = ceil($aResult['count'] / $this->aConfig['items_per_page']);
            $aResult = $this->PluginAceadminpanel_Admin_GetUserList($iCount, $iPage, $this->aConfig['items_per_page'], $aFilter, $aSort);
        }
        $aUserList = $aResult['collection'];
        /**
         * Формируем постраничность
         */
        if ($sMode == 'admins') {
            $aPaging = $this->Viewer_MakePaging($aResult['count'], $iPage, $this->aConfig['items_per_page'], 4, Router::GetPath('admin') . 'users/admins');
        } else {
            $aPaging = $this->Viewer_MakePaging($aResult['count'], $iPage, $this->aConfig['items_per_page'], 4, Router::GetPath('admin') . 'users');
        }
        $aStat = $this->User_GetStatUsers();

        // * Загружаем переменные в шаблон
        if ($aUserList) {
            $this->Viewer_Assign('aPaging', $aPaging);
        }
        if (isset($aFilter['admin'])) unset($aFilter['admin']); // чтобы блок в админке не раскрывался

        if (isset($aFilter['login']) AND $aFilter['login']) $sUserFilterLogin = $aFilter['login'];
        elseif (isset($aFilter['like']) AND $aFilter['like']) $sUserFilterLogin = $aFilter['like']; else $sUserFilterLogin = '';

        if (isset($aFilter['ip']) AND $aFilter['ip']) $sUserFilterIp = $aFilter['ip'];
        $aUserFilterIp = explode('.', str_replace('*', '', $sUserFilterIp));
        $sUserFilterIp = implode($aUserFilterIp);

        $this->Viewer_Assign('aUserList', $aUserList);
        $this->Viewer_Assign('aStat', $aStat);
        $this->Viewer_Assign('sMode', $sMode);
        $this->Viewer_Assign('sUserListSort', $sUserListSort);
        $this->Viewer_Assign('sUserListOrder', $sUserListOrder);

        $this->Viewer_Assign('sUserFilterLogin', $sUserFilterLogin);
        $this->Viewer_Assign('aUserFilterIp', $aUserFilterIp);
        $this->Viewer_Assign('sUserFilterIp', $sUserFilterIp);
        $this->Viewer_Assign('aFilter', $aFilter);
        $this->Viewer_Assign('aSort', $aSort);
        $this->Viewer_Assign('USER_USE_ACTIVATION', Config::Get('general.reg.activation'));

        $this->_PluginSetTemplate('users_list');
    }


    // Список инвайтов
    protected function EventUsersInvites()
    {
        if ($this->GetParam(1) == 'new') {
            $sMode = 'new';
        } else {
            $sMode = 'list';
        }

        $sInviteMode = $this->_getRequestCheck('adm_invite_mode');
        if (!$sInviteMode) $sInviteMode = 'mail';
        $iInviteCount = 0 + intVal(getRequest('invite_count'));
        $aNewInviteList = array();
        $sInviteOrder = getRequest('invite_order');
        $sInviteSort = getRequest('invite_sort');

        if ($this->_getRequestCheck('adm_invite_submit')) {
            if ($sInviteMode == 'text') {
                if ($iInviteCount <= 0) {
                    $this->_messageError($this->Lang_Get('adm_invaite_text_empty'));
                } else {
                    for ($i = 0; $i < $iInviteCount; $i++) {
                        $oInvite = $this->User_GenerateInvite($this->oUserCurrent);
                        $aNewInviteList[$i + 1] = $oInvite->GetCode();
                    }
                    $this->_messageNotice($this->Lang_Get('adm_invaite_text_done', array('num' => $iInviteCount)));
                }
            } else {
                $sEmails = str_replace("\n", ' ', getRequest('invite_mail'));
                $sEmails = str_replace(';', ' ', $sEmails);
                $sEmails = str_replace(',', ' ', $sEmails);
                $sEmails = preg_replace('/\s{2,}/', ' ', $sEmails);
                $aEmails = explode(' ', $sEmails);
                $iInviteCount = 0;
                foreach ($aEmails as $sEmail) {
                    if ($sEmail) {
                        if (func_check($sEmail, 'mail')) {
                            $oInvite = $this->User_GenerateInvite($this->oUserCurrent);
                            $this->Notify_SendInvite($this->oUserCurrent, $sEmail, $oInvite);
                            $aNewInviteList[$sEmail] = $oInvite->GetCode();
                            $iInviteCount += 1;
                        } else {
                            $aNewInviteList[$sEmail] = '### ' . $this->Lang_Get('settings_invite_mail_error') . ' ###';
                        }
                    }
                }
                if ($iInviteCount) {
                    $this->_messageNotice($this->Lang_Get('adm_invaite_mail_done', array('num' => $iInviteCount)));
                }
            }
        }
        if ($sMode == 'list') {
            // Передан ли номер страницы
            if (preg_match("/^page(\d+)$/i", $this->getParam(1), $aMatch)) {
                $iPage = $aMatch[1];
            } else {
                $iPage = 1;
            }

            $aParam = array();
            if ($sInviteSort AND
                in_array($sInviteSort, array('id', 'code', 'user_from', 'date_add', 'user_to', 'date_used'))
            ) {
                $aParam['sort'] = $sInviteSort;
            }
            if ($sInviteOrder) $aParam['order'] = intVal($sInviteOrder);
            // Получаем список инвайтов
            $iCount = 0;
            $aResult = $this->PluginAceadminpanel_Admin_GetInvites($iCount, $iPage, $this->aConfig['items_per_page'], $aParam);
            $aInvites = $aResult['collection'];

            // Формируем постраничность
            $aPaging = $this->Viewer_MakePaging($aResult['count'], $iPage, $this->aConfig['items_per_page'], 4, Config::Get('path.root.web') . '/' . ROUTE_PAGE_ADMIN . '/users/invites');
            if ($aPaging) {
                $this->Viewer_Assign('aPaging', $aPaging);
            }
            $this->Viewer_Assign('aInvites', $aInvites);
            $this->Viewer_Assign('iCount', $aResult['count']);
        }
        $this->Viewer_Assign('sMode', $sMode);

        if ($this->oUserCurrent->isAdministrator()) {
            $iCountInviteAvailable = -1;
        } else {
            $iCountInviteAvailable = $this->User_GetCountInviteAvailable($this->oUserCurrent);
        }
        $this->Viewer_Assign('iCountInviteAvailable', $iCountInviteAvailable);
        $this->Viewer_Assign('iCountInviteUsed', $this->User_GetCountInviteUsed($this->oUserCurrent->getId()));
        $this->Viewer_Assign('sInviteMode', $sInviteMode);
        $this->Viewer_Assign('iInviteCount', $iInviteCount);
        $this->Viewer_Assign('USER_USE_INVITE', Config::Get('general.reg.invite'));
        $this->Viewer_Assign('aNewInviteList', $aNewInviteList);
        $this->Viewer_Assign('sInviteOrder', getRequest('invite_order'));
        $this->Viewer_Assign('sInviteSort', getRequest('invite_sort'));

        $this->Viewer_Assign('include_tpl', Plugin::GetTemplatePath($this->sPlugin) . '/actions/ActionAdmin/users/users_invites.tpl');
    }

    protected function EventUsersDelete($aUsersLogin = null)
    {
        $this->Security_ValidateSendForm();

        if (!$aUsersLogin) $aUsersLogin = ACE::Str2Array(getRequest('adm_del_login'), ',', true);
        else $aUsersLogin = ACE::Str2Array($aUsersLogin, ',', true);

        foreach ($aUsersLogin as $sUserLogin) {
            if ($sUserLogin == $this->oUserCurrent->GetLogin()) {
                $this->_messageError($this->Lang_Get('adm_cannot_del_self'), 'users:delete');
            } elseif (($oUser = $this->PluginAceadminpanel_Admin_GetUserByLogin($sUserLogin))) {
                if (mb_strtolower($sUserLogin, 'UTF-8') == 'admin') {
                    $this->_messageError($this->Lang_Get('adm_cannot_with_admin'), 'users:delete');
                } elseif ($oUser->IsAdministrator()) {
                    $this->_messageError($this->Lang_Get('adm_cannot_del_admin'), 'users:delete');
                } elseif (!getRequest('adm_user_del_confirm') AND !getRequest('adm_bulk_confirm')) {
                    $this->_messageError($this->Lang_Get('adm_cannot_del_confirm'), 'users:delete');
                } else {
                    $this->PluginAceadminpanel_Admin_DelUser($oUser->GetId());
                    $this->_messageNotice($this->Lang_Get('adm_user_deleted', Array('user' => $sUserLogin ? $sUserLogin
                        : '')), 'users:delete');
                }
            } else {
                $this->_messageError($this->Lang_Get('adm_user_not_found', Array('user' => $sUserLogin ? $sUserLogin : '')), 'users:delete');
            }
        }
        return true;
    }

}

// EOF

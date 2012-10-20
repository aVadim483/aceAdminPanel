<?php
/*---------------------------------------------------------------------------
 * @Plugin Name: aceAdminPanel
 * @Plugin Id: aceadminpanel
 * @Plugin URI: 
 * @Description: Advanced Administrator's Panel for LiveStreet/ACE
 * @Version: 2.0
 * @Author: Vadim Shemarov (aka aVadim)
 * @Author URI: 
 * @LiveStreet Version: 1.0.1
 * @File Name: %%file_name%%
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *----------------------------------------------------------------------------
 */

class PluginAceadminpanel_HookAdmin extends Hook
{
    protected $sPlugin = 'aceadminpanel';
    protected $oUser = null;
    protected $sSkinName = 'default';
    protected $sCustomConfigPath;

    public function RegisterHook()
    {
        if (Config::Get('plugin.' . $this->sPlugin . '.skin'))
            $this->sSkinName = Config::Get('plugin.' . $this->sPlugin . '.skin');
        Config::Set('path.admin.skin', '___path.root.web___/plugins/aceadminpanel/templates/skin/admin_' . $this->sSkinName);

        if (Router::GetAction() == 'admin') {
            $this->_preInit();
        }
        $this->AddHook('engine_init_complete', 'EngineInitComplete', __CLASS__, 1000);
        $this->AddHook('init_action', 'InitAction', __CLASS__, 1000);
        $this->AddHook('template_html_head_end', 'HtmlHeadEnd', __CLASS__);
        $this->AddHook('template_body_end', 'MemoryStats', __CLASS__);
    }

    protected function _preInit()
    {
        $oUser = $this->_getUser();
        if ($oUser AND $oUser->isAdministrator()) {
            Config::Set($this->sPlugin . '.saved.view.skin', Config::Get('view.skin'));
            Config::Set($this->sPlugin . '.saved.path.smarty.template', Config::Get('path.smarty.template'));
            Config::Set($this->sPlugin . '.saved.path.static.skin', Config::Get('path.static.skin'));

            Config::Set('saved.view.skin', Config::Get('view.skin'));
            Config::Set('saved.path.smarty.template', Config::Get('path.smarty.template'));
            Config::Set('saved.path.static.skin', Config::Get('path.static.skin'));

            Config::Set('view.skin', 'admin_' . $this->sSkinName);
            Config::Set('path.smarty.template', '___path.root.server___/plugins/aceadminpanel/templates/skin/___view.skin___');
            Config::Set('path.static.skin', '___path.root.web___/plugins/aceadminpanel/templates/skin/___view.skin___');
        }
    }

    protected function _getUser()
    {
        if (is_null($this->oUser) AND $this->User_IsAuthorization()) {
            if (($sUserId = $this->Session_Get('user_id'))) {
                $this->oUser = $this->PluginAceadminpanel_Admin_GetUserById($sUserId);
            } elseif (isset($_REQUEST['submit_login']) AND isset($_REQUEST['login'])) {
                $this->oUser = $this->PluginAceadminpanel_Admin_GetUserByLogin($_REQUEST['login']);
            }
        }
        return $this->oUser;
    }

    protected function _userBanned($oUser)
    {
        if ($oUser) {
            if ($oUser->IsBannedUnlim()) {
                $sText = $this->Lang_Get('adm_banned2_text');
            } else {
                $sText = $this->Lang_Get('adm_banned1_text', array('date' => $oUser->GetBanLine()));
            }
            $this->Message_AddErrorSingle($sText, $this->Lang_Get('adm_denied_title'));
            $oUser->setKey(uniqid(time(), true));
            $this->User_Update($oUser);

            $this->User_Logout();
        }
        $this->Session_DropSession();
        Router::Action('error');
    }

    // Зарезервировано
    protected function _siteClosed()
    {
        return false;
    }

    protected function _checkPluginAction()
    {
        if ($this->Session_Get($this->sPlugin . '_activate')) {
            $aPluginList = $this->PluginAceadminpanel_Plugin_GetPluginList();
            $aPlugins = array();
            foreach ($aPluginList as $sPlugin => $oPlugin) {
                if ($oPlugin->isActive()) {
                    $aPlugins[] = $sPlugin;
                }
            }
            $this->Plugin_SetActivePlugins($aPlugins);
            $this->Session_Drop($this->sPlugin . '_activate');
        }
    }

    public function EngineInitComplete()
    {
        ACE_Config::LoadCustomConfig();
        $this->Logger_CheckLogFiles(true);
    }

    public function InitAction()
    {
        $oLang = $this->Lang_Dictionary();

        $this->Viewer_Assign('oLang', $oLang);
        $this->Viewer_Assign('MSIE6', ACE::MSIE6());
        $this->Viewer_Assign('WEB_ADMIN_SKIN', ACE::MSIE6());

        $oUser = $this->_getUser();
        $this->_checkPluginAction();

        $sScript = Config::Get('path.admin.skin') . '/assets/js/' . 'ace-admin.js';
        $this->Viewer_AppendScript($sScript);

        if (Router::GetAction() == 'admin' OR Router::GetAction() == 'error') return;

        if (!$oUser) {
            if (Router::GetAction() == 'registration') {
                $aIp = ACE::GetAllUserIp();
                foreach ($aIp as $sIp) {
                    if ($this->PluginAceadminpanel_Admin_IsBanIp($sIp)) {
                        $this->Message_AddErrorSingle($this->Lang_Get('adm_banned2_text'), $this->Lang_Get('adm_denied_title'));
                        return $this->_userBanned(null);
                    }
                }
            }
            return;
        }

        if (defined('ADMIN_SITE_CLOSED') AND ADMIN_SITE_CLOSED AND !$oUser->IsAdministrator()) {
            $this->SiteClosed();
        }

        if ($oUser->IsBannedByLogin() || ($oUser->IsBannedByIp() AND !$oUser->IsAdministrator())) {
            return $this->_UserBanned($oUser);
        }
    }

    public function HtmlHeadEnd()
    {
        $sTpl = HelperPlugin::GetPluginSkinPath($this->sPlugin) . 'hook.html_head_end.tpl';
        return $this->Viewer_Fetch($sTpl);
    }

    public function MemoryStats()
    {
        $aMemoryStats['memory_limit'] = ini_get('memory_limit');
        $aMemoryStats['usage'] = ACE::MemSizeFormat(memory_get_usage());
        $aMemoryStats['peak_usage'] = ACE::MemSizeFormat(memory_get_peak_usage(true));
        $this->Viewer_Assign('aMemoryStats', $aMemoryStats);
    }
}

// EOF
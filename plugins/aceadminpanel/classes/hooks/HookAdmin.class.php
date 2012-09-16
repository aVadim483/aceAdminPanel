<?php
/*---------------------------------------------------------------------------
 * @Plugin Name: aceAdminPanel
 * @Plugin Id: aceadminpanel
 * @Plugin URI: 
 * @Description: Advanced Administrator's Panel for LiveStreet/ACE
 * @Version:
 * @Author: Vadim Shemarov (aka aVadim)
 * @Author URI: 
 * @LiveStreet Version:
 * @File Name:
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

        $this->_checkJsLib();

        if (Router::GetAction() == 'admin') {
            $this->_preInit();
        }
        $this->AddHook('engine_init_complete', 'EngineInitComplete', __CLASS__, 1000);
        $this->AddHook('init_action', 'InitAction', __CLASS__);
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

    /**
     * Определение подгружаемой js-библиотеки
     *
     * @return void
     */
    protected function _checkJsLib()
    {
        $sJsLib = '';
        if (!Config::Get('js.lib')) {
            // Сначала смотрим по файлу конфигурации скина
            $sTemplatePath = Config::Get($this->sPlugin . '.saved.path.smarty.template');
            if (!$sTemplatePath)
                $sTemplatePath = Config::Get('path.smarty.template');
            if (file_exists($sTemplatePath . '/settings/config/config.php')) {
                $aConfig = include($sTemplatePath . '/settings/config/config.php');
                if (isset($aConfig['head']['default']['js'])) {
                    $sJsLib = $this->_checkJsLibFrom($aConfig['head']['default']['js']);
                }
            }
            // Если там нет, то по уже подгруженной конфигурации
            if (!$sJsLib) {
                $sJsLib = $this->_checkJsLibFrom(Config::Get('head.default.js'));
            }
            if ($sJsLib) {
                Config::Set('js.lib', $sJsLib);
                Config::Set('js.' . $sJsLib, true);
            }
        }
    }

    protected function _checkJsLibFrom($aList)
    {
        if ($aList AND is_array($aList)) {
            foreach ($aList as $sStr) {
                if ($sStr AND is_string($sStr)) {
                    if (strpos(strtolower($sStr), '/external/jquery')) {
                        return 'jquery';
                    } elseif (strpos(strtolower($sStr), '/external/mootools')) {
                        return 'mootools';
                    }
                }
            }
        }
    }

    /**
     * Чтение конфигурационного файла
     *
     * @param   string      $sFile
     *
     * @return  array
     */
    protected function _readConfigFile($sFile)
    {
        // переменная $config нужна для того, чтоб ее можно было не определять внутри файла
        $config = array();
        $result = include($sFile);
        return ($result AND is_array($result)) ? $result : $config;
    }

    /**
     * Чтение пользовательских конфиг-файлов конкретного плагина
     *
     * @param   string      $sPlugin
     */
    protected function _loadCustomPluginConfig($sPlugin)
    {
        if (is_dir($sPath = $this->sCustomConfigPath . '/' . $sPlugin)) {
            $sKey = 'plugin.' . $sPlugin;
            $aPluginConfig = Config::Get($sKey);
            if (is_file($sPath . '/config.php')) {
                $aResult = $this->_readConfigFile($sPath . '/config.php');
                if ($aResult) {
                    $aPluginConfig = func_array_merge_assoc($aPluginConfig, $aResult);
                }
            }
            if ($aFiles = glob($sPath . '/config.*.php')) {
                foreach ($aFiles as $sFile) {
                    if (is_file($sFile)) {
                        $aResult = $this->_readConfigFile($sFile);
                        if ($aResult) {
                            $aPluginConfig = func_array_merge_assoc($aPluginConfig, $aResult);
                        }
                    }
                }
            }
            Config::Set($sKey, $aPluginConfig);
        }
    }

    public function EngineInitComplete()
    {
        if (Config::Get('plugin.' . $this->sPlugin . '.custom_config.enable')) {
            $this->sCustomConfigPath = Config::Get('plugin.' . $this->sPlugin . '.custom_config.path');
            if (!$this->sCustomConfigPath) $this->sCustomConfigPath = Config::Get('path.root.server') . '/config/plugins';
            if (is_dir($this->sCustomConfigPath)) {
                if (is_file($this->sCustomConfigPath . '/config.php')) {
                    Config::LoadFromFile($this->sCustomConfigPath . '/config.php', false);
                }
                if ($aFiles = glob($this->sCustomConfigPath . '/config.*.php')) {
                    foreach ($aFiles as $sFile) {
                        if (is_file($sFile)) {
                            Config::LoadFromFile($sFile, false);
                        }
                    }
                }
                if (Config::Get('plugin.' . $this->sPlugin . '.custom_config.plugins')) {
                    $aPligins = $this->Plugin_GetActivePlugins();
                    foreach ($aPligins as $sPlugin) {
                        $this->_loadCustomPluginConfig($sPlugin);
                    }
                }
            }
        }
    }

    public function InitAction()
    {
        $oLang = $this->Lang_Dictionary();

        $this->Viewer_Assign('oLang', $oLang);
        $this->Viewer_Assign('MSIE6', ACE::MSIE6());
        $this->Viewer_Assign('WEB_ADMIN_SKIN', ACE::MSIE6());

        $oUser = $this->_getUser();
        $this->_checkPluginAction();

        //$sScript = Config::Get('path.admin.skin') . '/js/' . 'ace-wrapper.js';
        //$this->Viewer_AppendScript($sScript);
        if ($oUser AND $oUser->IsAdministrator()
            AND Config::Get('plugin.' . $this->sPlugin . '.' . 'icon_menu')
                AND (Router::GetAction() != 'admin')
        ) {
            //$sScript = Config::Get('path.admin.skin') . '/js/' . 'icon_menu.js';
            //$this->Viewer_AppendScript($sScript);
        }

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

    public function MemoryStats()
    {
        $aMemoryStats['memory_limit'] = ini_get('memory_limit');
        $aMemoryStats['usage'] = ACE::MemSizeFormat(memory_get_usage());
        $aMemoryStats['peak_usage'] = ACE::MemSizeFormat(memory_get_peak_usage(true));
        $this->Viewer_Assign('aMemoryStats', $aMemoryStats);
    }
}

// EOF
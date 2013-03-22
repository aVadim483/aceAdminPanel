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

class PluginAceadminpanel_ActionAdmin_Event extends PluginAceadminpanel_Inherit_ActionAdmin_Event
{
    private $sPlugin = 'aceadminpanel';

    protected $sMenuHeadItemSelect; // Главное меню
    protected $sMenuItemSelect; // Активное меню
    protected $sMenuSubItemSelect; // Активное подменю
    protected $sMenuNavItemSelect; // Навигационное меню

    protected $sUserLogin = null;
    protected $sConfigFileName = 'config/config.php';

    protected $aConfig = array();

    protected $sParamPathThemes;
    protected $sParamPathLanguages;

    protected $oLogs = null;
    protected $aLogsMsg = array();

    protected $aBlocks = array();

    protected $aPluginInfo;

    protected $sPageRef = '';
    protected $sFormAction = '';

    protected $aAddons = array();
    protected $bAddonsAutoCheck = true;
    protected $sRequestPath = '';

    protected $aExternalEvents = array();

    public function Init()
    {
        $this->sCurrentEvent = Router::GetActionEvent();

        if (($result = parent::Init())) {
            return $result;
        }
        if ($this->User_IsAuthorization()) {
            $this->oUserCurrent = $this->PluginAceadminpanel_Admin_GetUserCurrent();
        }
        if (!$this->oUserCurrent OR !$this->oUserCurrent->isAdministrator()) {
            return $this->EventDenied();
        }
        if (!$this->oUserCurrent) $this->oUserCurrent = $this->User_GetUserCurrent();

        $this->Viewer_Assign('ROUTE_PAGE_ADMIN', ROUTE_PAGE_ADMIN);
        $this->Viewer_Assign('sModuleVersion', $this->PluginAceadminpanel_Admin_getVersion(true));

        $this->_InitParams();
        $this->aConfig = array_merge($this->aConfig, HelperPlugin::GetConfig());

        if (Config::Get('plugin.avalogs.admin_file') AND Config::Get('plugin.avalogs.admin_enable')) {
            if (!$this->oLogs) $this->oLogs = $this->Adminlogs_GetLogs();
            $this->oLogs->SetLogOptions('admin', array('file' => Config::Get('plugin.avalogs.admin_file')));
            $this->aLogsMsg[] = 'user=>' . $this->oUserCurrent->GetLogin() . ', ip=>' . $_SERVER["REMOTE_ADDR"]
                . ', action=>' . Router::GetAction() . ', event=>' . Router::GetActionEvent()
                . ', path=>' . Router::GetPathWebCurrent();
        }

        $this->sPageRef = ACE::Backward('url');
        if (ACE::Backward('action') == Router::GetAction()) {
            $this->sFormAction = $this->sPageRef;
        }

        //$this->_PluginSetTemplate(Router::GetActionEvent());
        $this->sMenuItemSelect = Router::GetActionEvent();
        $this->sMenuSubItemSelect = Router::GetParam(0);

        $sVerion = HelperPlugin::GetConfig('version');
        if (!$sVerion) $sVerion = ACEADMINPANEL_VERSION . '.' . ACEADMINPANEL_VERSION_BUILD;
        if (preg_match('|[a-z\-]+|i', $sVerion, $m)) {
            $sVerion = str_replace($m[0], '', $sVerion) . $m[0];
        }
        $this->aPluginInfo = array('version' => $sVerion);

        $sHtmlTitle = $this->Lang_Get('adm_title') . ' v.' . $this->PluginAceadminpanel_Admin_getVersion();

        //$this->Viewer_AddTemplateDir(HelperPlugin::GetTemplatePath(), true);
        $this->Viewer_AddHtmlTitle($sHtmlTitle);
        $this->Viewer_Assign('sAdminTitle', 'aceAdminPanel v.' . $this->PluginAceadminpanel_Admin_getVersion());
    }

    protected function _InitParams()
    {
        $this->aConfig = array(
            'reserverd_urls' => array('admin'),
            'votes_per_page' => 15,
            'items_per_page' => 15,
            'vote_value' => 10,
            'edit_footer_text' => '<div style="border-top:1px solid #CCC;color:#F99;text-align:right;font-size:0.9em;">Edited by admin at [@date]</div>',
            'path_themes' => Config::Get('path.root.server') . '/templates/skin',
            'path_languages' => Config::Get('path.root.server') . '/templates/language',
            'check_password' => 1,
        );
        $sReserverdUrls = $this->PluginAceadminpanel_Admin_GetValue('param_reserved_urls');
        if ($sReserverdUrls)
            $this->aConfig['reserverd_urls'] = array_unique(array_merge($this->aConfig['reserverd_urls'], explode(',', $sReserverdUrls)));

        $this->aConfig['items_per_page'] = $this->PluginAceadminpanel_Admin_GetValue('param_items_per_page', $this->aConfig['items_per_page']);
        $this->aConfig['votes_per_page'] = $this->PluginAceadminpanel_Admin_GetValue('param_votes_per_page', $this->aConfig['votes_per_page']);
        $this->aConfig['edit_footer_text'] = $this->PluginAceadminpanel_Admin_GetValue('param_edit_footer', $this->aConfig['edit_footer_text']);
        $this->aConfig['vote_value'] = $this->PluginAceadminpanel_Admin_GetValue('param_vote_value', $this->aConfig['vote_value']);

        //$this->bParamSiteClosed=defined('adm_SITE_CLOSED')?ADMIN_SITE_CLOSED:false;
        //$this->sParamSiteClosedPage=$this->Admin_GetValue('param_site_closed_page', $this->sParamSiteClosedPage);
        //$this->sParamSiteClosedText=$this->Admin_GetValue('param_site_closed_text', $this->sParamSiteClosedText);
        //$this->sParamSiteClosedFile=$this->Admin_GetValue('param_site_closed_file', $this->sParamSiteClosedFile);

        //$this->sParamPathThemes=Config::Get('path.root.server').'/templates/skin';
        //$this->sParamPathLanguages=Config::Get('path.root.server').'/templates/language';

        $this->aConfig['check_password'] = $this->PluginAceadminpanel_Admin_GetValue('param_check_password', $this->aConfig['check_password']);

        $oLang = $this->Lang_Dictionary();
        $this->Viewer_Assign('oLang', $oLang);
    }

    protected function RegisterEvent()
    {
        parent::RegisterEvent();
    }

    public function  __call($sName, $aArgs)
    {
        if (preg_match('/^Event([A-Z]\w+)/', $sName, $matches)) {
            /*
            $sAddonId = $this->_CheckAdminAddon($matches[1]);
            if (isset($this->aAddons[$sAddonId])) {
                return $this->_CallAdminAddon($sAddonId, $aArgs);
            } elseif ($this->bAddonsAutoCheck) {

            }
            */
        } elseif (preg_match('/^Plugin_/', $sName)) {
            $sName = 'PluginAceadminpanel_' . $sName;
        }
        if (strpos($sName, '_'))
            return Engine::getInstance()->_CallModule($sName, $aArgs);
        else
            throw new Exception("The module has no required method: " . get_called_class() . '->' . $sName . '()');
    }

    protected function _pluginSetTemplate($sTemplate)
    {
        $this->SetTemplate($this->_GetTemplateFile('/actions/ActionAdmin/' . $sTemplate . '.tpl'));
    }

    protected function _GetTemplateFile($sFile)
    {
        return HelperPlugin::GetTemplatePath($sFile);
    }

    protected function _PluginLoadLangFile($sFile)
    {
        $sFile = HelperPlugin::GetPluginPath() . '/templates/language/' . $sFile . '.php';
        $this->Lang_LoadFile($sFile);
    }

    public function Message($type, $msg, $cmd = null, $bUseSession = false)
    {
        if (Config::Get('plugin.avalogs.admin_enable') AND $this->oLogs) {
            $this->aLogsMsg[] = ' * type=>' . $type . ', cmd=>' . $cmd . ', msg=>' . $msg;
        }
        if ($type == 'error') {
            $this->Message_AddError($msg, null, $bUseSession);
        } else {
            $this->Message_AddNotice($msg, null, $bUseSession);
        }
        return $msg;
    }

    protected function _MessageError($msg, $cmd = null, $bUseSession = false)
    {
        return $this->Message('error', $msg, $cmd, $bUseSession);
    }

    protected function _MessageNotice($msg, $cmd = null, $bUseSession = false)
    {
        return $this->Message('notice', $msg, $cmd, $bUseSession);
    }

    /**
     * Получение параметров с проверкой URL источника перехода
     *
     * @param   int     $nOffset
     * @param   mixed   $xDefault
     * @return  string
     */
    public function GetParam($nOffset, $xDefault = null)
    {
        if (!$this->_checkRefererUrl()) {
            return null;
        } else {
            return parent::GetParam($nOffset, $xDefault);
        }
    }

    protected function _getLastParam($default = null)
    {
        $nNumParams = sizeof(Router::GetParams());
        if ($nNumParams > 0) {
            $iOffset = $nNumParams - 1;
            return $this->GetParam($iOffset, $default);
        }
        return null;
    }

    protected function _checkRefererUrl()
    {
        $bChecked = true;
        if ($this->_pluginConfigGet('check_url')) {
            if (!isset($_SERVER["HTTP_REFERER"])) {
                $bChecked = false;
            } else {
                $sUrl = Config::Get('path.root.web') . '/admin/';
                if (strpos($_SERVER["HTTP_REFERER"], $sUrl) === false) {
                    $bChecked = false;
                }
            }
        }
        return $bChecked;
    }

    protected function _pluginConfigGet($sParam)
    {
        return Config::Get('plugin.' . $this->sPlugin . '.' . $sParam);
    }


    /**
     * Получение REQUEST-переменной с проверкой "ключа секретности"
     *
     * @param   string  $sName
     * @param   string  $default
     * @param   string  $sType
     * @return  string
     */
    protected function _GetRequestCheck($sName, $default = null, $sType = null)
    {
        $result = getRequest($sName, $default, $sType);

        if (!is_null($result)) $this->Security_ValidateSendForm();

        return $result;
    }

    public function EventNotFound()
    {
        if ($this->oUserCurrent AND $this->oUserCurrent->isAdministrator()) {
            if (Config::Get($this->sPlugin . '.saved.view.skin')) {
                // внутри своей админки
                $this->_pluginSetTemplate('error404');
            } else {
                return Router::Action('error','404');
            }
        } else {
            ACE::HeaderLocation(Router::GetPath('error'));
        }
    }

    public function EventDenied()
    {
        $this->Message_AddErrorSingle($this->Lang_Get('adm_denied_text'), $this->Lang_Get('adm_denied_title'));
        return Router::Action('error');
    }

    /**
     * Вернуться на предыдущую страницу
     */
    protected function _gotoBackPage()
    {
        if ($this->sPageRef)
            ACE::HeaderLocation($this->sPageRef);
        else
            ACE::HeaderLocation(Router::GetPath('admin'));
    }

    public function EventShutdown()
    {
        parent::EventShutdown();

        $this->Viewer_Assign('sMenuHeadItemSelect', $this->sMenuHeadItemSelect);
        $this->Viewer_Assign('sMenuItemSelect', $this->sMenuItemSelect);
        $this->Viewer_Assign('sMenuSubItemSelect', $this->sMenuSubItemSelect);
        $this->Viewer_Assign('sMenuNavItemSelect', $this->sMenuNavItemSelect);

        $this->Viewer_Assign('aModConfig', $this->aConfig);
        $this->Viewer_Assign('DIR_PLUGIN_SKIN', Plugin::GetTemplatePath($this->sPlugin));
        //$sWebPluginSkin=ACE::Path2Url(Plugin::GetTemplatePath($this->sPlugin));
        $sWebPluginSkin = Config::Get('path.admin.skin') . '/';
        $this->Viewer_Assign('sWebPluginPath', Config::Get('path.root.web') . '/plugins/' . $this->sPlugin);
        $this->Viewer_Assign('sWebPluginSkin', $sWebPluginSkin);

        $this->Viewer_Assign('sTemplatePath', HelperPlugin::GetTemplatePath());
        $this->Viewer_Assign('sTemplatePathAction', HelperPlugin::GetTemplateActionPath());
        $this->Viewer_Assign('aPluginInfo', $this->aPluginInfo);
        $this->Viewer_Assign('sPageRef', $this->sPageRef);
        $this->Viewer_Assign('sFormAction', $this->sFormAction);
        $this->Viewer_Assign('LS_VERSION', LS_VERSION);

        //$this->Hook_AddExecFunction('template_body_begin', array($this, '_CssUrls'));
    }

}
// EOF
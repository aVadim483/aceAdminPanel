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
 * @File Name: ActionAdmin.class.php
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *----------------------------------------------------------------------------
 */

require_once 'AceAdminPlugin.class.php';

class PluginAceadminpanel_ActionAdmin extends PluginAceadminpanel_Inherit_ActionAdmin
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
    protected $aAddons = array();
    protected $bAddonsAutoCheck = true;
    protected $sRequestPath = '';

    protected $aExternalEvents = array();

    public function Init()
    {


        //$this->PluginAppendStyle('admin.css');
        //$this->PluginAppendScript('admin.js');

        $this->_addBlock('right', 'AdminInfo');
    }

    protected function RegisterEvent()
    {
        parent::RegisterEvent();
        $this->AddEvent('info', 'EventInfo');
        $this->AddEvent('site', 'EventSite');
        $this->AddEvent('plugins', 'EventPlugins');
        $this->AddEvent('pages', 'EventPages');
        $this->AddEvent('blogs', 'EventBlogs');
        $this->AddEvent('topics', 'EventTopics');
        $this->AddEvent('users', 'EventUsers');
        $this->AddEvent('tools', 'EventTools');
        $this->AddEvent('userfields', 'EventUserfields');
        $this->CheckAdminEvents();
    }

    protected function CheckAdminEvents()
    {
        $aPlugins = $this->PluginAceadminpanel_Plugin_GetPluginList();
        foreach ($aPlugins as $oPlugin) {
            $aEvents = $oPlugin->GetAdminMenuEvents();
            if ($aEvents)
                foreach ($aEvents as $sEvent => $sClass) {
                    $this->aExternalEvents[$sEvent] = $sClass;
                    $this->AddEvent($sEvent, 'EventPluginsMenu');
                }
        }
    }


    protected function MakeMenu()
    {
        $this->Viewer_AddMenu('aceadmin', $this->GetTemplateFile('/menu.admin.tpl'));
        $this->Viewer_Assign('menu', 'aceadmin');
    }

    public function SetMenuItemSelect($sItem)
    {
        $this->sMenuItemSelect = $sItem;
    }

    public function SetMenuSubItemSelect($sItem)
    {
        $this->sMenuSubItemSelect = $sItem;
    }

    public function SetMenuNavItemSelect($sItem)
    {
        $this->sMenuNavItemSelect = $sItem;
    }

    /*************************************************************************/
    protected function GetPluginName()
    {
        return $this->sPlugin;
    }

    protected function _AddBlock($sGroup, $sBlock, $aParams = array(), $bSingle = true)
    {
        if (!$aParams OR !isset($aParams['plugin'])) $aParams['plugin'] = $this->sPlugin;
        if (!$aParams OR !isset($aParams['priority'])) $aParams['priority'] = 'top';
        return $this->PluginAddBlock($sGroup, $sBlock, $aParams, $bSingle);
    }

    public function PluginAddBlockTemplate($sGroup, $sBlockTemplate, $aParams = array(), $bSingle = true)
    {
        if (!isset($aParams['plugin'])) $aParams['plugin'] = null;
        if ($bSingle AND isset($this->aBlocks[$sGroup]))
            foreach ($this->aBlocks[$sGroup] as $aBlock) {
                if ($aBlock['block'] == $sBlockTemplate AND $aBlock['params']['plugin'] == $aParams['plugin']) {
                    // уже есть
                    return;
                }
            }
        $this->aBlocks[$sGroup][] = array('block' => $sBlockTemplate, 'params' => $aParams);
    }

    public function PluginAddBlock($sGroup, $sBlock, $aParams = array(), $bSingle = true)
    {
        //$sBlockTemplate = $this->GetTemplateFile('/block.'.$sBlockName.'.tpl');
        //$this->PluginAddBlockTemplate($sGroup, $sBlockTemplate, $bSingle);
        $this->PluginAddBlockTemplate($sGroup, $sBlock, $aParams, $bSingle);
    }

    protected function PluginDelBlock($sGroup, $sBlockName)
    {
        //$sTemplate = $this->GetTemplateFile('/block.'.$sBlockName.'.tpl');
        $sTemplate = $sBlockName;
        if (isset($this->aBlocks[$sGroup]))
            foreach ($this->aBlocks[$sGroup] as $nBlock => $sBlock) {
                if ($sBlock['block'] == $sTemplate) {
                    unset($this->aBlocks[$sGroup][$nBlock]);
                    return;
                }
            }
    }

    protected function PluginAppendScript($sScript, $aParams = array())
    {
        $this->Viewer_AppendScript(Plugin::GetTemplateWebPath($this->sPlugin) . 'js/' . $sScript);
    }

    protected function PluginAppendStyle($sStyle, $aParams = array())
    {
        $this->Viewer_AppendStyle(Plugin::GetTemplateWebPath($this->sPlugin) . 'css/' . $sStyle);
    }

    /*************************************************************************/
    protected function ParseText($sText, $aData = Array())
    {
        if (!isset($aData['date'])) $aData['date'] = time();
        if (!isset($aData['user'])) {
            if ($this->oUserCurrent) {
                $aData['user'] = $this->oUserCurrent->getLogin();
            } else {
                $aData['user'] = '';
            }
        }
        return ($this->PluginAceadminpanel_Lang_ParseText($sText, $aData));
    }

    protected function GetEditFooter()
    {
        if ($this->aConfig['edit_footer_text']) {
            return "\n" . $this->ParseText($this->aConfig['edit_footer_text']);
        } else {
            return '';
        }
    }

    /**
     * Вернуться на предыдущую страницу
     */
    protected function GoToBackPage()
    {
        if ($this->sPageRef)
            admHeaderLocation($this->sPageRef);
        else
            admHeaderLocation(Router::GetPath('admin'));
    }

    /* ==================================================================================== *
     * Events
     */

    /**
     * Запрет доступа
     *
     * @return string
     */
    protected function EventDenied()
    {
        $this->Message_AddErrorSingle($this->Lang_Get('adm_denied_text'), $this->Lang_Get('adm_denied_title'));
        return Router::Action('error');
    }

    protected function EventInfo()
    {
        $this->sMenuItemSelect = Router::GetActionEvent();
        if ($sReportMode = getRequest('report', null, 'post')) {
            $this->EventInfoReport($this->_getInfoData(), $sReportMode);
        }

        if ($this->GetParam(0) == 'phpinfo') {
            $this->EventInfoPhpInfo(1);
        } elseif ($this->GetParam(0) == 'params') {
            $this->EventInfoParams();
        } else {
            $this->sMenuSubItemSelect = 'about';
            $this->PluginSetTemplate('info_about');
            //$this->SetTemplate(HelperPlugin::GetTemplateActionPath('info_about.tpl'));
        }

        $this->_AddBlock('right', 'AdminInfo');

        $this->Viewer_Assign('aCommonInfo', $this->_getInfoData());
    }



    /* ==================================================================================== *
     * URL: admin/site
     */
    protected function EventSite()
    {
        $this->sMenuHeadItemSelect = 'site';

        if ($this->GetParam(0) == 'params') {
            $this->EventSitePlugins();
        } elseif ($this->GetParam(0) == 'reset') {
            $this->sMenuSubItemSelect = 'reset';
            $this->EventSiteReset();
        } elseif ($this->GetParam(0) == 'settings') {
            $this->sMenuSubItemSelect = 'settings';
            $this->EventSiteSettings();
        } elseif ($this->GetParam(0) == 'config') {
            $this->sMenuSubItemSelect = 'config';
            $this->EventSiteConfig();
        } else {
            $this->sMenuSubItemSelect = 'settings';
            $this->EventSiteSettings();
        }

        $this->_AddBlock('right', 'AdminInfo');

        $this->PluginSetTemplate('site');
    }

    /*
    * URL: admin/site
    * ==================================================================================== */

    /* ==================================================================================== *
     * URL: admin/plugins
     */
    /*
     * URL: admin/plugins
     * ==================================================================================== */

    /* ==================================================================================== *
     * URL: admin/tools
     */
    protected function EventTools()
    {
        $this->sMenuHeadItemSelect = 'tools';

        if ($this->GetParam(0) == 'params') {
            $this->EventSitePlugins();
        } elseif ($this->GetParam(0) == 'reset') {
            $this->sMenuSubItemSelect = 'reset';
            $this->EventSiteReset();
        } elseif ($this->GetParam(0) == 'settings') {
            $this->sMenuSubItemSelect = 'settings';
            $this->EventSiteSettings();
        } else {
            $this->sMenuSubItemSelect = 'comments';
            $this->EventToolsComments();
        }

        $this->_AddBlock('right', 'AdminInfo');

        $this->PluginSetTemplate('admincontent');
        $aPlugins = $this->Plugin_GetList();
    }

    /*
     * URL: admin/tools
     * ==================================================================================== */

    /* ==================================================================================== *
     * URL: admin/pages
     */
    /*
     * URL: admin/pages
     * ==================================================================================== */

    /* ==================================================================================== *
     * URL: admin/topics/
     */
    protected function EventTopics()
    {
        $this->sMenuSubItemSelect = 'list';
        $sMode = 'all';

        $sCmd = $this->GetParam(0);
        if ($sCmd == 'delete') {
            return $this->EventTopicsDelete();
        } else {
            return parent::EventNotFound();
        }
    }

    protected function EventTopicsDelete()
    {
        $bOk = false;
        $iTopicId = intval($this->GetRequestCheck('topic_id'));
        if ($iTopicId AND ($oTopic = $this->Topic_GetTopicById($iTopicId))) {
            $bOk = $this->PluginAceadminpanel_Admin_DelTopic($oTopic);
        }
        if ($bOk) {
            $this->MessageNotice($this->Lang_Get('adm_action_ok'), 'blog_del');
        } else {
            $this->MessageError($this->Lang_Get('adm_action_err'), 'blog_del');
        }
        $this->GoToBackPage();
    }

    /*
     * URL: admin/topics/
     * ====================================================================================*/

    /* ==================================================================================== *
     * URL: admin/users
     */

    /*
  * URL: admin/users
  * ==================================================================================== */

    public function EventShutdown()
    {

        if (Config::Get('plugin.avalogs.admin_enable') AND $this->oLogs AND $this->aLogsMsg) {
            $str = '';
            foreach ($this->aLogsMsg as $key => $val) {
                if ($key) $str .= str_repeat(' ', 20);
                $str .= $val;
                if ($key < sizeof($this->aLogsMsg) - 1) $str .= "\n";
            }
            $this->oLogs->Out('admin', $str);
        }

//var_dump($this->aBlocks['right']); exit;
        foreach ($this->aBlocks as $sGroup => $aGroupBlocks) {
            //$this->Viewer_AddBlocks($sGroup, $aGroupBlocks);
            /* */
            $this->Viewer_ClearBlocks($sGroup);
            foreach ($aGroupBlocks as $aBlock) {
                if ($aBlock['params']) {
                    $aParams = $aBlock['params'];
                } else {
                    $aParams = array('plugin' => $this->sPlugin);
                }
                $this->Viewer_AddBlock($sGroup, $aBlock['block'], $aParams, isset($aParams['priority'])
                                                      ? $aParams['priority'] : null);
            }
            /* */
        }

        if ($this->aConfig['check_password'] AND
            !$this->PluginAceadminpanel_Admin_IsPasswordQuality($this->oUserCurrent)
        ) {
            $this->Message_AddError($this->Lang_Get('adm_password_quality'));
        }
        $this->MakeMenu();
        /*
        $this->Viewer_Assign('sTemplatePath', HelperPlugin::GetTemplatePath());
        $this->Viewer_Assign('sTemplatePathAction', HelperPlugin::GetTemplateActionPath());
        $this->Viewer_Assign('aPluginInfo', $this->aPluginInfo);
        $this->Viewer_Assign('sPageRef', $this->sPageRef);
        $this->Viewer_Assign('LS_VERSION', LS_VERSION);

        $this->Hook_AddExecFunction('template_body_begin', array($this, '_CssUrls'));
        */
    }

    public function _cssUrls()
    {
        $sContent = '';
        $sWebPluginSkin = admPath2Url(Plugin::GetTemplatePath($this->sPlugin));
        $sFile = Plugin::GetTemplatePath($this->sPlugin) . 'css/admin-url.css';
        if (file_exists($sFile)) {
            $sContent = file_get_contents($sFile);
            if ($sContent) {
                $sContent = preg_replace('|/\*.+\*/|iusU', '', $sContent);
                $sContent = str_replace('background-image: url(', 'background-image: url(' . $sWebPluginSkin . 'images/', $sContent);
                $sContent = '<style type="text/css">' . $sContent . '</style>';
            }
        }
        return $sContent;
    }

    protected function _callAdminAddon($aAddon, $aArgs)
    {
        if (!is_array($aAddon)) {
            if (!isset($this->aAddons[$aAddon]))
                return;
            $aAddon = $this->aAddons[$aAddon];
        }
        $sFileName = $aAddon['file'];
        $sClassName = $aAddon['class'];

        if (isset($aAddon['template']) AND $aAddon['template']) $sTemplate = $aAddon['template'];
        else $sTemplate = '';

        if (isset($aAddon['language']) AND $aAddon['language']) $sLangFile = $aAddon['language'];
        else $sLangFile = '';

        include_once $sFileName;

        $oEventClass = new $sClassName($this->oEngine, $this->sCurrentAction);

        // * load template
        if ($sTemplate) $this->Viewer_Assign('tpl_include', $sTemplate);

        // * load css
        //$sCssFile = HelperPlugin::GetTemplatePath('css/admin_site_settings.css');
        //$this->Viewer_AppendStyle($sCssFile);

        // * load language texts
        if ($sLangFile) {
            $this->Lang_LoadFile($sLangFile);
        }

        $oEventClass->SetAdminAction($this);
        $oEventClass->Init();
        $result = call_user_func_array(array($oEventClass, 'Event'), $aArgs);
        $oEventClass->EventShutdown();
        $oEventClass->Done();
        return $result;
    }

    protected function _checkAdminAddon($sAddon)
    {
        $sAddonId = strtolower($sAddon);
        if (isset($this->aAddons[$sAddonId]))
            return $sAddonId;
        if ($this->bAddonsAutoCheck) {
            $sFile = HelperPlugin::GetPluginPath() . '/classes/actions/ActionAdmin' . $sAddon . '.class.php';
            if (file_exists($sFile)) {
                $sTemplate = HelperPlugin::GetTemplatePath('admin_' . admStrUnderScore($sAddon) . '.tpl');
                $this->aAddons[$sAddonId] = array(
                    'file' => $sFile,
                    'class' => 'PluginAceadminpanel_Admin' . $sAddon,
                    'template' => (file_exists($sTemplate) ? $sTemplate : ''),
                );
                return $sAddonId;
            }
        }
        return false;
    }

    public function  __call($sName, $aArgs)
    {
        if (preg_match('/^Event([A-Z]\w+)/', $sName, $matches)) {
            $sAddonId = $this->_CheckAdminAddon($matches[1]);
            if (isset($this->aAddons[$sAddonId])) {
                return $this->_CallAdminAddon($sAddonId, $aArgs);
            } elseif ($this->bAddonsAutoCheck) {

            }
        }
        elseif (preg_match('/^Plugin_/', $sName)) {
            $sName = 'PluginAceadminpanel_' . $sName;
        }
        return Engine::getInstance()->_CallModule($sName, $aArgs);
    }
}

// EOF
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

class PluginAceadminpanel extends Plugin
{
    private $sPlugin = 'aceadminpanel';

    static $sUpdateInfo;

    /**
     * Делегирование (т.е. полное переопределение)
     *
     * @var array
     */
    public $aDelegates = array(
        'action' => array(
            'ActionAdminPlugin' => 'PluginAceblogextender_ActionAdminPlugin',
        ),
        'module' => array(),
        'entity' => array(),
        'template' => array(
            //'statistics_performance.tpl',
        ),
    );

    /**
     * Наследование
     *
     * @var array
     */
    public $aInherits = array(
        'action' => array(
            'ActionAdmin',
            //'ActionError',
            'ActionTopic',
            'ActionAjax',
        ),
        'module' => array(
            'ModuleUser' => '_ModuleUser',
            'ModuleUrl' => '_ModuleUrl',
            'ModulePlugin' => '_ModulePlugin',
            'ModuleViewer' => '_ModuleViewer',
            'ModuleAdmin' => '_ModuleAdmin',
            'ModuleTopic' => '_ModuleTopic',
            'ModuleLang' => '_ModuleLang',
            'ModuleVote' => '_ModuleVote',
            'ModuleLogger' => '_ModuleLogger',
            //'ModuleNotify' => '_ModuleNotify',
        ),
        'mapper' => array(
            'ModuleUser_MapperUser' => '_ModuleUser_MapperUser',
            'ModuleTopic_MapperTopic' => '_ModuleTopic_MapperTopic',
        ),
        'entity' => array(
            'ModuleViewer_EntityTplHook',
            'ModuleUser_EntityUser' => '_ModuleUser_EntityUser',
        ),
    );

    public function __construct() {
        if (ACE::IsMobile()) {
            unset($this->aInherits['action']);
        }
    }

    public function GetXml()
    {
        preg_match('/^Plugin([\w]+)$/i', get_class($this), $aMatches);
        $sPluginXML = Config::Get('path.root.server') . '/plugins/' . strtolower($aMatches[1]) . '/' . ModulePlugin::PLUGIN_XML_FILE;
        if ($oXml = @simplexml_load_file($sPluginXML)) {
            return $oXml;
        }
        return null;
    }

    /**
     * Активация плагина
     *
     * @return bool
     */
    public function Activate()
    {
        $oXml = $this->GetXml();
        $sPhpNeed = (string)$oXml->requires->system->php;
        if (version_compare(PHP_VERSION, $sPhpNeed) < 0) {
            $this->Message_AddErrorSingle('You need PHP version ' . $sPhpNeed . ' or more', $this->Lang_Get('error'), true);
            $result = false;
        } else {
            // Создание таблиц в базе данных при их отсутствии.
            $result = true;
            $data = $this->ExportSQL(dirname(__FILE__) . '/sql.sql');
            if (!$data['result']) {
                foreach ($data['errors'] as $err) {
                    if ($err > '') $result = false;
                }
            }

            if ($result) {
                $this->ClearCache();
                $this->Session_Set($this->sPlugin . '_activate', 1);
            } else {
                $this->Message_AddErrorSingle('Cannot update database for this plugin', $this->Lang_Get('error'), true);
            }
        }
        return $result;
    }

    /**
     * Инициализация плагина
     *
     * @return void
     */
    public function Init()
    {
        if (preg_match('/^([\d\.]+)([^\d\.].*)$/', ACEADMINPANEL_VERSION, $m)) {
            $sVersion = $m[1] . '.' . ACEADMINPANEL_VERSION_BUILD . $m[2];
        } else {
            $sVersion = ACEADMINPANEL_VERSION . '.' . ACEADMINPANEL_VERSION_BUILD;
        }
        Config::Set('plugin.aceadminpanel.version', $sVersion);

        if (ACE::IsMobile()) {
            $this->_loadPluginsConfig();
        } else {
            HelperPlugin::AutoLoadRegister(array($this, 'Autoloader'));
            $sDataFile = $this->PluginAceadminpanel_Admin_GetCustomConfigFile();
            if (!file_exists($sDataFile)) {
                $aConfigSet = $this->PluginAceadminpanel_Admin_GetValueArrayByPrefix('config.all.');
                @file_put_contents($sDataFile, serialize($aConfigSet));
            }

            $this->_loadPluginsConfig();
            $this->_ActionAdminInerits();
        }
    }

    /**
     * Деактивация плагина
     *
     * @return bool
     */
    public function Deactivate()
    {
        $this->ClearCache();
        return true;
    }

    /**
     * Загрузка дополнительных конфигураций плагинов
     */
    protected function _loadPluginsConfig()
    {
        ACE_Config::LoadCustomConfig();
    }

    protected function ClearCache()
    {
        if (!ACE::ClearDir(Config::Get('path.smarty.compiled'))) {
            $this->Message_AddErrorSingle(
                'Unable to remove content of dir <b>' . ACE::FilePath(Config::Get('path.smarty.compiled'))
                    . '</b>. It is recommended to do it manually',
                $this->Lang_Get('attention'), true);
        }
        if (!ACE::ClearDir(Config::Get('path.smarty.cache'))) {
            $this->Message_AddErrorSingle(
                'Unable to remove content of dir <b>' . ACE::FilePath(Config::Get('path.smarty.cache'))
                    . '</b>. It is recommended to do it manually',
                $this->Lang_Get('attention'), true);
        }
        $result = ACE::ClearAllCache();
        return $result;
    }

    public function GetUpdateInfo()
    {
        if (!self::$sUpdateInfo)
            self::$sUpdateInfo = $this->PluginAceadminpanel_Admin_CheckDbo($this->sPlugin);
        return self::$sUpdateInfo;
    }

    public function __call($sName, $aArgs = array())
    {
        return parent::__call($sName, $aArgs);
    }

    public function Autoloader($sClass)
    {
        if (strpos($sClass, 'PluginAceadminpanel_ActionAdmin_Event') === 0) {
            $sFile = Plugin::GetPath($this->sPlugin) . '/classes/actions/ActionAdmin_Events/' . substr($sClass, 20) . '.class.php';
            if (is_file($sFile)) {
                include_once($sFile);
            }
        }
    }

    protected function _ActionAdminInerits()
    {
        $sFrom = 'ActionAdmin';
        $sMask = Plugin::GetPath($this->sPlugin) . '/classes/actions/ActionAdmin_Event*.class.php';
        $aFiles = glob($sMask);
        asort($aFiles);
        foreach ($aFiles as $sFile) {
            $sTo = 'PluginAceadminpanel_' . basename($sFile, '.class.php');
            Engine::getInstance()->Plugin_Inherit($sFrom, $sTo, get_class($this));
            include_once($sFile);
        }
        include_once(Plugin::GetPath($this->sPlugin) . '/classes/actions/ActionAdminPlugin.class.php');
    }

}

if (!class_exists('ACE')) {
    include_once 'include/ACE.php';
}

// EOF
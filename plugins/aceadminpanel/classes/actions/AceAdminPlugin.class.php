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

abstract class AceAdminPlugin extends ActionPlugin
{
    protected $sPageRef = '';
    protected $oUserCurrent;

    public $sMenuItemSelect;
    public $sMenuSubItemSelect;
    public $sMenuNavItemSelect = '';

    protected $oAdminAction;
    protected $sPluginAddon;
    protected $sTemplateFile;
    protected $aBlocks = array();


    public function __construct($oEngin, $sAction)
    {
        parent::__construct($oEngin, $sAction);
        $this->Viewer_Assign('tpl_content', '');
        $this->Viewer_Assign('tpl_include', '');
        if ($this->User_IsAuthorization())
            $this->oUserCurrent = $this->User_GetUserCurrent();
    }

    public function SetPluginAddon($sPluginAddon)
    {
        $this->sPluginAddon = $sPluginAddon;
        $this->SetTemplateAction('admin');
    }

    public function AddBlock($sBlock, $aParams = array())
    {
        //$sTemplateFile = Plugin::GetTemplatePath($this->sPluginAddon) . 'actions/ActionAdmin/block.' . $sBlock . '.tpl';
        //$sTemplateFile = 'actions/ActionAdmin/block.' . $sBlock . '.tpl';
        //$this->aBlocks[] = $sTemplateFile;
        if (!$aParams AND $this->sPluginAddon) {
            $aParams = array('plugin' => $this->sPluginAddon);
        }
        $this->aBlocks[] = array('block' => $sBlock, 'params' => $aParams);
    }

    public function GetBlocks()
    {
        return $this->aBlocks;
    }

    public function Init()
    {
    }

    final function RegisterEvent()
    {
    }

    public function SetAdminAction(&$oAction)
    {
        $this->oAdminAction = $oAction;
        if (isset($_SERVER['HTTP_REFERER'])) {
            $this->sPageRef = $_SERVER['HTTP_REFERER'];
        }
    }

    public function Event()
    {
        return $this->Admin();
    }

    public function Admin()
    {

    }

    public function Done()
    {
        $this->oAdminAction->SetMenuNavItemSelect($this->sMenuNavItemSelect);
    }

    protected function SetTemplateAction($sTemplate)
    {
        if (!$this->sPluginAddon) $this->sPluginAddon = HelperPlugin::GetPluginName($this, true);

        $sTemplateFile = Plugin::GetTemplatePath($this->sPluginAddon) . 'actions/ActionAdmin/' . $sTemplate . '.tpl';
        if (file_exists($sTemplateFile))
            $this->Viewer_Assign('tpl_include', $sTemplateFile);
    }

    protected function SetTemplateInclude($sTemplate)
    {
        list($sPlugin, $sAction) = explode('_', get_class($this), 2);
        $sPath = HelperPlugin::GetPluginPath() . '/templates/skin/';
        if (is_dir($sPath . Config::Get('view.skin'))) {
            $sPath .= Config::Get('view.skin');
        } elseif (is_dir($sPath . 'admin_default')) {
            $sPath .= 'admin_default';
        } elseif (is_dir($sPath . 'default')) {
            $sPath .= 'default';
        } else {
            $sPath = '';
        }
        if ($sPath) {
            $sTemplate = $sPath . '/actions/' . $sAction . '/' . $sTemplate . '.tpl';
        } else {
            $sTemplate = Plugin::GetTemplatePath($this->sPluginAddon)
                         . 'actions/' . $sAction . '/' . $sTemplate . '.tpl';
        }
        $this->Viewer_Assign('tpl_include', $sTemplate);
    }

    public function PluginConfigSave($sPlugin = null)
    {
        if (!$this->sPluginAddon) $this->sPluginAddon = HelperPlugin::GetPluginName($this, true);
        if (!$sPlugin) $sPlugin = $this->sPluginAddon;

        $sFile = ACE::FilePath(Config::Get('sys.cache.dir') . 'adm.' . $sPlugin . '.cfg');
        if (@file_put_contents($sFile, serialize(Config::Get('plugin.' . $sPlugin)))) {
            $this->Message('notice', $this->Lang_Get('adm_saved_ok'));
        } else {
            $this->Message('error', $this->Lang_Get('adm_saved_err'));
        }
    }

    public function Message($sType, $sText)
    {
        $this->oAdminAction->Message($sType, $sText, null, true);
    }
}

// EOF
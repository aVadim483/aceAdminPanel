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

class ActionAdminPlugin extends ActionPlugin
{
    protected $sPageRef = '';
    protected $oUserCurrent;

    public $sMenuItemSelect;
    public $sMenuSubItemSelect;
    public $sMenuNavItemSelect = '';

    protected $oAdminAction;
    protected $sTemplateFile;
    protected $nParamsOffset = null;


    public function __construct($oEngin, $sAction)
    {
        parent::__construct($oEngin, $sAction);
        $this->Viewer_Assign('tpl_content', '');
        $this->Viewer_Assign('tpl_include', '');
        if ($this->User_IsAuthorization())
            $this->oUserCurrent = $this->User_GetUserCurrent();
        if (parent::GetParam(0) == 'ext') {
            $this->nParamsOffset = 2;
        } else {
            $this->nParamsOffset = 1;
        }
    }

    public function RegisterEvent()
    {
        // nothing
    }

    public function Init()
    {
    }

    public function Admin()
    {

    }

    public function Done()
    {
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
            $sTemplate = $sPath . '/actions/ActionAdmin/' . $sTemplate . '.tpl';
        } else {
            $sTemplate = Plugin::GetTemplatePath($this->sPluginAddon)
                . 'actions/' . $sAction . '/' . $sTemplate . '.tpl';
        }
        $sTemplate = ACE::LocalPath($sTemplate, ACE::GetPluginsDir());
        //var_dump($s);exit;
        $this->Viewer_Assign('include_tpl', $sTemplate);
    }

    /**
     * Получает список параметров из URL с нужным смещением
     *
     * @return array
     */
    public function GetParams()
    {
        $aParams = parent::GetParams();
        $aResult = array();
        for ($i = $this->nParamsOffset; $i < sizeof($aParams); $i++) {
            $aResult[] = $aParams[$i];
        }
        return $aResult;
    }

    /**
     * Получает параметр из URL по его номеру, если его нет то null
     *
     * @param int $iOffset    Номер параметра, начинается с нуля
     * @return mixed
     */
    /**
     * @param   int     $nOffset    - Номер параметра, начинается с нуля
     * @param   null    $xDefault
     * @return  mixed|null
     */
    public function GetParam($nOffset, $xDefault = null)
    {
        $aParams = $this->GetParams();
        $nOffset = (int)$nOffset;
        return isset($aParams[$nOffset]) ? $aParams[$nOffset] : $xDefault;
    }


}

// EOF
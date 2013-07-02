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

class PluginAceadminpanel_ModuleNotify extends PluginAceadminpanel_Inherit_ModuleNotify
{
    private $sPlugin = 'aceadminpanel';

    public function GetTemplatePath($sName, $sPluginName = null)
    {
        if (!$sPluginName) {
            $sLangDir = 'notify/' . $this->Lang_GetLang();
            if (Config::Get($this->sPlugin . '.saved.path.static.skin')) {
                $sDir = ACE::Url2Path(rtrim(Config::Get($this->sPlugin . '.saved.path.static.skin'), '/') . '/' . $sLangDir);
                if (is_dir($sDir)) {
                    return $sDir . '/' . $sName;
                }
            }
        }
        return parent::GetTemplatePath($sName, $sPluginName);
    }

}

// EOF
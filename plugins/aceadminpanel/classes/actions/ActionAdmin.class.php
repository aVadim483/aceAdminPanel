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

require_once 'AceAdminPlugin.class.php';

class PluginAceadminpanel_ActionAdmin extends ActionAdmin
{
    private $sPlugin = 'aceadminpanel';

    public function Init()
    {
        if (($result = parent::Init())) {
            return $result;
        }
        $this->Lang_AddLangJs(
            array(
                $this->sPlugin . '_adm_select_file',
            )
        );
    }

}

// EOF

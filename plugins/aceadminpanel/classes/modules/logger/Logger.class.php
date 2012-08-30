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
 * @File Name: Logger.class.php
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *----------------------------------------------------------------------------
 */

class PluginAceadminpanel_ModuleLogger extends ModuleLogger
{
    protected function log($msg, $sLevel)
    {
        $msg = trim(str_replace("\n", '', $msg));
        $msg = preg_replace('/\s+/', ' ', $msg);
        return parent::log($msg, $sLevel);
    }
}
// EOF
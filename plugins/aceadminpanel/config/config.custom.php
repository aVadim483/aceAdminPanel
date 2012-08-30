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
 * @File Name: config.custom.php
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *----------------------------------------------------------------------------
 */

if (!class_exists('Config')) die('Hacking attempt!');

define('CUSTOM_CFG', 'adm.all.cfg');

$sDataFile = Config::Get('sys.cache.dir') . CUSTOM_CFG;
if (file_exists($sDataFile)) {
    $data = @file_get_contents($sDataFile);
    if ($data AND is_array($aConfigSet = unserialize($data))) {
        foreach ($aConfigSet as $aConfigValue) {
            if (($n = strpos($aConfigValue['key'], '.', 8))) {
                $key = substr($aConfigValue['key'], $n + 1);
                $val = @unserialize($aConfigValue['val']);
                if (($val !== false) OR ($val === false AND $aConfigValue['val'] === serialize(false))) {
                    if (($key != 'view.skin')
                        OR ($key == 'view.skin' AND is_dir(Config::Get('path.root.server') . '/templates/skin/' . $val))
                    ) {
                        Config::Set($key, $val);
                    }
                }
            }
        }
    }
}

return array();

// EOF
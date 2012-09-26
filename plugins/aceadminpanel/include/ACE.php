<?php
/*---------------------------------------------------------------------------
 * @Plugin Name: aceAdminPanel
 * @Plugin Id: aceadminpanel
 * @Plugin URI: 
 * @Description: Advanced Administrator's Panel for LiveStreet/ACE
 * @Version: 1.6.300
 * @Author: Vadim Shemarov (aka aVadim)
 * @Author URI: 
 * @LiveStreet Version: 1.0.0
 * @File Name:
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *----------------------------------------------------------------------------
 */

include_once 'ext/ACE.Functions.php';
include_once 'ext/ACE.Config.php';

class ACE extends ACE_Func
{
    static protected $bInit = false;

    static function Init()
    {
        if (!self::$bInit) {
            ACE_Config::Init();
            self::$bInit = true;
        }
    }
}

ACE::Init();

// EOF
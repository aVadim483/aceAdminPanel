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

include_once 'ext/ACE.Functions.php';
include_once 'ext/ACE.Config.php';

class ACE extends ACE_Func
{
    static protected $bInit = false;

    public static function GetParentInherit($sInheritClass)
    {
        $sParentClass = Engine::getInstance()->Plugin_GetParentInherit($sInheritClass);
        /*
        if (strpos($sInheritClass, 'Plugin') !== 0 AND strpos($sParentClass, 'Plugin') === 0) {
            // движок ошибочно выдает имя класса плагина
            $aInfo = Engine::GetClassInfo($sInheritClass, Engine::CI_CLASSPATH);
            if (isset($aInfo[Engine::CI_CLASSPATH])) {
                ACE::FileInclude($aInfo[Engine::CI_CLASSPATH]);
                return $sInheritClass;
            } else {
                return 'LsObject';
            }
        }
        */
        return $sParentClass;
    }

    public static function autoload($sClassName)
    {
        if (!class_exists('Engine')) return;

        $aInfo = Engine::GetClassInfo(
            $sClassName,
            Engine::CI_CLASSPATH | Engine::CI_INHERIT
        );
        if ($aInfo[Engine::CI_INHERIT]) {
            $sInheritClass = $aInfo[Engine::CI_INHERIT];
            $sParentClass = self::GetParentInherit($sInheritClass);
            if (class_alias($sParentClass, $sClassName)) {
                return true;
            }
        }
    }

    static function Init()
    {
        if (!self::$bInit) {
            ACE_Config::Init();
            spl_autoload_register(array('ACE','autoload'));
            self::$bInit = true;
        }
    }
}

ACE::Init();

// EOF
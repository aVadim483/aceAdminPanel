<?php
/*---------------------------------------------------------------------------
 * @Plugin Name: aceAdminPanel
 * @Plugin Id: aceadminpanel
 * @Plugin URI: 
 * @Description: Advanced Administrator's Panel for LiveStreet/ACE
 * @Version: 2.0.0
 * @Author: Vadim Shemarov (aka aVadim)
 * @Author URI: 
 * @LiveStreet Version: 1.0.1
 * @File Name: Logger.class.php
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *----------------------------------------------------------------------------
 */

class PluginAceadminpanel_ModuleLogger extends PluginAceadminpanel_Inherit_ModuleLogger
{
    protected $bLogEnable = true;

    public function Init()
    {
        parent::Init();
        $this->sPathLogs = Config::Get('sys.logs.path') . '/';

        // Проверяем, можем ли писать в лог-файл
        $sFile = ACE::FilePath($this->sPathLogs . $this->sFileName);
        $fp = @fopen($sFile, 'a');
        if (!$fp) {
            $this->bLogEnable = false;
        } else {
            fclose($fp);
        }
    }

    protected function write($msg)
    {
        // Если в лог-файл писать не можем, то даже и не пытаемся
        if (!$this->bLogEnable) return false;
        return parent::write($msg);
    }

    /*
    protected function log($msg, $sLevel)
    {
        $msg = trim(str_replace("\n", '', $msg));
        $msg = preg_replace('/\s+/', ' ', $msg);
        return parent::log($msg, $sLevel);
    }
    */
}
// EOF
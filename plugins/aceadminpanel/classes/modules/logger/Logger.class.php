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

class PluginAceadminpanel_ModuleLogger extends PluginAceadminpanel_Inherit_ModuleLogger
{
    protected $bCheckLogFiles = false;
    protected $oUserCurrent;
    protected $aCheckedFiles = array();

    public function Init()
    {
        parent::Init();
        if (Config::Get('sys.logs.path'))
            $this->sPathLogs = Config::Get('sys.logs.path') . '/';
    }

    /**
     * Задаем признак проверки
     * Это можно делать только после инициализации всех плагинов
     *
     * @param   bool    $bVal
     */
    public function CheckLogFiles($bVal)
    {
        $this->bCheckLogFiles = $bVal;
    }

    /**
     * Проверка, можем ли писать в текущий лог-файл
     */
    protected function _checkLogFile()
    {
        if ($this->bCheckLogFiles AND $this->sFileName AND $this->sFileName != '-') {
            $sFile = ACE::FilePath($this->sPathLogs . $this->sFileName);
            if (!isset($this->aCheckedFiles[$sFile])) {
                // Проверяем, можем ли писать в лог-файл
                if ($fp = @fopen($sFile, 'a')) {
                    fclose($fp);
                    $this->aCheckedFiles[$sFile] = true;
                } else {
                    if (($this->oUserCurrent OR ($this->oUserCurrent = $this->User_GetUserCurrent())) AND $this->oUserCurrent->isAdministrator()) {
                        $this->Message_AddError('Cannot write to log file "' . $sFile . '"', $this->Lang_Get('error'));
                        //$this->Message_AddError('Cannot write to log file "' . $sFile . '"', $this->Lang_Get('error'), true);
                    }
                    $this->aCheckedFiles[$sFile] = false;
                }
            }
            return $this->aCheckedFiles[$sFile];
        }
        return true;
    }

    protected function write($msg)
    {
        // Если в лог-файл писать не можем, то даже и не пытаемся
        if ($this->_checkLogFile()) {
            return parent::write($msg);
        }
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
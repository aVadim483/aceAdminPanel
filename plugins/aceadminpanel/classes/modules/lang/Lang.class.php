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

/**
 * Расширенный модуль поддержки языковых файлов
 *
 */
class PluginAceadminpanel_ModuleLang extends PluginAceadminpanel_Inherit_ModuleLang
{
    protected $sPlugin = 'aceadminpanel';

    protected $sDefaultLang = '';
    protected $aLangDefine = array('russian');

    /**
     * Инициализация модуля
     *
     */
    public function Init()
    {
        $this->sDefaultLang = Config::Get('lang.default');
        if (Config::Get('plugin.aceadminpanel.lang_define') AND ($sLangs = str_replace(' ', '', Config::Get('plugin.aceadminpanel.lang_define')))) {
            $this->aLangDefine = explode(',', $sLangs);
        }

        $this->SetCurrentLang($this->Session_Get('language'));
        parent::Init();
    }

    /**
     * Проверяет язык на соответствие заданному набору языков
     *
     * @param $sLang
     * @return
     */
    protected function CheckLang($sLang)
    {
        if (in_array($sLang, $this->aLangDefine)) return $sLang;
        else null;
    }

    protected function SetCurrentLang($sLang)
    {
        $sLang = $this->CheckLang($sLang);
        if ($sLang) $this->sCurrentLang = $sLang;
    }

    /**
     * Инициализирует языковой файл
     *
     * @param null $sLanguage
     * @return void
     */
    protected function InitLang($sLanguage = null)
    {
        $this->aLangMsg = array();
        if (!$sLanguage) $sLanguage = $this->sCurrentLang;

        // * Если используется кеширование через memcaсhed, то сохраняем данные языкового файла в кеш
        /*
        if (Config::Get('sys.cache.use') AND Config::Get('sys.cache.type') == 'memory') {
            if (false === ($this->aLangMsg = $this->Cache_Get("lang_" . $sLanguage))) {
                $this->aLangMsg = array();
                $this->LoadLangFiles($sLanguage);
                $this->Cache_Set($this->aLangMsg, "lang_" . $sLanguage, array('adm_lang'), 60 * 60);
            }
        } else {
            $this->LoadLangFiles($sLanguage);
        }
        */

        parent::InitLang($sLanguage);

        // * Загружаем в шаблон
        //$this->Viewer_Assign('aLang', $this->aLangMsg);
        $this->Viewer_Assign('oLang', $this);
    }

    protected function LoadLangFiles($sLanguage = null)
    {
        if (!$sLanguage) $sLanguage = $this->sCurrentLang;
        parent::LoadLangFiles($this->sDefaultLang);
        if ($this->sDefaultLang != $sLanguage) {
            $aMsgDefault = $this->aLangMsg;
            parent::LoadLangFiles($sLanguage);
            $this->aLangMsg = array_merge($aMsgDefault, $this->aLangMsg);
        }
    }

    protected function _subst($sText, $aReplace = array(), $bDelete = true)
    {
        if (is_array($aReplace) AND count($aReplace) AND is_string($sText)) {
            foreach ($aReplace as $sFrom => $sTo) {
                $aReplacePairs["%%{$sFrom}%%"] = $sTo;
            }
            $sText = strtr($sText, $aReplacePairs);
        }

        if (Config::Get('module.lang.delete_undefined') AND $bDelete AND is_string($sText)) {
            $sText = preg_replace("/\%\%[\S]+\%\%/U", '', $sText);
        }
        return $sText;
    }

    /**
     * Получает текстовку по её имени
     *
     * @param   string  $sName
     * @param   array   $aReplace
     * @param   bool    $bDelete    Удалять или нет параметры, которые не были заменены
     * @return  string
     */
    public function Get($sName, $aReplace = array(), $bDelete = true)
    {
        if (is_string($aReplace) AND strpos($aReplace, '=>')) {
            list($sKey, $sVal) = explode('=>', $aReplace);
            $aReplace = array($sKey => $sVal);
        }
        $sResult = parent::Get($sName, $aReplace, $bDelete);
        if ($sResult == 'NOT_FOUND_LANG_TEXT') {
            if (isset($this->aLangMsg[$sName])) {
                return $this->aLangMsg[$sName];
            } elseif (isset($this->aLangMsg['plugin']) AND is_array($this->aLangMsg['plugin'])) {
                foreach ($this->aLangMsg['plugin'] as $sPluginName => $aPluginMessages) {
                    if (isset($aPluginMessages[$sName])) {
                        return $this->_subst($aPluginMessages[$sName], $aReplace, $bDelete);
                    }
                }
            }
            if (strpos($sName, '_')) {
                list($sPlugin, $sKey) = explode('_', $sName, 2);
                if (isset($this->aLangMsg['plugin'][$sPlugin][$sKey]))
                    return $this->_subst($this->aLangMsg['plugin'][$sPlugin][$sKey], $aReplace, $bDelete);
            }
            $sResult = strtoupper($sName);
        }
        return $sResult;
    }

    public function __get($sName)
    {
        if (substr($sName, 0, 1) == '_') $sKey = substr($sName, 1);
        else $sKey = $sName;
        return $this->Get($sKey);
        /*
        if (isset($this->aLangMsg[$sName])) {
            return $this->aLangMsg[$sName];
        } elseif (isset($this->aLangMsg['plugin']) AND is_array($this->aLangMsg['plugin'])) {
            foreach ($this->aLangMsg['plugin'] as $sPluginName => $aPluginMessages) {
                if (isset($aPluginMessages[$sName])) {
                    return $aPluginMessages[$sName];
                }
            }
        }
        return strtoupper($sName);
        */
    }

    public function ResetLang()
    {
        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('adm_lang'));
        $this->InitLang();
    }

    public function Dictionary($sLanguage = null)
    {
        if ($sLanguage == null) {
            if (isset($_REQUEST['language'])) {
                $sLanguage = $this->CheckLang($_REQUEST['language']);
            } elseif (isset($_REQUEST['LANG_CURRENT'])) {
                $sLanguage = $this->CheckLang($_REQUEST['LANG_CURRENT']);
            }
            if (Config::Get('plugin.aceadminpanel.lang_save_period')) {
                @setcookie('LANG_CURRENT', $sLanguage, time() + 60 * 60 * 24 * intVal(Config::Get('plugin.aceadminpanel.lang_save_period')), Config::Get('sys.cookie.path'), Config::Get('sys.cookie.host'));
            }
        }
        if ($sLanguage AND $sLanguage !== $this->sCurrentLang) $this->InitLang($sLanguage);
        return $this;
    }

    public function ParseText($sText, $aData = Array())
    {
        if (!isset($aData['date'])) {
            $aData['date'] = time();
        } elseif (!is_numeric($aData['date'])) {
            $aData['date'] = strtotime($aData['date']);
        }
        if (!isset($aData['user'])) $aData['user'] = '';

        $sText = preg_replace('/\[@user\]/', $aData['user'], $sText);
        $sText = preg_replace('/\[@date\]/', date('Y-m-d H:i:s', $aData['date']), $sText);
        if (preg_match('/\[@date=([^\]]*)\]/', $sText, $match)) {
            $date = date($match[1], $aData['date']);
            $sText = preg_replace('/\[@date=([^\]]*)\]/', $date, $sText);
        }
        return ($sText);
    }

    public function Text($sMsgKey, $aData = Array())
    {
        $sText = $this->Get($sMsgKey);
        if (strpos($sText, '[@') === false) {
            return $sText;
        } else {
            return $this->ParseText($sText, $aData);
        }
    }

    public function LoadFile($sFileName)
    {
        if (strpos($sFileName, '%%language%%') !== false) {
            $this->LoadFile(ACE::FilePath(str_replace('%%language%%', $this->GetLangDefault(), $sFileName)));
            $this->LoadFile(ACE::FilePath(str_replace('%%language%%', $this->GetLang(), $sFileName)));
        } else {
            if (is_file($sFileName)) {
                $aLangMessages = (array)include($sFileName);
                $this->aLangMsg = array_merge($this->aLangMsg, $aLangMessages);
            }
        }
    }

}
// EOF
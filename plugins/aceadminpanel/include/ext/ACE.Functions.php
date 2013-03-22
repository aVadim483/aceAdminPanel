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

class ACE_Functions
{
    static protected $aBackward = null;

    static function HeaderLocation($sLocation)
    {
        Engine::getInstance()->Shutdown();

        if (!headers_sent()) {
            //        func_header_location($sLocation);
            //        header("HTTP/1.1 301 Moved Permanently");
            header('HTTP/1.1 303 See Other');
            header('Location: ' . $sLocation, true);
            header('Content-type: text/html; charset=UTF-8');
        }
        echo '<!DOCTYPE HTML">
<html>
<head>
<script type="text/javascript">
<!--
location.replace("' . $sLocation . '");
//-->
</script>
<noscript>
<meta http-equiv="Refresh" content="0; URL=' . $sLocation . '">
</noscript>
</head>
<body>
Redirect to <a href="' . $sLocation . '">' . $sLocation . '</a>
</body>
</html>';
        exit;
    }

    /**
     * Получает требуемый элемент из стека вызова
     * Если $nLevel == -1, то ищется первое совпадение
     *
     * @param int $nLevel
     * @param string $sElement
     * @param string $sMatch
     * @return null
     */
    static function Backtrace($nLevel = 0, $sElement = '', $sMatch = '')
    {
        $aBacktrace = debug_backtrace();
        if ($nLevel < 0 AND $sElement) {
            foreach ($aBacktrace as $aCaller) {
                if (isset($aCaller[$sElement])) {
                    if (($sMatch == '') OR ($sMatch > '' AND 0 === strpos($aCaller[$sElement], $sMatch)))
                        return $aCaller[$sElement];
                }
            }
            return null;
        } else {
            if (!isset($aBacktrace[++$nLevel])) return null;
            if ($sElement) {
                if (isset($aBacktrace[$nLevel][$sElement])) {
                    return $aBacktrace[$nLevel][$sElement];
                } else {
                    return null;
                }
            } else {
                return $aBacktrace[$nLevel];
            }
        }
    }

    /**
     * Возвращает нормализованый путь к корневой папке
     *
     * @return string
     */
    static function GetRootDir()
    {
        return self::FilePath(Config::Get('path.root.server'));
    }

    /**
     * Возвращает путь к папке с плагинами (если не задано имя плагина)
     * или путь к папке конкретного плагина (если задано имя плагина)
     *
     * @param   null|string $sPlugin
     *
     * @return  string
     */
    static function GetPluginsDir($sPlugin = null)
    {
        if ($sPlugin)
            return self::GetPluginDir($sPlugin);
        else
            return self::FilePath(self::GetRootDir() . '/plugins/');
    }

    /**
     * Возвращает путь к папке заданного плагина
     *
     * @param   null|string $sPlugin
     *
     * @return  string
     */
    static function GetPluginDir($sPlugin)
    {
        return self::FilePath(self::GetPluginsDir() . strtolower($sPlugin) . '/');
    }

    static function GetRootUrl()
    {
        return self::FilePath(Config::Get('path.root.web'));
    }

    /**
     * Преобразование пути на сервере в URL
     *
     * @param  string $sPath
     *
     * @return string
     */
    static function Dir2Url($sPath)
    {
        return ACE::FilePath(str_replace(
            str_replace(DIRECTORY_SEPARATOR, '/', self::GetRootDir()),
            self::GetRootUrl(),
            str_replace(DIRECTORY_SEPARATOR, '/', $sPath)
        ), '/');
    }

    /**
     * Алиас функции Dir2Url($sPath)
     *
     * @param $sPath
     * @return string
     */
    static function Path2Url($sPath)
    {
        return self::Dir2Url($sPath);
    }

    /**
     * Преобразование URL в путь на сервере
     *
     * @param   string          $sUrl
     * @param   string|null     $sSeparator
     *
     * @return  string
     */
    static function Url2Dir($sUrl, $sSeparator = null)
    {
        // * Delete www from path
        $sUrl = str_replace('//www.', '//', $sUrl);
        $sPathWeb = str_replace('//www.', '//', self::GetRootUrl());
        // * do replace
        $sUrl = str_replace($sPathWeb, self::GetRootDir(), $sUrl);
        return ACE::FilePath($sUrl, $sSeparator);

    }

    static function Url2Path($sUrl, $sSeparator = null)
    {
        return self::Url2Dir($sUrl, $sSeparator);
    }

    /**
     * Нормализует путь к файлу
     *
     * @param   string|array   $sPath
     * @param   string|null    $sSeparator
     *
     * @return  string
     */
    static function FilePath($sPath, $sSeparator = null)
    {
        if (!$sSeparator) $sSeparator = DIRECTORY_SEPARATOR;
        if (is_array($sPath)) {
            $aResult = array();
            foreach ($sPath as $s) $aResult[] = self::FilePath($s, $sSeparator);
            return $aResult;
        }

        if (preg_match('|^([a-z]+://)(.*)|i', $sPath, $aMatches)) {
            $sPrefix = $aMatches[1];
            $sPath = $aMatches[2];
        } elseif (preg_match('|^([a-z])(\:[\\\\/].*)$|u', $sPath, $aMatches)) {
            $sPrefix = '';
            $sPath = strtoupper($aMatches[1]) . $aMatches[2];
        } else {
            $sPrefix = '';
        }
        if ($sSeparator == '/') {
            $sPath = str_replace('\\', $sSeparator, $sPath);
        } elseif ($sSeparator == '\\') {
            $sPath = str_replace('/', $sSeparator, $sPath);
        } else {
            $sPath = str_replace(array('/', '\\'), $sSeparator, $sPath);
        }

        while (strpos($sPath, $sSeparator . $sSeparator))
            $sPath = str_replace($sSeparator . $sSeparator, $sSeparator, $sPath);

        return $sPrefix . $sPath;
    }

    /**
     * Из абсолютного пути выделяет относительный (локальный) относительно рута
     *
     * @param $sPath
     * @param $sRoot
     * @return string
     */
    static function LocalPath($sPath, $sRoot)
    {
        if ($sPath AND $sRoot) {
            $sPath = ACE::FilePath($sPath);
            $sRoot = ACE::FilePath($sRoot);
            if (strpos($sPath, $sRoot) === 0) {
                return substr($sPath, strlen($sRoot));
            }
        }
        return false;
    }

    /**
     * Из абсолютного пути выделяет локальный относительно корневой папки проекта
     *
     * @param $sPath
     * @return string
     */
    static function LocalDir($sPath)
    {
        return ACE::LocalPath($sPath, self::GetRootDir());
    }

    /**
     * Из абсолютного URL выделяет локальный относительно корневого URL проекта
     *
     * @param $sPath
     * @return string
     */
    static function LocalUrl($sPath)
    {
        return ACE::LocalPath($sPath, self::GetRootUrl());
    }

    /**
     * Является ли путь локальным
     *
     * @param $sPath
     * @return bool
     */
    static function IsLocalDir($sPath)
    {
        return (bool)self::LocalDir($sPath);
    }

    /**
     * Является ли URL локальным
     *
     * @param $sPath
     * @return bool
     */
    static function IsLocalUrl($sPath)
    {
        return (bool)self::LocalUrl($sPath);
    }

    static function CurrentRoute()
    {
        $sCurentRoute = Router::GetAction() . '/';
        if (Router::GetActionEvent()) $sCurentRoute .= Router::GetActionEvent() . '/';
        if (Router::GetParams()) $sCurentRoute .= implode('/', Router::GetParams()) . '/';
        return $sCurentRoute;
    }

    /**
     * Соответствует ли проверяемый путь одному из заданных путей
     *
     * @param   string          $sNeedle - проверяемый путь
     * @param   string|array    $aPaths  - путь (или массив путей), на соответствие которым идет проверка
     * @return  string|bool
     */
    static function InPath($sNeedle, $aPaths)
    {
        if (!is_array($aPaths)) $aPaths = array((string)$aPaths);
        $sNeedle = self::FilePath($sNeedle, '/');
        $aCheckPaths = self::FilePath($aPaths, '/');
        foreach ($aCheckPaths as $n => $sPath) {
            if ($sPath == '*') {
                return $aPaths[$n];
            } elseif (substr($sPath, -2) == '/*') {
                $sPath = substr($sPath, 0, strlen($sPath) - 2);
                if (strpos($sNeedle, $sPath) === 0) return $aPaths[$n];
            } else {
                if (substr($sPath, -1) != '/') $sPath .= '/';
                if ($sNeedle == $sPath) return $aPaths[$n];
            }
        }
        return false;
    }

    /**
     * Разбирает полный путь файла
     * В отличии от стандартной функции выделяет GET-параметры и очищает от них имя и расширение файла
     *
     * @param $sPath
     * @return array
     */
    static function PathInfo($sPath)
    {
        $aResult = array_merge(
            array(
                'dirname' => '',
                'basename' => '',
                'extension' => '',
                'filename' => '',
                'params' => '',
            ),
            pathinfo(self::FilePath($sPath))
        );
        $n = strpos($aResult['extension'], '?');
        if ($n !== false) {
            $aResult['params'] = substr($aResult['extension'], $n + 1);
            $aResult['extension'] = substr($aResult['extension'], 0, $n);
            $n = strpos($aResult['basename'], '?');
            $aResult['basename'] = substr($aResult['basename'], 0, $n);
        }
        return $aResult;
    }

    static function FileExtension($sPath)
    {
        $aInfo = self::PathInfo($sPath);
        return $aInfo['extension'];
    }

    static protected function _calledFilePath()
    {
        $aStack = debug_backtrace();
        foreach ($aStack as $aCaller) {
            if (isset($aCaller['file']) AND $aCaller['file'] != __FILE__) {
                return dirname($aCaller['file']) . '/';
            }
        }
        return '';
    }

    static function FullDir($sFile)
    {
        if (substr(strtolower($sFile), 0, 7) == 'plugin:') {
            $aParts = explode(':', substr($sFile, 7));
            if (sizeof($aParts) == 2 AND in_array($aParts[0], Engine::getInstance()->Plugin_GetActivePlugins())) {
                $sFile = ACE::FilePath(ACE::GetPluginDir($aParts[0]) . '/' . $aParts[1]);
            }
        }
        if (self::IsLocalDir($sFile)) {
            return self::FilePath($sFile);
        }
        return self::FilePath(self::_calledFilePath() . $sFile);
    }

    static function FileExists($sFile)
    {
        return is_file($sFile);
    }

    /**
     * Подключение файла
     *
     * @param   string  $sFile
     * @param   bool    $bOnce
     * @return  array|mixed|null
     */
    static function FileInclude($sFile, $bOnce = true)
    {
        $config = array();
        $sFile = self::FullDir($sFile);
        if ($bOnce) {
            $xResult = include_once($sFile);
        } else {
            $xResult = include($sFile);
        }
        if ($config AND is_array($config)) {
            $xResult = $config;
        }
        return $xResult;
    }

    /**
     * Подключение файла, если он существует
     *
     * @param   string  $sFile
     * @param   bool    $bOnce
     * @return  array|mixed|null
     */
    static function FileIncludeIfExists($sFile, $bOnce = true)
    {
        $xResult = null;
        $sFile = self::FullDir($sFile);
        if (ACE::FileExists($sFile)) $xResult = self::FileInclude($sFile, $bOnce);
        return $xResult;
    }

    /**
     * Сравнение одного пути (имени файла) с другим
     *
     * @param   string  $sPath1     - сравниваемый путь (имя файла)
     * @param   string  $sPath2     - с чем сравнивается
     * @param   bool    $bByEnd     - сравнивать конец пути (имени файла)
     *
     * @return  bool
     */
    static function PathCompare($sPath1, $sPath2, $bByEnd = false)
    {
        if (!$bByEnd) {
            return (bool)ACE::LocalPath($sPath1, $sPath2);
        }
        $sPath1 = ACE::FilePath($sPath1);
        $sPath2 = ACE::FilePath($sPath2);
        return substr($sPath2, -strlen($sPath1)) == $sPath1;
    }

    /**
     * Преобразует строку в массив
     *
     * @param   string|array    $sStr
     * @param   string $sChr
     * @param   bool $bSkipEmpty
     *
     * @return  array
     */
    static function Str2Array($sStr, $sChr = ',', $bSkipEmpty = false)
    {
        if (is_array($sStr)) $arr = $sStr;
        else $arr = explode($sChr, $sStr);

        $aResult = array();
        foreach ($arr as $str) {
            if ($str OR !$bSkipEmpty)
                $aResult[] = trim($str);
        }
        return $aResult;
    }

    /**
     * Рекурсивное создание папки (если ее нет)
     *
     * @static
     *
     * @param $sNewDir
     * @param int $nMode
     * @param bool $bQuiet
     *
     * @return bool|string
     */
    static function MakeDir($sNewDir, $nMode = 0755, $bQuiet = false)
    {
        $sBasePath = ACE::FilePath(self::GetRootDir() . '/');
        if (substr($sNewDir, 0, 2) == '//') {
            $sNewDir = substr($sNewDir, 2);
        } else {
            $sNewDir = ACE::LocalPath($sNewDir, $sBasePath);
        }
        $sTempPath = $sBasePath;
        $aNewDir = explode('/', $sNewDir);
        foreach ($aNewDir as $sDir) {
            if ($sDir != '.' AND $sDir != '') {
                $sCheckPath = $sTempPath . $sDir . '/';
                if (!is_dir($sCheckPath)) {
                    if ($bQuiet) {
                        $bResult = @mkdir($sCheckPath, $nMode, true);
                    } else {
                        $bResult = mkdir($sCheckPath, $nMode, true);
                    }
                    if ($bResult) {
                        //;
                    } else {
                        //die('Cannot make dir "' . $sCheckPath . '"');
                        return false;
                    }
                    if ($bQuiet)
                        @chmod($sCheckPath, $nMode);
                    else
                        chmod($sCheckPath, $nMode);
                }
                $sTempPath = $sCheckPath;
            }
        }
        return $sTempPath;
    }

    /**
     * Рекурсивное удаление папки
     *
     * @static
     * @param $sDir
     * @return bool
     */
    static function RemoveDir($sDir)
    {
        if (!is_dir($sDir)) return true;
        $sPath = rtrim($sDir, '/') . '/';

        if (($aFiles = glob($sPath . '*', GLOB_MARK))) {
            foreach ($aFiles as $sFile) {
                if (is_dir($sFile)) {
                    ACE::RemoveDir($sFile);
                } else {
                    @unlink($sFile);
                }
            }
        }
        if (is_dir($sPath)) @rmdir($sPath);
    }

    static function ClearDir($sDir, $bRecursive = true)
    {
        $result = true;
        $sDir = str_replace('\\', '/', $sDir);
        if (substr($sDir, -1) != '/') $sDir .= '/';
        if (is_dir($sDir) AND ($files = glob($sDir . '*'))) {
            foreach ($files as $file) {
                // delete all files except started with 'dot'
                if (substr(basename($file), 0, 1) != '.') {
                    if (is_dir($file) AND $bRecursive) $result = $result AND ACE::ClearDir($file, $bRecursive);
                    else $result = $result AND @unlink($file);
                }
            }
        }
        return $result;
    }

    /**
     * Чистит временные файлы Smarty - кеш и скомпилированные файлы
     *
     * @return  bool
     */
    static function ClearSmartyCache()
    {
        $result = ACE::ClearDir(Config::Get('path.smarty.compiled'));
        $result = $result AND ACE::ClearDir(Config::Get('path.smarty.cache'));
        return $result;
    }

    static function ClearHeadfilesCache()
    {
        $sCacheDir = Config::Get('path.smarty.cache') . "/" . Config::Get('view.skin');
        $result = ACE::ClearDir($sCacheDir);
        return $result;
    }

    static function ClearAllCache()
    {
        Engine::getInstance()->Cache_Clean();
        $result = ACE::ClearSmartyCache() AND ACE::ClearHeadfilesCache();
        setcookie('ls_photoset_target_tmp', null);
        return $result;
    }

    static function GetAllUserIp()
    {
        $aIp[] = $_SERVER['REMOTE_ADDR'];
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) $aIp[] = $_SERVER['HTTP_X_FORWARDED_FOR'];
        if (isset($_SERVER['HTTP_X_REAL_IP'])) $aIp[] = $_SERVER['HTTP_X_REAL_IP'];
        if (isset($_SERVER['HTTP_VIA'])) {
            if (preg_match('/\d+\.\d+\.\d+\.\d+/', $_SERVER['HTTP_VIA'], $m)) $aIp[] = $m[0];
        }
        return $aIp;
    }

    static function GetMajorVersion($sVersion)
    {
        return str_replace(',', '.', '' . floatVal($sVersion));
    }

    static function MSIE6()
    {
        if (isset($_SERVER['HTTP_USER_AGENT']) AND strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6.0'))
            return true;
        else
            return false;
    }

    /**
     * Определение (и сохранение в куках на год) уникального ID посетителя сайта
     *
     * @return string
     */
    static function GetVisitorId()
    {
        if (!defined('ADM_VISITOR_ID')) {
            if (!isset($_COOKIE['visitor_id'])) {
                if (headers_sent()) {
                    if (!isset($_SERVER['HTTP_USER_AGENT'])) {
                        // это точно не браузер
                        $sVisitorId = '';
                    } else {
                        $sUserAgent = @$_SERVER['HTTP_USER_AGENT'];
                        $sVisitorId = md5($sUserAgent . '::' . serialize(ACE::GetAllUserIp()));
                    }
                } else {
                    $sVisitorId = md5(uniqid(time()));
                }
            } else {
                $sVisitorId = $_COOKIE['visitor_id'];
            }
            if (!headers_sent()) {
                setcookie('visitor_id', $sVisitorId, time() + 60 * 60 * 24 * 365, Config::Get('sys.cookie.path'), Config::Get('sys.cookie.host'));
            }
            define('ADM_VISITOR_ID', $sVisitorId);
        }
        return ADM_VISITOR_ID;
    }

    static function MemSizeFormat($n)
    {
        $unit = array('B', 'K', 'M', 'G', 'TB', 'PB');
        $c = 0;
        while ($n >= 1024) {
            $c++;
            $n = $n / 1024;
        }
        return number_format($n, ($c ? 3 : 0), '.', '\'') . '&nbsp;' . $unit[$c];
    }

    /**
     * Нормализует ассоциативные массивы, т.е. приводит массивы вида {'aaa', 'bbb'=>'ccc'}
     * к виду {'aaa'=> NULL, 'bbb'=>'ccc'}
     *
     * @param  $aArray
     *
     * @return array
     */
    static function ArrayAssoc($aArray)
    {
        if (!is_array($aArray)) {
            $aArray = array($aArray);
        }
        $result = array();
        foreach ($aArray as $key => $val) {
            if (is_numeric($key) AND !is_array($val) AND $val) {
                $result[(string)$val] = NULL;
            } else {
                $result[$key] = $val;
            }
        }
        return $result;
    }

    /**
     * Transform from CamelCase to under_score
     *
     * @param  string $sStr
     *
     * @return string
     */
    static function StrUnderScore($sStr)
    {
        $sStr[0] = strtolower($sStr[0]);
        $func = create_function('$c', 'return "_" . strtolower($c[1]);');
        return preg_replace_callback('/([A-Z])/', $func, $sStr);
    }

    /**
     * Transform from under_score to CamelCase
     *
     * @param  $sStr
     * @param bool $bCapitaliseFirstChar
     * @return string
     */
    static function StrCamelCase($sStr, $bCapitaliseFirstChar = false)
    {
        if ($bCapitaliseFirstChar) {
            $sStr[0] = strtoupper($sStr[0]);
        }
        $func = create_function('$c', 'return strtoupper($c[1]);');
        return preg_replace_callback('/_([a-z])/', $func, $sStr);
    }

    static function SkinExists($sSkin)
    {
        return true;
    }

    static function AutoLoadRegister($xFunc)
    {
        return HelperPlugin::AutoLoadRegister($xFunc);
    }

    /**
     * Восстановление сохраненного скина, чтобы сторонние плагины могли создать свою собственную админику
     *
     * @param $aEvents
     */
    static function RestoreAdminSkin($aEvents)
    {
        if (Config::Get('saved')) {
            if ($aEvents AND !is_array($aEvents))
                $aEvents = array_map('trim', explode(',', $aEvents));
            else
                $aEvents = (array)$aEvents;
            if (Router::GetAction() == 'admin' AND (!$aEvents OR in_array(Router::GetActionEvent(), $aEvents))) {
                if (Config::Get('saved.view.skin'))
                    Config::Set('view.skin', Config::Get('saved.view.skin'));
                if (Config::Get('saved.path.smarty.template'))
                    Config::Set('path.smarty.template', Config::Get('saved.path.smarty.template'));
                if (Config::Get('saved.path.static.skin'))
                    Config::Set('path.static.skin', Config::Get('saved.path.static.skin'));
            }
        }
    }

    static function Boolean($xVal)
    {
        $bResult = null;
        if (!is_numeric($xVal) AND is_string($xVal)) {
            if (in_array($xVal, array('on', 'enable', 'yes', 'true', 'include'))) $bResult = true;
            if (in_array($xVal, array('off', 'disable', 'no', 'false', 'exclude'))) $bResult = false;
        }
        if (is_null($bResult)) $bResult = (bool)$xVal;
        return $bResult;
    }

    static function Backward($sPart = null)
    {
        if (is_null(self::$aBackward)) {
            self::$aBackward = array(
                'local' => false,
                'url' => '',
                'path' => null,
                'action' => null,
                'event' => null,
                'params' => array(),
            );
            if (isset($_SERVER['HTTP_REFERER'])) {
                self::$aBackward['url'] = $_SERVER['HTTP_REFERER'];
                if (strpos(self::$aBackward['url'], Config::Get('path.root.web')) === 0) {
                    self::$aBackward['local'] = true;
                    self::$aBackward['path'] = substr(self::$aBackward['url'], strlen(Config::Get('path.root.web')));
                    $aParts = explode('/', trim(self::$aBackward['path'], '/'));
                    if (isset($aParts[0])) self::$aBackward['action'] = $aParts[0];
                    if (isset($aParts[1])) self::$aBackward['event'] = $aParts[0];
                    if (isset($aParts[2])) self::$aBackward['params'] = array_slice($aParts, 2);
                }
            }
        }
        if (!$sPart) {
            return self::$aBackward;
        } elseif (isset(self::$aBackward[$sPart])) {
            return self::$aBackward[$sPart];
        }
        return null;
    }

    static function IsMobile()
    {
        return class_exists('MobileDetect') && MobileDetect::IsMobileTemplate();
    }

}

class ACE_Func extends ACE_Functions
{

}

// EOF
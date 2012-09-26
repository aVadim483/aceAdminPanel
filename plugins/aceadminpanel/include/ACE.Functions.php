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

class ACE_Functions
{
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
        echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<script language="JavaScript1.1" type="text/javascript">
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
    static function Path2Url($sPath)
    {
        return str_replace(
            str_replace(DIRECTORY_SEPARATOR, '/', self::GetRootDir()),
            self::GetRootUrl(),
            str_replace(DIRECTORY_SEPARATOR, '/', $sPath)
        );
    }

    /**
     * Преобразование URL в путь на сервере
     *
     * @param   string          $sUrl
     * @param   string|null     $sSeparator
     *
     * @return  string
     */
    static function Url2Path($sUrl, $sSeparator = null)
    {
        // * Delete www from path
        $sUrl = str_replace('//www.', '//', $sUrl);
        $sPathWeb = str_replace('//www.', '//', self::GetRootUrl());
        // * do replace
        $sUrl = str_replace($sPathWeb, self::GetRootDir(), $sUrl);
        return ACE::FilePath($sUrl, $sSeparator);

    }

    /**
     * Нормализует путь к файлу на сервере
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

        if ($sSeparator == '/') {
            $sPath = str_replace('\\', $sSeparator, $sPath);
        } elseif ($sSeparator == '\\') {
            $sPath = str_replace('/', $sSeparator, $sPath);
        } else {
            $sPath = str_replace(array('/', '\\'), $sSeparator, $sPath);
        }

        $sPath = str_replace($sSeparator . $sSeparator, $sSeparator, $sPath);
        return $sPath;
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
            pathinfo($sPath)
        );
        $n = strpos($aResult['extension'], '?');
        if ($n !== false) {
            $aResult['params'] = substr($aResult['extension'], $n+1);
            $aResult['extension'] = substr($aResult['extension'], 0, $n);
            $n = strpos($aResult['basename'], '?');
            $aResult['basename'] = substr($aResult['basename'], 0, $n);
        }
        return $aResult;
    }

    /**
     * Преобразует строку в массив
     *
     * @param   string|array    $sStr
     * @param   string          $sChr
     *
     * @return  array
     */
    static function Str2Array($sStr, $sChr = ',', $bSkipEmpty=false)
    {
        if (is_array($sStr)) $arr = $sStr;
        else $arr = explode($sChr, $sStr);

        $aResult = array();
        foreach($arr as $str) {
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
                    @chmod($sCheckPath, $nMode);
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
        $unim = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        $c = 0;
        while ($n >= 1024) {
            $c++;
            $n = $n / 1024;
        }
        return number_format($n, ($c ? 2 : 0), ',', '.') . '&nbsp;' . $unim[$c];
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

}

class ACE_Func extends ACE_Functions
{

}

// EOF
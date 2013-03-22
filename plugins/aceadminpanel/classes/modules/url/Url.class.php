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

class PluginAceadminpanel_ModuleUrl extends PluginAceadminpanel_Inherit_ModuleUrl
{
    protected static $nTimeOut = 5;

    /**
     * Задание таймаута
     *
     * @param $nTimeout
     */
    public function SetTimeOut($nTimeout)
    {
        self::$nTimeOut = $nTimeout;
    }

    /**
     * Формирование строки параметров из массива
     *
     * @param $aParams
     * @return string
     */
    public function BuildParamsStr($aParams)
    {
        if (!$aParams) {
            $sParams = '';
        } elseif (is_string($aParams)) {
            $sParams = urlencode($aParams);
        } else {
            $sParams = http_build_query($aParams);
        }
        return $sParams;
    }

    /**
     * Запрос URL через механизм cURL
     *
     * @param $sUrl
     * @param string $aParams
     * @param string $sMethod
     * @param array $aOptions
     * @return string
     */
    public function curlSend($sUrl, $aParams = '', $sMethod = 'GET', $aOptions = array())
    {
        $sParams = self::BuildParamsStr($aParams);

        if (strtoupper($sMethod) == 'POST') {

            $aCurlOptions = array(
                CURLOPT_URL => $sUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => self::$nTimeOut,
                CURLOPT_POSTFIELDS => $sParams,
            );
        } else {
            $aCurlOptions = array(
                CURLOPT_URL => $sUrl . (strpos($sUrl, '?') === false ? '?' : '') . $sParams,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => self::$nTimeOut,
            );
        }

        $aCurlOptions = func_array_merge_assoc($aCurlOptions, $aOptions);

        $ch = curl_init();
        curl_setopt_array($ch, $aCurlOptions);

        $sResponse = curl_exec($ch);

        curl_close($ch);

        return $sResponse;
    }

    /**
     * Запрос URL через механизм socket
     *
     * @param $sUrl
     * @param string $aParams
     * @param string $sMethod
     * @param array $aOptions
     * @return string
     */
    public function socketSend($sUrl, $aParams = '', $sMethod = 'GET', $aOptions = array())
    {
        $sParams = self::BuildParamsStr($aParams);

        $aUrl = parse_url($sUrl);

        $sMethod = strtoupper($sMethod);
        if ($sMethod == 'POST') {
            $aSocketOptions = array(
                'Content-type' => 'application/x-www-form-urlencoded',
                'Content-length' => strlen($sParams),
            );
        } else {
            $aSocketOptions = array(
            );
        }
        $aSocketOptions = func_array_merge_assoc($aSocketOptions, $aOptions);

        $sRequest = "POST {$aUrl['path']} HTTP/1.1\r\n";
        $sRequest .= "Host: {$aUrl['host']}\r\n";
        foreach ($aSocketOptions as $sKey => $sVal) {
            if (is_integer($sKey)) {
                $sRequest .= $sVal . "\r\n";
            } else {
                $sRequest .= $sKey . ':' . $sVal . "\r\n";
            }
        }
        if ($sMethod == 'POST') {
            $sRequest .= "\r\n$sParams\r\n\r\n";
        }

        // открывает сокет
        $sh = @fsockopen($aUrl['host'], isset($aUrl['port']) ? $aUrl['port'] : 80, $errno, $errstr, self::$nTimeOut);

        if (!$sh) {
            return false;
        }
        // передаем данные
        fputs($sh, $sRequest);

        $sResponse = '';
        while (($sLine = fgets($sh, 4096)) !== false) {
            $sResponse .= $sLine;
        }
        // закрываем сокет
        fclose($sh);

        return $sResponse;
    }

    /**
     * Запрос URL
     *
     * @param $sUrl
     * @param string $aParams
     * @param string $sMethod
     * @return string
     */
    public function Send($sUrl, $aParams = '', $sMethod = 'GET')
    {
        if (function_exists('curl_init')) {
            $sResponse = self::curlSend($sUrl, $aParams, $sMethod);
        } else {
            $sResponse = self::socketSend($sUrl, $aParams, $sMethod);
        }
        return $sResponse;
    }

}

if (!function_exists('http_build_query')) {
    function http_build_query($xData, $sPrefix = '', $sSeparator = '', $sKey = '')
    {
        $aResult = array();
        foreach ((array)$xData as $k => $v) {
            if (is_integer($k) AND $sPrefix != null) {
                $k = urlencode($sPrefix . $k);
            }
            if ((!empty($sKey)) OR ($sKey === 0)) $k = $sKey . '[' . urlencode($k) . ']';
            if (is_array($v) OR is_object($v)) {
                array_push($aResult, http_build_query($v, '', $sSeparator, $k));
            } else {
                array_push($aResult, $k . '=' . urlencode($v));
            }
        }
        if (empty($sSeparator)) $sSeparator = ini_get('arg_separator.output');
        return implode($sSeparator, $aResult);
    }
}

// EOF
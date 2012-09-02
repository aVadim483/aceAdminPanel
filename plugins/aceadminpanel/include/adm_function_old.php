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
 * @File Name: adm_function.php
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *----------------------------------------------------------------------------
 */

/*
 * Старые вызовы функций (оставлены для совместимости, потом будут удалены)
 */

function admHeaderLocation($sLocation)
{
    return ACE::HeaderLocation($sLocation);
}

function admBacktrace($nLevel = 0, $sElement = '', $sMatch = '')
{
    return ACE::Backtrace($nLevel, $sElement, $sMatch);
}

function admPath2Url($sPath)
{
    return ACE::Path2Url($sPath);
}

function admUrl2Path($sUrl, $sSeparator = null)
{
    return ACE::Url2Path($sUrl, $sSeparator);
}

function admFilePath($sPath, $sSeparator = null)
{
    return ACE::FilePath($sPath, $sSeparator);
}

function admLocalPath($sPath, $sRoot)
{
    return ACE::LocalPath($sPath, $sRoot);
}

function admLocalDir($sPath)
{
    return ACE::LocalDir($sPath);
}

function admLocalUrl($sPath)
{
    return ACE::LocalUrl($sPath);
}

function admStr2Array($sStr, $sChr = ',')
{
    return ACE::Str2Array($sStr, $sChr);
}

function admMakeDir($sNewDir)
{
    return ACE::MakeDir($sNewDir);
}

function admRemoveDir($sDir)
{
    return ACE::RemoveDir($sDir);
}

function admClearDir($sDir, $bRecursive = true)
{
    return ACE::ClearDir($sDir, $bRecursive);
}

function admClearSmartyCache()
{
    return ACE::ClearSmartyCache();
}

function admClearHeadfilesCache()
{
    return ACE::ClearHeadfilesCache();
}

function admClearAllCache()
{
    return ACE::ClearAllCache();
}

function admGetAllUserIp()
{
    return ACE::GetAllUserIp();
}

function admGetMajorVersion($sVersion)
{
    return ACE::GetMajorVersion($sVersion);
}

function admMSIE6()
{
    return ACE::MSIE6();
}

function admGetVisitorId()
{
    return ACE::GetVisitorId();
}

function admSize($n)
{
    return ACE::MemSizeFormat($n);
}

function admArrayAssoc($aArray)
{
    return ACE::ArrayAssoc($aArray);
}

function admStrUnderScore($sStr)
{
    return ACE::StrUnderScore($sStr);
}

function admStrCamelCase($sStr, $bCapitaliseFirstChar = false)
{
    return ACE::StrCamelCase($sStr, $bCapitaliseFirstChar = false);
}

function admSkinExists($sSkin)
{
    return ACE::SkinExists($sSkin);
}


if (!defined('ADM_VISITOR_ID')) {
    admGetVisitorId();
}


// EOF
<?php
/*---------------------------------------------------------------------------
 * @Plugin Name: aceAdminPanel
 * @Plugin Id: aceadminpanel
 * @Plugin URI:
 * @Description:
 * @Version: 2.0
 * @Author: Vadim Shemarov (aka aVadim)
 * @Author URI:
 * @LiveStreet Version: 1.0.1
 * @File Name: Viewer.class.php
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *----------------------------------------------------------------------------
 */

/**
 * @param $sSource
 * @param Smarty_Internal_Template $oTemplate
 * @return mixed
 */
function smarty_prefilter_tplhook($sSource, Smarty_Internal_Template $oTemplate)
{
    $aTplHooks = $oTemplate->getTemplateVars('aTplHooks');
    $aSmartyExp = null;
    $sHtml = $sSource;
    $bChanged = false;
    foreach ($aTplHooks as $oTplHook) {
        if ($oTplHook->isCurrentTemplate($oTemplate->smarty->_current_file)) {
            if (is_null($aSmartyExp)) {
                $aSmartyExp = array();
                $sHtml = _smarty_prefilter_tplhook_replace($sHtml, $aSmartyExp);
            }
            $doc = phpQuery::newDocumentHTML($sHtml);
            $sSelector = $oTplHook->GetSelector();
            $bParent = (substr($sSelector, -7) == ':parent');
            if ($bParent)
                $aElements = $doc->find($sSelector)->parent();
            else
                $aElements = $doc->find($sSelector);
            if ($aElements) {
                $bChanged = true;
                foreach ($aElements as $el) {
                    $sTplCode = $oTplHook->Call();
                    $sTplCode = _smarty_prefilter_tplhook_replace($sTplCode, $aSmartyExp);
                    switch ($oTplHook->GetAction()) {
                        case 'prepend':
                            pq($el)->prepend($sTplCode);
                            break;
                        case 'append':
                            pq($el)->append($sTplCode);
                            break;
                        case 'before':
                            pq($el)->before($sTplCode);
                            break;
                        case 'after':
                            pq($el)->after($sTplCode);
                            break;
                        case 'html':
                            pq($el)->html($sTplCode);
                            break;
                        case 'text':
                            pq($el)->text($sTplCode);
                            break;
                        default:
                            pq($el)->replaceWith($sTplCode);
                    }
                }
            }
        }
    }
    if ($bChanged AND isset($doc)) {
        $sSource = '' . $doc;
        if ($aSmartyExp) {
            foreach ($aSmartyExp as $sKey => $sVal) {
                $sSource = str_replace($sKey, $sVal, $sSource);
            }
        }
    }
    if (Config::Get('plugin.aceadminpanel.smarty.options.mark_template')) {
        if ((strtoupper(substr($sSource, 0, 10)) == '<!DOCTYPE ') AND ($n = strpos($sSource, '>'))) {
            $sSource = substr($sSource, 0, $n+1)
                . "\n<!-- TEMPLATE BEGIN " . $oTemplate->smarty->_current_file . " -->\n"
                . substr($sSource, $n+1)
                . "\n<!-- TEMPLATE END " . $oTemplate->smarty->_current_file . " -->\n";
        } else {
            $sSource = "\n<!-- TEMPLATE BEGIN " . $oTemplate->smarty->_current_file . " -->\n"
                . $sSource . "\n<!-- TEMPLATE END " . $oTemplate->smarty->_current_file . " -->\n";
        }
    }
    return $sSource;
}

function _smarty_prefilter_tplhook_replace($sHtml, &$aSmartyExp)
{
    if (is_null($aSmartyExp)) $aSmartyExp = array();
    if (preg_match_all('|\{[a-z\$\/].*\}|imuU', $sHtml, $aMatches, PREG_OFFSET_CAPTURE)) {
        $nOffset = 0;
        foreach ($aMatches[0] as $m) {
            $sExpId = uniqid('smarty_expression_' . time() . '_');
            $aSmartyExp[$sExpId] = $m[0];
            $nPos = $m[1] + $nOffset;
            $sHtml = substr($sHtml, 0, $nPos) . $sExpId . substr($sHtml, $nPos + strlen($m[0]));
            $nOffset += strlen($sExpId) - strlen($m[0]);
        }
    } else {
        $aSmartyExp = ($aSmartyExp ? $aSmartyExp : array());
    }
    return $sHtml;
}

// EOF

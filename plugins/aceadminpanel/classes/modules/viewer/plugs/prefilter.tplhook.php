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
 * @param $sSource
 * @param Smarty_Internal_Template $oTemplate
 * @return mixed
 */
function smarty_prefilter_tplhook($sSource, Smarty_Internal_Template $oTemplate)
{
    $aTplHooks = $oTemplate->getTemplateVars('aTplHooks');
    foreach ($aTplHooks as $oTplHook) {
        if ($oTplHook->isCurrentTemplate($oTemplate->smarty->_current_file) AND ($sSelector = $oTplHook->GetSelector())) {
            $sTplCode = $oTplHook->Call();

            $doc = new DomFrag($sSource);
            $oElements = $doc->find($sSelector);
            if ($oElements->count()) {
                switch ($oTplHook->GetAction()) {
                    case 'prepend':
                        $oElements->prepend($sTplCode);
                        break;
                    case 'append':
                        $oElements->append($sTplCode);
                        break;
                    case 'before':
                        $oElements->before($sTplCode);
                        break;
                    case 'after':
                        $oElements->after($sTplCode);
                        break;
                    case 'wrap':
                        $oElements->wrap($sTplCode);
                        break;
                    case 'html':
                        $oElements->html($sTplCode);
                        break;
                    case 'text':
                        $oElements->text($sTplCode);
                        break;
                    default:
                        $oElements->replaceWith($sTplCode);
                }
            }
            $sSource = $doc->html();
            $doc->clear();
            unset($doc);
        }
    }
    if (Config::Get('plugin.aceadminpanel.smarty.options.mark_template')) {
        $sSource = _smarty_prefilter_tplhook_mark($sSource, $oTemplate);
    }
    return $sSource;
}

function _smarty_prefilter_tplhook_mark($sSource, Smarty_Internal_Template $oTemplate)
{
    $sTemplateFile = ACE::FilePath($oTemplate->smarty->_current_file);
    $nLevel = intval(Config::Get('plugin.aceadminpanel.smarty.options.mark_template_lvl'));

    $sSource = ($nLevel ? "\n\n" : "")
        . "<!-- TEMPLATE BEGIN ($nLevel " . $sTemplateFile . ") -->" . ($nLevel ? "\n" : "")
        . $sSource . ($nLevel ? "\n" : "")
        . "<!-- TEMPLATE END ($nLevel " . $sTemplateFile . ") -->" . ($nLevel ? "\n" : "");

    Config::Set('plugin.aceadminpanel.smarty.options.mark_template_lvl', ++$nLevel);
    return $sSource;
}

function smarty_outputfilter_tplhook_mark($sSource, Smarty_Internal_Template $oTemplate)
{
    if ($nPos = stripos($sSource, '<!doctype html>')) {
        $sSource = substr($sSource, $nPos, 15) . substr($sSource, 0, $nPos) . substr($sSource, $nPos + 15);
    }

    return $sSource;
}

// EOF

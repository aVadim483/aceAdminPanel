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

class PluginAceadminpanel_ActionAdmin_EventInfo extends PluginAceadminpanel_Inherit_ActionAdmin_EventInfo
{

    public function Init()
    {
        if (($result = parent::Init())) {
            return $result;
        }

        $this->SetDefaultEvent('info');
    }

    protected function RegisterEvent()
    {
        parent::RegisterEvent();
        $this->AddEvent('info', 'EventInfo');
    }

    public function EventInfo()
    {
        if ($sReportMode = getRequest('report', null, 'post')) {
            $this->EventInfoReport($this->_getInfoData(), $sReportMode);
        } elseif ($this->GetParam(0) == 'phpinfo') {
            $this->EventInfoPhpInfo(1);
        }

        $this->_PluginSetTemplate('info');
        $this->Viewer_Assign('aCommonInfo', $this->_getInfoData());
    }

    protected function _getInfoData()
    {
        $aPlugins = $this->Plugin_GetList();
        $aActivePlugins = $this->Plugin_GetActivePlugins();
        $aPluginList = array();
        foreach ($aActivePlugins as $sPlugin) {
            if (isset($aPlugins[$sPlugin])) {
                $aPliginProps = $aPlugins[$sPlugin];
                $sPluginName = htmlspecialchars((string)$aPliginProps['property']->name->data);
                $aPluginInfo = array(
                    'item' => $sPlugin,
                    'label' => $sPluginName,
                );
                if ($aPliginProps['property']->version) {
                    $aPluginInfo['value'] = 'v.' . htmlspecialchars((string)$aPliginProps['property']->version);
                }
                $sPluginClass = 'Plugin' . ucfirst($sPlugin);
                if (class_exists($sPluginClass) AND method_exists($sPluginClass, 'GetUpdateInfo')) {
                    $oPlugin = new $sPluginClass;
                    $aPluginInfo['.html'] = ' - ' . $oPlugin->GetUpdateInfo();
                }
                $aPluginList[$sPlugin] = $aPluginInfo;
            }
        }

        $aSiteStat = $this->PluginAceadminpanel_Admin_GetSiteStat();
        $sSmartyVersion = $this->Viewer_GetSmartyVersion();

        $aInfo = array(
            'versions' => array(
                'label' => $this->Lang_Get('adm_info_versions'),
                'data' => array(
                    'php' => array('label' => $this->Lang_Get('adm_info_version_php'), 'value' => PHP_VERSION,),
                    'smarty' => array('label' => $this->Lang_Get('adm_info_version_smarty'), 'value' => $sSmartyVersion ? $sSmartyVersion : 'n/a',),
                    'ls' => array('label' => $this->Lang_Get('adm_info_version_ls'), 'value' => LS_VERSION,),
                    'adminpanel' => array('label' => $this->Lang_Get('adm_info_version_adminpanel'), 'value' => $this->aPluginInfo['version'],),
                )

            ),
            'site' => array(
                'label' => $this->Lang_Get('adm_site_info'),
                'data' => array(
                    'url' => array('label' => $this->Lang_Get('adm_info_site_url'), 'value' => Config::Get('path.root.web'),),
                    'skin' => array('label' => $this->Lang_Get('adm_info_site_skin'), 'value' => Config::Get('aceadminpanel.saved.view.skin'),),
                    //'jslib' => array('label' => $this->Lang_Get('adm_info_site_jslib'), 'value' => Config::Get('js.lib'),),
                    'client' => array('label' => $this->Lang_Get('adm_info_site_client'), 'value' => $_SERVER['HTTP_USER_AGENT'],),
                ),
            ),
            'plugins' => array(
                'label' => $this->Lang_Get('adm_active_plugins'),
                'data' => $aPluginList,
            ),
            'stats' => array(
                'label' => $this->Lang_Get('adm_site_statistics'),
                'data' => array(
                    'users' => array('label' => $this->Lang_Get('adm_site_stat_users'), 'value' => $aSiteStat['users'],),
                    'blogs' => array('label' => $this->Lang_Get('adm_site_stat_blogs'), 'value' => $aSiteStat['blogs'],),
                    'topics' => array('label' => $this->Lang_Get('adm_site_stat_topics'), 'value' => $aSiteStat['topics'],),
                    'comments' => array('label' => $this->Lang_Get('adm_site_stat_comments'), 'value' => $aSiteStat['comments'],),
                ),
            ),
        );

        return $aInfo;
    }

    public function EventInfoReport($aInfo, $sMode = 'txt')
    {
        $this->Security_ValidateSendForm();
        $sMode = strtolower($sMode);
        $aParams = array(
            'filename' => $sFileName = str_replace(array('.', '/'), '_', str_replace(array('http://', 'https://'), '', Config::Get('path.root.web'))) . '.' . $sMode,
            'date' => date('Y-m-d H:i:s'),
        );

        if ($sMode == 'xml') {
            $this->_reportXml($aInfo, $aParams);
        } else {
            $this->_reportTxt($aInfo, $aParams);
        }
        exit;
    }

    protected function _reportTxt($aInfo, $aParams)
    {
        $sText = '[report]' . "\n";
        foreach ($aParams as $sKey => $sVal) {
            $sText .= $sKey . ' = ' . $sVal . "\n";
        }
        $sText .= "\n";

        foreach ($aInfo as $sSectionKey => $aSection) {
            if (getRequest('adm_report_' . $sSectionKey)) {
                $sText .= '[' . $sSectionKey . '] ; ' . $aSection['label'] . "\n";
                foreach ($aSection['data'] as $sItemKey => $aItem) {
                    $sText .= $sItemKey . ' = ' . $aItem['value'] . '; ' . $aItem['label'] . "\n";
                }
                $sText .= "\n";
            }
        }
        $sText .= "; EOF\n";

        header('Content-Type: text/plain; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $aParams['filename'] . '"');
        echo $sText;
        exit;
    }

    protected function _reportXml($aInfo, $aParams)
    {
        $nLevel = 0;
        $sText = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<report';
        foreach ($aParams as $sKey => $sVal) {
            $sText .= ' ' . $sKey . '="' . $sVal . '"';
        }
        $sText .= ">\n";
        foreach ($aInfo as $sSectionKey => $aSection) {
            if (getRequest('adm_report_' . $sSectionKey)) {
                $nLevel = 1;
                $sText .= str_repeat(' ', $nLevel * 2) . '<' . $sSectionKey . ' label="' . $aSection['label'] . '">' . "\n";
                $nLevel += 1;
                foreach ($aSection['data'] as $sItemKey => $aItem) {
                    $sText .= str_repeat(' ', $nLevel * 2) . '<' . $sItemKey . ' label="' . $aItem['label'] . '">';
                    if (is_array($aItem['value'])) {

                        $sText .= "\n" . str_repeat(' ', $nLevel * 2) . '</' . $sItemKey . '>' . "\n";
                    } else {
                        $sText .= $aItem['value'];
                    }
                    $sText .= '</' . $sItemKey . '>' . "\n";
                }
                $nLevel -= 1;
                $sText .= str_repeat(' ', $nLevel * 2) . '</' . $sSectionKey . '>' . "\n";
            }
        }

        $sText .= '</report>';

        header('Content-Type: text/xml; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $aParams['filename'] . '"');
        echo $sText;
        exit;
    }

    protected function EventInfoPhpInfo($nMode = 0)
    {
        if ($nMode) {
            ob_start();
            phpinfo(-1);

            $phpinfo = preg_replace(
                array('#^.*<body>(.*)</body>.*$#ms', '#<h2>PHP License</h2>.*$#ms',
                    '#<h1>Configuration</h1>#', "#\r?\n#", "#</(h1|h2|h3|tr)>#", '# +<#',
                    "#[ \t]+#", '#&nbsp;#', '#  +#', '# class=".*?"#', '%&#039;%',
                    '#<tr>(?:.*?)" src="(?:.*?)=(.*?)" alt="PHP Logo" /></a>'
                        . '<h1>PHP Version (.*?)</h1>(?:\n+?)</td></tr>#',
                    '#<h1><a href="(?:.*?)\?=(.*?)">PHP Credits</a></h1>#',
                    '#<tr>(?:.*?)" src="(?:.*?)=(.*?)"(?:.*?)Zend Engine (.*?),(?:.*?)</tr>#',
                    "# +#", '#<tr>#', '#</tr>#'),
                array('$1', '', '', '', '</$1>' . "\n", '<', ' ', ' ', ' ', '', ' ',
                    '<h2>PHP Configuration</h2>' . "\n" . '<tr><td>PHP Version</td><td>$2</td></tr>' .
                        "\n" . '<tr><td>PHP Egg</td><td>$1</td></tr>',
                    '<tr><td>PHP Credits Egg</td><td>$1</td></tr>',
                    '<tr><td>Zend Engine</td><td>$2</td></tr>' . "\n" .
                        '<tr><td>Zend Egg</td><td>$1</td></tr>', ' ', '%S%', '%E%'),
                ob_get_clean());
            $sections = explode('<h2>', strip_tags($phpinfo, '<h2><th><td>'));
            unset($sections[0]);

            $aPhpInfo = array();
            foreach ($sections as $ns => $section) {
                $n = substr($section, 0, strpos($section, '</h2>'));
                preg_match_all(
                    '#%S%(?:<td>(.*?)</td>)?(?:<td>(.*?)</td>)?(?:<td>(.*?)</td>)?%E%#',
                    $section, $askapache, PREG_SET_ORDER);
                foreach ($askapache as $k => $m) {
                    if (!isset($m[2])) $m[2] = '';
                    $aPhpInfo[$n][$m[1]] = (!isset($m[3])OR$m[2] == $m[3]) ? $m[2] : array_slice($m, 2);
                }
            }
            $this->Viewer_Assign('aPhpInfo', array('collection' => $aPhpInfo, 'count' => sizeof($aPhpInfo)));
        } else {
            ob_start();
            phpinfo();
            $phpinfo = ob_get_contents();
            ob_end_clean();
            $phpinfo = str_replace("\n", ' ', $phpinfo);
            $info = '';
            if (preg_match('|<style\s*[\w="/]*>(.*)<\/style>|imu', $phpinfo, $match)) $info .= $match[0];
            if (preg_match('|<body\s*[\w="/]*>(.*)<\/body>|imu', $phpinfo, $match)) $info .= $match[1];
            if (!$info) $info = $phpinfo;
            $this->Viewer_Assign('sPhpInfo', $info);
        }
        $this->PluginSetTemplate('info_phpinfo');
        $this->PluginAppendScript('phpinfo.js');
    }

}
// EOF
<?php
/*---------------------------------------------------------------------------
 * @Plugin Name: aceAdminPanel
 * @Plugin Id: aceadminpanel
 * @Plugin URI: 
 * @Description: Advanced Administrator's Panel for LiveStreet/ACE
 * @Version: 2.0.348
 * @Author: Vadim Shemarov (aka aVadim)
 * @Author URI: 
 * @LiveStreet Version: 1.0.1
 * @File Name: %%filename%%
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *----------------------------------------------------------------------------
 */

/**
 * Обработка УРЛа вида /topic/ - управление своими топиками
 *
 */
class PluginAceadminpanel_ActionLess extends ActionPlugin
{
    private $sPlugin = 'aceadminpanel';

    protected $sEvent;
    protected $aFiles = array();

    public function Init()
    {
        $this->Viewer_SetResponseAjax(true);
        $this->sEvent = Router::GetActionEvent();
        //$this->SetDefaultEvent($this->sEvent);
        $this->SetDefaultEvent('file');
    }

    protected function RegisterEvent()
    {
        //$this->AddEvent($this->sEvent, 'EventExec');
        $this->AddEvent('file', 'EventExec');
        //$this->AddEventPreg('/^[\w\-\_]*$/i','EventShowPage');
    }

    public function EventExec()
    {
        $sFile = '';
        $sSourceFile = implode('/', $this->GetParams());
        $sSourceFile = str_replace('[skin]', HelperPlugin::GetTemplatePath(), $sSourceFile);
        $sSourceFile = str_replace('[admin_skin]', HelperPlugin::GetPluginPath('aceadminpanel') . '/templates/skin/' . $this->Admin_GetAdminSkin(), $sSourceFile);
        if (isset($_SERVER['QUERY_STRING']) AND $_SERVER['QUERY_STRING']) $sSourceFile .= '?' . $_SERVER['QUERY_STRING'];
        $sCachePath = Config::Get('path.smarty.cache') . '/' . $this->Admin_GetAdminSkin();

        $aFileParts = ACE::PathInfo($sSourceFile);

        if (strtolower($aFileParts['extension']) == 'css' AND getRequest('from', '', 'get') == 'less') {
            $sSourceFile = $aFileParts['dirname'] . '/' . $aFileParts['filename'] . '.less';
            $sFileType = 'less';
        } elseif (strtolower($aFileParts['extension']) == 'less') {
            $sFileType = 'less';
        } else {
            $sFileType = 'other';
        }
        if ($sFileType == 'less') {
            $sCachePath .= '/css/';
            if (!is_dir($sCachePath)) ACE::MakeDir($sCachePath);
            $aLessParams = array(
                'file' => $sSourceFile,
                'config' => array(
                    'formatter' => 'compressed',
                ),
                'variables' => array(
                    'gridColumns' => 16,
                    'gridColumnWidth' => '65px',
                    'gridGutterWidth' => '12px',
                    'baseFontSize' => '12px',
                    'baseLineHeight' => '18px',
                ),
            );
            // определяем целевой CSS-файл
            $sFile = ACE::FilePath($sCachePath . '/' . md5(serialize($aLessParams)) . '.css');

            // если целевого файла нет - компилируем его из исходного LESS-файла
            if (!is_file($sFile)) {
                $oLess = $this->PluginAceadminpanel_Aceless_GetLessCompiler();
                $oLess->setVariables($aLessParams['variables'], true);
                $oLess->setFormatter('compressed');
                $oLess->checkedCompile($sSourceFile, $sFile);
                $this->aFiles[] = array(
                    'source' => $sSourceFile,
                    'target' => $sFile,
                );
            }
            $sContentType = 'text/css';
        } else {
            $sCachePath .= '/' . basename($aFileParts['dirname']) . '/';
            if (!is_dir($sCachePath)) ACE::MakeDir($sCachePath);
            $sFile = $sCachePath . $aFileParts['basename'];
            copy($sSourceFile, $sFile);
            $sContentType = 'image/' . strtolower($aFileParts['extension']);
        }

        if ($sFile AND is_file($sFile)) {
            $sCssContent = file_get_contents($sFile);
            header('Content-type: ' . $sContentType);
            echo $sCssContent;
        }
        /* */
        exit;
    }

}

// EOF
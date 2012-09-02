<?php
/*---------------------------------------------------------------------------
* @Plugin Name: aceAdminPanel
* @Plugin Id: aceadminpanel
* @Plugin URI:
* @Description: Advanced Administrator's Panel for LiveStreet/ACE
* @Version: 1.5.210
* @Author: Vadim Shemarov (aka aVadim)
* @Author URI:
* @LiveStreet Version: 0.5
* @File Name: ActionAjax.class.php
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
        $aFileParts = pathinfo($sSourceFile);
        if (strtolower($aFileParts['extension']) !== 'less') {
            $sCachePath .= '/' . basename($aFileParts['dirname']) . '/';
            if (!is_dir($sCachePath)) ACE::MakeDir($sCachePath);
            $sFile = $sCachePath . $aFileParts['basename'];
            copy($sSourceFile, $sFile);
            $sContentType = 'image/' . strtolower($aFileParts['extension']);
        } else {
            $sCachePath .= '/css/';
            if (!is_dir($sCachePath)) ACE::MakeDir($sCachePath);
            $aLessParams = array(
                'file' => $sSourceFile,
                'config' => array(
                    'formatter' => 'compressed',
                ),
                'variables' => array(
                    'gridColumns' => 14,
                    'gridColumnWidth' => '75px',
                    'gridGutterWidth' => '15px',
                    'baseFontSize' => '12px',
                    'baseLineHeight' => '18px',
                ),
            );

            $sFile = ACE::FilePath($sCachePath . '/' . md5(serialize($aLessParams)) . '.css');
            /* */
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
        }
        if ($sFile AND is_file($sFile)) {
            $sCssContent = file_get_contents($sFile);
            header('Content-type: ' . $sContentType);
            echo $sCssContent;
        }
        /* */
        //var_dump($sSourceFile, $sCssFile);
        exit;
    }

}

// EOF
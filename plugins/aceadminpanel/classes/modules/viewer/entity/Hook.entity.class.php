<?php
/*---------------------------------------------------------------------------
 * @Plugin Name: aceAdminPanel
 * @Plugin Id: aceadminpanel
 * @Plugin URI: 
 * @Description: Advanced Administrator's Panel for LiveStreet/ACE
 * @Version: 2.0
 * @Author: Vadim Shemarov (aka aVadim)
 * @Author URI: 
 * @LiveStreet Version: 1.0.1
 * @File Name: User.entity.class.php
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *----------------------------------------------------------------------------
 */

class PluginAceadminpanel_ModuleViewer_EntityHook extends Entity
{
    const TYPE_FILE = 1;
    const TYPE_FUNC = 2;
    const TYPE_METHOD = 3;

    public function Init()
    {
        parent::Init();
        $xAction = $this->GetAction();
        if (!is_array($xAction) AND substr($xAction, -4) == '.tpl') {
            $this->SetType(self::TYPE_FILE);
            $this->SetIncludeTemplate($xAction);
        } elseif (is_array($xAction)) {
            $this->SetType(self::TYPE_METHOD);
            $this->SetCallback($xAction);
        } else {
            $this->SetType(self::TYPE_FUNC);
            $this->SetCallback($xAction);
        }
        if (!$this->GetSmarty()) {
            $this->SetSmarty($this->Viewer_GetSmartyObject());
        }
    }

    public function isTemplateEnd($sTpl)
    {
        $sTargetTpl = $this->GetTemplate();
        return substr($sTpl, -strlen($sTargetTpl)) == $sTargetTpl;
    }

    public function Call()
    {
        if ($this->GetType() == self::TYPE_FILE) {
            // получаем пути к шаблонам
            $aTplDirs = $this->GetSmarty()->getTemplateDir();
            $sTemplate = $this->GetIncludeTemplate();
            $sFile = '';
            // лежит ли подгружаемый шаблон по одному из путей
            foreach ($aTplDirs as $sDir) {
                if (ACE::LocalPath(dirname($sTemplate), $sDir) AND ACE::FileExists($sTemplate)) {
                    $sFile = $sTemplate;
                    break;
                }
            }
            if (!$sFile) {
                // варианты расположения шаблона
                foreach ($aTplDirs as $sDir) {
                    if (ACE::FileExists($sDir . '/' . $sTemplate)) {
                        $sFile = ACE::FilePath($sDir . '/' . $sTemplate);
                    }
                }
            }
            if (!$sFile) $sFile = $sTemplate;
            $sResult = file_get_contents($sFile);
        } else {
            $sResult = call_user_func_array($this->GetCallback(), array());
        }
        return $sResult;
    }

    public function GetSmarty()
    {
        if (!isset($this->_aData['smarty'])) {
            $this->_aData['smarty'] = $this->Viewer_GetSmartyObject();
        }
        return $this->_aData['smarty'];
    }
}

// EOF
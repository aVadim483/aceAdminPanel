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

class PluginAceadminpanel_ModuleViewer_EntityTplHook extends Entity
{

    public function Init()
    {
        parent::Init();
        if (!$this->GetSourceType()) {
            $this->SetSourceType();
        }
        if (!$this->GetSmarty()) {
            $this->SetSmarty($this->Viewer_GetSmartyObject());
        }
    }

    public function SetType($sSourceType = null)
    {
        return $this->SetSourceType($sSourceType);
    }

    public function SetSourceType($sSourceType = null)
    {
        if (!is_null($sSourceType)) {
            // Так нельзя!!! В PHP 5.2.9 в ф-цию __call() передается имя в нижнем регистре!!!
            // return parent::SetSourceType($sSourceType);
            $this->_aData['source_type'] = $sSourceType;
        } else {
            $xContentSource = $this->GetContentSource();
            if (!is_array($xContentSource) AND substr($xContentSource, -4) == '.tpl') {
                $this->SetSourceTypeTemplate();
            } elseif (is_array($xContentSource)) {
                $this->SetSourceTypeCallback();
            } else {
                $this->SetSourceTypeText();
            }
        }
    }

    public function SetSourceTypeTemplate()
    {
        $this->SetSourceType('template');
    }

    public function SetSourceTypeCallback()
    {
        $this->SetSourceType('callback');
    }

    public function SetSourceTypeText()
    {
        $this->SetSourceType('text');
    }

    public function GetType()
    {
        $sSourceType = parent::GetType();
        if (is_null($sSourceType))
            $sSourceType = $this->GetSourceType();
        return $sSourceType;
    }

    /**
     * Тип источника - шаблон?
     *
     * @return bool
     */
    public function SourceTypeIsTemplate()
    {
        return $this->GetSourceType() == 'template';
    }

    /**
     * Тип источника - вызываемая функция - ?
     *
     * @return bool
     */
    public function SourceTypeIsCallback()
    {
        return $this->GetSourceType() == 'callback';
    }

    /**
     * Тип источника - текст?
     *
     * @return bool
     */
    public function SourceTypeIsText()
    {
        return $this->GetSourceType() == 'text';
    }

    /**
     * Совпадает ли заданный шаблон с текущим?
     *
     * @param $sCurrentTpl
     * @return bool
     */
    public function isCurrentTemplate($sCurrentTpl)
    {
        $aTargetTpl = (array)$this->GetTemplate();
        foreach ($aTargetTpl as $sTargetTpl) {
            if ($sTargetTpl[0] != '/') $sTargetTpl = '/' . $sTargetTpl;
            if (ACE::PathCompare($sTargetTpl, $sCurrentTpl, true))
                return true;
        }
        return false;
    }


    public function Call()
    {
        if ($this->SourceTypeIsTemplate()) {
            // получаем пути к шаблонам
            $aTplDirs = $this->GetSmarty()->getTemplateDir();
            $sTemplate = $this->GetContentSource();
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
        } elseif ($this->SourceTypeIsCallback()) {
            $sResult = call_user_func_array($this->GetContentSource(), array());
        } else {
            $sResult = $this->GetContentSource();
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
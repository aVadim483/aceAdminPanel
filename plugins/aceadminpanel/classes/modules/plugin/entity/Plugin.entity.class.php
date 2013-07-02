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

class PluginAceadminpanel_ModulePlugin_EntityPlugin extends Entity
{
    protected function _getDataItem($sKey)
    {
        if (isset($this->_aData[$sKey]))
            return $this->_aData[$sKey];
        else
            return null;
    }

    public function GetProperty($sProp=null)
    {
        if (is_null($sProp))
            return $this->_aData['property'];
        else
            return $this->_aData['property']->$sProp;
    }

    public function GetName()
    {
        $xProp = $this->GetProperty('name');
        if ($xProp->data)
            return $xProp->data;
        else
            return $xProp->lang;
    }

    public function GetDescription()
    {
        $xProp = $this->GetProperty('description');
        if ($xProp->data)
            return $xProp->data;
        else
            return $xProp->lang;
    }

    public function GetAuthor()
    {
        $xProp = $this->GetProperty('author');
        if ($xProp->data)
            return $xProp->data;
        else
            return $xProp->lang;
    }

    public function GetPluginClass()
    {
        return 'Plugin' . ucfirst($this->GetCode());
    }

    public function GetAdminClass()
    {
        $aAdminPanel = $this->_getDataItem('adminpanel');
        if (isset($aAdminPanel['class']))
            return $aAdminPanel['class'];
        else {
            return 'Plugin'.ucfirst($this->GetId()).'_ActionAdmin';
        }
    }

    public function HasAdminpanel()
    {
        $sClass = $this->GetAdminClass();
        try {
            if (class_exists($sClass, true)) {
                return true;
            }
        } catch (Exception $e) {
            //if (class_exists())
        }
        return false;
    }

    public function GetAdminMenuEvents()
    {
        if ($this->IsActive()) {
            $aEvents = array();
            $sPluginClass = $this->GetPluginClass();
            $aProps = (array)(new $sPluginClass);
            if (isset($aProps['aAdmin']) AND is_array($aProps['aAdmin']) AND isset($aProps['aAdmin']['menu'])) {
                foreach ((array)$aProps['aAdmin']['menu'] as $sEvent=>$sClass) {
                    if (substr($sClass, 0, 1) == '_') {
                        $sClass = $sPluginClass . $sClass;
                    }
                    if (!preg_match('/Plugin([A-Z][a-z0-9]+)_(\w+)/', $sClass)) {
                        // nothing
                    }
                    $aEvents[$sEvent] = $sClass;
                }
            }
            return $aEvents;
        }
        return false;
    }

    public function GetVersion()
    {
        return $this->GetProperty('version');
    }

    public function GetHomepage()
    {
        return $this->GetProperty('homepage');
    }

    public function GetEmail()
    {
        return (string)$this->GetProperty('author')->email;
    }

    public function GetId()
    {
        return $this->GetCode();
    }

    public function IsActive()
    {
        return (bool)$this->_getDataItem('is_active');
    }
}

// EOF
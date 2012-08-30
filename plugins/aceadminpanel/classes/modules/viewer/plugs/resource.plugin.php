<?php

class Smarty_Resource_Plugin extends Smarty_Internal_Resource_File
//class Smarty_Resource_Plugin extends Smarty_Internal_Resource_Extends
{
    protected function _normalizeFilepath($_filepath, $_prefix='plugin:')
    {
        if (strpos($_filepath, $_prefix) === 0) {
            $_filepath = substr($_filepath, strlen($_prefix));
        }
        if (strpos($_filepath, '//') === 0) {
            $_filepath = admFilePath(Config::Get('path.root.server') . $_filepath, '/');
        }
        return $_filepath;
    }

    public function getTemplateFilepath($_template)
    {
        if (strpos($_template->resource_name, ':') === 0)
            $_template->resource_name = substr($_template->resource_name, 1);
        $sTemplate = HelperPlugin::GetDelegate('template', $_template->resource_name);
        $_filepath = $_template->buildTemplateFilepath ($sTemplate);

        if ($_filepath !== false) {
            if (is_object($_template->smarty->security_policy)) {
                $_template->smarty->security_policy->isTrustedResourceDir($_filepath);
            }
            $sPathRoot = admFilePath(Config::Get('path.root.server'), '/');
            if (strpos($_filepath, $sPathRoot)===0) {
                $_filepath = 'plugin:/' . substr($_filepath, strlen($sPathRoot));
            }
        }
        $_template->templateUid = sha1($_filepath);
        return $_filepath;
    }

    public function getTemplateSource($_template)
    {
        // read template file
        $_tfp = $this->_normalizeFilepath($_template->getTemplateFilepath());
        if (is_file($_tfp)) {
            $_template->template_source = file_get_contents($_tfp);
            return true;
        } else {
            return false;
        }
    }

    public function getTemplateTimestamp($_template)
    {
        return filemtime($this->_normalizeFilepath($_template->getTemplateFilepath()));
    }

    public function getTemplateTimestampTypeName($resource_type, $resource_name)
    {
        return filemtime($this->_normalizeFilepath($resource_name, $resource_type . ':'));
    }

    public function getCompiledFilepath($_template)
    {
        return str_replace('.:', '.', parent::getCompiledFilepath($_template));
    }
}

// EOF
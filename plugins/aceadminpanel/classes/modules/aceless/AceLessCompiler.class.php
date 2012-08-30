<?php

require_once(__DIR__ . '/../../../lib/lessphp/lessc.inc.php');

class AceLessCompiler extends lessc
{
    protected $forcedVars = array();

    public function setVariables($variables, $bForced = false)
    {
        if ($bForced) {
            $this->forcedVars = array_merge($this->forcedVars, $variables);
        }
        $this->registeredVars = array_merge($this->registeredVars, $variables, $this->forcedVars);
    }

    protected function set($name, $value)
    {
        if ($name[0] == $this->vPrefix) {
            if (isset($this->forcedVars[substr($name, 1)]) AND $this->get($name)) {
                return;
            }
        }
        return parent::set($name, $value);
    }


}

// EOF
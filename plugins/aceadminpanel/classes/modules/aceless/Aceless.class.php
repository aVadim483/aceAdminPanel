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
 * @File Name: Comment.class.php
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *----------------------------------------------------------------------------
 */

/**
 * Модуль для работы с топиками
 *
 */
require_once('AceLessCompiler.class.php');

class PluginAceadminpanel_ModuleAceless extends Module
{
    protected $oLess;

    public function Init()
    {
    }

    public function GetLessCompiler()
    {
        if (!$this->oLess)
            $this->oLess = new AceLessCompiler;
        return $this->oLess;
    }
}

// EOF
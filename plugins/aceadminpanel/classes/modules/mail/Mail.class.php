<?php
/*---------------------------------------------------------------------------
 * @Plugin Name: aceAdminPanel
 * @Plugin Id: aceadminpanel
 * @Plugin URI: 
 * @Description: Advanced Administrator's Panel for LiveStreet/ACE
 * @Version: 1.5.271
 * @Author: Vadim Shemarov (aka aVadim)
 * @Author URI: 
 * @LiveStreet Version: 0.5
 * @File Name: Notify.class.php
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *----------------------------------------------------------------------------
 */

class PluginAceadminpanel_ModuleMail extends PluginAceadminpanel_Inherit_ModuleMail
{
    private $sPlugin = 'aceadminpanel';

    /** @var PHPMailer */
    protected $oMailer;

    public function AddAdress($sMail, $sName = null)
    {
        if (PHPMailer::ValidateAddress($sMail)) {
            return parent::AddAdress($sMail, $sName);
        } else {
            // todo: добавить логгирование ошибки
        }
    }

    public function SetAdress($sMail, $sName = null)
    {
        if (PHPMailer::ValidateAddress($sMail)) {
            return parent::SetAdress($sMail, $sName);
        } else {
            // todo: добавить логгирование ошибки
        }
    }
}

// EOF
<?php
/*---------------------------------------------------------------------------
* @Plugin Name: aceAdminPanel
* @Plugin Id: aceadminpanel
* @Plugin URI:
* @Description: Advanced Administrator's Panel for LiveStreet/ACE
* @Version:
* @Author: Vadim Shemarov (aka aVadim)
* @Author URI:
* @LiveStreet
* @File Name:
* @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*----------------------------------------------------------------------------
*/

/**
 * Модуль для работы с топиками
 *
 */
class PluginAceadminpanel_ModuleUser extends PluginAceadminpanel_Inherit_ModuleUser
{
    public function Init()
    {
        parent::Init();
        $this->oMapper = Engine::GetMapper(__CLASS__);
    }

    /**
     * Получить статистику по юзерам
     *
     * @return array
     */
    public function GetStatUsers()
    {
        if (false === ($aStat = $this->Cache_Get("user_stats"))) {
            $aStat = parent::GetStatUsers();
            $aStat['count_admins'] = $this->oMapper->GetCountAdministrators();
        }
        return $aStat;
    }
}
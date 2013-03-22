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

/**
 * Модуль для работы с топиками
 *
 */
class PluginAceadminpanel_ModuleUser extends PluginAceadminpanel_Inherit_ModuleUser
{
    public function Init()
    {
        parent::Init();
        //$this->oMapper = Engine::GetMapper(__CLASS__);
    }

    /**
     * Получить статистику по юзерам
     *
     * @return array
     */
    public function GetStatUsers()
    {
        if (false === ($aStat = $this->Cache_Get('adm_user_stats'))) {
            $aStat = parent::GetStatUsers();
            $aStat['count_admins'] = $this->oMapper->GetCountAdministrators();

            $this->Cache_Set($aStat, 'adm_user_stats', array('user_update', 'user_new'), 60 * 60 * 24 * 4);
        }
        return $aStat;
    }
}

// EOF
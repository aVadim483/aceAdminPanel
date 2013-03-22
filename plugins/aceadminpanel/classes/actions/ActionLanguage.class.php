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
 * Класс обработки УРЛа вида /language/
 *
 */
class PluginAceadminpanel_ActionLanguage extends Action {
    protected $sMenuHeadItemSelect='language';

    public function Init() {
        $this->aLanguages=array();
        $this->SetDefaultEvent('index');
    }

    protected function RegisterEvent() {
        $this->AddEvent('index', 'EventIndex');
        if (Config::Get('plugin.aceadminpanel.lang_define') && ($sLangs=str_replace(' ', '', Config::Get('plugin.aceadminpanel.lang_define')))) {
            $aLangs=explode(',', $sLangs);
            if ($aLangs) {
                foreach($aLangs as $sLang) {
                    $this->AddEvent($sLang, 'EventIndex');
                }
            }
        } else {
            $this->AddEventPreg('/^(.+)?$/i', 'EventIndex');
        }
    }


    protected function EventIndex() {
        $sLanguage=Router::GetActionEvent();
        if ($sLanguage) {
            $this->Session_Set('language', $sLanguage);
            if (Config::Get('plugin.aceadminpanel.lang_save_period')) {
                @setcookie('LANG_CURRENT', $sLanguage, time()+60*60*24*intVal(Config::Get('plugin.aceadminpanel.lang_save_period')), Config::Get('sys.cookie.path'), Config::Get('sys.cookie.host'));
            }
            if (isset($_SERVER['HTTP_REFERER'])) {
                func_header_location($_SERVER['HTTP_REFERER']);
            }
        }
        func_header_location(Config::Get('path.root.web'));
    }

    /**
     * Выполняется при завершении работы экшена
     *
     */
    public function EventShutdown() {
    }
}
// EOF
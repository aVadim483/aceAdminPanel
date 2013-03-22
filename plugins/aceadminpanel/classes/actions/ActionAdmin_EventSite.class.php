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

class PluginAceadminpanel_ActionAdmin_EventSite extends PluginAceadminpanel_Inherit_ActionAdmin_EventSite
{
    protected $sPlugin = 'aceadminpanel';
    protected $aFields = array();

    protected function RegisterEvent()
    {
        parent::RegisterEvent();
        $this->AddEvent('site', 'EventSite');
    }

    protected function _DefaultConfigVal($val)
    {
        if (strlen($val) > 3 AND substr($val, 0, 2) == '--' AND substr($val, -2) == '--') {
            return true;
        }
        return false;
    }

    protected function _SetFields()
    {
        $aSkins = array('-- from config file --');
        $aDirs = glob(Config::Get('path.root.server') . '/templates/skin/*', GLOB_ONLYDIR);
        foreach ($aDirs as $sDir)
            $aSkins[] = basename($sDir);

        $aLangs = array();
        $aDirs = glob(Config::Get('path.root.server') . '/templates/language/*.php');
        foreach ($aDirs as $sDir)
            $aLangs[] = basename($sDir, '.php');

        $this->aFields['base'] =
            array(
                'adm_set_section_general' => array(
                    'type' => 'section',
                ),
                'adm_set_view_skin' => array(
                    'type' => 'select',
                    'options' => $aSkins,
                    'config' => 'view.skin',
                    'class' => 'w100p',
                    'valtype' => 'string',
                ),
                'adm_set_view_name' => array(
                    'type' => 'input',
                    'config' => 'view.name',
                    'class' => 'w100p'
                ),
                'adm_set_view_description' => array(
                    'type' => 'input',
                    'config' => 'view.description',
                    'class' => 'w100p'
                ),
                'adm_set_view_keywords' => array(
                    'type' => 'input',
                    'config' => 'view.keywords',
                    'class' => 'w100p'
                ),

                'adm_set_general_close' => array(
                    'type' => 'checkbox',
                    'config' => 'general.close',
                ),
                'adm_set_general_reg_invite' => array(
                    'type' => 'checkbox',
                    'config' => 'general.reg.invite',
                ),
                'adm_set_general_reg_activation' => array(
                    'type' => 'checkbox',
                    'config' => 'general.reg.activation',
                ),

                'adm_set_section_sys_lang' => array(
                    'type' => 'section',
                ),
                'adm_set_lang_current' => array(
                    'type' => 'select',
                    'options' => $aLangs,
                    'config' => 'lang.current',
                    'class' => 'w100p',
                    'valtype' => 'string',
                ),
                'adm_set_lang_default' => array(
                    'type' => 'select',
                    'options' => $aLangs,
                    'config' => 'lang.default',
                    'class' => 'w100p',
                    'valtype' => 'string',
                ),

                'adm_set_section_edit' => array(
                    'type' => 'section',
                ),
                'adm_set_view_tinymce' => array(
                    'type' => 'checkbox',
                    'config' => 'view.tinymce',
                ),
                'adm_set_view_noindex' => array(
                    'type' => 'checkbox',
                    'config' => 'view.noindex',
                ),
                'adm_set_view_img_resize_width' => array(
                    'type' => 'text',
                    'config' => 'view.img_resize_width',
                    'class' => 'w50 number',
                    'valtype' => 'number',
                ),
                'adm_set_view_img_max_width' => array(
                    'type' => 'text',
                    'config' => 'view.img_max_width',
                    'class' => 'w50 number',
                    'valtype' => 'number',
                ),
                'adm_set_view_img_max_height' => array(
                    'type' => 'text',
                    'config' => 'view.img_max_height',
                    'class' => 'w50 number',
                    'valtype' => 'number',
                ),
            );

        $this->aFields['sys'] =
            array(
                'adm_set_section_sys_cookie' => array(
                    'type' => 'section',
                ),
                'adm_set_sys_cookie_host' => array(
                    'type' => 'input',
                    'config' => 'sys.cookie.host',
                    'class' => 'w100p',
                    'valtype' => 'string',
                    'empty' => null,
                ),
                'adm_set_sys_cookie_path' => array(
                    'type' => 'input',
                    'config' => 'sys.cookie.path',
                    'class' => 'w100p',
                    'valtype' => 'string',
                ),
                'adm_set_sys_session_standart' => array(
                    'type' => 'checkbox',
                    'config' => 'sys.session.standart',
                ),
                'adm_set_sys_session_name' => array(
                    'type' => 'input',
                    'config' => 'sys.session.name',
                    'class' => 'w100p',
                    'valtype' => 'string',
                ),
                'adm_set_sys_session_timeout' => array(
                    'type' => 'text',
                    'config' => 'sys.session.timeout',
                    'class' => 'w50 number',
                    'valtype' => 'string',
                    'empty' => null,
                ),
                'adm_set_sys_session_host' => array(
                    'type' => 'input',
                    'config' => 'sys.session.host',
                    'class' => 'w100p',
                    'valtype' => 'string',
                ),
                'adm_set_sys_session_path' => array(
                    'type' => 'input',
                    'config' => 'sys.session.path',
                    'class' => 'w100p',
                    'valtype' => 'string',
                ),

                'adm_set_section_sys_mail' => array(
                    'type' => 'section',
                ),
                'adm_set_sys_mail_from_email' => array(
                    'type' => 'input',
                    'config' => 'sys.mail.from_email',
                    'class' => 'w100p',
                    'valtype' => 'string',
                ),
                'adm_set_sys_mail_from_name' => array(
                    'type' => 'input',
                    'config' => 'sys.mail.from_name',
                    'class' => 'w100p',
                    'valtype' => 'string',
                ),
                'adm_set_sys_mail_charset' => array(
                    'type' => 'input',
                    'config' => 'sys.mail.charset',
                    'class' => 'w100p',
                    'valtype' => 'string',
                ),
                'adm_set_sys_mail_type' => array(
                    'type' => 'select',
                    'options' => array('mail', 'sendmail', 'smtp'),
                    'config' => 'sys.mail.type',
                    'class' => 'w100p',
                    'valtype' => 'string',
                ),
                'adm_set_sys_mail_smtp_host' => array(
                    'type' => 'input',
                    'config' => 'sys.mail.smtp.host',
                    'class' => 'w100p',
                    'valtype' => 'string',
                ),
                'adm_set_sys_mail_smtp_port' => array(
                    'type' => 'text',
                    'config' => 'sys.mail.smtp.port',
                    'class' => 'w50 number',
                    'valtype' => 'number',
                ),
                'adm_set_sys_mail_smtp_user' => array(
                    'type' => 'input',
                    'config' => 'sys.mail.smtp.user',
                    'class' => 'w100p',
                    'valtype' => 'string',
                ),
                'adm_set_sys_mail_smtp_password' => array(
                    'type' => 'password',
                    'config' => 'sys.mail.smtp.password',
                    'class' => 'w100p',
                    'valtype' => 'password',
                ),
                'adm_set_sys_mail_smtp_auth' => array(
                    'type' => 'checkbox',
                    'config' => 'sys.mail.smtp.auth',
                ),
                'adm_set_sys_mail_include_comment' => array(
                    'type' => 'checkbox',
                    'config' => 'sys.mail.include_comment',
                ),
                'adm_set_sys_mail_include_talk' => array(
                    'type' => 'checkbox',
                    'config' => 'sys.mail.include_talk',
                ),

                'adm_set_section_sys_logs' => array(
                    'type' => 'section',
                ),
                'adm_set_sys_logs_sql_query' => array(
                    'type' => 'checkbox',
                    'config' => 'sys.logs.sql_query',
                ),
                'adm_set_sys_logs_sql_query_file' => array(
                    'type' => 'input',
                    'config' => 'sys.logs.sql_query_file',
                    'class' => 'w100p',
                    'valtype' => 'string',
                ),
                'adm_set_sys_logs_sql_error' => array(
                    'type' => 'checkbox',
                    'config' => 'sys.logs.sql_error',
                ),
                'adm_set_sys_logs_sql_error_file' => array(
                    'type' => 'input',
                    'config' => 'sys.logs.sql_error_file',
                    'class' => 'w100p',
                    'valtype' => 'string',
                ),
                'adm_set_sys_logs_profiler' => array(
                    'type' => 'checkbox',
                    'config' => 'sys.logs.profiler',
                ),
                'adm_set_sys_logs_profiler_file' => array(
                    'type' => 'input',
                    'config' => 'sys.logs.profiler_file',
                    'class' => 'w100p',
                    'valtype' => 'string',
                ),
                'adm_set_sys_logs_cron_file' => array(
                    'type' => 'input',
                    'config' => 'sys.logs.cron_file',
                    'class' => 'w100p',
                    'valtype' => 'string',
                ),
            );

        $this->aFields['acl'] =
            array(
                'adm_set_section_acl' => array(
                    'type' => 'section',
                ),
                'adm_set_acl_create_blog_rating' => array(
                    'type' => 'input',
                    'config' => 'acl.create.blog.rating',
                    'class' => 'w100p',
                    'valtype' => 'string',
                    'empty' => '0',
                ),
                'adm_set_acl_create_comment_rating' => array(
                    'type' => 'input',
                    'config' => 'acl.create.comment.rating',
                    'class' => 'w100p',
                    'valtype' => 'string',
                    'empty' => '0',
                ),
                'adm_set_acl_create_comment_limit_time' => array(
                    'type' => 'input',
                    'config' => 'acl.create.comment.limit_time',
                    'class' => 'w100p',
                    'valtype' => 'string',
                    'empty' => '0',
                ),
                'adm_set_acl_create_comment_limit_time_rating' => array(
                    'type' => 'input',
                    'config' => 'acl.create.comment.limit_time_rating',
                    'class' => 'w100p',
                    'valtype' => 'string',
                    'empty' => '0',
                ), //
                'adm_set_acl_create_topic_limit_time' => array(
                    'type' => 'input',
                    'config' => 'acl.create.topic.limit_time',
                    'class' => 'w100p',
                    'valtype' => 'string',
                    'empty' => '0',
                ),
                'adm_set_acl_create_topic_limit_time_rating' => array(
                    'type' => 'input',
                    'config' => 'acl.create.topic.limit_time_rating',
                    'class' => 'w100p',
                    'valtype' => 'string',
                    'empty' => '0',
                ),
                'adm_set_acl_create_talk_limit_time' => array(
                    'type' => 'input',
                    'config' => 'acl.create.talk.limit_time',
                    'class' => 'w100p',
                    'valtype' => 'string',
                    'empty' => '0',
                ),
                'adm_set_acl_create_talk_limit_time_rating' => array(
                    'type' => 'input',
                    'config' => 'acl.create.talk.limit_time_rating',
                    'class' => 'w100p',
                    'valtype' => 'string',
                    'empty' => '0',
                ),
                'adm_set_acl_create_talk_comment_limit_time' => array(
                    'type' => 'input',
                    'config' => 'acl.create.talk_comment.limit_time',
                    'class' => 'w100p',
                    'valtype' => 'string',
                    'empty' => '0',
                ),
                'adm_set_acl_create_talk_comment_limit_time_rating' => array(
                    'type' => 'input',
                    'config' => 'acl.create.talk_comment.limit_time_rating',
                    'class' => 'w100p',
                    'valtype' => 'string',
                    'empty' => '0',
                ),
                'adm_set_acl_vote_comment_rating' => array(
                    'type' => 'input',
                    'config' => 'acl.vote.comment.rating',
                    'class' => 'w100p',
                    'valtype' => 'string',
                    'empty' => '0',
                ),
                'adm_set_acl_vote_blog_rating' => array(
                    'type' => 'input',
                    'config' => 'acl.vote.blog.rating',
                    'class' => 'w100p',
                    'valtype' => 'string',
                    'empty' => '0',
                ),
                'adm_set_acl_vote_topic_rating' => array(
                    'type' => 'input',
                    'config' => 'acl.vote.topic.rating',
                    'class' => 'w100p',
                    'valtype' => 'string',
                    'empty' => '0',
                ),
                'adm_set_acl_vote_user_rating' => array(
                    'type' => 'input',
                    'config' => 'acl.vote.user.rating',
                    'class' => 'w100p',
                    'valtype' => 'string',
                    'empty' => '0',
                ),
                'adm_set_acl_vote_topic_limit_time' => array(
                    'type' => 'input',
                    'config' => 'acl.vote.topic.limit_time',
                    'class' => 'w100p',
                    'valtype' => 'string',
                    'empty' => '0',
                ),
                'adm_set_acl_vote_comment_limit_time' => array(
                    'type' => 'input',
                    'config' => 'acl.vote.comment.limit_time',
                    'class' => 'w100p',
                    'valtype' => 'string',
                    'empty' => '0',
                ),
            );
    }

    public function Init()
    {
        if (($result = parent::Init())) {
            return $result;
        }
        if ($this->sCurrentEvent != 'site') return;

        //$this->Lang_LoadFile('%%language%%.site_settings');
        $this->_PluginLoadLangFile('%%language%%.site_settings');
        $this->_SetFields();
        $aConfigSet = $this->PluginAceadminpanel_Admin_GetValueArrayByPrefix('config.all.', true);
        foreach ($this->aFields as $sMode => $aModeSet) {
            foreach ($aModeSet as $sName => $aField) {
                if ($aField['type'] !== 'section') {
                    if (!isset($aField['valtype']) OR !in_array($aField['valtype'], array('boolean', 'integer', 'float', 'string'))) {
                        if (isset($aField['valtype']) AND $aField['valtype'] == 'number') {
                            $this->aFields[$sMode][$sName]['valtype'] = 'integer';
                        } else {
                            $this->aFields[$sMode][$sName]['valtype'] = ($aField['type'] == 'checkbox') ? 'boolean'
                                : 'string';
                        }
                    }

                    $sValue = null;

                    // проверка на значение по умолчанию только для скина
                    if ($aField['config'] == 'view.skin') {
                        if (isset($aConfigSet['config.all.' . $aField['config']])) {
                            $sValue = $aConfigSet['config.all.' . $aField['config']];
                        }
                    } else {
                        // сначала смотрим в сохраненных значениях
                        if (isset($aConfigSet['config.all.' . $aField['config']])) {
                            $sValue = $aConfigSet['config.all.' . $aField['config']];
                        } // затем в сохраненным админкой конфиге
                        elseif (Config::Get($this->sPlugin . '.saved.' . $aField['config'])) {
                            $sValue = Config::Get($this->sPlugin . '.saved.' . $aField['config']);
                        }
                        // затем в оригинальном конфиге
                        elseif (Config::Get($aField['config'])) {
                            $sValue = Config::Get($aField['config']);
                        }
                    }
                    $this->aFields[$sMode][$sName]['value'] = $sValue;
                }
            }
        }
    }

    public function EventSite()
    {
        $sParam = $this->GetParam(0);
        if ($sParam=='reset') {
            $this->SetTemplateAction('site_reset');
            return $this->EventSiteReset();
        } else {
            $this->SetTemplateAction('site_settings');
            return $this->EventSiteSetting();
        }
    }

    /* ================ setting ================ */

    public function EventSiteSetting()
    {
        $sMode = $this->GetParam(1);
        if (in_array($sMode, array('base', 'sys', 'acl'))) {
            $this->sMenuNavItemSelect = $sMode;
        } else {
            $this->sMenuNavItemSelect = $sMode = 'base';
        }
        if (isPost('submit_data_save')) {
            $this->SaveConfig($sMode);
        }
        $this->Viewer_Assign('aFields', $this->aFields[$this->sMenuNavItemSelect]);
        $this->Viewer_Assign('sSavedPrefix', $this->sPlugin . '.saved');
    }

    public function SaveConfig($sMode)
    {
        $this->Security_ValidateSendForm();
        $aConfigSet = array();
        $aConfigDel = array();
        foreach ($this->aFields[$sMode] as $sName => $aField) {
            // секции пропускаем
            if ($aField['type'] != 'section') {
                $aConfigField['key'] = 'config.all.' . $aField['config'];
                if (!isset($_POST[$sName]) OR !$_POST[$sName]) {
                    if (isset($aField['empty'])) $aConfigField['val'] = $aField['empty'];
                    else {
                        if ($aField['valtype'] == 'boolean') $val = false;
                        else $val = '';
                    }
                } else {
                    $val = $_POST[$sName];
                    settype($val, $aField['valtype']);
                }
                // если используется значене по умолчанию, то этот параметр не сохраняем
                if ($this->_DefaultConfigVal($val)) {
                    $aConfigDel[] = $aConfigField['key'];
                } else {
                    $aConfigField['val'] = serialize($val);
                    $aConfigSet[] = $aConfigField;
                }
            }
        }

        // сохраняем настройки в базе
        if ($this->PluginAceadminpanel_Admin_SetValueArray($aConfigSet)) {
            $aConfigSet = $this->PluginAceadminpanel_Admin_GetValueArrayByPrefix('config.all.');
            if ($aConfigSet AND $aConfigDel) {
                foreach ($aConfigSet as $nIndex => $aConfigField) {
                    // удаляем ненужные настройки из базы и из массива
                    if (in_array($aConfigField['key'], $aConfigDel)) {
                        $this->PluginAceadminpanel_Admin_DelValue($aConfigField['key']);
                        unset($aConfigSet[$nIndex]);
                    }
                }
            }
            // получаем внешний файл для хранения настроек
            $sDataFile = $this->PluginAceadminpanel_Admin_GetCustomConfigFile();
            if ($sDataFile)
                file_put_contents($sDataFile, serialize($aConfigSet));
            $this->Message('notice', $this->Lang_Get('adm_saved_ok'), null, true);
        } else {
            $this->Message('error', $this->Lang_Get('adm_saved_err'), null, true);
        }
        ACE::HeaderLocation(Router::GetPath('admin') . 'site/settings/' . $this->sMenuNavItemSelect);
    }

    /* ================ reset ================ */

    public function EventSiteReset()
    {
        if (isPost('adm_reset_submit')) {
            $this->_EventSiteResetSubmit();
            $this->Viewer_Assign('submit_cache_save', 1);
        }
    }

    protected function _EventSiteResetSubmit()
    {
        $this->Security_ValidateSendForm();
        if (isPost('adm_cache_clear_data')) $this->Cache_Clean();
        if (isPost('adm_cache_clear_headfiles')) ACE::ClearHeadfilesCache();
        if (isPost('adm_cache_clear_smarty')) ACE::ClearSmartyCache();
        if (isPost('adm_reset_config_data')) $this->_ResetCustomConfig();
        $this->Message('notice', $this->Lang_Get('adm_action_ok'), null, true);
        ACE::HeaderLocation(Router::GetPath('admin') . 'site/reset/');
    }

    protected function _ResetCustomConfig()
    {
        $this->PluginAceadminpanel_Admin_DelValueArrayByPrefix('config.all.');
        $sFileName = $this->PluginAceadminpanel_Admin_GetCustomConfigFile();
        unlink($sFileName);
    }

}

// EOF
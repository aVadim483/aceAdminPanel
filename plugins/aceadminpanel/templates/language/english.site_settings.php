<?php
/*---------------------------------------------------------------------------
 * @Plugin Name: aceAdminPanel
 * @Plugin Id: aceadminpanel
 * @Plugin URI: 
 * @Description: Advanced Administrator's Panel for LiveStreet/ACE
 * @Version: 2.0.354
 * @Author: Vadim Shemarov (aka aVadim)
 * @Author URI: 
 * @LiveStreet Version: 1.0.1
 * @File Name: %%filename%%
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *----------------------------------------------------------------------------
 */


/**
 * Language file for module (English language)
 */
return array(
    'adm_settings_title' => 'Settings',
    'adm_settings_base' => 'Primary',
    'adm_settings_sys' => 'System',
    'adm_settings_acl' => 'Rights',

    'adm_set_section_site' => 'Site',
    'adm_set_view_skin' => 'Site skin',
    'adm_set_view_name' => 'Name of the site',
    'adm_set_view_description' => 'Value of meta-tag description',
    'adm_set_view_keywords' => 'Value of meta-tag keywords',

    'adm_set_section_general' => 'General',
    'adm_set_general_close' => 'Use the private operation site',
    'adm_set_general_reg_invite' => 'Registration is available by invitation only',
    'adm_set_general_reg_activation' => 'When you register to use the activation of users',

    'adm_set_section_sys_lang' => 'Languages',
    'adm_set_lang_current' => 'Current language',
    'adm_set_lang_default' => 'Default language (if there is no phrase in the current language)',

    'adm_set_section_edit' => 'Edit',
    'adm_set_view_tinymce' => 'Use the visual editor TinyMCE',
    'adm_set_view_noindex' => '"Hide" links from websearch engines',
    'adm_set_view_img_resize_width' => 'Up to what size in width (in pixels) of the image to compress the text',
    'adm_set_view_img_max_width' => 'The maximum width of the loaded image in pixels',
    'adm_set_view_img_max_height' => 'The maximum height of the loaded image in pixels',

    'adm_set_section_sys_cookie'=>'Cookies and session',
    'adm_set_sys_cookie_host'=>'Host to set cookies',
    'adm_set_sys_cookie_path'=>'The way to set cookies',
    'adm_set_sys_session_standart'=>'Use standard session mechanism',
    'adm_set_sys_session_name'=>'Session name',
    'adm_set_sys_session_timeout'=>'Session timeout in seconds',
    'adm_set_sys_session_host'=>'Session host in cookies',
    'adm_set_sys_session_path'=>'Session way in cookies',

    'adm_set_section_sys_mail'=>'The settings of mail',
    'adm_set_sys_mail_type'=>'What type of dispatch to use',
    'adm_set_sys_mail_from_email'=>'Address from which all notices sent',
    'adm_set_sys_mail_from_name'=>'The sender\'s name, from which all notices sent',
    'adm_set_sys_mail_charset'=>'How should I encode in letters',
    'adm_set_sys_mail_smtp_host'=>'Settings SMTP - Host',
    'adm_set_sys_mail_smtp_port'=>'Settings SMTP - Port',
    'adm_set_sys_mail_smtp_user'=>'Settings SMTP - User',
    'adm_set_sys_mail_smtp_password'=>'Settings SMTP - Password',
    'adm_set_sys_mail_smtp_auth'=>'Use authentication when sending',
    'adm_set_sys_mail_include_comment'=>'Includes notification of new comments text commentary',
    'adm_set_sys_mail_include_talk'=>'Includes notification of new private messages text messages',

    'adm_set_section_sys_logs' => 'Logging settings',
    'adm_set_sys_logs_sql_query' => 'Включить логгирование всех SQL-запросов',
    'adm_set_sys_logs_sql_query_file' => 'The log file SQL-queries',
    'adm_set_sys_logs_sql_error' => 'Enable logging erroneous SQL-queries',
    'adm_set_sys_logs_sql_error_file' => 'The log file is erroneous SQL-queries',
    'adm_set_sys_logs_profiler' => 'Enable profiling process',
    'adm_set_sys_logs_profiler_file' => 'The log file profiling process',
    'adm_set_sys_logs_cron_file' => 'The log file is run cron-processes',

    'adm_set_section_acl' => 'Settings of access control',
    'adm_set_acl_create_blog_rating' => 'Rating threshold, at which the user can create a team blog',
    'adm_set_acl_create_comment_rating' => 'Rating threshold, at which the user can add comments',
    'adm_set_acl_create_comment_limit_time' => 'Time (sec) between posting comments, 0 if the constraint is not working',
    'adm_set_acl_create_comment_limit_time_rating' => 'Rating above which no longer valid time limit on posting comments',
    'adm_set_acl_create_topic_limit_time' => 'Time (s) between the creation of records, if 0 then the restriction does not work',
    'adm_set_acl_create_topic_limit_time_rating' => 'Rating above which expires the time limit for the creation of records',
    'adm_set_acl_create_talk_limit_time' => 'Time (seconds) between sending internal mail, if 0 then the restriction does not work',
    'adm_set_acl_create_talk_limit_time_rating' => 'Rating above which no longer valid time limit for sending internal mail',
    'adm_set_acl_create_talk_comment_limit_time' => 'Time (seconds) between sending internal mail',
    'adm_set_acl_create_talk_comment_limit_time_rating' => 'Rating above which no longer valid time limit for sending internal mail',
    'adm_set_acl_vote_blog_rating' => 'Rating threshold, at which the user may vote for blog',
    'adm_set_acl_vote_topic_rating' => 'Rating threshold, at which the user may vote for a topic',
    'adm_set_acl_vote_comment_rating' => 'Rating threshold, at which the user may vote for comments',
    'adm_set_acl_vote_user_rating' => 'Порог рейтинга, при котором юзер может голосовать за пользователя',
    'adm_set_acl_vote_topic_limit_time' => 'Limiting the time of voting for the topic (s)',
    'adm_set_acl_vote_comment_limit_time' => 'Limitation of voting for the comment (s)',

);
// EOF

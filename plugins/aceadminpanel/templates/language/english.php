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
    'adm_title' => 'Adminpanel',
    'adm_goto_site' => 'Go to site',
    'adm_need_upgrade' => '<b>WARNING!</b> You need to upgrade the module by clicking on the link below',
    'adm_denied_title' => 'Access error',
    'adm_denied_text' => 'You\'re don\'t have access to this mode',
    'adm_banned1_text' => 'You\'re banned on a site until %%date%%',
    'adm_banned2_text' => 'You\'re banned on a site',
    'adm_banned3_text' => 'Your IP banned on a site',
    'adm_user_login' => 'User login',
    'adm_user_email' => 'User e-mail',
    'adm_user_not_found' => 'User %%user%% not found',
    'adm_user_ip' => 'IP пользователя',
    'adm_user_filter_ip_notice' => 'Example: 83.167.100.46 or 83.167.*.* ',
    'adm_user_filter_regdate_notice' => 'Example: 2009-2 or 2009-3-31',
    'adm_user_filter_email_notice' => 'Enter user email',

    'adm_button_report' => 'Generate report',
    'adm_button_checkin' => 'Include in the report',

    'adm_menu_info' => 'Information',
    'adm_menu_panel' => 'Adminpanel',
    'adm_menu_params' => 'Options',

    'adm_menu_site' => 'Site',
    'adm_menu_statistics' => 'Statistics',
    'adm_menu_plugins' => 'Plugins',
    'adm_menu_delegates' => 'Delegation',
    'adm_menu_settings' => 'Settings',
    'adm_menu_config' => 'Configuration',
    'adm_menu_logs' => 'Logs',
    'adm_menu_close_site' => 'Close the site',
    'adm_menu_cache' => 'Caching',
    'adm_menu_reset' => 'Data reset',
    'adm_menu_reset_cache' => 'Reset cache',
    'adm_menu_reset_config' => 'Reset configuration',

    'adm_menu_themes' => 'Themes',
    'adm_menu_installed' => 'Installed',
    'adm_theme_activate' => 'Activate',

    'adm_menu_languages' => 'Languages',
    'adm_menu_installed' => 'Installed',

    'adm_menu_pages' => 'Pages',
    'adm_menu_pages_list' => 'List',
    'adm_menu_pages_new' => 'New',
    'adm_menu_pages_options' => 'Options',

    'adm_menu_blogs' => 'Blogs',
    'adm_menu_blogs_list' => 'List',

    'adm_menu_users' => 'Users',
    'adm_menu_users_profile' => 'User profile',
    'adm_menu_users_list' => 'List',
    'adm_menu_users_banlist' => 'Banlist',
    'adm_banlist_ids' => 'Users',
    'adm_banlist_ips' => 'IP-addresses',

    'adm_menu_users_fields' => 'Additional fields',

    'adm_menu_additional' => 'Additional',
    'adm_menu_additional_item' => 'Additional ',

    'adm_info_versions' => 'Versions',

    'adm_info_version_php' => 'PHP version',
    'adm_info_version_smarty' => 'Smarty version',
    'adm_info_version_ls' => 'LiveStreet version',
    'adm_info_version_adminpanel' => 'Adminpanel version',

    'adm_site_statistics' => 'Site statistics',
    'adm_site_stat_users' => 'Users',
    'adm_site_stat_blogs' => 'Blogs',
    'adm_site_stat_topics' => 'Topics',
    'adm_site_stat_comments' => 'Comments',

    'adm_site_info' => 'Site info',
    'adm_info_site_url' => 'Site addresss',
    'adm_info_site_skin' => 'Current skin',
    'adm_info_site_jslib' => 'Used Javascript library',
    'adm_info_site_client' => 'Web-client',

    'adm_active_plugins' => 'Active plugins',

    'adm_params_title' => 'Adminpanel settings',
    'adm_plugins_title' => 'Enable/disable plug-ins',

    'adm_users_list' => 'Users',
    'adm_admins_list' => 'Administrators',
    'adm_users_date_reg' => 'Date of registration',
    'adm_users_ip_reg' => 'Registration IP',
    'adm_users_activated' => 'Activated',
    'adm_users_last_activity' => 'Last activity',
    'adm_users_banned' => 'Banned',
    'adm_users_activate' => 'Activate',
    'adm_users_action' => 'Action',
    'adm_users_ban' => 'Ban',
    'adm_users_unban' => 'Unban',
    'adm_cannot_ban_self' => 'You can not ban yourself',
    'adm_cannot_be_banned' => 'Заблокированные пользователи не могут быть администратором',
    'adm_already_added' => 'This user already an administrator',
    'adm_cannot_ban_admin' => 'Cannot ban administrator',
    'adm_cannot_with_admin' => 'Operation is not possible with the user admin',
    'adm_users_del' => 'Delete',
    'adm_users_del_warning' => 'WARNING!<br/>By removing the user, will remove all his blogs, topics, comments, vote. Also removed the comments of other users, written in response to this user',
    'adm_users_del_confirm' => 'Сonfirm the deletion',
    'adm_cannot_del_self' => 'You can not remove yourself',
    'adm_cannot_del_admin' => 'You can not remove an administrator',
    'adm_cannot_del_confirm' => 'You have not confirmed the removal of a user',
    'adm_user_deleted' => 'User %%user%% removed',
    'adm_msg_sent_ok' => 'Message sent successfully',

    'adm_selected_users' => 'Users were selected',
    'adm_users_not_selected' => 'Users not selected',

    'adm_user_voted' => 'Voted',
    'adm_user_voted_title' => 'User voted',
    'adm_user_voted_users' => 'For other users',
    'adm_user_voted_topics' => 'For topics',
    'adm_user_voted_blogs' => 'For blogs',
    'adm_user_voted_comments' => 'For comments',

    'adm_user_votes_title' => 'For the user voted',
    'adm_user_votes_users' => 'In his profile',
    'adm_user_votes_topics' => 'For his topics',
    'adm_user_votes_blogs' => 'For his blogs',
    'adm_user_votes_comments' => 'For his comments',

    'adm_user_wrote_topics' => 'Topics created',
    'adm_user_wrote_comments' => 'Comments written',
    'adm_comment_edit' => 'Comment edit',

    'adm_param_items_per_page' => 'Number of lines per page',
    'adm_param_items_per_page_notice' => 'The number of rows displayed on a page in tabular lists (users, ban lists, etc.)',
    'adm_param_votes_per_page' => 'The number of recent votes in the profile',
    'adm_param_votes_per_page_notice' => 'The number of recent polls that appear in the user profile for each table - a vote for other people, their topics, comments',
    'adm_param_edit_footer' => 'Signature of editable topics/comments',
    'adm_param_edit_footer_notice' => 'Signature that is automatically added when editing topics/comments from the adminpanel',
    'adm_param_vote_value' => 'Power of admin vote',
    'adm_param_vote_value_notice' => 'The value of "enhanced voting", available in the user profile in the adminpanel',

    'adm_vote_error' => 'Error when voting administrator',
    'adm_repeat_vote_error' => 'Error on the second voting Administrator',

    'adm_ban_upto' => 'Ban until',
    'adm_ban_unlim' => 'Permban',
    'adm_ban_for' => 'Ban for',
    'adm_ban_days' => 'days',
    'adm_ban_comment' => 'Comment',

    'adm_pages' => 'Static pages',
    'adm_pages_new' => 'New page',
    'adm_pages_options' => 'Options',
    'adm_page_options_urls' => 'Reserved URLs',
    'adm_page_options_urls_notice' => 'Reserved URLs (separated by commas), which can not be used when creating new pages',

    'adm_themes' => 'Themes',
    'adm_close_open_site' => 'Close/open the site',
    'adm_site_closed' => 'Site closed',
    'adm_site_openned' => 'Site opened',
    'adm_close_site_notice' => 'You can close site from visitors. In this mode only administrators have access to site.',
    'adm_close_site_text_notice' => 'Enter the text visitors will see on the closed site',
    'adm_close_site_file_notice' => 'Or set name of HTML-file, which will be redirected visitors (must be in the root folder)',
    'adm_close_site_text_empty' => 'Text of message cannot be void',
    'adm_close_site_file_empty' => 'Name of file cannot be void',

    'adm_yes' => 'yes',
    'adm_no' => 'no',
    'adm_include' => 'Include',
    'adm_exclude' => 'Exclude',
    'adm_seek' => 'Search',
    'adm_seek_users' => 'Search users',
    'adm_save' => 'Save',
    'adm_reset' => 'Reset',
    'adm_continue' => 'Contiue',
    'adm_saved_ok' => 'The data saved',
    'adm_saved_err' => 'Error saving data',
    'adm_file_not_found' => 'File not found',
    'adm_err_read' => 'Error reading',
    'adm_err_read_dir' => 'Error directory reading',
    'adm_err_read_file' => 'Error file reading',
    'adm_err_copy_file' => 'Error copying file %%file%%',
    'adm_err_wrong_ip' => 'Wrong IP-адрес',
    'adm_config_err_read' => 'Error of reading configuration file',
    'adm_config_err_backup' => 'Error of creating backup-file',
    'adm_config_err_save' => 'Error of saving configuration',
    'adm_config_save_ok' => 'The modified configuration file is saved',
    'adm_themes_err_read' => 'Error themes reading',
    'adm_themes_select_skin' => 'Select theme',
    'adm_themes_activate_skin' => 'Activate theme',
    'adm_themes_activate_label' => 'Select theme for activation',
    'adm_themes_activate_notice' => 'Select theme for activation from list of installed themes',
    'adm_themes_need_files' => 'For correct operation of the theme requires the following files:',
    'adm_themes_need_files_copy' => 'Copy them from the current theme?',
    'adm_themes_changed' => 'Theme changed. Need to refresh the page',
    'adm_activate_language' => 'Activate language',
    'adm_compare_language' => 'Compare language files',
    'adm_languages_select' => 'Select language',
    'adm_languages_activate' => 'Activate',
    'adm_languages_compare' => 'compare',
    'adm_languages_default' => 'Default',
    'adm_languages_activate_label' => 'Select language for activation',
    'adm_languages_activate_notice' => 'Select language for activation from list of installed languages',
    'adm_language_not_found' => 'Language not defined',
    'adm_current_language' => 'Current language',
    'adm_selected_language' => 'Selected language',

    'adm_include_admin' => 'Add administrator',
    'adm_exclude_admin' => 'Remove administrator',

    'adm_select_file' => 'Select file',

    'adm_send_copy_self' => 'Send a copy to self',
    'adm_send_err_to_user' => 'Error of sending messages to the user %%user%%',
    'adm_send_common_message' => 'Overall message',
    'adm_send_separate_messages' => 'Separate messages',
    'adm_send_common_notice' => 'Users will benefit from the overall message and any response to it will see all the other recipients',
    'adm_send_separate_notice' => 'Each user will receive separate messages',

    'adm_logs_title' => 'Logs settings',
    'adm_logs_users_enable_title' => 'User actions log',
    'adm_logs_users_enable_notice' => 'You can enable/disable the logging of user actions',
    'adm_logs_turned_on' => 'Enabled',
    'adm_logs_turned_off' => 'Disabled',
    'adm_logs_users_file' => 'Name of the log file of user actions',
    'adm_logs_users_file_notice' => 'Specify the name of the log file of user actions that will be hosted in your logs',
    'adm_logs_users_debug' => 'Enabled debug info',
    'adm_logs_users_debug_notice' => 'In a log will include debug information (call stack)',
    'adm_logs_users_logins' => 'Enable logs only for those users',
    'adm_logs_users_logins_notice' => 'User logins are listed separated by commas with no spaces. If specified, the logs are maintained only for such users, or is one log for all',

    'adm_cache_title' => 'Cache settings',
    'adm_cache_not_used' => 'Not used',
    'adm_cache_file' => 'File cache',
    'adm_cache_memory' => 'Use Memcached',
    'adm_cache_type' => 'Cache type',
    'adm_cache_type_notice' => 'Enter "none" to disable caching. Option "memory" using memcached',
    'adm_cache_prefix' => 'Cache prefix',
    'adm_cache_prefix_notice' => 'Required if more sites are using shared cache storage',
    'adm_cache_clean' => 'Reset cache',
    'adm_cache_clean_notice' => 'Check if you want to clear the cache',

    'adm_logs_admin_enable_title' => 'Log of administrators actions',
    'adm_logs_admin_enable_notice' => 'You can enable/disable logging administrators actions',
    'adm_logs_admin_file' => 'Name of file administrators actions log',
    'adm_logs_admin_file_notice' => 'Specify the name of the log file operations administrators are placed in directory "logs"',

    'adm_logs_max_size' => 'The maximum size of the log file',
    'adm_logs_max_size_notice' => 'When this size is reached, a copy is created and a new file. If zero, a new file is created every day',
    'adm_logs_max_files' => 'The number of copies of the log files',
    'adm_logs_max_files_notice' => 'Number of backup copies of the log files to be stored on site',

    'adm_blog_edit' => 'Edit blog',
    'adm_blog_delete' => 'Delete blog',
    'adm_blog_del_confirm' => 'Blog &quot;%%blog%%&quot; will be permanently deleted and all its contents. \nContinue?',

    'adm_topic_edit' => 'Edit topic',
    'adm_topic_delete' => 'Delete topic',
    'adm_topic_del_confirm' => 'Topic &quot;%%topic%%&quot; will be permanently deleted and all its contents. \nContinue?',

    'adm_invite_code' => 'Invite code',
    'adm_invite_user_from' => 'Sender',
    'adm_invite_user_to' => 'Reciver',
    'adm_invite_date_add' => 'Created',
    'adm_invite_date_used' => 'Date of use',
    'adm_send_invite_mail' => 'Send invitations by e-mail',
    'adm_make_invite_text' => 'How many invitations you need to create',
    'adm_invite_mode_mail' => 'Send an invitation by e-mail',
    'adm_invite_mode_text' => 'Generate and show invitations',
    'adm_invite_submit' => 'Generate invitations',
    'adm_invaite_mail_empty' => 'You must specify at least one e-mail',
    'adm_invaite_text_empty' => 'The required number of invitations must be greater than zero',
    'adm_invaite_mail_done' => 'New invitations sent out: %%num%%',
    'adm_invaite_text_done' => 'New invitations created: %%num%%',

    'adm_param_check_password' => 'Check the administrator password',
    'adm_param_check_password_notice' => 'When set, it checks the quality of the administrator password on the reliability of',

    'adm_password_quality' => 'You have a very weak password! It is strongly recommended to change the default password!',

    'adm_act_activate' => 'Activate',
    'adm_act_deactivate' => 'Deactivate',

    'adm_action_ok' => 'Command complete',
    'adm_action_err' => 'Error of performing command',

    'adm_cache_title' => 'Cache settings',
    'adm_cache_type' => 'Cache type',
    'adm_cache_type_notice' => 'Type <b>memory</b> used memcached',
    'adm_cache_prefix' => 'Cache prefix',
    'adm_cache_prefix_notice' => 'It must be unique for each site, so that you can keep a few sites with a total cache storage',
    'adm_cache_clear_data' => 'Cleaning the cache data',
    'adm_cache_clear_data_notice' => 'Clearing the cache data storage',
    'adm_cache_clear_headfiles' => 'Clearing the cache js- and css-files',
    'adm_cache_clear_headfiles_notice' => 'Clearing cache-storage of js- and css-files',
    'adm_cache_clear_smarty' => 'Clearing Smarty cache',
    'adm_cache_clear_smarty_notice' => 'Resetting the cache storage file compiled Smarty',

    'adm_reset_config_data' => 'Reset modified configuration settings',
    'adm_reset_config_data_notice' => 'All the parameters that you have changed through the admin panel will be reset to its original value, ie those that are specified with the configuration files',

    'adm_plugin_file_not_found' => 'Plugin file <b>%%file%%</b> not found',
    'adm_plugin_havenot_getversion_method' => 'Required plugin <b>%%plugin%%</b> does not return the version number (no method <b>GetVersion()</b>)',
    'adm_plugin_activation_reqversion_error_eq' => 'The plugin is required to activate the plugin <b>%%plugin%%</b> version <b>%%version%%</b>',
    'adm_plugin_activation_reqversion_error_ge' => 'The plugin is required to activate the plugin <b>%%plugin%%</b> later version<b>%%version%%</b>',
    'adm_plugin_activation_reqversion_error_gt' => 'The plugin is required to activate the plugin <b>%%plugin%%</b> version is not above <b>%%version%%</b>',
    'adm_plugin_activation_reqversion_error_le' => 'The plugin is required to activate the plugin <b>%%plugin%%</b> version of the above <b>%%version%%</b>',
    'adm_plugin_activation_reqversion_error_lt' => 'The plugin is required to activate the plugin <b>%%plugin%%</b> version below <b>%%version%%</b>',

    'adm_plugin_activation_error_php' => 'For a plugin work PHP version must be not less than <b>%%version%%</b>',

    'adm_action_for_admin_only' => 'This action is available only to administrators',

    'adm_cannot_clear_dir' => 'Unable to clear the folder %%dir%%. It is recommended to do this manually',

    'adm_plugin_priority_notice' => 'Plug-ins are loaded in the order in which they are displayed in the table. You can change the boot order of plug-ins by using the arrows in the far right column',
    'adm_plugin_priority_up' => 'Increase the priority',
    'adm_plugin_priority_down' => 'Decrease the priority',

    'adm_execute' => 'Execute',

    'adm_menu_list' => 'List',
    'adm_menu_options' => 'Options',

    'adm_all_plugins' => 'All plugins',
    'adm_active_plugins' => 'Active plugins',
    'adm_inactive_plugins' => 'Inactive plugins',

    'adm_plugin_write_error' => 'File writing error in <b>%%file%%</b>',

    'adm_text_about' =>
    'Author: aVadim<br/>
        E-mail: vadim483@gmail.com<br/>
        ',
    'adm_text_donate' =>
    'For those who want to fit in a noble material and encourage plugin authors,
         announce details for donations: Wallets
        WebMoney <b>Z178319650868</b> or <b>R312496642374</b>.',
);

// EOF

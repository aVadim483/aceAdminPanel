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
 * Языковой файл для модуля (Русский язык)
 */
return array(
    'adm_title' => 'Админпанель',
    'adm_goto_site' => 'Перейти на сайт',
    'adm_need_upgrade' => '<b>ВНИМАНИЕ!</b> Вам необходимо выполнить обновление модуля, кликнув по ссылке ниже',
    'adm_denied_title' => 'Ошибка доступа',
    'adm_denied_text' => 'У вас нет доступа к этому режиму',
    'adm_banned1_text' => 'Вам закрыт доступ к этому сайту до %%date%%',
    'adm_banned2_text' => 'Вам закрыт доступ к этому сайту',
    'adm_banned3_text' => 'Закрыт доступ к этому сайту с вашего IP-адреса',
    'adm_user_login' => 'Логин пользователя',
    'adm_user_email' => 'E-mail пользователя',
    'adm_user_not_found' => 'Пользователь %%user%% не найден',
    'adm_user_ip' => 'IP пользователя',
    'adm_user_filter_ip_notice' => 'Напр., 83.167.100.46 или 83.167.*.* ',
    'adm_user_filter_regdate_notice' => 'Напр., 2009-2 или 2009-3-31',
    'adm_user_filter_email_notice' => 'Укажите email пользователя',

    'adm_button_report' => 'Сформировать отчет',
    'adm_button_checkin' => 'Включить в отчет',

    'adm_menu_info' => 'Информация',
    'adm_menu_panel' => 'Админпанель',
    'adm_menu_params' => 'Параметры',

    'adm_menu_site' => 'Сайт',
    'adm_menu_statistics' => 'Статистика',
    'adm_menu_plugins' => 'Плагины',
    'adm_menu_delegates' => 'Делегирование',
    'adm_menu_settings' => 'Настройки',
    'adm_menu_config' => 'Конфигурация',
    'adm_menu_logs' => 'Журналы',
    'adm_menu_close_site' => 'Закрыть сайт',
    'adm_menu_cache' => 'Кеширование',
    'adm_menu_reset' => 'Сброс данных',
    'adm_menu_reset_cache' => 'Сброс кеша',
    'adm_menu_reset_config' => 'Сброс конфигурации',

    'adm_menu_themes' => 'Темы',
    'adm_menu_installed' => 'Установленные',
    'adm_theme_activate' => 'Активировать',

    'adm_menu_languages' => 'Языки',
    'adm_menu_installed' => 'Установленные',

    'adm_menu_pages' => 'Страницы',
    'adm_menu_pages_list' => 'Список',
    'adm_menu_pages_new' => 'Новая',
    'adm_menu_pages_options' => 'Опции',

    'adm_menu_blogs' => 'Блоги',
    'adm_menu_blogs_list' => 'Список',

    'adm_menu_users' => 'Пользователи',
    'adm_menu_users_profile' => 'Профиль пользователя',
    'adm_menu_users_list' => 'Список',
    'adm_menu_banlist' => 'Бан-лист',
    'adm_banlist_ids' => 'Пользователи',
    'adm_banlist_ips' => 'IP-адреса',
    'adm_banlist_add' => 'Добавить в бан-лист',

    'adm_menu_users_fields' => 'Дополнительные поля',

    'adm_menu_additional' => 'Дополнительно',
    'adm_menu_db' => 'Контроль БД',
    'adm_menu_additional_item' => 'Дополнительно ',

    'adm_info_versions' => 'Версии',

    'adm_info_version_php' => 'Версия PHP',
    'adm_info_version_smarty' => 'Версия Smarty',
    'adm_info_version_ls' => 'Версия LiveStreet',
    'adm_info_version_adminpanel' => 'Версия админпанели',

    'adm_site_statistics' => 'Статистика сайта',
    'adm_site_stat_users' => 'Пользователей',
    'adm_site_stat_blogs' => 'Блогов',
    'adm_site_stat_topics' => 'Топиков',
    'adm_site_stat_comments' => 'Комментариев',

    'adm_site_info' => 'Информация о сайте',
    'adm_info_site_url' => 'Адрес сайта',
    'adm_info_site_skin' => 'Текущий скин',
    'adm_info_site_jslib' => 'Используемая библиотека javascript',
    'adm_info_site_client' => 'Веб-клиент',

    'adm_active_plugins' => 'Активные плагины',

    'adm_params_title' => 'Параметры Админпанели',
    'adm_plugins_title' => 'Включение/выключение плагинов',

    'adm_users_list' => 'Пользователи',
    'adm_admins_list' => 'Администраторы',
    'adm_users_date_reg' => 'Дата регистрации',
    'adm_users_ip_reg' => 'IP регистрации',
    'adm_users_activated' => 'Активирован',
    'adm_users_last_activity' => 'Последняя активность',
    'adm_users_banned' => 'Забанен',
    'adm_users_activate' => 'Активировать',
    'adm_users_action' => 'Действие',
    'adm_users_ban' => 'Забанить',
    'adm_users_unban' => 'Разбанить',
    'adm_cannot_ban_self' => 'Вы не можете забанить сами себя',
    'adm_cannot_be_banned' => 'Нельзя сделать администратором забаненного пользователя',
    'adm_already_added' => 'Этот пользователь уже администратор',
    'adm_cannot_ban_admin' => 'Нельзя забанить администратора',
    'adm_cannot_with_admin' => 'Операция невозможна с пользователем admin',
    'adm_users_del' => 'Удалить',
    'adm_users_del_warning' => 'ВНИМАНИЕ!<br/>Удаляя пользователя, Вы удалите ВСЕ его блоги, топики, комментарии, голосования. Могут быть удалены также комментарии других пользователей, написанные в ответ на удаляемые комментарии',
    'adm_users_del_confirm' => 'Подтвердите удаление',
    'adm_cannot_del_self' => 'Вы не можете удалить сами себя',
    'adm_cannot_del_admin' => 'Вы не можете удалить администратора',
    'adm_cannot_del_confirm' => 'Вы не подтвердили удаление пользователя',
    'adm_user_deleted' => 'Пользователь %%user%% удален',
    'adm_msg_sent_ok' => 'Сообщение успешно отправлено',

    'adm_selected_users' => 'Выбраны пользователи',
    'adm_users_not_selected' => 'Пользователи не выбраны',

    'adm_user_voted' => 'Голосовал',
    'adm_user_voted_title' => 'Пользователь голосовал',
    'adm_user_voted_users' => 'За других пользователей',
    'adm_user_voted_topics' => 'За топики',
    'adm_user_voted_blogs' => 'За блоги',
    'adm_user_voted_comments' => 'За комментарии',

    'adm_user_votes_title' => 'За пользователя голосовали',
    'adm_user_votes_users' => 'В его профиле',
    'adm_user_votes_topics' => 'За его топики',
    'adm_user_votes_blogs' => 'За его блоги',
    'adm_user_votes_comments' => 'За его комментарии',

    'adm_user_wrote_topics' => 'Написал топиков',
    'adm_user_wrote_comments' => 'Написал комментариев',
    'adm_comment_edit' => 'Редактирование комментария',

    'adm_user_profile_link' => 'Администрирование',

    'adm_param_items_per_page' => 'Число строк на странице',
    'adm_param_items_per_page_notice' => 'Число строк, выводимых на странице в табличных списках (пользователи, бан листы и т.д.)',
    'adm_param_votes_per_page' => 'Число последних голосований в профайле',
    'adm_param_votes_per_page_notice' => 'Число последних голосований, выводимых в профайле пользователя в каждой таблице - голосование за других пользователей, их топики, комментарии',
    'adm_param_edit_footer' => 'Подпись редактируемых топиков/комментариев',
    'adm_param_edit_footer_notice' => 'Подпись, которая автоматически будет добавляться при редактировании топиков/комментариев из Админпанели',
    'adm_param_vote_value' => 'Сила администраторского голоса',
    'adm_param_vote_value_notice' => 'Значение "усиленного голосования", доступного в профайле пользователя в Админпанели',

    'adm_vote_error' => 'Ошибка при голосовании администратора',
    'adm_repeat_vote_error' => 'Ошибка при повторном голосовании администратора',

    'adm_ban_period' => 'Срок бана',
    'adm_ban_upto' => 'Бан до',
    'adm_ban_unlim' => 'Бан бессрочный',
    'adm_ban_for' => 'Бан на',
    'adm_ban_days' => 'дней',
    'adm_ban_comment' => 'Комментарий',

    'adm_pages' => 'Статические страницы',
    'adm_pages_new' => 'Новая страница',
    'adm_pages_options' => 'Опции',
    'adm_page_options_urls' => 'Зарезервированные URLs',
    'adm_page_options_urls_notice' => 'Зарезервированные URLs (через запятую), которые нельзя использовать при создании новых страниц',

    'adm_themes' => 'Темы',
    'adm_close_open_site' => 'Закрыть/открыть сайт',
    'adm_site_closed' => 'Cайт закрыт',
    'adm_site_openned' => 'Сайт открыт',
    'adm_close_site_notice' => 'Вы можете закрыть сайт от посетителей. В этом режиме только администраторы имеют доступ к сайту.',
    'adm_close_site_text_notice' => 'Укажите текст, который будут видеть посетители на закрытом сайте',
    'adm_close_site_file_notice' => 'Или укажите имя HTML-файла, на который будут перенаправляться посетители (должен находиться в корневой папке)',
    'adm_close_site_text_empty' => 'Текст сообщения не может быть пустым',
    'adm_close_site_file_empty' => 'Имя файла не может быть пустым',

    'adm_db_check_deleted_blogs' => 'Проверка удаленных блогов',
    'adm_db_check_blogs_joined' => 'Удаленные блоги, в которых состоят пользователи',
    'adm_db_check_blogs_comments_online' => 'Удаленные блоги, с онлайн-комментариями',
    'adm_db_clear_unlinked_blogs' => 'Удалить связи',

    'adm_yes' => 'да',
    'adm_no' => 'нет',
    'adm_include' => 'Добавить',
    'adm_exclude' => 'Исключить',
    'adm_seek' => 'Искать',
    'adm_seek_users' => 'Искать пользователей',
    'adm_save' => 'Сохранить',
    'adm_reset' => 'Сброс',
    'adm_continue' => 'Продолжить',
    'adm_saved_ok' => 'Данные сохранены',
    'adm_saved_err' => 'Ошибка сохранения данных',
    'adm_file_not_found' => 'Файл не найден',
    'adm_err_read' => 'Ошибка чтения',
    'adm_err_read_dir' => 'Ошибка чтения папки',
    'adm_err_read_file' => 'Ошибка чтения файла',
    'adm_err_copy_file' => 'Ошибка копирования файла %%file%%',
    'adm_err_wrong_ip' => 'Неверный IP-адрес',
    'adm_config_err_read' => 'Ошибка чтения файла конфигурации',
    'adm_config_err_backup' => 'Ошибка создания бэкап-файла',
    'adm_config_err_save' => 'Ошибка сохранения файла конфигурации',
    'adm_config_save_ok' => 'Измененный файл конфигурации сохранен',
    'adm_themes_err_read' => 'Ошибка чтения тем',
    'adm_themes_select_skin' => 'Выбрать тему',
    'adm_themes_activate_skin' => 'Активировать тему',
    'adm_themes_activate_label' => 'Выберите тему для активации',
    'adm_themes_activate_notice' => 'Выберите тему для активации из списка установленных тем',
    'adm_themes_need_files' => 'Для корректной работы темы необходимы следующие файлы:',
    'adm_themes_need_files_copy' => 'Копировать их из текущей темы?',
    'adm_themes_changed' => 'Тема изменена. Необходимо обновить страницу',
    'adm_activate_language' => 'Активировать язык',
    'adm_compare_language' => 'Сравнить языковые файлы',
    'adm_languages_select' => 'Выбрать язык',
    'adm_languages_activate' => 'Активировать',
    'adm_languages_compare' => 'Сравнить',
    'adm_languages_default' => 'По умолчанию',
    'adm_languages_activate_label' => 'Выберите язык для активации',
    'adm_languages_activate_notice' => 'Выберите язык для активации из списка установленных языков',
    'adm_language_not_found' => 'Язык не определен',
    'adm_current_language' => 'Текущий язык',
    'adm_selected_language' => 'Выбран язык',

    'adm_include_admin' => 'Добавить администратора',
    'adm_exclude_admin' => 'Исключить администратора',

    'adm_select_file' => 'Выбрать файл',

    'adm_send_copy_self' => 'Отослать копию себе',
    'adm_send_err_to_user' => 'Ошибка отправки сообщения пользователю %%user%%',
    'adm_send_common_message' => 'Общее сообщение',
    'adm_send_separate_messages' => 'Отдельные сообщения',
    'adm_send_common_notice' => 'Пользователи получат общее сообщение и любой ответ на него будут видеть все остальные получатели',
    'adm_send_separate_notice' => 'Каждый пользователь получит отдельное персональное сообщение',

    'adm_logs_title' => 'Настройки журналов',
    'adm_logs_users_enable_title' => 'Журнал действий пользователей',
    'adm_logs_users_enable_notice' => 'Вы можете включить/выключить ведение журнала действий пользователей',
    'adm_logs_turned_on' => 'Включено',
    'adm_logs_turned_off' => 'Выключено',
    'adm_logs_users_file' => 'Имя файла журнала действий пользователей',
    'adm_logs_users_file_notice' => 'Укажите имя файла журнала действий пользователей, размещаемого в папке logs',
    'adm_logs_users_debug' => 'Включать отладочную информацию',
    'adm_logs_users_debug_notice' => 'В журнал будет включаться отладочная информация (стек вызовов)',
    'adm_logs_users_logins' => 'Включить журналы только для этих пользователей',
    'adm_logs_users_logins_notice' => 'Перечисляются логины пользователей через запятую без пробелов. Если заданы, то журналы ведутся только для этих пользователей, иначе ведется единый журнал для всех',

    'adm_cache_title' => 'Настройки кеширования',
    'adm_cache_not_used' => 'Не используется',
    'adm_cache_file' => 'Файловый кеш',
    'adm_cache_memory' => 'Использовать Memcached',
    'adm_cache_type' => 'Тип кеширования',
    'adm_cache_type_notice' => 'Укажите "none" для отключения кеширования. Вариант "memory" использует memcached',
    'adm_cache_prefix' => 'Префикс кеширования',
    'adm_cache_prefix_notice' => 'Необходим, если несколько сайтов используют общим кеш-хранилище',
    'adm_cache_clean' => 'Сброс кеша',
    'adm_cache_clean_notice' => 'Установите, если хотите сбросить кеш',

    'adm_logs_admin_enable_title' => 'Журнал действий администраторов',
    'adm_logs_admin_enable_notice' => 'Вы можете включить/выключить ведение журнала действий администраторов',
    'adm_logs_admin_file' => 'Имя файла журнала действий администраторов',
    'adm_logs_admin_file_notice' => 'Укажите имя файла журнала действий администраторов, размещаемого в папке logs',

    'adm_logs_max_size' => 'Максимальный размер лог-файла',
    'adm_logs_max_size_notice' => 'При достижении этого размера, создается копия и создается новый файл. Если ноль, то новый файл создается каждые сутки',
    'adm_logs_max_files' => 'Число копий лог-файлов',
    'adm_logs_max_files_notice' => 'Количество архивных копий лог-файлов, сохраняемых на сайте',

    'adm_blog_edit' => 'Редактировать блог',
    'adm_blog_delete' => 'Удалить блог',
    'adm_blog_del_confirm' => 'Блог &quot;%%blog%%&quot; будет удален навсегда со всем его содержимым. \nПродолжить?',

    'adm_topic_edit' => 'Редактировать топик',
    'adm_topic_delete' => 'Удалить топик',
    'adm_topic_del_confirm' => 'Топик &quot;%%topic%%&quot; будет удален навсегда со всем его содержимым. \nПродолжить?',

    'adm_menu_invites' => 'Инвайты',
    'adm_invite_code' => 'Код приглашения',
    'adm_invite_user_from' => 'Отправитель',
    'adm_invite_user_to' => 'Получатель',
    'adm_invite_date_add' => 'Дата создания',
    'adm_invite_date_used' => 'Дата использования',
    'adm_send_invite_mail' => 'Выслать приглашения по e-mail',
    'adm_make_invite_text' => 'Сколько приглашений надо создать',
    'adm_invite_mode_mail' => 'Отправить приглашения по e-mail',
    'adm_invite_mode_text' => 'Сгенерировать и показать приглашения',
    'adm_invite_submit' => 'Сгенерировать приглашения',
    'adm_invaite_mail_empty' => 'Необходимо указать хотя бы один e-mail',
    'adm_invaite_text_empty' => 'Количество требуемых приглашений должно быть больше нуля',
    'adm_invaite_mail_done' => 'Разослано новых приглашений: %%num%%',
    'adm_invaite_text_done' => 'Создано новых приглашений: %%num%%',
    'adm_invaite_deleted' => 'Удалено приглашений: %%num%%',

    'adm_param_check_password' => 'Проверять пароль администратора',
    'adm_param_check_password_notice' => 'Если задано, то проверяется качество пароля администратора на надежность',

    'adm_password_quality' => 'У Вас очень слабый пароль! Настоятельно рекомендуется изменить текущий пароль!',

    'adm_act_activate' => 'Активировать',
    'adm_act_deactivate' => 'Деактивировать',

    'adm_action_ok' => 'Команда выполнена',
    'adm_action_err' => 'Ошибка выполнения команды',

    'adm_cache_title' => 'Параметры кеширования',
    'adm_cache_type' => 'Тип кеширования',
    'adm_cache_type_notice' => 'Тип <b>memory</b> использует memcached',
    'adm_cache_prefix' => 'Префикс кеширования',
    'adm_cache_prefix_notice' => 'Должен быть уникальным для каждого сайта, чтобы можно было держать несколько сайтов с общим кеш-хранилищем',
    'adm_cache_clear_data' => 'Очистка кеша данных',
    'adm_cache_clear_data_notice' => 'Сброс кеш-хранилища данных',
    'adm_cache_clear_headfiles' => 'Очистка кеша js- и css-файлов',
    'adm_cache_clear_headfiles_notice' => 'Сброс кеш-хранилища js- и css-файлов',
    'adm_cache_clear_smarty' => 'Очистка кеша Smarty',
    'adm_cache_clear_smarty_notice' => 'Сброс кеш-хранилища компилированных файлов Smarty',

    'adm_reset_config_data' => 'Сброс измененных параметров конфигурации',
    'adm_reset_config_data_notice' => 'Все параметры, которые Вы меняли через Админпанель, будут сброшены в первоначальное значение, т.е. те, что заданны в конфигурационных файлах',

    'adm_plugin_file_not_found' => 'Файл плагина <b>%%file%%</b> не найден',
    'adm_plugin_havenot_getversion_method' => 'Требуемый плагин <b>%%plugin%%</b> не возвращает номер версии (нет метода <b>GetVersion()</b>)',
    'adm_plugin_activation_reqversion_error_eq' => 'Для работы плагина необходим активированный плагин <b>%%plugin%%</b> версии <b>%%version%%</b>',
    'adm_plugin_activation_reqversion_error_ge' => 'Для работы плагина необходим активированный плагин <b>%%plugin%%</b> версии не ниже <b>%%version%%</b>',
    'adm_plugin_activation_reqversion_error_gt' => 'Для работы плагина необходим активированный плагин <b>%%plugin%%</b> версии выше <b>%%version%%</b>',
    'adm_plugin_activation_reqversion_error_le' => 'Для работы плагина необходим активированный плагин <b>%%plugin%%</b> версии не выше <b>%%version%%</b>',
    'adm_plugin_activation_reqversion_error_lt' => 'Для работы плагина необходим активированный плагин <b>%%plugin%%</b> версии ниже <b>%%version%%</b>',

    'adm_plugin_activation_error_php' => 'Для работы плагина необходима версии PHP не ниже <b>%%version%%</b>',

    'adm_action_for_admin_only' => 'Это действие доступно только администраторам',

    'adm_cannot_clear_dir' => 'Невозможно очистить папку %%dir%%. Рекомендуется сделать это вручную',

    'adm_plugin_priority_notice' => 'Плагины будут загружаться в том порядке, в котором они выводятся в таблице. Вы можете изменить порядок загрузки плагинов, используя стрелки в правом крайнем столбце',
    'adm_plugin_priority_up' => 'Увеличить приоритет',
    'adm_plugin_priority_down' => 'Уменьшить приоритет',

    'adm_execute' => 'Выполнить',

    'adm_menu_list' => 'Список',
    'adm_menu_options' => 'Опции',

    'adm_all_plugins' => 'Все плагины',
    'adm_active_plugins' => 'Активные плагины',
    'adm_inactive_plugins' => 'Неактивные плагины',

    'adm_plugin_write_error' => 'Ошибка записи в файл <b>%%file%%</b>',

    'adm_text_about' =>
    'Author: aVadim<br/>
        E-mail: vadim483@gmail.com<br/>
        ',
    'adm_text_donate' =>
    'Для тех, кто в благородном порыве желает материально поощрить автора плагина,
        сообщаю реквизиты для добровольных пожертвований: кошельки
        WebMoney <b>Z178319650868</b> или <b>R312496642374</b>.',
);

// EOF
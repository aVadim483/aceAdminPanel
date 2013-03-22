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
    'adm_settings_title' => 'Настройки',
    'adm_settings_base' => 'Основные',
    'adm_settings_sys' => 'Системные',
    'adm_settings_acl' => 'Права',

    'adm_set_section_site' => 'Сайт',
    'adm_set_view_skin' => 'Скин (оформление) сайта',
    'adm_set_view_name' => 'Название сайта',
    'adm_set_view_description' => 'Значение meta-тега description',
    'adm_set_view_keywords' => 'Значение meta-тега keywords',

    'adm_set_section_general' => 'Общие',
    'adm_set_general_close' => 'Использовать закрытый режим работы сайта',
    'adm_set_general_reg_invite' => 'Регистрация доступна только по приглашениям',
    'adm_set_general_reg_activation' => 'При регистрации использовать активацию пользователей',

    'adm_set_section_sys_lang' => 'Языки',
    'adm_set_lang_current' => 'Текущий язык',
    'adm_set_lang_default' => 'Язык по умолчанию (если нет фразы на текущем языке)',

    'adm_set_section_edit' => 'Редактирование',
    'adm_set_view_tinymce' => 'Использовать визуальный редактор TinyMCE',
    'adm_set_view_noindex' => '"Прятать" ссылки от поисковиков',
    'adm_set_view_img_resize_width' => 'До какого размера по ширине (в пикселях) ужимать картинки в тексте',
    'adm_set_view_img_max_width' => 'Максимальная ширина загружаемых изображений в пикселях',
    'adm_set_view_img_max_height' => 'Максимальная высота загружаемых изображений в пикселях',

    'adm_set_section_sys_cookie'=>'Куки и сессии',
    'adm_set_sys_cookie_host'=>'Хост для установки куков',
    'adm_set_sys_cookie_path'=>'Путь для установки куков',
    'adm_set_sys_session_standart'=>'Использовать стандартный механизм сессий',
    'adm_set_sys_session_name'=>'Имя сессии',
    'adm_set_sys_session_timeout'=>'Тайм-аут сессии в секундах',
    'adm_set_sys_session_host'=>'Хост сессии в куках',
    'adm_set_sys_session_path'=>'Путь сессии в куках',

    'adm_set_section_sys_mail'=>'Настройки почтовых отправлений',
    'adm_set_sys_mail_type'=>'Какой тип отправки использовать',
    'adm_set_sys_mail_from_email'=>'Адрес с которого отправляются все уведомления',
    'adm_set_sys_mail_from_name'=>'Имя отправителя, от которого отправляются все уведомления',
    'adm_set_sys_mail_charset'=>'Какую кодировку использовать в письмах',
    'adm_set_sys_mail_smtp_host'=>'Настройки SMTP - хост',
    'adm_set_sys_mail_smtp_port'=>'Настройки SMTP - порт',
    'adm_set_sys_mail_smtp_user'=>'Настройки SMTP - пользователь',
    'adm_set_sys_mail_smtp_password'=>'Настройки SMTP - пароль',
    'adm_set_sys_mail_smtp_auth'=>'Использовать авторизацию при отправке',
    'adm_set_sys_mail_include_comment'=>'Включает в уведомление о новых комментах текст коммента',
    'adm_set_sys_mail_include_talk'=>'Включает в уведомление о новых личных сообщениях текст сообщения',

    'adm_set_section_sys_logs' => 'Настройки логгирования (журналов)',
    'adm_set_sys_logs_sql_query' => 'Включить логгирование всех SQL-запросов',
    'adm_set_sys_logs_sql_query_file' => 'Файл журнала SQL-запросов',
    'adm_set_sys_logs_sql_error' => 'Включить логгирование ошибочных SQL-запросов',
    'adm_set_sys_logs_sql_error_file' => 'Файл журнала ошибочных SQL-запросов',
    'adm_set_sys_logs_profiler' => 'Включить профилирование процессов',
    'adm_set_sys_logs_profiler_file' => 'Файл журнала профилирования процессов',
    'adm_set_sys_logs_cron_file' => 'Файл журнала запуска крон-процессов',

    'adm_set_section_acl' => 'Настройки контроля доступа',
    'adm_set_acl_create_blog_rating' => 'Порог рейтинга, при котором юзер может создать коллективный блог',
    'adm_set_acl_create_comment_rating' => 'Порог рейтинга, при котором юзер может добавлять комментарии',
    'adm_set_acl_create_comment_limit_time' => 'Время (сек) между постингом комментариев, если 0 то ограничение не работает',
    'adm_set_acl_create_comment_limit_time_rating' => 'Рейтинг, выше которого перестаёт действовать ограничение по времени на постинг комментариев',
    'adm_set_acl_create_topic_limit_time' => 'Время (сек) между созданием записей, если 0 то ограничение не работает',
    'adm_set_acl_create_topic_limit_time_rating' => 'Рейтинг, выше которого перестаёт действовать ограничение по времени на создание записей',
    'adm_set_acl_create_talk_limit_time' => 'Время (сек) между отправкой внутренней почты, если 0 то ограничение не работает',
    'adm_set_acl_create_talk_limit_time_rating' => 'Рейтинг, выше которого перестаёт действовать ограничение по времени на создание писем по внутренней почте',
    'adm_set_acl_create_talk_comment_limit_time' => 'Время (сек) между отправкой внутренней почты',
    'adm_set_acl_create_talk_comment_limit_time_rating' => 'Рейтинг, выше которого перестаёт действовать ограничение по времени на отправку ответов по внутренней почте',
    'adm_set_acl_vote_blog_rating' => 'Порог рейтинга, при котором юзер может голосовать за блог',
    'adm_set_acl_vote_topic_rating' => 'Порог рейтинга, при котором юзер может голосовать за топик',
    'adm_set_acl_vote_comment_rating' => 'Порог рейтинга, при котором юзер может голосовать за комментарии',
    'adm_set_acl_vote_user_rating' => 'Порог рейтинга, при котором юзер может голосовать за пользователя',
    'adm_set_acl_vote_topic_limit_time' => 'Ограничение времени голосования за топик (сек)',
    'adm_set_acl_vote_comment_limit_time' => 'Ограничение времени голосования за комментарий (сек)',

);
// EOF
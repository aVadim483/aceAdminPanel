<?php
/*---------------------------------------------------------------------------
 * @Plugin Name: aceAdminPanel
 * @Plugin Id: aceadminpanel
 * @Plugin URI: 
 * @Description: Advanced Administrator's Panel for LiveStreet/ACE
 * @Version: 1.5.251
 * @Author: Vadim Shemarov (aka aVadim)
 * @Author URI: 
 * @LiveStreet Version: 0.5
 * @File Name: config.php
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *----------------------------------------------------------------------------
 */

if (!class_exists('Config')) die('Hacking attempt!');

if (defined('ACEADMINPANEL_VERSION')) return array();

define('ACEADMINPANEL_VERSION', '2.0-dev');
define('ACEADMINPANEL_VERSION_BUILD', '312');

$config = array('version' => ACEADMINPANEL_VERSION . '.' . ACEADMINPANEL_VERSION_BUILD);

/***
 * Проверка URL действий администратора
 * Если задано, то проверяется URL действия администратора.
 * Этот параметр увеличивает безопасность.
 */
$config['check_url'] = false;

/***
 * Использовать "выплывающую" иконку меню
 */
$config['icon_menu'] = true;

/***
 * Разрешить админу голосовать несколько раз
 */
$config['admin_many_votes'] = true;

/***
 *
 */
$config['autoloader_error'] = false;

// определение таблиц
Config::Set('db.table.adminset', '___db.table.prefix___adminset');
Config::Set('db.table.adminban', '___db.table.prefix___adminban');
Config::Set('db.table.adminips', '___db.table.prefix___adminips');

define('ROUTE_PAGE_ADMIN', 'admin');

Config::Set('router.page.less', 'PluginAceadminpanel_ActionLess');

Config::Set('head.rules.admin',
            array(
                 'path' => '___path.root.web___' . '/admin/',
                 'js' => array(
                     'exclude' => array(
                         "___path.static.skin___/js/favourites.js",
                         "___path.static.skin___/js/questions.js",
                     )
                 )
            ));

Config::Set('head.admin.css',
            array(
                 "___path.static.skin___/css/admin.css?v=" . $config['version'],
                 "___path.static.skin___/css/style.css?v=1",
                 "___path.static.skin___/css/Roar.css",
                 "___path.static.skin___/css/piechart.css",
                 "___path.static.skin___/css/Autocompleter.css",
                 "___path.static.skin___/css/prettify.css",
            ));

Config::Set('head.admin.js',
            array(
                "___path.static.skin___/js/admin.js?v=" . $config['version'],
                 "___path.static.skin___/js/vote.js",
                 "___path.static.skin___/js/favourites.js",
                 "___path.static.skin___/js/questions.js",
                 "___path.static.skin___/js/block_loader.js",
                 "___path.static.skin___/js/friend.js",
                 "___path.static.skin___/js/blog.js",
                 "___path.static.skin___/js/other.js?v=" . $config['version'],
                 "___path.static.skin___/js/login.js",
                 "___path.static.skin___/js/panel.js",
                 "___path.static.skin___/js/vote.js",
            ));

/***
 * Совместимость с Yii
 */
$config['autoloader_yii'] = true;

/***
 * Скин админпанели
 */
$config['skin'] = 'new';

/***
 * Пользовательская конфигурация
 */
$config['custom_config'] = array(
    'enable' => false,                                      // разрешить/запретить пользовательские конфигурации
    'path'  => '___path.root.server___/config/plugins',     // путь к пользовательским конфигурациям плагинов
    'plugins' => true,                                      // подгружать пользовательские конфигурации плагинов
    'saved' => true,                                        // подгружать сохраненные конфигурации плагинов
);

$config['tmp']['path']['use'] = false; // использовать единую папку для всех временных файлов
$config['tmp']['path']['root'] = '___path.root.server___/_tmp/'; // общая папка для всех временных файлов

$config['tmp']['dir']['sys'] = 'sys/'; // папка для файлового кеша, также используется для временных картинок
$config['tmp']['dir']['tpl']['compiled'] = 'smarty/compiled/'; // папка для скомпилированных шаблонов Smarty
$config['tmp']['dir']['tpl']['cache'] = 'smarty/cache/'; // папка для кеша Smarty
$config['tmp']['dir']['log'] = 'log/'; // папка для лог-файлов

return $config;

// EOF
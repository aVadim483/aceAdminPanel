<?php
/*---------------------------------------------------------------------------
 * @Plugin Name: aceAdminPanel
 * @Plugin Id: aceadminpanel
 * @Plugin URI: 
 * @Description: Advanced Administrator's Panel for LiveStreet/ACE
 * @Version: 1.5.210
 * @Author: Vadim Shemarov (aka aVadim)
 * @Author URI: 
 * @LiveStreet Version: 0.5
 * @File Name: config.api.php
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *----------------------------------------------------------------------------
 */

if (!class_exists('Config')) die('Hacking attempt!');

/**
 * Разрешены ли ф-ции API
 *
 * Допустимые значения:
 *   true - разрешены все функции
 *   false - запрещены все функции
 *   array(_список_функций_) - разрешены только перечисленные функции
 *
 * Например:
 *
 * $config['api'] = true; // разрешены все функции API
 * $config['api'] = array('IsUser', 'GetUserLogin'); // разрешены только две указанные функции, остальные функции запрещены
 *
 */

$config['api'] = false;

return $config;

// EOF
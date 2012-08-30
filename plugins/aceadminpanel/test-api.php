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
 * @File Name: test-api.php
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *----------------------------------------------------------------------------
 */

/**
 * Демонстрация работы хелпера API
 */
require_once('../../plugins/aceadminpanel/include/adm_helper_api.php');

if (isset($_POST['api_do_login'])) {
    $sLogin = $_POST['api_login'];
    $sPassword = $_POST['api_password'];
    HelperApi::Login($sLogin, $sPassword);
}
elseif (isset($_POST['api_do_logout'])) {
    HelperApi::Logout();
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <title>Демонстрация работы хелпера API</title>
        <style type="text/css">
            table {border: 1px solid #111; border-collapse: collapse;}
            td {border: 1px solid #111; padding: 4px;}
            .ulogout {color: #f00; font-weight: bold;}
            .ulogin {color: #090; font-weight: bold;}
        </style>
    </head>
    <body>
        <p>Демонстрация работы хелпера API</p>
        <?php if (!HelperApi::IsUser()) : ?>
        <div class="ulogout">Пользователь не залогинен</div>
        <form action="" method="post">
            <table>
                <tr>
                    <td>Логин:</td>
                    <td><input type="text" name="api_login" /></td>
                </tr>
                <tr>
                    <td>Пароль:</td>
                    <td><input type="password" name="api_password" /></td>
                </tr>
            </table>
            <br/>
            <input type="submit" name="api_do_login" value="Login" />
        </form>
        <?php else : ?>
        <div class="ulogin">Пользователь залогинен</div>
        <table>
            <tr>
                <td>Логин пользователя</td>
                <td><?php echo HelperApi::GetUserLogin(); ?></td>
            </tr>
            <tr>
                <td>Является администратором</td>
                <td><?php if (HelperApi::IsAdministrator()) echo 'Да'; else 'Нет'; ?></td>
            </tr>
            <tr>
                <td>Текущий скин</td>
                <td><?php echo HelperApi::GetConfig('view.skin'); ?></td>
            </tr>
        </table>
        <br/>
        <form action="" method="post">
            <input type="submit" name="api_do_logout" value="Logout" />
        </form>
        <?php endif ?>
    </body>
</html>

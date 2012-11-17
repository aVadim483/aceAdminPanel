<?php

class ACE_Config
{
    static protected $sCustomConfigPath;
    static protected $bCustomConfigLoaded = false;

    /**
     * Чтение списка активных плагинов
     *
     * @return array
     */
    static protected function _getActivePlugins()
    {
        $sPluginsFile = Config::Get('path.root.server') . '/plugins/' . Config::Get('sys.plugins.activation_file');
        $aPlugins = @file($sPluginsFile);
        if (is_array($aPlugins)) {
            $aPlugins = array_map('trim', $aPlugins);
            foreach ($aPlugins as $nKey => $sPlugin) {
                if (!preg_match('/^\w+$/', $sPlugin)) {
                    unset($aPlugins[$nKey]);
                }
            }
            $aPlugins = array_map('trim', $aPlugins);
        } else {
            $aPlugins = array();
        }
        return $aPlugins;
    }

    static protected function _getVal($sKey, $sPlugin = 'aceadminpanel')
    {
        return Config::Get('plugin.' . $sPlugin . '.' . $sKey);
    }

    static protected function _getCustomConfigPath()
    {
        if (!self::$sCustomConfigPath) {
            self::$sCustomConfigPath = self::_getVal('custom_config.path');
            if (!self::$sCustomConfigPath) self::$sCustomConfigPath = Config::Get('path.root.server') . '/config/plugins';
        }
        return self::$sCustomConfigPath;
    }

    static function Init()
    {
        ACE_Config::LoadCustomPluginsConfig('aceadminpanel', true, true);
        ACE_Config::CheckTmpDirs();

        define('CUSTOM_CFG', 'adm.all.cfg');

        $sDataFile = Config::Get('sys.cache.dir') . CUSTOM_CFG;
        if (file_exists($sDataFile)) {
            $data = @file_get_contents($sDataFile);
            if ($data AND is_array($aConfigSet = unserialize($data))) {
                foreach ($aConfigSet as $aConfigValue) {
                    if (($n = strpos($aConfigValue['key'], '.', 8))) {
                        $key = substr($aConfigValue['key'], $n + 1);
                        $val = @unserialize($aConfigValue['val']);
                        if (($val !== false) OR ($val === false AND $aConfigValue['val'] === serialize(false))) {
                            if (($key != 'view.skin')
                                OR ($key == 'view.skin' AND is_dir(Config::Get('path.root.server') . '/templates/skin/' . $val))
                            ) {
                                Config::Set($key, $val);
                            }
                        }
                    }
                }
            }
        }

    }

    /**
     * Подгрузка пользовательской конфигурации плагинов
     *
     * @param   bool    $bReload - подгружать, даже если уже была подгрузка
     * @param   bool    $bForce  - подгружать, даже если подгрузка запрещена в конфиге
     */
    static function LoadCustomConfig($bReload = false, $bForce = false)
    {
        if (!self::$bCustomConfigLoaded OR $bReload) {
            $aPlugins = self::_getActivePlugins();
            self::LoadCustomPluginsConfig($aPlugins, $bForce);
            self::LoadSavedPluginsConfig($aPlugins, $bForce);
            self::$bCustomConfigLoaded = true;
        }
    }

    /**
     * Чтение сохраненной конфигурации плагинов
     *
     * @param null|string|array $aPlugins - имя плагина или массив имен, если не задано, то все активные плагины
     * @param   bool    $bForce  - подгружать, даже если подгрузка запрещена в конфиге
     */
    static function LoadSavedPluginsConfig($aPlugins = null, $bForce = false)
    {
        if ((self::_getVal('custom_config.enable') AND self::_getVal('custom_config.saved')) OR $bForce) {
            if (is_null($aPlugins)) $aPlugins = self::_getActivePlugins();
            elseif (!is_array($aPlugins)) $aPlugins = array($aPlugins);

            foreach ($aPlugins as $sPlugin) {
                $sFile = ACE::FilePath(Config::Get('sys.cache.dir') . 'adm.' . $sPlugin . '.cfg');
                if (is_file($sFile)) {
                    $sData = file_get_contents($sFile);
                    if ($sData) {
                        $aConfig = unserialize($sData);
                        Config::Set('plugin.' . $sPlugin, $aConfig);
                    }
                }
            }
        }
    }

    /**
     * Чтение пользовательской конфигурации плагинов
     *
     * @param   null|string|array $aPlugins - имя плагина или массив имен, если не задано, то все активные плагины
     * @param   bool    $bForce  - подгружать, даже если подгрузка запрещена в конфиге
     * @param   bool    $bPluginOnly - подгружать только кофиг плагина
     */
    static function LoadCustomPluginsConfig($aPlugins = null, $bForce = false, $bPluginOnly = false)
    {
        if (self::_getVal('custom_config.enable') OR $bForce) {
            if (($sCustomConfigPath = self::_getCustomConfigPath()) AND is_dir($sCustomConfigPath)) {
                // Подгрузка общего файла конфигурации, если он есть
                if (!$bPluginOnly AND is_file($sCustomConfigPath . '/config.php')) {
                    Config::LoadFromFile($sCustomConfigPath . '/config.php', false);
                }
                if (self::_getVal('custom_config.plugins')) {
                    if (is_null($aPlugins)) $aPlugins = self::_getActivePlugins();
                    elseif (!is_array($aPlugins)) $aPlugins = array($aPlugins);
                    // Подгрузка файлов конфигурации для каждого плагина
                    foreach ($aPlugins as $sPlugin) {
                        // сначала проверяем файл config.<plugin>.php
                        if (is_file($sFile = $sCustomConfigPath . '/config.' . $sPlugin . '.php')) {
                            self::LoadPluginConfig($sPlugin, $sFile);
                        }
                        // проверяем файлы <plugin>/config.php
                        if (is_dir($sPath = $sCustomConfigPath . '/' . $sPlugin)) {
                            if (is_file($sFile = $sPath . '/config.php')) {
                                self::LoadPluginConfig($sPlugin, $sFile);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Подгрузка файла конфигурации для конкретного плагина
     *
     * @param   string  $sPlugin    - плагин
     * @param   string  $sConfigFile - файл конфигурации
     */
    static function LoadPluginConfig($sPlugin, $sConfigFile)
    {
        if (is_file($sConfigFile)) {
            $sKey = 'plugin.' . $sPlugin;
            $aPluginConfig = Config::Get($sKey);
            // переменная $config нужна для того, чтоб ее можно было не определять внутри файла
            $config = array();
            $aResult = include($sConfigFile);
            if (!$aResult OR !is_array($aResult)) $aResult = $config;
            if ($aResult) {
                $aPluginConfig = func_array_merge_assoc($aPluginConfig, $aResult);
                Config::Set($sKey, $aPluginConfig);
            }
        }
    }

    static function CheckTmpDirs()
    {
        if (self::_getVal('tmp.path.use')) {
            $sTmpPath = ACE::FilePath(self::_getVal('tmp.path.root'));
            if (!$sTmpPath) $sTmpPath = Config::Get('sys.cache.dir');

            if (!is_dir($sTmpPath)) {
                $bOk = false;
                $sContent = 'Order Deny,Allow' . PHP_EOL . 'Deny from all';
                if (ACE::MakeDir($sTmpPath, 0777, true) AND file_put_contents($sTmpPath . '.htaccess', $sContent)) {
                    $bOk = chmod($sTmpPath . '.htaccess', 0644);
                }
            } else {
                $bOk = true;
            }
            if ($bOk) {
                self::_makeTmpDir($sTmpPath, 'tmp.dir.sys', 'sys.cache.dir');
                self::_makeTmpDir($sTmpPath, 'tmp.dir.tpl.compiled', 'path.smarty.compiled');
                self::_makeTmpDir($sTmpPath, 'tmp.dir.tpl.cache', 'path.smarty.cache');
                self::_makeTmpDir($sTmpPath, 'tmp.dir.log', 'sys.logs.path');
            }
        }
    }

    static protected function _makeTmpDir($sTmpPath, $sDirKey, $sConfigKey)
    {
        if ($sDir = self::_getVal($sDirKey)) {
            if (is_dir($sPath = $sTmpPath . '/' . $sDir) OR ACE::MakeDir($sPath, 0777, true)) {
                Config::Set($sConfigKey, $sPath);
                return true;
            }
        }
    }
}

// EOF
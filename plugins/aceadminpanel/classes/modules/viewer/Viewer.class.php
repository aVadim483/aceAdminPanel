<?php
/*----------------------------------------------------------------------------
 * @Plugin Name: aceAdminPanel
 * @Plugin Id: aceadminpanel
 * @Plugin URI: 
 * @Description: Advanced Administrator's Panel for LiveStreet/ACE
 * @Version: 2.0.382
 * @Author: Vadim Shemarov (aka aVadim)
 * @Author URI: 
 * @LiveStreet Version: 1.0.1
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *----------------------------------------------------------------------------
 */

/**
 * Расширение (перекрытие) стандартного модуля Viwer
 */
class PluginAceadminpanel_ModuleViewer extends PluginAceadminpanel_Inherit_ModuleViewer
{
    private $sPlugin = 'aceadminpanel';

    /** @var Smarty */
    protected $oSmarty;

    protected $bLocal = false;
    protected $bAddPluginDirs = false;

    protected $aSmartyOptions = array();

    protected $aTplHooks = array();

    /**
     * Вспомогательная функция для сортировки блоков
     *
     * @param   array   $a
     * @param   array   $b
     *
     * @return  int
     */
    protected function _CompareBlocks($a, $b)
    {
        if ($a["priority"] == $b["priority"]) {
            return ($a["index"] < $b["index"]) ? -1 : 1;
        } elseif ($a['priority'] === 'top') {
            return -1;
        } elseif ($b['priority'] === 'top') {
            return 1;
        }
        return ($a["priority"] > $b["priority"]) ? -1 : 1;
    }

    /**
     * Сортировка блоков по приоритету
     *
     * @return void
     */
    protected function _extSortBlocks()
    {
        foreach ($this->aBlocks as $sGroup => $aBlocks) {
            // вводим дополнительный параметр сортировке,
            // иначе блоки с одинаковыми приоритетами сортируются неверно
            foreach ($aBlocks as $nIndex => $aBlock) {
                $aBlocks[$nIndex]['index'] = $nIndex;
            }
            uasort($aBlocks, array($this, '_CompareBlocks'));
            $this->aBlocks[$sGroup] = $aBlocks;
        }
    }

    /**
     * Определение вызвавшего плагина по стеку вызовов
     *
     * @return string
     */
    protected function _getCallerPlugin()
    {
        $aStack = debug_backtrace();
        foreach ($aStack as $aCaller) {
            if (isset($aCaller['class'])) {
                if ($aCaller['class'] != get_class() AND preg_match('/^Plugin([A-Z][a-z0-9]+)_[a-zA-Z0-9_]+$/', $aCaller['class'], $aMatches)) {
                    return strtolower($aMatches[1]);
                }
            }
        }
        return '';
    }

    /**
     * Определение (и корректировка) реального пути к шаблону
     * с учетом подмены путей в админке и возможного учета js-lib
     *
     * @param $sTemplate
     *
     * @return string
     */
    protected function _getRealTeplate($sTemplate)
    {
        $sRealTemplate = '';
        $sTemplate = ACE::FilePath($sTemplate, '/');
        $sPathRoot = ACE::FilePath(Config::Get('path.root.server'), '/');

        // На формирование шаблонов через "Plugin::GetTemplatePath(__CLASS__)" мы повлиять не можем
        // Поэтому ищем пуговицу
        if (Config::Get($this->sPlugin . '.saved.view.skin')) {
            if (strpos($sTemplate, $sPathRoot) !== 0 AND !is_file($sTemplate) AND ($sPlugin = $this->_getCallerPlugin())) {
                $sRealTemplate = $sPathRoot . '/plugins/' . $sPlugin . '/templates/skin/'
                    . Config::Get($this->sPlugin . '.saved.view.skin') . '/' . $sTemplate;
            } elseif (preg_match('|^' . $sPathRoot . '/plugins/(\w+)/templates/skin/default/(.*)$|', $sTemplate, $aMatches)) {
                // если дефолтный шаблон плагина, то проверим, нет ли шаблона в подмененном скине
                $sRealTemplate = ACE::FilePath(
                    Config::Get('path.root.server') . '/plugins/' . $aMatches[1] . '/templates/skin/'
                        . Config::Get($this->sPlugin . '.saved.view.skin') . '/' . $aMatches[2]);
            }
        }
        if ($sRealTemplate AND is_file($sRealTemplate)) {
            $sTemplate = $sRealTemplate;
        }
        return $sTemplate;
    }

    protected function _jsUniq($sJs, $aParams)
    {
        if ((in_array($sJs, $this->aJsInclude['append']) OR in_array($sJs, $this->aJsInclude['prepend']))
            AND $this->aFilesParams['js'][$sJs] === $aParams
        ) return true;
        else return false;
    }

    protected function _cssUniq($sCss, $aParams)
    {
        if ((in_array($sCss, $this->aCssInclude['append']) OR in_array($sCss, $this->aCssInclude['prepend']))
            AND $this->aFilesParams['css'][$sCss] === $aParams
        ) return true;
        else return false;
    }

    /**
     * Инициализация хуков и префильтра Smarty
     */
    protected function _initTplHooks()
    {
        if ($this->aTplHooks OR Config::Get('plugin.aceadminpanel.smarty.options.mark_template')) {
            if (!class_exists('DomFrag')) ACE::FileInclude('plugin:aceadminpanel:lib/DomFrag.class.php');
            if (class_exists('DomFrag')) {
                // Подключаем Smarty-плагин
                $this->oSmarty->loadFilter('pre', 'tplhook');
                $this->Assign('aTplHooks', $this->aTplHooks);
            }
            if (Config::Get('plugin.aceadminpanel.smarty.options.mark_template')) {
                $this->oSmarty->loadFilter('output', 'tplhook_mark');
            }
        }
    }

    /**
     * Инициализация вьюера
     *
     * @param bool $bLocal
     *
     * @return null|string
     */
    public function Init($bLocal = false)
    {
        $this->bLocal = $bLocal;
        // если не созданы вспомогательные папки, то создаем
        if (!is_dir(Config::Get('path.smarty.compiled'))) @mkdir(Config::Get('path.smarty.compiled'), 0775, true);
        if (!is_dir(Config::Get('path.smarty.cache'))) @mkdir(Config::Get('path.smarty.cache'), 0775, true);

        $xResult = parent::Init($bLocal);
        $this->sCurentPath = ACE::CurrentRoute();

        // хуки шаблонизатора
        $this->InitHooks();
        if (Config::Get($this->sPlugin . '.saved.path.smarty.template')) {
            $this->AddTemplateDir(Config::Get($this->sPlugin . '.saved.path.smarty.template'));
        }
        $this->oSmarty->addPluginsDir(dirname(__FILE__) . '/plugs');
        $this->oSmarty->default_template_handler_func = array($this, 'TemplateHandler');

        // устанавливаем опции Smarty
        $this->aSmartyOptions = (array)Config::Get('plugin.aceadminpanel.smarty.options');

        foreach ($this->aSmartyOptions as $sKey => $xVal) {
            switch ($sKey) {
                case 'compile_check':
                    $this->oSmarty->compile_check = (bool)$xVal;
                    break;
                case 'force_compile':
                    // Если массив, то задаются значения для разных путей
                    if (is_array($xVal)) {
                        $bResult = null;
                        foreach ($xVal as $sKey=>$aPaths) {
                            if (!is_array($aPaths)) $aPaths = explode(',', $aPaths);
                            if (ACE::InPath($this->sCurentPath, $aPaths)) {
                                $bResult = ((is_null($bResult) ? true : false) AND ACE::Boolean($sKey));
                            } else {
                                // Если есть только ключи 'off', то считаем, что по умолчанию - 'on'
                                if (is_null($bResult) AND !ACE::Boolean($sKey))
                                    $bResult = true;
                            }
                        }
                    } else {
                        $bResult = ACE::Boolean($xVal);
                    }
                    $this->oSmarty->force_compile = (bool)$bResult;
                    break;
                case 'caching':
                    if ($xVal === true) {
                        $xVal = 1;
                    } elseif (intval($xVal) > 2) {
                        $xVal = 1;
                    } elseif (intval($xVal) < 0) {
                        $xVal = 0;
                    }
                    $this->oSmarty->caching = intval($xVal);
                    break;
                case 'cache_lifetime':
                    $this->oSmarty->cache_lifetime = intval($xVal);
                    break;
            }
        }
        return $xResult;
    }

    /**
     * Инициализация хуков шаблонизатора
     */
    public function InitHooks()
    {
        $aActivePlugins = $this->Plugin_GetActivePlugins();
        foreach ($aActivePlugins as $sPlugin) {
            $aConfig = ACE::FileIncludeIfExists(Plugin::GetTemplatePath($sPlugin) . '/settings/config/config.php');
            Config::Load($aConfig, false);
        }
        $aHooks = Config::Get('view.hooks');
        if (is_array($aHooks))
            foreach ($aHooks as $aHook) {
                if (!isset($aHook['enable']) OR $aHook['enable']) {
                    $this->TplHookCreate($aHook['template'], $aHook['selector'], $aHook['content'], $aHook['action']);
                }
            }
    }

    /**
     * Инициализация параметров отображения блоков
     *
     * @return mixed
     */
    protected function InitBlockParams()
    {
        // для ajax-запросов и локального вьюера блоки не нужны
        if (!$this->GetResponseAjax() AND !$this->bLocal) {
            return parent::InitBlockParams();
        }
    }

    public function TemplateHandler($sTemplateType, $sTemplateName, &$sContent, &$sModified, $oSmartyTemplate)
    {
        $sTemplateFile = $this->_getRealTeplate($sTemplateName);
        if ((!$sTemplateFile OR $sTemplateFile == $sTemplateName)
            AND $oSmartyTemplate->parent AND $oSmartyTemplate->parent->template_filepath
                AND !in_array(dirname($oSmartyTemplate->parent->template_filepath), array('.', '..'))
        ) {
            $sTemplateFile = dirname($oSmartyTemplate->parent->template_filepath) . '/' . $sTemplateName;
            if (!is_file($sTemplateFile) AND preg_match('|(.+)/actions/[\w+]|', $oSmartyTemplate->parent->template_filepath, $aMatches)) {
                $sTemplateFile = $aMatches[1] . '/' . $sTemplateName;
            }
        }
        if (is_file($sTemplateFile))
            return $sTemplateFile;
        else
            return false;
    }

    public function AppendScript($sJs, $aParams = array())
    {
        $sJs = ACE::Dir2Url($sJs);
        if (!in_array($sJs, $this->aJsInclude['append']) OR $this->aFilesParams['js'][$sJs] !== $aParams)
            return parent::AppendScript($sJs, $aParams);
    }

    public function PrependScript($sJs, $aParams = array())
    {
        $sJs = ACE::Dir2Url($sJs);
        if (!in_array($sJs, $this->aJsInclude['prepend']) OR $this->aFilesParams['js'][$sJs] !== $aParams)
            return parent::PrependScript($sJs, $aParams);
    }

    public function AppendStyle($sCss, $aParams = array())
    {
        $sCss = ACE::Dir2Url($sCss);
        if (!in_array($sCss, $this->aCssInclude['append']) OR $this->aFilesParams['css'][$sCss] !== $aParams)
            return parent::AppendStyle($sCss, $aParams);
    }

    public function PrependStyle($sCss, $aParams = array())
    {
        $sCss = ACE::Dir2Url($sCss);
        if (!in_array($sCss, $this->aCssInclude['prepend']) OR $this->aFilesParams['css'][$sCss] !== $aParams)
            return parent::PrependStyle($sCss, $aParams);
    }

    public function GetLocalViewer()
    {
        $sViewerClass = get_class();
        $oViewerLocal = new $sViewerClass(Engine::getInstance());
        $oViewerLocal->Init(true);
        $oViewerLocal->VarAssign();
        $oViewerLocal->Assign('aLang', $this->Lang_GetLangMsg());

        return $oViewerLocal;
    }

    public function AssignArray($sVarName, $aValue)
    {
        $this->oSmarty->append($sVarName, (array)$aValue, true);
    }

    public function VarAssign()
    {
        $this->_extSortBlocks();
        parent::VarAssign();

        $aPlugins = $this->Plugin_GetActivePlugins();
        $plugins = array();
        foreach ($aPlugins as $sPlugin) {
            $plugins[$sPlugin] = array(
                'skin' => array(
                    'name' => HelperPlugin::GetPluginSkin($sPlugin),
                    'path' => HelperPlugin::GetPluginSkinPath($sPlugin),
                    'url' => HelperPlugin::GetPluginSkinUrl($sPlugin),
                ),
                'config' => Config::Get('plugin.' . $sPlugin)
            );
        }
        $ls = array(
            'site' => array(
                'skin' => array(
                    'name' => Config::Get($this->sPlugin . '.saved.view.skin')
                        ? Config::Get($this->sPlugin . '.saved.view.skin')
                        : Config::Get('view.skin'),
                    'path' => Config::Get($this->sPlugin . '.saved.path.smarty.template')
                        ? Config::Get($this->sPlugin . '.saved.path.smarty.template')
                        : Config::Get('path.smarty.template'),
                    'url' => Config::Get($this->sPlugin . '.saved.path.static.skin')
                        ? Config::Get($this->sPlugin . '.saved.path.static.skin')
                        : Config::Get('path.static.skin'),
                ),
            ),
            'js' => array(
                'lib' => Config::Get('js.lib'),
                'jquery' => Config::Get('js.jquery'),
                'mootools' => Config::Get('js.mootools'),
            ),
            'router' => array(
                'action' => Router::GetAction(),
                'event' => Router::GetActionEvent(),
                'param' => Router::GetParams(),
            ),
            'url' => $this->oSmarty->getTemplateVars('aRouter'),
            'plugin' => $plugins,
        );
        $this->AssignArray('ls', $ls);
    }

    /**
     * Добавить путь к шаблонам Smarty
     *
     * @param   array|string    $aTemplateDirs
     * @param   bool            $bFirst     - вставляем в начало списка
     *
     * @return  void
     */
    public function AddTemplateDir($aTemplateDirs, $bFirst = false)
    {
        $aSavedDirs = ACE::FilePath($this->oSmarty->getTemplateDir());
        if (!is_array($aTemplateDirs)) {
            $aTemplateDirs = array((string)$aTemplateDirs);
        }
        if ($bFirst) {
            $aTemplateDirs = array_merge(ACE::FilePath($aTemplateDirs), $aSavedDirs);
        } else {
            $aTemplateDirs = array_merge($aSavedDirs, ACE::FilePath($aTemplateDirs));
        }
        $this->SetTemplateDir($aTemplateDirs);
    }

    /**
     * Задать путь (пути) к шаблонам Smarty
     * Раннее заданные пути удаляются
     *
     * @param   array|string    $aTemplateDirs
     *
     * @return  void
     */
    public function SetTemplateDir($aTemplateDirs)
    {
        $this->oSmarty->setTemplateDir(array_unique(ACE::FilePath($aTemplateDirs)));
    }

    public function GetTemplateDir()
    {
        return $this->oSmarty->getTemplateDir();
    }

    public function Display($sTemplate)
    {
        // ajax-запросы нас не интересуют ?
        if (!$this->sResponseAjax) {
            if ($sTemplate) {
                $sTemplate = ACE::FilePath($this->Plugin_GetDelegate('template', $sTemplate), '/');
                if (!$this->TemplateExists($sTemplate)) {
                    if (dirname($sTemplate) == '.') {
                        if (strpos($sClass = Router::GetActionClass(), 'Plugin') === 0) {
                            $sTemplate = HelperPlugin::GetPluginSkinPath($sClass) . 'actions/Action' . ucfirst(Router::GetAction()) . '/' . $sTemplate;
                        }
                    }
                    $sTemplate = $this->_getRealTeplate($sTemplate);
                }
                $sPathRoot = ACE::FilePath(Config::Get('path.root.server'), '/');
                if ($this->bAddPluginDirs AND (strpos($sTemplate, $sPathRoot) === 0) AND is_file($sTemplate)) {
                    // добавляем пути к шаблонам
                    $sPath = dirname($sTemplate);
                    if ($sPath AND $sPath != '.') {
                        $this->AddTemplateDir($sPath, true);
                        if (basename(dirname($sPath)) == 'actions') {
                            $this->AddTemplateDir(dirname(dirname($sPath)), true);
                        }
                    }
                }
            }
        }
        $this->_initTplHooks();
        return parent::Display($sTemplate);
    }

    public function Fetch($sTemplate)
    {
        if (Config::Get($this->sPlugin . '.saved.view.skin')) {
            $sTemplate = $this->_getRealTeplate($sTemplate);
        }
        $this->_initTplHooks();
        return parent::Fetch($sTemplate);
    }

    public function GetSmartyVersion()
    {
        $sSmartyVersion = null;
        if (property_exists($this->oSmarty, '_version')) {
            $sSmartyVersion = $this->oSmarty->_version;
        } elseif (defined('Smarty::SMARTY_VERSION')) {
            $sSmartyVersion = Smarty::SMARTY_VERSION;
        }
        return $sSmartyVersion;
    }

    public function AddBlock($sGroup, $sName, $aParams = array(), $nPriority = 5)
    {
        /**
         * Если не указана директория шаблона, но указана приналежность к плагину,
         * то "вычисляем" правильную директорию
         */
        if (!isset($aParams['dir']) AND isset($aParams['plugin'])) {
            //$aParams['dir'] = HelperPlugin::GetTemplatePath('', $aParams['plugin']);
            //Plugin::GetTemplatePath();
        }
        return parent::AddBlock($sGroup, $sName, $aParams, $nPriority);
    }

    /**
     * Определяет тип блока
     *
     * @param   string  $sName - Название блока
     * @param   string|null $sDir - Путь до блока (определяется само для плагинов, если передать параметр 'plugin'=>'myplugin')
     * @return  string  ('block','template','undefined')
     * @throws  Exception
     */
    protected function DefineTypeBlock($sName, $sDir = null)
    {
        if ($sDir) {
            // * Если найден шаблон вида block.name.tpl то считаем что тип 'block'
            if (is_file(ACE::FilePath($sDir . '/blocks/block.' . $sName . '.tpl')))
                return 'block';
            // * Если найден шаблон по имени блока то считаем его простым шаблоном
            if (is_file(ACE::FilePath($sDir . '/' . $sName)))
                return 'template';
        }
        /*
        try {
            $xResult = parent::DefineTypeBlock($sName, $sDir);
        } catch (Exception $e) {
            // если ищется шаблон плагина, и его скин не default, и он не найден, то делается попытка найти шаблон в default
            if (substr($e->getMessage(), 0, 22) == 'Can not find the block' AND ($s = ACE::InPath($sDir, ACE::GetRootDir() . '/plugins/*'))) {
                $n = strrpos($sDir, '/', -2);
                $sSkin = trim(substr($sDir, $n), '/');
                if ($sSkin !== 'default')
                    $sDir = substr($sDir, 0, $n) . '/default/';
                $xResult = parent::DefineTypeBlock($sName, $sDir);
            }
        }
        */
        $xResult = parent::DefineTypeBlock($sName, $sDir);
        return $xResult;
    }

    /**
     * Добавить объект TPL-хук
     *
     * @param   $oTplHook
     */
    public function AddTplHook($oTplHook)
    {
        $this->aTplHooks[] = $oTplHook;
    }

    public function TplHookCreate($sTemplate, $sSelector, $xContent, $sAction)
    {
        $aParams = array(
            'template' => $sTemplate,
            'selector' => $sSelector,
            'content_source' => $xContent,
            'action' => $sAction,
        );
        $oTplHook = Engine::GetEntity('Viewer_TplHook', $aParams);
        $this->AddTplHook($oTplHook);
    }

    /**
     * TPL-хук: вставить контент перед дочерними элементами
     *
     * @param $sTemplate
     * @param $sSelector
     * @param $xContent
     */
    public function TplHookPrepend($sTemplate, $sSelector, $xContent)
    {
        $this->TplHookCreate($sTemplate, $sSelector, $xContent, 'prepend');
    }

    /**
     * TPL-хук: вставить контент после дочерних элементов
     *
     * @param $sTemplate
     * @param $sSelector
     * @param $xContent
     */
    public function TplHookAppend($sTemplate, $sSelector, $xContent)
    {
        $this->TplHookCreate($sTemplate, $sSelector, $xContent, 'append');
    }

    /**
     * TPL-хук: вставить контент после всех найденных элементов
     *
     * @param $sTemplate
     * @param $sSelector
     * @param $xContent
     */
    public function TplHookAfter($sTemplate, $sSelector, $xContent)
    {
        $this->TplHookCreate($sTemplate, $sSelector, $xContent, 'after');
    }

    /**
     * TPL-хук: вставить контент перед всеми найденными элементами
     *
     * @param $sTemplate
     * @param $sSelector
     * @param $xContent
     */
    public function TplHookBefore($sTemplate, $sSelector, $xContent)
    {
        $this->TplHookCreate($sTemplate, $sSelector, $xContent, 'before');
    }

    /**
     * TPL-хук: заменить контентом все найденные элементы
     *
     * @param $sTemplate
     * @param $sSelector
     * @param $xContent
     */
    public function TplHookReplace($sTemplate, $sSelector, $xContent)
    {
        $this->TplHookCreate($sTemplate, $sSelector, $xContent, 'replace');
    }

    /**
     * TPL-хук: заменить html-контент у всех найденных элементов
     *
     * @param $sTemplate
     * @param $sSelector
     * @param $xContent
     */
    public function TplHookHtml($sTemplate, $sSelector, $xContent)
    {
        $this->TplHookCreate($sTemplate, $sSelector, $xContent, 'html');
    }

    /**
     * TPL-хук: заменить текстовый контент у всех найденных элементов
     *
     * @param $sTemplate
     * @param $sSelector
     * @param $xContent
     */
    public function TplHookText($sTemplate, $sSelector, $xContent)
    {
        $this->TplHookCreate($sTemplate, $sSelector, $xContent, 'text');
    }

    public function MakePaging($iCount, $iCurrentPage, $iCountPerPage, $iCountPageLine, $sBaseUrl, $aGetParamsList = array()) {
        $nCategoryId = intval(getRequest('category_id', 'get', 0));
        if ($nCategoryId && !isset($aGetParamsList['category_id'])) {
            $aGetParamsList['category_id'] = $nCategoryId;
        }
        return parent::MakePaging($iCount, $iCurrentPage, $iCountPerPage, $iCountPageLine, $sBaseUrl, $aGetParamsList);
    }
}

// EOF
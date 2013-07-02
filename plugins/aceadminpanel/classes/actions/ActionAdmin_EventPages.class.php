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

class PluginAceadminpanel_ActionAdmin_EventPages extends PluginAceadminpanel_Inherit_ActionAdmin_EventPages
{
    protected function RegisterEvent()
    {
        parent::RegisterEvent();
        $this->AddEvent('pages', 'EventPages');
    }

    public function EventPages()
    {
        if (!$this->PluginAceadminpanel_Plugin_PluginActivated('Page')) {
            return parent::EventNotFound();
        }

        $this->sMenuSubItemSelect = 'list';

        if (($sAdminAction = $this->_getRequestCheck('action'))) {
            $this->EventPagesAction($sAdminAction);
        }
        // * Обработка создания новой страницы
        if ($this->_getRequestCheck('submit_page_save')) {
            if (!getRequest('page_id')) {
                $this->EventPagesAddSubmit();
            }
        }

        if ($this->GetParam(0) == 'new') { // создание новой страницы
            $this->sMenuSubItemSelect = 'new';
            $this->Viewer_Assign('include_tpl', Plugin::GetTemplatePath($this->sPlugin) . '/actions/ActionAdmin/pages_edit.tpl');
        }
        elseif ($this->GetParam(0) == 'edit') { // вывод формы для редактирования
            $this->EventPagesEdit();
            $this->Viewer_Assign('include_tpl', Plugin::GetTemplatePath($this->sPlugin) . '/actions/ActionAdmin/pages_edit.tpl');
        }
        elseif ($this->GetParam(0) == 'delete') { // отработка команды удаления
            $this->EventPagesDelSubmit();
            ACE::HeaderLocation(Router::GetPath('admin') . 'pages/');
            return;
        }
        elseif ($this->GetParam(0) == 'sort') { // отработка команды сортировки
            $this->EventPagesSort();
            ACE::HeaderLocation(Router::GetPath('admin') . 'pages/');
            return;
        }
        elseif ($this->GetParam(0) == 'options') { // вывод опций
            $this->sMenuSubItemSelect = 'options';
            $this->EventPagesOptions();
            $this->Viewer_Assign('include_tpl', Plugin::GetTemplatePath($this->sPlugin) . '/actions/ActionAdmin/pages_options.tpl');
        }

        // * Получаем и загружаем список всех страниц
        $aPages = $this->PluginPage_Page_GetPages();
        if (sizeof($aPages) == 0 AND $this->PluginPage_Page_GetCountPage()) {
            $this->PluginPage_Page_SetPagesPidToNull();
            $aPages = $this->PluginPage_Page_GetPages();
        }
        $this->Viewer_Assign('aPages', $aPages);
    }

    protected function EventPagesAction($sAdminAction = null)
    {
        if ($sAdminAction) {
            $oPage = $this->PluginPage_Page_GetPageById($this->_getRequestCheck('page_id'));

            if ($oPage) {
                if (($sAdminAction == 'activate') OR ($sAdminAction == 'deactivate')) {
                    $oPage->setActive(($sAdminAction == 'activate') ? 1 : 0);
                    if ($this->PluginPage_Page_UpdatePage($oPage)) {
                        $this->Message_AddNotice($this->Lang_Get('adm_action_ok'), $this->Lang_Get('attention'), true);
                    } else {
                        $this->Message_AddError($this->Lang_Get('adm_action_err'), $this->Lang_Get('error'), true);
                    }
                }
            }
        }
        Router::Location(Router::GetPath('admin') . 'pages/');
    }

    protected function EventPagesSort()
    {
        $this->Security_ValidateSendForm();
        $oPage = $this->PluginPage_Page_GetPageById($this->GetParam(1));
        if ($oPage) {
            $sDirection = $this->GetParam(2) == 'down' ? 'down' : 'up';
            $iSortOld = $oPage->getSort();
            if (($oPagePrev = $this->PluginPage_Page_GetNextPageBySort($iSortOld, $oPage->getPid(), $sDirection))) {
                $iSortNew = $oPagePrev->getSort();
                $oPagePrev->setSort($iSortOld);
                $this->PluginPage_Page_UpdatePage($oPagePrev);
            } else {
                if ($sDirection == 'down') {
                    $iSortNew = $iSortOld - 1;
                } else {
                    $iSortNew = $iSortOld + 1;
                }
            }
            /**
             * Меняем значения сортировки местами
             */
            $oPage->setSort($iSortNew);
            $this->PluginPage_Page_UpdatePage($oPage);
        }

    }

    /**
     * Обработка отправки формы добавления новой страницы
     *
     */
    protected function EventPagesAddSubmit()
    {
        // * Проверяем корректность полей
        if (!$this->EventPagesCheckFields()) {
            return;
        }
        // * Заполняем свойства
        $oPage = Engine::GetEntity('PluginPage_Page');
        $oPage->setAutoBr(getRequest('page_auto_br') ? 1 : 0);
        $oPage->setActive(getRequest('page_active') ? 1 : 0);
        $oPage->setMain(getRequest('page_main') ? 1 : 0);
        $oPage->setDateAdd(date("Y-m-d H:i:s"));
        $oPage->setOtherUrl(getRequest('page_other_url'));
        if (getRequest('page_pid') == 0) {
            $oPage->setUrlFull(getRequest('page_url'));
            $oPage->setPid(null);
        } else {
            $oPage->setPid(getRequest('page_pid'));
            $oPageParent = $this->PluginPage_Page_GetPageById(getRequest('page_pid'));
            $oPage->setUrlFull($oPageParent->getUrlFull() . '/' . getRequest('page_url'));
        }
        $oPage->setSeoDescription(getRequest('page_seo_description'));
        $oPage->setSeoKeywords(getRequest('page_seo_keywords'));
        $oPage->setText(getRequest('page_text'));
        $oPage->setTitle(getRequest('page_title'));
        $oPage->setUrl(getRequest('page_url'));

        if (getRequest('page_sort')) {
            $oPage->setSort(intval(getRequest('page_sort')));
        } else {
            $oPage->setSort($this->PluginPage_Page_GetMaxSortByPid($oPage->getPid()) + 1);
        }

        // * Добавляем страницу
        if ($this->PluginPage_Page_AddPage($oPage)) {
            $this->_messageNotice($this->Lang_Get('page_create_submit_save_ok'), 'page:add');
            $this->SetParam(0, null);
        } else {
            $this->_messageError($this->Lang_Get('system_error'), 'page:add');
        }
    }

    /**
     * Обработка вывода формы для редактирования страницы
     *
     */
    protected function EventPagesEdit()
    {
        if (($oPageEdit = $this->PluginPage_Page_GetPageById($this->GetParam(1)))) {
            if ($this->_getRequestCheck('submit_page_save')) {
                // * Если отправили форму с редактированием, то обрабатываем её
                $this->EventPagesEditSubmit($oPageEdit);
            } else {
                $_REQUEST['page_id'] = $oPageEdit->getId();

                $_REQUEST['page_title'] = $oPageEdit->getTitle();
                $_REQUEST['page_pid'] = $oPageEdit->getPid();
                $_REQUEST['page_url'] = $oPageEdit->getUrl();
                $_REQUEST['page_text'] = $oPageEdit->getText();
                $_REQUEST['page_seo_keywords'] = $oPageEdit->getSeoKeywords();
                $_REQUEST['page_seo_description'] = $oPageEdit->getSeoDescription();

                $_REQUEST['page_active'] = $oPageEdit->getActive();
                $_REQUEST['page_auto_br'] = $oPageEdit->getAutoBr();
                $_REQUEST['page_main'] = $oPageEdit->getMain();
                $_REQUEST['page_sort'] = $oPageEdit->getSort();
                $_REQUEST['page_other_url'] = $oPageEdit->getOtherUrl();
            }
            $this->Viewer_Assign('oPageEdit', $oPageEdit);
        } else {
            $this->_messageError($this->Lang_Get('page_edit_notfound'), 'page:edit');
            $this->SetParam(0, null);
        }
    }

    /**
     * Обработка отправки формы при редактировании страницы
     *
     * @param unknown_type $oPageEdit
     */
    protected function EventPagesEditSubmit($oPageEdit)
    {
        $this->Security_ValidateSendForm();
        // * Проверяем корректность полей
        if (!$this->EventPagesCheckFields()) {
            return;
        }

        if ($oPageEdit->getId() == getRequest('page_pid')) {
            $this->_messageError($this->Lang_Get('system_error'), 'page:edit');
            return;
        }

        // * Обновляем свойства страницы
        $oPageEdit->setAutoBr(getRequest('page_auto_br') ? 1 : 0);
        $oPageEdit->setActive(getRequest('page_active') ? 1 : 0);
        $oPageEdit->setMain(getRequest('page_main') ? 1 : 0);
        $oPageEdit->setDateEdit(date("Y-m-d H:i:s"));
        if (getRequest('page_pid') == 0) {
            $oPageEdit->setUrlFull(getRequest('page_url'));
            $oPageEdit->setPid(null);
        } else {
            $oPageEdit->setPid(getRequest('page_pid'));
            $oPageParent = $this->PluginPage_Page_GetPageById(getRequest('page_pid'));
            $oPageEdit->setUrlFull($oPageParent->getUrlFull() . '/' . getRequest('page_url'));
        }
        $oPageEdit->setSeoDescription(getRequest('page_seo_description'));
        $oPageEdit->setSeoKeywords(getRequest('page_seo_keywords'));
        $oPageEdit->setText(getRequest('page_text'));
        $oPageEdit->setTitle(getRequest('page_title'));
        $oPageEdit->setUrl(getRequest('page_url'));
        $oPageEdit->setSort(intval(getRequest('page_sort')));
        $oPageEdit->setOtherUrl(getRequest('page_other_url'));

        // * Обновляем страницу
        if ($this->PluginPage_Page_UpdatePage($oPageEdit)) {
            $this->PluginPage_Page_RebuildUrlFull($oPageEdit);
            $this->_messageNotice($this->Lang_Get('page_edit_submit_save_ok'), 'page:edit');
            $this->SetParam(0, null);
            $this->SetParam(1, null);
        } else {
            $this->_messageError($this->Lang_Get('system_error'), 'page:edit');
        }
    }

    /**
     * Обработка удаления страницы
     *
     * @return  bool
     */
    protected function EventPagesDelSubmit()
    {
        $nPageId = $this->_getRequestCheck('page_id');
        if ($this->PluginPage_Page_DeletePageById($nPageId)) {
            $this->_messageNotice($this->Lang_Get('page_admin_action_delete_ok'), 'page:delete', true);
            return true;
        } else {
            $this->_messageError($this->Lang_Get('page_admin_action_delete_error'), 'page:delete', true);
            return false;
        }
    }

    /**
     * Обработка вывода/сохранения опций статических страниц
     */
    protected function EventPagesOptions()
    {
        if ($this->_getRequestCheck('submit_options_save')) {
            if ($this->EventInfoParamsSubmit()) {
                $this->_messageNotice($this->Lang_Get('adm_saved_ok'), 'page:options');
            } else {
                $this->_messageError($this->Lang_Get('adm_saved_err'), 'page:options');
            }
        }
        $this->Viewer_Assign('sParamPageUrlReserved', implode(',', $this->aConfig['reserverd_urls']));
    }

    /**
     * Проверка полей на корректность
     *
     * @return  bool
     */
    protected function EventPagesCheckFields()
    {
        $this->Security_ValidateSendForm();

        $bOk = true;

        // * Проверяем есть ли заголовок страницы
        if (!func_check(getRequest('page_title'), 'text', 2, 200)) {
            $this->_messageError($this->Lang_Get('page_create_title_error'), $this->Lang_Get('error'));
            $bOk = false;
        }
        //  * Проверяем есть ли заголовок страницы, с заменой всех пробельных символов на "_"
        $pageUrl = preg_replace("/\s+/", '_', getRequest('page_url'));
        $_REQUEST['page_url'] = $pageUrl;
        if (!func_check(getRequest('page_url'), 'login', 1, 50)) {
            $this->_messageError($this->Lang_Get('page_create_url_error'), $this->Lang_Get('error'));
            $bOk = false;
        }

        // * Проверяем на плохие/зарезервированные УРЛы
        if (in_array(getRequest('page_url'), $this->aConfig['reserverd_urls'])) {
            $this->_messageError($this->Lang_Get('page_create_url_error_bad') . ' ' . join(',', $this->aConfig['reserverd_urls']), $this->Lang_Get('error'));
            $bOk = false;
        }

        // * Проверяем есть ли содержимое страницы
        if (!func_check(getRequest('page_text'), 'text', 1, 50000)) {
            $this->_messageError($this->Lang_Get('page_create_text_error'), $this->Lang_Get('error'));
            $bOk = false;
        }

        // * Проверяем страницу в которую хотим вложить
        if (getRequest('page_pid') != 0 and !($oPageParent = $this->PluginPage_Page_GetPageById(getRequest('page_pid')))) {
            $this->_messageError($this->Lang_Get('page_create_parent_page_error'), $this->Lang_Get('error'));
            $bOk = false;
        }

        // * Проверяем сортировку
        if (getRequest('page_sort') and !is_numeric(getRequest('page_sort'))) {
            $this->Message_AddError($this->Lang_Get('page_create_sort_error'), $this->Lang_Get('error'));
            $bOk = false;
        }

        // * Выполнение хуков
        $this->Hook_Run('check_page_fields', array('bOk' => &$bOk));

        return $bOk;
    }


}

// EOF
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
 * Обработка УРЛа вида /topic/ - управление своими топиками
 *
 */
class PluginAceadminpanel_ActionTopic extends PluginAceadminpanel_Inherit_ActionTopic
{
    private $sPlugin = 'aceadminpanel';

    protected $sBackUrl;

    public function Init()
    {
        if (isset($_SERVER['HTTP_REFERER'])) $this->sBackUrl = $_SERVER['HTTP_REFERER'];
        return parent::Init();
    }

    /**
     * Удаление топика
     *
     * @return  void
     */
    protected function EventDelete()
    {
        $this->Security_ValidateSendForm();
        // * Получаем номер топика из УРЛ и проверяем существует ли он
        $sTopicId = $this->GetParam(0);
        if (!($oTopic = $this->Topic_GetTopicById($sTopicId))) {
            return parent::EventNotFound();
        }
        // * проверяем есть ли право на удаление топика
        if (!$this->ACL_IsAllowDeleteTopic($oTopic, $this->oUserCurrent)) {
            return parent::EventNotFound();
        }
        $oBlog = $oTopic->getBlog();
        // * Гарантировано удаляем топик и его зависимости
        $this->Hook_Run('topic_delete_before', array('oTopic' => $oTopic));
        $this->PluginAceadminpanel_Admin_DelTopic($oTopic->GetId());
        $this->Hook_Run('topic_delete_after', array('oTopic' => $oTopic));

        // * Перенаправляем на страницу со списком топиков из блога этого топика
        //Router::Location($oTopic->getBlog()->getUrlFull());
        if ($this->sBackUrl AND $this->sBackUrl != $oTopic->getUrl()) {
            ACE::HeaderLocation($this->sBackUrl);
        } else {
            ACE::HeaderLocation($oTopic->getBlog()->getUrlFull());
        }

    }

    protected function SetTemplate($sTemplate)
    {
        return parent::SetTemplate(HelperPlugin::GetDelegate('template', $sTemplate));
        /*
        $s = HelperPlugin::GetDelegate('template', $sTemplate);
        parent::SetTemplate($sTemplate);
        if (!file_exists($this->sActionTemplate)) {
            $sPlugin = HelperPlugin::GetPluginStr(function_exists('get_called_class')?get_called_class():$this);
            $sReaplTemplate = HelperPlugin::GetTemplatePath($this->sActionTemplate, $sPlugin);
            if ($sReaplTemplate AND is_file($sReaplTemplate))
                $this->sActionTemplate = $sReaplTemplate;
        }
        */
    }

    protected function SetTemplateAction($sTemplate)
    {
        $sResultTemplate = HelperPlugin::GetDelegate('template', $sTemplate . '.tpl', true);
        if ($sResultTemplate AND is_file($sResultTemplate)) {
            return parent::SetTemplate($sResultTemplate);
        } else {
            parent::SetTemplateAction(HelperPlugin::GetDelegate('template', $sTemplate));
        }
        /*
        parent::SetTemplateAction($sTemplate);
        $this->SetTemplate($this->sActionTemplate);
        */
    }

}

// EOF
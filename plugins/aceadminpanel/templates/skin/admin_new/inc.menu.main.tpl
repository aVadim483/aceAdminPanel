<ul class="nav nav-list well well-small">
    <li class="nav-header">{$oLang->_adm_menu_panel}</li>
    <li {if $sEvent=='' OR $sEvent=='info'}class="active"{/if}>
        <a href="{router page='admin'}info/">{$oLang->_adm_menu_info}</a>
    </li>
    <li {if $sMenuSubItemSelect=='params'}class="active"{/if}>
        <a href="{router page='admin'}params/">{$oLang->adm_menu_params}</a>
    </li>

    <li class="nav-header">{$oLang->_adm_menu_config}</li>
    <li {if $sMenuSubItemSelect=='settings'}class="active"{/if}>
        <a href="{router page='admin'}site/settings/">{$oLang->_adm_menu_settings}</a>
    </li>
    <li {if $sMenuSubItemSelect=='reset'}class="active"{/if}>
        <a href="{router page='admin'}site/reset/">{$oLang->_adm_menu_reset}</a>
    </li>
    <li {if $sMenuSubItemSelect=='plugins'}class="active"{/if}>
        <a href="{router page=admin}plugins/">{$oLang->_adm_menu_plugins}</a>
    </li>

    <li class="nav-header">{$oLang->_adm_menu_site}</li>
    <li {if $sEvent=='users'}class="active"{/if}>
        <a href="{router page=admin}users/">{$oLang->_adm_menu_users}
        {if $oUserProfile}<i class="icon icon-arrow-right"></i>{/if}
        </a>
    </li>
    <li {if $sEvent=='blogs'}class="active"{/if}>
        <a href="{router page=admin}blogs/">{$oLang->_adm_menu_blogs}</a>
    </li>
    {if $aPluginActive.aceblogextender AND $oConfig->GetValue('plugin.aceblogextender.category.enable')}
    <li {if $sMenuSubItemSelect=='plugins_admin_aceblogextender'}class="active"{/if}>
        <a href="{router page=admin}plugins/aceblogextender/categories/">{$oLang->_adm_menu_categories}</a>
    </li>
    {/if}
    {if $aPluginActive.aceblogextender AND $oConfig->GetValue('plugin.page')}
    <li {if $sEvent=='pages'}class="active"{/if}>
        <a href="{router page=admin}pages/">{$oLang->_adm_menu_pages}</a>
    </li>
    {/if}

    {hook run='admin_menu_item'}

    <li class="nav-header">{$oLang->_adm_menu_additional}</li>
    <li id="admin_action_submenu">
        <a href="{router page=admin}others/">
        {$oLang->_adm_menu_additional_item}
            <i class="icon-chevron-right icon-gray"></i>
        </a>
    </li>
</ul>

<div id="admin_action_item" style="display: none;">
    <ul class="nav nav-list">
        <li><a href="{router page="admin"}userfields/">{$aLang.admin_list_userfields}</a></li>
        <li><a href="{router page="admin"}restorecomment/?security_ls_key={$LIVESTREET_SECURITY_KEY}">{$aLang.admin_list_restorecomment}</a></li>
        <li><a href="{router page="admin"}recalcfavourite/?security_ls_key={$LIVESTREET_SECURITY_KEY}">{$aLang.admin_list_recalcfavourite}</a></li>
        <li><a href="{router page="admin"}recalcvote/?security_ls_key={$LIVESTREET_SECURITY_KEY}">{$aLang.admin_list_recalcvote}</a></li>
        <li><a href="{router page="admin"}recalctopic/?security_ls_key={$LIVESTREET_SECURITY_KEY}">{$aLang.admin_list_recalctopic}</a></li>
        {hook run='admin_action_item'}
    </ul>
    <br/>
    <div class="nav nav-list">
        {hook run='admin_action'}
    </div>
</div>

<script type="">
    var $ace = $ace || { };

    $ace.submenuAction = function(el) {
        var target = $(el);
        var popover = target.getPopover();
        if (!popover.isVisible()) {
            target.popover('show');
            popover.bind('mouseenter', function(){
                popover.data('is-mouse-over', 1);
            });
            popover.bind('mouseleave', function(){
                popover.data('is-mouse-over', null);
                setTimeout(function () {
                            if (!target.data('is-mouse-over'))
                                target.popover('hide');
                        },
                        500
                );
            });
        }
        target.data('is-mouse-over', 1);
    }

    $(function () {
        var c = $('#admin_action_item').find('li').first().children().first();
        if (c && c.length && c[0].nodeName == 'BR') {
            $(c).detach();
        }
        var options = {
            title:false,
            content:function () {
                return $('#admin_action_item').html();
            },
            html:true,
            trigger:'manual',
            css:{
                width:'auto'
            },
            events:{
                'click':function () {
                    $ace.submenuAction(this);
                },
                'mouseover':function () {
                    $ace.submenuAction(this);
                },
                'mouseout':function () {
                    //return;
                    var target = $(this);
                    setTimeout(function () {
                                if (target.getPopover().isVisible() && !target.getPopover().data('is-mouse-over')) {
                                    target.popover('hide');
                                }
                            },
                            500
                    );
                    target.data('is-mouse-over', null);
                }
            }
        };
        var submenu = $('#admin_action_submenu');
        var popover = submenu.setPopover(options);
        popover.mouseover(function(){
            $(this).data('is-mouse-over', true);
        });
        popover.mouseout(function(){ alert(1);
            $(this).data('is-mouse-over', null);
        });
        $('body').click(function () {
            submenu.popover('hide');
            if (submenu.getPopover().isVisible()) {
                submenu.popover('hide');
            }
        });

        /*
        $('#admin_action_item a').each(function(){
            var href = $(this).prop('href');
            $(this).prop('href', href.replace(aRouter['admin'], aRouter['admin'] + 'x/'));
        });
        */
    });
</script>

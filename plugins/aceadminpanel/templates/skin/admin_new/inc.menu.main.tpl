<ul class="nav nav-list nav-menu well well-small">
    <li class="nav-header">{$oLang->_adm_menu_panel}</li>
    <li class="nav-menu_info {if $sEvent=='' OR $sEvent=='info'}active{/if}">
        <a href="{router page='admin'}info/">{$oLang->_adm_menu_info}</a>
    </li>
    <li class="nav-menu_params {if $sMenuSubItemSelect=='params'}active{/if}">
        <a href="{router page='admin'}params/">{$oLang->adm_menu_params}</a>
    </li>

    <li class="nav-header">{$oLang->_adm_menu_config}</li>
    <li class="nav-menu_settings {if $sMenuSubItemSelect=='settings'}active{/if}">
        <a href="{router page='admin'}site/settings/">{$oLang->_adm_menu_settings}</a>
    </li>
    <li class="nav-menu_reset {if $sMenuSubItemSelect=='reset'}active{/if}">
        <a href="{router page='admin'}site/reset/">{$oLang->_adm_menu_reset}</a>
    </li>
    <li class="nav-menu_plugins {if $sMenuSubItemSelect=='plugins'}active{/if}">
        <a href="{router page=admin}plugins/">{$oLang->_adm_menu_plugins}</a>
    </li>

    <li class="nav-header">{$oLang->_adm_menu_site}</li>
    <li class="nav-menu_users {if $sEvent=='users'}active{/if}">
        <a href="{router page=admin}users/">{$oLang->_adm_menu_users}
        {if $oUserProfile}<i class="icon icon-arrow-right"></i>{/if}
        </a>
    </li>
    <li class="nav-menu_banlist {if $sEvent=='banlist'}active{/if}">
        <a href="{router page=admin}banlist/">{$oLang->_adm_menu_banlist}</a>
    </li>
    <li class="nav-menu_invites {if $sEvent=='invites'}active{/if}">
        <a href="{router page=admin}invites/">{$oLang->_adm_menu_invites}</a>
    </li>
    <li class="nav-menu_blogs {if $sEvent=='blogs'}active{/if}">
        <a href="{router page=admin}blogs/">{$oLang->_adm_menu_blogs}</a>
    </li>
    {if $aPluginActive.aceblogextender AND $oConfig->GetValue('plugin.aceblogextender.category.enable')}
    <li class="nav-menu_categories {if $sMenuSubItemSelect=='plugins_admin_aceblogextender'}active{/if}">
        <a href="{router page=admin}plugins/aceblogextender/categories/">{$oLang->_adm_menu_categories}</a>
    </li>
    {/if}
    {if $aPluginActive.page AND $oConfig->GetValue('plugin.page')}
    <li class="nav-menu_pages {if $sEvent=='pages'}active{/if}">
        <a href="{router page=admin}pages/">{$oLang->_adm_menu_pages}</a>
    </li>
    {/if}

    {hook run='admin_menu_item'}

    <li class="nav-header">{$oLang->_adm_menu_additional}</li>
    <li class="nav-menu_db {if $sEvent=='db'}active{/if}">
        <a href="{router page=admin}db/">{$oLang->_adm_menu_db}</a>
    </li>
    <li id="admin_action_submenu" class="nav-menu_others" >
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

<!doctype html>

<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="ru"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="ru"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="ru"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="ru"> <!--<![endif]-->

<head>
{hook run='html_head_begin'}

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title>{$sHtmlTitle}</title>

    <meta name="description" content="{$sHtmlDescription}">
    <meta name="keywords" content="{$sHtmlKeywords}">

    <meta name="viewport" content="width=device-width,initial-scale=1">

{$aHtmlHeadFiles.css}

    <link href='http://fonts.googleapis.com/css?family=PT+Sans:400,700&subset=latin,cyrillic' rel='stylesheet'
          type='text/css'>

    <link href="{cfg name='path.static.skin'}/images/favicon.ico?v1" rel="shortcut icon"/>
    <link rel="search" type="application/opensearchdescription+xml" href="{router page='search'}opensearch/"
          title="{cfg name='view.name'}"/>

{if $aHtmlRssAlternate}
    <link rel="alternate" type="application/rss+xml" href="{$aHtmlRssAlternate.url}" title="{$aHtmlRssAlternate.title}">
{/if}

{if $sHtmlCanonical}
    <link rel="canonical" href="{$sHtmlCanonical}"/>
{/if}

{if $bRefreshToHome}
    <meta HTTP-EQUIV="Refresh" CONTENT="3; URL={cfg name='path.root.web'}/">
{/if}


    <script type="text/javascript">
        var DIR_WEB_ROOT = '{cfg name="path.root.web"}';
        var DIR_STATIC_SKIN = '{cfg name="path.static.skin"}';
        var DIR_ROOT_ENGINE_LIB = '{cfg name="path.root.engine_lib"}';
        var LIVESTREET_SECURITY_KEY = '{$LIVESTREET_SECURITY_KEY}';
        var SESSION_ID = '{$_sPhpSessionId}';
        var BLOG_USE_TINYMCE = '{cfg name="view.tinymce"}';

        var TINYMCE_LANG = 'en';
        {if $oConfig->GetValue('lang.current') == 'russian'}
        TINYMCE_LANG = 'ru';
        {/if}

        var aRouter = new Array();
        {foreach from=$aRouter key=sPage item=sPath}
        aRouter['{$sPage}'] = '{$sPath}';
        {/foreach}
    </script>


{$aHtmlHeadFiles.js}


    <script type="text/javascript">
        var tinyMCE = false;
        ls.lang.load({json var = $aLangJs});
        ls.registry.set('comment_max_tree', '{cfg name="module.comment.max_tree"}');
    </script>

{hook run='html_head_end'}
</head>

{assign var=body_classes value=$body_classes|cat:' ls-user-role-user'}

{if $oUserCurrent->isAdministrator()}
    {assign var=body_classes value=$body_classes|cat:' ls-user-role-admin'}
{/if}

{add_block group='toolbar' name='toolbar_admin.tpl' priority=100}
{add_block group='toolbar' name='toolbar_scrollup.tpl' priority=-100}

<body class="{$body_classes}">
{hook run='body_begin'}

<header id="header">
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container">
                <div class="nav-collapse nav logo">
                    <a href="{router page=admin}">
                        <img src="{$sWebPluginSkin}images/logo32x32.png" alt="{$sAdminTitle}"/>
                    </a>
                </div>
                <a class="brand" href="{router page=admin}">
                {$sAdminTitle}
                </a>

                <div class="nav-collapse">
                    <ul class="nav">
                        <li class="divider-vertical"></li>
                        <li><a href="{cfg name='path.root.web'}" target="_blank">{$oLang->adm_goto_site}</a></li>
                    {hook run='main_menu'}
                    </ul>
                </div>

                <div class="nav-collapse pull-right">
                    <ul class="nav">
                        <li>
                            <a href="{router page='login'}exit/?security_ls_key={$LIVESTREET_SECURITY_KEY}">
                                <i class="icon-off icon-gray"></i>
                                {$aLang.exit}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>

<div id="container" class="container {hook run='container_class'}">

    <div id="wrapper" class="row">
        <div class="span2">
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
                <li {if $sEvent=='plugins'}class="active"{/if}>
                    <a href="{router page=admin}plugins/">{$oLang->_adm_menu_plugins}</a>
                </li>

                <li class="nav-header">{$oLang->_adm_menu_site}</li>
                <li {if $sEvent=='users'}class="active"{/if}>
                    <a href="{router page=admin}users/">{$oLang->_adm_menu_users}
                    {if $oUserProfile}/ {$oUserProfile->getLogin()}{/if}
                    </a>
                </li>
                <li {if $sEvent=='blogs'}class="active"{/if}>
                    <a href="{router page=admin}blogs/">{$oLang->_adm_menu_blogs}</a>
                </li>
                <!--
                <li {if $sEvent=='pages'}class="active"{/if}>
                    <a href="{router page=admin}pages/">{$oLang->_adm_menu_pages}</a>
                </li>
                -->
            </ul>
        </div>

        <div id="content" role="main" class="span11">
        {include file='system_message.tpl'}
        {hook run='content_begin'}
        {block name="content"}
            <p>Action: {$sAction}</p>

            <p>Event: {$sEvent}</p>

            {if $tpl_content}
                {$tpl_content}
            {/if}
            {if $tpl_include}
            {include file="$tpl_include"}
            {/if}

        {/block}
        {hook run='content_end'}
        </div>
        <!-- /content -->

        <div class="sidebar span3">
            <div class="widget-info well"></div>
        {block name="sidebar"}
        {/block}
        </div>

    </div>
    <!-- /wrapper -->

</div>
<!-- /container -->

<footer id="footer">
    <div class="container">

    {hook run='footer_end'}
    </div>
</footer>

{include file='toolbar.tpl'}

{hook run='body_end'}

</body>
</html>
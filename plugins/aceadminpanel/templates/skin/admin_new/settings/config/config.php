<?php

$config['head']['default']['css'] = array(
    //'___path.static.skin___/assets/css/reset.css',
    '___path.root.engine_lib___/external/jquery/markitup/skins/simple/style.css',
    '___path.root.engine_lib___/external/jquery/markitup/sets/default/style.css',
    '___path.root.engine_lib___/external/jquery/jcrop/jquery.Jcrop.css',
    '___path.root.engine_lib___/external/prettify/prettify.css',
    //"___path.static.skin___/css/jquery.jqmodal.css",
    "___path.static.skin___/assets/css/jquery.notifier.css",
    //"___path.static.skin___/css/smoothness/jquery-ui.css",
    //'___path.static.skin___/assets/bootstrap/css/bootstrap.css',
    //'___path.static.skin___/assets/bootstrap/css/bootstrap-responsive.css',
    '___path.root.web___/less/file/[admin_skin]/assets/bootstrap/less/bootstrap.css?from=less',
    '___path.static.skin___/assets/css/main.css',
    '___path.static.skin___/assets/css/admin.css',
);

$config['head']['default']['js'] = Config::Get('head.default.js');
$config['head']['default']['js'][] = '___path.static.skin___/assets/bootstrap/js/bootstrap.js';
$config['head']['default']['js'][] = '___path.static.skin___/assets/js/waypoints.min.js';
$config['head']['default']['js'][] = '___path.static.skin___/assets/js/ace-admin.js';

$config['compress']['css']['merge'] = false;       // указывает на необходимость слияния файлов по указанным блокам.
$config['compress']['css']['use']   = false;       // указывает на необходимость компрессии файлов. Компрессия используется только в активированном

return $config;

// EOF
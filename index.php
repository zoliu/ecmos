<?php
error_reporting(E_ALL & ~E_NOTICE);
define('ROOT_PATH', dirname(__FILE__));
include(ROOT_PATH . '/eccore/ecmall.php');

if (!file_exists(ROOT_PATH . "/data/install.lock") && is_dir(ROOT_PATH . "/install")) {
    @header("location: install");
    exit;
}
/* 定义配置信息 */
ecm_define(ROOT_PATH . '/data/config.inc.php');

/* 启动ECMall */
ECMall::startup(array(
    'default_app'   =>  'default',
    'default_act'   =>  'index',
    'app_root'      =>  ROOT_PATH . '/app',
    'external_libs' =>  array(
        ROOT_PATH . '/includes/global.lib.php',
        ROOT_PATH . '/includes/libraries/time.lib.php',
        ROOT_PATH . '/includes/ecapp.base.php',
        ROOT_PATH . '/includes/plugin.base.php',
        ROOT_PATH . '/app/frontend.base.php',
        ROOT_PATH . '/includes/subdomain.inc.php',
        ROOT_PATH . '/includes/libraries/zllib/common.lib.php',
      
    ),
));
?>

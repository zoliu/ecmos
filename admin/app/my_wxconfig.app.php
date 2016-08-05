<?php

/* 微公众平台接口管理控制器 */

class My_wxconfigApp extends BackendApp {

    var $my_wxconfig_mod;

    function __construct() {
        $this->My_wxconfig();
    }

    function My_wxconfig() {
        parent::__construct();
        $this->my_wxconfig_mod = & m('wxconfig');
    }

    function index() {
        if (!IS_POST) {
            $wx_config = $this->my_wxconfig_mod->get_info_user(0);
            
            if (empty($wx_config)) {
                $wx_config=array();
                $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                for ($i = 0; $i < 8; $i++) {
                    $wx_config['token'] .= $chars[mt_rand(0, strlen($chars) - 1)];
                }
            }
            
            $wx_config['url'] = SITE_URL . '/index.php?app=weixin&id=0';
            $this->assign('wx_config', $wx_config);

            $this->import_resource('jquery.plugins/jquery.validate.js');

            $this->display('my_wxconfig.index.html');
        } else {
            $data = array(
                'user_id' => 0,
                'url' => $_POST['url'],
                'token' => $_POST['token'],
                'appid' => $_POST['appid'],
                'appsecret' => $_POST['appsecret'],
            );
            $w_id = $this->my_wxconfig_mod->unique(0);
            if ($w_id) {
                $this->my_wxconfig_mod->edit($w_id, $data);
                if ($this->my_wxconfig_mod->has_error()) {
                    $this->show_warning($this->my_wxconfig_mod->get_error());

                    return;
                }
                $this->show_message('edit_wxconfig_successed');
            } else {
                $this->my_wxconfig_mod->add($data);
                if ($this->my_wxconfig_mod->has_error()) {
                    $this->show_warning($this->my_wxconfig_mod->get_error());
                    return;
                }
                $this->show_message('edit_wxconfig_successed');
            }
        }
    }


}

?>
<?php

define('RAPP_PATH', dirname(__FILE__));

include_once RAPP_PATH . '/lib/pclzip.lib.php';
include_once RAPP_PATH . '/lib/methods.lib.php';

use rapp\lib\Dir;
use rapp\lib\Methods;

/**
 * 应用中心模块界面类
 *
 * @author Mosquito
 * @link www.360cd.cn
 */
class RappModule extends AdminbaseModule {
    
    protected $config_path;
    protected $s_url;

    function __construct() {
        parent::__construct();
        
        $this->config_path = ROOT_PATH . '/data/rapp.inc.php';
        $this->s_url = Methods::load_config($this->config_path, 's_url');
        $this->assign('s_url', $this->s_url);
    }

    function index() {
        $this->app_list();
    }
    
    /**
     * 更新应用中心
     */
    function update_appcenter() {
    	$url = $this->s_url . '/rapi/?app=rapp&act=update_appcenter';
    	$data = Methods::curl($url, 'post');
    	if (!$data || (isset($data['done']) && !$data['done'])) {
    		show_warning($data['msg']);
    		exit();
    	}
    	$retval = $data['retval'];
    	
    	//获取文件
    	$file = file_get_contents($retval['url']);
    	$zip_name = Dir::init()->create_dir(ROOT_PATH . '/data/app/download/') . "rapp.zip";
    	if (is_file($zip_name)) {
    		@unlink($zip_name);
    	}
    	file_put_contents($zip_name, $file);
    	
    	//解压
    	if (is_dir(ROOT_PATH . '/data/app/test/rapp')) {
    		Dir::init()->del_dir(ROOT_PATH . '/data/app/test/rapp');
    	}
    	
    	$zip = new PclZip($zip_name);
    	$zip->extract(PCLZIP_OPT_PATH, Dir::init()->create_dir(ROOT_PATH . '/data/app/test/rapp'));
    	if ($zip->errorCode() != 0) {
    		show_warning('解压下载文件失败');
    		exit();
    	}
    	
    	//拷贝到根目录
    	Dir::init()->copy_dir(ROOT_PATH . '/data/app/test/rapp', ROOT_PATH);
    	
    	show_message('更新成功');
    }

    /**
     * 应用列表
     */
    function app_list() {
        
        $page = intval($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
        
        $url = $this->s_url . '/rapi/?app=rapp&act=app_list';
        $param = array();
        $param['page'] = $page;
        $param['app_name'] = trim($_GET['app_name']);
        $param['author'] = trim($_GET['author']);
        
        $data = Methods::curl($url, 'post', $param);
        if(!$data)
        {
            show_warning('应用中心服务器连接失败!');
            return;
        }
        if(isset($data['done']) && !$data['done'])
        {
            show_warning($data['msg']);
            return;
        }
        $retval = $data['retval'];
        $this->assign('app_list', $retval['app_list']);
        $this->assign('page_info', $retval['page_info']);
        $this->assign('app_status_options', $retval['app_status_options']);
        
        $this->assign('query', $retval['query']);
        if ($retval['query']) {
        	$this->assign('filter', true);
        }
        
        $this->display('rapp.app_list.html');
    }
    
    /**
     * 应用详情
     */
    function app_view() {
        
        $app_id = intval($_GET['app_id']);
        
        $url = $this->s_url . '/rapi/?app=rapp&act=app_view';
        $param = array();
        $param['app_id'] = $app_id;
        
        $data = Methods::curl($url, 'post', $param);
        if(!$data)
        {
            show_warning('应用中心服务器连接失败!');
            return;
        }
        if(isset($data['done']) && !$data['done'])
        {
            show_warning($data['msg']);
            return;
        }
        $retval = $data['retval'];
        $this->assign('app_info', $retval['app_info']);
        
        $this->display('rapp.app_view.html');
    }
    
    /**
     * 应用购买
     */
    function app_buy() {
        
        $app_id = intval($_GET['app_id']);
        
        $url = $this->s_url . '/rapi/?app=rapp&act=app_buy';
        $param = array();
        $param['app_id'] = $app_id;
        $param = array_merge($param, $this->get_user_config());
        
        $data = Methods::curl($url, 'post', $param);
        if (!$data || (isset($data['done']) && !$data['done'])) {
            if ($data['retval']['recharge']) {
                show_warning($data['msg'], '前往充值', $data['retval']['recharge']);
            }
            else {
                show_warning($data['msg']);
            }
            exit();
        }
        
        show_message($data['msg']);
    }
    
    /**
     * 应用安装
     */
    function app_install() {
        $app_id = intval($_GET['app_id']);
        $version_id = intval($_GET['version_id']);
        
        //获取应用安装需要上传的文件列表
        $url = $this->s_url . '/rapi/?app=rapp&act=get_upload_file_list';
        $param = array();
        $param['app_id'] = $app_id;
        $param['version_id'] = $version_id;
        $data = Methods::curl($url, 'post', $param);
        if ($data['done']) {
            //打包需要上传的文件
            $retval = $data['retval'];
            $file_list = $retval['file_list'];
            
            $zip_name = Dir::init()->create_dir(ROOT_PATH . '/data/app/upload/') . "{$app_id}-{$version_id}.zip";
            if (is_file($zip_name)) {
                @unlink($zip_name);
            }
            $zip = new PclZip($zip_name);
            foreach ($file_list as $file) {
                $file_name = ROOT_PATH . '/' . $file['file_path'] . '/' . $file['file_name'];
                if (is_file($file_name)) {
                    $zip->add($file_name, PCLZIP_OPT_REMOVE_PATH, ROOT_PATH);
                }
                else {
                    //未找到文件处理
                }
            }
            if ($zip->errorCode() != 0) {
                show_warning('打包上传文件失败');
                exit();
            }
        }
        
        //开始安装
        $url = $this->s_url . '/rapi/?app=rapp&act=app_install';
        $param = array();
        $param['app_id'] = $app_id;
        $param['version_id'] = $version_id;
        if ($zip_name) {
            $param['zip_name'] = '@'.$zip_name;
        }
        $param = array_merge($param, $this->get_user_config());
        
        $data = Methods::curl($url, 'post', $param);
        if (!$data || (isset($data['done']) && !$data['done'])) {
            show_warning($data['msg']);
            exit();
        }
        
        $retval = $data['retval'];
        
        //安装成功获取文件
        $file = file_get_contents($retval['url']);
        $zip_name = Dir::init()->create_dir(ROOT_PATH . '/data/app/download/') . "{$app_id}-{$version_id}.zip";
        if (is_file($zip_name)) {
            @unlink($zip_name);
        }
        file_put_contents($zip_name, $file);
        
        //sql
        $db = &db();
        $install_sql = @file_get_contents('zip://'.$zip_name.'#sql/install.sql');
        if($install_sql) {
            $bl = $db->query($install_sql);
            if (!$bl) {
                show_warning('数据库安装失败');
                exit();
            }
        }
        
        //解压
        if (is_dir(ROOT_PATH . '/data/app/test' . "/{$app_id}-{$version_id}")) {
            Dir::init()->del_dir(ROOT_PATH . '/data/app/test' . "/{$app_id}-{$version_id}");
        }
        $zip = new PclZip($zip_name);
        $zip->delete(PCLZIP_OPT_BY_NAME, 'sql/');
        $zip->extract(PCLZIP_OPT_PATH, Dir::init()->create_dir(ROOT_PATH . '/data/app/test' . "/{$app_id}-{$version_id}"));
        if ($zip->errorCode() != 0) {
            show_warning('解压下载文件失败');
            exit();
        }
        
        //拷贝到根目录
        Dir::init()->copy_dir(ROOT_PATH . '/data/app/test' . "/{$app_id}-{$version_id}", ROOT_PATH);
        
        show_message('安装成功');
    }
    
    /**
     * 应用卸载
     */
    function app_uninstall() {
        $app_id = intval($_GET['app_id']);
        $version_id = intval($_GET['version_id']);
        
        //获取应用安装需要上传的文件列表
        $url = $this->s_url . '/rapi/?app=rapp&act=get_upload_file_list';
        $param = array();
        $param['app_id'] = $app_id;
        $param['version_id'] = $version_id;
        $data = Methods::curl($url, 'post', $param);
        if ($data['done']) {
            //打包需要上传的文件
            $retval = $data['retval'];
            $file_list = $retval['file_list'];
        
            $zip_name = Dir::init()->create_dir(ROOT_PATH . '/data/app/upload/') . "{$app_id}-{$version_id}.zip";
            if (is_file($zip_name)) {
                @unlink($zip_name);
            }
            
            $zip = new PclZip($zip_name);
            foreach ($file_list as $file) {
                $file_name = ROOT_PATH . '/' . $file['file_path'] . '/' . $file['file_name'];
                if (is_file($file_name)) {
                    $zip->add($file_name, PCLZIP_OPT_REMOVE_PATH, ROOT_PATH);
                }
                else {
                    //未找到文件处理
                }
            }
            if ($zip->errorCode() != 0) {
                show_warning('打包上传文件失败');
                exit();
            }
        }
        
        //开始安装
        $url = $this->s_url . '/rapi/?app=rapp&act=app_uninstall';
        $param = array();
        $param['app_id'] = $app_id;
        $param['version_id'] = $version_id;
        if ($zip_name) {
            $param['zip_name'] = '@'.$zip_name;
        }
        $param = array_merge($param, $this->get_user_config());
        
        $data = Methods::curl($url, 'post', $param);
        if (!$data || (isset($data['done']) && !$data['done'])) {
            show_warning($data['msg']);
            exit();
        }
        
        $retval = $data['retval'];
        
        //安装成功获取文件
        $file = file_get_contents($retval['url']);
        $zip_name = Dir::init()->create_dir(ROOT_PATH . '/data/app/download/') . "{$app_id}-{$version_id}.zip";
        if (is_file($zip_name)) {
            @unlink($zip_name);
        }
        file_put_contents($zip_name, $file);
        
        //sql
        $db = &db();
        $uninstall_sql = @file_get_contents('zip://'.$zip_name.'#sql/uninstall.sql');
        if($uninstall_sql) {
            $bl = $db->query($uninstall_sql);
            if (!$bl) {
                show_warning('数据库卸载失败');
                exit();
            }
        }
        
        //要删除的资源文件
        foreach ($retval['res_options'] as $k => $v) {
            $v_temp = ROOT_PATH . '/' . $v;
            if (is_file($v_temp)) {
                @unlink($v_temp);
            }
        }
        Dir::init()->del_empty_dir(ROOT_PATH . '/external');
        
        //解压
        if (is_dir(ROOT_PATH . '/data/app/test' . "/{$app_id}-{$version_id}")) {
            Dir::init()->del_dir(ROOT_PATH . '/data/app/test' . "/{$app_id}-{$version_id}");
        }
        
        $zip = new PclZip($zip_name);
        $zip->delete(PCLZIP_OPT_BY_NAME, 'sql/');
        $zip->extract(PCLZIP_OPT_PATH, Dir::init()->create_dir(ROOT_PATH . '/data/app/test' . "/{$app_id}-{$version_id}"));
        if ($zip->errorCode() != 0) {
            show_warning('解压下载文件失败');
            exit();
        }
        
        //拷贝到根目录
        Dir::init()->copy_dir(ROOT_PATH . '/data/app/test' . "/{$app_id}-{$version_id}", ROOT_PATH);
        
        show_message('卸载成功');
    }
    
    /**
     * 个人中心
     */
    function user_center() {
        
        $url = $this->s_url . '/rapi/?app=rapp&act=user_center';
        $param = array();
        $param = array_merge($param, $this->get_user_config());
        
        $data = Methods::curl($url, 'post', $param);
        if (!$data || (isset($data['done']) && !$data['done'])) {
            show_warning($data['msg'], '重新配置', 'index.php?module=rapp&act=user_config');
            exit();
        }
        $retval = $data['retval'];
        
        $this->assign('member_info', $retval['member_info']);
        $this->assign('money_info', $retval['money_info']);
        
        $this->display('rapp.user_center.html');
    }
    
    /**
     * 配置用户信息
     */
    function user_config() {
        
        if (IS_POST) {
            $data = array();
            $data['user_name'] = trim($_POST['user_name']);
            $data['password'] = md5(trim($_POST['password']));
            
            $config = Methods::load_config($this->config_path);
            $config = array_merge($config, $data);
            Methods::save_config($this->config_path, $config);
            
            header('location: index.php?module=rapp&act=user_center');
            exit();
        }
        else {
            $this->display('rapp.user_config.html');
        }
    }
    
    /**
     * 获取用户信息
     */
    function get_user_config() {
        $user_name = Methods::load_config($this->config_path, 'user_name');
        $password = Methods::load_config($this->config_path, 'password');
        
        return array(
            'user_name' => $user_name,
            'password' => $password,
        );
    }
}
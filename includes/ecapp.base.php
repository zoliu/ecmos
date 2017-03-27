<?php

define('IMAGE_FILE_TYPE', 'gif|jpg|jpeg|png'); // 图片类型，上传图片时使用

define('SIZE_GOODS_IMAGE', '2097152'); // 商品大小限制2M
define('SIZE_STORE_LOGO', '20480'); // 店铺LOGO大小限制2OK
define('SIZE_STORE_BANNER', '1048576'); // 店铺BANNER大小限制1M
define('SIZE_STORE_CERT', '409600'); // 店铺证件执照大小限制400K
define('SIZE_STORE_PARTNER', '102400'); // 店铺合作伙伴图片大小限制100K
define('SIZE_CSV_TAOBAO', '2097152'); // 淘宝助理CSV大小限制2M

/* 店铺状态 */
define('STORE_APPLYING', 0); // 申请中
define('STORE_OPEN', 1); // 开启
define('STORE_CLOSED', 2); // 关闭

/* 订单状态 */
define('ORDER_SUBMITTED', 10); // 针对货到付款而言，他的下一个状态是卖家已发货
define('ORDER_PENDING', 11); // 等待买家付款
define('ORDER_ACCEPTED', 20); // 买家已付款，等待卖家发货
define('ORDER_SHIPPED', 30); // 卖家已发货
define('ORDER_FINISHED', 40); // 交易成功
define('ORDER_CANCELED', 0); // 交易已取消

/* 特殊文章分类ID */
define('STORE_NAV', -1); // 店铺导航
define('ACATE_HELP', 1); // 商城帮助
define('ACATE_NOTICE', 2); // 商城快讯（公告）
define('ACATE_SYSTEM', 3); // 内置文章

/* 系统文章分类code字段 */
define('ACC_NOTICE', 'notice'); //acategory表中code字段为notice时——商城公告类别
define('ACC_SYSTEM', 'system'); //acategory表中code字段为system时——内置文章类别
define('ACC_HELP', 'help'); //acategory表中code字段为help时——商城帮助类别

/* 邮件的优先级 */
define('MAIL_PRIORITY_LOW', 1);
define('MAIL_PRIORITY_MID', 2);
define('MAIL_PRIORITY_HIGH', 3);

/* 发送邮件的协议类型 */
define('MAIL_PROTOCOL_LOCAL', 0, true);
define('MAIL_PROTOCOL_SMTP', 1, true);

/* 数据调用的类型 */
define('TYPE_GOODS', 1);

/* 上传文件归属 */
define('BELONG_ARTICLE', 1);
define('BELONG_GOODS', 2);
define('BELONG_STORE', 3);
define('BELONG_GCATEGORY', 4);

define('STOCK_STORE', 1); //设定库存商品的所属商家

/* 二级域名开关 */
!defined('ENABLED_SUBDOMAIN') && define('ENABLED_SUBDOMAIN', 0);

/* 环境 */
define('CHARSET', substr(LANG, 3));
define('IS_AJAX', isset($_REQUEST['ajax']));
/* 短消息的标志 */
define('MSG_SYSTEM', 0); //系统消息

/* 团购活动状态 */
define('GROUP_PENDING', 0); // 未发布
define('GROUP_ON', 1); // 正在进行
define('GROUP_END', 2); // 已结束
define('GROUP_FINISHED', 3); // 已完成
define('GROUP_CANCELED', 4); // 已取消

define('GROUP_CANCEL_INTERVAL', 5); // 团购结束后自动取消的间隔天数

/* 通知类型 */
define('NOTICE_MAIL', 1); // 邮件通知
define('NOTICE_MSG', 2); // 站内短消息

//---www.360cd.cn  Mosquito---
define('ADMIN_ID', 1); //管理员账户id

/**
 *    ECBaseApp
 *
 *    @author    Garbin
 *    @usage    none
 */
class ECBaseApp extends BaseApp {
	var $outcall;
	function __construct() {
		$this->ECBaseApp();
	}
	function ECBaseApp() {
		parent::__construct();

		if (!defined('MODULE')) // 临时处理方案，此处不应对模块进行特殊处理
		{
			/* GZIP */
			if ($this->gzip_enabled()) {
				ob_start('ob_gzhandler');
			} else {
				ob_start();
			}

			/* 非utf8转码 */
			if (CHARSET != 'utf-8' && isset($_REQUEST['ajax'])) {
				$_FILES = ecm_iconv_deep('utf-8', CHARSET, $_FILES);
				$_GET = ecm_iconv_deep('utf-8', CHARSET, $_GET);
				$_POST = ecm_iconv_deep('utf-8', CHARSET, $_POST);
			}

			/* 载入配置项 */
			$setting = &af('settings');
			Conf::load($setting->getAll());

			/* 初始化访问者(放在此可能产生问题) */
			$this->_init_visitor();

			/* 计划任务守护进程 */
			$this->_run_cron();
		}
	}
	function _init_visitor() {
	}

	/**
	 *    初始化Session
	 *
	 *    @author    Garbin
	 *    @param    none
	 *    @return    void
	 */
	function _init_session() {
		import('session.lib');
		if (!defined('SESSION_TYPE')) {
			define('SESSION_TYPE', 'mysql');
		}
		if (SESSION_TYPE == 'mysql' || defined('IN_BACKEND')) {
			//---www.360cd.cn  Mosquito---
			$this->_session = new SessionProcessor(db(), '`ecm_sessions`', '`ecm_sessions_data`', 'ECM_ID');
			/* 清理超时的购物车项目 */
			$this->_session->add_related_table('`ecm_cart`', 'cart', 'session_id', 'user_id=0');
		} else if (SESSION_TYPE == 'memcached') {
			//---www.360cd.cn  Mosquito---
			$this->_session = new MemcacheSession(SESSION_MEMCACHED, 'ECM_ID');
		} else {
			exit('Unkown session type.');
		}
		define('SESS_ID', $this->_session->get_session_id());

		$this->_session->my_session_start();
		env('session', $this->_session);
	}
	function _config_view() {
		$this->_view->caching = ((DEBUG_MODE & 1) == 0); // 是否缓存
		$this->_view->force_compile = ((DEBUG_MODE & 2) == 2); // 是否需要强制编译
		$this->_view->direct_output = ((DEBUG_MODE & 4) == 4); // 是否直接输出
		$this->_view->gzip = (defined('ENABLED_GZIP') && ENABLED_GZIP === 1);
		$this->_view->lib_base = site_url() . '/includes/libraries/javascript';
	}

	/**
	 *    转发至模块
	 *
	 *    @author    Garbin
	 *    @param    none
	 *    @return    void
	 */
	function do_action($action) {
		/* 指定了要运行的模块则调用模块控制器 */
		(!empty($_GET['module']) && !defined('MODULE')) && $action = 'run_module';
		parent::do_action($action);
	}

	function _run_action() {
		/*
			        if (!$this->visitor->i_can('do_action'))
			        {
			            if (!$this->visitor->has_login)
			            {
			                $this->login();
			            }
			            else
			            {
			                $this->show_warning($this->visitor->get_error());
			            }

			            return;
			        }
		*/
		if ($this->_hook('on_run_action')) {
			return;
		}
		parent::_run_action();

		if ($this->_hook('end_run_action')) {
			return;
		}
	}

	function run_module() {
		$module_name = empty($_REQUEST['module']) ? false : strtolower(preg_replace('/(\W+)/', '', $_REQUEST['module']));
		if (!$module_name) {
			$this->show_warning('no_such_module');

			return;
		}
		$file = defined('IN_BACKEND') ? 'admin' : 'index';
		$module_class_file = ROOT_PATH . '/external/modules/' . $module_name . '/' . $file . '.module.php';
		require ROOT_PATH . '/includes/module.base.php';
		require $module_class_file;
		define('MODULE', $module_name);
		$module_class_name = ucfirst($module_name) . 'Module';

		/* 判断模块是否启用 */
		$model_module = &m('module');
		$find_data = $model_module->find('index:' . $module_name);
		if (empty($find_data)) {
			/* 没有安装 */
			$this->show_warning('no_such_module');

			return;
		}
		$info = current($find_data);
		if (!$info['enabled']) {
			/* 尚未启用 */
			$this->show_warning('module_disabled');

			return;
		}

		/* 加载模块配置 */
		Conf::load(array($module_name . '_config' => unserialize($info['module_config'])));

		/* 运行模块 */
		$module = new $module_class_name();
		c($module);
		$module->do_action(ACT);
		$module->destruct();
	}

	function login() {
		$this->display('login.html');
	}
	function logout() {
		$this->visitor->logout();
	}
	function jslang($lang) {
		header('Content-Encoding:' . CHARSET);
		header("Content-Type: application/x-javascript\n");
		header("Expires: " . date(DATE_RFC822, strtotime("+1 hour")) . "\n");
		if (!$lang) {
			echo 'var lang = null;';
		} else {
			echo 'var lang = ' . ecm_json_encode($lang) . ';';
			echo <<<EOT
lang.get = function(key){
    eval('var langKey = lang.' + key);
    if(typeof(langKey) == 'undefined'){
        return key;
    }else{
        return langKey;
    }
}
EOT;
		}
	}

	/**
	 *    插件
	 *
	 *    @author    Garbin
	 *    @param    none
	 *    @return    void
	 */
	function _hook($event, $data = array()) {
		if ($this->outcall) {
			return;
		}
		static $plugins = null;
		$conf_file = ROOT_PATH . '/data/plugins.inc.php';
		if ($plugins === null) {
			is_file($conf_file) && $plugins = include $conf_file;
			if (!is_array($plugins)) {
				$plugins = false;
			}
		}
		if (!isset($plugins[$event])) {
			return null;
		}

		/* 获取可用插件列表 */
		$plugin_list = $plugins[$event];
		if (empty($plugin_list)) {
			return null;
		}
		foreach ($plugin_list as $plugin_name => $plugin_info) {
			$plugin_main_file = ROOT_PATH . "/external/plugins/{$plugin_name}/main.plugin.php";
			if (is_file($plugin_main_file)) {
				include_once $plugin_main_file;
			}
			$plugin_class_name = ucfirst($plugin_name) . 'Plugin';
			$plugin = new $plugin_class_name($data, $plugin_info);
			$this->outcall = true;

			/* 返回一个结果，若要停止当前控制器流程则会返回true */
			$stop_flow = $this->_run_plugin($plugin);
			$plugin = null;
			$this->outcall = false;
			/* 停止原控制器流程 */
			if ($stop_flow) {
				return $stop_flow;
			}
		}
	}

	/**
	 *    运行插件
	 *
	 *    @author    Garbin
	 *    @param     Plugin $plugin
	 *    @return    void
	 */
	function _run_plugin(&$plugin) {
		return $plugin->execute();
	}

	/**
	 *    head标签内的内容
	 *
	 *    @author    Garbin
	 *    @param     string $contents
	 *    @return    void
	 */
	function headtag($string) {
		$this->_init_view();
		$this->assign('_head_tags', $this->_view->fetch('str:' . $string));
	}

	/**
	 *    导入资源到模板
	 *
	 *    @author    Garbin
	 *    @param     mixed $resources
	 *    @return    string
	 */
	function import_resource($resources, $spec_type = null) {
		$headtag = '';
		if (is_string($resources) || $spec_type) {
			!$spec_type && $spec_type = 'script';
			$resources = $this->_get_resource_data($resources);
			foreach ($resources as $params) {
				$headtag .= $this->_get_resource_code($spec_type, $params) . "\r\n";
			}
			$this->headtag($headtag);
		} elseif (is_array($resources)) {
			foreach ($resources as $type => $res) {
				$headtag .= $this->import_resource($res, $type);
			}
			$this->headtag($headtag);
		}

		return $headtag;
	}

	/**
	 * 配置seo信息
	 *
	 * @param array/string $seo_info
	 * @return void
	 */
	function _config_seo($seo_info, $ext_info = null) {
		if (is_string($seo_info)) {
			$this->_assign_seo($seo_info, $ext_info);
		} elseif (is_array($seo_info)) {
			foreach ($seo_info as $type => $info) {
				$this->_assign_seo($type, $info);
			}
		}
	}

	function _assign_seo($type, $info) {
		$this->_init_view();
		$_seo_info = $this->_view->get_template_vars('_seo_info');
		if (is_array($_seo_info)) {
			$_seo_info[$type] = $info;
		} else {
			$_seo_info = array($type => $info);
		}
		$this->assign('_seo_info', $_seo_info);
		$this->assign('page_seo', $this->_get_seo_code($_seo_info));
	}

	function _get_seo_code($_seo_info) {
		$html = '';
		foreach ($_seo_info as $type => $info) {
			$info = trim(htmlspecialchars($info));
			switch ($type) {
			case 'title':
				$html .= "<{$type}>{$info}</{$type}>";
				break;
			case 'description':
			case 'keywords':
			default:
				$html .= "<meta name=\"{$type}\" content=\"{$info}\" />";
				break;
			}
			$html .= "\r\n";
		}
		return $html;
	}

	/**
	 *    获取资源数据
	 *
	 *    @author    Garbin
	 *    @param     mixed $resources
	 *    @return    array
	 */
	function _get_resource_data($resources) {
		$return = array();
		if (is_string($resources)) {
			$items = explode(',', $resources);
			array_walk($items, create_function('&$val, $key', '$val = trim($val);'));
			foreach ($items as $path) {
				$return[] = array('path' => $path, 'attr' => '');
			}
		} elseif (is_array($resources)) {
			foreach ($resources as $item) {
				!isset($item['attr']) && $item['attr'] = '';
				$return[] = $item;
			}
		}

		return $return;
	}

	/**
	 *    获取资源文件的HTML代码
	 *
	 *    @author    Garbin
	 *    @param     string $type
	 *    @param     array  $params
	 *    @return    string
	 */
	function _get_resource_code($type, $params) {
		switch ($type) {
		case 'script':
			$pre = '<script charset="utf-8" type="text/javascript"';
			$path = ' src="' . $this->_get_resource_url($params['path']) . '"';
			$attr = ' ' . $params['attr'];
			$tail = '></script>';
			break;
		case 'style':
			$pre = '<link rel="stylesheet" type="text/css"';
			$path = ' href="' . $this->_get_resource_url($params['path']) . '"';
			$attr = ' ' . $params['attr'];
			$tail = ' />';
			break;
		}
		$html = $pre . $path . $attr . $tail;

		return $html;
	}

	/**
	 *    获取真实的资源路径
	 *
	 *    @author    Garbin
	 *    @param     string $res
	 *    @return    void
	 */
	function _get_resource_url($res) {
		$res_par = explode(':', $res);
		$url_type = $res_par[0];
		$return = '';
		switch ($url_type) {
		case 'url':
			$return = $res_par[1];
			break;
		case 'res':
			$return = '{res file="' . $res_par[1] . '"}';
			break;
		default:
			$res_path = empty($res_par[1]) ? $res : $res_par[1];
			$return = '{lib file="' . $res_path . '"}';
			break;
		}

		return $return;
	}

	function display($f) {
		if ($this->_hook('on_display', array('display_file' => &$f))) {
			return;
		}
		$this->assign('site_url', SITE_URL);
		$this->assign('real_site_url', defined('IN_BACKEND') ? dirname(site_url()) : site_url());
		$this->assign('ecmall_version', VERSION);
		$this->assign('random_number', rand());

		/* 语言项 */
		$this->assign('lang', Lang::get());

		/* 用户信息 */
		$this->assign('visitor', isset($this->visitor) ? $this->visitor->info : array());

		$this->assign('charset', CHARSET);
		$this->assign('price_format', Conf::get('price_format'));
		$this->assign('async_sendmail', $this->_async_sendmail());
		$this->_assign_query_info();

		parent::display($f);

		if ($this->_hook('end_display', array('display_file' => &$f))) {
			return;
		}
	}

	/* 页面查询信息 */
	function _assign_query_info() {
		$query_time = ecm_microtime() - START_TIME;

		$this->assign('query_time', $query_time);
		$db = &db();
		$this->assign('query_count', $db->_query_count);
		$this->assign('query_user_count', $this->_session->get_users_count());

		/* 内存占用情况 */
		if (function_exists('memory_get_usage')) {
			$this->assign('memory_info', memory_get_usage() / 1048576);
		}

		$this->assign('gzip_enabled', $this->gzip_enabled());
		$this->assign('site_domain', urlencode(get_domain()));
		$this->assign('ecm_version', VERSION . ' ' . RELEASE);
	}

	function gzip_enabled() {
		static $enabled_gzip = NULL;

		if ($enabled_gzip === NULL) {
			$enabled_gzip = (defined('ENABLED_GZIP') && ENABLED_GZIP === 1 && function_exists('ob_gzhandler'));
		}

		return $enabled_gzip;
	}

	/**
	 *    显示错误警告
	 *
	 *    @author    Garbin
	 *    @param    none
	 *    @return    void
	 */
	function show_warning() {
		$args = func_get_args();
		call_user_func_array('show_warning', $args);
	}

	/**
	 *    显示提示消息
	 *
	 *    @author    Garbin
	 *    @return    void
	 */
	function show_message() {
		$args = func_get_args();
		call_user_func_array('show_message', $args);
	}
	/**
	 * Make a error message by JSON format
	 *
	 * @param   string  $msg
	 *
	 * @return  void
	 */
	function json_error($msg = '', $retval = null, $jqremote = false) {
		if (!empty($msg)) {
			$msg = Lang::get($msg);
		}
		$result = array('done' => false, 'msg' => $msg);
		if (isset($retval)) {
			$result['retval'] = $retval;
		}

		$this->json_header();
		$json = ecm_json_encode($result);
		if ($jqremote === false) {
			$jqremote = isset($_GET['jsoncallback']) ? trim($_GET['jsoncallback']) : false;
		}
		if ($jqremote) {
			$json = $jqremote . '(' . $json . ')';
		}

		echo $json;
	}

	/**
	 * Make a successfully message
	 *
	 * @param   mixed   $retval
	 * @param   string  $msg
	 *
	 * @return  void
	 */
	function json_result($retval = '', $msg = '', $jqremote = false) {
		if (!empty($msg)) {
			$msg = Lang::get($msg);
		}
		$this->json_header();
		$json = ecm_json_encode(array('done' => true, 'msg' => $msg, 'retval' => $retval));
		if ($jqremote === false) {
			$jqremote = isset($_GET['jsoncallback']) ? trim($_GET['jsoncallback']) : false;
		}
		if ($jqremote) {
			$json = $jqremote . '(' . $json . ')';
		}

		echo $json;
	}

	/**
	 * Send a Header
	 *
	 * @author weberliu
	 *
	 * @return  void
	 */
	function json_header() {
		header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
		header("Content-type:text/plain;charset=" . CHARSET, true);
	}

	/**
	 *    验证码
	 *
	 *    @author    Garbin
	 *    @return    void
	 */
	function _captcha($width, $height) {
		import('captcha.lib');
		$word = generate_code();
		$_SESSION['captcha'] = base64_encode($word);
		$code = new Captcha(array(
			'width' => $width,
			'height' => $height,
		));
		$code->display($word);
	}

	/**
	 * 重写获取分页信息
	 * @param number $page_per
	 * @param string $feilds
	 * @author Mosquito
	 * @link www.360cd.cn
	 */
	function get_page($page_per = 10, $feilds = 'page') {
		$page = empty($_REQUEST[$feilds]) ? 1 : intval($_REQUEST[$feilds]);
		$start = ($page - 1) * $page_per;
		return array('limit' => "{$start},{$page_per}", 'curr_page' => $page, 'pageper' => $page_per);
	}

	/**
	 * 重写格式化分页信息
	 * @author Mosquito
	 * @link www.360cd.cn
	 */
	function format_page(&$page, $num = 7, $feilds = 'page') {
		$page['page_count'] = ceil($page['item_count'] / $page['pageper']);
		$mid = ceil($num / 2) - 1;
		if ($page['page_count'] <= $num) {
			$from = 1;
			$to = $page['page_count'];
		} else {
			$from = $page['curr_page'] <= $mid ? 1 : $page['curr_page'] - $mid + 1;
			$to = $from + $num - 1;
			$to > $page['page_count'] && $to = $page['page_count'];
		}

		/* 生成app=goods&act=view之类的URL */
		if (preg_match('/[&|\?]?' . $feilds . '=\w+/i', $_SERVER['QUERY_STRING']) > 0) {
			$url_format = preg_replace('/[&|\?]?' . $feilds . '=\w+/i', '', $_SERVER['QUERY_STRING']);
		} else {
			$url_format = $_SERVER['QUERY_STRING'];
		}

		$page['page_links'] = array();
		$page['first_link'] = ''; // 首页链接
		$page['first_suspen'] = ''; // 首页省略号
		$page['last_link'] = ''; // 尾页链接
		$page['last_suspen'] = ''; // 尾页省略号
		for ($i = $from; $i <= $to; $i++) {
			$page['page_links'][$i] = url("{$url_format}&{$feilds}={$i}");
		}
		if (($page['curr_page'] - $from) < ($page['curr_page'] - 1) && $page['page_count'] > $num) {
			$page['first_link'] = url("{$url_format}&{$feilds}=1");
			if (($page['curr_page'] - 1) - ($page['curr_page'] - $from) != 1) {
				$page['first_suspen'] = '..';
			}
		}
		if (($to - $page['curr_page']) < ($page['page_count'] - $page['curr_page']) && $page['page_count'] > $num) {
			$page['last_link'] = url("{$url_format}&{$feilds}=" . $page['page_count']);
			if (($page['page_count'] - $page['curr_page']) - ($to - $page['curr_page']) != 1) {
				$page['last_suspen'] = '..';
			}
		}

		$page['prev_link'] = $page['curr_page'] > $from ? url("{$url_format}&{$feilds}=" . ($page['curr_page'] - 1)) : "";
		$page['next_link'] = $page['curr_page'] < $to ? url("{$url_format}&{$feilds}=" . ($page['curr_page'] + 1)) : "";
	}

	/**
	 *    获取分页信息
	 *
	 *    @author    Garbin
	 *    @return    array
	 */
	function _get_page($page_per = 10) {
		$page = empty($_REQUEST['page']) ? 1 : intval($_REQUEST['page']);
		$start = ($page - 1) * $page_per;

		return array('limit' => "{$start},{$page_per}", 'curr_page' => $page, 'pageper' => $page_per);
	}

	/**
	 * 格式化分页信息
	 * @param   array   $page
	 * @param   int     $num    显示几页的链接
	 */
	function _format_page(&$page, $num = 7) {
		$page['page_count'] = ceil($page['item_count'] / $page['pageper']);
		$mid = ceil($num / 2) - 1;
		if ($page['page_count'] <= $num) {
			$from = 1;
			$to = $page['page_count'];
		} else {
			$from = $page['curr_page'] <= $mid ? 1 : $page['curr_page'] - $mid + 1;
			$to = $from + $num - 1;
			$to > $page['page_count'] && $to = $page['page_count'];
		}

		/*
			        if (preg_match('/[&|\?]?page=\w+/i', $_SERVER['REQUEST_URI']) > 0)
			        {
			            $url_format = preg_replace('/[&|\?]?page=\w+/i', '', $_SERVER['REQUEST_URI']);
			        }
			        else
			        {
			            $url_format = $_SERVER['REQUEST_URI'];
			        }
		*/

		/* 生成app=goods&act=view之类的URL */
		if (preg_match('/[&|\?]?page=\w+/i', $_SERVER['QUERY_STRING']) > 0) {
			$url_format = preg_replace('/[&|\?]?page=\w+/i', '', $_SERVER['QUERY_STRING']);
		} else {
			$url_format = $_SERVER['QUERY_STRING'];
		}

		$page['page_links'] = array();
		$page['first_link'] = ''; // 首页链接
		$page['first_suspen'] = ''; // 首页省略号
		$page['last_link'] = ''; // 尾页链接
		$page['last_suspen'] = ''; // 尾页省略号
		for ($i = $from; $i <= $to; $i++) {
			$page['page_links'][$i] = url("{$url_format}&page={$i}");
		}
		if (($page['curr_page'] - $from) < ($page['curr_page'] - 1) && $page['page_count'] > $num) {
			$page['first_link'] = url("{$url_format}&page=1");
			if (($page['curr_page'] - 1) - ($page['curr_page'] - $from) != 1) {
				$page['first_suspen'] = '..';
			}
		}
		if (($to - $page['curr_page']) < ($page['page_count'] - $page['curr_page']) && $page['page_count'] > $num) {
			$page['last_link'] = url("{$url_format}&page=" . $page['page_count']);
			if (($page['page_count'] - $page['curr_page']) - ($to - $page['curr_page']) != 1) {
				$page['last_suspen'] = '..';
			}
		}

		$page['prev_link'] = $page['curr_page'] > $from ? url("{$url_format}&page=" . ($page['curr_page'] - 1)) : "";
		$page['next_link'] = $page['curr_page'] < $to ? url("{$url_format}&page=" . ($page['curr_page'] + 1)) : "";
	}

	/**
	 *    获取查询条件
	 *
	 *    @author    Garbin
	 *    @param    none
	 *    @return    void
	 */
	function _get_query_conditions($query_item) {
		$str = '';
		$query = array();
		foreach ($query_item as $options) {
			if (is_string($options)) {
				$field = $options;
				$options['field'] = $field;
				$options['name'] = $field;
			}
			!isset($options['equal']) && $options['equal'] = '=';
			!isset($options['assoc']) && $options['assoc'] = 'AND';
			!isset($options['type']) && $options['type'] = 'string';
			!isset($options['name']) && $options['name'] = $options['field'];
			!isset($options['handler']) && $options['handler'] = 'trim';
			if (isset($_GET[$options['name']])) {
				$input = $_GET[$options['name']];
				$handler = $options['handler'];
				$value = ($input == '' ? $input : $handler($input));
				if ($value === '' || $value === false) //若未输入，未选择，或者经过$handler处理失败就跳过
				{
					continue;
				}
				strtoupper($options['equal']) == 'LIKE' && $value = "%{$value}%";
				if ($options['type'] != 'numeric') {
					$value = "'{$value}'"; //加上单引号，安全第一
				} else {
					$value = floatval($value); //安全起见，将其转换成浮点型
				}
				$str .= " {$options['assoc']} {$options['field']} {$options['equal']} {$value}";
				$query[$options['name']] = $input;
			}
		}
		$this->assign('query', stripslashes_deep($query));

		return $str;
	}
	function _build_editor($params = array()) {
		$name = isset($params['name']) ? $params['name'] : null;
		$theme = isset($params['theme']) ? $params['theme'] : 'normal';
		$ext_js = isset($params['ext_js']) ? $params['ext_js'] : true;
		$content_css = isset($params['content_css']) ? 'content_css:"' . $params['content_css'] . '",' : null;
		$if_media = false;
		$visit = $this->visitor->get('manage_store');
		$store_id = isset($visit) ? intval($visit) : 0;
		$privs = $this->visitor->get('privs');
		if (!empty($privs)) {
			if ($privs == 'all') {
				$if_media = true;
			} else {
				$privs_array = explode(',', $privs);
				if (in_array('article|all', $privs_array)) {
					$if_media = true;
				}
			}
		}
		if (!empty($store_id) && !$if_media) {
			$store_mod = &m('store');
			$store = $store_mod->get_info($store_id);
			$sgrade_mod = &m('sgrade');
			$sgrade = $sgrade_mod->get_info($store['sgrade']);
			$functions = explode(',', $sgrade['functions']);
			if (in_array('editor_multimedia', $functions)) {
				$if_media = true;
			}
		}

		$include_js = '';

		if ($ext_js) {
			$include_js = ' <script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.config.js"></script> <script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.all.min.js"> </script><script type="text/javascript" charset="utf-8" src="/ueditor/lang/zh-cn/zh-cn.js"></script>';
		}

		/* 指定哪个(些)textarea需要编辑器 */
		if ($name === null) {
			$mode = 'mode:"textareas",';
		} else {
			$mode = 'mode:"exact",elements:"' . $name . '",';
		}

		switch ($theme) {
		case 'simple':
			$theme_config = $themes['simple'];
			break;
		case 'normal':
			$theme_config = $themes['normal'];
			break;
		default:
			$theme_config = $themes['normal'];
			break;
		}
		/* 配置界面语言 */
		$_lang = substr(LANG, 0, 2);
		switch ($_lang) {
		case 'sc':
			$lang = 'zh_cn';
			break;
		case 'tc':
			$lang = 'zh';
			break;
		case 'en':
			$lang = 'en';
			break;
		default:
			$lang = 'zh_cn';
			break;
		}
		$str = <<<EOT
$include_js
<script type="text/javascript">
var ue = UE.getEditor('{$name}');
</script>
EOT;

		return $this->_view->fetch('str:' . $str);
	}

	/**
	 *    使用编辑器
	 *
	 *    @author    Garbin
	 *    @param     array $params
	 *    @return    string
	 */
	function _build_editor2($params = array()) {
		$name = isset($params['name']) ? $params['name'] : null;
		$theme = isset($params['theme']) ? $params['theme'] : 'normal';
		$ext_js = isset($params['ext_js']) ? $params['ext_js'] : true;
		$content_css = isset($params['content_css']) ? 'content_css:"' . $params['content_css'] . '",' : null;
		$if_media = false;
		$visit = $this->visitor->get('manage_store');
		$store_id = isset($visit) ? intval($visit) : 0;
		$privs = $this->visitor->get('privs');
		if (!empty($privs)) {
			if ($privs == 'all') {
				$if_media = true;
			} else {
				$privs_array = explode(',', $privs);
				if (in_array('article|all', $privs_array)) {
					$if_media = true;
				}
			}
		}
		if (!empty($store_id) && !$if_media) {
			$store_mod = &m('store');
			$store = $store_mod->get_info($store_id);
			$sgrade_mod = &m('sgrade');
			$sgrade = $sgrade_mod->get_info($store['sgrade']);
			$functions = explode(',', $sgrade['functions']);
			if (in_array('editor_multimedia', $functions)) {
				$if_media = true;
			}
		}

		$include_js = $ext_js ? '<script type="text/javascript" src="{lib file="tiny_mce/tiny_mce.js"}"></script>' : '';

		/* 指定哪个(些)textarea需要编辑器 */
		if ($name === null) {
			$mode = 'mode:"textareas",';
		} else {
			$mode = 'mode:"exact",elements:"' . $name . '",';
		}

		/* 指定使用哪种主题 */
		$themes = array(
			'normal' => 'plugins:"inlinepopups,preview,fullscreen,paste' . ($if_media ? ',media' : '') . '",
            theme:"advanced",
            theme_advanced_buttons1:"code,fullscreen' . ($content_css ? ',preview' : '') . ',removeformat,|,bold,italic,underline,strikethrough,|," +
                "formatselect,fontsizeselect,|,forecolor,backcolor",
            theme_advanced_buttons2:"bullist,numlist,|,outdent,indent,blockquote,|,justifyleft,justifycenter," +
                "justifyright,justifyfull,|,link,unlink,charmap,image,|,pastetext,pasteword,|,undo,redo,|,media",
            theme_advanced_buttons3 : "",',
			'simple' => 'theme:"simple",',
		);
		switch ($theme) {
		case 'simple':
			$theme_config = $themes['simple'];
			break;
		case 'normal':
			$theme_config = $themes['normal'];
			break;
		default:
			$theme_config = $themes['normal'];
			break;
		}
		/* 配置界面语言 */
		$_lang = substr(LANG, 0, 2);
		switch ($_lang) {
		case 'sc':
			$lang = 'zh_cn';
			break;
		case 'tc':
			$lang = 'zh';
			break;
		case 'en':
			$lang = 'en';
			break;
		default:
			$lang = 'zh_cn';
			break;
		}

		/* 输出 */
		$str = <<<EOT
$include_js
<script type="text/javascript">
    tinyMCE.init({
        {$mode}
        {$theme_config}
        {$content_css}
        language:"{$lang}",
        convert_urls : false,
        relative_urls : false,
        remove_script_host : false,
        theme_advanced_toolbar_location:"top",
        theme_advanced_toolbar_align:"left"
});
</script>
EOT;

		return $this->_view->fetch('str:' . $str);
	}

	/**
	 *    使用swfupload
	 *
	 *    @author    Hyber
	 *    @param     array $params
	 *    @return    string
	 */
	function _build_upload($params = array()) {
		$belong = isset($params['belong']) ? $params['belong'] : 0; //上传文件所属模型
		$item_id = isset($params['item_id']) ? $params['item_id'] : 0; //所属模型的ID
		$file_size_limit = isset($params['file_size_limit']) ? $params['file_size_limit'] : '2 MB'; //默认最大2M
		$button_text = isset($params['button_text']) ? Lang::get($params['button_text']) : Lang::get('bat_upload'); //上传按钮文本
		$image_file_type = isset($params['image_file_type']) ? $params['image_file_type'] : IMAGE_FILE_TYPE;
		$upload_url = isset($params['upload_url']) ? $params['upload_url'] : 'index.php?app=swfupload';
		$button_id = isset($params['button_id']) ? $params['button_id'] : 'spanButtonPlaceholder';
		$progress_id = isset($params['progress_id']) ? $params['progress_id'] : 'divFileProgressContainer';
		$if_multirow = isset($params['if_multirow']) ? $params['if_multirow'] : 0;
		$define = isset($params['obj']) ? 'var ' . $params['obj'] . ';' : '';
		$assign = isset($params['obj']) ? $params['obj'] . ' = ' : '';
		$ext_js = isset($params['ext_js']) ? $params['ext_js'] : true;
		$ext_css = isset($params['ext_css']) ? $params['ext_css'] : true;

		$include_js = $ext_js ? '<script type="text/javascript" charset="utf-8" src="{lib file="swfupload/swfupload.js"}"></script>
<script type="text/javascript" charset="utf-8" src="{lib file="swfupload/js/handlers.js"}"></script>' : '';
		$include_css = $ext_css ? '<link type="text/css" rel="stylesheet" href="{lib file="swfupload/css/default.css"}"/>' : '';
		/* 允许类型 */
		$file_types = '';
		$image_file_type = explode('|', $image_file_type);
		foreach ($image_file_type as $type) {
			$file_types .= '*.' . $type . ';';
		}
		$file_types = trim($file_types, ';');
		$str = <<<EOT

{$include_js}
{$include_css}
<script type="text/javascript">
{$define}
$(function(){
    {$assign}new SWFUpload({
        upload_url: "{$upload_url}",
        flash_url: "{lib file="swfupload/swfupload.swf"}",
        post_params: {
            "ECM_ID": "{$_COOKIE['ECM_ID']}",
            "HTTP_USER_AGENT":"{$_SERVER['HTTP_USER_AGENT']}",
            'belong': {$belong},
            'item_id': {$item_id},
            'ajax': 1
        },
        file_size_limit: "{$file_size_limit}",
        file_types: "{$file_types}",
        custom_settings: {
            upload_target: "{$progress_id}",
            if_multirow: {$if_multirow}
        },

        // Button Settings
        button_image_url: "{lib file="swfupload/images/SmallSpyGlassWithTransperancy_17x18.png"}",
        button_width: 86,
        button_height: 18,
        button_text: '<span class="button">{$button_text}</span>',
        button_text_style: '.button { font-family: Helvetica, Arial, sans-serif; font-size: 12pt; font-weight: bold; color: #3F3D3E; } .buttonSmall { font-size: 10pt; }',
        button_text_top_padding: 0,
        button_text_left_padding: 18,
        button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
        button_cursor: SWFUpload.CURSOR.HAND,

        // The event handler functions are defined in handlers.js
        file_queue_error_handler: fileQueueError,
        file_dialog_complete_handler: fileDialogComplete,
        upload_progress_handler: uploadProgress,
        upload_error_handler: uploadError,
        upload_success_handler: uploadSuccess,
        upload_complete_handler: uploadComplete,
        button_placeholder_id: "{$button_id}",
        file_queued_handler : fileQueued
    });
});
</script>
EOT;
		return $this->_view->fetch('str:' . $str);
	}

	/**
	 *    发送邮件
	 *
	 *    @author    Garbin
	 *    @param     mixed  $to
	 *    @param     string $subject
	 *    @param     string $message
	 *    @param     int    $priority
	 *    @return    void
	 */
	function _mailto($to, $subject, $message, $priority = MAIL_PRIORITY_LOW) {
		/* 加入邮件队列，并通知需要发送 */
		$model_mailqueue = &m('mailqueue');
		$mails = array();
		$to_emails = is_array($to) ? $to : array($to);
		foreach ($to_emails as $_to) {
			$mails[] = array(
				'mail_to' => $_to,
				'mail_encoding' => CHARSET,
				'mail_subject' => $subject,
				'mail_body' => $message,
				'priority' => $priority,
				'add_time' => gmtime(),
			);
		}

		$model_mailqueue->add($mails);

		/* 默认采用异步发送邮件，这样可以解决响应缓慢的问题 */
		$this->_sendmail();
	}

	/**
	 *    发送邮件
	 *
	 *    @author    Garbin
	 *    @param     bool $is_sync
	 *    @return    void
	 */
	function _sendmail($is_sync = false) {
		if (!$is_sync) {
			/* 采用异步方式发送邮件，与模板引擎配合达到目的 */
			$_SESSION['ASYNC_SENDMAIL'] = true;

			return true;
		} else {
			/* 同步发送邮件，将异步发送的命令去掉 */
			unset($_SESSION['ASYNC_SENDMAIL']);
			$model_mailqueue = &m('mailqueue');

			return $model_mailqueue->send(5);
		}
	}

	/**
	 *     获取异步发送邮件代码
	 *
	 *    @author    Garbin
	 *    @return    string
	 */
	function _async_sendmail() {
		$script = '';
		if (isset($_SESSION['ASYNC_SENDMAIL']) && $_SESSION['ASYNC_SENDMAIL']) {
			/* 需要异步发送 */
			$async_sendmail = SITE_URL . '/index.php?app=sendmail';
			$script = '<script type="text/javascript">sendmail("' . $async_sendmail . '");</script>';
		}

		return $script;
	}
	function _get_new_message() {
		$user_id = $this->visitor->get('user_id');
		if (empty($user_id)) {
			return '';
		}
		$ms = &ms();
		return $ms->pm->check_new($user_id);
	}

	/**
	 *    计划任务守护进程
	 *
	 *    @author    Garbin
	 *    @return    void
	 */
	function _run_cron() {

		register_shutdown_function(create_function('', '
            /*if (ob_get_level() > 0)
            {
                ob_end_flush();         //输出
            }*/
            if (!is_file(ROOT_PATH . "/data/tasks.inc.php"))
            {
                $default_tasks = array(
                    "cleanup" =>
                        array (
                            "cycle" => "custom",
                            "interval" => 3600,     //每一个小时执行一次清理
                        ),
                );
                file_put_contents(ROOT_PATH . "/data/tasks.inc.php", "<?php\r\n\r\nreturn " . var_export($default_tasks, true) . ";\r\n\r\n", LOCK_EX);
            }
            import("cron.lib");
            $cron = new Crond(array(
                "task_list" => ROOT_PATH . "/data/tasks.inc.php",
                "task_path" => ROOT_PATH . "/includes/tasks",
                "lock_file" => ROOT_PATH . "/data/crond.lock"
            ));                     //计划任务实例
            $cron->execute();       //执行
        '));
	}

	/**
	 * 发送Feed
	 *
	 * @author Garbin
	 * @param
	 * @return void
	 **/
	function send_feed($event, $data) {
		$ms = &ms();
		if (!$ms->feed->feed_enabled()) {
			return;
		}

		$feed_config = $this->visitor->get('feed_config');
		$feed_config = empty($feed_config) ? Conf::get('default_feed_config') : unserialize($feed_config);
		if (!$feed_config[$event]) {
			return;
		}

		$ms->feed->add($event, $data);
	}

}

/**
 *    访问者基础类，集合了当前访问用户的操作
 *
 *    @author    Garbin
 *    @return    void
 */
class BaseVisitor extends Object {
	var $has_login = false;
	var $info = null;
	var $privilege = null;
	var $_info_key = '';
	function __construct() {
		$this->BaseVisitor();
	}
	function BaseVisitor() {
		if (!empty($_SESSION[$this->_info_key]['user_id'])) {
			$this->info = $_SESSION[$this->_info_key];
			$this->has_login = true;
		} else {
			$this->info = array(
				'user_id' => 0,
				'user_name' => Lang::get('guest'),
			);
			$this->has_login = false;
		}
	}
	function assign($user_info) {
		$_SESSION[$this->_info_key] = $user_info;
	}

	/**
	 *    获取当前登录用户的详细信息
	 *
	 *    @author    Garbin
	 *    @return    array      用户的详细信息
	 */
	function get_detail() {
		/* 未登录，则无详细信息 */
		if (!$this->has_login) {
			return array();
		}

		/* 取出详细信息 */
		static $detail = null;

		if ($detail === null) {
			$detail = $this->_get_detail();
		}

		return $detail;
	}

	/**
	 *    获取用户详细信息
	 *
	 *    @author    Garbin
	 *    @return    array
	 */
	function _get_detail() {
		$model_member = &m('member');

		/* 获取当前用户的详细信息，包括权限 */
		$member_info = $model_member->findAll(array(
			'conditions' => "member.user_id = '{$this->info['user_id']}'",
			'join' => 'has_store', //关联查找看看是否有店铺
			'fields' => 'email, password, real_name, logins, ugrade, portrait, store_id, state, sgrade , feed_config',
			'include' => array( //找出所有该用户管理的店铺
				'manage_store' => array(
					'fields' => 'user_priv.privs, store.store_name',
				),
			),
		));
		$detail = current($member_info);

		/* 如果拥有店铺，则默认管理的店铺为自己的店铺，否则需要用户自行指定 */
		if ($detail['store_id'] && $detail['state'] != STORE_APPLYING) // 排除申请中的店铺
		{
			$detail['manage_store'] = $detail['has_store'] = $detail['store_id'];
			//360cd.cn
			$store_mod = &m('store');
			$store_info = $store_mod->get_info($detail['store_id']);
			$detail['is_open_pay'] = $store_info['is_open_pay'];
			//360cd.cn
		}

		return $detail;
	}

	/**
	 *    获取当前用户的指定信息
	 *
	 *    @author    Garbin
	 *    @param     string $key  指定用户信息
	 *    @return    string  如果值是字符串的话
	 *               array   如果是数组的话
	 */
	function get($key = null) {
		$info = null;

		if (empty($key)) {
			/* 未指定key，则返回当前用户的所有信息：基础信息＋详细信息 */
			$info = array_merge((array) $this->info, (array) $this->get_detail());
		} else {
			/* 指定了key，则返回指定的信息 */
			if (isset($this->info[$key])) {
				/* 优先查找基础数据 */
				$info = $this->info[$key];
			} else {
				/* 若基础数据中没有，则查询详细数据 */
				$detail = $this->get_detail();
				$info = isset($detail[$key]) ? $detail[$key] : null;
			}
		}

		return $info;
	}

	/**
	 *    登出
	 *
	 *    @author    Garbin
	 *    @return    void
	 */
	function logout() {
		unset($_SESSION[$this->_info_key]);
	}
	function i_can($event, $privileges = array()) {
		$fun_name = 'check_' . $event;

		return $this->$fun_name($privileges);
	}

	function check_do_action($privileges) {
		$mp = APP . '|' . ACT;

		if ($privileges == 'all') {
			/* 拥有所有权限 */
			return true;
		} else {
			/* 查看当前操作是否在白名单中，如果在，则允许，否则不允许 */
			$privs = explode(',', $privileges);
			if (in_array(APP . '|all', $privs) || in_array($mp, $privs)) {
				return true;
			}

			return false;
		}
	}

}
?>
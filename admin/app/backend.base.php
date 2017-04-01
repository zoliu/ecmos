<?php

/**
 *    Desc
 *
 *    @author    Garbin
 *    @usage    none
 */
class BackendApp extends ECBaseApp {
	function __construct() {
		$this->BackendApp();
	}
	function BackendApp() {
		Lang::load(lang_file('admin/common'));
		Lang::load(lang_file('admin/' . APP));
		parent::__construct();

		//---www.360cd.cn  Mosquito---
		//引入钱包方法相关
		import('zllib/money.lib');
	}
	function login() {
		if ($this->visitor->has_login) {
			$this->show_warning('has_login');

			return;
		}
		if (!IS_POST) {
			if (Conf::get('captcha_status.backend')) {
				$this->assign('captcha', 1);
			}
			$this->display('login.html');
		} else {
			if (Conf::get('captcha_status.backend') && base64_decode($_SESSION['captcha']) != strtolower($_POST['captcha'])) {
				$this->show_warning('captcha_faild');

				return;
			}

			$user_name = trim($_POST['user_name']);
			$password = $_POST['password'];

			// $ms =& ms();
			// $user_id = $ms->user->auth($user_name, $password);
			//360cd.cn
			$admin_model = &m('admin');
			$user_id = $admin_model->doLogin($user_name, $password);
			if ($user_id < 1) {
				/* 未通过验证，提示错误信息 */
				// $this->show_warning($ms->user->get_error());
				$this->show_warning($admin_model->get_error());

				return;
			}
			//360cd.cn

			/* 通过验证，执行登陆操作 */
			if (!$this->_do_login($user_id)) {
				return;
			}

			$this->show_message('login_successed',
				'go_to_admin', 'index.php');
		}
	}

	function logout() {
		parent::logout();
		$this->show_message('logout_successed',
			'go_to_admin', 'index.php');
	}

	/**
	 * 执行登陆操作
	 *
	 * @param int $user_id
	 * @return bool
	 */
	function _do_login($user_id) {
		$mod_user = &m('member');
		$user_info = $mod_user->get(array(
			'conditions' => $user_id,
			'join' => 'manage_mall',
			'fields' => 'this.user_id, user_name, reg_time, last_login, last_ip, user_priv.privs',
		));

		if (!$user_info['privs']) {
			$this->show_warning('not_admin');

			return false;
		}

		/* 分派身份 */
		$this->visitor->assign(array(
			'user_id' => $user_info['user_id'],
			'user_name' => $user_info['user_name'],
			'reg_time' => $user_info['reg_time'],
			'last_login' => $user_info['last_login'],
			'last_ip' => $user_info['last_ip'],
		));

		/* 更新登录信息 */
		$time = gmtime();
		$ip = real_ip();
		$mod_user->edit($user_id, "last_login = '{$time}', last_ip='{$ip}', logins = logins + 1");

		return true;
	}

	/**
	 *    获取JS语言项
	 *
	 *    @author    Garbin
	 *    @param    none
	 *    @return    void
	 */
	function jslang($lang = '') {
		$lang = Lang::fetch(lang_file('admin/jslang'));
		parent::jslang($lang);
	}

	/**
	 *    后台的需要权限验证机制
	 *
	 *    @author    Garbin
	 *    @return    void
	 */
	function _run_action() {
		/* 先判断是否登录 */
		if (!$this->visitor->has_login) {
			$this->login();

			return;
		}

		/* 登录后判断是否有权限 */
		if (!$this->visitor->i_can('do_action', $this->visitor->get('privs'))) {
			$this->show_warning('no_permission');

			return;
		}

		/* 运行 */
		parent::_run_action();
	}

	function _config_view() {
		parent::_config_view();
		$this->_view->template_dir = APP_ROOT . '/templates';
		$this->_view->compile_dir = ROOT_PATH . '/temp/compiled/admin';
		$this->_view->res_base = BASE_URL . '/templates';
		$this->_view->lib_base = dirname(site_url()) . '/includes/libraries/javascript';
	}

	/**
	 *   获取商城当前模板名称
	 */
	function _get_template_name() {
		$template_name = Conf::get('template_name');
		if (!$template_name) {
			$template_name = 'default';
		}

		return $template_name;
	}

	/**
	 *    获取商城当前风格名称
	 */
	function _get_style_name() {
		$style_name = Conf::get('style_name');
		if (!$style_name) {
			$style_name = 'default';
		}

		return $style_name;
	}

	function _init_visitor() {
		$this->visitor = &env('visitor', new AdminVisitor());
	}

	/* 清除缓存 */
	function _clear_cache() {
		$cache_server = &cache_server();
		$cache_server->clear();
	}

	function display($tpl) {
		$this->assign('real_backend_url', site_url());
		parent::display($tpl);
	}
}

/**
 *    后台访问者
 *
 *    @author    Garbin
 *    @usage    none
 */
class AdminVisitor extends BaseVisitor {
	var $_info_key = 'admin_info';
	/**
	 *    获取用户详细信息
	 *
	 *    @author    Garbin
	 *    @param    none
	 *    @return    void
	 */
	function _get_detail() {
		$model_member = &m('member');
		$detail = $model_member->get(array(
			'conditions' => "member.user_id = '{$this->info['user_id']}'",
			'join' => 'manage_mall', //关联查找看看是否有店铺
		));
		unset($detail['user_id'], $detail['user_name'], $detail['reg_time'], $detail['last_login'], $detail['last_ip']);

		return $detail;
	}
}

/* 实现消息基础类接口 */
class MessageBase extends BackendApp {};

/* 实现模块基础类接口 */
class BaseModule extends BackendApp {};

/* 消息处理器 */
require ROOT_PATH . '/eccore/controller/message.base.php';

?>
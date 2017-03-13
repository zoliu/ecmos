<?php
// ini_set('display_errors', 1);
// error_reporting(E_ALL);
/**
 * 此功能用于接口性登陆或注册
 * 登陆接口格式如/?app=api&appid=[管理员后台填写的登陆appid]&appkey=[管理员后台填写的登陆appkey]&uid=[用户的email|用户的mobile|用户的code|用户的username]&act=[userLogin|emailLogin|codeLogin|mobileLogin]
 *
 * 注册接口为/?app=api&appid=[管理员后台填写的登陆appid]&appkey=[管理员后台填写的登陆appkey]&username=&email=&mobile=&password=&act=register
 * 其中username,email,mobile,不能全为空
 */
class ApiApp extends MallbaseApp {
	function __construct() {
		parent::__construct();
		$model_setting = &af('settings');
		$setting = $model_setting->getAll(); //载入系统设置数据
		$this->appid = $setting['login_appid'];
		$this->appkey = $setting['login_appkey'];
		$this->auth();
		$this->uid = isset($_GET['uid']) && !empty($_GET['uid']) ? trim($_GET['uid']) : '';

	}
	function index() {
		exit('welcome to use ecmos APIS!');
	}

	/**
	 * 验证是否俱备权限
	 * @return [type] [description]
	 */
	function auth() {
		$appid = isset($_GET['appid']) && !empty($_GET['appid']) ? trim($_GET['appid']) : '';
		$appkey = isset($_GET['appkey']) && !empty($_GET['appkey']) ? trim($_GET['appkey']) : '';
		if ($appid != $this->appid || $appkey != $this->appkey) {
			$this->toJson('-1', '权限验证出错!');
		}

	}

	public function register() {
		$username = isset($_GET['username']) && !empty($_GET['username']) ? trim($_GET['username']) : '';
		$email = isset($_GET['email']) && !empty($_GET['email']) ? trim($_GET['email']) : '';
		$mobile = isset($_GET['mobile']) && !empty($_GET['mobile']) ? trim($_GET['mobile']) : '';
		$password = isset($_GET['password']) && !empty($_GET['password']) ? trim($_GET['password']) : '';
		if (empty($username) && empty($email) && 　empty($mobile)) {
			$this->toJson(-1, '用户名，邮箱，手机号不能全为空');
		}
		$user_id = $this->_get_last_userid();
		if (empty($username)) {
			$username = '360cd_' . $user_id;
		}
		if (empty($email)) {
			$email = $username . '@zoliu.cn';
		}
		if (empty($password)) {
			$password = rand_code(8);
		}
		$data = array(
			'user_name' => $username, //360cd.cn
			'email' => $email, //360cd.cn
			'phone_mob' => $mobile, //360cd.cn
			'password' => $password, //360cd.cn
		);
		$u_id = LM('member')->add($data);
		if ($u_id) {
			$this->toJson(1, '注册成功');
		}
		$this->toJson(-2, '注册失败');
	}

	function _get_last_userid() {
		$user = LM('member')->get(array('order' => 'user_id desc'));
		if ($user) {
			return $user['user_id'] + 1;
		}
		return 2;
	}

	function userLogin() {

		if (!empty($this->uid)) {
			$user = SL('member')->get_user("user_name='{$this->uid}'");
			if ($user) {
				$this->_dologin($user['user_id']);
			}
			$this->toJson(-3, '用户不存在');
		}
		$this->toJson(-2, '用户名不能为空');
	}

	function emailLogin() {
		if (!empty($this->uid)) {
			$user = SL('member')->get_user("email='{$this->uid}'");
			if ($user) {
				$this->_dologin($user['user_id']);
			}
			$this->toJson(-3, '用户不存在');
		}
		$this->toJson(-2, '邮箱不能为空');
	}

	function mobileLogin() {
		if (!empty($this->uid)) {
			$user = SL('member')->get_user("phone_mob='{$this->uid}'");
			if ($user) {
				$this->_dologin($user['user_id']);
			}
			$this->toJson(-3, '用户不存在');
		}
		$this->toJson(-2, '手机号不能为空');
	}

	function codeLogin() {
		if (!empty($this->uid)) {
			$user = SL('member')->get_user("valid_code='{$this->uid}'");
			if ($user) {
				$this->_dologin($user['user_id']);
			}
			$this->toJson(-3, '用户不存在');
		}
		$this->toJson(-2, '登陆码不能为空');
	}

	/**
	 * 底层执行登陆并返回登陆码
	 * @param  int $user_id 用户ID
	 * @return void
	 */
	function _dologin($user_id) {
		$this->_do_login($user_id);
		$code = rand_code(25);
		LM('member')->edit($user_id, array('valid_code' => $code));
		$this->toJson(1, '登陆成功', array('login_code' => $code));
	}

	/**
	 * 输出结果
	 * @param  string $status 状态
	 * @param  string $msg    消息
	 * @param  array  $data   传入数据
	 * @return void
	 */
	function toJson($status, $msg = '', $data = array()) {
		echo json_encode(array('status' => $status, 'msg' => $msg, 'data' => $data));
		exit();
	}
}
?>
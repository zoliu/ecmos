<?php
// ini_set('display_errors', 1);
// error_reporting(E_ALL);
class WxApp extends MallbaseApp {
	function index() {
		$check_wxkey = '360cd2016';
		$wxkey = isset($_GET['wxkey']) ? trim($_GET['wxkey']) : '';
		if (empty($wxkey) || $check_wxkey != $wxkey) {
			echo 'invalid hacker';
			exit;
		}
		$func = isset($_GET['wxType']) ? trim($_GET['wxType']) : 0;
		if ($func == 'oauth') {
			$this->oauth();
		}
	}

	function oauth() {
		$openid = isset($_GET['openid']) && !empty($_GET['openid']) ? trim($_GET['openid']) : '';
		if (empty($openid)) {
			return 0;
		}
		$tourl = isset($_GET['tourl']) && !empty($_GET['tourl']) ? trim($_GET['tourl']) : '';
		$tourl = urldecode($tourl);
		$member = &m("member");
		$ms = &ms(); //连接用户中心
		$user_info = $member->get("wx_openid='{$openid}'");
		if (!$user_info) {

			$user_name = isset($_GET['nickname']) && !empty($_GET['nickname']) ? trim($_GET['nickname']) : '';
			$password = mt_rand(11111111, 99999999);
			$email = md5($user_name) . '@126.com';

			$user_id = $ms->user->register($user_name, $password, $email);
			if (!$user_id) {
				$this->show_warning($ms->user->get_error());
				return;
			}
			$member->edit($user_id, array('wx_openid' => $openid));
		} else {
			$user_id = $user_info['user_id'];
		}
		$this->_do_login($user_id);

		/* 同步登陆外部系统 */
		$synlogin = $ms->user->synlogin($user_id);
		header('Location:' . $tourl);

	}

	function toLogs($data) {
		file_put_contents('logs.txt', var_export($data, 1), FILE_APPEND);
	}
}
<?php

/**
 *    Desc
 *
 *    @author    Garbin
 *    @usage    none
 */
class MemberApp extends MemberbaseApp {
	public $_feed_enabled = false;

	public function __construct() {
		$this->MemberApp();
	}

	public function MemberApp() {
		parent::__construct();
		$ms = &ms();
		$this->_feed_enabled = $ms->feed->feed_enabled();
		$this->assign('feed_enabled', $this->_feed_enabled);

	}

	public function index() {
		/* 清除新短消息缓存 */
		$cache_server = &cache_server();
		$cache_server->delete('new_pm_of_user_' . $this->visitor->get('user_id'));

		$user = $this->visitor->get();
		$user_mod = &m('member');
		$info = $user_mod->get_info($user['user_id']);
		$user['portrait'] = portrait($user['user_id'], $info['portrait'], 'middle');
		$this->assign('user', $user);

		//360cd.cn
		$this->assign('user_info', $info);
		$member_ext_mod = &m('member_ext');
		$user_ext = $member_ext_mod->get($user['user_id']);
		$this->assign('user_ext', $user_ext);
		$member_level = &m('member_grade');
		$member_level_info = $member_level->getOptions();
		$this->assign('options_user_level', $member_level_info);
		//360cd.cn

		// $member_grade_model = &m('member_grade');
		// $member_grade_model->updateGrade($user['user_id']);

		//---www.360cd.cn  Mosquito---
		//360cd.cn 余额支付
		$my_user_id = $this->visitor->get('user_id');
		$money_info = Money::init()->get_account($my_user_id);
		$this->assign('money_info', $money_info);

		//360cd.cn

		/* 店铺信用和好评率 */
		if ($user['has_store']) {
			$store_mod = &m('store');
			$store = $store_mod->get_info($user['has_store']);
			$step = intval(Conf::get('upgrade_required'));
			$step < 1 && $step = 5;
			$store['credit_image'] = $this->_view->res_base . '/images/' . $store_mod->compute_credit($store['credit_value'], $step);
			$this->assign('store', $store);
			$this->assign('store_closed', STORE_CLOSED);
		}
		$goodsqa_mod = &m('goodsqa');
		$groupbuy_mod = &m('groupbuy');
		/* 买家提醒：待付款、待确认、待评价订单数 */
		$order_mod = &m('order');
		$sql1 = "SELECT COUNT(*) FROM {$order_mod->table} WHERE buyer_id = '{$user['user_id']}' AND status = '" . ORDER_PENDING . "'";
		$sql2 = "SELECT COUNT(*) FROM {$order_mod->table} WHERE buyer_id = '{$user['user_id']}' AND status = '" . ORDER_SHIPPED . "'";
		$sql3 = "SELECT COUNT(*) FROM {$order_mod->table} WHERE buyer_id = '{$user['user_id']}' AND status = '" . ORDER_FINISHED . "' AND evaluation_status = 0";
		$sql4 = "SELECT COUNT(*) FROM {$goodsqa_mod->table} WHERE user_id = '{$user['user_id']}' AND reply_content !='' AND if_new = '1' ";
		$sql5 = "SELECT COUNT(*) FROM " . DB_PREFIX . "groupbuy_log AS log LEFT JOIN {$groupbuy_mod->table} AS gb ON gb.group_id = log.group_id WHERE log.user_id='{$user['user_id']}' AND gb.state = " . GROUP_CANCELED;
		$sql6 = "SELECT COUNT(*) FROM " . DB_PREFIX . "groupbuy_log AS log LEFT JOIN {$groupbuy_mod->table} AS gb ON gb.group_id = log.group_id WHERE log.user_id='{$user['user_id']}' AND gb.state = " . GROUP_FINISHED;
		$buyer_stat = array(
			'pending' => $order_mod->getOne($sql1),
			'shipped' => $order_mod->getOne($sql2),
			'finished' => $order_mod->getOne($sql3),
			'my_question' => $goodsqa_mod->getOne($sql4),
			'groupbuy_canceled' => $groupbuy_mod->getOne($sql5),
			'groupbuy_finished' => $groupbuy_mod->getOne($sql6),
		);
		$sum = array_sum($buyer_stat);
		$buyer_stat['sum'] = $sum;
		$this->assign('buyer_stat', $buyer_stat);

		/* 卖家提醒：待处理订单和待发货订单 */
		if ($user['has_store']) {

			$sql7 = "SELECT COUNT(*) FROM {$order_mod->table} WHERE seller_id = '{$user['user_id']}' AND status = '" . ORDER_SUBMITTED . "'";
			$sql8 = "SELECT COUNT(*) FROM {$order_mod->table} WHERE seller_id = '{$user['user_id']}' AND status = '" . ORDER_ACCEPTED . "'";
			$sql9 = "SELECT COUNT(*) FROM {$goodsqa_mod->table} WHERE store_id = '{$user['user_id']}' AND reply_content ='' ";
			$sql10 = "SELECT COUNT(*) FROM {$groupbuy_mod->table} WHERE store_id='{$user['user_id']}' AND state = " . GROUP_END;
			$seller_stat = array(
				'submitted' => $order_mod->getOne($sql7),
				'accepted' => $order_mod->getOne($sql8),
				'replied' => $goodsqa_mod->getOne($sql9),
				'groupbuy_end' => $goodsqa_mod->getOne($sql10),
			);

			$this->assign('seller_stat', $seller_stat);
		}
		/* 卖家提醒： 店铺等级、有效期、商品数、空间 */
		if ($user['has_store']) {
			$store_mod = &m('store');
			$store = $store_mod->get_info($user['has_store']);

			$grade_mod = &m('sgrade');
			$grade = $grade_mod->get_info($store['sgrade']);

			$goods_mod = &m('goods');
			$goods_num = $goods_mod->get_count_of_store($user['has_store']);
			$uploadedfile_mod = &m('uploadedfile');
			$space_num = $uploadedfile_mod->get_file_size($user['has_store']);
			$sgrade = array(
				'grade_name' => $grade['grade_name'],
				'add_time' => empty($store['end_time']) ? 0 : sprintf('%.2f', ($store['end_time'] - gmtime()) / 86400),
				'goods' => array(
					'used' => $goods_num,
					'total' => $grade['goods_limit']),
				'space' => array(
					'used' => sprintf("%.2f", floatval($space_num) / (1024 * 1024)),
					'total' => $grade['space_limit']),
			);
			$this->assign('sgrade', $sgrade);

		}

		/* 待审核提醒 */
		if ($user['state'] != '' && $user['state'] == STORE_APPLYING) {
			$this->assign('applying', 1);
		}
		$this->assign('system_notice', $this->_get_system_notice($_SESSION['member_role']));

		/* 当前位置 */
		$this->_curlocal(LANG::get('member_center'), url('app=member'),
			LANG::get('overview'));

		/* 当前用户中心菜单 */
		$this->_curitem('overview');
		$this->_config_seo('title', Lang::get('member_center'));
		$this->display('member.index.html');
	}

	public function _get_system_notice($member_role = 'buyer_admin') {
		// 根据不同的用户角色（卖家或买家），在用户中心首页显示不同的文章
		if ($member_role == 'seller_admin') {
			$article_cate_id = 2;
		} else {
			$article_cate_id = 1;
		}
		$article_mod = &m('article');
		$acategory_mod = &m('acategory');

		$cate_ids = $acategory_mod->get_descendant($article_cate_id);
		if ($cate_ids) {
			$conditions = ' AND cate_id ' . db_create_in($cate_ids);
		} else {
			$conditions = '';
		}
		//var_dump($cate_ids);exit();
		$data = $article_mod->find(array(
			'conditions' => 'code = "" AND if_show=1 AND store_id=0 ' . $conditions,
			'fields' => 'article_id, title',
			'limit' => 5,
			'order' => 'sort_order ASC, article_id DESC',
		));
		return $data;
	}

	/**
	 *    注册一个新用户
	 *
	 *    @author    Garbin
	 *    @return    void
	 */
	public function register() {
		if ($this->visitor->has_login) {
			//$this->show_warning('has_login');
			//---www.360cd.cn  Mosquito---
			header('Location: index.php?app=default');
			return;
		}

		$user_model = &m('member');
		if (!IS_POST) {
			if (!empty($_GET['ret_url'])) {
				$ret_url = trim($_GET['ret_url']);
			} else {
				if (isset($_SERVER['HTTP_REFERER'])) {
					$ret_url = $_SERVER['HTTP_REFERER'];
				} else {
					$ret_url = SITE_URL . '/index.php';
				}
			}
			$this->assign('ret_url', rawurlencode($ret_url));
			$this->_curlocal(LANG::get('user_register'));
			$this->_config_seo('title', Lang::get('user_register') . ' - ' . Conf::get('site_title'));

			if (Conf::get('captcha_status.register')) {
				$this->assign('captcha', 1);
			}

			//---www.360cd.cn  Mosquito---
			//通过分享链接注册
			$parent_id = intval($_GET['parent_id']) ? intval($_GET['parent_id']) : ADMIN_ID;
			$parent_info = $user_model->get($parent_id);
			$this->assign('parent_name', $parent_info['user_name']);

			/* 导入jQuery的表单验证插件 */
			$this->import_resource('jquery.plugins/jquery.validate.js');
			$this->display('member.register.html');
		} else {
			if (!$_POST['agree']) {
				$this->show_warning('agree_first');

				return;
			}
			if (Conf::get('captcha_status.register') && base64_decode($_SESSION['captcha']) != strtolower($_POST['captcha'])) {
				$this->show_warning('captcha_failed');
				return;
			}
			if ($_POST['password'] != $_POST['password_confirm']) {
				/* 两次输入的密码不一致 */
				$this->show_warning('inconsistent_password');
				return;
			}

			/* 注册并登陆 */
			$user_name = trim($_POST['user_name']);
			$password = $_POST['password'];
			$email = trim($_POST['email']);
			$passlen = strlen($password);
			$user_name_len = strlen($user_name);
			if ($user_name_len < 3 || $user_name_len > 25) {
				$this->show_warning('user_name_length_error');

				return;
			}
			if ($passlen < 6 || $passlen > 20) {
				$this->show_warning('password_length_error');

				return;
			}
			if (!is_email($email)) {
				$this->show_warning('email_error');

				return;
			}

			$ms = &ms(); //连接用户中心
			$user_id = $ms->user->register($user_name, $password, $email);

			if (!$user_id) {
				$this->show_warning($ms->user->get_error());

				return;
			}

			//<<<---www.360cd.cn  Mosquito---
			//插入用户其他信息
			$data = array();
			$data['user_id'] = $user_id;

			$parent_name = trim($_POST['parent_name']);
			$parent_info = $user_model->get("user_name = '{$parent_name}'");
			if (!$parent_info) {
				$parent_info = $user_model->get(ADMIN_ID);
			}
			if ($parent_info) {
				$data['parent_id'] = intval($parent_info['user_id']);
				$data['parent_path'] = $parent_info['parent_path'] . ',' . $data['parent_id'];
			}

			SL('member')->modify_user($data); //修改用户信息

			//---www.360cd.cn  Mosquito--->>>

			$this->_hook('after_register', array('user_id' => $user_id));
			//登录
			$this->_do_login($user_id);

			/* 同步登陆外部系统 */
			$synlogin = $ms->user->synlogin($user_id);

			#TODO 可能还会发送欢迎邮件

			//360cd.cn
			$point = &m("point_set");
			$point->registerPoint($user_id);
			//360cd.cn

			$this->show_message(Lang::get('register_successed') . $synlogin,
				'back_before_register', rawurldecode($_POST['ret_url']),
				'enter_member_center', 'index.php?app=member',
				'apply_store', 'index.php?app=apply'
			);
		}
	}

	/**
	 *    检查用户是否存在
	 *
	 *    @author    Garbin
	 *    @return    void
	 */
	public function check_user() {
		$user_name = empty($_GET['user_name']) ? null : trim($_GET['user_name']);
		if (!$user_name) {
			echo ecm_json_encode(false);

			return;
		}
		$ms = &ms();

		echo ecm_json_encode($ms->user->check_username($user_name));
	}

	/**
	 *    修改基本信息
	 *
	 *    @author    Hyber
	 *    @usage    none
	 */
	public function profile() {

		$user_id = $this->visitor->get('user_id');
		if (!IS_POST) {
			/* 当前位置 */
			$this->_curlocal(LANG::get('member_center'), 'index.php?app=member',
				LANG::get('basic_information'));

			/* 当前用户中心菜单 */
			$this->_curitem('my_profile');

			/* 当前所处子菜单 */
			$this->_curmenu('basic_information');

			$ms = &ms(); //连接用户系统
			$edit_avatar = $ms->user->set_avatar($this->visitor->get('user_id')); //获取头像设置方式

			$model_user = &m('member');
			$profile = $model_user->get_info(intval($user_id));
			$profile['portrait'] = portrait($profile['user_id'], $profile['portrait'], 'middle');
			$this->assign('profile', $profile);
			$this->import_resource(array(
				'script' => 'jquery.plugins/jquery.validate.js',
			));
			$this->assign('edit_avatar', $edit_avatar);
			$this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('my_profile'));
			if (get('ajax_edit')) {
				$this->display('member.profile.ajax.html');
				return;
			}
			if (get('portrait')) {
				$this->display('member.profile.portrait.html');
				return;
			}
			$this->display('member.profile.html');
		} else {
			$data = array(
				'real_name' => $_POST['real_name'],
				'gender' => $_POST['gender'],
				'birthday' => $_POST['birthday'],
				'im_msn' => $_POST['im_msn'],
				'im_qq' => $_POST['im_qq'],
			);

			if (!empty($_FILES['portrait'])) {
				$portrait = $this->_upload_portrait($user_id);
				if ($portrait === false) {
					return;
				}
				$data['portrait'] = $portrait;
			}
			if (isset($_POST['portrait'])) {
				$data = array();
				$data['portrait'] = $_POST['portrait'];
			}

			$model_user = &m('member');
			$model_user->edit($user_id, $data);
			if ($model_user->has_error()) {
				$this->show_warning($model_user->get_error());

				return;
			}

			$this->show_message('edit_profile_successed');
		}
	}
	/**
	 *    修改密码
	 *
	 *    @author    Hyber
	 *    @usage    none
	 */
	public function password() {
		$user_id = $this->visitor->get('user_id');
		if (!IS_POST) {
			/* 当前位置 */
			$this->_curlocal(LANG::get('member_center'), 'index.php?app=member',
				LANG::get('edit_password'));

			/* 当前用户中心菜单 */
			$this->_curitem('my_profile');

			/* 当前所处子菜单 */
			$this->_curmenu('edit_password');
			$this->import_resource(array(
				'script' => 'jquery.plugins/jquery.validate.js',
			));
			$this->_config_seo('title', Lang::get('user_center') . ' - ' . Lang::get('edit_password'));
			$this->display('member.password.html');
		} else {
			/* 两次密码输入必须相同 */
			$orig_password = $_POST['orig_password'];
			$new_password = $_POST['new_password'];
			$confirm_password = $_POST['confirm_password'];
			if ($new_password != $confirm_password) {
				$this->show_warning('twice_pass_not_match');

				return;
			}
			if (!$new_password) {
				$this->show_warning('no_new_pass');

				return;
			}
			$passlen = strlen($new_password);
			if ($passlen < 6 || $passlen > 20) {
				$this->show_warning('password_length_error');

				return;
			}

			/* 修改密码 */
			$ms = &ms(); //连接用户系统
			$result = $ms->user->edit($this->visitor->get('user_id'), $orig_password, array(
				'password' => $new_password,
			));
			if (!$result) {
				/* 修改不成功，显示原因 */
				$this->show_warning($ms->user->get_error());

				return;
			}

			$this->show_message('edit_password_successed');
		}
	}
	/**
	 *    修改电子邮箱
	 *
	 *    @author    Hyber
	 *    @usage    none
	 */
	public function email() {
		$user_id = $this->visitor->get('user_id');
		if (!IS_POST) {
			/* 当前位置 */
			$this->_curlocal(LANG::get('member_center'), 'index.php?app=member',
				LANG::get('edit_email'));

			/* 当前用户中心菜单 */
			$this->_curitem('my_profile');

			/* 当前所处子菜单 */
			$this->_curmenu('edit_email');
			$this->import_resource(array(
				'script' => 'jquery.plugins/jquery.validate.js',
			));
			$this->_config_seo('title', Lang::get('user_center') . ' - ' . Lang::get('edit_email'));
			$this->display('member.email.html');
		} else {
			$orig_password = $_POST['orig_password'];
			$email = isset($_POST['email']) ? trim($_POST['email']) : '';
			$captcha = $_POST['captcha'];
			if (!$email) {
				$this->show_warning('email_required');

				return;
			}
			if (!is_email($email)) {
				$this->show_warning('email_error');

				return;
			}

			//360cd.cd 对比输入的验证码是否与系统发送的一致 fay
			if (trim($captcha) != $_SESSION["modify_email"]) {

				$this->show_warning('captcha_failed');

				return;
			}

			$_SESSION["modify_email"] = ""; //清除session
			$ms = &ms(); //连接用户系统
			$result = $ms->user->edit($this->visitor->get('user_id'), $orig_password, array(
				'email' => $email,
			));
			if (!$result) {
				$this->show_warning($ms->user->get_error());

				return;
			}

			$this->show_message('edit_email_successed');
		}
	}

	/**
	 * Feed设置
	 *
	 * @author Garbin
	 * @param
	 * @return void
	 **/
	public function feed_settings() {
		if (!$this->_feed_enabled) {
			$this->show_warning('feed_disabled');
			return;
		}
		if (!IS_POST) {
			/* 当前位置 */
			$this->_curlocal(LANG::get('member_center'), 'index.php?app=member',
				LANG::get('feed_settings'));

			/* 当前用户中心菜单 */
			$this->_curitem('my_profile');

			/* 当前所处子菜单 */
			$this->_curmenu('feed_settings');
			$this->_config_seo('title', Lang::get('user_center') . ' - ' . Lang::get('feed_settings'));

			$user_feed_config = $this->visitor->get('feed_config');
			$default_feed_config = Conf::get('default_feed_config');
			$feed_config = !$user_feed_config ? $default_feed_config : unserialize($user_feed_config);

			$buyer_feed_items = array(
				'store_created' => Lang::get('feed_store_created.name'),
				'order_created' => Lang::get('feed_order_created.name'),
				'goods_collected' => Lang::get('feed_goods_collected.name'),
				'store_collected' => Lang::get('feed_store_collected.name'),
				'goods_evaluated' => Lang::get('feed_goods_evaluated.name'),
				'groupbuy_joined' => Lang::get('feed_groupbuy_joined.name'),
			);
			$seller_feed_items = array(
				'goods_created' => Lang::get('feed_goods_created.name'),
				'groupbuy_created' => Lang::get('feed_groupbuy_created.name'),
			);
			$feed_items = $buyer_feed_items;
			if ($this->visitor->get('manage_store')) {
				$feed_items = array_merge($feed_items, $seller_feed_items);
			}
			$this->assign('feed_items', $feed_items);
			$this->assign('feed_config', $feed_config);
			$this->display('member.feed_settings.html');
		} else {
			$feed_settings = serialize($_POST['feed_config']);
			$m_member = &m('member');
			$m_member->edit($this->visitor->get('user_id'), array(
				'feed_config' => $feed_settings,
			));
			$this->show_message('feed_settings_successfully');
		}
	}

	public function edit_phone_mob() {
		$user_id = $this->visitor->get('user_id');
		if (!IS_POST) {
			/* 当前位置 */
			$this->_curlocal(LANG::get('member_center'), url('app=member'),
				LANG::get('my_profile'), url('app=member&act=profile'),
				LANG::get('edit_phone_mob'));
			$this->_curitem('my_profile');
			$this->_curmenu('edit_phone_mob');
			$this->import_resource(array(
				'script' => 'jquery.plugins/jquery.validate.js',
			));

			$this->display("member.phone.html");
		} else {
			$password = $_POST['password'];
			//$vcode  = intval($_POST['vcode']);
			$phone_mob = isset($_POST['phone_mob']) ? trim($_POST['phone_mob']) : '';

			if (!$phone_mob) {
				$this->show_warning('phone_required');

				return;
			}
			if (!$this->check_mobile($phone_mob)) {
				$this->show_warning('手机号已存在');
				return;
			}

			/*if(!$vcode || $_SESSION['email_vcode']!=$vcode)
				            {
				            $this->show_warning('phone_valid_error');

				            return;
			*/
			$user_mob = &m("member");
			$member_data = $user_mob->get($user_id);
			if ($member_data['password'] != md5($password)) {
				$this->show_warning('密码验证失败');
				return;
			}
			$result = $user_mob->edit($user_id, array(
				'phone_mob' => $phone_mob,
			));
			if (!$result) {
				$this->show_warning($ms->user->get_error());
				return;
			}

			$_SESSION['email_vcode'] = ""; //清除session
			$this->show_message('edit_phone_successed');
		}
	}

	public function send_email_valid($user_id) {
		//360cd.cn
		$member_model = &m('member');
		$where = $user_id;
		$member_data = $member_model->get($where);
		if (!$member_data) {
			//此处填写数据不存在内容
			return 0;
		}
		//360cd.cn
		$to = $member_data['email'];
		$subject = "【修改手机验证】";
		$vcode = mt_rand(111111, 999999);
		$content = "您修改手机的邮箱修改验证码是:" . $vcode;
		$_SESSION['email_vcode'] = $vcode;
		$this->_mailto($to, $subject, $content);
	}

	/*
		    发送电子邮件
		    360cd.cn seema
	*/
	public function send_email() {
		$user_id = $this->visitor->get('user_id');
		$this->send_email_valid($user_id);
		//$result=$this->_sendmail(1);

		echo ecm_json_encode(true);

	}
	//检查手机号是否已经存在
	public function check_mobile($mobile) {
		if (!$mobile) {

			return 0;
		}
		//360cd.cn
		$member_model = &m('member');
		$where = " phone_mob='{$mobile}'";
		$member_data = $member_model->get($where);
		if ($member_data) {
			return 0;
		}
		return 1;

	}

	//检查手机号
	public function check_phone() {
		$phone_mob = empty($_GET['phone_mob']) ? null : trim($_GET['phone_mob']);
		if (!$phone_mob) {
			echo ecm_json_encode(false);

			return;
		}
		$result = LM('member')->where("phone_mob='" . $phone_mob . "'")->get();
		if ($result) {
			echo ecm_json_encode(true);
		} else {
			echo ecm_json_encode(false);
		}

	}

	/**
	 *    三级菜单
	 *
	 *    @author    Hyber
	 *    @return    void
	 */
	public function _get_member_submenu() {
		$submenus = array(
			array(
				'name' => 'basic_information',
				'url' => 'index.php?app=member&amp;act=profile',
			),
			array(
				'name' => 'edit_password',
				'url' => 'index.php?app=member&amp;act=password',
			),
			array(
				'name' => 'edit_email',
				'url' => 'index.php?app=member&amp;act=email',
			),
			array(

				'name' => 'edit_phone_mob',

				'url' => 'index.php?app=member&amp;act=edit_phone_mob',

			),
		);
		if ($this->_feed_enabled) {
			$submenus[] = array(
				'name' => 'feed_settings',
				'url' => 'index.php?app=member&amp;act=feed_settings',
			);
		}

		return $submenus;
	}

	/**
	 * 上传头像
	 *
	 * @param int $user_id
	 * @return mix false表示上传失败,空串表示没有上传,string表示上传文件地址
	 */
	public function _upload_portrait($user_id) {
		$file = $_FILES['portrait'];
		if ($file['error'] != UPLOAD_ERR_OK) {
			return '';
		}
		import('uploader.lib');
		$uploader = new Uploader();
		$uploader->allowed_type(IMAGE_FILE_TYPE);
		$uploader->addFile($file);
		if ($uploader->file_info() === false) {
			$this->show_warning($uploader->get_error(), 'go_back', 'index.php?app=member&amp;act=profile');
			return false;
		}
		$uploader->root_dir(ROOT_PATH);
		return $uploader->save('data/files/mall/portrait/' . ceil($user_id / 500), $user_id);
	}
	//提交订单时验证密码  360cd.cn  seema
	public function valid_pwd() {
		$password = isset($_GET['password']) && !empty($_GET['password']) ? trim($_GET['password']) : '';
		if (empty($password)) {
			echo ecm_json_encode(false);
			return;
		}

		$user_id = $this->visitor->get('user_id');

		//360cd.cn
		$money_model = &m('money');
		$where = "user_id=" . $user_id . " and password ='" . md5($password) . "'";
		$money_info = $money_model->get($where);

		if (!$money_info) {

			echo ecm_json_encode(false);
			return;
		}
		echo ecm_json_encode(true);

	}
	//手机版用户首页  360cd.cn  seema
	public function wap_index() {
		$user_id = $this->visitor->get("user_id");
		$user_mob = &m("member");
		$info = $user_mob->get($user_id);
		
		$this->display('member.index.html');
	}
}

<?php

//钱包状态
define('MONEY_S_OPEN', 1); //启用
define('MONEY_S_LOCK', 2); //锁定

//资金流向
define('FLOW_IN', 1); //收入
define('FLOW_OUT', 2); //支出

//钱包记录状态
define('MONEY_L_S_NO', 1); //无效
define('MONEY_L_S_AUDIT', 2); //待审核
define('MONEY_L_S_GO', 3); //进行中
define('MONEY_L_S_OK', 4); //完成

//钱包记录类型
define('MONEY_L_T_CZ', 1); //充值
define('MONEY_L_T_ZZ', 2); //转账
define('MONEY_L_T_TX', 3); //提现
define('MONEY_L_T_TK', 9); //退款
define('MONEY_L_T_BUYER', 4); //购买商品
define('MONEY_L_T_SELLER', 5); //卖出商品
define('MONEY_L_T_SYSTEM', 6); //系统操作
define('MONEY_L_T_BUY_TC', 7); //推荐会员购物提成
define('MONEY_L_T_SELL_TC', 8); //推荐商家销售提成

import('zllib/methods.lib');

/**
 * 用户钱包公用方法类
 *
 * @author Mosquito
 * @link www.360cd.cn
 */
class Money {

	protected $money_model;
	protected $money_log_model;
	protected $bank_model;
	protected $member_model;

	function __construct() {
		$this->money_model = &m('money');
		$this->money_log_model = &m('money_log');
		$this->bank_model = &m('bank');
		$this->member_model = &m('member');
	}

	/**
	 * 实例化对象
	 */
	static function init() {
		return new Money();
	}

	/**
	 * 获取支付方式配置信息
	 */
	static function get_payment() {
		return array(
			'payment_id' => 0,
			'payment_name' => '钱包支付',
			'payment_code' => 'wallet',
		);
	}

	/**
	 * 钱包状态选项
	 * @return string[]
	 */
	static function get_money_status_options() {
		return array(
			MONEY_S_OPEN => '启用',
			MONEY_S_LOCK => '锁定',
		);
	}

	/**
	 * 资金流向选项
	 * @return string[]
	 */
	static function get_flow_options() {
		return array(
			FLOW_IN => '收入',
			FLOW_OUT => '支出',
		);
	}

	/**
	 * 钱包记录状态选项
	 * @return string[]
	 */
	static function get_money_log_status_options() {
		return array(
			MONEY_L_S_NO => '无效',
			MONEY_L_S_AUDIT => '待审核',
			MONEY_L_S_GO => '进行中',
			MONEY_L_S_OK => '完成',
		);
	}

	/**
	 * 钱包记录类型选项
	 * @return string[]
	 */
	static function get_money_log_type_options() {
		return array(
			MONEY_L_T_CZ => '充值',
			MONEY_L_T_ZZ => '转账',
			MONEY_L_T_TX => '提现',
			MONEY_L_T_TK => '退款',
			MONEY_L_T_BUYER => '购买商品',
			MONEY_L_T_SELLER => '卖出商品',
			MONEY_L_T_SYSTEM => '系统操作',
			MONEY_L_T_BUY_TC => '推荐会员购物提成',
			MONEY_L_T_SELL_TC => '推荐商家销售提成',
		);
	}

	/**
	 * 获取支付方式列表
	 */
	static function get_pay_list($id = 0) {
		$pay_model = &m('payment');
		$pay_list = $pay_model->find(array(
			'conditions' => "store_id = 0 AND is_online = 1 AND enabled = 1",
			'order' => 'sort_order',
		));

		if ($id) {
			return $pay_list[$id];
		}

		return $pay_list;
	}

	/**
	 * 钱包是否开通,未开通则自动创建默认账户
	 */
	function isset_account($user_id) {
		$temp = $this->money_model->get("user_id = {$user_id}");
		if (!$temp) {
			//未开通则自动创建默认账户
			$this->init_account($user_id);
			return 0;
		}
		return 1;
	}

	/**
	 * 初始化一个钱包账户
	 */
	protected function init_account($user_id) {
		$temp = array(
			'user_id' => $user_id,
			'password' => '',
			'money' => 0,
			'money_dj' => 0,
			'status' => MONEY_S_OPEN,
			'add_time' => gmtime(),
		);
		$this->money_model->add($temp);

		return 1;
	}

	/**
	 * 获取钱包信息
	 */
	function get_account($user_id) {
		return $this->money_model->get("user_id = {$user_id}");
	}

	/**
	 * 是否已设置支付密码
	 */
	function isset_password($user_id) {
		$temp = $this->money_model->get("user_id = {$user_id}");
		if (!$temp['password']) {
			return 0;
		}
		return 1;
	}

	/**
	 * 检查钱包支付密码是否正确
	 */
	function check_password($user_id, $password) {
		$temp = $this->money_model->get("user_id = {$user_id} AND password = '" . md5($password) . "'");
		if (!$temp) {
			return 0;
		}
		return 1;
	}

	/**
	 * 是否设置银行卡
	 * @param int $user_id
	 */
	function isset_bank($user_id = 0) {
		$temp = $this->bank_model->get("user_id = $user_id");
		if (!$temp) {
			return 0;
		}

		return 1;
	}

	/**
	 * 添加充值记录
	 */
	function recharge_add($user_id, $money, $pay_id) {
		$temp = $this->money_log_model->add(array(
			'user_id' => $user_id,
			'party_id' => 0,
			'money' => $money,
			'flow' => FLOW_IN,
			'status' => MONEY_L_S_GO,
			'type' => MONEY_L_T_CZ,
			'remark' => '',
			'pay_id' => $pay_id,
			'add_time' => gmtime(),
		));

		return $temp;
	}

	/**
	 * 更新充值状态
	 */
	function recharge_update_status($money_log_id, $status) {

		switch ($status) {
		case MONEY_L_S_OK:{
				//变更账户资金
				$money_log_info = $this->money_log_model->get("id = '{$money_log_id}'");
				$temp = $this->money_model->edit("user_id = {$money_log_info['user_id']}", "money = money + {$money_log_info['money']}");
				if (!$temp) {
					return 0;
				}

				//变更记录状态
				$this->money_log_model->edit($money_log_id, "status = $status");
				break;
			}
		default:
			return 0;
		}

		return 1;
	}

	/**
	 * 管理员充值
	 */
	function recharge_admin($user_id, $party_id, $money) {
		$temp = $this->money_model->edit("user_id = {$user_id}", "money = money + $money");
		if (!$temp) {
			return 0;
		}

		$temp = $this->money_log_model->add(array(
			'user_id' => $user_id,
			'party_id' => $party_id,
			'money' => $money,
			'flow' => FLOW_IN,
			'status' => MONEY_L_S_OK,
			'type' => MONEY_L_T_CZ,
			'remark' => '',
			'pay_id' => 0,
			'add_time' => gmtime(),
		));

		return $temp;
	}

	/**
	 * 转账资金改变
	 */
	function transfer_money_change($user_id, $party_id, $money) {

		//支出方
		$temp = $this->money_model->edit("user_id = {$party_id}", "money = money - $money");
		if (!$temp) {
			return 0;
		}
		$this->money_log_model->add(array(
			'user_id' => $party_id,
			'party_id' => $user_id,
			'money' => $money,
			'flow' => FLOW_OUT,
			'status' => MONEY_L_S_OK,
			'type' => MONEY_L_T_ZZ,
			'remark' => '',
			'add_time' => gmtime(),
		));

		//收入方
		$temp = $this->money_model->edit("user_id = {$user_id}", "money = money + $money");
		if (!$temp) {
			return 0;
		}
		$this->money_log_model->add(array(
			'user_id' => $user_id,
			'party_id' => $party_id,
			'money' => $money,
			'flow' => FLOW_IN,
			'status' => MONEY_L_S_OK,
			'type' => MONEY_L_T_ZZ,
			'remark' => '',
			'add_time' => gmtime(),
		));

		return 1;
	}

	/**
	 * 添加提现记录
	 */
	function withdrawal_add($user_id, $money, $bank_id) {

		//冻结提现资金
		$temp = $this->money_model->edit("user_id = {$user_id}", "money = money - $money, money_dj = money_dj + $money");
		if (!$temp) {
			return 0;
		}
		$temp = $this->money_log_model->add(array(
			'user_id' => $user_id,
			'party_id' => 0,
			'money' => $money,
			'flow' => FLOW_OUT,
			'status' => MONEY_L_S_AUDIT,
			'type' => MONEY_L_T_TX,
			'remark' => '',
			'bank_id' => $bank_id,
			'add_time' => gmtime(),
		));

		return $temp;
	}

	/**
	 * 更新提现状态
	 */
	function withdrawal_update_status($money_log_id, $status) {

		switch ($status) {
		case MONEY_L_S_OK:{
				//扣除相应冻结资金
				$money_log_info = $this->money_log_model->get("id = '{$money_log_id}'");
				$temp = $this->money_model->edit("user_id = {$money_log_info['user_id']}", "money_dj = money_dj - {$money_log_info['money']}");
				if (!$temp) {
					return 0;
				}

				//变更记录
				$this->money_log_model->edit($money_log_id, "status = $status");
				break;
			}
		case MONEY_L_S_NO:{
				//解除相应冻结资金
				$money_log_info = $this->money_log_model->get("id = '{$money_log_id}'");
				$temp = $this->money_model->edit("user_id = {$money_log_info['user_id']}", "money = money + {$money_log_info['money']}, money_dj = money_dj - {$money_log_info['money']}");
				if (!$temp) {
					return 0;
				}

				//变更记录
				$this->money_log_model->edit($money_log_id, "status = $status");
				break;
			}
		default:
			return 0;
		}

		return 1;
	}

	/**
	 * 添加买家支付记录
	 */
	function pay_buyer_add($user_id, $party_id, $money, $order_id, $payment_code = 'wallet') {
		//冻结资金
		$temp = $this->money_model->edit("user_id = {$party_id}", "money_dj = money_dj + $money");
		if (!$temp) {
			return 0;
		}

		if ($payment_code == 'wallet') {
			$temp = $this->money_model->edit("user_id = {$user_id}", "money = money - $money");
			if (!$temp) {
				return 0;
			}
		}

		$temp = $this->money_log_model->add(array(
			'user_id' => $user_id,
			'party_id' => $party_id,
			'money' => $money,
			'flow' => FLOW_OUT,
			'status' => MONEY_L_S_GO,
			'type' => MONEY_L_T_BUYER,
			'remark' => $payment_code,
			'order_id' => $order_id,
			'add_time' => gmtime(),
		));

		return $temp;
	}

	/**
	 * 添加卖家收入记录
	 */
	function pay_seller_add($user_id, $party_id, $money, $order_id) {

		if ($money <= 0) {
			return 0;
		}

		//更新卖家资金
		$temp = $this->money_model->edit("user_id = {$user_id}", "money = money + {$money}");
		if (!$temp) {
			return 0;
		}
		$temp = $this->money_log_model->add(array(
			'user_id' => $user_id,
			'party_id' => $party_id,
			'money' => $money,
			'flow' => FLOW_IN,
			'status' => MONEY_L_S_OK,
			'type' => MONEY_L_T_SELLER,
			'remark' => '',
			'order_id' => $order_id,
			'add_time' => gmtime(),
		));

		return $temp;
	}

	/**
	 * 更新买家钱包支付状态
	 */
	function pay_buyer_update_status($money_log_id, $status) {

		switch ($status) {
		case MONEY_L_S_OK:{
				//扣除相应冻结资金
				$money_log_info = $this->money_log_model->get($money_log_id);
				if (!money_log_info) {
					return 0;
				}
				$temp = $this->money_model->edit("user_id = {$money_log_info['party_id']}", "money_dj = money_dj - {$money_log_info['money']}");
				if (!$temp) {
					return 0;
				}

				//变更记录
				$this->money_log_model->edit($money_log_id, "status = $status");
				break;
			}
		case MONEY_L_S_NO:{
				//解除相应冻结资金
				$money_log_info = $this->money_log_model->get($money_log_id);
				if (!money_log_info) {
					return 0;
				}
				$temp = $this->money_model->edit("user_id = {$money_log_info['user_id']}", "money = money + {$money_log_info['money']}");
				if (!$temp) {
					return 0;
				}
				$temp = $this->money_model->edit("user_id = {$money_log_info['party_id']}", "money_dj = money_dj - {$money_log_info['money']}");
				if (!$temp) {
					return 0;
				}

				//变更记录
				$this->money_log_model->edit($money_log_id, "status = $status");
				break;
			}
		default:
			return 0;
		}

		return 1;
	}

	/**
	 * 管理员确认退款后相关资金
	 */
	function refund_money_chang($buyer_id, $seller_id, $refund_money, $order_amount, $order_id) {
		//买家
		if ($refund_money > 0) {
			if ($this->money_log_model->get("user_id = {$buyer_id} AND party_id = {$seller_id} AND order_id = {$order_id}")) {
				$temp = $this->money_model->edit("user_id = {$buyer_id}", "money = money + {$refund_money}");
				$temp = $this->money_model->edit("user_id = {$seller_id}", "money_dj = money_dj - {$order_amount}");
			} else {
				$temp = $this->money_model->edit("user_id = {$buyer_id}", "money = money + {$refund_money}");
			}
			if (!$temp) {
				return 0;
			}

			$this->money_log_model->add(array(
				'user_id' => $buyer_id,
				'party_id' => $seller_id,
				'money' => $refund_money,
				'flow' => FLOW_IN,
				'status' => MONEY_L_S_OK,
				'type' => MONEY_L_T_TK,
				'remark' => '',
				'add_time' => gmtime(),
			));
		}

		//卖家
		$dif_money = $order_amount - $refund_money;
		if ($dif_money > 0) {
			$temp = $this->money_model->edit("user_id = {$seller_id}", "money = money + {$dif_money}");
			if (!$temp) {
				return 0;
			}
			$this->money_log_model->add(array(
				'user_id' => $seller_id,
				'party_id' => $buyer_id,
				'money' => $dif_money,
				'flow' => FLOW_IN,
				'status' => MONEY_L_S_OK,
				'type' => MONEY_L_T_TK,
				'remark' => '',
				'add_time' => gmtime(),
			));
		}

		return 1;
	}

	/**
	 * 订单完成后相关操作
	 * @param unknown $order_info
	 */
	function order($order_info) {

		$filename = ROOT_PATH . '/data/member_level.inc.php';
		$config = Methods::load_config($filename);

		//获取相关信息
		$buyer_id = $order_info['buyer_id'];
		$seller_id = $order_info['seller_id'];

		//商家店铺等级
		$store_model = &m('store');
		$store_info = $store_model->get("store_id = {$seller_id}");

		//订单商品
		$order_goods_model = &m('ordergoods');
		$joinstr = $order_goods_model->parseJoin('goods_id', 'goods_id', 'goods');
		$goods_list = $order_goods_model->find(array(
			'joinstr' => $joinstr,
			'fields' => 'order_goods.price, order_goods.quantity, goods.cate_id',
			'conditions' => "order_id = {$order_info['order_id']}",
			'index_key' => 'goods_id',
		));

		//计算提成
		$money = 0;
		$gcategory_model = &bm('gcategory');
		foreach ($goods_list as $k => $v) {
			$cate_id_arr = $gcategory_model->get_ancestor($v['cate_id']);
			$cate_temp = array_shift($cate_id_arr);
			$cate_id = $cate_temp['cate_id'];

			$money += $v['quantity'] * $v['price'] * ($config['gcate'][$cate_id] ? $config['gcate'][$cate_id] : $config['order_rate']);
		}
		if ($config['sgrade'][$store_info['sgrade']]) {
			$money = $money * (1 - $config['sgrade'][$store_info['sgrade']]);
		}

		//钱包变更
		if ($order_info['payment_code'] != 'cod') {
			$money_log_info = $this->money_log_model->get("order_id = {$order_info['order_id']} AND user_id = {$order_info['buyer_id']} AND party_id = {$order_info['seller_id']}");
			$this->pay_buyer_update_status($money_log_info['id'], MONEY_L_S_OK);

			//卖家
			$this->pay_seller_add($order_info['seller_id'], $order_info['buyer_id'], $order_info['order_amount'] - $money, $order_info['order_id']);
		}

		//更新用户购物总额
		$member_ext_model = &m('member_ext');
		$member_ext_model->edit($buyer_id, "total_buy = total_buy + {$order_info['order_amount']}");

		//更新相关用户的等级
		$buyer_info = $this->member_model->get($buyer_id);
		$member_grade_model = &m('member_grade');
		$member_grade_model->updateGrade($buyer_id);

		//更新提成相关
		$this->buy_tc($buyer_id, $money * $config['tc']['buy_ratio'], $config['tc_layer']);
		$this->sell_tc($seller_id, $money * $config['tc']['sell_ratio'], $config['tc_layer']);
		$this->tc_add(ADMIN_ID, $seller_id, $money * $config['mall_ratio'], MONEY_L_T_SYSTEM);
	}

	/**
	 * 推荐会员购物提成
	 */
	function buy_tc($buyer_id, $money, $layer) {

		//得到买家信息
		$buyer_info = $this->member_model->get($buyer_id);
		if (!$buyer_info) {
			return 0;
		}

		$member_grade_model = &m('member_grade');

		//得到相应层级类的上级用户信息
		$parent_id_arr = explode(',', $buyer_info['parent_path']);
		while ($layer > 0) {
			$layer--;

			$parent_id = intval(array_pop($parent_id_arr));
			if (!$parent_id) {
				continue;
			}
			if (!$parent_id_arr) {
				break;
			}

			$parent_grade_info = $member_grade_model->getGradeinfo($parent_id);
			if (!$parent_grade_info) {
				continue;
			}

			//计算money
			$income_money = $money * $parent_grade_info['buy_tc'];

			//加入记录，变更余额
			$this->tc_add($parent_id, $buyer_info['user_id'], $income_money, MONEY_L_T_BUY_TC);

			$money = $money - $income_money;
		}

		if ($money > 0) {
			$this->tc_add(ADMIN_ID, $buyer_info['user_id'], $money, MONEY_L_T_BUY_TC);
		}

		return 1;
	}

	/**
	 * 推荐商家销售提成
	 */
	function sell_tc($seller_id, $money, $layer) {

		//得到卖家信息
		$seller_info = $this->member_model->get($seller_id);
		if (!$seller_info) {
			return 0;
		}

		$member_grade_model = &m('member_grade');

		//得到相应层级类的上级用户信息
		$parent_id_arr = explode(',', $seller_info['parent_path']);
		while ($layer > 0) {
			$layer--;

			$parent_id = intval(array_pop($parent_id_arr));
			if (!$parent_id) {
				continue;
			}
			if (!$parent_id_arr) {
				break;
			}

			$parent_grade_info = $member_grade_model->getGradeinfo($parent_id);
			if (!$parent_grade_info) {
				continue;
			}

			//计算money
			$income_money = $money * $parent_grade_info['sell_tc'];

			//加入记录，变更余额
			$this->tc_add($parent_id, $seller_info['user_id'], $income_money, MONEY_L_T_SELL_TC);

			$money = $money - $income_money;
		}

		if ($money > 0) {
			$this->tc_add(ADMIN_ID, $seller_info['user_id'], $money, MONEY_L_T_SELL_TC);
		}

		return 1;
	}

	/**
	 * 添加提成记录与变更余额
	 */
	function tc_add($user_id, $party_id, $money, $type) {

		//更新资金
		$temp = $this->money_model->edit("user_id = {$user_id}", "money = money + {$money}");
		if (!$temp) {
			return 0;
		}
		$temp = $this->money_log_model->add(array(
			'user_id' => $user_id,
			'party_id' => $party_id,
			'money' => $money,
			'flow' => FLOW_IN,
			'status' => MONEY_L_S_OK,
			'type' => $type,
			'remark' => '',
			'add_time' => gmtime(),
		));

		return $temp;
	}
}
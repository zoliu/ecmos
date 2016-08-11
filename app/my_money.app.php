<?php

/**
 * 
 * 用户钱包中心
 * 
 * @author Mosquito
 * @link www.360cd.cn
 */
class My_moneyApp extends MemberbaseApp {
	
	protected $money_model;
	protected $money_log_model;
	protected $user_id;
	
	function __construct() {
		parent::__construct();
		$this->My_moneyApp();
	}
	
	function My_moneyApp() {
		$this->money_model = &m('money');
		$this->money_log_model = &m('money_log');
		$this->user_id = $this->visitor->get('user_id');
		
		if (!Money::init()->isset_account($this->user_id)) {
			show_warning('钱包未设置', '前往设置', url('app=my_money&act=setting'));
			exit();
		}
	}
	
	/**
	 * 总览
	 * {@inheritDoc}
	 * @see BaseApp::index()
	 */
	function index() {
	    
		$this->assign('money_info', Money::init()->get_account($this->user_id));
		
		$this->_curlocal(
				LANG::get('member_center'), url('app=member'),
				LANG::get('my_money'), url('app=my_money'),
				LANG::get('money_index')
				);
		$this->_curitem('my_money');
		$this->_curmenu('money_index');
		
		$this->display('my_money.index.html');
	}
	
	/**
	 * 设置
	 */
	function setting() {
		
		$isset_password = true;
		if (!Money::init()->isset_password($this->user_id)) {
			$isset_password = false;
		}
		$this->assign('isset_password', $isset_password);
		
		if (IS_POST) {
			
			if ($isset_password) {
				$old_password = str_replace(' ', '', $_POST['old_password']);
				if (!Money::init()->check_password($this->user_id, $old_password)) {
					show_warning('旧支付密码错误，操作失败');
					exit();
				}
			}
			
			$password = str_replace(' ', '', $_POST['password']);
			$ok_password = str_replace(' ', '', $_POST['ok_password']);
			if ($password !== $ok_password) {
				show_warning('两次输入的密码不一致，操作失败');
				exit();
			}
			
			$password = md5($password);
			$this->money_model->edit("user_id = '{$this->user_id}'", "password = '{$password}'");
			show_message('操作成功', '查看钱包', url('app=my_money'));
		}
		else {
			
			$this->_curlocal(
					LANG::get('member_center'), url('app=member'),
					LANG::get('my_money'), url('app=my_money'),
					LANG::get('setting')
					);
			$this->_curitem('my_money');
			$this->_curmenu('setting');
			
			$this->display('my_money.setting.html');
		}
	}
	
	function zfreset_email() {
		echo json_encode(0);
	}
	
	/**
	 * 记录
	 */
	function log() {
	    
	    $query = array();
	    $conditions = '1 = 1';
	    if (trim($_GET['start_time']) != '' ) {
	        $query['start_time'] = trim($_GET['start_time']);
	        $conditions .= " AND money_log.add_time >= " . gmstr2time($query['start_time']);
	    }
	    if (trim($_GET['end_time']) != '' ) {
	        $query['end_time'] = trim($_GET['end_time']);
	        $conditions .= " AND money_log.add_time < " . gmstr2time($query['end_time']);
	    }
	    if (trim($_GET['type']) != '' ) {
	        $query['type'] = intval($_GET['type']);
	        $conditions .= " AND money_log.type = {$query['type']}";
	    }
	    
	    if ($query) {
	        $this->assign('filter', true);
	    }
	    $this->assign('query', $query);
	    
	    $conditions .= " AND money_log.user_id = '{$this->user_id}'";
	    
	    $page = $this->_get_page();
	    $joinstr = $this->money_log_model->parseJoin('user_id', 'user_id', 'member', 'money_log', 'left', 'user_m');
	    $joinstr .= $this->money_log_model->parseJoin('party_id', 'user_id', 'member', 'money_log', 'left', 'party_m');
	    $money_log_list = $this->money_log_model->find(array(
	        'joinstr' => $joinstr,
	        'fields' => 'this.*, user_m.user_name AS user_name, party_m.user_name AS party_name',
	        'conditions' => $conditions,
	        'order' => 'add_time DESC',
	        'limit' => $page['limit'],
	        'count' => true,
	    ));
	    $this->assign('money_log_list', $money_log_list);
	    
	    $page['item_count'] = $this->money_log_model->getCount();
	    $this->_format_page($page);
	    $this->assign('page_info', $page);
	    
	    //
	    $this->assign('money_log_type_options', Money::get_money_log_type_options());
	    $this->assign('flow_options', Money::get_flow_options());
	    $this->assign('money_log_status_options', Money::get_money_log_status_options());
	    
        //
        $this->import_resource(array(
            'script' => array(
                array(
                    'path' => 'jquery.ui/jquery.ui.js','attr' => '' 
                ),array(
                    'path' => 'jquery.ui/i18n/' . i18n_code() . '.js','attr' => '' 
                ) 
            ),'style' => 'jquery.ui/themes/ui-lightness/jquery.ui.css' 
        ));
	    
		$this->_curlocal(
				LANG::get('member_center'), url('app=member'),
				LANG::get('my_money'), url('app=my_money'),
				LANG::get('log')
				);
		$this->_curitem('my_money');
		$this->_curmenu('log');
		
		$this->display('my_money.log.html');
	}
	
	/**
	 * 充值
	 */
	function recharge() {
	    
	    if (IS_POST) {
	        
	        $pay_id = intval($_POST['pay_id']);
	        $money = floatval($_POST['money']);
	        
	        //验证信息
	        if ($pay_id <= 0) {
	            show_warning('支付方式错误');
	            exit();
	        }
	        if ($money <= 0) {
	            show_warning('充值金额错误');
	            exit();
	        }
	        
	        //
	        $id = Money::init()->recharge_add($this->user_id, $money, $pay_id);
	        
	        //
	        $payment_model = &m('payment');
	        $pay_info = $payment_model->get("payment_id = $pay_id");
	        
	        $order_info = array(
	            'order_id' => $id,
                'order_amount' => $money,
	            'order_sn' => gmtime(),
	            'out_trade_sn' => gmtime(),
	        );
	        
	        $payment = $this->_get_payment($pay_info['payment_code'], $pay_info);
	        $payment->set_notify_app('moneynotify');
	        $payment_form = $payment->get_payform($order_info);
	        
	        $this->assign('payform', $payment_form);
	        header('Content-Type:text/html;charset='.CHARSET);
	        $this->display('my_money.recharge.html');
	    }
	    else {
	        
	        $id = intval($_GET['id']);
	        
	        if ($id) {
	            $money_log_info = $this->money_log_model->get("id = $id");
	            
	            //
	            $payment_model = &m('payment');
	            $pay_info = $payment_model->get("payment_id = {$money_log_info['pay_id']}");
	             
	            $order_info = array(
	                'order_id' => $money_log_info['id'],
	                'order_amount' => $money_log_info['money'],
	                'order_sn' => gmtime(),
	                'out_trade_sn' => gmtime(),
	            );
	             
	            $payment = $this->_get_payment($pay_info['payment_code'], $pay_info);
	            $payment->set_notify_app('moneynotify');
	            $payment_form = $payment->get_payform($order_info);
	            
	            $this->assign('payform', $payment_form);
	            header('Content-Type:text/html;charset='.CHARSET);
	        }
	        else {
	            //充值方式
	            $this->assign('pay_list', Money::get_pay_list());
	             
	            $this->_curlocal(
	                    LANG::get('member_center'), url('app=member'),
	                    LANG::get('my_money'), url('app=my_money'),
	                    LANG::get('recharge')
	                    );
	            $this->_curitem('my_money');
	            $this->_curmenu('recharge');
	        }
	         
	        $this->display('my_money.recharge.html');
	    }
	}

	/**
	 * 转账
	 */
	function transfer() {
	    
	    $money_info = Money::init()->get_account($this->user_id);
	    
	    if (IS_POST) {
	        
	        $user_name = trim($_POST['user_name']);
	        $money = floatval($_POST['money']);
	        $password = str_replace(' ', '', $_POST['password']);
	        
	        //验证信息
	        $member_model = &m('member');
	        $member_info = $member_model->get("user_name = '{$user_name}'");
	        if (!$member_info) {
	            show_warning('目标用户不存在');
	            exit();
	        }
	        Money::init()->isset_account($member_info['user_id']);
	        
	        if ($money <= 0 || $money > $money_info['money']) {
	            show_warning('转出金额错误');
	            exit();
	        }
	        
	        if (!Money::init()->check_password($this->user_id, $password)) {
	            show_warning('支付密码错误');
	            exit();
	        }
	        
	        if ($member_info['user_id'] == $this->user_id) {
	            show_warning('不能给自己转账');
	            exit();
	        }
	        
	        //
	        $tmep = Money::init()->transfer_money_change($member_info['user_id'], $this->user_id, $money);
	        $tmep ? show_message('操作成功') : show_warning('操作失败');
	    }
	    else {
	        $this->assign('money_info', $money_info);
	        
	        $this->_curlocal(
	                LANG::get('member_center'), url('app=member'),
	                LANG::get('my_money'), url('app=my_money'),
	                LANG::get('transfer')
	                );
	        $this->_curitem('my_money');
	        $this->_curmenu('transfer');
	        
	        $this->display('my_money.transfer.html');
	    }
	}
	
	/**
	 * 提现
	 */
	function withdrawal() {
	    
	    if (!Money::init()->isset_bank($this->user_id)) {
	        show_warning('未设置银行卡', '前往设置' , '/?app=my_money&act=bank');
	        exit();
	    }
	    
	    $money_info = Money::init()->get_account($this->user_id);
	    
	    if (IS_POST) {
	        $bank_id = intval($_POST['bank_id']);
	        $money = floatval($_POST['money']);
	        $password = str_replace(' ', '', $_POST['password']);
	         
	        //验证信息
	        if ($bank_id <= 0) {
	            show_warning('银行卡错误');
	            exit();
	        }
	        if ($money <= 0 || $money > $money_info['money']) {
	            show_warning('提现金额错误');
	            exit();
	        }
	        if (!Money::init()->check_password($this->user_id, $password)) {
	            show_warning('支付密码错误');
	            exit();
	        }
	        
	        //
	        $tmep = Money::init()->withdrawal_add($this->user_id, $money, $bank_id);
	        $tmep ? show_message('操作成功') : show_warning('操作失败');
	    }
	    else {
	        $this->assign('money_info', $money_info);
	        
	        //
	        $bank_model = &m('bank');
	        $bank_list = $bank_model->find(array(
	            'conditions' => "user_id = {$this->user_id}",
	        ));
	        
	        $bank_options = array();
	        foreach ($bank_list as $k => $v) {
	            $bank_options[$v['id']] = $v['bank_name'] . ' : ' . $v['cardholder'] . ' ' . $v['card_number'];
	        }
	        $this->assign('bank_options', $bank_options);
	        
	        $this->_curlocal(
	                LANG::get('member_center'), url('app=member'),
	                LANG::get('my_money'), url('app=my_money'),
	                LANG::get('withdrawal')
	                );
	        $this->_curitem('my_money');
	        $this->_curmenu('withdrawal');
	        
	        $this->display('my_money.withdrawal.html');
	    }
	}
	
	/**
	 * 银行卡
	 */
	function bank() {
	    
        $bank_model = &m('bank');
        $bank_list = $bank_model->find(array(
            'conditions' => "user_id = {$this->user_id}",
        ));
        $this->assign('bank_list', $bank_list);
        
         
        $this->_curlocal(
                LANG::get('member_center'), url('app=member'),
                LANG::get('my_money'), url('app=my_money'),
                LANG::get('bank')
                );
        $this->_curitem('my_money');
        $this->_curmenu('bank');
         
        $this->display('my_money.bank.html');
	}
	
	function bank_add() {
	    
	    $bank_name_list = include 'data/bank.inc.php';
	    
	    if (IS_POST) {
	        $data = array();
	        $data['user_id'] = $this->user_id;
	        $data['card_number'] = trim($_POST['card_number']);
	        $data['cardholder'] = trim($_POST['cardholder']);
	        $data['bank_name'] = $bank_name_list[$_POST['bank_name']];
	        $data['bank_address'] = trim($_POST['bank_address']);
	        $data['add_time'] = gmtime();
	        
	        if (!$data['card_number'] || !$data['cardholder']) {
	            show_warning('操作失败');
	            return;
	        }
	        
	        $bank_model = &m('bank');
	        $bank_model->add($data);
	        
	        show_message('操作成功', '返回列表', 'index.php?app=my_money&act=bank');
	    }
	    else {
	        $this->assign('bank_name_list', $bank_name_list);
	        
	        $this->display('my_money.bank_add.html');
	    }
	}
	
	function bank_drop() {
	    $id_arr = trim($_GET['id']) ? explode(',', trim($_GET['id'])) : '';
	    if (!$id_arr) {
	        show_warning('操作失败');
	        exit();
	    }
	    
	    $bank_model = &m('bank');
	    $bank_model->drop(db_create_in($id_arr, 'id'));
	    show_message('操作成功');
	}
	
	/**
	 * 支付订单
	 */
	function pay_order() {
	    $order_id = intval($_GET['order_id']);
	    if (!$order_id) {
	        show_warning('操作非法');
	        exit();
	    }
	    
	    //钱包信息
	    $money_info = Money::init()->get_account($this->user_id);
	    
	    //订单信息
	    $order_model = &m('order');
	    $order_info = $order_model->get_order_info($order_id, $this->user_id);
	    if (!$order_info) {
	        show_warning('订单不存在');
	        exit();
	    }
	    
	    if (IS_POST) {
	        $password = str_replace(' ', '', $_POST['password']);
	        
	        if ($money_info['money'] < $order_info['order_amount']) {
	            show_warning('余额不足支付当前订单');
	            exit();
	        }
	        if (!Money::init()->check_password($this->user_id, $password)) {
	            show_warning('支付密码错误');
	            exit();
	        }
	        
	        $payment = Money::get_payment();
	        
	        //变更订单状态
	        if ($order_info['order_merge']) {
	        	$order_sn_arr = unserialize($order_info['order_sns']);
	        	
	        	foreach ($order_sn_arr as $k => $v) {
	        		$order_model->edit($k, array(
	        			'payment_id' => $payment['payment_id'],
	        			'payment_name' => $payment['payment_name'],
	        			'payment_code' => $payment['payment_code'],
	        			'pay_time' => gmtime(),
	        			'out_trade_sn' => $v,
	        			'status' => ORDER_ACCEPTED,
	        		));
	        		
	        		$order_temp = $order_model->get($k);
	        		
	        		//添加钱包记录
	        		Money::init()->pay_buyer_add($order_temp['buyer_id'], $order_temp['seller_id'], $order_temp['order_amount'], $k);
	        	}
	        }
	        else {
	        	$order_model->edit($order_id, array(
	        		'payment_id' => $payment['payment_id'],
	        		'payment_name' => $payment['payment_name'],
	        		'payment_code' => $payment['payment_code'],
	        		'pay_time' => gmtime(),
	        		'out_trade_sn' => $order_info['order_sn'],
	        		'status' => ORDER_ACCEPTED,
	        	));
	        	
	        	//添加钱包记录
	        	Money::init()->pay_buyer_add($order_info['buyer_id'], $order_info['seller_id'], $order_info['order_amount'], $order_id);
	        }
	        
	        show_message('支付成功', '订单中心' , url('app=buyer_order'));
	        exit();
	    }
	    else {
	        $this->assign('money_info', $money_info);
	        $this->assign('order_info', $order_info);
	        
	        $this->display('my_money.pay_order.html');
	    }
	}
	
	function _get_member_submenu() {
		$menus = array(
			array(
				'name' => 'money_index',
				'url' => '?app=my_money',
			),
			array(
				'name' => 'setting',
				'url' => '?app=my_money&act=setting',
			),
			array(
				'name' => 'log',
				'url' => '?app=my_money&act=log',
			),
		    array(
		        'name' => 'bank',
		        'url' => '?app=my_money&act=bank',
		    ),
		);
		
		if (ACT == 'recharge') {
		    $menus[] = array(
		        'name' => 'recharge',
		        'url' => '',
		    );
		}
		if (ACT == 'transfer') {
		    $menus[] = array(
		        'name' => 'transfer',
		        'url' => '',
		    );
		}
		if (ACT == 'withdrawal') {
		    $menus[] = array(
		        'name' => 'withdrawal',
		        'url' => '',
		    );
		}
		
		return $menus;
	}
}
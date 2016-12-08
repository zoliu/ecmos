<?php

//include_once ROOT_PATH . '/includes/models/money.model.php';

/**
 *    买家的订单管理控制器
 *
 *    @author    Garbin
 *    @usage    none
 */
class Buyer_orderApp extends MemberbaseApp {
	function index() {
		/* 获取订单列表 */
		$this->_get_orders();

		/* 当前位置 */
		$this->_curlocal(LANG::get('member_center'), 'index.php?app=member',
			LANG::get('my_order'), 'index.php?app=buyer_order',
			LANG::get('order_list'));

		/* 当前用户中心菜单 */
		$this->_curitem('my_order');
		$this->_curmenu('order_list');
		$this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('my_order'));
		$this->import_resource(array(
			'script' => array(
				array(
					'path' => 'dialog/dialog.js',
					'attr' => 'id="dialog_js"',
				),
				array(
					'path' => 'jquery.ui/jquery.ui.js',
					'attr' => '',
				),
				array(
					'path' => 'jquery.ui/i18n/' . i18n_code() . '.js',
					'attr' => '',
				),
				array(
					'path' => 'jquery.plugins/jquery.validate.js',
					'attr' => '',
				),
			),
			'style' => 'jquery.ui/themes/ui-lightness/jquery.ui.css',
		));

		/* 显示订单列表 */
		$this->display('buyer_order.index.html');
	}
	/**
	 *    查看订单详情
	 *
	 *    @author    Garbin
	 *    @return    void
	 */
	function view() {
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		$model_order = &m('order');
		//$order_info  = $model_order->get("order_id={$order_id} AND buyer_id=" . $this->visitor->get('user_id'));
		$order_info = $model_order->get(array(
			'fields' => "*, order.add_time as order_add_time",
			'conditions' => "order_id={$order_id} AND buyer_id=" . $this->visitor->get('user_id'),
			'join' => 'belongs_to_store',
		));
		if (!$order_info) {
			$this->show_warning('no_such_order');

			return;
		}

		/* 团购信息 */
		if ($order_info['extension'] == 'groupbuy') {
			$groupbuy_mod = &m('groupbuy');
			$group = $groupbuy_mod->get(array(
				'join' => 'be_join',
				'conditions' => 'order_id=' . $order_id,
				'fields' => 'gb.group_id',
			));
			$this->assign('group_id', $group['group_id']);
		}

		/* 当前位置 */
		$this->_curlocal(LANG::get('member_center'), 'index.php?app=member',
			LANG::get('my_order'), 'index.php?app=buyer_order',
			LANG::get('view_order'));

		/* 当前用户中心菜单 */
		$this->_curitem('my_order');

		$this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('order_detail'));

		/* 调用相应的订单类型，获取整个订单详情数据 */
		$order_type = &ot($order_info['extension']);
		$order_detail = $order_type->get_order_detail($order_id, $order_info);
		foreach ($order_detail['data']['goods_list'] as $key => $goods) {
			empty($goods['goods_image']) && $order_detail['data']['goods_list'][$key]['goods_image'] = Conf::get('default_goods_image');
		}

		$this->assign('order', $order_info);
		$this->assign($order_detail['data']);
		//360cd.cn

		$shipping_ext_model = &m('shipping_ext');
		$shipping_info = $shipping_ext_model->get_shipping_info($order_info['order_id'], $order_info['invoice_no']);

		$this->assign('options_shipping', $this->_get_shipping());

		$this->assign('shipping_info', $shipping_info);

		//360cd.cn
		$this->display('buyer_order.view.html');
	}
	function _get_shipping() {
		$shipping = &m('shipping_ext');

		return $shipping->get_shipping_list();
	}

	/**
	 *    取消订单
	 *
	 *    @author    Garbin
	 *    @return    void
	 */
	function cancel_order() {
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		if (!$order_id) {
			echo Lang::get('no_such_order');

			return;
		}
		$model_order = &m('order');
		/* 只有未发货的订单可以取消 */
		$order_info = $model_order->get("order_id={$order_id} AND buyer_id=" . $this->visitor->get('user_id') . " AND status " . db_create_in(array(ORDER_PENDING, ORDER_SUBMITTED, ORDER_ACCEPTED)));
		if (empty($order_info)) {
			echo Lang::get('no_such_order');

			return;
		}

		//保存修改前的订单状态 360cd.cn  seema
		$order_info_status = $order_info['status'];
		if (!IS_POST) {
			header('Content-Type:text/html;charset=' . CHARSET);
			$this->assign('order', $order_info);
			$this->display('buyer_order.cancel.html');
		} else {
			$model_order->edit($order_id, array('status' => ORDER_CANCELED));
			if ($model_order->has_error()) {
				$this->pop_warning($model_order->get_error());

				return;
			}

			//360cd.cn born statics
			$statics_model = &m('statics');
			$statics_model->update($order_info['seller_id'], 'cancels', 1);
			//360cd.cn

			/* 加回商品库存 */
			$model_order->change_stock('+', $order_id);
			$cancel_reason = (!empty($_POST['remark'])) ? $_POST['remark'] : $_POST['cancel_reason'];
			/* 记录订单操作日志 */
			$order_log = &m('orderlog');
			$order_log->add(array(
				'order_id' => $order_id,
				'operator' => addslashes($this->visitor->get('user_name')),
				'order_status' => order_status($order_info['status']),
				'changed_status' => order_status(ORDER_CANCELED),
				'remark' => $cancel_reason,
				'log_time' => gmtime(),
			));

			//360cd.cn born statics
			$statics_model = &m('statics');
			$statics_model->update($order_info['seller_id'], 'sales', 1);
			$statics_model->update($order_info['seller_id'], 'moneys', $order_info['order_amount']);
			//360cd.cn

			/* 发送给卖家订单取消通知 */
			$model_member = &m('member');
			$seller_info = $model_member->get($order_info['seller_id']);
			$mail = get_mail('toseller_cancel_order_notify', array('order' => $order_info, 'reason' => $_POST['remark']));
			$this->_mailto($seller_info['email'], addslashes($mail['subject']), addslashes($mail['message']));

			$new_data = array(
				'status' => Lang::get('order_canceled'),
				'actions' => array(), //取消订单后就不能做任何操作了
			);
			//买家已付款 则退款给买家 360cd.cn  seema
			/* if($order_info_status==20){
	                import('zllib/biz.lib');
	                $biz=new bizOrder();
	                $biz->cancel_order($order_id);
*/

			//---www.360cd.cn  Mosquito---
			//买家已付款 则退款给买家
			if ($order_info_status == ORDER_ACCEPTED) {
				$payment = Money::get_payment();
				if ($order_info['payment_id'] == $payment['payment_id']) {
					$money_log_model = &m('money_log');
					$money_log_info = $money_log_model->get("order_id = {$order_info['order_id']} AND user_id = {$order_info['buyer_id']} AND party_id = {$order_info['seller_id']}");

					Money::init()->pay_buyer_update_status($money_log_info['id'], MONEY_L_S_NO);
				}
			}

			$this->pop_warning('ok');
		}

	}

	/**
	 *    确认订单
	 *
	 *    @author    Garbin
	 *    @return    void
	 */
	function confirm_order() {
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		if (!$order_id) {
			echo Lang::get('no_such_order');

			return;
		}
		$model_order = &m('order');
		/* 只有已发货的订单可以确认 */
		$order_info = $model_order->get("order_id={$order_id} AND buyer_id=" . $this->visitor->get('user_id') . " AND status=" . ORDER_SHIPPED);
		if (empty($order_info)) {
			echo Lang::get('no_such_order');

			return;
		}
		if (!IS_POST) {
			header('Content-Type:text/html;charset=' . CHARSET);
			$this->assign('order', $order_info);
			$this->display('buyer_order.confirm.html');
		} else {
			$model_order->edit($order_id, array('status' => ORDER_FINISHED, 'finished_time' => gmtime()));
			if ($model_order->has_error()) {
				$this->pop_warning($model_order->get_error());

				return;
			}

			//360cd.cn
			$point = &m("point_set");
			$point->buyerPoint($this->visitor->get('user_id'), $order_info['order_amount']);
			//360cd.cn

			/* 记录订单操作日志 */
			$order_log = &m('orderlog');
			$order_log->add(array(
				'order_id' => $order_id,
				'operator' => addslashes($this->visitor->get('user_name')),
				'order_status' => order_status($order_info['status']),
				'changed_status' => order_status(ORDER_FINISHED),
				'remark' => Lang::get('buyer_confirm'),
				'log_time' => gmtime(),
			));

			/* 发送给卖家买家确认收货邮件，交易完成 */
			$model_member = &m('member');
			$seller_info = $model_member->get($order_info['seller_id']);
			$mail = get_mail('toseller_finish_notify', array('order' => $order_info));
			$this->_mailto($seller_info['email'], addslashes($mail['subject']), addslashes($mail['message']));

			$new_data = array(
				'status' => Lang::get('order_finished'),
				'actions' => array('evaluate'),
			);

			/* 更新累计销售件数 */
			$model_goodsstatistics = &m('goodsstatistics');
			$model_ordergoods = &m('ordergoods');
			$order_goods = $model_ordergoods->find("order_id={$order_id}");
			foreach ($order_goods as $goods) {
				$model_goodsstatistics->edit($goods['goods_id'], "sales=sales+{$goods['quantity']}");
			}

			/* //把冻结金额转给卖家  360cd.cn
				            //$pop_model=&m('pop');
				            //$pop_model->deduct($order_id);
				            import('zllib/biz.lib');
				            $biz=new bizOrder();
				            $biz->confirm_order($order_id);
			*/

			//---www.360cd.cn  Mosquito---

			//订单完成其他相关信息
			Money::init()->order($order_info);

			$this->pop_warning('ok', '', 'index.php?app=buyer_order&act=evaluate&order_id=' . $order_id);
		}

	}

	/**
	 *    给卖家评价
	 *
	 *    @author    Garbin
	 *    @param    none
	 *    @return    void
	 */
	function evaluate() {
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		if (!$order_id) {
			$this->show_warning('no_such_order');

			return;
		}

		/* 验证订单有效性 */
		$model_order = &m('order');
		$order_info = $model_order->get("order_id={$order_id} AND buyer_id=" . $this->visitor->get('user_id'));
		if (!$order_info) {
			$this->show_warning('no_such_order');

			return;
		}
		if ($order_info['status'] != ORDER_FINISHED) {
			/* 不是已完成的订单，无法评价 */
			$this->show_warning('cant_evaluate');

			return;
		}
		if ($order_info['evaluation_status'] != 0) {
			/* 已评价的订单 */
			$this->show_warning('already_evaluate');

			return;
		}
		$model_ordergoods = &m('ordergoods');

		if (!IS_POST) {
			/* 显示评价表单 */
			/* 获取订单商品 */
			$goods_list = $model_ordergoods->find("order_id={$order_id}");
			foreach ($goods_list as $key => $goods) {
				empty($goods['goods_image']) && $goods_list[$key]['goods_image'] = Conf::get('default_goods_image');
			}
			$this->_curlocal(LANG::get('member_center'), 'index.php?app=member',
				LANG::get('my_order'), 'index.php?app=buyer_order',
				LANG::get('evaluate'));
			$this->assign('goods_list', $goods_list);
			$this->assign('order', $order_info);

			$this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('credit_evaluate'));
			$this->display('buyer_order.evaluate.html');
		} else {
			//360cd.cn born statics
			$statics_model = &m('statics');
			$statics_model->update_order($order_id, 'comments', 1);
			//360cd.cn
			$evaluations = array();
			/* 写入评价 */
			foreach ($_POST['evaluations'] as $rec_id => $evaluation) {
				if ($evaluation['evaluation'] <= 0 || $evaluation['evaluation'] > 3) {
					$this->show_warning('evaluation_error');

					return;
				}
				switch ($evaluation['evaluation']) {
				case 3:
					$credit_value = 1;
					$statics_model->update_order($order_id, 'goodcomments', 1); //360cd.cn
					break;
				case 1:
					$credit_value = -1;
					$statics_model->update_order($order_id, 'badcomments', 1); //360cd.cn
					break;
				default:
					$credit_value = 0;
					$statics_model->update_order($order_id, 'normalcomments', 1); //360cd.cn
					break;
				}
				$evaluations[intval($rec_id)] = array(
					'evaluation' => $evaluation['evaluation'],
					'comment' => $evaluation['comment'],
					'credit_value' => $credit_value,
				);
			}
			$goods_list = $model_ordergoods->find("order_id={$order_id}");
			foreach ($evaluations as $rec_id => $evaluation) {
				$model_ordergoods->edit("rec_id={$rec_id} AND order_id={$order_id}", $evaluation);
				$goods_url = SITE_URL . '/' . url('app=goods&id=' . $goods_list[$rec_id]['goods_id']);
				$goods_name = $goods_list[$rec_id]['goods_name'];
				$this->send_feed('goods_evaluated', array(
					'user_id' => $this->visitor->get('user_id'),
					'user_name' => $this->visitor->get('user_name'),
					'goods_url' => $goods_url,
					'goods_name' => $goods_name,
					'evaluation' => Lang::get('order_eval.' . $evaluation['evaluation']),
					'comment' => $evaluation['comment'],
					'images' => array(
						array(
							'url' => SITE_URL . '/' . $goods_list[$rec_id]['goods_image'],
							'link' => $goods_url,
						),
					),
				));
			}

			/* 更新订单评价状态 */
			$model_order->edit($order_id, array(
				'evaluation_status' => 1,
				'evaluation_time' => gmtime(),
			));

			/* 更新卖家信用度及好评率 */
			$model_store = &m('store');
			$model_store->edit($order_info['seller_id'], array(
				'credit_value' => $model_store->recount_credit_value($order_info['seller_id']),
				'praise_rate' => $model_store->recount_praise_rate($order_info['seller_id']),
			));

			/* 更新商品评价数 */
			$model_goodsstatistics = &m('goodsstatistics');
			$goods_ids = array();
			foreach ($goods_list as $goods) {
				$goods_ids[] = $goods['goods_id'];
			}
			$model_goodsstatistics->edit($goods_ids, 'comments=comments+1');

			$this->show_message('evaluate_successed',
				'back_list', 'index.php?app=buyer_order');
		}
	}

	/**
	 *    获取订单列表
	 *
	 *    @author    Garbin
	 *    @return    void
	 */
	function _get_orders() {
		$page = $this->_get_page(10);
		$model_order = &m('order');
		!$_GET['type'] && $_GET['type'] = 'all_orders';
		$con = array(
			array( //按订单状态搜索
				'field' => 'status',
				'name' => 'type',
				'handler' => 'order_status_translator',
			),
			array( //按店铺名称搜索
				'field' => 'seller_name',
				'equal' => 'LIKE',
			),
			array( //按下单时间搜索,起始时间
				'field' => 'add_time',
				'name' => 'add_time_from',
				'equal' => '>=',
				'handler' => 'gmstr2time',
			),
			array( //按下单时间搜索,结束时间
				'field' => 'add_time',
				'name' => 'add_time_to',
				'equal' => '<=',
				'handler' => 'gmstr2time_end',
			),
			array( //按订单号
				'field' => 'order_sn',
			),
		);
		$conditions = $this->_get_query_conditions($con);
		/* 查找订单 */
		$orders = $model_order->findAll(array(
			'conditions' => "buyer_id=" . $this->visitor->get('user_id') . "{$conditions}",
			'fields' => 'this.*',
			'count' => true,
			'limit' => $page['limit'],
			'order' => 'add_time DESC',
			'include' => array(
				'has_ordergoods', //取出商品
			),
		));
		foreach ($orders as $key1 => $order) {
			$orders[$key1]['goods_quantities'] = count($order['order_goods']);
			foreach ($order['order_goods'] as $key2 => $goods) {
				empty($goods['goods_image']) && $orders[$key1]['order_goods'][$key2]['goods_image'] = Conf::get('default_goods_image');
			}
		}

		$page['item_count'] = $model_order->getCount();
		$this->assign('types', array('all' => Lang::get('all_orders'),
			'pending' => Lang::get('pending_orders'),
			'submitted' => Lang::get('submitted_orders'),
			'accepted' => Lang::get('accepted_orders'),
			'shipped' => Lang::get('shipped_orders'),
			'finished' => Lang::get('finished_orders'),
			'canceled' => Lang::get('canceled_orders')));
		$this->assign('type', $_GET['type']);
		$this->assign('orders', $orders);
		$this->_format_page($page);
		$this->assign('page_info', $page);
	}
	//合并订单  360cd.cn    seema
	function order_merge() {
		$order_ids = empty($_GET['order_id']) ? 0 : trim($_GET['order_id']);
		$orders = explode(',', $order_ids);

		$order_id = SL('order')->get_order_id($orders);
		header('Location:index.php?app=cashier&order_id=' . $order_id);
	}

	function _get_member_submenu() {
		$menus = array(
			array(
				'name' => 'order_list',
				'url' => 'index.php?app=buyer_order',
			),
		);
		return $menus;
	}

}

?>
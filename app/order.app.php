<?php

/**
 *    售货员控制器，其扮演实际交易中柜台售货员的角色，你可以这么理解她：你告诉我（售货员）要买什么东西，我会询问你你要的收货地址是什么之类的问题
＊        并根据你的回答来生成一张单子，这张单子就是“订单”
 *
 *    @author    Garbin
 *    @param    none
 *    @return    void
 */
class OrderApp extends ShoppingbaseApp {

	/**
	 * 填写收货人信息，选择配送，支付方式。
	 *
	 * @author Garbin
	 * @param
	 *        	none
	 * @return void
	 */
	function index() {
		if (!$_GET['rec_id']) {
			return;
		}
		foreach ($_GET['rec_id'] as $key => $value) {
			$rec_id = explode(':', $value);
			$carts[$rec_id['0']] = $rec_id['0'];
		}

		foreach ($carts as $key => $cart) {

			$goods_info = $this->_get_goods_info($cart);
			if ($goods_info === false) {
				/* 购物车是空的 */
				$this->show_warning('goods_empty');

				return;
			}

			/* 检查库存 */
			$goods_beyond = $this->_check_beyond_stock($goods_info['items']);
			if ($goods_beyond) {
				$str_tmp = '';
				foreach ($goods_beyond as $goods) {
					$str_tmp .= '<br /><br />' . $goods['goods_name'] . '&nbsp;&nbsp;' . $goods['specification'] . '&nbsp;&nbsp;' . Lang::get('stock') . ':' . $goods['stock'];
				}
				$this->show_warning(sprintf(Lang::get('quantity_beyond_stock'), $str_tmp));
				return;
			}
			$carts[$key] = $goods_info;
		}

		if (!IS_POST) {

			foreach ($carts as $key => $goods_info) {
				/* 根据商品类型获取对应订单类型 */
				$goods_type = &gt($goods_info['type']);
				$order_type = &ot($goods_info['otype']);

				/* 显示订单表单 */
				$form = $order_type->get_order_form($goods_info['store_id']);
				if ($form === false) {
					$this->show_warning($order_type->get_error());

					return;
				}
				$carts[$key]['shipping_methods'] = SL('order')->_get_shipping($form['data']['shipping_methods'], $goods_info['quantity']);
			}

			// 360cd.cn 得到买家余额信息
			$user_id = $this->visitor->get('user_id');
			if ($user_id) {
				$money_model = &m('money');
				$this->assign('my_money', $money_model->get('user_id=' . $user_id));
			}

			// 360cd.cn 得到买家收货地址信息
			$address = $order_type->get_order_address();

			// 得到所有配送方式名称
			$this->assign('options_shipping', SL('order')->_get_shipping_name());

			// 得到所有配送信息
			$this->assign('shippings', SL('order')->_get_shipping_json());

			$this->_curlocal(LANG::get('create_order'));
			$this->_config_seo('title', Lang::get('confirm_order') . ' - ' . Conf::get('site_title'));

			// $this->assign('goods_info', $goods_info);
			// $this->assign($form['data']);

			// header('content-type:text/html; charset=utf8');
			// print_r("<pre>");
			// print_r(SL('order')->_get_shipping_json());
			// print_r("</pre>");

			$this->assign('carts', $carts);
			$this->assign($address['data']);
			$this->display($address['template']);
		} else {

			/* 在此获取生成订单的两个基本要素：用户提交的数据（POST），商品信息（包含商品列表，商品总价，商品总数量，类型），所属店铺 */

			// $store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;

			foreach ($carts as $key => $goods_info) {
				if ($goods_info === false) {
					/* 购物车是空的 */
					$this->show_warning('goods_empty');

					return;
				}
				$store_id = $key;
				/* 优惠券数据处理 */
				if ($goods_info['allow_coupon'] && isset($_POST['coupon_sn']) && !empty($_POST['coupon_sn'])) {
					$coupon_sn = trim($_POST['coupon_sn']);
					$coupon_mod = &m('couponsn');
					$coupon = $coupon_mod->get(array(
						'fields' => 'coupon.*,couponsn.remain_times',
						'conditions' => "coupon_sn.coupon_sn = '{$coupon_sn}' AND coupon.store_id = " . $store_id,
						'join' => 'belongs_to_coupon',
					));
					if (empty($coupon)) {
						$this->show_warning('involid_couponsn');
						exit();
					}
					if ($coupon['remain_times'] < 1) {
						$this->show_warning("times_full");
						exit();
					}
					$time = gmtime();
					if ($coupon['start_time'] > $time) {
						$this->show_warning("coupon_time");
						exit();
					}

					if ($coupon['end_time'] < $time) {
						$this->show_warning("coupon_expired");
						exit();
					}
					if ($coupon['min_amount'] > $goods_info['amount']) {
						$this->show_warning("amount_short");
						exit();
					}
					unset($time);
					$goods_info['discount'] = $coupon['coupon_value'];
				}
				/* 根据商品类型获取对应的订单类型 */
				$goods_type = &gt($goods_info['type']);
				$order_type = &ot($goods_info['otype']);

				/* 将这些信息传递给订单类型处理类生成订单(你根据我提供的信息生成一张订单) */
				$order_id = $order_type->submit_order(array(
					'goods_info' => $goods_info, // 商品信息（包括列表，总价，总量，所属店铺，类型）,可靠的!
					'post' => $_POST, // 用户填写的订单信息
				));

				if (!$order_id) {
					$this->show_warning($order_type->get_error());

					return;
				}
				$orders[$order_id] = $order_id;

				/* 下单完成后清理商品，如清空购物车，或将团购拍卖的状态转为已下单之类的 */
				$this->_clear_goods($order_id);

				/* 发送邮件 */
				$model_order = &m('order');

				/* 减去商品库存 */
				$model_order->change_stock('-', $order_id);

				/* 获取订单信息 */
				$order_info = $model_order->get($order_id);

				/* 发送事件 */
				$feed_images = array();
				foreach ($goods_info['items'] as $_gi) {
					$feed_images[] = array(
						'url' => SITE_URL . '/' . $_gi['goods_image'],
						'link' => SITE_URL . '/' . url('app=goods&id=' . $_gi['goods_id']),
					);
				}
				$this->send_feed('order_created', array(
					'user_id' => $this->visitor->get('user_id'),
					'user_name' => addslashes($this->visitor->get('user_name')),
					'seller_id' => $order_info['seller_id'],
					'seller_name' => $order_info['seller_name'],
					'store_url' => SITE_URL . '/' . url('app=store&id=' . $order_info['seller_id']),
					'images' => $feed_images,
				));

				$buyer_address = $this->visitor->get('email');
				$model_member = &m('member');
				$member_info = $model_member->get($goods_info['store_id']);
				$seller_address = $member_info['email'];

				/* 发送给买家下单通知 */
				$buyer_mail = get_mail('tobuyer_new_order_notify', array(
					'order' => $order_info,
				));
				$this->_mailto($buyer_address, addslashes($buyer_mail['subject']), addslashes($buyer_mail['message']));

				/* 发送给卖家新订单通知 */
				$seller_mail = get_mail('toseller_new_order_notify', array(
					'order' => $order_info,
				));
				$this->_mailto($seller_address, addslashes($seller_mail['subject']), addslashes($seller_mail['message']));

				/* 更新下单次数 */
				$model_goodsstatistics = &m('goodsstatistics');
				$goods_ids = array();
				foreach ($goods_info['items'] as $goods) {
					$goods_ids[] = $goods['goods_id'];
				}
				$model_goodsstatistics->edit($goods_ids, 'orders=orders+1');
			}
			// 360cd.cn
			//

			//检查是否添加收货人地址
			if (isset($_POST['save_address']) && (intval(trim($_POST['save_address'])) == 1)) {
				$data = array(
					'user_id' => $this->visitor->get('user_id'),
					'consignee' => trim($_POST['consignee']),
					'region_id' => $_POST['region_id'],
					'region_name' => $_POST['region_name'],
					'address' => trim($_POST['address']),
					'zipcode' => trim($_POST['zipcode']),
					'phone_tel' => trim($_POST['phone_tel']),
					'phone_mob' => trim($_POST['phone_mob']),
				);
				$model_address = &m('address');
				$model_address->add($data);
			}

			$my_money = isset($_POST['my_money']) && !empty($_POST['my_money']) ? floatval($_POST['my_money']) : 0;

			// $update_order_result=$this->update_order_money($orders,$my_money);
			// 360cd.cn

			// 360cd.cn
			if ($update_order_result == 20) {
				foreach ($orders as $key => $order_id) {
					// $biz->update_order($order_id,array('status'=>20,));
					$this->show_message("order_commit_yes", "back_buyer_order", 'index.php?app=buyer_order');
				}
			} else {
				// 360cd.cn
				/* 到收银台付款 */
				$order_id = SL('order')->get_order_id($orders); // 返回订单id 360cd.cn seema

				header('location: index.php?app=cashier&order_id=' . $order_id);
			}
		}
	}

	/**
	 * 获取外部传递过来的商品
	 *
	 * @author Garbin
	 * @param
	 *        	none
	 * @return void
	 */
	function _get_goods_info($store_id) {
		$return = array(
			'items' => array(), // 商品列表
			'quantity' => 0, // 商品总量
			'amount' => 0, // 商品总价
			'store_id' => 0, // 所属店铺
			'store_name' => '', // 店铺名称
			'type' => null, // 商品类型
			'otype' => 'normal', // 订单类型
			'allow_coupon' => true,
		) // 是否允许使用优惠券
		;

		switch ($_GET['goods']) {
		case 'groupbuy':
			/* 团购的商品 */
			$group_id = isset($_GET['group_id']) ? intval($_GET['group_id']) : 0;
			$user_id = $this->visitor->get('user_id');
			if (!$group_id || !$user_id) {
				return false;
			}
			/* 获取团购记录详细信息 */
			$model_groupbuy = &m('groupbuy');
			$groupbuy_info = $model_groupbuy->get(array(
				'join' => 'be_join, belong_store, belong_goods',
				'conditions' => $model_groupbuy->getRealFields("groupbuy_log.user_id={$user_id} AND groupbuy_log.group_id={$group_id} AND groupbuy_log.order_id=0 AND this.state=" . GROUP_FINISHED),
				'fields' => 'store.store_id, store.store_name, goods.goods_id, goods.goods_name, goods.default_image, groupbuy_log.quantity, groupbuy_log.spec_quantity, this.spec_price',
			));

			if (empty($groupbuy_info)) {
				return false;
			}

			/* 库存信息 */
			$model_goodsspec = &m('goodsspec');
			$goodsspec = $model_goodsspec->find('goods_id=' . $groupbuy_info['goods_id']);

			/* 获取商品信息 */
			$spec_quantity = unserialize($groupbuy_info['spec_quantity']);
			$spec_price = unserialize($groupbuy_info['spec_price']);
			$amount = 0;
			$groupbuy_items = array();
			$goods_image = empty($groupbuy_info['default_image']) ? Conf::get('default_goods_image') : $groupbuy_info['default_image'];
			foreach ($spec_quantity as $spec_id => $spec_info) {
				$the_price = $spec_price[$spec_id]['price'];
				$subtotal = $spec_info['qty'] * $the_price;
				$groupbuy_items[] = array(
					'goods_id' => $groupbuy_info['goods_id'],
					'goods_name' => $groupbuy_info['goods_name'],
					'spec_id' => $spec_id,
					'specification' => $spec_info['spec'],
					'price' => $the_price,
					'quantity' => $spec_info['qty'],
					'goods_image' => $goods_image,
					'subtotal' => $subtotal,
					'stock' => $goodsspec[$spec_id]['stock'],
				);
				$amount += $subtotal;
			}

			$return['items'] = $groupbuy_items;
			$return['quantity'] = $groupbuy_info['quantity'];
			$return['amount'] = $amount;
			$return['store_id'] = $groupbuy_info['store_id'];
			$return['store_name'] = $groupbuy_info['store_name'];
			$return['type'] = 'material';
			$return['otype'] = 'groupbuy';
			$return['allow_coupon'] = false;
			break;
		default:
			/* 从购物车中取商品 */
			if (!$_GET['rec_id']) {
				return;
			}
			foreach ($_GET['rec_id'] as $key => $value) {
				$rec_id = explode(':', $value);
				$rec_ids .= $rec_id['1'] . ",";
			}

			if (!$rec_ids) {
				return false;
			}

			$rec_ids .= 0;

			$cart_model = &m('cart');

			$conditions = ' 1 = 1 ';
			$conditions .= " AND user_id = " . $this->visitor->get('user_id') . " AND rec_id in ({$rec_ids}) and store_id={$store_id} ";

			$cart_items = $cart_model->find(array(
				'conditions' => $conditions,
				'join' => 'belongs_to_goodsspec',
			));
			if (empty($cart_items)) {
				return false;
			}

			$store_model = &m('store');
			$store_info = $store_model->get($store_id);

			foreach ($cart_items as $rec_id => $goods) {
				$return['quantity'] += $goods['quantity']; // 商品总量
				$return['amount'] += $goods['quantity'] * $goods['price']; // 商品总价
				$cart_items[$rec_id]['subtotal'] = $goods['quantity'] * $goods['price']; // 小计
				empty($goods['goods_image']) && $cart_items[$rec_id]['goods_image'] = Conf::get('default_goods_image');
			}

			$return['items'] = $cart_items;
			$return['store_id'] = $store_id;
			$return['store_name'] = $store_info['store_name'];
			$return['type'] = 'material';
			$return['otype'] = 'normal';

			break;
		}

		return $return;
	}

	/**
	 * 下单完成后清理商品
	 *
	 * @author Garbin
	 * @return void
	 */
	function _clear_goods($order_id) {
		switch ($_GET['goods']) {
		case 'groupbuy':
			/* 团购的商品 */
			$model_groupbuy = &m('groupbuy');
			$model_groupbuy->updateRelation('be_join', $_GET['group_id'], $this->visitor->get('user_id'), array(
				'order_id' => $order_id,
			));
			break;
		default: // 购物车中的商品
			/* 订单下完后清空指定购物车 */
			// $_GET['store_id'] = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
			$store_id = $this->get_store_id($order_id);

			if (!$store_id) {
				return false;
			}

			$model_cart = &m('cart');
			$model_cart->drop("store_id = {$store_id} AND user_id = " . $this->visitor->get('user_id'));
			// 优惠券信息处理
			if (isset($_POST['coupon_sn']) && !empty($_POST['coupon_sn'])) {
				$sn = trim($_POST['coupon_sn']);
				$couponsn_mod = &m('couponsn');
				$couponsn = $couponsn_mod->get("coupon_sn = '{$sn}'");
				if ($couponsn['remain_times'] > 0) {
					$couponsn_mod->edit("coupon_sn = '{$sn}'", "remain_times= remain_times - 1");
				}
			}
			break;
		}
	}

	/**
	 * 检查优惠券有效性
	 */
	function check_coupon() {
		$coupon_sn = $_GET['coupon_sn'];
		$store_id = is_numeric($_GET['store_id']) ? $_GET['store_id'] : 0;
		if (empty($coupon_sn)) {
			$this->js_result(false);
		}
		$coupon_mod = &m('couponsn');
		$coupon = $coupon_mod->get(array(
			'fields' => 'coupon.*,couponsn.remain_times',
			'conditions' => "coupon_sn.coupon_sn = '{$coupon_sn}' AND coupon.store_id = " . $store_id,
			'join' => 'belongs_to_coupon',
		));
		if (empty($coupon)) {
			$this->json_result(false);
			exit();
		}
		if ($coupon['remain_times'] < 1) {
			$this->json_result(false);
			exit();
		}
		$time = gmtime();
		if ($coupon['start_time'] > $time) {
			$this->json_result(false);
			exit();
		}

		if ($coupon['end_time'] < $time) {
			$this->json_result(false);
			exit();
		}

		// 检查商品价格与优惠券要求的价格

		$model_cart = &m('cart');
		$item_info = $model_cart->find("store_id={$store_id} AND session_id='" . SESS_ID . "'");
		$price = 0;
		foreach ($item_info as $val) {
			$price = $price + $val['price'] * $val['quantity'];
		}
		if ($price < $coupon['min_amount']) {
			$this->json_result(false);
			exit();
		}
		$this->json_result(array(
			'res' => true,
			'price' => $coupon['coupon_value'],
		));
		exit();
	}

	function _check_beyond_stock($goods_items) {
		$goods_beyond_stock = array();
		foreach ($goods_items as $rec_id => $goods) {
			if ($goods['quantity'] > $goods['stock']) {
				$goods_beyond_stock[$goods['spec_id']] = $goods;
			}
		}
		return $goods_beyond_stock;
	}

	/*
		 * //修改订单金额
		 * function update_order_money($orders,$my_money){
		 * //---www.360cd.cn Mosquito---
		 * return 20;
		 *
		 * import('zllib/biz.lib');
		 * //$biz=new bizOrder();
		 * $order_mob=&m('order');
		 * foreach ($orders as $key => $order_id) {
		 * $order_info=$order_mob->get($order_id);
		 * $order_amount+=$order_info['order_amount'];
		 * }
		 * $point=$my_money/$order_amount;
		 *
		 * foreach ($orders as $key => $order_id) {
		 * if($order_id != end($orders)) {
		 * $order_info=$order_mob->get($order_id);
		 * $amount=$order_info['order_amount'];
		 * $pay_money=sprintf("%.2f",($amount*$point));
		 * //$update_order_result=$biz->update_order_money($order_id,$pay_money);
		 * $money+=$pay_money;
		 * } else {
		 * $pay_money=$my_money-$money;
		 * //$update_order_result=$biz->update_order_money($order_id,$pay_money);
		 * }
		 * }
		 * if ($my_money==$order_amount) {
		 * return 20;
		 * }
		 *
		 * }
	*/

	// 通过order_id 得到store_id
	function get_store_id($order_id) {
		$order_mob = &m('order');
		$order_info = $order_mob->get($order_id);
		return $store_id = $order_info['seller_id'];
	}
}

?>
<?php
class Order {

	//360cd.cn 得到所有配送方式名称
	function _get_shipping_name() {
		return LM('shipping_ext')->get_shipping_list();
	}
	//360cd.cn 得到所有配送方式信息
	function _get_shipping_json() {
		return ecm_json_encode(LM('shipping_ext')->find());
	}
	//360cd.cn  得到当前配送金额
	function _get_shipping($shippings, $quantity) {
		foreach ($shippings as $key => $shipping) {
			$shippings[$key]['price'] = $shipping['first_price'] + ($quantity - 1) * $shipping['step_price'];
		}
		return $shippings;
	}
	//生成合并订单信息
	function get_order_id($orders) {

		$order_type = &ot('normal');
		if (count($orders) < 2) {
			foreach ($orders as $key => $order_id) {
				return $order_id;
			}
		}
		foreach ($orders as $key => $order_id) {
			$where = ("order_id={$order_id}");
			$order_info = $this->get_order($where);
			$order_amount += $order_info['order_amount'];
			$pay_money += $order_info['pay_money'];
			$order_sns[$order_id] = $order_info['order_sn'];
		}
		$data = array(
			'order_sn' => $order_type->_gen_order_sn(),
			'order_merge' => 1,
			'order_sns' => serialize($order_sns),
			'status' => ORDER_PENDING,
			'add_time' => gmtime(),
			'order_amount' => $order_amount,
			'pay_money' => $pay_money,
			'extension' => 'normal',
		);
		LM('order')->add($data);
	}

	//得到订单信息
	function get_order($where) {
		return LM('order')->get($where);
	}

}

?>
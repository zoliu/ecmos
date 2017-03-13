<?php
/**
 * 菜单类
 * {ds name=menu type=show role=? return=_menu}
 * 角色代码
 */
class MenuDs extends baseDs {

	function _init($params) {
		echo $_SESSION['MEMBER_MENU'];
	}

	/**
	 * 显示菜单
	 * {ds name=menu type=show role=? return=menu} role为角色切换代码，可以通过get传参
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	public function dsShow($params) {
		$role = $_SESSION['MEMBER_MENU'];
		if (isset($params['role']) && !empty($params['role'])) {
			$role = trim($params['role']);
		}
		if (empty($role)) {
			$role = 'member';
		}

		if (in_array($role, array('store', 'supply', 'agent', 'member'))) {
			$_SESSION['MEMBER_MENU'] = $role;
			return $this->$role($params);
		}
		return $this->member($params);
	}

	/**
	 * 用户界面
	 * {ds name=menu type=member return=menu}
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	public function member($params) {
		$_menu = array();
		$_menu['person_center'] = array(
			'name' => 'person_center',
			'text' => '会员中心',
			'submenu' => array(
				'my_baseinfo' => array(
					'text' => '基本信息',
					'url' => 'index.php?app=member&act=profile',
					'name' => 'my_baseinfo',
					'icon' => 'ico1',
				),

				'my_address' => array(
					'text' => '收货地址',
					'url' => 'index.php?app=my_address',
					'name' => 'my_address',
					'icon' => 'ico1',
				),
				'my_point' => array(
					'text' => '积分管理',
					'url' => 'index.php?app=my_integral_goods',
					'name' => 'my_point',
					'icon' => 'ico1',
				),
				'my_money' => array(
					'text' => '钱包管理',
					'url' => 'index.php?app=my_money&act=logall',
					'name' => 'my_money',
					'icon' => 'ico1',
				),
				'buyer_order' => array(
					'text' => '我的订单',
					'url' => 'index.php?app=buyer_order',
					'name' => 'buyer_order',
					'icon' => 'ico1',
				),

			));
		return $_menu;
	}

	/**
	 * 商家界面
	 * {ds name=menu type=store return=menu}
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	public function store($params) {
		$_menu = array();
		$_menu['store_center'] = array(
			'name' => 'store_center',
			'text' => '商家中心',
			'submenu' => array(
				'my_goods' => array(
					'text' => Lang::get('my_goods'),
					'url' => 'index.php?app=my_goods',
					'name' => 'my_goods',
					'icon' => 'ico8',
				),

				'order_manage' => array(
					'text' => Lang::get('order_manage'),
					'url' => 'index.php?app=seller_order',
					'name' => 'order_manage',
					'icon' => 'ico10',
				),
				'my_category' => array(
					'text' => Lang::get('my_category'),
					'url' => 'index.php?app=my_category',
					'name' => 'my_category',
					'icon' => 'ico9',
				),
				'my_store' => array(
					'text' => Lang::get('my_store'),
					'url' => 'index.php?app=my_store',
					'name' => 'my_store',
					'icon' => 'ico11',
				),
			));
		return $_menu;
	}

	/**
	 * 代理界面
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	public function agent($params) {
		$_menu = array();
		$_menu['agent_center'] = array(
			'name' => 'agent_center',
			'text' => Lang::get('person_center'),
			'submenu' => array(
				'my_baseinfo' => array(
					'text' => '基本信息',
					'url' => 'index.php?app=member&act=profile',
					'name' => 'my_baseinfo',
					'icon' => 'ico1',
				),

				'my_address' => array(
					'text' => '收货地址',
					'url' => 'index.php?app=my_address',
					'name' => 'my_address',
					'icon' => 'ico1',
				),
				'my_point' => array(
					'text' => '积分管理',
					'url' => 'index.php?app=my_integral_goods',
					'name' => 'my_point',
					'icon' => 'ico1',
				),
				'epay' => array(
					'text' => '钱包管理',
					'url' => 'index.php?app=epay&act=logall',
					'name' => 'epay',
					'icon' => 'ico1',
				),
			));
		return $_menu;
	}

	/**
	 * 供应商界面
	 * @param  string $value [description]
	 * @return [type]        [description]
	 */
	public function supply($params) {
		$_menu = array();
		$_menu['supply_center'] = array(
			'name' => 'person_center',
			'text' => Lang::get('person_center'),
			'submenu' => array(
				'my_baseinfo' => array(
					'text' => '基本信息',
					'url' => 'index.php?app=member&act=profile',
					'name' => 'my_baseinfo',
					'icon' => 'ico1',
				),

				'my_address' => array(
					'text' => '收货地址',
					'url' => 'index.php?app=my_address',
					'name' => 'my_address',
					'icon' => 'ico1',
				),
				'my_point' => array(
					'text' => '积分管理',
					'url' => 'index.php?app=my_integral_goods',
					'name' => 'my_point',
					'icon' => 'ico1',
				),
				'epay' => array(
					'text' => '钱包管理',
					'url' => 'index.php?app=epay&act=logall',
					'name' => 'epay',
					'icon' => 'ico1',
				),
			));
		return $_menu;
	}

}

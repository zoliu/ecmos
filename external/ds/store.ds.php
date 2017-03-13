<?php
class StoreDs extends baseDs {

	/**
	 * 调用商铺的友情链接
	 *{ds name=store type=partner store_id=?}
	 *
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	public function dsPartner($params) {
		$store_id = intval($params['store_id']);
		if ($store_id < 1) {
			return;
		}

		$partner_mod = &m('partner');
		return $partner_mod->find(array(
			'conditions' => "store_id = {$store_id}",
			'order' => 'sort_order',
		));
	}
	/**
	 * 调用商铺分类
	 * {ds name=store type=category store_id=?}
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	function dsCategory($params) {
		$store_id = intval($params['store_id']);
		if ($store_id < 1) {
			return;
		}

		$gcategory_mod = &bm('gcategory', array('_store_id' => $store_id));
		$gcategories = $gcategory_mod->get_list(-1, true);
		import('tree.lib');
		$tree = new Tree();
		$tree->setTree($gcategories, 'cate_id', 'parent_id', 'cate_name');
		return $tree->getArrayList(0);
	}
	/**
	 * 调用商铺文章导航
	 * {ds name=store type=nav store_id=?}
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	function dsNav($params) {
		$store_id = intval($params['store_id']);
		if ($store_id < 1) {
			return;
		}

		$article_mod = &m('article');
		return $article_mod->find(array(
			'conditions' => "store_id = {$store_id} AND cate_id = '" . STORE_NAV . "' AND if_show = 1",
			'order' => 'sort_order',
			'fields' => 'title',
		));
	}

	/**
	 * 调用商铺详细
	 * {ds name=store type=view store_id=?}
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	function dsView($params) {
		$store_id = intval($params['store_id']);
		if ($store_id < 1) {
			return;
		}

		//360cd.cn
		$store_mod = &m('store');
		$store = $store_mod->get_info($store_id);
		if (empty($store)) {
			$this->show_warning('the_store_not_exist');
			exit;
		}
		if ($store['state'] == 2) {
			$this->show_warning('the_store_is_closed');
			exit;
		}
		$step = intval(Conf::get('upgrade_required'));
		$step < 1 && $step = 5;

		$store['credit_image'] = $this->_view->res_base . '/images/' . $store_mod->compute_credit($store['credit_value'], $step);

		empty($store['store_logo']) && $store['store_logo'] = Conf::get('default_store_logo');
		$store['store_owner'] = LM('member')->where('user_id=' . $store_id)->get();
		$goods_mod = &m('goods');
		$store['goods_count'] = $goods_mod->get_count_of_store($store_id);

		return $store;
	}

	public function dsGrade($params) {
		$store_id = intval($params['store_id']);
		if ($store_id < 1) {
			return;
		}
		$joinstr .= LM('sgrade')->parseJoin('grade_id', 'sgrade', 'store');
		$data = LM('sgrade')->fields('this.*')->where('store.store_id=' . $store_id)->join($joinstr)->get();
		if ($data) {
			$data['func'] = array();
			$functions = $data['functions'];
			if ($functions) {
				$functions = explode(',', $functions);
				foreach ($functions as $k => $v) {
					$data['func'][$v] = $v;
				}
			}
		}
		return $data;

	}

}
?>

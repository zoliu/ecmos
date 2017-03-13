<?php
/**
 * {ds name=goods type=hotSales num=?}
 */
class GoodsDs extends baseDs {

	/**
	 * 显示商品详细
	 * {ds name=goods type=view goods_id=? }
	 *
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	public function dsView($params) {

		if (intval($params['goods_id']) > 0) {
			$goods_id = intval($params['goods_id']);
			$joinstr .= LM('goods')->parseJoin('store_id', 'store_id', 'store');
			$joinstr2 = LM('goods')->parseJoin('goods_id', 'goods_id', 'goodsstatistics');
			$data = LM('goods')->where('this.goods_id=' . $goods_id)->join($joinstr)->join($joinstr2)->get();
			if ($data) {
				$data['specs'] = LM('goodsspec')->where('this.goods_id=' . $goods_id)->find();
			}
			return $data;
		}
	}

	/**
	 * 热销产品
	 *{ds name=goods type=hotSales num=?}
	 *
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	public function dsHotSales($params) {
		$gcategory_mod = &m('gcategory');
		$gcategory = $gcategory_mod->find('parent_id=0 and store_id=0');
		$goods_mod = &m('goods');
		foreach ($gcategory as $key => $gc) {
			$gcategory[$key]['goods'] = $goods_mod->find(array(
				'conditions' => 'cate_id_1 = ' . $gc['cate_id'],
				'join' => 'has_goodsstatistics',
				'order' => 'goodsstatistics.sales desc',
				'limit' => $params['num'],
			));
			if (!$gcategory[$key]['goods']) {
				unset($gcategory[$key]);
			}
		}
		$data = $gcategory;
		return $data;
	}
	/**
	 * 得到商品的历史记录并且保存当前商品的记录
	 * {ds name=goods type=history num=? goods_id=?}
	 * goods_id=当前商品ID
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	public function dsHistory($params) {
		if ($params['goods_id'] < 1) {
			return;
		}

		$_goods_mod = &m('goods');
		$goods_list = array();
		$goods_ids = ecm_getcookie('goodsBrowseHistory');
		$goods_ids = $goods_ids ? explode(',', $goods_ids) : array();
		if ($goods_ids) {
			$rows = $_goods_mod->find(array(
				'conditions' => $goods_ids,
				'fields' => 'goods_name,default_image',
			));
			foreach ($goods_ids as $goods_id) {
				if (isset($rows[$goods_id])) {
					empty($rows[$goods_id]['default_image']) && $rows[$goods_id]['default_image'] = Conf::get('default_goods_image');
					$goods_list[] = $rows[$goods_id];
				}
			}
		}
		$goods_ids[] = $params['goods_id'];
		if (count($goods_ids) > $params['num']) {
			unset($goods_ids[0]);
		}
		ecm_setcookie('goodsBrowseHistory', join(',', array_unique($goods_ids)));

		return $goods_list;
	}
}
?>

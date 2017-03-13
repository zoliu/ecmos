<?php
class CategoryDs extends baseDs {

	/**
	 * 得到商铺分类
	 *{ds name=category type=store}
	 *
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	function dsStore($params) {
		$cate_id = intval($params['cate_id']);
		$parent_id_1 = -1;
		$parent_id_2 = 0;
		if ($cate_id > 0) {
			$parent_id_2 = $parent_id_1 = $cate_id;
		}
		$category_mod = &m('scategory');
		$categories = $category_mod->get_list($parent_id_1, true);
		import('tree.lib');
		$tree = new Tree();
		$tree->setTree($categories, 'cate_id', 'parent_id', 'cate_name');
		$data = $tree->getArrayList($parent_id_2);
		return $data;
	}

	/**
	 * 调用商品的分类
	 * {ds name=category type=goods}
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	function dsGoods($params) {
		$cate_id = intval($params['cate_id']);
		$parent_id_1 = -1;
		$parent_id_2 = 0;
		if ($cate_id > 0) {
			$parent_id_2 = $parent_id_1 = $cate_id;
		}
		$category_mod = &bm('gcategory', array('_store_id' => 0));
		$categories = $category_mod->get_list($parent_id_1, true);
		import('tree.lib');
		$tree = new Tree();
		$tree->setTree($categories, 'cate_id', 'parent_id', 'cate_name');
		$data = $tree->getArrayList($parent_id_2);
		return $data;
	}
}

?>
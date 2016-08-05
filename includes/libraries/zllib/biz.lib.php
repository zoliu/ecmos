<?php

class bizArticle {
	// 360cd.cn
	function get_nav_category($cate_id) {
		$article_model = &m('acategory');
		$childs = $article_model->get_list($cate_id);
		if (!$childs) {
			return 0;
		}
		
		if (is_array($childs) && count($childs) > 0) {
			foreach ( $childs as $k => $v ) {
				$childs[$k]['childs'] = $this->get_nav_article($v['cate_id']);
			}
		}
		return $childs;
	}

	function get_nav_article($cate_id) {
		// 360cd.cn
		$article_model = &m('article');
		$where = array(
			'conditions' => ' cate_id=' . $cate_id,
			'order' => 'sort_order desc,article_id desc' 
		);
		$article_data = $article_model->find($where);
		if (!$article_data) {
			// 此处填写数据不存在内容
			return 0;
		}
		return $article_data;
		// 360cd.cn
	}
	// 360cd.cn
}



/**
 * 通用业务相关
 * @author Mosquito
 * @link www.360cd.cn
 */
class Biz {
	
	//参数说明（二级分类显示数量,弹出层位置,品牌是否为推荐）
	static function get_header_gcategories($amount, $position, $recomd_brand = 1) {
		$gcategory_mod = &bm('gcategory', array(
			'_store_id' => 0 
		));
		$gcategories = array();
		
		if (!$amount) {
			$gcategories = $gcategory_mod->get_list(-1, true);
		} else {
			$gcategory = $gcategory_mod->get_list(0, true);
			$gcategories = $gcategory;
			foreach ( $gcategory as $val ) {
				$result = $gcategory_mod->get_list($val['cate_id'], true);
				$result = array_slice($result, 0, $amount);
				$gcategories = array_merge($gcategories, $result);
			}
		}
		$ogcates = $gcategories;
		
		import('tree.lib');
		$tree = new Tree();
		$tree->setTree($gcategories, 'cate_id', 'parent_id', 'cate_name');
		$gcategory_list = $tree->getArrayList(0);
		foreach ( $gcategory_list as $key => $value ) {
			$gcategory_list[$key]['logo'] = $ogcates[$value['id']]['logo'];
			$gcategory_list[$key]['recom_logo'] = self::get_recom_logo($value['id']);
		}
		$i = 0;
		$brand_mod = &m('brand');
		foreach ( $gcategory_list as $k => $v ) {
			$gcategory_list[$k]['top'] = isset($position[$i]) ? $position[$i] : '0px';
			$i++;
			$gcategory_list[$k]['brands'] = $brand_mod->find(array(
				'conditions' => "tag = '" . $v['value'] . "' AND recommended=" . $recomd_brand,
				'order' => 'sort_order asc,brand_id desc' 
			));
		}
		return array(
			'gcategories' => $gcategory_list 
		);
	}

	//得到分类下推荐的广告图片
	static function get_recom_logo($gcategory_id) {
		$mod = &m('recommendation');
		
		$cate_mod = &m('rcategory');
		
		$cate_list = $cate_mod->get('sort_order=12');
		$conditions .= ' and cate_id=' . $cate_list['cate_id'];
		$conditions .= $r_type ? ' and r_type="image_5"' : '';
		$conditions .= $gcategory_id ? ' and gcategory_id=' . $gcategory_id : '';
		
		$recom_list = $mod->find(array(
			'conditions' => '1=1' . $conditions,
			'order' => 'sort_order asc',
			'limit' => 3 
		));
		return $recom_list;
	}
	
	//头部推荐行为
	static function get_image_4() {
		$mod = &m('recommendation');
		$cate_mod = &m('rcategory');
		
		$cate_list = $cate_mod->get('sort_order = 1');
		$conditions .= ' and cate_id=' . $cate_list['cate_id'];
		$conditions .= $r_type ? ' and r_type="' . $r_type . '"' : '';
		$conditions .= $gcategory_id ? ' and gcategory_id=' . $gcategory_id : '';
		
		$recom_list = $mod->find(array(
			'conditions' => 'if_show=1' . $conditions,
			'order' => 'sort_order asc',
			'limit' => 8, 
		));
		foreach ( $recom_list as $key => $value ) {
			$recom_list[$key]['key_list'] = explode(' ', $value['key_words']);
		}
		return $recom_list;
	}
	
	static function get_search_stats($type) {
		
		import('search.lib');
		
		$search = new search();
		$cate_id = $_GET['cate_id'];
		$brand = $_GET['brand'];
		$price = $_GET['price'];
		$region_id = $_GET['region_id'];
		$keyword = $_GET['keyword'];
		// 查询参数
		$param = $search->_get_query_param($cate_id, $brand, $price, $region_id, $keyword);
		if (empty($param)) {
			header('Location: index.php?app=category');
			exit();
		}
		if (isset($param['cate_id']) && $param['layer'] === false) {
			$this->show_warning('no_such_category');
			return;
		}
		if ($type == "filters") {
			//筛选条件
			$data = $search->_get_filter($param);
		} else if ($type == "orders") {
			//排序 
			$data = self::get_search_stats_orders();
		} else {
			//按分类、品牌、地区、价格区间统计商品数量 
			$stats = $search->_get_group_by_info($param, ENABLE_SEARCH_CACHE);
			$data['stats'] = $stats[$type];
			$data['stats_count'] = count($stats[$type]);
		}
		
		return $data;
	}
	
	//商品排序方式 
	static function get_search_stats_orders() {
		return array(
			'' => Lang::get('default_order'),
			'sales' => Lang::get('sales_desc'), // 销量
			'credit_value' => Lang::get('credit_value_desc'), // 信用度
			'price' => Lang::get('price'), // 价格
			'views' => Lang::get('views_desc'), // 浏览量
			'add_time' => Lang::get('add_time_desc') // 上架时间
		);
	}
	
	static function get_search_goods($type) {
		$id = $_GET['id'];
		$goods_mod = & bm('goods', array(
			'_store_id' => $id 
		));
		$search_name = LANG::get('all_goods');
		
		$conditions = $_GET['keyword'] ? " and goods_name like '%" . $_GET['keyword'] . "%'" : '';
		if ($conditions) {
			$search_name = sprintf(LANG::get('goods_include'), $_GET['keyword']);
			$sgcate_id = 0;
		} else {
			$sgcate_id = empty($_GET['cate_id']) ? 0 : intval($_GET['cate_id']);
		}
		
		if ($sgcate_id > 0) {
			$gcategory_mod = & bm('gcategory', array(
				'_store_id' => $id 
			));
			$sgcate = $gcategory_mod->get_info($sgcate_id);
			$search_name = $sgcate['cate_name'];
			
			$sgcate_ids = $gcategory_mod->get_descendant_ids($sgcate_id);
		} else {
			$sgcate_ids = array();
		}
		
		//排序方式 
		$orders = self::get_orders();
		
		$page = $this->_get_page(16);
		$goods_list = $goods_mod->get_list(array(
			'conditions' => 'closed = 0 AND if_show = 1' . $conditions,
			'count' => true,
			'order' => empty($_GET['order']) || !isset($orders[$_GET['order']]) ? 'add_time desc' : $_GET['order'],
			'limit' => $page['limit'] 
		), $sgcate_ids);
		foreach ( $goods_list as $key => $goods ) {
			empty($goods['default_image']) && $goods_list[$key]['default_image'] = Conf::get('default_goods_image');
		}
		
		$page['item_count'] = $goods_mod->getCount();
		$this->_format_page($page);
		
		switch ($type) {
			case 'orders' :
				$data = $orders;
				break;
			case 'searched_goods' :
				$data = $goods_list;
				break;
			case 'page_info' :
				$data = $page;
				break;
			case 'search_name' :
				$data = $search_name;
				break;
			default :
				$data = '';
				break;
		}
		
		return $data;
	}

	static function get_orders() {
		return array(
			'add_time desc' => LANG::get('add_time_desc'),
			'price asc' => LANG::get('price_asc'),
			'price desc' => LANG::get('price_desc') 
		);
	}
}

?>
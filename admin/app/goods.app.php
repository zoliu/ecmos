<?php
/**
 *    商品管理控制器
 */
class GoodsApp extends BackendApp {
	var $_goods_mod;

	function __construct() {
		$this->GoodsApp();
	}
	function GoodsApp() {
		parent::BackendApp();

		$this->_goods_mod = &m('goods');
	}

	/* 商品列表 */
	function index() {
		$conditions = $this->_get_query_conditions(array(
			array(
				'field' => 'goods_name',
				'equal' => 'like',
			),
			array(
				'field' => 'store_name',
				'equal' => 'like',
			),
			array(
				'field' => 'brand',
				'equal' => 'like',
			),
			array(
				'field' => 'closed',
				'type' => 'int',
			),
		));

		// 分类
		$cate_id = empty($_GET['cate_id']) ? 0 : intval($_GET['cate_id']);
		if ($cate_id > 0) {
			$cate_mod = &bm('gcategory');
			$cate_ids = $cate_mod->get_descendant_ids($cate_id);
			$conditions .= " AND cate_id" . db_create_in($cate_ids);
		}

		//更新排序
		if (isset($_GET['sort']) && isset($_GET['order'])) {
			$sort = strtolower(trim($_GET['sort']));
			$order = strtolower(trim($_GET['order']));
			if (!in_array($order, array('asc', 'desc'))) {
				$sort = 'goods_id';
				$order = 'desc';
			}
		} else {
			$sort = 'goods_id';
			$order = 'desc';
		}

		$page = $this->_get_page();
		$goods_list = $this->_goods_mod->get_list(array(
			'conditions' => "1 = 1" . $conditions,
			'count' => true,
			'order' => "$sort $order",
			'limit' => $page['limit'],
		));

		foreach ($goods_list as $key => $goods) {
			$goods_list[$key]['cate_name'] = $this->_goods_mod->format_cate_name($goods['cate_name']);
			$goods_spec = $this->get_spec_list($key);
			$goods_list[$key]['_specs'] = $goods_spec;
		}
		$this->assign('goods_list', $goods_list);

		$page['item_count'] = $this->_goods_mod->getCount();
		$this->_format_page($page);
		$this->assign('page_info', $page);

		// 第一级分类
		$cate_mod = &bm('gcategory', array('_store_id' => 0));
		$this->assign('gcategories', $cate_mod->get_options(0, true));
		$this->import_resource(array('script' => 'mlselection.js,inline_edit.js'));
		$this->assign('enable_radar', Conf::get('enable_radar'));
		$this->display('goods.index.html');
	}
	function get_spec_list($goods_id) {
		//360cd.cn
		$goodsspec_model = &m('goodsspec');
		$where = "goods_id={$goods_id}";
		$goodsspec_data = $goodsspec_model->find($where);
		if (!$goodsspec_data) {
			//此处填写数据不存在内容
			return 0;
		}
		return $goodsspec_data;
		//360cd.cn
	}

	/* 推荐商品到 */
	function recommend() {
		if (!IS_POST) {
			/* 取得推荐类型 */
			$recommend_mod = &bm('recommend', array('_store_id' => 0));
			$recommends = $recommend_mod->get_options();
			if (!$recommends) {
				$this->show_warning('no_recommends', 'go_back', 'javascript:history.go(-1);', 'set_recommend', 'index.php?app=recommend');
				return;
			}
			$this->assign('recommends', $recommends);
			$this->display('goods.batch.html');
		} else {
			$id = isset($_POST['id']) ? trim($_POST['id']) : '';
			if (!$id) {
				$this->show_warning('Hacking Attempt');
				return;
			}

			$recom_id = empty($_POST['recom_id']) ? 0 : intval($_POST['recom_id']);
			if (!$recom_id) {
				$this->show_warning('recommend_required');
				return;
			}

			$ids = explode(',', $id);
			$recom_mod = &bm('recommend', array('_store_id' => 0));
			$recom_mod->createRelation('recommend_goods', $recom_id, $ids);
			$ret_page = isset($_GET['ret_page']) ? intval($_GET['ret_page']) : 1;
			$this->show_message('recommend_ok',
				'back_list', 'index.php?app=goods&page=' . $ret_page,
				'view_recommended_goods', 'index.php?app=recommend&amp;act=view_goods&amp;id=' . $recom_id);
		}
	}

	/* 编辑商品 */
	function edit() {
		if (!IS_POST) {
			// 第一级分类
			$cate_mod = &bm('gcategory', array('_store_id' => 0));
			$this->assign('gcategories', $cate_mod->get_options(0, true));

			$this->headtag('<script type="text/javascript" src="{lib file=mlselection.js}"></script>');
			$this->display('goods.batch.html');
		} else {
			$id = isset($_POST['id']) ? trim($_POST['id']) : '';
			if (!$id) {
				$this->show_warning('Hacking Attempt');
				return;
			}

			$ids = explode(',', $id);
			$data = array();
			if ($_POST['cate_id'] > 0) {
				$data['cate_id'] = $_POST['cate_id'];
				$data['cate_name'] = $_POST['cate_name'];
			}
			if (trim($_POST['brand'])) {
				$data['brand'] = trim($_POST['brand']);
			}
			if ($_POST['closed'] >= 0) {
				$data['closed'] = $_POST['closed'] ? 1 : 0;
				$data['close_reason'] = $_POST['closed'] ? $_POST['close_reason'] : '';
			}

			if (empty($data)) {
				$this->show_warning('no_change_set');
				return;
			}

			$this->_goods_mod->edit($ids, $data);
			$ret_page = isset($_GET['ret_page']) ? intval($_GET['ret_page']) : 1;
			$this->show_message('edit_ok',
				'back_list', 'index.php?app=goods&page=' . $ret_page);
		}
	}

	//异步修改数据
	function ajax_col() {
		$id = empty($_GET['id']) ? 0 : intval($_GET['id']);
		$column = empty($_GET['column']) ? '' : trim($_GET['column']);
		$value = isset($_GET['value']) ? trim($_GET['value']) : '';
		$data = array();

		if (in_array($column, array('goods_name', 'brand', 'closed'))) {
			$data[$column] = $value;
			$this->_goods_mod->edit($id, $data);
			if (!$this->_goods_mod->has_error()) {
				echo ecm_json_encode(true);
			}
		} else {
			return;
		}
		return;
	}

	/* 删除商品 */
	function drop() {
		if (!IS_POST) {
			$this->display('goods.batch.html');
		} else {
			$id = isset($_POST['id']) ? trim($_POST['id']) : '';
			if (!$id) {
				$this->show_warning('Hacking Attempt');
				return;
			}
			$ids = explode(',', $id);

			// notify store owner
			$ms = &ms();
			$goods_list = $this->_goods_mod->find(array(
				"conditions" => $ids,
				"fields" => "goods_name, store_id",
			));
			foreach ($goods_list as $goods) {
				//$content = sprintf(LANG::get('toseller_goods_droped_notify'), );
				$content = get_msg('toseller_goods_droped_notify', array('reason' => trim($_POST['drop_reason']),
					'goods_name' => addslashes($goods['goods_name'])));
				$ms->pm->send(MSG_SYSTEM, $goods['store_id'], '', $content);
			}

			// drop
			$this->_goods_mod->drop_data($ids);
			$this->_goods_mod->drop($ids);
			$ret_page = isset($_GET['ret_page']) ? intval($_GET['ret_page']) : 1;
			$this->show_message('drop_ok',
				'back_list', 'index.php?app=goods&page=' . $ret_page);
		}
	}
}

?>
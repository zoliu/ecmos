<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
define('THUMB_WIDTH', 300);
define('THUMB_HEIGHT', 300);
define('THUMB_QUALITY', 85);
import('image.func');

/* 店铺分类控制器 */
class My_goodsApp extends BackendApp {
	var $_my_goods_mod;
	var $_goods_mod;
	var $_spec_mod;
	var $_image_mod;
	var $_uploadedfile_mod;
	var $_store_id;
	var $_brand_mod;
	var $_last_update_id;

	function __construct() {
		$this->My_goodsApp();
	}

	function My_goodsApp() {
		parent::__construct();
		$this->_my_goods_mod = &m('goods');
		$this->_store_id = intval($this->visitor->get('user_id'));
		$this->_goods_mod = &bm('goods', array('_store_id' => $this->_store_id));
		$this->_spec_mod = &m('goodsspec');
		$this->_image_mod = &m('goodsimage');
		$this->_uploadedfile_mod = &m('uploadedfile');
		$this->_brand_mod = &m('brand');
		$this->assign('mgcategories', $this->_get_mgcategory_options(0)); // 商城分类第一级

	}
	/* 取得商城商品分类，指定parent_id */
	function _get_mgcategory_options($parent_id = 0) {
		$res = array();
		$mod = &bm('gcategory', array('_store_id' => 0));
		$gcategories = $mod->get_list($parent_id, true);
		foreach ($gcategories as $gcategory) {
			$res[$gcategory['cate_id']] = $gcategory['cate_name'];
		}
		return $res;
	}

	function _check_store_where() {
		$conditions = '';
		if (trim($_GET['store_name'])) {
			$str1 = "LIKE '%" . trim($_GET['store_name']) . "%'";
			$conditions .= " AND (store_name {$str1} )";
		} else {
			return " and store_id=" . $this->_store_id;
		}
		//360cd.cn
		$store_model = &m('store');
		$where = " 1=1 " . $conditions;
		$store_data = $store_model->find($where);
		if (!$store_data) {
			//此处填写数据不存在内容
			return " and store_id=" . $this->_store_id;
		}
		$ids = array();
		foreach ($store_data as $key => $value) {
			$ids[] = $value['store_id'];
		}
		$where = ' and store_id in (' . implode(',', $ids) . ')';
		return $where;
		//360cd.cn
	}
	/* 管理 */
	function index() {
		$conditions = '';
		$conditions .= $this->_get_query_conditions(array(
			array(
				'field' => 'goods_name', //可搜索字段title
				'equal' => 'LIKE', //等价关系,可以是LIKE, =, <, >, <>
				'assoc' => 'AND', //关系类型,可以是AND, OR
				'name' => 'goods_name', //GET的值的访问键名
				'type' => 'string', //GET的值的类型
			),
		));
		if (trim($_GET['keyword'])) {
			$str = "LIKE '%" . trim($_GET['keyword']) . "%'";
			$conditions .= " AND (goods_name {$str} OR brand {$str} OR cate_name {$str})";
		}

		$cate_id = empty($_GET['cate_id']) ? 0 : intval($_GET['cate_id']);
		if ($cate_id > 0) {
			$cate_mod = &bm('gcategory');
			$cate_ids = $cate_mod->get_descendant_ids($cate_id);
			$conditions .= " AND cate_id" . db_create_in($cate_ids);
		}

		$page = $this->_get_page(10); //获取分页信息
		$conditions .= $this->_check_store_where();
		//获取统计数据
		$my_goods_list = $this->_my_goods_mod->find(array(
			'conditions' => '1=1 ' . $conditions,
			'limit' => $page['limit'],
			'join' => 'has_default_spec',
			'count' => true, //允许统计
		));
		foreach ($my_goods_list as $key => $goods) {
			$goods['default_image'] || $my_goods_list[$key]['default_image'] = '/' . Conf::get('default_goods_image');
		}
		$page['item_count'] = $this->_my_goods_mod->getCount();
		$this->_format_page($page);
		$this->assign('page_info', $page);
		$this->assign('my_goods_list', $my_goods_list);
		$cate_mod = &bm('gcategory', array('_store_id' => 0));
		$this->assign('gcategories', $cate_mod->get_options(0, true));

		//引入jquery表单插件
		$this->import_resource(array(
			'script' => 'jquery.plugins/jquery.validate.js,mlselection.js,jqtreetable.js,inline_edit.js,jquery.ui/jquery.ui.js,jquery.ui/i18n/' . i18n_code() . '.js',
			'style' => 'res:style/jqtreetable.css,jquery.ui/themes/ui-lightness/jquery.ui.css'));

		$this->display('my_goods.index.html');
	}

	/* 新增 */
	function add() {
		if (!IS_POST) {

			//编辑器功能

			/* 添加传给iframe空的id,belong*/
			$this->assign("id", 0);
			$this->assign("belong", BELONG_GOODS);

			/* 取得游离状的图片 */
			$goods_images = array();
			$desc_images = array();
			$uploadfiles = $this->_uploadedfile_mod->find(array(
				'join' => 'belongs_to_goodsimage',
				'conditions' => "belong=" . BELONG_GOODS . " AND item_id=0 AND store_id=" . $this->_store_id,
				'order' => 'add_time ASC',
			));
			$this->assign('goods_images', $goods_images);
			$this->assign('all_images', $uploadfiles);

			$template_name = $this->_get_template_name();
			$style_name = $this->_get_style_name();

			$this->assign('build_editor_description', $this->_build_editor(array(
				'name' => 'description',
				'content_css' => SITE_URL . "/themes/mall/" . $template_name . "/styles/" . $style_name . "/css/ecmall.css",
			)));
			$this->assign('build_upload', $this->_build_upload(
				array(
					'obj' => 'GOODS_SWFU',
					'belong' => BELONG_GOODS,
					'if_multirow' => 1,
					'upload_url' => 'index.php?app=swfupload2&instance=desc_image',
					'item_id' => 0,
				)
			)); // 构建swfupload上传组件

			$this->import_resource(array(
				'script' => 'jquery.plugins/jquery.validate.js,change_upload.js,mlselection.js,jqtreetable.js,inline_edit.js,jquery.ui/jquery.ui.js,jquery.ui/i18n/' . i18n_code() . '.js',
				'style' => 'res:style/jqtreetable.css,jquery.ui/themes/ui-lightness/jquery.ui.css'));
			/* 参数 */
			$this->display('my_goods.form.html');
		} else {
			$data = array();
			$data['goods_name'] = trim($_POST['goods_name']);
			$data['description'] = trim($_POST['description']);
			$data['price'] = trim($_POST['price']);
			$data['tags'] = trim($_POST['tags']);
			$data['store_id'] = $this->_store_id;
			$data['cate_id'] = trim($_POST['cate_id']);
			$data['cate_name'] = trim($_POST['cate_name']);
			$data['spec_name_1'] = trim($_POST['spec_name_1']);
			$data['spec_name_2'] = trim($_POST['spec_name_2']);
			$data['brand'] = trim($_POST['brand']);
			$data = $this->_check_spec($data);

			/* 保存 */
			$id = $this->_my_goods_mod->add($data);
			if (!$id) {
				$this->show_warning($this->_my_goods_mod->get_error());
				return;
			}
			$this->save_pic($id);

			$this->save_spec($id);

			$this->show_message('add_ok',
				'back_list', 'index.php?app=my_goods',
				'continue_add', 'index.php?app=my_goods&amp;act=add&amp;'
			);
		}
	}

	function _check_spec($data) {
		if ($_POST['open_spec'] == '') {
			$data['spec_name_1'] = '';
			$data['spec_name_2'] = '';
			return $data;
		}
		$spec_qty = 0;
		if (empty($data['spec_name_1'])) {
			$data['spec_name_1'] = $data['spec_name_2'];
			$data['spec_name_2'] = '';

		}
		!empty($data['spec_name_1']) ? $spec_qty++ : null;
		!empty($data['spec_name_2']) ? $spec_qty++ : null;
		$data['spec_qty'] = $spec_qty;
		return $data;
	}

	/* 编辑 */
	function edit() {
		$id = empty($_GET['id']) ? 0 : intval($_GET['id']);
		if (!IS_POST) {
			/* 是否存在 */
			$goods = $this->_my_goods_mod->get(array(
				'conditions' => "goods_id = '$id'",
			));
			if (!$goods) {
				$this->show_warning('my_goods_empty');
				return;
			}
			if ($goods) {
				/* 商品规格 */
				$spec_mod = &m('goodsspec');
				$specs = $this->_spec_mod->find(array(
					'conditions' => "goods_id = '$id'",
					'order' => 'spec_id',
				));
				$goods['_specs'][] = $specs[$goods['default_spec']];
				unset($specs[$goods['default_spec']]);
				$goods['_specs'] = array_merge($goods['_specs'], array_values($specs));
				/* 商品图片 */
				$image_mod = &m('goodsimage');
				$goods['_images'] = array_values($this->_image_mod->find(array(
					'conditions' => "goods_id = '$id'",
					'order' => 'sort_order',
				)));

				/* 统计情况 */
				$stat_mod = &m('goodsstatistics');
				$goods = array_merge($goods, $stat_mod->get_info($id));
			}

			$this->assign('my_goods', $goods);
			//360cd.cn
			/* 添加传给iframe空的id,belong*/
			$this->assign("id", 0);
			$this->assign("belong", BELONG_GOODS);

			/* 取得游离状的图片 */
			$goods_images = array();
			$desc_images = array();
			$uploadfiles = $this->_uploadedfile_mod->find(array(
				'join' => 'belongs_to_goodsimage',
				'conditions' => "belong=" . BELONG_GOODS . " AND item_id in(" . $id . ",0) AND store_id=" . $this->_store_id,
				'order' => 'add_time ASC',
			));

			foreach ($uploadfiles as $key => $uploadfile) {
				if ($uploadfile['goods_id'] == null) {
					$desc_images[$key] = $uploadfile;
				} else {
					$goods_images[$key] = $uploadfile;
				}
			}

			$this->assign('goods_images', $goods_images);
			$this->assign('all_images', $uploadfiles);

			$template_name = $this->_get_template_name();
			$style_name = $this->_get_style_name();

			$this->assign('build_editor_description', $this->_build_editor(array(
				'name' => 'description',
				'content_css' => SITE_URL . "/themes/mall/" . $template_name . "/styles/" . $style_name . "/css/ecmall.css",
			)));
			$this->assign('build_upload', $this->_build_upload(
				array(
					'obj' => 'GOODS_SWFU',
					'belong' => BELONG_GOODS,
					'if_multirow' => 1,
					'upload_url' => 'index.php?app=swfupload2&instance=desc_image',
					'item_id' => 0,
				)
			)); // 构建swfupload上传组件
			//360cd.cn
			$this->import_resource(array(
				'script' => 'jquery.plugins/jquery.validate.js,change_upload.js,mlselection.js,jqtreetable.js,inline_edit.js,jquery.ui/jquery.ui.js,jquery.ui/i18n/' . i18n_code() . '.js',
				'style' => 'res:style/jqtreetable.css,jquery.ui/themes/ui-lightness/jquery.ui.css'));

			//编辑器功能

			$this->assign('goods_specs', $this->_spec_mod->find(' goods_id=' . $id));

			$this->display('my_goods.form.html');
		} else {
			$data = array();
			$data['goods_name'] = trim($_POST['goods_name']);
			$data['description'] = trim($_POST['description']);
			$data['price'] = trim($_POST['price']);
			$data['tags'] = trim($_POST['tags']);
			$data['store_id'] = $this->_store_id;
			$data['cate_id'] = trim($_POST['cate_id']);
			$data['cate_name'] = trim($_POST['cate_name']);
			$data['spec_name_1'] = trim($_POST['spec_name_1']);
			$data['spec_name_2'] = trim($_POST['spec_name_2']);
			$data['brand'] = trim($_POST['brand']);
			$data = $this->_check_spec($data);

			/* 保存 */
			$rows = $this->_my_goods_mod->edit($id, $data);
			if ($this->_my_goods_mod->has_error()) {
				$this->show_warning($this->_my_goods_mod->get_error());
				return;
			}
			$this->save_pic($id);

			$this->save_spec($id);

			$this->show_message('edit_ok',
				'back_list', 'index.php?app=my_goods',
				'edit_again', 'index.php?app=my_goods&amp;act=edit&amp;id=' . $id
			);
		}
	}

	function save_spec($goods_id) {
		//var_dump($_POST);exit;
		if ($_POST['open_spec'] == '') {

			$data = array(
				'price' => $_POST['price'],
				'stock' => $_POST['stock'],
				'sku' => $_POST['sku'],
				'goods_id' => $goods_id,
			);
			$spec = intval($_POST['default_spec_id']);

			if ($spec) {
				$this->_spec_mod->edit($spec, $data);
			} else {
				$this->_spec_mod->drop('goods_id=' . $goods_id);
				$spec_id = $this->_spec_mod->add($data);
				$this->_goods_mod->edit($goods_id, array('default_spec' => $spec_id));
			}
			return;
		}
		$spec_ids = $this->_spec_mod->find('goods_id=' . $goods_id);
		if ($spec_ids) {
			$spec_ids1 = '';
			foreach ($spec_ids as $k => $v) {
				$spec_ids1 = $spec_ids1 == '' ? $v['spec_id'] : ',' . $v['spec_id'];
			}
			$spec_exists = explode(',', $spec_ids1);

		}

		$spec_id = isset($_POST['spec_id']) ? $_POST['spec_id'] : null;
		if (!$spec_id) {
			return;
		}
		$default_spec_arr = '';
		if (is_array($spec_id)) {
			foreach ($spec_id as $index => $spec) {
				$data = array(
					'spec_1' => $_POST['goods_spec1'][$index],
					'spec_2' => $_POST['goods_spec2'][$index],
					'price' => $_POST['goods_price'][$index],
					'stock' => $_POST['goods_stock'][$index],
					'sku' => $_POST['goods_sk'][$index],
					'goods_id' => $goods_id,
				);
				if ($this->_check_spec_id_exists($spec, $spec_exists)) {
					$this->_spec_mod->edit($spec, $data);
				} else {
					$spec = $this->_spec_mod->add($data);
				}
				$default_spec_arr = array('default_spec' => $spec, 'price' => $data['price']);
			}
		}
		$this->_goods_mod->edit($goods_id, $default_spec_arr);
	}

	function _check_spec_id_exists($spec_id, $spec_exists) {
		if (empty($spec_id)) {
			return 0;
		}
		if ($spec_exists && is_array($spec_exists)) {
			if (in_array($spec_id, $spec_exists)) {
				return 1;
			}
		}
		return 0;
	}

	function save_pic($goods_id) {
		$files = isset($_POST['goods_file_ids']) ? $_POST['goods_file_ids'] : null;
		if (!is_array($files)) {
			return;
		}

		$file_infos = $this->_uploadedfile_mod->find(db_create_in($files, 'file_id'));

		if ($file_infos) {
			foreach ($file_infos as $k => $v) {
				$this->_add_goods_image($v['file_id'], $v['file_path'], $goods_id);
			}
		}
		$this->_uploadedfile_mod->edit('item_id=' . $goods_id, array('item_id' => 0));
		$this->_uploadedfile_mod->edit(db_create_in($files, 'file_id'), array('item_id' => $goods_id));

	}

	function drop_goods_images() {
		$file_id = isset($_GET['file_id']) ? intval($_GET['file_id']) : 0;
		if ($file_id && $this->_image_mod->drop(' file_id=' . $file_id)) {
			$this->json_result('drop_ok');
			return;
		} else {
			$this->json_error('drop_error');
			return;
		}
	}

	/* 异步删除附件 */
	function drop_uploadedfile() {
		$file_id = isset($_GET['file_id']) ? intval($_GET['file_id']) : 0;
		if ($file_id && $this->_uploadedfile_mod->drop($file_id)) {
			$this->json_result('drop_ok');
			return;
		} else {
			$this->json_error('drop_error');
			return;
		}
	}

	function _add_goods_image($file_id, $file_path, $goods_id) {

		$item_info = $this->_image_mod->get(' goods_id=' . $goods_id . ' and file_id=' . $file_id);

		if ($item_info) {
			return;
		}
		/* 生成缩略图 */
		$thumbnail = dirname($file_path) . '/small_' . basename($file_path);
		make_thumb(ROOT_PATH . '/' . $file_path, ROOT_PATH . '/' . $thumbnail, THUMB_WIDTH, THUMB_HEIGHT, THUMB_QUALITY);

		/* 更新商品相册 */
		$data = array(
			'goods_id' => $goods_id,
			'image_url' => $file_path,
			'thumbnail' => $thumbnail,
			'sort_order' => 255,
			'file_id' => $file_id,
		);
		if (!$image_id = $this->_image_mod->add($data)) {

			return false;
		}
		$this->_goods_mod->edit($goods_id, array('default_image' => $thumbnail));

	}

	/* 删除 */
	function drop() {
		$id = isset($_GET['id']) ? trim($_GET['id']) : '';
		if (!$id) {
			$this->show_warning('no_my_goods_to_drop');
			return;
		}
		$ids = explode(',', $id);
		if (!$this->_my_goods_mod->drop($ids)) {
			$this->show_warning($this->_my_goods_mod->get_error());
			return;
		}
		$this->show_message('drop_ok');
	}
	/* 更新排序 */
	function update_order() {
		if (empty($_GET['id'])) {
			$this->show_warning('Hacking Attempt');
			return;
		}
		$ids = explode(',', $_GET['id']);
		$sort_orders = explode(',', $_GET['sort_order']);
		foreach ($ids as $key => $id) {
			$this->_my_goods_mod->edit($id, array('sort_order' => $sort_orders[$key]));
		}
		$this->show_message('update_order_ok');
	}
	//异步修改数据
	function ajax_col() {
		$id = empty($_GET['id']) ? 0 : intval($_GET['id']);
		$column = empty($_GET['column']) ? '' : trim($_GET['column']);
		$value = isset($_GET['value']) ? trim($_GET['value']) : '';
		$data = array();
		if (in_array($column, array('recommended', 'sort_order'))) {
			$data[$column] = $value;
			$this->_my_goods_mod->edit($id, $data);
			if (!$this->_my_goods_mod->has_error()) {
				echo ecm_json_encode(true);
			}
		} else {
			return;
		}
		return;
	}

	function assign_stock() {
		$id = isset($_GET['id']) ? trim($_GET['id']) : '';
		if (!$id) {
			$this->show_warning('no_my_goods_to_drop');
			return;
		}
		$ids = explode(',', $id);
		$ids = implode(',', $ids);
		$zlgoods = SL('store_goods');
		if (!IS_POST) {

			$conditions = '';
			if ($ids) {
				$conditions .= " and g.goods_id in(" . $ids . ") ";
			}
			$conditions .= " and store_id=" . STOCK_STORE;
			$where = array(
				'conditions' => ' 1=1 ' . $conditions,
				'join' => 'has_default_spec',
				'limit' => '20',
			);
			$goods_list = $zlgoods->get_all($where);
			$this->assign('goods_list', $goods_list);
			$this->display('my_goods.download.html');
		} else {
			$conditions = '';
			$ids = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : 0;
			$store_id = isset($_POST['qstore_id']) && !empty($_POST['qstore_id']) ? $_POST['qstore_id'] : 0;
			if (!$store_id) {
				$this->show_warning('need_store');
				return;
			}
			if ($ids) {
				$conditions .= " and g.goods_id in(" . $ids . ") ";
			}
			$conditions .= " and store_id=" . STOCK_STORE;
			$where = array(
				'conditions' => ' 1=1 ' . $conditions,
				'limit' => '20',
			);
			$goods_list = $zlgoods->get_all($where);

			$spec_lists = $_POST['spec'];
			if (is_array($spec_lists) && count($spec_lists) > 0) {
				foreach ($spec_lists as $k => $v) {
					foreach ($v as $k1 => $v1) {
						$goods_list[$k]['_specs'][$k1]['stock'] = intval($v1);
					}
				}
			}

			if ($goods_list) {
				foreach ($goods_list as $key => $v) {
					$zlgoods->update_item($v, $store_id);
				}
			}
			$this->show_message('download_ok',
				'back_list', 'index.php?app=my_goods&');

		}

	}

	function ajax_query_store() {
		$keyword = isset($_GET['keyword']) && !empty($_GET['keyword']) ? $_GET['keyword'] : null;
		if (!$keyword) {
			$this->json_error('关键词不为空');
			return;
		}

		//360cd.cn
		$store_model = &m('store');
		$where = " store_name ='" . $keyword . "'";
		$store_data = $store_model->get($where);
		if (!$store_data) {
			//此处填写数据不存在内容
			$this->json_error('无数据');
			return;
		}
		$this->json_result($store_data);
		//360cd.cn
	}
}
?>
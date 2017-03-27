<?php
class Store_goods {
	function get_all($where) {

		//360cd.cn
		$goods_model = &m('goods');
		$goods_data = $goods_model->find($where);
		if (!$goods_data) {
			//此处填写数据不存在内容
			return;
		}
		foreach ($goods_data as $key => $data) {
			$goods_data[$key]['_specs'] = $this->_get_spec_by_goods_id($key);
			$goods_data[$key]['_images'] = $this->_get_images_by_goods_id($key);
			$goods_data[$key]['_files'] = $this->_get_file_by_goods_id($key);
		}
		return $goods_data;
	}

	function _get_images_by_goods_id($goods_id) {
		$goodsimage_model = &m('goodsimage');
		$where = " goods_id=" . $goods_id;
		$goodsimage_data = $goodsimage_model->find($where);
		if (!$goodsimage_data) {
			//此处填写数据不存在内容
			return;
		}

		return $goodsimage_data;
	}

	function _get_spec_by_goods_id($goods_id) {
		//360cd.cn
		$goodsspec_model = &m('goodsspec');
		$where = " goods_id=" . $goods_id;
		$goodsspec_data = $goodsspec_model->find($where);
		if (!$goodsspec_data) {
			//此处填写数据不存在内容
			return;
		}
		return $goodsspec_data;
		//360cd.cn
	}

	function _get_file_by_goods_id($goods_id) {
		//360cd.cn
		$uploadedfile_model = &m('uploadedfile');
		$where = " item_id=" . $goods_id . " and belong=" . BELONG_GOODS;
		$uploadfile_data = $uploadedfile_model->find($where);
		if (!$uploadfile_data) {
			//此处填写数据不存在内容
			return;
		}
		//360cd.cn
		return $uploadfile_data;
	}

	function update_item($goods_data, $store_id) {
		$goods_id = $this->update_goods($goods_data, $store_id);
		$spec_data = $goods_data['_specs'];
		$spec_list = $this->update_spec($goods_data['_specs'], $goods_id);
		$this->update_default_spec($goods_id, $goods_data['default_spec'], $spec_list);
		$image_list = $this->update_images($goods_data['_images'], $goods_id);
		$file_list = $this->update_file($goods_data['_files'], $store_id, $goods_id);
		$this->updata_image_file($image_list, $file_list);
	}

	function update_default_spec($goods_id, $default_spec_id, $spec_list) {
		if (isset($spec_list[$default_spec_id]) && !empty($spec_list[$default_spec_id])) {
			$default_spec = $spec_list[$default_spec_id];
			//360cd.cn
			$goods_model = &m('goods');
			$goods_model->edit($goods_id, array('default_spec' => $default_spec));
		}
	}

	function updata_image_file($image_list, $file_list) {
		//360cd.cn
		$goodsimage_model = &m('goodsimage');
		if (is_array($image_list) && count($image_list) > 0) {
			foreach ($image_list as $k => $v) {
				if (isset($file_list[$v['file_id']]) && !empty($file_list[$v['file_id']])) {
					$file_id = $file_list[$v['file_id']];
					$goodsimage_model->edit($v['image_id'], array('file_id' => $file_id));
				}
			}
		}
	}

	//$goods_data['goods']
	//$goods_data['_spec']
	//$goods_data['_images']
	function update_spec($spec_data, $goods_id) {
		$spec_arr = array();
		$goodsspec_model = &m('goodsspec');
		if (is_array($spec_data) && count($spec_data) > 0) {
			//360cd.cn
			foreach ($spec_data as $k => $spec) {
				$in_spec_id = $spec['spec_id'];
				$spec = $this->_unset_in_array($spec, array('spec_id', 'in_spec_id'));
				$spec['goods_id'] = $goods_id;
				$spec['in_spec_id'] = $in_spec_id;
				$where = " in_spec_id=" . $in_spec_id . " and goods_id=" . $goods_id;

				$goodsspec_data = $goodsspec_model->get($where);
				if ($goodsspec_data) {
					$spec_id = $goodsspec_data['spec_id'];
					$goodsspec_model->edit($spec_id, $spec);
				} else {
					$spec_id = $goodsspec_model->add($spec);
				}
				$spec_arr[$in_spec_id] = $spec_id;

			}
		}
		return $spec_arr;

		//处理默认规格同步
		//
	}

	function update_goods($data, $store_id) {
		$goods_id = $data['goods_id'];
		$data = $this->_unset_in_array($data, array('goods_id', 'in_goods_id'));
		//360cd.cn
		$goods_model = &m('goods');
		$data['store_id'] = $store_id;
		$where = " in_goods_id =" . $goods_id . " and store_id=" . $store_id;
		$goods_data = $goods_model->get($where);
		if (!$goods_data) {
			$data['in_goods_id'] = $goods_id;
			$goods_id = $goods_model->add($data);
			//此处填写数据不存在内容
		} else {
			$goods_id = $goods_data['goods_id'];
			$goods_model->edit($goods_id, $data);
		}

		return $goods_id;
		//360cd.cn
	}

	function update_images($img_data, $goods_id) {
		$img_arr = array();
		if (is_array($img_data) && count($img_data) > 0) {
			//360cd.cn
			$goodsimage_model = &m('goodsimage');
			foreach ($img_data as $k => $img) {
				$in_image_id = $img['image_id'];
				$img = $this->_unset_in_array($img, array('image_id', 'in_image_id'));
				$img['goods_id'] = $goods_id;
				$img['in_image_id'] = $in_image_id;
				$where = " in_image_id=" . $in_image_id . " and goods_id=" . $goods_id;
				$goodsimage_data = $goodsimage_model->get($where);
				if ($goodsimage_data) {
					$image_id = $goodsimage_data['image_id'];
					$goodsimage_model->edit($image_id, $img);
				} else {
					$image_id = $goodsimage_model->add($img);
				}
				$img_arr['in_image_id'] = array('image_id' => $image_id, 'file_id' => $img['file_id']);

			}

		}
		return $img_arr;
	}

	function update_file($file_data, $store_id, $goods_id) {
		//360cd.cn
		$uploadedfile_model = &m('uploadedfile');
		$fild_arr = array();
		if (is_array($file_data) && count($file_data) > 0) {
			foreach ($file_data as $k => $v) {
				$in_file_id = $v['file_id'];
				$v = $this->_unset_in_array($v, array('file_id', 'in_file_id'));
				$v['item_id'] = $goods_id;
				$v['store_id'] = $store_id;
				$where = " in_file_id=" . $in_file_id . " and item_id=" . $goods_id . ' and store_id=' . $store_id;
				//360cd.cn
				$uploadedfile_data = $uploadedfile_model->get($where);
				if ($uploadedfile_data) {
					$file_id = $uploadedfile_data['file_id'];
					$uploadedfile_model->edit($file_id, $v);

				} else {
					$file_id = $uploadedfile_model->add($v);
				}
				$file_arr[$in_file_id] = $file_id;
			}
		}
		return $file_arr;
	}

	function _unset_in_array($data, $rules = array()) {
		if (is_array($rules) && count($rules)) {
			foreach ($rules as $k => $v) {
				unset($data[$v]);
			}
		}
		return $data;
	}

	function check_is_stock($goods_id) {
		//360cd.cn
		$goods_model = &m('goods');
		$where = " in_goods_id <>'' and goods_id=" . $goods_id;
		$goods_data = $goods_model->get($where);
		if (!$goods_data) {
			//此处填写数据不存在内容
			return;
		}
		return 1;
		//360cd.cn
	}

}

?>
<?php

class mll2015_floor7Widget extends BaseWidget {

    var $_name = 'mll2015_floor7';
    var $_ttl = 86400;
    var $_num = 4;

    function _get_data() {
        $cache_server = & cache_server();
        $key = $this->_get_cache_id();
        $data = $cache_server->get($key);
        
        if ($data === false) {
            $recom_mod = & m('recommend');
            $data = array(
                'more' => explode(' ', $this->options['more']),
                 'model_subtitle' => $this->options['model_subtitle'],
                'model_subtitle1' => $this->options['model_subtitle1'],
                'model_id' => mt_rand(),
                'model_name' => $this->options['model_name'],
                'model_name2' => $this->options['model_name2'],
                'model_name3' => $this->options['model_name3'],
                'model_name4' => $this->options['model_name4'],
                //
                'ad1_image_url' => $this->options['ad1_image_url'],
                'ad1_link_url' => $this->options['ad1_link_url'],
                'ad2_link_url' => $this->options['ad2_link_url'],
                'ad0_link_url' => $this->options['ad0_link_url'],
                //
                'ad1_title' => $this->options['ad1_title'],
                'ad1_price' => $this->options['ad1_price'],
                'ad1_toprice' => $this->options['ad1_toprice'],
                'ad1_dis' => $this->options['ad1_dis'],
                //
                //产品调用
                'goods_list_1' => $recom_mod->get_recommended_goods($this->options['img_recom_id_1'], $this->_num, true, $this->options['img_cate_id_1']),
            );
            $cache_server->set($key, $data, $this->_ttl);   
        }
        return $data;
    }

    function parse_config($input) {
        $images = $this->_upload_image();
        if ($images) {
            foreach ($images as $key => $image) {
                $input['ad' . $key . '_image_url'] = $image;
            }
        }

        return $input;
    }

    function _upload_image() {
        import('uploader.lib');
        $images = array();
        for ($i = 1; $i <= 8; $i++) {
            $file = $_FILES['ad' . $i . '_image_file'];
            if ($file['error'] == UPLOAD_ERR_OK) {
                $uploader = new Uploader();
                $uploader->allowed_type(IMAGE_FILE_TYPE);
                $uploader->addFile($file);
                $uploader->root_dir(ROOT_PATH);
                $images[$i] = $uploader->save('data/files/mall/template', $uploader->random_filename());
            }
        }

        return $images;
    }

    function get_config_datasrc() {
        // 取得推荐类型
        $this->assign('recommends', $this->_get_recommends());
        // 取得一级商品分类
        $this->assign('gcategories', $this->_get_gcategory_options());
    }

}

?>
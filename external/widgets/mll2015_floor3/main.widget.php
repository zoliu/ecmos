<?php

class mll2015_floor3Widget extends BaseWidget {

    var $_name = 'mll2015_floor3';
    var $_ttl = 86400;
    var $_num = 4;

    function _get_data() {
        $recom_mod = & m('recommend');
        $data = array(
            'model_id' => mt_rand(),
            'keywords' => explode(' ', $this->options['keyword']),
            'model_name' => $this->options['model_name'],
            'model_name' => $this->options['model_name1'],
            'model_subtitle' => $this->options['model_subtitle'],
            //
            'ad1_image_url' => $this->options['ad1_image_url'],
            'ad1_link_url' => $this->options['ad1_link_url'],
            //
            'ad2_image_url' => $this->options['ad2_image_url'],
            'ad2_link_url' => $this->options['ad2_link_url'],
            //
            'ad3_image_url' => $this->options['ad3_image_url'],
            'ad3_link_url' => $this->options['ad3_link_url'],
            //
            'ad4_image_url' => $this->options['ad4_image_url'],
            'ad4_link_url' => $this->options['ad4_link_url'],
            //产品调用
            'goods_list_1' => $recom_mod->get_recommended_goods($this->options['img_recom_id_1'], $this->_num, true, $this->options['img_cate_id_1']),
        );
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
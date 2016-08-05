<?php

class mll2015_floor10Widget extends BaseWidget {

    var $_name = 'mll2015_floor10';
    var $_ttl = 86400;
    var $_num = 3;

    function _get_data() {
        $cache_server = & cache_server();
        $key = $this->_get_cache_id();
        $data = $cache_server->get($key);
        
        if ($data === false) {
            $recom_mod = & m('recommend');
            $data = array(
                'model_id' => mt_rand(),
                'more' => explode(' ', $this->options['more']),
                //楼层样式风格
               'floor_id' => empty($this->options['floor_id'])?"floor-1F":$this->options['floor_id'],
                'model_name' => $this->options['model_name'],
                'sub_title' => $this->options['sub_title'],
                'keywords' => explode(' ', $this->options['keyword']),
                //
                'ad1_image_url' => $this->options['ad1_image_url'],
                'ad1_link_url' => $this->options['ad1_link_url'],
                
                'ad2_image_url' => $this->options['ad2_image_url'],
                'ad2_link_url' => $this->options['ad2_link_url'],
                
                'ad3_image_url' => $this->options['ad3_image_url'],
                'ad3_link_url' => $this->options['ad3_link_url'],
                
                'ad4_image_url' => $this->options['ad4_image_url'],
                'ad4_link_url' => $this->options['ad4_link_url'],
                
                'ad5_image_url' => $this->options['ad5_image_url'],
                'ad5_link_url' => $this->options['ad5_link_url'],
                
                'ad6_image_url' => $this->options['ad6_image_url'],
                'ad6_link_url' => $this->options['ad6_link_url'],
                
                //产品调用
                'goods_list_1' => $recom_mod->get_recommended_goods($this->options['img_recom_id_1'], 4, true, $this->options['img_cate_id_1']),
                'goods_list_2' => $recom_mod->get_recommended_goods($this->options['img_recom_id_2'], 3, true, $this->options['img_cate_id_2']),
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
    function _get_floors()
    {
        return array(
            'floor-1F'=>'楼层样式1',
            'floor-2F'=>'楼层样式2',
            'floor-3F'=>'楼层样式3',
            'floor-4F'=>'楼层样式4',
            'floor-5F'=>'楼层样式5',
            'floor-6F'=>'楼层样式6',
        );
    }

    function _upload_image() {
        import('uploader.lib');
        $images = array();
        for ($i = 1; $i <= 6; $i++) {
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
         //取得可设置楼层样式
        $this->assign('floors', $this->_get_floors());
    }

}

?>
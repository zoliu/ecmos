<?php

class mll2015_floor2Widget extends BaseWidget {

    var $_name = 'mll2015_floor2';
    var $_ttl = 86400;
    var $_num = 4;

    function _get_data() {
        $data = array(
            'model_id' => mt_rand(),
            'model_name' => $this->options['model_name'],
            'model_name_url' => $this->options['model_name_url'],
            'cates' => $this->get_cate($this->options['cate_id']),
            //楼层样式风格
            'floor_id' => empty($this->options['floor_id'])?"floor-1F":$this->options['floor_id'],
            
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
            //
            'ad5_image_url' => $this->options['ad5_image_url'],
            'ad5_link_url' => $this->options['ad5_link_url'],
            //
            'ad6_image_url' => $this->options['ad6_image_url'],
            'ad6_link_url' => $this->options['ad6_link_url'],
            //
            'ad7_image_url' => $this->options['ad7_image_url'],
            'ad7_link_url' => $this->options['ad7_link_url'],
            //
            'ad8_image_url' => $this->options['ad8_image_url'],
            'ad8_link_url' => $this->options['ad8_link_url'],
            //
            'ad9_image_url' => $this->options['ad9_image_url'],
            'ad9_link_url' => $this->options['ad9_link_url'],
            //
            'ad10_image_url' => $this->options['ad10_image_url'],
            'ad10_link_url' => $this->options['ad10_link_url'],
            //
            'ad11_image_url' => $this->options['ad11_image_url'],
            'ad11_link_url' => $this->options['ad11_link_url'],
            //
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
    //获取设定分类下的子分类
    function get_cate($cate_id) {
        $mod_gcage = &bm('gcategory');
        $cates = $mod_gcage->get_children($cate_id, true);
        return $cates;
    }
    function get_config_datasrc() {
        // 取得一级商品分类
        $this->assign('gcategories', $this->_get_gcategory_options());
        
        //取得可设置楼层样式
        $this->assign('floors', $this->_get_floors());
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
        for ($i = 1; $i <= 11; $i++) {
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


}

?>
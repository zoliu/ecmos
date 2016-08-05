<?php

class mll2015_floor11Widget extends BaseWidget {

    var $_name = 'mll2015_floor11';
    var $_ttl = 86400;
    var $_num = 4;

    function _get_data() {
        
        $data = array(
            'articles1' => $this->_get_article($this->options['cate_id_1']),
            'articles2' => $this->_get_article($this->options['cate_id_2']),
            'model_id' => mt_rand(),
            'model_name' => $this->options['model_name'],
            'model_name1' => $this->options['model_name1'],
            
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
            'ad12_image_url' => $this->options['ad12_image_url'],
            'ad12_link_url' => $this->options['ad12_link_url'],
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
        for ($i = 1; $i <= 12; $i++) {
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

    
    function _get_article($cate_id) {
        if ($cate_id > 0) {
            $acategory_mod = & m('acategory');
            $cate_ids = $acategory_mod->get_descendant($cate_id);
            /* 店铺分类检索条件 */
            $condition_id = implode(',', $cate_ids);
            $condition_id && $condition_id = ' AND cate_id IN(' . $condition_id . ')';
        }
        $article_mod = & m('article');
        $article_list = $article_mod->find(array(
            'conditions' => '1=1 '.$condition_id,
            'order' => 'sort_order desc', 
            'limit' => 6,
        ));
        
        return $article_list;
    }

    function get_config_datasrc() {
        // 取得多级文章分类，去除系统文章
        $this->assign('acategories', $this->_get_acategory_options(2));
    }

    function parse_config1($input) {
        return $input;
     }
    function _get_acategory_options($layer = 0) {
        $acategory_mod = & m('acategory');
        $acategories = $acategory_mod->get_list();
        foreach ($acategories as $key => $val) {
            if ($val['code'] == ACC_SYSTEM) {
                unset($acategories[$key]);
            }
        }
    
        import('tree.lib');
        $tree = new Tree();
        $tree->setTree($acategories, 'cate_id', 'parent_id', 'cate_name');

        return $tree->getOptions($layer);
    }
}

?>
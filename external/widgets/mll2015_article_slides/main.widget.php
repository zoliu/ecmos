<?php

class mll2015_article_slidesWidget extends BaseWidget {

    var $_name = 'mll2015_article_slides';
    var $_ttl = 86400;
    var $_num = 4;

    function _get_data() {
        $cache_server = & cache_server();
        $key = $this->_get_cache_id();
        $data = $cache_server->get($key);
        if ($data === false) {
            $data = array(
                'model_id' => mt_rand(),
                'model_name' => $this->options['model_name'],
                'model_name1' => $this->options['model_name1'],
                'model_name2' => $this->options['model_name2'],
                'model_name3' => $this->options['model_name3'],
                'model_name4' => $this->options['model_name4'],
                'model_name5' => $this->options['model_name5'],
                
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
        for ($i = 1; $i <=5; $i++) {
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
<?php

class mll2015_floor4Widget extends BaseWidget {

    var $_name = 'mll2015_floor4';
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
                'more' => explode(' ', $this->options['more']),
                'model_subtitle' => $this->options['model_subtitle'],
                
                'ad1_link_url' => $this->options['ad0_link_url'],
                //
                'ad1_image_url' => $this->options['ad1_image_url'],
                'ad1_link_url' => $this->options['ad1_link_url'],
                'ad1_title' => $this->options['ad1_title'],
                'ad1_price' => $this->options['ad1_price'],
                //
                'ad2_image_url' => $this->options['ad2_image_url'],
                'ad2_link_url' => $this->options['ad2_link_url'],
                'ad1_title' => $this->options['ad2_title'],
                'ad1_price' => $this->options['ad2_price'],
                //
                'ad3_image_url' => $this->options['ad3_image_url'],
                'ad3_link_url' => $this->options['ad3_link_url'],
                'ad1_title' => $this->options['ad3_title'],
                'ad1_price' => $this->options['ad3_price'],
                //
                'ad4_image_url' => $this->options['ad4_image_url'],
                'ad4_link_url' => $this->options['ad4_link_url'],
                'ad1_title' => $this->options['ad4_title'],
                'ad1_price' => $this->options['ad4_price'],
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
        for ($i = 1; $i <=8; $i++) {
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
<?php

/**
 * 4个图片广告挂件
 *
 * @param   string  $ad_image_url   广告图片地址1-4
 * @param   string  $ad_link_url    广告链接地址1-4
 * @return  array
 */
class Four_image_adsWidget extends BaseWidget
{
    var $_name = 'four_image_ads';

    function _get_data()
    {
        return array(
            'ad1_image_url'  => $this->options['ad1_image_url'],
            'ad1_link_url'   => $this->options['ad1_link_url'],
            'ad2_image_url'  => $this->options['ad2_image_url'],
            'ad2_link_url'   => $this->options['ad2_link_url'],
            'ad3_image_url'  => $this->options['ad3_image_url'],
            'ad3_link_url'   => $this->options['ad3_link_url'],
            'ad4_image_url'  => $this->options['ad4_image_url'],
            'ad4_link_url'   => $this->options['ad4_link_url'],
        );
    }

    function parse_config($input)
    {
        $images = $this->_upload_image();
        if ($images)
        {
            foreach ($images as $key => $image)
            {
                $input['ad' . $key . '_image_url'] = $image;
            }
        }

        return $input;
    }

    function _upload_image()
    {
        import('uploader.lib');
        $images = array();
        for ($i = 1; $i <= 4; $i++)
        {
            $file = $_FILES['ad' . $i . '_image_file'];
            if ($file['error'] == UPLOAD_ERR_OK)
            {
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
<?php

/**
 * 图片广告挂件
 *
 * @param   string  $image_url  图片地址
 * @param   string  $link_url   链接地址
 */
class Jd_image_adsWidget extends BaseWidget
{
    var $_name = 'jd_image_ads';

    function _get_data()
    {
        return array(
            'ad_image_url'  => $this->options['ad_image_url'],
            'ad_link_url'   => $this->options['ad_link_url'],
			'ad_width'      => $this->options['ad_width'],
			'ad_height'     => $this->options['ad_height'],
			'ad_border'     => $this->options['ad_border'],
			'ad_margin'     => $this->options['ad_margin'],
			'ad_background_color'  => $this->options['ad_background_color'],
			'ad_button_close'	=> $this->options['ad_button_close']
        );
    }

    function parse_config($input)
    {
        $image = $this->_upload_image();
        if ($image)
        {
            $input['ad_image_url'] = $image;
        }

        return $input;
    }

    function _upload_image()
    {
        import('uploader.lib');
        $file = $_FILES['ad_image_file'];
        if ($file['error'] == UPLOAD_ERR_OK)
        {
            $uploader = new Uploader();
            $uploader->allowed_type(IMAGE_FILE_TYPE);
            $uploader->addFile($file);
            $uploader->root_dir(ROOT_PATH);
            return $uploader->save('data/files/mall/template', $uploader->random_filename());
        }

        return '';
    }
}

?>
<?php

/**
 *
 * @return  array
 */
class Jd_circleWidget extends BaseWidget
{
    var $_name = 'jd_circle';
    var $_ttl  = 1800;
	var $_num = 15;
	
	function _get_data()
    {
		$data = array(
		   'model_id'=>mt_rand(),
		   'ads'=> $this->options['ads'],
		   'ad1_image_url' => $this->options['ad1_image_url']
		);
		//dump($data);
        return $data;
    }

    function parse_config($input)
    {
        $result = array();
        $num    = isset($input['ad_link_url']) ? count($input['ad_link_url']) : 0;
        if ($num > 0 || isset($input['ad1_image_url']))
        {
            $images = $this->_upload_image($num);
            for ($i = 0; $i < $num ; $i++)
            {
                if (!empty($images[$i]))
                {
                    $input['ad_image_url'][$i] = $images[$i];
                }
    
                if (!empty($input['ad_image_url'][$i]))
                {
                    $result[] = array(
                        'ad_image_url' => $input['ad_image_url'][$i],
                        'ad_link_url'  => $input['ad_link_url'][$i],
                        'ad_title' => $input['ad_title'][$i]
                    );
                }
            }
        }
		!empty($images['ad1']) && $input['ad1_image_url'] = $images['ad1'];
		$input['ads'] = $result;
		unset($input['ad_image_url']);
		unset($input['ad_link_url']);
        return $input;
    }

    function _upload_image($num)
    {
        import('uploader.lib');

        $images = array();
        for ($i = 0; $i < $num; $i++)
        {
            $file = array();
            foreach ($_FILES['ad_image_file'] as $key => $value)
            {
                $file[$key] = $value[$i];
            }

            if ($file['error'] == UPLOAD_ERR_OK)
            {
                $uploader = new Uploader();
                $uploader->allowed_type(IMAGE_FILE_TYPE);
                $uploader->addFile($file);
                $uploader->root_dir(ROOT_PATH);
                $images[$i] = $uploader->save('data/files/mall/template', $uploader->random_filename());
            }
        }
		
		$file1 = $_FILES['ad1_image_file'];
        if (!empty($file1) && $file1['error'] == UPLOAD_ERR_OK)
        {
           $uploader = new Uploader();
           $uploader->allowed_type(IMAGE_FILE_TYPE);
           $uploader->addFile($file1);
           $uploader->root_dir(ROOT_PATH);
           $images['ad1'] = $uploader->save('data/files/mall/template', $uploader->random_filename());
         }

        return $images;
    }
}

?>
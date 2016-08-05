<?php

/**
 * 商品模块挂件
 */
class Jd_tesegouWidget extends BaseWidget
{
    var $_name = 'jd_tesegou';

    function _get_data()
    {
			$rec_brands= Psmb_init()->Jd_widget_get_brand_list($this->options['tag'],10);
			$data=array(
				'model_id'   =>mt_rand(),
				'model_name' =>$this->options['model_name'],
				'rec_brands'=>$rec_brands
			);
			for($i=1;$i<=9;$i++)
			{
				$data['ad'.$i.'_image_url'] = $this->options['ad'.$i.'_image_url'];
				$data['ad'.$i.'_link_url'] = $this->options['ad'.$i.'_link_url'];
			}
			if(!empty($this->options['more']))
			{
				$mores = explode(';',str_replace('；',';',$this->options['more']));
				foreach($mores as $key => $more)
				{
					$temp = explode('|',$more);
					$data['more'][$key] = array('name'=>$temp[0],'link'=>$temp[1]);
				}
			}
			return $data;
			
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
        for ($i = 1; $i <= 9; $i++)
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
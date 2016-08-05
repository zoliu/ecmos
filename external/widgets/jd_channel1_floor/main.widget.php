<?php
class Jd_channel1_floorWidget extends BaseWidget
{
    var $_name = 'jd_channel1_floor';

    function _get_data()
    {
		$ads=array();
		for($i=1;$i<5;$i++)
		{
			$ads[$i]['ad_image_url']=$this->options['ad'.$i.'_image_url'];
			$ads[$i]['ad_title_url']=explode(' ',$this->options['ad'.$i.'_title_url']);
			$ads[$i]['ad_link_url']=$this->options['ad'.$i.'_link_url'];
		}
		$brands=Psmb_init()->Jd_widget_get_brand_list($this->options['tag'],15);
		return array(
			'ads'=>$ads,
			'model_id'  =>mt_rand(),
			'model_name'=>$this->options['model_name'],
			'keywords'=>explode(' ',$this->options['keywords']),
			'ad5_image_url'=>$this->options['ad5_image_url'],
			'ad5_title_url'=>$this->options['ad5_title_url'],
			'ad5_link_url'=>$this->options['ad5_link_url'],
			'ad6_image_url'=>$this->options['ad6_image_url'],
			'ad6_title_url'=>$this->options['ad6_title_url'],
			'ad6_link_url'=>$this->options['ad6_link_url'],
			'brands'      =>array_chunk($brands,5)
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
        for ($i = 1; $i <= 6; $i++)
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
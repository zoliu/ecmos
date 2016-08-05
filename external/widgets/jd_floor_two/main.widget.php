<?php

/**
 * 商品模块挂件
 */
class Jd_floor_twoWidget extends BaseWidget
{
    var $_name = 'jd_floor_two';

    function _get_data()
    {
			$gcategory_mod = &bm('gcategory' , array('store_id = 0'));
			$cate_id=$this->options['cate_id']?$this->options['cate_id']:0;
			$cates=$gcategory_mod->find('parent_id='.$cate_id);
			$data=array(
				'model_id'   =>mt_rand(),
				'model_num' =>$this->options['model_num'],
				'model_name' =>$this->options['model_name'],
				'ad0_image_url' => $this->options['ad0_image_url'],
				'ad0_link_url ' => $this->options['ad0_link_url'],
				'words' => Psmb_init()->Jd_widget_get_words($this->options['words']),
				'cates'=>$cates,
				'ads' => Psmb_init()->Jd_widget_get_ads($this->options,6),
				'slides' => $this->options['slides'],
				'goods_list' => Psmb_init()->Jd_widget_get_tabs_goods($this->options['tabs'],10), 
			);
			$slides_pos = $this->options['slides_pos'] && in_array($this->options['slides_pos'],array(1,2,3,4))?$this->options['slides_pos']:2;
			switch($slides_pos)
			{
				case 1:
					$data['slides_left'] = '0px';
					break;
				case 2:
					$data['slides_left'] = '220px';
					break;
				case 3:
					$data['slides_left'] = '440px';
					break;
				case 4:
					$data['slides_left'] = '660px';
					break;
				default:
					$data['slides_left'] = '220px';
					break;
			}
			return $data;
			
    }
    function get_config_datasrc()
    {
		// 取得推荐类型
        $this->assign('recommends', $this->_get_recommends());
        // 取得一级商品分类
        $this->assign('gcategories', $this->_get_gcategory_options(2));
    }
	
	function parse_config($input)
    {
        $result = array();
        $num    = isset($input['ad_link_url']) ? count($input['ad_link_url']) : 0;
		$images = $this->_upload_image($num);
        if ($num > 0)
        {
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
			unset($images[$i]);
        }
		foreach ($images as $key => $image)
        {
             $input[$key] = $image;
        }
		foreach($input['tab_name'] as $key => $tab_name)
		{
			$tabs[] = array(
				'tab_name' => $tab_name,
				'img_recom_id' => $input['img_recom_id'][$key],
				'img_cate_id' => $input['img_cate_id'][$key],
				'sort_by' => $input['sort_by'][$key],
			);
		}
		$input['tabs'] = $tabs;
		$input['slides'] = $result;
		unset($input['ad_image_url']);
		unset($input['ad_link_url']);
        return $input;
    }
	
	function _upload_image($num)
    {
        import('uploader.lib');

        $images = array();
		
		for ($i = 0; $i <= 7; $i++)
        {
            $file = $_FILES['ad' . $i . '_image_file'];
            if ($file['error'] == UPLOAD_ERR_OK)
            {
                $uploader = new Uploader();
                $uploader->allowed_type(IMAGE_FILE_TYPE);
                $uploader->addFile($file);
                $uploader->root_dir(ROOT_PATH);
                $images['ad' . $i . '_image_url'] = $uploader->save('data/files/mall/template', $uploader->random_filename());
            }
        }
		
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
		
        return $images;
    }
}

?>
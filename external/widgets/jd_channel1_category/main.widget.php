<?php

/**
 * 频道页商品分类挂件
 *
 * @return  array  
 */
class Jd_channel1_categoryWidget extends BaseWidget
{
    var $_name = 'jd_channel1_category';
    var $_ttl  = 86400;


   function _get_data()
    {
        $cache_server =& cache_server();
        $key = $this->_get_cache_id();
        $data = $cache_server->get($key);
        if($data === false)
        {
			$gcategory_mod =& bm('gcategory');
            $gcategories = array();
            if(!empty($this->options['cate_id']))
            {
                $gcategorys = $gcategory_mod->get_children($this->options['cate_id']);
				foreach($gcategorys as $key => $cate)
				{
					$gcategorys[$key]['child'] = $gcategory_mod->get_children($cate['cate_id']);
					$gcategorys[$key]['brand'] =Psmb_init()->Jd_widget_get_brand_list($cate['cate_name'],15);
				}
            }
			$data = array(
				'model_name' => $this->options['model_name'],
				'gcategories' => $gcategorys,
			);
            $cache_server->set($key, $data, $this->_ttl);
        }
		return $data;
    }

	function get_config_datasrc()
    {
        // 取得一级商品分类
        $this->assign('gcategories', $this->_get_gcategory_options(1));
    }
	

}

?>
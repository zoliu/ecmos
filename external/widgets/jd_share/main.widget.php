<?php

class Jd_shareWidget extends BaseWidget
{
    var $_name = 'jd_share';
    var $_ttl  = 86400;
    var $_num  = 12;

    function _get_data()
    {
        $cache_server =& cache_server();
        $key = $this->_get_cache_id();
        $data = $cache_server->get($key);
        if($data === false)
        {
			$recom_mod =& m('recommend');
            $goods_list = $recom_mod->get_recommended_goods($this->options['img_recom_id'], 7, true, $this->options['img_cate_id']);
			$data = array(
				'model_id'   =>mt_rand(),
				'model_name_1' => $this->options['model_name_1'],
				'model_name_2' => $this->options['model_name_2'],
				'goods_list'=>$goods_list,
				'comments' => Psmb_init()->Jd_share_get_comment(),
			);
            $cache_server->set($key, $data, $this->_ttl);
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
}

?>
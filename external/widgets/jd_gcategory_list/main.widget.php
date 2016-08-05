<?php

/**
 * 商品分类挂件
 *
 * @return  array   $category_list
 */
class Jd_gcategory_listWidget extends BaseWidget
{
    var $_name = 'jd_gcategory_list';
    var $_ttl  = 86400;


    function _get_data()
    {
        $cache_server =& cache_server();
        $key = $this->_get_cache_id();
        $data = $cache_server->get($key);
        if($data === false)
        {
			$amount = (empty($this->options['amount']) || intval($this->options['amount'])<=0) ? 0 : intval($this->options['amount']);
			/* position: 给弹出层设置高度，使得页面效果美观 */
			$position = array('0px','0px','0px','0px','0px','0px','0px','0px');
			$data = Psmb_init()->get_header_gcategories($amount,$position,1);// 参数说明（二级分类显示数量,弹出层位置,品牌是否为推荐）
			$cache_server->set($key, $data, $this->_ttl);
        }
        return $data;
    }
	
	function get_config_datasrc()
    {
        $this->assign('gcategories',array_values($this->_get_gcategory_options(1)));
    }
	
}

?>
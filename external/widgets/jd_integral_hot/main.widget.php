<?php

class Jd_integral_hotWidget extends BaseWidget
{
    var $_name = 'jd_integral_hot';

    function _get_data()
    {
		return array(
			'news'=>$this->get_list(),
			'hots'=>$this->get_list('hot'),
		);
    }
	function get_list($param='')
	{
		if($param == 'hot')
		{
			$order = 'goods_statistics.sales desc';
		}
		else
		{
			$order = 'g.add_time desc';
		}
		
		$goods_mod=&m('goods');
		$list = $goods_mod->find(array(
			'conditions'=> 'gi.max_exchange > 0 '.$conditions,
			'join'      => 'has_goodsstatistics,has_goodsintegral, has_default_spec',
			'fields'    => 'gi.max_exchange,g.default_image,g.goods_name,gs.price,sales',
			'limit'     => 10,
			'order'     => $order
		));
		
		$list = !empty($list)? $list : array();
		
		foreach($list as $key=>$goods){
			empty($goods['default_image']) && $list[$key]['default_image']=Conf::get('default_goods_image');
		} 	
		return $list;
	}
}

?>
<?php

class point_goodsModel extends BaseModel
{
    var $table  = 'point_goods';
    var $prikey = 'id';
    var $_name  = 'point_goods';
    var $_relation  = array(
        // 一个订单有一个实物商品订单扩展
        'has_log' => array(
            'model'         => 'piont_goods_log',
            'type'          => HAS_MANY,
            'foreign_key'   => 'goods_id',
            'dependent'     => true
        ),
        );


    function applyPointGoods($user_id,$goods_id,$num)
    {
    	$user_info=$this->_getUserInfo($user_id);
    	$goods_info=$this->_getGoodsInfo($goods_id);
    	$user_point=$this->_getUserPoint($user_id);
    	if(!$user_info || !$goods_info )
    	{
    		return -2;//用户不存在或商品不存在
    	}

    	if(!$this->_checkStock($num,$goods_info['stock']))
    	{
    		return -3;//库存不足
    	}

    	if(!$total_point=$this->_checkPoint($goods_info['need_point'],$num,$user_point))
    	{
    		return -4;//可用积分不足
    	}

        if(!$this->_checkMaxNum($user_id,$goods_id,$num,$goods_info['max_num']))
        {
            return -5;
        }

    	$point_logs= &m('point_logs');
    	$this->_change_stock($goods_info,$num);
    	$point_logs->change_point($user_id,$total_point,gmtime(),'buyer_to_point',$remark='兑换积分商品【'.$goods_info['goods_name'].'】');
    	$this->_write_logs($user_info,$goods_info,$num,$total_point);

    	return 1;
    }

    //逻辑区
    function _checkStock($goods_num,$stock)
    {
      if($goods_num>$stock)
      {
      	return 0;
      }
      return 1;

    }

    function _checkMaxNum($user_id,$goods_id,$goods_num,$max_num)
    {
      if(intval($max_num)==0)
      {
        return 1;
      }
      $buy_max_num=$this->_getUserCurrentMaxNum($user_id,$goods_id);
      if(($buy_max_num+$goods_num)>$max_num)
      {
        return 0;
      }
      return 1;
    }

    function _checkPoint($need_point,$num,$user_point)
    {
    	$need_point=intval($need_point)*$num;
    	if($need_point>intval($user_point))
    	{
    		return 0;
    	}
    	return $need_point;
    }

    function _change_stock($goods_info,$num)
    {
    	$stock=$goods_info['stock'];
        $used_stock=$goods_info['used_stock'];
    	$stock=$stock-$num;
        $used_stock=intval($used_stock)+$num;
    	return $this->edit($goods_info['id'],array('stock'=>$stock,'used_stock'=>$used_stock));

    }





    //基础信息区
    /**
    *得到数据库已存的此用户已购买此商品数量
    */
    function _getUserCurrentMaxNum($user_id,$goods_id)
    {
        $logs_mod= &m('point_goods_log');
        $where=array('fields'=>'sum(goods_num) as max_num','conditions'=>" user_id=".$user_id." and goods_id=".$goods_id);
        $max_info=$logs_mod->get($where);
        if($max_info)
        {
            return intval($max_info['max_num']);
        }
        return 0;
    }

    /**
    *得到用户可用积分
    */
    function _getUserPoint($user_id)
    {
    	$user_ext=&m('member_ext');
    	$user_info=$user_ext->get('user_id='.$user_id);
    	if($user_info)
    	{
    		return $user_info['integral'];
    	}
    	return 0;
    }

    /**
    *得到用户信息
    */
    function _getUserInfo($user_id)
    {
    	$user=&m('member');
    	$user_info=$user->get('user_id='.$user_id);
    	if($user_info)
    	{
    		return $user_info;
    	}
    	return ;
    }

    /**
    *得到商品信息
    */
    function _getGoodsInfo($goods_id)
    {    	
    	$goods_info=$this->get('id='.$goods_id);
    	if($goods_info)
    	{
    		return $goods_info;
    	}
    	return ;
    }


    function _write_logs($user_info,$goods_info,$num,$total_point)
    {
    	$logs_mod= &m('point_goods_log');
    	$data=array(
    		'user_id'=>$user_info['user_id'],
    		'user_name'=>$user_info['user_name'],
    		'goods_id'=>$goods_info['id'],
    		'goods_name'=>$goods_info['goods_name'],
    		'addtime'=>gmtime(),
    		'goods_num'=>$num,
    		'status'=>'applying',
    		'total_point'=>$total_point,
    		);        
    	return $logs_mod->add($data);
    }
  
}
?>
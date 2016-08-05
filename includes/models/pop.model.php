<?php
class PopModel extends BaseModel
{
    var $table  = 'member';
    var $prikey = 'user_id';
    var $_name  = 'member';

    function register($user_id)
    {
    }

    function check_parent_pop($user_id)
    {
        //360cd.cn
        $member_model=&m('member');
        $where="parent_pop>0 and user_id=".$user_id;
        $member_data=$member_model->get($where);
        if(!$member_data)
        {
            //此处填写数据不存在内容
            return 0;
        }
        return 1;
        //360cd.cn
    }

    function deduct($order_id)
    {
       //360cd.cn
        $order_model=&m('order');
        $where=" order_id=".$order_id;
        $order_data=$order_model->get($where);
        if(!$order_data)
        {
            //此处填写数据不存在内容
            return 0;
        }
        $buyer_id=$order_data['buyer_id'];
        $seller_id=$order_data['seller_id'];
        $total_point=$this->get_total_point($order_id);
        if($level_info=$this->check_level($buyer_id))
        {
            $level_per=$level_info['level_discount'];
            if(floatval($level_per))
            {
                $total_point=$total_point*$level_per;
            }            
        }
        //360cd.cn
        //360cd.cn
        $setting_center_model=&m('setting_center');
        $setting_center_model->check_point($buyer_id,$total_point);
        //360cd.cn
        /* import('zllib/biz.lib');
        $biz_m=new bizMcoupon();
        $total_money=$order_data['order_amount']+$order_data['pay_money'];
        $biz_m->buy_mcoupon($buyer_id,$total_point,$order_data['order_sn'],$total_money);

        $biz_m->share_mcoupon($total_point,$order_data['order_sn'],$total_money);
        $biz=new bizBase();
        $biz->bizPerson2($total_point,$buyer_id);
        $biz->bizStore2($total_point,$seller_id);         */
        //$this->update_point($buyer_id,$total_point);      
      
        //360cd.cn
    }

  

    function check_level($user_id)
    {
        //360cd.cn
        $member_ext_model=&m('member_ext');
        return $level_info=$member_ext_model->getLevelDetailByUserId($user_id);       
    }

    function update_point($user_id,$point)
    {
        //360cd.cn
        $remark='得到购物赠送积分#point#';
        $point_logs_model=&m('point_logs');
        $point_logs_model->change_point2($user_id,$point,1,$remark);
        
    }

    function get_seller_region_id($user_id)
    {
        //360cd.cn
        $store_model=&m('store');
        $where=" store_id=".$user_id;
        $store_data=$store_model->get($where);
        if(!$store_data)
        {
            //此处填写数据不存在内容
            return;
        }
        return $store_data['region_id'];
        //360cd.cn
    }

    


    function _get_goods_ids($order_id)
    {
        $order_model=&m('ordergoods');
        $order_goods=$order_model->find(array('conditions'=>" order_goods.order_id=".$order_id,'join'=>'belongs_to_order'));
        if($order_goods)
        {
            $goods_ids=array();
            foreach($order_goods as $key=>$goods)
            {
                $goods_ids[]=$goods['goods_id'];                
            }
        }
        return implode(',',$goods_ids);
    }

    function get_total_point($order_id)
    {
        $goods_ids=$this->_get_goods_ids($order_id);
        //360cd.cn        
        $goods_model=&m('goods');
        $joinstr=$goods_model->parseJoin('goods_id','goods_id','ordergoods');
            $where=array(
                'conditions'=>" g.goods_id in(".$goods_ids.") and order_goods.order_id=".$order_id,
                'fields'=>'sum(g.pvprice * order_goods.quantity) as total_point,order_goods.quantity,g.pvprice',
                'joinstr'=>$joinstr,
            );

           
        $goods_data=$goods_model->get($where);
        if(!$goods_data)
        {
            //此处填写数据不存在内容
            return 0;
        }
        return $goods_data['total_point'];
        //360cd.cn
    }

    function compute_money($user_id,$price,$order_id=0,$goods_id=0)
    {
    	$per=0.8;
    	$user_ids=$this->_get_all_parent_id($user_id);
        $next_price=$price;
    	foreach($user_ids as $user)
    	{    		
            $user_price=$next_price*$per;
    		$next_price=$next_price*(1-$per);
            $last_user_id=$user;
            $this->_update_user_pop_info($last_user_id,$user_price,$order_id,$goods_id);
    	}
        $this->_update_user_pop_info($last_user_id,$next_price,$order_id,$goods_id);
    }

    function _get_all_parent_id($user_id)
    {
    	$user_ids=array();
    	while($user_id)
    	{
    		$user_id=$this->_get_parent_pop_id($user_id);
    		if($user_id)
    		{
    			$user_ids[]=$user_id;
    		}    		
    	}
    	return $user_ids;
    }

    function _get_parent_pop_id($user_id)
    {
    	if(!$user_id)
    	{
    		return 0;
    	}

    	$user_info=$this->get_info($user_id);
    	if($user_info)
    	{
           return $user_info['parent_pop'];
    	}
    	return 0;
    }
}
?>
<?php

/* 订单 order */
class OrderModel extends BaseModel
{
    var $table  = 'order';
    var $alias  = 'order_alias';
    var $prikey = 'order_id';
    var $_name  = 'order';
    var $_relation  = array(
        //360cd.cn
             'has_discus' => array(
                'model'         => 'discus',
                'type'          => HAS_ONE,
                'foreign_key'   => 'order_id',
                'dependent'     => true
            ),
            //360cd.cn
        // 一个订单有一个实物商品订单扩展
        'has_orderextm' => array(
            'model'         => 'orderextm',
            'type'          => HAS_ONE,
            'foreign_key'   => 'order_id',
            'dependent'     => true
        ),
        // 一个订单有多个订单商品
        'has_ordergoods' => array(
            'model'         => 'ordergoods',
            'type'          => HAS_MANY,
            'foreign_key'   => 'order_id',
            'dependent'     => true
        ),
        // 一个订单有多个订单日志
        'has_orderlog' => array(
            'model'         => 'orderlog',
            'type'          => HAS_MANY,
            'foreign_key'   => 'order_id',
            'dependent'     => true
        ),
        'belongs_to_store'  => array(
            'type'          => BELONGS_TO,
            'reverse'       => 'has_order',
            'model'         => 'store',
        ),
        'belongs_to_user'  => array(
            'type'          => BELONGS_TO,
            'reverse'       => 'has_order',
            'model'         => 'member',
        ),
    );
    //改变订单状态
    function edit_status($order_id,$order_edit_array){

        $this->edit($order_id,$order_edit_array);

        $order_merge=$this->check_order_merge($order_id);
        if ($order_merge) {
            $orders=unserialize($order_merge['order_sns']);
            foreach ($orders as $key => $order_sn) {

                $where="order_sn='{$order_sn}'";
                $order_info=$this->get($where);

                $this->edit($order_info['order_id'],$order_edit_array);
            }
        }
        return 1;
    }

    /**
     *    修改订单中商品的库存，可以是减少也可以是加回
     *
     *    @author    Garbin
     *    @param     string $action     [+:加回， -:减少]
     *    @param     int    $order_id   订单ID
     *    @return    bool
     */
    function change_stock($action, $order_id)
    {
        if (!in_array($action, array('+', '-')))
        {
            $this->_error('undefined_action');

            return false;
        }
        if (!$order_id)
        {
            $this->_error('no_such_order');

            return false;
        }

        /* 获取订单商品列表 */
        $model_ordergoods =& m('ordergoods');
        $order_goods = $model_ordergoods->find("order_id={$order_id}");
        if (empty($order_goods))
        {
            $this->_error('goods_empty');

            return false;
        }

        $model_goodsspec =& m('goodsspec');
        $model_goods =& m('goods');

        /* 依次改变库存 */
        foreach ($order_goods as $rec_id => $goods)
        {
            $model_goodsspec->edit($goods['spec_id'], "stock=stock {$action} {$goods['quantity']}");
            $model_goods->clear_cache($goods['goods_id']);
        }

        /* 操作成功 */
        return true;
    }
    /*检查订单是否是合并订单*/
    function check_order_merge($where){

        $order_info=$this->get($where);
        if ($order_info['order_merge']==1) {
            return $order_info;
        }else{
            return false;
        }
    }
    function get_order_merge_sn($order_id){
        $order_info=$this->get("order_id={$order_id} and order_merge=1");
        if ($order_info) {
            $orders=unserialize($order_info['order_sns']);
            $num=count($orders);
            return "{$num}笔合并订单";
        }else{
            return false;
        }
    }
    
    function get_order_info($order_id, $buyer_id) {
        
        $order_merge = $this->check_order_merge($order_id);
        if ($order_merge) {
            $order_info = $order_merge;
            $order_info['order_sn_num'] = $this->get_order_merge_sn($order_merge['order_id']);
        } else {
            $order_info = $this->get("order_id = {$order_id} AND buyer_id = {$buyer_id}");
        }
        return $order_info;
    }
}

?>
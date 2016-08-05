<?php

/* 订单商品 ordergoods */
class OrdergoodsModel extends BaseModel
{
    var $table  = 'order_goods';
    var $prikey = 'rec_id';
    var $_name  = 'ordergoods';
    var $_relation = array(
        // 一个订单商品只能属于一个订单
        'belongs_to_order' => array(
            'model'         => 'order',
            'type'          => BELONGS_TO,
            'foreign_key'   => 'order_id',
            'reverse'       => 'has_ordergoods',
        ),
    );
}

?>
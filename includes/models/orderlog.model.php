<?php

/* 订单日志 orderlog */
class OrderlogModel extends BaseModel
{
    var $table  = 'order_log';
    var $prikey = 'log_id';
    var $_name  = 'orderlog';
    var $_relation  = array(
        // 一个订单日志只能属于一个订单
        'belongs_to_order' => array(
            'model'         => 'order',
            'type'          => BELONGS_TO,
            'foreign_key'   => 'order_id',
            'reverse'       => 'has_orderlog',
        ),
    );
}

?>
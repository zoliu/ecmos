<?php

/**
 *    订单扩展信息表
 *
 *    @author    Garbin
 *    @usage    none
 */
class OrderextmModel extends BaseModel
{
    var $table  =   'order_extm';
    var $prikey =   'order_id';
    var $_name  =   'orderextm';
    var $_relation = array(
        'belongs_to_order'  => array(
            'type'          => BELONGS_TO,
            'reverse'       => 'has_orderextm',
            'model'         => 'order',
            'foreign_key'   => 'order_id',
        ),
    );
}

?>
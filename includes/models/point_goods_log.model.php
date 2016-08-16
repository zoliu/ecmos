<?php

class point_goods_logModel extends BaseModel
{
    var $table  = 'point_goods_log';
    var $prikey = 'id';
    var $_name  = 'point_goods_log';

     var $_relation  = array(
        // 一个订单有一个实物商品订单扩展
        'belong_to_point_goods' => array(
            'model'         => 'point_goods',
            'type'          => BELONGS_TO,
            'foreign_key'   => 'id',
            'reverse'       => 'has_log',
        ),
    );


    function getTypeList()
    {
    	return array(
    		'applying'=>'审核中',
    		'passport'=>'已兑换',
    		);
    }
}
?>
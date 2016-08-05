<?php

/* 商品属性 goodsattr */
class GoodsattrModel extends BaseModel
{
    var $table  = 'goods_attr';
    var $prikey = 'gattr_id';
    var $_name  = 'goodsattr';

    var $_relation  = array(
        // 一个商品属性只能属于一个商品
        'belongs_to_goods' => array(
            'model'         => 'goods',
            'type'          => BELONGS_TO,
            'foreign_key'   => 'goods_id',
            'reverse'       => 'has_goodsattr',
        ),
    );
}

?>
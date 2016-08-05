<?php

/* 商品规格 goodsspec */
class GoodsspecModel extends BaseModel
{
    var $table  = 'goods_spec';
    var $prikey = 'spec_id';
    var $alias  = 'gs';
    var $_name  = 'goodsspec';

    var $_relation  = array(
        // 一个商品规格只能属于一个商品
        'belongs_to_goods' => array(
            'model'         => 'goods',
            'type'          => BELONGS_TO,
            'foreign_key'   => 'goods_id',
            'reverse'       => 'has_goodsspec',
        ),
        'has_cart_items' => array(
            'model'         => 'cart',
            'type'          => HAS_MANY,
            'foreign_key'   => 'spec_id',
        ),
    );
}

?>
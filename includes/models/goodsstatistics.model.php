<?php

/* 商品统计 goodsstatistics */
class GoodsstatisticsModel extends BaseModel
{
    var $table  = 'goods_statistics';
    var $prikey = 'goods_id';
    var $_name  = 'goodsstatistics';

    var $_relation  = array(
        // 一个商品统计只能属于一个商品
        'belongs_to_goods' => array(
            'model'         => 'goods',
            'type'          => BELONGS_TO,
            'foreign_key'   => 'goods_id',
            'reverse'       => 'has_goodsstatistics',
        ),
    );
}

?>
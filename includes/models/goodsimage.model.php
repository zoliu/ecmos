<?php

class GoodsimageModel extends BaseModel
{
    var $table  = 'goods_image';
    var $prikey = 'image_id';
    var $_name  = 'goodsimage';
    var $_relation = array(
        // 一个商品图片只能属于一个商品
        'belongs_to_goods' => array(
            'model'         => 'goods',
            'type'          => BELONGS_TO,
            'foreign_key'   => 'goods_id',
            'reverse'       => 'has_goodsimage',
        ),
        // 一个商品图片对应一个图片文件
        'has_uploadedfile' => array(
            'model'         => 'uploadedfile',
            'type'          => HAS_ONE,
            'foreign_key'   => 'file_id',
            'refer_key'     => 'file_id',
            'dependent'     => true
        ),
    );
}
?>
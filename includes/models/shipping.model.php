<?php

/* 配送方式 shipping */
class ShippingModel extends BaseModel
{
    var $table  = 'shipping';
    var $prikey = 'shipping_id';
    var $_name  = 'shipping';
    var $_autov = array(
        'shipping_name' =>  array(
            'required'  => true,
            'filter'    => 'trim',
        ),
        'first_price'   =>  array(
            'required'  => true,
            'filter'    => 'floatval',
        ),
        'step_price'    =>  array(
            'filter'    => 'floatval'
        ),
        'cod_regions'   =>  array(
            'filter'    => 'trim',
        ),
        'enabled'       =>  array(
            'filter'    => 'intval',
        ),
        'sort_order'    =>  array(
            'filter'    => 'intval'
        ),
    );

    var $_relation  =   array(
        // 一个配送方式只能属于一个店铺
        'belongs_to_store' => array(
            'model'         => 'store',
            'type'          => BELONGS_TO,
            'foreign_key'   => 'store_id',
            'reverse'       => 'has_shipping',
        ),
    );
}

?>
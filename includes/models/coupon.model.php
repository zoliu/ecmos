<?php

class CouponModel extends BaseModel
{
    var $table  = 'coupon';
    var $prikey = 'coupon_id';
    var $_name  = 'coupon';
    var $_relation  = array(
        // 一种优惠券有多个优惠券编号
        'has_couponsn' => array(
            'model'         => 'couponsn',
            'type'          => HAS_MANY,
            'foreign_key'   => 'coupon_id',
            'dependent'     => true
        ),
        // 一种优惠券只能属于一个店铺
        'belong_to_store' => array(
            'model'         => 'store',
            'type'          => BELONGS_TO,
            'foreign_key'   => 'store_id',
            'reverse'       => 'has_coupon',    
        ),
    );
}

?>
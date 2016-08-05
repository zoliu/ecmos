<?php

class CouponsnModel extends BaseModel
{
    var $table  = 'coupon_sn';
    var $prikey = 'coupon_sn';
    var $_name  = 'couponsn';
    var $_relation  = array(
        // 一个优惠券编号只能属于一种优惠券
        'belongs_to_coupon' => array(
            'model'         => 'coupon',
            'type'          => BELONGS_TO,
            'foreign_key'   => 'coupon_id',
            'reverse'       => 'has_couponsn',
        ),
        // 用户与优惠券是多对多的关系   
        'bind_user' => array(
            'model'         => 'member',
            'type'          => HAS_AND_BELONGS_TO_MANY,
            'middle_table'  => 'user_coupon',
            'foreign_key'   => 'coupon_sn',
            'reverse'       => 'bind_couponsn',
        ),
    );
}

?>
<?php

/* 收货地址 address */
class AddressModel extends BaseModel
{
    var $table  = 'address';
    var $prikey = 'addr_id';
    var $_name  = 'address';

    /* 表单自动验证 */
    var $_autov = array(
        'user_id'   => array(
            'required'  => true,
        ),
        'consignee' => array(
            'required'  => true,
            'filter'    => 'trim',
        ),
        'address'   => array(
            'required'  => true,
            'filter'    => 'trim',
        ),
        'region_id' => array(
            'required'  => true,
            'filter'    => 'intval',
        ),
        'region_name'   => array(
            'required'  => true,
            'filter'    => 'trim',
        ),
        'phone_tel' => array(
            'reg'   => '/^[0-9\+(\s]{3,}[0-9\-)\s]{2,}[0-9]$/',      //电话号码至少6位
        ),
        'phone_mob' => array(
            'reg'   => '/\d{6}/',      //至少6位的数字
        ),
    );

    /* 关系列表 */
    var $_relation  = array(
        // 一个收货地址只能属于一个会员
        'belongs_to_member' => array(
            'model'             => 'member',
            'type'              => BELONGS_TO,
            'foreign_key'       => 'user_id',
            'reverse'           => 'has_address',
        ),
    );
}

?>
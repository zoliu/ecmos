<?php

return array(
    'code'      => 'tenpay',
    'name'      => Lang::get('tenpay'),
    'desc'      => Lang::get('tenpay_desc'),
    'is_online' => '1',
    'author'    => 'ECMall TEAM',
    'website'   => 'http://www.tenpay.com',
    'version'   => '1.0',
    'currency'  => Lang::get('tenpay_currency'),
    'config'    => array(
        'tenpay_account'   => array(        //账号
            'text'  => Lang::get('tenpay_account'),
            'desc'  => Lang::get('tenpay_account_desc'),
            'type'  => 'text',
        ),
        'tenpay_key'       => array(        //密钥
            'text'  => Lang::get('tenpay_key'),
            'type'  => 'text',
        ),
        'tenpay_type'  => array(            //交易类型
            'text'      => Lang::get('tenpay_type'),
            'desc'  => Lang::get('tenpay_type_desc'),
            'type'      => 'select',
            'items'     => array(
                1   => Lang::get('tenpay_type_material'),
                2   => Lang::get('tenpay_type_virtual'),
            ),
        ),
    ),
);

?>
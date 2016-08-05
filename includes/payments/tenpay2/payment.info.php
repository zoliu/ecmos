<?php

return array(
    'code'      => 'tenpay2',
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
        'magic_string' => array(            //数字签名
            'text' => Lang::get('magic_string'),
            'type' => 'text',
        ),
    ),
);

?>
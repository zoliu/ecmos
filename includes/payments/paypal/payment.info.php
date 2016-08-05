<?php

return array(
    'code'      => 'paypal',
    'name'      => Lang::get('paypal'),
    'desc'      => Lang::get('paypal_desc'),
    'is_online' => '1',
    'is_code'   => 0,
    'author'    => 'ECMall TEAM',
    'website'   => 'http://www.paypal.com',
    'version'   => '1.0',
    'currency'  => Lang::get('paypal_currency'),
    'config'    => array(
        'paypal_account'   => array(        //账号
            'text'  => Lang::get('paypal_account'),
            'desc'  => Lang::get('paypal_account_desc'),
            'type'  => 'text',
        ),
 //       'pay_fee'  => array(
 //           'text' => Lang::get('pay_fee'),
 //           'desc' => Lang::get('pay_fee_desc'),
 //           'type' => 'text',
 //       ),
        'paypal_currency'  => array(
            'text' => Lang::get('currency'),
            'desc' => Lang::get('currency_desc'),
            'type' => 'select',
            'items' => array(
                'AUD' => Lang::get('AUD'),
                'CAD' => Lang::get('CAD'),
                'EUR' => Lang::get('EUR'),
                'GBP' => Lang::get('GBP'),
                'HKD' => Lang::get('HKD'),
                'JPY' => Lang::get('JPY'),
                'NZD' => Lang::get('NZD'),
                'USD' => Lang::get('USD'),
            ),
        ),
    ),
);

?>
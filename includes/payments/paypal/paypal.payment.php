<?php

/**
 *    贝宝支付方式插件
 *
 *    @author    Garbin
 *    @usage    none
 */

class PaypalPayment extends BasePayment
{
    /* paypal网关 */
    var $_gateway   =   'https://www.paypal.com/cgi-bin/webscr';
    var $_code      =   'paypal';

    /**
     *    获取支付表单
     *
     *    @author    Garbin
     *    @param     array $order_info  待支付的订单信息，必须包含总费用及唯一外部交易号
     *    @return    array
     */
    function get_payform($order_info)
    {
        $currency_code = $this->_config['paypal_currency'];
        $params = array(
            'cmd' => '_xclick',                                 //自己的购物车
            'business' => $this->_config['paypal_account'],     //商家的贝宝账号
            'item_name'=> $order_info['order_sn'],              //订单号
            'amount'   => $order_info['order_amount'],          //商品总价
            'currency_code' => $currency_code,                  //使用哪种货币
            'return'    => $this->_create_return_url($order_info['order_id']),
            'invoice' => $order_info['order_id'],
            'charset' => CHARSET,
            'no_shipping' => '1',
            'no_note'     => '',
            'cancel_return' => site_url(),
            'notify_url'  => $this->_create_notify_url($order_info['order_id']),
            'rm' => '2',
        );
        return $this->_create_payform('GET', $params);
    }

    /**
     *    返回通知结果
     *
     *    @author    Garbin
     *    @param     array $order_info
     *    @param     bool  $strict
     *    @return    array
     */
    function verify_notify($order_info, $strict = false)
    {
        if (empty($order_info))
        {
            $this->_error('order_info_empty');

            return false;
        }
        $merchant_id = $this->_config['paypal_account'];
        $req = 'cmd=_notify-validate';
        foreach ( $_POST as $key => $value)
        {
            $value = urlencode(stripslashes($value));
            $req .= "&$key=$value";
        }
        $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . strlen($req) ."\r\n\r\n";
        $fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);

        $item_name = $_POST['item_name'];
        $item_number = $_POST['item_number'];
        $payment_status = $_POST['payment_status'];
        $payment_amount = $_POST['mc_gross'];
        $payment_currency = $_POST['mc_currency'];
        $txn_id = $_POST['txn_id'];
        $receiver_email = $_POST['receiver_email'];
        $payer_email = $_POST['payer_email'];
        $order_sn = $_POST['invoice'];
        $memo = empty($_POST['memo']) ? '' : $_POST['memo'];
        if (!$fp)
        {
            fclose($fp);
            return false;
        }
        else
        {
            fputs($fp, $header . $req);
            while (!feof($fp))
            {
                $res = fgets($fp, 1024);
                if (strcmp($res, 'VERIFIED') == 0)
                {
                    if ($payment_status != 'Completed' && $payment_status != 'Pending')
                    {
                        fclose($fp);

                        return false;
                    }
                    if ($receiver_email != $merchant_id)
                    {
                        fclose($fp);

                        return false;
                    }
                     if ($order_info['order_amount'] != $payment_amount)
                    {
                        fclose($fp);
                        $this->_error('money_inequalit');
                        return false;
                    }
                    if ($this->_config['paypal_currency'] != $payment_currency)
                    {
                        fclose($fp);

                        return false;
                    }
                    fclose($fp);
                    return array(
                        'target'    =>  ORDER_ACCEPTED,
                    );
                }
                elseif (strcmp($res, 'INVALID') == 0)
                {
                    fclose($fp);

                    return false;
                }
            }
        }
    }
}

?>
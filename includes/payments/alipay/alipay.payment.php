<?php

/**
 *    支付宝支付方式插件
 *
 *    @author    Garbin
 *    @usage    none
 */

class AlipayPayment extends BasePayment
{
    /* 支付宝网关 */
    //var $_gateway   =   'https://mapi.alipay.com/gateway.do';
    var $_gateway   =   'https://www.alipay.com/cooperate/gateway.do';
    var $_code      =   'alipay';

    /**
     *    获取支付表单
     *
     *    @author    Garbin
     *    @param     array $order_info  待支付的订单信息，必须包含总费用及唯一外部交易号
     *    @return    array
     */
    function get_payform($order_info)
    {
        $service = $this->_config['alipay_service'];
        $agent = 'C4335319945672464113';

        $params = array(

            /* 基本信息 */
            'agent'             => $agent,
            'service'           => $service,
            'partner'           => $this->_config['alipay_partner'],
            '_input_charset'    => CHARSET,
            'notify_url'        => $this->_create_notify_url($order_info['order_id']),
            'return_url'        => $this->_create_return_url($order_info['order_id']),

            /* 业务参数 */
            'subject'           => $this->_get_subject($order_info),
            //订单ID由不属签名验证的一部分，所以有可能被客户自行修改，所以在接收网关通知时要验证指定的订单ID的外部交易号是否与网关传过来的一致
            'out_trade_no'      => $this->_get_trade_sn($order_info),
            'price'             => $order_info['order_amount'],   //应付总价
            'quantity'          => 1,
            'payment_type'      => 1,

            /* 物流参数 */
            'logistics_type'    => 'EXPRESS',
            'logistics_fee'     => 0,
            'logistics_payment' => 'BUYER_PAY_AFTER_RECEIVE',

            /* 买卖双方信息 */
            'seller_email'      => $this->_config['alipay_account']
        );

        $params['sign']         =   $this->_get_sign($params);
        $params['sign_type']    =   'MD5';

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

        /* 初始化所需数据 */
        $notify =   $this->_get_notify();
        //write_log($notify);
        /* 验证来路是否可信 */
        if ($strict)
        {
            /* 严格验证 */
            $verify_result = $this->_query_notify($notify['notify_id']);
            if(!$verify_result)
            {
                /* 来路不可信 */
                $this->_error('notify_unauthentic');

                return false;
            }
        }

        /* 验证通知是否可信 */
        $sign_result = $this->_verify_sign($notify);
        if (!$sign_result)
        {
            /* 若本地签名与网关签名不一致，说明签名不可信 */
            $this->_error('sign_inconsistent');

            return;
        }

        /*----------通知验证结束----------*/

        /*----------本地验证开始----------*/
        /* 验证与本地信息是否匹配 */
        /* 这里不只是付款通知，有可能是发货通知，确认收货通知 */

        if ($order_info['out_trade_sn'] != $notify['out_trade_no'])
        {
            /* 通知中的订单与欲改变的订单不一致 */
            $this->_error('order_inconsistent');

            return false;
        }
        if ($order_info['order_amount'] != $notify['total_fee'])
        {
            /* 支付的金额与实际金额不一致 */
            $this->_error('price_inconsistent');

            return false;
        }
        //至此，说明通知是可信的，订单也是对应的，可信的

        /* 按通知结果返回相应的结果 */
        switch ($notify['trade_status'])
        {
            case 'WAIT_SELLER_SEND_GOODS':      //买家已付款，等待卖家发货

                $order_status = ORDER_ACCEPTED;
            break;

            case 'WAIT_BUYER_CONFIRM_GOODS':    //卖家已发货，等待买家确认

                $order_status = ORDER_SHIPPED;
            break;

            case 'TRADE_FINISHED':              //交易结束
                if ($order_info['status'] == ORDER_PENDING)
                {
                    /* 如果是等待付款中，则说明是即时到账交易，这时将状态改为已付款 */
                    $order_status = ORDER_ACCEPTED;
                }
                else
                {
                    /* 说明是第三方担保交易，交易结束 */
                    $order_status = ORDER_FINISHED;
                }
                break;
            case 'TRADE_CLOSED':                //交易关闭
                $order_status = ORDER_CANCLED;
                break;
            
            /*case 'TRADE_SUCCESS':
                $order_status = ORDER_ACCEPTED;
                break;*/
            case 'TRADE_SUCCESS':              //交易结束
                if ($order_info['status'] == ORDER_PENDING)
                {
                   
                    $order_status = ORDER_ACCEPTED;
                }
                else
                {
                   
                    $order_status = ORDER_FINISHED;
                }
                break;

            default:
                $this->_error('undefined_status');
                return false;
            break;
        }

        switch ($notify['refund_status'])
        {
            case 'REFUND_SUCCESS':              //退款成功，取消订单
                $order_status = ORDER_CANCLED;
            break;
        }

        //write_log($order_status);

        return array(
            'target'    =>  $order_status,
        );
    }

    /**
     *    查询通知是否有效
     *
     *    @author    Garbin
     *    @param     string $notify_id
     *    @return    string
     */
    function _query_notify($notify_id)
    {
        //$query_url = "https://mapi.alipay.com/gateway.do?service=notify_verify&partner={$this->_config['alipay_partner']}&notify_id={$notify_id}";
        $query_url = "http://notify.alipay.com/trade/notify_query.do?partner={$this->_config['alipay_partner']}&notify_id={$notify_id}";
        return (ecm_fopen($query_url, 60) === 'true');
    }

    /**
     *    获取签名字符串
     *
     *    @author    Garbin
     *    @param     array $params
     *    @return    string
     */
    function _get_sign($params)
    {
        /* 去除不参与签名的数据 */
        unset($params['sign'], $params['sign_type'], $params['order_id'], $params['app'], $params['act']);

        /* 排序 */
        ksort($params);
        reset($params);

        $sign  = '';
        foreach ($params AS $key => $value)
        {
            $sign  .= "{$key}={$value}&";
        }

        return md5(substr($sign, 0, -1) . $this->_config['alipay_key']);
    }

    /**
     *    验证签名是否可信
     *
     *    @author    Garbin
     *    @param     array $notify
     *    @return    bool
     */
    function _verify_sign($notify)
    {
        $local_sign = $this->_get_sign($notify);

        return ($local_sign == $notify['sign']);
    }
}

?>
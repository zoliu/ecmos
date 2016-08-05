<?php

/**
 *    财付通支付方式插件
 *
 *    @author    Garbin
 *    @usage    none
 */

class Tenpay2Payment extends BasePayment
{
    /* 财付通网关 */
    var $_gateway   =   'https://www.tenpay.com/cgi-bin/v1.0/pay_gate.cgi';
    var $_code      =   'tenpay2';

    /**
     *    获取支付表单
     *
     *    @author    Garbin
     *    @param     array $order_info  待支付的订单信息，必须包含总费用及唯一外部交易号
     *    @return    array
     */
    function get_payform($order_info)
    {
        /* 版本号 */
        $version = '1.0';

        /* 任务代码，定值：1 */
        $cmd_no = '1';

        /* 编码标准 */
        if (!defined('CHARSET'))
        {
            $encode_type = 2;
        }
        else
        {
            if (CHARSET == 'utf-8')
            {
                $encode_type = 2;
            }
            else
            {
                $encode_type = 1;
            }
        }

        /* 交易日期 */
        $today = date('Ymd');
        
        /* 银行类型:支持纯网关和财付通 */
        $bank_type = '0';
        /* 订单描述，用订单号替代 */
        if (!empty($order_info['order_id']))
        {
            $attach = '';
        }
        else
        {        
            $attach = 'voucher';
        }
        
        /* 平台提供者,代理商的财付通账号 */
        $chnid = $this->_config['tenpay_account'];

        /* 收款方财付通账号 */
        $seller = $this->_config['tenpay_account'];

        /* 商品名称 */
        $mch_name = $this->_get_subject($order_info);

        /* 总金额 */
        $mch_price = floatval($order_info['order_amount']) * 100;

       

        /* 交易说明 */
        $mch_desc = $this->_get_subject($order_info);
        $need_buyerinfo = '2' ;

        /* 货币类型 */
        $fee_type = '1';

        /* 生成一个随机扰码 */
        /*$rand_num = rand(1,9);
        for ($i = 1; $i < 10; $i++)
        {
            $rand_num .= rand(0,9);
        }*/

        /* 获得订单的流水号，补零到10位 */    
        $mch_vno = $this->_get_trade_sn($order_info);    

        /* 将商户号+年月日+流水号 */
        $transaction_id = $this->_config['tenpay_account'].$today.$mch_vno;
        /* 返回的路径 */
        $mch_returl = $this->_create_notify_url($order_info['order_id']);
        $show_url   = $this->_create_return_url($order_info['order_id']);
        //$attach = $rand_num;
        $spbill_create_ip =  real_ip();

        /* 数字签名 */
        $sign_text = "cmdno=" . $cmd_no . "&date=" . $today . "&bargainor_id=" . $seller .
          "&transaction_id=" . $transaction_id . "&sp_billno=" . $mch_vno .
          "&total_fee=" . $mch_price . "&fee_type=" . $fee_type . "&return_url=" . $mch_returl .
          "&attach=" . $attach . "&spbill_create_ip=" . $spbill_create_ip . "&key=" . $this->_config['tenpay_key'];
        $sign = strtoupper(md5($sign_text));

        /* 交易参数 */
        $parameter = array(
            'cmdno'             => $cmd_no,                      // 业务代码, 财付通支付支付接口填  1
            'date'              => $today,                       // 商户日期：如20051212
            'bank_type'         => $bank_type,                    // 银行类型:支持纯网关和财付通                            
            'desc'              => $mch_name,
            'purchaser_id'      => '',                            // 用户(买方)的财付通帐户,可以为空
            'bargainor_id'      => $seller,                        // 商家的财付通商户号
            'transaction_id'    => $transaction_id,             // 交易号(订单号)，由商户网站产生(建议顺序累加)
            'sp_billno'         => $mch_vno,                    //商户系统内部的定单号,最多10位    
            'total_fee'         => $mch_price,                    //订单总价
            'fee_type'          => $fee_type,                    //现金支付币种
            'return_url'        => $mch_returl,
            'attach'            => $attach,
            'sign'              => $sign,                      // MD5签名
            'sys_id'            => '542554970',                  //ECMall C账号 不参与签名
            'spbill_create_ip'  => $spbill_create_ip   //财付通风险防范参数
        );

        return $this->_create_payform('GET', $parameter);
    }

    /**
     *    返回通知结果
     *
     *    @author    Garbin
     *    @param     array $order_info
     *    @param     bool  $strict
     *    @return    array 返回结果
     *               false 失败时返回
     */
    function verify_notify($order_info, $strict = false)
    {
        /*取返回参数*/    
        $cmd_no         = $_GET['cmdno'];
        $pay_result     = $_GET['pay_result'];
        $pay_info       = $_GET['pay_info'];
        $bill_date      = $_GET['date'];
        $bargainor_id   = $_GET['bargainor_id'];
        $transaction_id = $_GET['transaction_id'];
        $sp_billno      = $_GET['sp_billno'];
        $total_fee      = $_GET['total_fee'];
        $fee_type       = $_GET['fee_type'];
        $attach         = $_GET['attach'];
        $sign           = $_GET['sign'];
        
        $order_amount = $total_fee / 100;
        
        if ($attach == 'voucher')
        {
            $this->_error('no_order');
            return false;
        }
        /* 如果pay_result大于0则表示支付失败 */
        if ($pay_result > 0)
        {
            $this->_error('pay_fail');
            return false;
        }
         /* 检查支付的金额是否相符 */
        if ($order_info['order_amount'] != $order_amount)
        {
            /* 支付的金额与实际金额不一致 */
            $this->_error('price_inconsistent');

            return false;
        }
        if ($order_info['out_trade_sn'] != $sp_billno)
        {
            /* 通知中的订单与欲改变的订单不一致 */
            $this->_error('order_inconsistent');

            return false;
        }
        /* 检查数字签名是否正确 */
        $sign_text  = "cmdno=" . $cmd_no . "&pay_result=" . $pay_result .
                      "&date=" . $bill_date . "&transaction_id=" . $transaction_id .
                      "&sp_billno=" . $sp_billno . "&total_fee=" . $total_fee .
                      "&fee_type=" . $fee_type . "&attach=" . $attach .
                      "&key=" . $this->_config['tenpay_key'];
        $sign_md5 = strtoupper(md5($sign_text));
        if ($sign_md5 != $sign)
        {
            /* 若本地签名与网关签名不一致，说明签名不可信 */
            $this->_error('sign_inconsistent');

            return false;
        }
         return array(
            'target'    =>  ORDER_ACCEPTED,
        );
    }
    
    function verify_result($result) 
    {
        if ($result)
        {
            $url = $this->_create_return_url($_GET['order_id']);
            $back_url = $url . '&cmdno=' . $_GET['cmdno'] . '&pay_result=' . $_GET['pay_result'] . '&pay_info=' . $_GET['pay_info'].
                '&date=' . $_GET['date'] . '&bargainor_id=' . $_GET['bargainor_id'] .'&transaction_id=' . $_GET['transaction_id'].
                '&sp_billno=' . $_GET['sp_billno'] . '&total_fee=' . $_GET['total_fee'] . '&fee_type=' . $_GET['fee_type'] . '&attach=' . $_GET['attach'] . '&sign=' . $_GET['sign'];
            echo "<meta name='TENCENT_ONLINE_PAYMENT' content='China TENCENT'><html><script language=javascript>window.location.href='". $back_url ."';</script></html>";
        }
    }
    
    /**
     *    获取外部交易号 覆盖基类
     *
     *    @author    huibiaoli
     *    @param     array $order_info
     *    @return    string
     */
    function _get_trade_sn($order_info)
    {
        if (!$order_info['out_trade_sn'] || $order_info['pay_alter'])
        {
            $out_trade_sn = $this->_gen_trade_sn();
        }
        else
        {
            $out_trade_sn = $order_info['out_trade_sn'];
        }
        
        /* 将此数据写入订单中 */
        $model_order =& m('order');
        $model_order->edit(intval($order_info['order_id']), array('out_trade_sn' => $out_trade_sn, 'pay_alter' => 0));
        return $out_trade_sn;
    }
    
    /**
     *    生成外部交易号
     *
     *    @author    huibiaoli
     *    @return    string
     */
    function _gen_trade_sn()
    {
        /* 选择一个随机的方案 */
        mt_srand((double) microtime() * 1000000);
        $timestamp = gmtime();
        $y = date('y', $timestamp);
        $z = date('z', $timestamp);
        $out_trade_sn = $y . str_pad($z, 3, '0', STR_PAD_LEFT) . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);

        $model_order =& m('order');
        $orders = $model_order->find('out_trade_sn=' . $out_trade_sn);
        if (empty($orders))
        {
            /* 否则就使用这个交易号 */
            return $out_trade_sn;
        }

        /* 如果有重复的，则重新生成 */
        return $this->_gen_trade_sn();
    }
}

?>
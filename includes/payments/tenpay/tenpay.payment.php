<?php

/**
 *    财付通支付方式插件
 *
 *    @author    Garbin
 *    @usage    none
 */

class TenpayPayment extends BasePayment
{
    /* 财付通网关 */
    var $_gateway   =   'https://www.tenpay.com/cgi-bin/med/show_opentrans.cgi';
    var $_code      =   'tenpay';

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
        $version = '2';

        /* 任务代码，定值：12 */
        $cmdno = '12';

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

        /* 平台提供者,代理商的财付通账号 */
        $chnid = $this->_config['tenpay_account'];

        /* 收款方财付通账号 */
        $seller = $this->_config['tenpay_account'];

        /* 商品名称 */
        $mch_name = $this->_get_subject($order_info);

        /* 总金额 */
        $mch_price = floatval($order_info['order_amount']) * 100;

        /* 物流配送说明 */
        $transport_desc = '';
        $transport_fee = '';

        /* 交易说明 */
        $mch_desc = $this->_get_subject($order_info);
        $need_buyerinfo = '2' ;

        /* 交易类型：2、虚拟交易，1、实物交易 */
        $mch_type = $this->_config['tenpay_type'];

        /* 生成一个随机扰码 */
        $rand_num = rand(1,9);
        for ($i = 1; $i < 10; $i++)
        {
            $rand_num .= rand(0,9);
        }

        /* 获得订单的流水号，补零到10位 */
        $mch_vno = $this->_get_trade_sn($order_info);

        /* 返回的路径 */
        $mch_returl = $this->_create_notify_url($order_info['order_id']);
        $show_url   = $this->_create_return_url($order_info['order_id']);
        $attach = $rand_num;

        /* 数字签名 */
        $sign_text = "attach=" . $attach . "&chnid=" . $chnid . "&cmdno=" . $cmdno . "&encode_type=" . $encode_type . "&mch_desc=" . $mch_desc . "&mch_name=" . $mch_name . "&mch_price=" . $mch_price ."&mch_returl=" . $mch_returl . "&mch_type=" . $mch_type . "&mch_vno=" . $mch_vno . "&need_buyerinfo=" . $need_buyerinfo ."&seller=" . $seller . "&show_url=" . $show_url . "&version=" . $version . "&key=" . $this->_config['tenpay_key'];

        $sign =md5($sign_text);

        /* 交易参数 */
        $parameter = array(
            'attach'            => $attach,
            'chnid'             => $chnid,
            'cmdno'             => $cmdno,                     // 业务代码, 财付通支付支付接口填  1
            'encode_type'       => $encode_type,                //编码标准
            'mch_desc'          => $mch_desc,
            'mch_name'          => $mch_name,
            'mch_price'         => $mch_price,                  // 订单金额
            'mch_returl'        => $mch_returl,                 // 接收财付通返回结果的URL
            'mch_type'          => $mch_type,                   //交易类型
            'mch_vno'           => $mch_vno,             // 交易号(订单号)，由商户网站产生(建议顺序累加)
            'need_buyerinfo'    => $need_buyerinfo,             //是否需要在财付通填定物流信息
            'seller'            => $seller,  // 商家的财付通商户号
            'show_url'          => $show_url,
            'transport_desc'    => $transport_desc,
            'transport_fee'     => $transport_fee,
            'version'           => $version,                    //版本号 2
            'key'               => $this->_config['tenpay_key'],
            'sign'              => $sign,                       // MD5签名
            'sys_id'            => '542554970'                  //ECMall C账号 不参与签名
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
        $retcode        = $_GET['retcode'];
        $status         = $_GET['status'];
        $seller         = $_GET['seller'];
        $total_fee      = $_GET['total_fee'];
        $trade_price    = $_GET['trade_price'];
        $transport_fee  = $_GET['transport_fee'];
        $buyer_id       = $_GET['buyer_id'];
        $chnid          = $_GET['chnid'];
        $cft_tid        = $_GET['cft_tid'];
        $mch_vno        = $_GET['mch_vno'];
        $attach         = !empty($_GET['attach']) ? $_GET['attach'] : '';
        $version        = $_GET['version'];
        $sign           = $_GET['sign'];
        $log_id = $mch_vno; //取得支付的log_id

        /* 如果$retcode大于0则表示支付失败 */
        if ($retcode > 0)
        {
            //echo '操作失败';
            return false;
        }
        $order_amount = $total_fee / 100;

         /* 检查支付的金额是否相符 */
        if ($order_info['order_amount'] != $order_amount)
        {
            /* 支付的金额与实际金额不一致 */
            $this->_error('price_inconsistent');

            return false;
        }

        if ($order_info['out_trade_sn'] != $log_id)
        {
            /* 通知中的订单与欲改变的订单不一致 */
            $this->_error('order_inconsistent');

            return false;
        }

        /* 检查数字签名是否正确 */
        $sign_text = "attach=" . $attach . "&buyer_id=" . $buyer_id . "&cft_tid=" . $cft_tid . "&chnid=" . $chnid . "&cmdno=" . $cmd_no . "&mch_vno=" . $mch_vno . "&retcode=" . $retcode . "&seller=" .$seller . "&status=" . $status . "&total_fee=" . $total_fee . "&trade_price=" . $trade_price . "&transport_fee=" . $transport_fee . "&version=" . $version . "&key=" . $this->_config['tenpay_key'];
        $sign_md5 = strtoupper(md5($sign_text));
        if ($sign_md5 != $sign)
        {
            /* 若本地签名与网关签名不一致，说明签名不可信 */
            $this->_error('sign_inconsistent');

            return false;
        }
        if ($status != 3)
        {
            return false;
        }

        return array(
            'target'    =>  ORDER_ACCEPTED,
        );
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
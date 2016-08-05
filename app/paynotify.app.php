<?php

/**
 *    支付网关通知接口
 *
 *    @author    Garbin
 *    @usage    none
 */
class PaynotifyApp extends MallbaseApp
{
    /**
     *    支付完成后返回的URL，在此只进行提示，不对订单进行任何修改操作,这里不严格验证，不改变订单状态
     *
     *    @author    Garbin
     *    @return    void
     */
    function index()
    {
        //这里是支付宝，财付通等当订单状态改变时的通知地址
        $order_id   = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0; //哪个订单
        if (!$order_id)
        {
            /* 无效的通知请求 */
            $this->show_warning('forbidden');

            return;
        }

        /* 获取订单信息 */
        $model_order =& m('order');
        $order_info  = $model_order->get($order_id);
        if (empty($order_info))
        {
            /* 没有该订单 */
            $this->show_warning('forbidden');

            return;
        }

        $model_payment =& m('payment');
	//360cd.cn
        $store_mod = & m('store');
        $store_info = $store_mod->get_info($order_info['seller_id']);
        if($store_info['is_open_pay']){
        	$payment_info  = $model_payment->get("payment_code='{$order_info['payment_code']}' AND store_id={$order_info['seller_id']}");
        }else{
        	$payment_info  = $model_payment->get("payment_code='{$order_info['payment_code']}' AND store_id=0");
        }
	//360cd.cn
        if (empty($payment_info))
        {
            /* 没有指定的支付方式 */
            $this->show_warning('no_such_payment');

            return;
        }

        /* 调用相应的支付方式 */
        $payment = $this->_get_payment($order_info['payment_code'], $payment_info);

        /* 获取验证结果 */
        $notify_result = $payment->verify_notify($order_info);
        if ($notify_result === false)
        {
            /* 支付失败 */
            $this->show_warning($payment->get_error());

            return;
        }
        $notify_result['target']=ORDER_ACCEPTED;

        //---www.360cd.cn  Mosquito---
        /* if ($order_info['payment_code']=='cbpay') {
          import('zllib/biz.lib');
          $biz=new bizOrder();
          $data=$biz->pay_success($order_id);
          
        } */

        #TODO 360cd.cn   seema  2015.3.25  临时在此也改变订单状态为方便调试，实际发布时应把此段去掉，订单状态的改变以notify为准
        $this->_change_order_status($order_id, $order_info['extension'], $notify_result);

        //write_log($order_id);
        //write_log($$order_info['extension']);
        //write_log($notify_result);

        /* 只有支付时会使用到return_url，所以这里显示的信息是支付成功的提示信息 */
        $this->_curlocal(LANG::get('pay_successed'));
        
        $this->assign('order', $order_info);
        $this->assign('payment', $payment_info);
        $this->display('paynotify.index.html');
    }

    /**
     *    支付完成后，外部网关的通知地址，在此会进行订单状态的改变，这里严格验证，改变订单状态
     *
     *    @author    Garbin
     *    @return    void
     */
    function notify()
    {
        //这里是支付宝，财付通等当订单状态改变时的通知地址
        $order_id   = 0;
        if(isset($_POST['order_id']))
        {
            $order_id = intval($_POST['order_id']);
        }
        else
        {
            $order_id = intval($_GET['order_id']);
        }
        if (!$order_id)
        {
            /* 无效的通知请求 */
            $this->show_warning('no_such_order');
            return;
        }
        //write_log($_GET['order_id']);
        //write_log($_POST['order_id']);

        /* 获取订单信息 */
        $model_order =& m('order');
        $order_info  = $model_order->get($order_id);
        if (empty($order_info))
        {
            /* 没有该订单 */
            $this->show_warning('no_such_order');
            return;
        }

        $model_payment =& m('payment');
	//360cd.cn
        $store_mod = & m('store');
        $store_info = $store_mod->get_info($order_info['seller_id']);
        if($store_info['is_open_pay']){
        	$payment_info  = $model_payment->get("payment_code='{$order_info['payment_code']}' AND store_id={$order_info['seller_id']}");
        }else{
        	$payment_info  = $model_payment->get("payment_code='{$order_info['payment_code']}' AND store_id=0");
        }
	//360cd.cn
        if (empty($payment_info))
        {
            /* 没有指定的支付方式 */
            $this->show_warning('no_such_payment');
            return;
        }

        /* 调用相应的支付方式 */
        $payment = $this->_get_payment($order_info['payment_code'], $payment_info);

        /* 获取验证结果 */
        $notify_result = $payment->verify_notify($order_info, true);
        if ($notify_result === false)
        {
            /* 支付失败 */
            $payment->verify_result(false);
            return;
        }

        //改变订单状态
        $this->_change_order_status($order_id, $order_info['extension'], $notify_result);
        $payment->verify_result(true);
        
        //---www.360cd.cn  Mosquito---
        /* //360cd.cn
          import('zllib/biz.lib');
          $biz=new bizOrder();
          $data=$biz->pay_success($order_id);
          //360cd.cn */

        if ($notify_result['target'] == ORDER_ACCEPTED)
        {
            /* 发送邮件给卖家，提醒付款成功 */
            $model_member =& m('member');
            $seller_info  = $model_member->get($order_info['seller_id']);

            $mail = get_mail('toseller_online_pay_success_notify', array('order' => $order_info));
            $this->_mailto($seller_info['email'], addslashes($mail['subject']), addslashes($mail['message']));

            /* 同步发送 */
            $this->_sendmail(true);
        }
    }

    /**
     *    改变订单状态
     *
     *    @author    Garbin
     *    @param     int $order_id
     *    @param     string $order_type
     *    @param     array  $notify_result
     *    @return    void
     */
    function _change_order_status($order_id, $order_type, $notify_result)
    {
        /* 将验证结果传递给订单类型处理 */
        $order_type  =& ot($order_type);

        //write_log($order_type);

        $order_type->respond_notify($order_id, $notify_result);    //响应通知
        
        //---www.360cd.cn  Mosquito---
        //变更订单状态
        $order_model = &m('order');
        $order_info = $order_model->get_order_info($order_id, $this->visitor->get('user_id'));
        if (!$order_info) {
        	show_warning('订单不存在');
        	exit();
        }
        if ($order_info['order_merge']) {
        	$order_sn_arr = unserialize($order_info['order_sns']);
        
        	foreach ($order_sn_arr as $k => $v) {
        		$order_model->edit($k, array(
        			'status' => ORDER_ACCEPTED,
        		));
        		 
        		$order_temp = $order_model->get($k);
        		 
        		//添加钱包记录
        		Money::init()->pay_buyer_add($order_temp['buyer_id'], $order_temp['seller_id'], $order_temp['order_amount'], $k, $order_temp['payment_code']);
        	}
        }
        else {
        	$order_model->edit($order_id, array(
        		'status' => ORDER_ACCEPTED,
        	));
        
        	//添加钱包记录
        	Money::init()->pay_buyer_add($order_info['buyer_id'], $order_info['seller_id'], $order_info['order_amount'], $order_id, $order_info['payment_code']);
        }
        
    }
}

?>
<?php

/**
 * 钱包充值接口
 *
 * @author Mosquito
 * @link www.360cd.cn
 */
class MoneynotifyApp extends MallbaseApp {

    /**
     * 支付完成后返回的URL，在此只进行提示，不对订单进行任何修改操作,这里不严格验证，不改变订单状态
     */
    function index() {
        
        $order_id = intval($_GET['order_id']);
        if (!$order_id) {
            show_warning('forbidden');
            return;
        }
        
        //
        $money_log_model = &m('money_log');
        $money_log_info = $money_log_model->get("id = $order_id");
        if (!$money_log_info) {
            show_warning('充值失败', '继续充值', '/?app=my_money&act=recharge');
            exit();
        }
        
        //
        $payment_model = &m('payment');
        $payment_info = $payment_model->get("payment_id = {$money_log_info['pay_id']}");
        if (!$payment_info) {
            show_warning('充值失败', '继续充值', '/?app=my_money&act=recharge');
            exit();
        }
        
        show_message('充值成功', '前往查看', '/?app=my_money');
        exit();
    }

    /**
     * 支付完成后，外部网关的通知地址，在此会进行订单状态的改变，这里严格验证，改变订单状态
     */
    function notify() {
        $order_id = intval($_REQUEST['order_id']);
        if (!$order_id) {
            show_warning('forbidden');
            exit();
        }
        
        // 改变状态
        $temp = Money::init()->recharge_update_status($order_id, MONEY_L_S_OK);
        if (!$temp) {
            show_warning('充值失败', '继续充值', '/?app=my_money&act=recharge');
            exit();
        }
    }
}

?>
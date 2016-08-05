<?php

/**
 *    邮件模板
 *
 *    @author    Hyber
 *    @usage    none
 */
class MailtemplateArrayfile extends BaseArrayfile
{
    var $_mail_user_dir;     // 用户邮件模板存放路径
    var $_mail_default_dir;  // 默认邮件模板存放路径

    function __construct()
    {
        $this->MailtemplateArrayfile();
    }
    function MailtemplateArrayfile()
    {
        $this->_mail_default_dir = ROOT_PATH . '/includes/arrayfiles/mailtemplate/';
        $this->_mail_user_dir    = ROOT_PATH . '/data/mailtemplate/';
    }
    /**
     * 获取默认设置
     *
     * @author    Hyber
     * @return    void
     */
    function get_default()
    {
        return array(
            'tobuyer_new_order_notify'=> array(
                'description' => Lang::get('tobuyer_new_order_notify_desc'),
            ),
            'tobuyer_adjust_fee_notify'=> array(
                'description' => Lang::get('tobuyer_adjust_fee_notify_desc'),
            ),
            'tobuyer_shipped_notify'=> array(
                'description' => Lang::get('tobuyer_shipped_notify_desc'),
            ),
            'tobuyer_offline_pay_success_notify'=> array(
                'description' => Lang::get('tobuyer_offline_pay_success_notify_desc'),
            ),
            'tobuyer_confirm_cod_order_notify'=> array(
                'description' => Lang::get('tobuyer_confirm_cod_order_notify_desc'),
            ),
            'tobuyer_cod_order_finish_notify'=> array(
                'description' => Lang::get('tobuyer_cod_order_finish_notify_desc'),
            ),
            'tobuyer_cancel_order_notify'=> array(
                'description' => Lang::get('tobuyer_cancel_order_notify_desc'),
            ),
            'toseller_finish_notify'=> array(
                'description' => Lang::get('toseller_finish_notify_desc'),
            ),
            'toseller_offline_pay_notify'=> array(
                'description' => Lang::get('toseller_offline_pay_notify_desc'),
            ),
            'toseller_cancel_order_notify'=> array(
                'description' => Lang::get('toseller_cancel_order_notify_desc'),
            ),
            'toseller_new_order_notify'=> array(
                'description' => Lang::get('toseller_new_order_notify_desc'),
            ),
            'toseller_online_pay_success_notify'=> array(
                'description' => Lang::get('toseller_online_pay_success_notify_desc'),
            ),
            'touser_find_password' => array(
                'description' =>
                Lang::get('touser_find_password_desc'),
            ),
            'tobuyer_question_replied' => array(
                'description' =>
                Lang::get('tobuyer_question_replied_desc'),
            ),
            'touser_send_coupon' => array(
                'description' =>
                Lang::get('touser_send_coupon_desc'),
            ),
        );
    }
    function getOne($key)
    {
        $index_data = parent::getOne($key);
        if (!$index_data)
        {
            return false;
        }
        $details_data = file_exists($this->_mail_user_dir . $key . '.php') ? include($this->_mail_user_dir . $key . '.php') : include($this->_mail_default_dir . $key . '.php');
        $data = array_merge($index_data,$details_data);
        return $data;
    }

}
?>
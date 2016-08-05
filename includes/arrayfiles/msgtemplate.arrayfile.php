<?php

/**
 *    短消息模板
 *
 *    @author    Hyber
 *    @usage    none
 */
class MsgtemplateArrayfile extends BaseArrayfile
{
    var $_msg_user_file;     // 用户短消息模板存放路径

    function __construct()
    {
        $this->MailtemplateArrayfile();
    }
    function MailtemplateArrayfile()
    {
        $this->_msg_user_file    = ROOT_PATH . '/data/msg.lang.php';
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
            'toseller_store_closed_notify'=> array(
                'description' => Lang::get('toseller_store_closed_notify_desc'),
            ),
            'toseller_store_expired_closed_notify'=> array(
                'description' => Lang::get('toseller_store_expired_closed_notify_desc'),
            ),
            'toseller_groupbuy_end_notify'=> array(
                'description' => Lang::get('toseller_groupbuy_end_notify_desc'),
            ),
            'toseller_goods_droped_notify'=> array(
                'description' => Lang::get('toseller_goods_droped_notify_desc'),
            ),
            'toseller_brand_passed_notify'=> array(
                'description' => Lang::get('toseller_brand_passed_notify_desc'),
            ),
            'toseller_brand_refused_notify' => array(
                'description' => Lang::get('toseller_brand_refused_notify_desc'),
            ),
            'toseller_store_droped_notify' => array(
                'description' => Lang::get('toseller_store_droped_notify_desc'),
            ),
            'toseller_store_passed_notify' => array(
                'description' => Lang::get('toseller_store_passed_notify_desc'),
            ),
            'toseller_store_refused_notify' => array(
                'description' => Lang::get('toseller_store_refused_notify_desc'),
            ),
            'tobuyer_groupbuy_cancel_notify'=> array(
                'description' => Lang::get('tobuyer_groupbuy_cancel_notify_desc'),
            ),
            'tobuyer_group_auto_cancel_notify'=> array(
                'description' => Lang::get('tobuyer_group_auto_cancel_notify_desc'),
            ),
            'tobuyer_groupbuy_finished_notify'=> array(
                'description' => Lang::get('tobuyer_groupbuy_finished_notify_desc'),
            ),
            'touser_send_coupon'=> array(
                'description' => Lang::get('touser_send_coupon_desc'),
            ),
        );
    }
    function getOne($key)
    {
        $ms = &ms();
        $msgtemplate = Lang::get($key);
        return $msgtemplate;
    }

}
?>
<?php
    function get_user_info($user_id)
    {
        //360cd.cn
        $member_model=&m('member');
        $where=" user_id=".$user_id;
        $member_data=$member_model->get($where);
        if(!$member_data)
        {
            //此处填写数据不存在内容
            return 0;
        }
        return $member_data;
        //360cd.cn
    }

    function get_payment_info($payment_id)
    {
        //360cd.cn
        $payment_model=&m('payment');
        $where=" payment_id=".$payment_id;
        $payment_data=$payment_model->get($where);
        if(!$payment_data)
        {
            //此处填写数据不存在内容
            return 0;
        }
        return $payment_data;
        //360cd.cn
    }
?>
<?php

/**
 *    自动交易
 *
 *    @author    Garbin
 *    @usage    none
 */
class CleanupTask extends BaseTask
{
    function run()
    {
        /* 自动确认收货 */
        $this->_auto_confirm();

        /* 自动好评 */
        $this->_auto_evaluate();

        /* 关闭过期店铺 */
        $this->_close_expired_store();

        /* 团购活动自动开始 */
        $this->_group_auto_start();

        /* 自动结束团购 */
        $this->_group_auto_end();

        /* 自动取消团购 */
        $this->_group_auto_cancel();
    }

    /**
     *    自动确认指定时间后未确认收货的订单
     *
     *    @author    Garbin
     *    @param    none
     *    @return    void
     */
    function _auto_confirm()
    {
        $now = gmtime();
        /* 默认15天 */
        $interval = empty($this->_config['confirm_interval']) ? 15 * 24 * 3600 : intval($this->_config['confirm_interval']);
        $model_order =& m('order');

        /* 确认收货 */
        /* 款到发货的订单 */
        $orders = $model_order->find(array(
            'fields'    => 'order_id',
            'conditions'=> "ship_time + {$interval} < {$now} AND status = " . ORDER_SHIPPED,
        ));
        /* 货到付款的订单 */
        $cod_orders = $model_order->find(array(
            'fields'    => 'order_id',
            'conditions'=> "ship_time + {$interval} < {$now} AND status =" . ORDER_SHIPPED . ' AND payment_code=\'cod\'',
        ));

        if (empty($orders) && empty($cod_orders))
        {
            return;
        }

        /* 操作日志 */
        $order_logs = array();
        $order_shipped = order_status(ORDER_SHIPPED);
        $order_finished= order_status(ORDER_FINISHED);

        /* 款到发货的订单 */
        if (!empty($orders))
        {
            /* 更新订单状态 */
            $model_order->edit(array_keys($orders), array('status' => ORDER_FINISHED, 'finished_time' => gmtime()));

            /* 更新商品统计 */
            $model_goodsstatistics =& m('goodsstatistics');
            $model_ordergoods =& m('ordergoods');
            $order_goods = $model_ordergoods->find('order_id ' . db_create_in(array_keys($orders)));

            $tmp1 = $tmp2 = array();
            foreach ($order_goods as $goods)
            {
                $tmp1[$goods['goods_id']] += $goods['quantity'];
            }
            foreach ($tmp1 as $_goods_id => $_quantity)
            {
                $tmp2[$_quantity][] = $_goods_id;
            }
            foreach ($tmp2 as $_quantity => $_goods_ids)
            {
                $model_goodsstatistics->edit($_goods_ids, "sales=sales+{$_quantity}");
            }

            /* 操作记录 */
            foreach ($orders as $order_id => $order)
            {
                $order_logs[] = array(
                    'order_id'  => $order_id,
                    'operator'  => '0',
                    'order_status' => $order_shipped,
                    'changed_status' => $order_finished,
                    'remark'    => '',
                    'log_time'  => $now,
                );
            }
        }

        /* 货到付款的订单 */
        if (!empty($cod_orders))
        {
            /* 修改订单状态 */
            $model_order->edit(array_keys($cod_orders), array(
                'status' => ORDER_FINISHED,
                'pay_time' => $now,
                'finished_time' => $now
            ));

            /* 操作记录 */
            foreach ($cod_orders as $order_id => $order)
            {
                $order_logs[] = array(
                    'order_id'  => $order_id,
                    'operator'  => '0',
                    'order_status' => $order_shipped,
                    'changed_status' => $order_finished,
                    'remark'    => '',
                    'log_time'  => $now,
                );
            }
        }

        $order_log =& m('orderlog');
        $order_log->add($order_logs);
    }

    function _auto_evaluate()
    {
        $now = gmtime();

        /* 默认30天未评价自动好评 */
        $interval = empty($this->_config['evaluate_interval']) ? 30 * 24 * 3600 : intval($this->_config['evaluate_interval']);
        $goods_evaluation = array(
            'evaluation'    => 3,
            'comment'       => '',
            'credit_value'  => 1
        );

        /* 获取满足条件的订单 */
        $model_order =& m('order');

        /* 指定时间后已确认收货的未评价的 */
        $orders = $model_order->find(array(
            'conditions'    => "finished_time + {$interval} < {$now} AND evaluation_status = 0 AND status = " . ORDER_FINISHED,
            'fields'        => 'order_id, seller_id',
        ));

        /* 没有满足条件的订单 */
        if (empty($orders))
        {
            return;
        }

        $order_ids = array_keys($orders);

        /* 获取待评价的商品列表 */
        $model_ordergoods =& m('ordergoods');
        $order_goods = $model_ordergoods->find(array(
            'conditions'    => 'order_id ' . db_create_in($order_ids),
            'fields'        => 'rec_id, goods_id',
        ));

        /* 自动好评 */
        $model_ordergoods->edit(array_keys($order_goods), $goods_evaluation);
        $model_order->edit($order_ids, array(
                'evaluation_status' => 1,
                'evaluation_time'   => gmtime()
        ));

        $model_store =& m('store');

        /* 因为店铺ID有可能重复，因此 */
        $sellers = array();
        foreach ($orders as $order_id => $order)
        {
            $sellers[$order['seller_id']] = $order['seller_id'];
        }
        foreach ($sellers as $seller_id)
        {
            $model_store->edit($seller_id, array(
                'credit_value'  =>  $model_store->recount_credit_value($seller_id),
                'praise_rate'   =>  $model_store->recount_praise_rate($seller_id)
            ));
        }

        /* 因为商品ID有可能重复，因此 */
        $comments = array();
        foreach ($order_goods as $rec_id => $og)
        {
            $comments[$og['goods_id']]++;
        }
        $edit_comments = array();
        foreach ($comments as $og_id => $t)
        {
            $edit_comments[$t][] = $og_id;
        }

        $model_goodsstatistics =& m('goodsstatistics');
        foreach ($edit_comments as $times => $goods_ids)
        {
            $model_goodsstatistics->edit($goods_ids, 'comments=comments+' . $times);
        }
    }

    function _close_expired_store()
    {
        $store_mod =& m('store');
        $stores = $store_mod->find(array(
            'conditions' => "state = '" . STORE_OPEN . "' AND end_time > 0 AND end_time < '" . gmtime() . "'",
            'join'       => 'belongs_to_user',
            'fields'     => 'store_id, user_id, user_name, email',
        ));

        /* 无过期店铺 */
        if (empty($stores))
        {
            return;
        }

        $ms =& ms();
        $store_ids = $store_emails = array();

        /* 消息内容 */
        $content = get_msg('toseller_store_expired_closed_notify');

        foreach ($stores as $store)
        {
            $store_ids[] = $store['store_id'];
            $store_emails[] = $store['email'];
        }

        
        $ms->pm->send(MSG_SYSTEM, $store_ids, '', $content);
        
        
        
        $store_mod->edit($store_ids, array('state' => STORE_CLOSED, 'close_reason' => Lang::get('toseller_store_expired_closed_notify')));
    }

    function _group_auto_start()
    {
        $groupbuy_mod =& m('groupbuy');
        $groupbuy_mod->edit(
            "state = '" . GROUP_PENDING . "' AND start_time > 0 AND start_time < '" . gmtime() . "'",
            array(
                'state' => GROUP_ON,
        ));
    }

    function _group_auto_end()
    {
        $ms =& ms();
        $groupbuy_mod =& m('groupbuy');
        $groups = $groupbuy_mod -> find(array(
            'conditions' => "gb.state = '" . GROUP_ON . "' AND gb.end_time > 0 AND gb.end_time < '" . gmtime() . "'",
            'join' => 'belong_store',
        ));
        $content = get_msg('toseller_groupbuy_end_notify',array('cancel_days' => GROUP_CANCEL_INTERVAL));
        foreach ($groups as $group)
        {
            $group_ids [] = $group['group_id'];
            $ms->pm->send(
                MSG_SYSTEM,
                $group['store_id'],
                '',
                $content
            );
        }
        if (!empty($group_ids))
        {
            $groupbuy_mod->edit($group_ids,array('state' => GROUP_END));
        }

    }

    function _group_auto_cancel()
    {
        /* 自动取消团购的天数 */
        $interval = GROUP_CANCEL_INTERVAL * 3600 * 24;

        $groupbuy_mod =& m('groupbuy');
        $groups = $groupbuy_mod -> findAll(array(
            'conditions' => "gb.state = '" . GROUP_END . "' AND gb.end_time > 0 AND gb.end_time + {$interval} < '" . gmtime() . "'",
            'join' => 'belong_store',
            'include' => array('be_join')
        ));

        // 短信通知
        $ms =& ms();
        $userpriv_mod = &m('userpriv');
        foreach ($groups as $group)
        {
            // 管理员
            $admin_id = $userpriv_mod->get_admin_id();
            $to_id = array_keys($admin_id);

            $group_ids[] =  $group['group_id'];

            // 参与团购的用户
            if (!empty($group['member']))
            {
                foreach ($group['member'] as $join_user)
                {
                    $to_id[] = $join_user['user_id'];
                }
                $to_id = array_unique($to_id);
            }
            
            $content = get_msg('tobuyer_group_auto_cancel_notify', array('cancel_days' => GROUP_CANCEL_INTERVAL, 'url' => SITE_URL . '/' . url("app=groupbuy&id=" . $group['group_id'])));
            $ms->pm->send(
                MSG_SYSTEM,
                $to_id,
                '',
                $content
            );

        }

        // 取消团购活动
        empty($group_ids) || $groupbuy_mod->edit($group_ids, array('state' => GROUP_CANCELED));
    }
}

?>
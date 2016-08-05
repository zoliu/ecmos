<?php

class groupbuy
{
    
    
    function _query_goods_info($goods_id)
    {
        $this->_groupbuy_mod = &m('groupbuy');
        $this->_goods_mod = &m('goods');
        $goods = $this->_goods_mod->get_info($goods_id);
        if ($goods['spec_qty'] ==1 || $goods['spec_qty'] ==2)
        {
            $goods['spec_name'] = htmlspecialchars($goods['spec_name_1'] . ($goods['spec_name_2'] ? ' ' . $goods['spec_name_2'] : ''));
        }
        else
        {
            $goods['spec_name'] = Lang::get('spec');
        }
        foreach ($goods['_specs'] as $key => $spec)
        {
            if ($goods['spec_qty'] ==1 || $goods['spec_qty'] ==2)
            {
                $goods['_specs'][$key]['spec'] = htmlspecialchars($spec['spec_1'] . ($spec['spec_2'] ? ' ' . $spec['spec_2'] : ''));
            }
            else
            {
                $goods['_specs'][$key]['spec'] = Lang::get('default_spec');
            }
        }
        $goods['default_image'] || $goods['default_image'] = Conf::get('default_goods_image');
        return $goods;
    }
    function _get_state_desc($state, $end_time)
    {
        $lefttime = lefttime($end_time);
        $desc = array(
            GROUP_ON    =>  Lang::get('desc_on') . ' ' . $lefttime,
            GROUP_END   =>  Lang::get('desc_end'),
            GROUP_FINISHED  => Lang::get('desc_finished'),
            GROUP_CANCELED  => Lang::get('desc_cancel'),
        );
        return $desc[$state];
    }
    
    function _ican($id, $state, $store_id, $act = '')
    {
        $state_permission = array(
            GROUP_PENDING   => array(),
            GROUP_ON        => array(),
            GROUP_END       => array(),
            GROUP_FINISHED  => array(),
            GROUP_CANCELED  => array()
        );
        $member_mod = &m('member');

        if ($this->_visitor['user_id'] > 0) //已登陆用户
        {
            // 是否已经参加
            $join = current($member_mod->getRelatedData('join_groupbuy', $this->_visitor['user_id'], array(
                    'conditions' => 'gb.group_id=' . $id,
                    'order' => 'gb.group_id DESC',
                    'fields' => 'gb.state'
            )));
            if ($join)
            {
                $state_permission[GROUP_ON] = array('ask', 'exit' ,'join_info'); // 咨询,退出团购,参团信息
                $state_permission[GROUP_CANCELED] = array('join_info');
                $state_permission[GROUP_FINISHED] = array('join_info', 'buy');
                $state_permission[GROUP_END] = array('join_info');
            }
            else
            {
                $state_permission[GROUP_ON] = array('ask', 'join');
            }

            if ($store_id == $this->_visitor['user_id']) // 浏览者为团购发起者
            {
                $state_permission[GROUP_ON] = array('ask');
            }
        }
        else // 游客
        {
            $state_permission[GROUP_ON] = array('ask', 'join', 'login'); // login提示需要登陆才能参加
        }

        if (empty($act))
        {
            $actions = array();
            foreach ($state_permission[$state] as $action)
            {
                $actions[$action] = true;
            }
            return $actions; // 返回该团购此状态时允许的操作
        }
        return in_array($act, $state_permission[$state]) ? true : false; // 该团购此状态是否允许执行此操作
    }
    
    


}

?>
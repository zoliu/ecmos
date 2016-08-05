<?php

/* 团购活动 groupbuy */
class GroupbuyModel extends BaseModel
{
    var $table  = 'groupbuy';
    var $alias  = 'gb';
    var $prikey = 'group_id';
    var $_name  = 'groupbuy';
    var $_relation  = array(
        // 一个团购活动属于一个商品
        'belong_goods' => array(
            'model'         => 'goods',
            'type'          => BELONGS_TO,
            'foreign_key'   => 'goods_id',
            'reverse'       => 'has_groupbuy',
        ),
        // 一个团购活动属于一个店铺
        'belong_store' => array(
            'model'         => 'store',
            'type'          => BELONGS_TO,
            'foreign_key'   => 'store_id',
            'reverse'       => 'has_groupbuy',
        ),
        // 团购活动和会员是多对多的关系（会员参加团购活动）
        'be_join' => array(
            'model'         => 'member',
            'type'          => HAS_AND_BELONGS_TO_MANY,
            'middle_table'  => 'groupbuy_log',
            'foreign_key'   => 'group_id',
            'reverse'       => 'join_groupbuy',
        ),
        // 一个团购被一个会员发起
        'be_start' => array(
            'model'         => 'member',
            'type'          => BELONGS_TO,
            'foreign_key'   => 'user_id',
            'reverse'       => 'start_groupbuy',
        ),
        //一个团购有多个咨询
        'has_consulting' => array(
            'model' => 'goodsqa',
            'type' => HAS_MANY,
            'foreign_key' => 'item_id',
            'ext_limit' => array('type' => 'groupbuy'),
            'dependent' => true,
        ),
    );
    var $_autov = array(
        'group_name' => array(
            'required'  => true,
            'filter'    => 'trim',
            'max'       => 255,
        ),
        'group_desc' => array(
            'filter'    => 'trim',
        ),
        'min_quantity' => array(
            'required'  => true,
            'type'      => 'int',
            'filter'    => 'intval',
            'max'       => 65535,
        ),
        'max_per_user' => array(
            'type'      => 'int',
            'filter'    => 'intval',
            'max'       => 65535,
        ),
    );

    function get_join_list($group_id)
    {
        $join_list = $this->getRelatedData('be_join', $group_id);
        foreach ($join_list as $key => $val)
        {
            $val['spec_quantity'] = unserialize($val['spec_quantity']);
            $join_list[$val['user_id']] = $val;
            unset($join_list[$key]);
        }
        return $join_list;
    }

    /**
     * 查询订购数
     *
     * @param mix $group_id
     */
    function get_join_quantity($group_id)
    {
        if (is_array($group_id))
        {
            $ids = $group_id;
        }
        else
        {
            $ids = array(intval($group_id));
        }
        $quantity = $this->db->getAllWithIndex("SELECT group_id,sum(quantity) as quantity FROM ". DB_PREFIX ."groupbuy_log  WHERE group_id " . db_create_in($ids) . "GROUP BY group_id", array('group_id'));
        if (is_array($group_id))
        {
            foreach ($ids as $id)
            {
                !isset($quantity[$id]) && $quantity[$id] = array();
            }
            return $quantity;
        }
        else
        {
            return isset($quantity[$group_id]['quantity']) ? $quantity[$group_id]['quantity'] : 0;
        }
    }

        /**
     * 查询订单数
     *
     * @param mix $group_id
     */
    function get_order_count($group_id)
    {
        if (is_array($group_id))
        {
            $ids = $group_id;
        }
        else
        {
            $ids = array(intval($group_id));
        }
        $count = $this->db->getAllWithIndex("SELECT group_id,count(*) as count FROM ". DB_PREFIX ."groupbuy_log  WHERE group_id " . db_create_in($ids) . " AND order_id>0 GROUP BY group_id", array('group_id'));
        if (is_array($group_id))
        {
            foreach ($ids as $id)
            {
                !isset($count[$id]) && $count[$id] = array();
            }
            return $count;
        }
        else
        {
            return isset($count[$group_id]['count']) ? $count[$group_id]['count'] : 0;
        }
    }

    function get_order_ids($group_id)
    {
        $orders = $this->db->getAllWithIndex("SELECT order_id FROM ". DB_PREFIX ."groupbuy_log  WHERE group_id=" . $group_id . " AND order_id>0", array('order_id'));
        return array_keys($orders);
    }

    /**
     * 发送通知
     *
     * @param array  $id   团购ID
     * @param array  $to   如array('buyer','admin','seller')
     * @param string $title
     * @param string $content
     * @param array  $type  如array('msg','email')
     */
    function sys_notice($id, $to, $title, $content, $type)
    {
        $userpriv_mod = &m('userpriv');
        $to_ids = $to_emails = array();
        if (in_array('admin', $to))
        {
            $admins = $userpriv_mod->get_admin_id();
            if (is_array($admins))
            {
                foreach ($admins as $k => $v)
                {
                    $to_ids[] = $v['user_id'];
                    $to_emails[] = $v['email'];
                }
            }
        }
        if (in_array('buyer', $to))
        {
            $join_list = $this->get_join_list($id);

            if (is_array($join_list))
            {
                foreach ($join_list as $key => $val)
                {
                    $to_ids[] = $val['user_id'];
                    $to_emails[] = $val['email'];
                }
            }
        }
        if (in_array('seller', $to))
        {
            $group = $this->get(array(
                'conditions' => 'group_id=' . $id,
                'fields' => 'gb.group_id,gb.store_id,member.email',
                'join'  => 'be_start'
            ));

            if (is_array($group))
            {
                $to_ids[] = $group['store_id'];
                $to_emails[] = $group['email'];
            }
        }
        $to_ids = array_unique($to_ids);
        $to_emails = array_unique($to_emails);
        if (in_array('msg',$type))
        {
            if (!empty($to_ids))
            {
                $ms =& ms();
                $ms->pm->send(MSG_SYSTEM, $to_ids, $title, $content);
            }
            else
            {
                $this->_error('empty_ids');
                return false;
            }
        }
        if(in_array('email',$type))
        {
            if (!empty($to_emails))
            {
                $this->_mailto($to_emails, $title, $content);
            }
            else
            {
                $this->_error('empty_emails');
                return false;
            }
        }
    }
}
?>
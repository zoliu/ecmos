<?php

/**
 *    买家我的团购控制器
 *
 *    @author    Hyber
 *    @usage    none
 */
class Buyer_groupbuyApp extends MemberbaseApp
{
    var $_goods_mod;
    var $_store_mod;
    var $_groupbuy_mod;
    var $_member_mod;

    /* 构造函数 */
    function __construct()
    {
         $this->Buyer_groupbuyApp();
    }

    function Buyer_groupbuyApp()
    {
        parent::__construct();

        $this->_goods_mod =& m('goods');
        $this->_store_mod =& m('store');
        $this->_groupbuy_mod =& m('groupbuy');
        $this->_member_mod =& m('member');
    }

    function index()
    {
        /* 取得列表数据 */
        $conditions = $this->_get_query_conditions(array(
            array(      //按团购状态搜索
                'field' => 'state',
                'name'  => 'state',
                'handler' => 'groupbuy_state_translator',
            ),
            array(      //按团购名称搜索
                'field' => 'group_name',
                'name'  => 'group_name',
                'equal' => 'LIKE',
            ),
        ));
        // 标识有没有过滤条件
        if ($conditions)
        {
            $this->assign('filtered', 1);
        }
        $page   =   $this->_get_page(10);    //获取分页信息
        $groupbuy_list = $this->_groupbuy_mod->find(array(
            'join' => 'be_join',
            'order' => 'gb.group_id DESC',
            'limit' => $page['limit'],  //获取当前页的数据
            'count' => true,
            'conditions' => 'user_id=' . $this->visitor->info['user_id'] . $conditions
        ));
        $page['item_count'] = $this->_groupbuy_mod->getCount();   //获取统计的数据
        foreach ($groupbuy_list as $key => $groupbuy)
        {
            $groupbuy['ican'] = $this->_ican($groupbuy['group_id']);
            $groupbuy_list[$key] = $groupbuy;
            $groupbuy_list[$key]['spec_quantity'] = unserialize($groupbuy['spec_quantity']);
            $groupbuy['default_image'] || $groupbuy_list[$key]['default_image'] = Conf::get('default_goods_image');
        }//dump($groupbuy_list);
        /* 当前位置 */
        $this->_curlocal(LANG::get('member_center'),    'index.php?app=member',
                         LANG::get('my_groupbuy'), 'index.php?app=buyer_groupbuy',
                         LANG::get('groupbuy_list'));

        /* 当前用户中心菜单 */
        $this->_curitem('my_groupbuy');

        /* 当前所处子菜单 */
        $this->_curmenu('groupbuy_list');
        $this->_format_page($page);
        $this->assign('page_info', $page);          //将分页信息传递给视图，用于形成分页条
        $this->assign('groupbuy_list', $groupbuy_list);
        $this->assign('state', array('all' => Lang::get('group_all'),
             'on' => Lang::get('group_on'),
             'end' => Lang::get('group_end'),
             'finished' => Lang::get('group_finished'),
             'canceled' => Lang::get('group_canceled'))
        );
        $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('my_groupbuy'));
        $this->display('buyer_groupbuy.index.html');
    }

    /**
     *    三级菜单
     *
     *    @author    Garbin
     *    @return    void
     */
    function _get_member_submenu()
    {
        $menus = array(
            array(
                'name'  => 'groupbuy_list',
                'url'   => 'index.php?app=seller_groupbuy',
            ),
        );
        if (ACT == 'add' || ACT == 'edit' || ACT == 'desc' || ACT == 'cancel')
        {
            $menus[] = array(
                'name' => ACT . '_groupbuy',
                'url'  => '',
            );
        }
        return $menus;
    }

    function exit_group()
    {
        $id = empty($_GET['id']) ? 0 : $_GET['id'];
        if (!$id)
        {
            $this->show_warning('no_such_groupbuy');
            return false;
        }

        // 判断是否能退团
        if (!$this->_ican($id, ACT))
        {
            $this->show_warning('Hacking Attempt');
            return;
        }
        $member_mod = &m('member');
        $member_mod->unlinkRelation('join_groupbuy', $this->visitor->info['user_id'], $id);
        $this->show_message('exit_groupbuy_succeed');
    }

    function _ican($id, $act = '')
    {
        $state_permission = array(
            GROUP_PENDING   => array(),
            GROUP_ON        => array('view', 'exit_group'),
            GROUP_END       => array('view'),
            GROUP_FINISHED  => array('view', 'buy'),
            GROUP_CANCELED  => array('view')
        );

        $group = current($this->_member_mod->getRelatedData('join_groupbuy', $this->visitor->info['user_id'], array(
                'conditions' => 'gb.group_id=' . $id,
                'order' => 'gb.group_id DESC',
                'fields' => 'gb.state,groupbuy_log.order_id'
        )));
        if (!$group)
        {
            return false; // 越权或没有该团购
        }
        else
        {
            $state_permission[GROUP_FINISHED] = $group['order_id'] > 0 ? array('view', 'view_order') : array('view', 'buy');
        }
        if (empty($act))
        {
            return $state_permission[$group['state']]; // 返回该团购此状态时允许的操作
        }
        return in_array($act, $state_permission[$group['state']]) ? true : false; // 该团购此状态是否允许执行此操作
    }
}

?>
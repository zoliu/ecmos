<?php

/**
 *    卖家团购管理控制器
 *
 *    @author    Hyber
 *    @usage    none
 */
class Seller_groupbuyApp extends StoreadminbaseApp
{
    var $_store_id;
    var $_goods_mod;
    var $_store_mod;
    var $_groupbuy_mod;
    var $_last_update_id;

    /* 构造函数 */
    function __construct()
    {
         $this->Seller_groupbuyApp();
    }

    function Seller_groupbuyApp()
    {
        parent::__construct();

        $this->_store_id  = intval($this->visitor->get('manage_store'));
        $this->_goods_mod =& m('goods');
        $this->_store_mod =& m('store');
        $this->_groupbuy_mod =& m('groupbuy');
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
        $groupbuy_list = $this->_groupbuy_mod->find(
            array(
                'join' => 'belong_goods',
                'conditions' => 'gb.store_id=' . $this->_store_id . $conditions,
                'order' => 'gb.group_id DESC',
                'limit' => $page['limit'],  //获取当前页的数据
                'count' => true
            )
        );
        $page['item_count'] = $this->_groupbuy_mod->getCount();   //获取统计的数据
        if ($ids = array_keys($groupbuy_list))
        {
            $quantity = $this->_groupbuy_mod->get_join_quantity($ids);
            $order_count = $this->_groupbuy_mod->get_order_count($ids);
        }
        foreach ($groupbuy_list as $key => $groupbuy)
        {
            $groupbuy['quantity'] = empty($quantity[$key]['quantity']) ? 0 : $quantity[$key]['quantity'];
            $groupbuy['order_count'] = empty($order_count[$key]['count']) ? 0 : $order_count[$key]['count'];
            $groupbuy['ican'] = $this->_ican($groupbuy['group_id']);
            $groupbuy_list[$key] = $groupbuy;
            $groupbuy['default_image'] || $groupbuy_list[$key]['default_image'] = Conf::get('default_goods_image');
        }
        /* 当前位置 */
        $this->_curlocal(LANG::get('member_center'),    'index.php?app=member',
                         LANG::get('groupbuy_manage'), 'index.php?app=seller_groupbuy',
                         LANG::get('groupbuy_list'));

        /* 当前用户中心菜单 */
        $this->_curitem('groupbuy_manage');

        /* 当前所处子菜单 */
        $this->_curmenu('groupbuy_list');
        $this->_format_page($page);
        $this->_import_resource();
        $this->assign('page_info', $page);          //将分页信息传递给视图，用于形成分页条
        $this->assign('groupbuy_list', $groupbuy_list);
        $this->assign('state', array('all' => Lang::get('group_all'),
             'pending' => Lang::get('group_pending'),
             'on' => Lang::get('group_on'),
             'end' => Lang::get('group_end'),
             'finished' => Lang::get('group_finished'),
             'canceled' => Lang::get('group_canceled'))
        );
        $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('groupbuy_manage'));
        $this->display('seller_groupbuy.index.html');
    }

    function add()
    {
        if (!IS_POST)
        {
            $goods_mod = &bm('goods', array('_store_id' => $this->_store_id));
            $goods_count = $goods_mod->get_count();
            if ($goods_count == 0)
            {
                $this->show_warning('has_no_goods', 'add_goods', 'index.php?app=my_goods&act=add');
                return;
            }
            /* 当前位置 */
            $this->_curlocal(LANG::get('member_center'),    'index.php?app=member',
                             LANG::get('groupbuy_manage'), 'index.php?app=seller_groupbuy',
                             LANG::get('add_groupbuy'));

            /* 当前用户中心菜单 */
            $this->_curitem('groupbuy_manage');

            /* 当前所处子菜单 */
            $this->_curmenu('add_groupbuy');
            $this->assign('group', array('max_per_user' => 0, 'end_time' => gmtime() + 7 * 24 * 3600));
            $this->assign('store_id', $this->_store_id);
            $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('add_groupbuy'));
            $this->_import_resource();
            $this->display('seller_groupbuy.form.html');
        }
        else
        {
            /* 检查数据 */
            if (!$this->_handle_post_data($_POST, 0))
            {
                $this->show_warning($this->get_error());
                return;
            }
            $groupbuy_info = $this->_groupbuy_mod->get($this->_last_update_id);
            if ($groupbuy_info['state'] == GROUP_ON)
            {
                $_goods_info  = $this->_query_goods_info($groupbuy_info['goods_id']);
                $groupbuy_url = SITE_URL . '/' . url('app=groupbuy&id=' . $groupbuy_info['group_id']);
                $feed_images = array();
                $feed_images[] = array(
                    'url'   => SITE_URL . '/' . $_goods_info['default_image'],
                    'link'   => $groupbuy_url,
                );
                $this->send_feed('groupbuy_created', array(
                    'user_id' => $this->visitor->get('user_id'),
                    'user_name' => $this->visitor->get('user_name'),
                    'groupbuy_url' => $groupbuy_url,
                    'groupbuy_name' => $groupbuy_info['group_name'],
                    'message' => $groupbuy_info['group_desc'],
                    'images' => $feed_images,
                ));
            }
            $this->show_message('add_groupbuy_ok',
                'back_list', 'index.php?app=seller_groupbuy',
                'continue_add', 'index.php?app=seller_groupbuy&amp;act=add'
            );
        }
    }

    function edit()
    {
        $id = empty($_GET['id']) ? 0 : $_GET['id'];
        if (!$id)
        {
            $this->show_warning('no_such_groupbuy');
            return false;
        }
        if (!$this->_ican($id, ACT))
        {
            $this->show_warning('Hacking Attempt');
            return;
        }
        if (!IS_POST)
        {
            /* 当前位置 */
            $this->_curlocal(LANG::get('member_center'),    'index.php?app=member',
                             LANG::get('groupbuy_manage'), 'index.php?app=seller_groupbuy',
                             LANG::get('edit_groupbuy'));

            /* 当前用户中心菜单 */
            $this->_curitem('groupbuy_manage');

            /* 当前所处子菜单 */
            $this->_curmenu('edit_groupbuy');

            /* 团购信息 */
            $group = $this->_groupbuy_mod->get($id);
            $group['spec_price'] = unserialize($group['spec_price']);
            $goods = $this->_query_goods_info($group['goods_id']);
            foreach ($goods['_specs'] as $key => $spec)
            {
                if (!empty($group['spec_price'][$spec['spec_id']]))
                {
                    $goods['_specs'][$key]['group_price'] = $group['spec_price'][$spec['spec_id']]['price'];
                }
            }
            $this->assign('group', $group);
            $this->assign('goods', $goods);
            $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('edit_groupbuy'));
            $this->_import_resource();
            $this->display('seller_groupbuy.form.html');
        }
        else
        {
            /* 检查数据 */
            if (!$this->_handle_post_data($_POST, $id))
            {
                $this->show_warning($this->get_error());
                return;
            }
            $this->show_message('edit_groupbuy_ok',
                'back_list', 'index.php?app=seller_groupbuy',
                'continue_edit', 'index.php?app=seller_groupbuy&act=edit&id=' . $id
            );
        }
    }

    function drop()
    {
        $id = empty($_GET['id']) ? 0 : $_GET['id'];
        if (!$id)
        {
            $this->show_warning('no_such_groupbuy');
            return false;
        }
        if (!$this->_ican($id, ACT))
        {
            $this->show_warning('Hacking Attempt');
            return;
        }
        if (!$this->_groupbuy_mod->drop($id))
        {
            $this->show_warning($this->_groupbuy_mod->get_error());

            return;
        }

        $this->show_message('drop_groupbuy_successed');
    }

    function start()
    {
        $id = empty($_GET['id']) ? 0 : $_GET['id'];
        if (!$id)
        {
            $this->show_warning('no_such_groupbuy');
            return false;
        }
        if (!$this->_ican($id, ACT))
        {
            $this->show_warning('Hacking Attempt');
            return;
        }
        if (!$this->_groupbuy_mod->edit($id, array('start_time' => gmtime(), 'state' => GROUP_ON)))
        {
            $this->show_warning($this->_groupbuy_mod->get_error());

            return;
        }

        $this->show_message('start_ok');
    }

    function finished()
    {
        $id = empty($_GET['id']) ? 0 : $_GET['id'];
        if (!$id)
        {
            $this->show_warning('no_such_groupbuy');
            return false;
        }
        if (!$this->_ican($id, ACT))
        {
            $this->show_warning('Hacking Attempt');
            return;
        }
        /* 团购信息 */
        $group = $this->_groupbuy_mod->get(array(
            'conditions' => 'group_id=' . $id,
            'fields'     => 'group_name',
        ));

        if (!$this->_groupbuy_mod->edit($id, array('state' => GROUP_FINISHED, 'end_time' => gmtime())))
        {
            $this->show_warning($this->_groupbuy_mod->get_error());

            return;
        }
        $content = get_msg('tobuyer_groupbuy_finished_notify', array('group_name' => $group['group_name'], 'id' => $id));
        $this->_groupbuy_mod->sys_notice(
            $id,
            array('buyer'),
            '',
            $content,
            array('msg')
        );

        $this->show_message('finished_ok');
    }

    function desc()
    {
        $id = empty($_GET['id']) ? 0 : $_GET['id'];
        if (!$id)
        {
            $this->show_warning('no_such_groupbuy');
            return false;
        }
        if (!$this->_ican($id, ACT))
        {
            $this->show_warning('Hacking Attempt');
            return;
        }
        if (!IS_POST)
        {
            /* 当前位置 */
            $this->_curlocal(LANG::get('member_center'),    'index.php?app=member',
                             LANG::get('groupbuy_manage'), 'index.php?app=seller_groupbuy',
                             LANG::get('desc_groupbuy'));

            /* 当前用户中心菜单 */
            $this->_curitem('groupbuy_manage');

            /* 当前所处子菜单 */
            $this->_curmenu('desc_groupbuy');

            /* 团购信息 */
            $group = $this->_groupbuy_mod->get(array(
                'conditions' => 'group_id=' . $id,
                'fields'     => 'group_desc',
            ));
            $this->assign('group', $group);
            $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('desc_groupbuy'));
            $this->display('seller_groupbuy.desc.html');
        }
        else
        {
            $this->_groupbuy_mod->edit($id, array('group_desc' => trim($_POST['group_desc'])));
            if ($this->_groupbuy_mod->has_error())
            {
                $this->show_warning($this->_groupbuy_mod->get_error());

                return;
            }
            $this->show_message('desc_ok',
                'back_list', 'index.php?app=seller_groupbuy'
            );
        }
    }

    function cancel()
    {
        $id = empty($_GET['id']) ? 0 : $_GET['id'];
        if (!$id)
        {
            $this->show_warning('no_such_groupbuy');
            return false;
        }
        if (!$this->_ican($id, ACT))
        {
            $this->show_warning('Hacking Attempt');
            return;
        }
        /* 团购信息 */
        $group = $this->_groupbuy_mod->get(array(
            'conditions' => 'group_id=' . $id,
            'fields'     => 'group_desc,group_name,owner_name',
            'join'       => 'belong_store'
        ));

        if (!IS_POST)
        {
            /* 当前位置 */
            $this->_curlocal(LANG::get('member_center'),    'index.php?app=member',
                             LANG::get('groupbuy_manage'), 'index.php?app=seller_groupbuy',
                             LANG::get('cancel_groupbuy'));

            /* 当前用户中心菜单 */
            $this->_curitem('groupbuy_manage');

            /* 当前所处子菜单 */
            $this->_curmenu('cancel_groupbuy');


            $this->assign('group', $group);
            $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('desc_groupbuy'));
            $this->display('seller_groupbuy.cancel.html');
        }
        else
        {
            if (!$this->_groupbuy_mod->edit($id, array('state' => GROUP_CANCELED)))
            {
                $this->show_warning($this->_groupbuy_mod->get_error());

                return;
            }
            $content = get_msg('tobuyer_groupbuy_cancel_notify', array('reason' => $_POST['reason'], 'url' => SITE_URL . '/' . url("app=groupbuy&id=$id")));
            $this->_groupbuy_mod->sys_notice(
                $id,
                array('admin','buyer'),
                '',
                $content,
                array('msg')
            );

            $this->show_message('cancel_ok',
                'back_list', 'index.php?app=seller_groupbuy'
            );
        }

    }

    function log()
    {
        $id = empty($_GET['id']) ? 0 : $_GET['id'];
        if (!$id)
        {
            $this->show_warning('no_such_groupbuy');
            return false;
        }
        if (!$this->_ican($id, ACT))
        {
            $this->show_warning('Hacking Attempt');
            return;
        }
        /* 团购信息 */
        $group = $this->_groupbuy_mod->get(array(
            'conditions' => 'group_id=' . $id,
            'fields'     => 'group_desc, group_name, goods_id',
        ));
        $goods = $this->_query_goods_info($group['goods_id']);
        $join_list = $this->_groupbuy_mod->get_join_list($id);
        $this->assign('join_list', $join_list);
        $this->assign('goods', $goods);
        $this->assign('group', $group);
        header('Content-Type:text/html;charset=' . CHARSET);
        $this->display('seller_groupbuy.log.html');
    }

    /**
     * 检查提交的数据
     */
    function _handle_post_data($post, $id = 0)
    {
        if ($post['if_publish'] == 1 || gmstr2time($post['start_time']) <= gmtime())
        {
            $post['start_time'] = gmtime(); //立即发布
            $post['state'] = GROUP_ON;
        }
        else
        {
            $post['start_time'] = gmstr2time($post['start_time']);
            $post['state'] = GROUP_PENDING;
        }
        if (intval($post['end_time']))
        {
            $post['end_time'] = gmstr2time_end($post['end_time']);
        }
        else
        {
            $this->_error('fill_end_time');
            return false;
        }
        if ($post['end_time'] < $post['start_time'])
        {
            $this->_error('start_not_gt_end');
            return false;
        }

        if (($post['goods_id'] = intval($post['goods_id'])) == 0)
        {
            $this->_error('fill_goods');
            return false;
        }
        if (empty($post['spec_id']) || !is_array($post['spec_id']))
        {
            $this->_error('fill_spec');
            return false;
        }
        foreach ($post['spec_id'] as $key => $val)
        {
            if (empty($post['group_price'][$key]))
            {
                $this->_error('invalid_group_price');
                return false;
            }
            $spec_price[$val] = array('price' => number_format($post['group_price'][$key], 2, '.', ''));
        }

        $data = array(
            'group_name' => $post['group_name'],
            'group_desc' => $post['group_desc'],
            'start_time' => $post['start_time'],
            'end_time'   => $post['end_time'] - 1,
            'goods_id'   => $post['goods_id'],
            'spec_price' => serialize($spec_price),
            'min_quantity' => $post['min_quantity'],
            'max_per_user' => $post['max_per_user'],
            'state'        => $post['state'],
            'store_id'     => $this->_store_id
        );
        if ($id > 0)
        {
            $this->_groupbuy_mod->edit($id, $data);
            if ($this->_groupbuy_mod->has_error())
            {
                $this->_error($this->_groupbuy_mod->get_error());
                return false;
            }
        }
        else
        {
            if (!($id = $this->_groupbuy_mod->add($data)))
            {
                $this->_error($this->_groupbuy_mod->get_error());
                return false;
            }
        }
        $this->_last_update_id = $id;

        return true;
    }

    function query_goods_info()
    {
        $goods_id = empty($_GET['goods_id']) ? 0 : intval($_GET['goods_id']);
        if ($goods_id)
        {
            $goods = $this->_query_goods_info($goods_id);
            $this->json_result($goods);
        }
    }

    function _query_goods_info($goods_id)
    {
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
    function query_goods()
    {
        $goods_mod = &bm('goods', array('_store_id' => $this->_store_id));

        /* 搜索条件 */
        $conditions = "1 = 1";
        if (trim($_GET['goods_name']))
        {
            $str = "LIKE '%" . trim($_GET['goods_name']) . "%'";
            $conditions .= " AND (goods_name {$str})";
        }

        if (intval($_GET['sgcate_id']) > 0)
        {
            $cate_mod =& bm('gcategory', array('_store_id' => $this->visitor->get('manage_store')));
            $cate_ids = $cate_mod->get_descendant(intval($_GET['sgcate_id']));
        }
        else
        {
            $cate_ids = 0;
        }

        /* 取得商品列表 */
        $goods_list = $goods_mod->get_list(array(
            'conditions' => $conditions . ' AND g.if_show=1 AND g.closed=0',
            'order' => 'g.add_time DESC',
            'limit' => 100,
        ), $cate_ids);

        foreach ($goods_list as $key => $val)
        {
            $goods_list[$key]['goods_name'] = htmlspecialchars($val['goods_name']);
        }
        $this->json_result($goods_list);
    }

    function _import_resource()
    {
        if(in_array(ACT, array('index' , 'add', 'edit')))
        {
            $resource['script'][] = array( // JQUERY UI
                'path' => 'jquery.ui/jquery.ui.js'
            );
        }
        if(in_array(ACT, array('index', 'add', 'edit')))
        {
            $resource['script'][] = array( // 对话框
                'attr' => 'id="dialog_js"',
                'path' => 'dialog/dialog.js'
            );
        }
        if(in_array(ACT, array('add', 'edit')))
        {
            $resource['script'][] = array( // 验证
                'path' => 'jquery.plugins/jquery.validate.js'
            );
        }
        if(in_array(ACT, array('add', 'edit'))) //日历相关
        {
            $resource['script'][] = array(
                'path' => 'jquery.ui/i18n/' . i18n_code() . '.js'
            );
            $resource['style'] .= 'jquery.ui/themes/ui-lightness/jquery.ui.css';
        }
        $this->import_resource($resource);
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
            array(
                'name'  => 'add_groupbuy',
                'url'   => 'index.php?app=seller_groupbuy&act=add',
            ),
        );
        if (ACT == 'edit' || ACT == 'desc' || ACT == 'cancel')
        {
            $menus[] = array(
                'name' => ACT . '_groupbuy',
                'url'  => '',
            );
        }
        return $menus;
    }

    function _ican($id, $act = '')
    {
        $state_permission = array(
            GROUP_PENDING   => array('start', 'edit', 'drop'),
            GROUP_ON        => array('cancel', 'desc', 'log', 'finished', 'export_ubbcode'),
            GROUP_END       => array('cancel', 'desc', 'finished', 'log'),
            GROUP_FINISHED  => array('drop', 'log', 'view_order'),
            GROUP_CANCELED  => array('drop', 'log')
        );

        $group = $this->_groupbuy_mod->get(array(
            'join'       => 'belong_goods',
            'conditions' => 'gb.group_id=' . $id . ' AND g.store_id=' . $this->_store_id,
            'fields'     => 'gb.state',
        ));
        if (!$group)
        {
            return false; // 越权或没有该团购
        }
        if (empty($act))
        {
            return $state_permission[$group['state']]; // 返回该团购此状态时允许的操作
        }
        return in_array($act, $state_permission[$group['state']]) ? true : false; // 该团购此状态是否允许执行此操作
    }

    function export_ubbcode()
    {
        $code = '';
        $crlf = "\\n";
        $group_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        /* 团购信息 */
        $group = $this->_groupbuy_mod->get($group_id);
        $group['spec_price'] = unserialize($group['spec_price']);
        $goods = $this->_query_goods_info($group['goods_id']);
        foreach ($goods['_specs'] as $key => $spec)
        {
            if (!empty($group['spec_price'][$spec['spec_id']]))
            {
                $goods['_specs'][$key]['group_price'] = $group['spec_price'][$spec['spec_id']]['price'];
            }
        }

        /* 默认图片 */
        $goods['default_image'] && $code .= '[img]' . SITE_URL . '/' . $goods['default_image'] . '[/img]' . $crlf;

        /* 团购名称 */
        $code .= '[b]' . Lang::get('group_name') . ':[/b]' . addslashes($group['group_name']) . $crlf ;

        /* 团购说明 */
        $code .= '[b]' . Lang::get('group_desc') . ':[/b]' . addslashes($group['group_desc']) . $crlf;
        $code .= '[b]' . Lang::get('group_min_quantity') . ':[/b]' . $group['min_quantity'] . $crlf;

        /* 规格 */
        if ($goods['spec_qty'] == 0)
        {
            $code .= '[b]' . Lang::get('group_price') . ':[/b][color=Red]' . price2ubb($goods['_specs'][0]['group_price']) . '[/color](' . Lang::get('price') . ':' . price2ubb($goods['_specs'][0]['price']) . ')' .$crlf;
        }
        elseif ($goods['spec_qty'] == 1 || $goods['spec_qty'] == 2)
        {
            $code .= '[b]' . Lang::get('group_price') . ':[/b]' . $crlf;
            foreach ($goods['_specs'] as $goods)
            {
                 isset($goods['group_price']) && $code .=  addslashes($goods['spec_1']) . '  ' . addslashes($goods['spec_2']) . '[color=Red]' . price2ubb($goods['group_price']) . '[/color](' . Lang::get('price') . ':' . price2ubb($goods['price']) . ')' . $crlf;
            }
            $code .= $crlf;
        }

        /* 购买地址 */
        $url = SITE_URL . '/' . url('app=groupbuy&id=' . $group_id);
        $url = str_replace('&amp;', '&' , $url);
        $code .= '[b]' . Lang::get('join_groupbuy') . ':[/b]' . '[url=' .$url . ']' . $url . '[/url]';

        $this->assign('code', $code);
        $this->assign('alert_code', str_replace("\n", '\\n', $code));

        header("Content-type:text/html;charset=" . CHARSET, true);
        $this->display('export_ubbcode.html');
    }
}

function price2ubb($price)
{
    return str_replace('&yen;', ' RMB', price_format($price));
}

?>
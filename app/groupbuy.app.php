<?php

class GroupbuyApp extends StorebaseApp
{

    var $_groupbuy_mod;
    var $_goods_mod;
    var $_visitor;

    function __construct()
    {
        $this->GroupbuyApp();
    }

    function GroupbuyApp()
    {
        $this->_groupbuy_mod = &m('groupbuy');
        $this->_goods_mod = &m('goods');

        parent::__construct();
        $this->_visitor = $this->visitor->info;
    }

    function index()
    {
        $id = empty($_GET['id']) ? 0 : intval($_GET['id']);
        if (!$id)
        {
            $this->show_warning('no_such_groupbuy');
            return false;
        }
        // 团购信息
        $group = $this->_groupbuy_mod->get(array(
            'conditions' => 'group_id=' . $id . ' AND gb.state<>' . GROUP_PENDING,
            'join' => 'belong_store',
            'fields' => 'gb.*,s.owner_name'
        ));

        if (empty($group))
        {
            $this->show_warning('no_such_groupbuy');
            return;
        }

        // 团购商品信息
        $goods = $this->_query_goods_info($group['goods_id']);
        if ($goods['closed'] == 1)
        {
            $this->show_warning('groupbuy_goods_closed');
            return;
        }

        if (!IS_POST)
        {
            $data['views'] = $group['views'] + 1; // 浏览数
            if ($group['end_time'] < gmtime() && $group['state'] == GROUP_ON)
            {
                $group['state'] = GROUP_END; // 结束团购

                /* 通知卖家 */
                $content = get_msg('toseller_groupbuy_end_notify', array('cancel_days' => GROUP_CANCEL_INTERVAL));
                $this->_groupbuy_mod->sys_notice(
                    $id,
                    array('seller'),
                    '',
                    $content,
                    array('msg')
                );
            }
            else if ($group['end_time'] + GROUP_CANCEL_INTERVAL * 3600 * 24 < gmtime() && $group['state'] == GROUP_END)
            {
                $group['state'] = GROUP_CANCELED; // 取消团购

                /* 通知买家和站长 */
                $content = get_msg('tobuyer_group_auto_cancel_notify', array('cancel_days' => GROUP_CANCEL_INTERVAL,'url' => SITE_URL . '/' . url("app=groupbuy&id=$id")));
                $this->_groupbuy_mod->sys_notice(
                    $id,
                    array('admin','buyer'),
                    '',
                    $content,
                    array('msg')
                );
            }
            $data['state'] = $group['state'];
            $this->_groupbuy_mod->edit($id,$data);
            // 订购数
            $group['quantity'] = $this->_groupbuy_mod->get_join_quantity($id);
            // 进度
            $group['left_quantity'] = $group['min_quantity'] - $group['quantity'];
            $group['left_per'] = 100 - intval(100 * $group['quantity'] / $group['min_quantity']);
            $group['left_per'] < 0 && $group['left_per'] = 0;
            // 状态描述
            $group['state_desc'] = $this->_get_state_desc($group['state'], $group['end_time']);
            // 团购规格价格
            $group['spec_price'] = unserialize($group['spec_price']);
            // 可执行操作
            $group['ican'] = $this->_ican($group['group_id'], $group['state'], $group['store_id']);
            // 参团记录
            $join_list = $this->_groupbuy_mod->get_join_list($id);
            foreach ($goods['_specs'] as $key => $spec)
            {
                if (empty($group['spec_price'][$spec['spec_id']]))
                {
                    unset($goods['_specs'][$key]);
                }
                else
                {
                    $goods['_specs'][$key]['group_price'] = $group['spec_price'][$spec['spec_id']]['price'];
                    if (isset($join_list[$this->_visitor['user_id']]))
                    {
                        $goods['_specs'][$key]['my_qty'] = $join_list[$this->_visitor['user_id']]['spec_quantity'][$spec['spec_id']]['qty'];
                    }
                }
            }

            // 店铺信息
            $this->set_store($goods['store_id']);
            $store = $this->get_store_data();


            // 当前位置
            $this->_curlocal(array(
                array(
                    'text' => Lang::get('groupbuy'),
                    'url' => url('app=search&act=groupbuy')
                ),
                array(
                    'text' => $group['group_name'],
                )
            ));
            // 团购咨询数据
            $data = $this->_get_groupbuy_qa($id);
            
            if (Conf::get('captcha_status.goodsqa'))
            {
                $this->assign('captcha', 1);
            }

            $this->assign('email',      $data['email']);
            $this->assign('page_info',  $data['page_info']);
            $this->assign('qa_info',    $data['qa_info']);
            /* 页面标题 */
            $this->_config_seo('title', $group['group_name'] . ' - ' .Lang::get('groupbuy') . ' - ' . Conf::get('site_title'));
            $this->_import_resource();
            $this->assign('store', $store);
            $this->assign('goods', $goods);
            $this->assign('group', $group);
            $this->assign('guest_comment_enable', Conf::get('guest_comment'));
            $this->assign('join_list', $join_list);
            $this->display('groupbuy.index.html');
        }
        else
        {
            if (isset($_POST['join']))
            {

                $quantity = 0;
                $spec_quantity = array();
                foreach ($_POST['quantity'] as $key => $val)
                {
                    if ($_POST['quantity'][$key] > 0)
                    {
                        $spec_quantity[$_POST['spec_id'][$key]] = array(
                            'spec'  => $_POST['spec'][$key],
                            'qty'   => $_POST['quantity'][$key],
                        );
                        $quantity += $_POST['quantity'][$key];
                    }
                    elseif ($_POST['quantity'][$key] != '')
                    {
                        $this->show_warning('invalid_quantity');
                        return;
                    }
                }
                if ($quantity == 0)
                {
                    $this->show_warning('fill_quantity');
                    return;
                }
                if ($group['max_per_user'] > 0 && $quantity > $group['max_per_user'])
                {
                    $this->show_warning(sprintf(Lang::get('error_max_per_user'), $group['max_per_user']));
                    return;
                }
                $link_man = trim($_POST['link_man']);
                $tel = trim($_POST['tel']);
                if (!$link_man || !$tel)
                {
                    $this->show_warning('fill_join_user_info');
                    return;
                }
                $data[$group['group_id']] = array(
                    'user_name'     => $this->_visitor['user_name'],
                    'quantity'      => $quantity,
                    'spec_quantity' => serialize($spec_quantity),
                    'linkman'       => $link_man,
                    'tel'           => $tel,
                    'order_id'      => 0,
                    'add_time'      => gmtime(),
                );
                $member_mod = &m('member');
                $member_mod->createRelation('join_groupbuy', $this->_visitor['user_id'], $data);
                $this->show_message('join_groupbuy_successed');

                $groupbuy_url = SITE_URL . '/' . url('app=groupbuy&id=' . $group['group_id']);
                $groupbuy_name  = $group['group_name'];
                $this->send_feed('groupbuy_joined', array(
                    'user_id'   => $this->visitor->get('user_id'),
                    'user_name'   => $this->visitor->get('user_name'),
                    'groupbuy_url'   => $groupbuy_url,
                    'groupbuy_name'   => $groupbuy_name,
                    'images'   => array(
                        array(
                            'url'   => SITE_URL . '/' . $goods['default_image'],
                            'link'   => $groupbuy_url,
                        )
                    ),
                ));

                return;
            }
            elseif (isset($_POST['qa']))
            {
                /* 不允许游客评论 */
                if (!Conf::get('guest_comment') && !$this->visitor->has_login)
                {
                    $this->show_warning('guest_comment_disabled');

                    return;
                }

                //团购咨询
                $content = (isset($_POST['content'])) ? trim($_POST['content']) : '';
                $email = (isset($_POST['email'])) ? trim($_POST['email']) : '';
                $hide_name = (isset($_POST['hide_name'])) ? trim($_POST['hide_name']) : '';
                if (empty($content))
                {
                    $this->show_warning('content_not_null');
                    return;
                }
                $qa_mod =& m('goodsqa');
                //对验证码和邮件进行判断
                if (Conf::get('captcha_status.goodsqa'))
                {
                    if (base64_decode($_SESSION['captcha']) != strtolower($_POST['captcha']))
                    {
                        $this->show_warning('captcha_failed');
                        return;
                    }
                }
                if (!empty($email) && !is_email($email))
                {
                    $this->show_warning('email_not_correct');
                    return;
                }
                // 匿名发布
                $user_id = empty($hide_name) ? $_SESSION['user_info']['user_id'] : 0;
                $conditions = 'group_id ='.$id;
                $groupbuy_mod = & m('groupbuy');
                $ids = $groupbuy_mod->get(array(
                    'fields' => 'store_id,group_name',
                    'conditions' => $conditions
                ));
                extract($ids);
                $data = array(
                    'question_content' => $content,
                    'type' => 'groupbuy',
                    'item_id' => $id,
                    'item_name' => $group_name,
                    'store_id' => $store_id,
                    'email' => $email,
                    'user_id' => $user_id,
                    'time_post' => gmtime(),
                );
                
                if ($qa_mod->add($data))
                {
                    $this->show_message('question_successful');
                    return;
                }
                else
                {
                    $this->show_warning('post_fail');
                    exit;
                }

            }
            else if (isset($_POST['exit']))
            {
               
                $member_mod = &m('member');
                $member_mod->unlinkRelation('join_groupbuy', $this->_visitor['user_id'], $group['group_id']);
                $this->show_message('exit_groupbuy_successed');
                return;
            }

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

    function _import_resource()
    {
        if(in_array(ACT, array('view')))
        {
            $resource['script'][] = array( // 验证
                'path' => 'jquery.plugins/jquery.validate.js'
            );
        }
        $this->import_resource($resource);
    }

    // 取团购咨询
    function _get_groupbuy_qa($id)
    {
        $page = $this->_get_page(10);
        $groupbuy_qa = & m('goodsqa');
        $qa_info = $groupbuy_qa->find(array(
            'join' => 'belongs_to_user',
            'fields' => 'member.user_name,question_content,reply_content,time_post,time_reply',
            'conditions' => '1 = 1 AND item_id = '.$id . " AND type = 'groupbuy'",
            'limit' => $page['limit'],
            'order' =>'time_post desc',
            'count' => true
        ));
        $page['item_count'] = $groupbuy_qa->getCount();
        $this->_format_page($page);
        if (!empty($_SESSION['user_info']))
        {
            $user_mod = & m('member');
            $user_info = $user_mod->get(array(
                'fields' => 'email',
                'conditions' => '1=1 AND user_id = '.$_SESSION['user_info']['user_id']
            ));
            extract($user_info);
        }
        return array(
            'email' => $email,
            'page_info' => $page,
            'qa_info' => $qa_info,
        );
    }
}

?>
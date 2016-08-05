<?php

class MessageApp extends MemberbaseApp
{
    /**
     *    新短信
     *
     *    @author    Hyber
     *    @return    void
     */
    function newpm()
    {
        $this->_clear_newpm_cache();

        /* 当前位置 */
        $this->_curlocal(LANG::get('member_center'),   'index.php?app=member',
                         LANG::get('message'),         'index.php?app=message&amp;act=newpm',
                         LANG::get('newpm')
                         );

        /* 当前所处子菜单 */
        $this->_curmenu('newpm');
        /* 当前用户中心菜单 */
        $this->_curitem('message');
        $this->import_resource(array(
            'script' => array(
                array(
                    'path' => 'dialog/dialog.js',
                    'attr' => 'id="dialog_js"',
                ),
                array(
                    'path' => 'jquery.ui/jquery.ui.js',
                    'attr' => '',
                ),
                array(
                    'path' => 'jquery.plugins/jquery.validate.js',
                    'attr' => '',
                ),
            ),
            'style' =>  'jquery.ui/themes/ui-lightness/jquery.ui.css',
        ));
        $this->assign('messages', $this->_list_message('newpm', $this->visitor->get('user_id')));
        $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('newpm'));
        $this->display('message.box.html');
    }

    /**
     *    发件箱
     *
     *    @author    Hyber
     *    @return    void
     */
    function privatepm()
    {
        $this->_clear_newpm_cache();

        /* 当前位置 */
        $this->_curlocal(LANG::get('member_center'),   'index.php?app=member',
                         LANG::get('message'),         'index.php?app=message&amp;act=newpm',
                         LANG::get('privatepm')
                         );
        /* 当前所处子菜单 */
        $this->_curmenu('privatepm');
        $this->import_resource(array(
            'script' => array(
                array(
                    'path' => 'dialog/dialog.js',
                    'attr' => 'id="dialog_js"',
                ),
                array(
                    'path' => 'jquery.ui/jquery.ui.js',
                    'attr' => '',
                ),
                array(
                    'path' => 'jquery.plugins/jquery.validate.js',
                    'attr' => '',
                ),
            ),
            'style' =>  'jquery.ui/themes/ui-lightness/jquery.ui.css',
        ));
        /* 当前用户中心菜单 */
        $this->_curitem('message');
        $messages = $this->_list_message('privatepm', $this->visitor->get('user_id'));
        
        $this->assign('messages', $messages);
        $this->_config_seo('title', Lang::get('user_center') . ' - ' . Lang::get('privatepm'));
        $this->display('message.box.html');
    }
    
    function systempm()
    {
        $this->_clear_newpm_cache();

        /* 当前位置 */
        $this->_curlocal(LANG::get('member_center'),   'index.php?app=member',
                         LANG::get('message'),         'index.php?app=message&amp;act=newpm',
                         LANG::get('systempm')
                         );
        /* 当前所处子菜单 */
        $this->_curmenu('systempm');
        $this->import_resource(array(
            'script' => array(
                array(
                    'path' => 'dialog/dialog.js',
                    'attr' => 'id="dialog_js"',
                ),
                array(
                    'path' => 'jquery.ui/jquery.ui.js',
                    'attr' => '',
                ),
                array(
                    'path' => 'jquery.plugins/jquery.validate.js',
                    'attr' => '',
                ),
            ),
            'style' =>  'jquery.ui/themes/ui-lightness/jquery.ui.css',
        ));
        /* 当前用户中心菜单 */
        $this->_curitem('message');
        $this->assign('messages', $this->_list_message('systempm', $this->visitor->get('user_id')));
        $this->_config_seo('title', Lang::get('user_center') . ' - ' . Lang::get('systempm'));
        $this->display('message.box.html');
    }
    
    function announcepm()
    {
        $this->_clear_newpm_cache();

        /* 当前位置 */
        $this->_curlocal(LANG::get('member_center'),   'index.php?app=member',
                         LANG::get('message'),         'index.php?app=message&amp;act=newpm',
                         LANG::get('announcepm')
                         );
        /* 当前所处子菜单 */
        $this->_curmenu('announcepm');
        $this->import_resource(array(
            'script' => array(
                array(
                    'path' => 'dialog/dialog.js',
                    'attr' => 'id="dialog_js"',
                ),
                array(
                    'path' => 'jquery.ui/jquery.ui.js',
                    'attr' => '',
                ),
                array(
                    'path' => 'jquery.plugins/jquery.validate.js',
                    'attr' => '',
                ),
            ),
            'style' =>  'jquery.ui/themes/ui-lightness/jquery.ui.css',
        ));
        /* 当前用户中心菜单 */
        $this->_curitem('message');
        $this->assign('messages', $this->_list_message('announcepm', $this->visitor->get('user_id')));
        $this->_config_seo('title', Lang::get('user_center') . ' - ' . Lang::get('announcepm'));
        $this->display('message.box.html');
    }
    /**
     *    发送短消息
     *
     *    @author    Hyber
     *    @return    void
     */
    function send()
    {

        if (!IS_POST){
            /* 当前位置 */
            $this->_curlocal(LANG::get('member_center'),   'index.php?app=member',
                             LANG::get('message'),         'index.php?app=message&amp;act=newpm',
                             LANG::get('send_message')
                             );
            /* 当前所处子菜单 */
            $this->_curmenu('send_message');
            /* 当前用户中心菜单 */
            $this->_curitem('message');
            $to_ids = array(); //防止foreach报错
            $to_id = trim($_GET['to_id']); //获取url中的to_id
            $to_id && $to_ids = explode(',',$to_id); //转换成数组
            $mod_member = &m('member');
            foreach ($to_ids as $key => $to_id)
            {
                /* 如果用户存在 存入$to_user_name数组中 */
                $user_name = $mod_member->get_info(intval($to_id));
                $user_name && $to_user_name[] = $user_name['user_name'];
            }
            /* 如果用户名存在，赋值给$_GET,方便模板获取 */
            isset($to_user_name) && $_GET['to_user_name'] = implode(',', $to_user_name);

            header('Content-Type:text/html;charset=' . CHARSET);
            /* 好友 */
            $friends = $this->_list_friend();
            $this->assign('friends',        $friends);
            $this->assign('friend_num',    count($friends));

            //引入jquery表单插件
            $this->import_resource(array(
                'script' => 'jquery.plugins/jquery.validate.js',
            ));
            $this->_config_seo('title', Lang::get('user_center') . ' - ' . Lang::get('send_message'));
            $this->display('message.send.html');
        }
        else
        {
            $to_user_name = str_replace(Lang::get('comma'), ',', trim($_POST['to_user_name'])); //替换中文格式的逗号
            if (!$to_user_name)
            {
                $this->show_warning('no_to_user_name'); //没有填写用户名
                return;
            }
            $to_user_names = explode(',', $to_user_name); //将逗号分割的用户名转换成数组
            $mod_member = &m('member');
            $members = $mod_member->find('user_name ' . db_create_in($to_user_names));
            $to_ids = array();
            foreach ($members as $_user)
            {
                if (isset($_user['user_id']) && $_user['user_id']!= $this->visitor->get('user_id'))
                {
                    $to_ids[] = $_user['user_id'];
                }
            }
            if (!$to_ids)
            {
                $this->show_warning('no_user_self'); //没有该用户名
                return;
            }

            /* 连接用户系统 */
            $ms =& ms();
            $msg_id = $ms->pm->send($this->visitor->get('user_id'), $to_ids, '', $_POST['msg_content']);
            if (!$msg_id)
            {
                //$this->show_warning($ms->pm->get_error());
                $rs = $ms->pm->get_error();
                $msg = current($rs);
                $this->show_warning($msg['msg'], 'go_back', 'index.php?app=message&act=send');
                return;
            }
            $this->show_message('send_message_successed', 'go_back', 'index.php?app=message&act=privatepm');
        }
    }

    /**
     *    查看短消息
     *
     *    @author    Hyber
     *    @return    void
     */
    function view()
    {
        $this->_clear_newpm_cache();

        $msg_id = isset($_GET['msg_id']) ? intval($_GET['msg_id']) : 0;
        if (!$msg_id)
        {
            $this->show_warning('no_such_message');
            return;
        }
        $my_id = $this->visitor->get('user_id');
        $ms =& ms();
        if (!IS_POST)
        {
            $message = $ms->pm->get($this->visitor->get('user_id'), $msg_id, true);
            if (empty($message))
            {
                $this->show_warning('no_such_message');
                return;
            };
            $new = $message['topic']['new'];
            !empty($new) && $ms->pm->mark($this->visitor->get('user_id'), array($msg_id), 0); //标示已读
            
            $box = '';
            
            if ($message['topic']['from_id'] == 0 && $message['topic']['to_id'] == 0 )
            {
                $box = 'announcepm';
            }
            elseif ($message['topic']['from_id'] == MSG_SYSTEM)
            {
                $box = 'systempm';
            }
            elseif ($my_id == $message['topic']['from_id'] || $my_id == $message['topic']['to_id'])
            {
                $box = 'privatepm';
            }
            $ms = &ms();
            if ($message['topic']['from_id'] == 0 && $message['topic']['to_id'] == 0)
            {
                $message['topic']['user_name'] = Lang::get('announce_msg');
                $message['topic']['portrait'] = portrait(0, '');
            }
            elseif ($message['topic']['from_id'] == MSG_SYSTEM)
            {
                $message['topic']['user_name'] = Lang::get('system_msg');
                $message['topic']['portrait'] = portrait(0, '');
            }
            else
            {
                $uid = $message['topic']['from_id'];
                $user_info = $ms->user->get($uid);
                $message['topic']['user_name'] = $user_info['user_name'];
                $portrait = portrait($user_info['user_id'], $user_info['portrait']);
                $message['topic']['portrait'] = $portrait;
            }
            
            $uid = 0;
            $user_info = array();
            
            foreach ($message['replies'] as $key => $value)
            {
                $uid = $value['from_id'];
                $user_info = $ms->user->get($uid);
                $message['replies'][$key]['user_name'] = $user_info['user_name'];
                $portrait = portrait($user_info['user_id'], $user_info['portrait']);
                $message['replies'][$key]['portrait'] = $portrait;
            }
            $this->assign('message', $message['topic']);
            $this->assign('replies', $message['replies']);
            $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('view_message'));
            $this->assign('box', $box);
            /* 当前位置 */
            $this->_curlocal(LANG::get('member_center'),   'index.php?app=member',
                             LANG::get('message'),         'index.php?app=message&amp;act=newpm',
                             LANG::get('view_message')
                             );
            /* 当前所处子菜单（必须放在这里，否则新消息数量不正确） */
            $this->_curmenu('view_message');
            /* 当前用户中心菜单 */
            $this->_curitem('message');
            $this->display('message.view.html');
        }
        else
        {
            $message = $ms->pm->get($this->visitor->get('user_id'), $msg_id);
            $reply_to_id = 0;
            if ($my_id == $message['topic']['to_id'])
            {
                $reply_to_id = $message['topic']['from_id'];
            }
            elseif ($my_id == $message['topic']['from_id'])
            {
                $reply_to_id = $message['topic']['to_id'];
            }

            if (empty($reply_to_id) || $reply_to_id == MSG_SYSTEM)
            {
                $this->show_warning('cannot_replay_system_message');
                return;
            }

            $mod_member = &m('member');
            if (!$mod_member->get_info($reply_to_id))
            {
                $this->show_warning('no_such_user');
                return;
            }
            if (!$msg_id = $ms->pm->send($this->visitor->get('user_id'), $reply_to_id, '', $_POST['msg_content'] , $msg_id))  //获取msg_id
            {
                $this->show_warning($ms->pm->get_error());

                return;
            }
            $this->show_message('send_message_successed');
        }
    }

    /**
     *    删除短消息
     *
     *    @author    Hyber
     *    @return    void
     */
    function drop()
    {
        $msg_ids = isset($_GET['msg_id']) ? trim($_GET['msg_id']) : '';
        if(in_array($_GET['back'],array('newpm','privatepm')))
        {
            $folder = trim($_GET['back']);
        }
        if (!$msg_ids)
        {
            $this->show_warning('no_such_message');
            return;
        }
        $msg_ids = explode(',',$msg_ids);
        if (!$msg_ids)
        {
            $this->show_warning('no_such_message');
            return;
        }
        $ms =& ms();
        if (!$ms->pm->drop($this->visitor->get('user_id'), $msg_ids, $folder))    //删除单条消息
        {
            $this->show_warning('drop_error');

            return;
        }

        /* 删除成功返回 */
        if (in_array($_GET['back'],array('newpm', 'privatepm')))
        {
            $this->show_message('drop_message_successed',
                'back_' . $_GET['back'] ,'index.php?app=message&amp;act=' . $_GET['back']);
        }
        else
        {
            $this->show_message('drop_message_successed');
        }
    }
    
    /**
     * 删除与该会员的所有会话（UC的短消息是人与人的关系，不分主题和回复）
     * 
     * @return void
     *
     */
    function drop_relate()
    {
        $msg_ids = isset($_GET['msg_id']) ? trim($_GET['msg_id']) : '';
        if(in_array($_GET['back'],array('newpm', 'privatepm')))
        {
            $folder = trim($_GET['back']);
        }
        if (!$msg_ids)
        {
            $this->show_warning('no_such_message');
            return;
        }
        $msg_id = intval($msg_ids);
        if (!$msg_id)
        {
            $this->show_warning('no_such_message');
            return;
        }
        $ms =& ms();
        if (!$ms->pm->drop($this->visitor->get('user_id'), $msg_id, $folder, true))    //删除
        {
            $this->show_warning($ms->pm->get_error());

            return;
        }

        /* 删除成功返回 */
        if (in_array($_GET['back'],array('newpm', 'privatepm')))
        {
            $this->show_message('drop_message_successed',
                'back_' . $_GET['back'] ,'index.php?app=message&amp;act=' . $_GET['back']);
        }
        else
        {
            $this->show_message('drop_message_successed');
        }
    }

     /**
     *    三级菜单
     *
     *    @author    Hyber
     *    @return    void
     */
    function _get_member_submenu()
    {
        $ms =& ms();
        $new = $ms->pm->check_new($this->visitor->get('user_id'));
        $new && $newpm = "(". $new . ")";
        $menus = array(
                array(
                    'name'  => 'newpm',
                    'url'   => 'index.php?app=message&amp;act=newpm',
                    'text'  => Lang::get('newpm') . $newpm,
                ),
                array(
                    'name'  => 'privatepm',
                    'url'   => 'index.php?app=message&amp;act=privatepm',
                    'text'  => Lang::get('privatepm'),
                ),
                array(
                    'name'  => 'systempm',
                    'url'   => 'index.php?app=message&amp;act=systempm',
                    'text'  => Lang::get('systempm'),
                ),
        );
        if ($ms->pm->show_announce)
        {
            $menus[] = array(
                    'name'  => 'announcepm',
                    'url'   => 'index.php?app=message&amp;act=announcepm',
                    'text'  => Lang::get('announcepm'),
                );
        }

        ACT == 'send' && $menus[] = array(
                'name' => 'send_message',
        );

        ACT == 'view' && $menus[] = array(
                'name' => 'view_message',
        );
        return $menus;
    }

    function _list_message($pattern, $user_id)
    {
        /* 连接用户系统 */
        $user_id = intval($user_id);
        if (!$user_id){
            $this->show_warning('no_such_user');

            return;
        }
        if (!in_array($pattern, array('newpm', 'privatepm', 'announcepm', 'systempm')))
        {
            $this->show_warning('request_error');
            exit;
        }
        $page = $this->_get_page(10);
        $ms =& ms();
        $pms = $ms->pm->get_list($user_id, $page, $pattern);
        $page['item_count'] = $pms['count'];
        $this->_format_page($page);
        $this->assign('page_info', $page);          //将分页信息传递给视图，用于形成分页条
        
        //处理取出的数据
        $my_id = $this->visitor->get('user_id');
        $ms = &ms();
        //$i_send = 0;
        $messages = $pms['data'];
        foreach ($messages as $key=>$message)
        {
            //$i_send = $message['to_id'] == $my_id ? 0 : 1;
            $user_info = $ms->user->get($message['to_id'] == $my_id ? $message['from_id'] : $message['to_id']);
            //$messages[$key]['i_send'] = $i_send;
            if ($message['from_id'] == 0 && $message['to_id'] == 0)
            {
                $user_info['user_name'] = Lang::get('announce_msg');
                $user_info['user_id'] = 0;
                $user_info['portrait'] = '';
            }
            elseif ($message['from_id'] == MSG_SYSTEM) 
            {
                $user_info['user_name'] = Lang::get('system_msg');
                $user_info['user_id'] = 0;
                $user_info['portrait'] = '';
            }
            $user_info['portrait'] = portrait($user_info['user_id'], $user_info['portrait']);
            $messages[$key]['user_info'] = $user_info;
            //$messages[$key]['i_send'] = $i_send;
        }
        return $messages;
    }
    function _list_friend()
    {
        $friends = array();
        $ms =& ms();
        $friends = $ms->friend->get_list($this->visitor->get('user_id'), '0, 10000');

        return $friends;
    }

    function _clear_newpm_cache()
    {
        /* 清除新短消息缓存 */
        $cache_server =& cache_server();
        $cache_server->delete('new_pm_of_user_' . $this->visitor->get('user_id'));
    }
}
?>
<?php

class FriendApp extends MemberbaseApp
{
    /**
     *    好友列表
     *
     *    @author    Hyber
     *    @return    void
     */
    function index()
    {
        /* 当前位置 */
        $this->_curlocal(LANG::get('member_center'),   'index.php?app=member',
                         LANG::get('friend'),         'index.php?app=friend',
                         LANG::get('friend_list')
                         );

        /* 当前所处子菜单 */
        $this->_curmenu('friend_list');
        /* 当前用户中心菜单 */
        $this->_curitem('friend');
        $page = $this->_get_page(10);

        $ms =& ms();
        $friends = $ms->friend->get_list($this->visitor->get('user_id'), $page['limit']);

        $page['item_count'] = $ms->friend->get_count($this->visitor->get('user_id'));   //获取统计的数据
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
        $this->_format_page($page);
        $this->assign('page_info', $page);          //将分页信息传递给视图，用于形成分页条
        $this->assign('friends', $friends);
        $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('friend'));
        $this->display('friend.index.html');
    }

    /**
     *    添加好友
     *
     *    @author    Hyber
     *    @return    void
     */
    function add()
    {
        if (!IS_POST){
            /* 当前位置 */
            $this->_curlocal(LANG::get('member_center'),   'index.php?app=member',
                             LANG::get('friend'),         'index.php?app=friend',
                             LANG::get('add_friend')
                             );
             header('Content-Type:text/html;charset=' . CHARSET);
            /* 当前所处子菜单 */
            $this->_curmenu('add_friend');
            /* 当前用户中心菜单 */
            $this->_curitem('friend');
            $this->display('friend.form.html');
        }
        else
        {
            $user_name = str_replace(Lang::get('comma'), ',', $_POST['user_name']); //替换中文格式的逗号
            if (!$user_name)
            {
                $this->pop_warning('input_username');
                return;
            }
            $user_names = explode(',',$user_name); //将逗号分割的用户名转换成数组
            $mod_member = &m('member');
            $members = $mod_member->find("user_name " . db_create_in($user_names));
            $friend_ids = array_keys($members);
            if (!$friend_ids)
            {
                $this->pop_warning('no_such_user');
                return;
            }

            $ms =& ms();
            $ms->friend->add($this->visitor->get('user_id'), $friend_ids);
            if ($ms->has_error())
            {
                $this->pop_warning($ms->friend->get_error());

                return;
            }
            $this->pop_warning('ok', APP.'_'.ACT);
            /*$this->show_message('add_friend_successed',
                'back_list',    'index.php?app=friend',
                'continue_add', 'index.php?app=friend&amp;act=add'
            );*/
        }
    }

    /**
     *    删除好友
     *
     *    @author    Hyber
     *    @return    void
     */
    function drop()
    {
         $user_ids = isset($_GET['user_id']) ? trim($_GET['user_id']) : '';
        if (!$user_ids)
        {
            $this->show_warning('no_such_friend');
            return;
        }
        $user_ids = explode(',',$user_ids);

        $ms =& ms();
        $result = $ms->friend->drop($this->visitor->get('user_id'), $user_ids);
        if (!$result)    //删除
        {
            $this->show_warning($ms->friend->get_error());

            return;
        }

        /* 删除成功返回 */
        $this->show_message('drop_friend_successed');
    }

     /**
     *    三级菜单
     *
     *    @author    Hyber
     *    @return    void
     */
    function _get_member_submenu()
    {
        return array(
            array(
                'name'  => 'friend_list',
                'url'   => 'index.php?app=friend',
            ),
        );
    }
}
?>
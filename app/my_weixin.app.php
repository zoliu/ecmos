<?php
/* 微信公众平台 */

class My_weixinApp extends MstoreadminbaseApp {

    function __construct() {
        $this->My_weixinApp();
    }

    function My_weixinApp() {
        parent::__construct();
        $this->my_weixin_mod = & m('myweixin');
        $this->member_mod = & m('member');
    }

    function index() {
        $page = $this->_get_page(10);
        $weixin_list     = $this->my_weixin_mod->find(array(
            'conditions'    => 'user_id = ' . $this->visitor->get('user_id'),
            'order'         => 'send_time DESC',
            'count'         => true,
            'limit'         => $page['limit'],
        ));
        $page['item_count'] = $this->my_weixin_mod->getCount();
        $this->_format_page($page);
        $this->assign('page_info', $page);
        $this->assign('weixin_list', $weixin_list);

        /* 当前位置 */
        $this->_curlocal(LANG::get('member_center'),    'index.php?app=member',
                         LANG::get('my_weixin'), 'index.php?app=my_weixin',
                         LANG::get('weixin_list'));

        /* 当前用户中心菜单 */
        $this->_curitem('my_weixin');

        /* 当前所处子菜单 */
        $this->_curmenu('weixin_list');

        $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('my_weixin'));
        
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
                    'path' => 'jquery.ui/i18n/' . i18n_code() . '.js',
                    'attr' => '',
                ),
                array(
                    'path' => 'jquery.plugins/jquery.validate.js',
                    'attr' => '',
                ),
            ),
            'style' =>  'jquery.ui/themes/ui-lightness/jquery.ui.css',
        ));

        $this->display('my_weixin.index.html');
    }
    function send(){
        $user_id=$this->visitor->get('user_id');
        if(!IS_POST){

            /* 当前位置 */
            $this->_curlocal(LANG::get('member_center'),    'index.php?app=member',
                         LANG::get('my_weixin'), 'index.php?app=my_weixin',
                         LANG::get('send_weixin'));

            /* 当前用户中心菜单 */
            $this->_curitem('my_weixin');

            /* 当前所处子菜单 */
            $this->_curmenu('send_weixin');

            $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('my_weixin'));
            $this->display('my_weixin.send.html');
        }else{
            $data['content']=$_POST['content']."&amp;mstore=".$user_id;
            $data['titles']=$_POST['titles'];
            $data['send_time']=gmtime();
            $data['user_id']=$user_id;
            import('wxsend.lib');
            //$biz=new wxsendOrder();
            $content=$data['content'];
            //$biz->send_myweixin($user_id,$content);
            
            if($this->my_weixin_mod->add($data)){
                $this->show_message('send_weixin_successed', 'go_back', 'index.php?app=my_weixin');

            }/*
            var_dump($data);
            */
        }
    }
    /**
     *    查看微信消息
     *
     *    @author    Hyber
     *    @return    void
     */
    function view()
    {
            /* 当前位置 */
            $this->_curlocal(LANG::get('member_center'),    'index.php?app=member',
                         LANG::get('my_weixin'), 'index.php?app=my_weixin',
                         LANG::get('view_weixin'));

            /* 当前用户中心菜单 */
            $this->_curitem('my_weixin');

            /* 当前所处子菜单 */
            $this->_curmenu('view_weixin');

            $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('my_weixin'));
        $id = isset($_GET['id']) ? trim($_GET['id']) : '';
        $weixin=$this->my_weixin_mod->get($id);
        $this->assign("weixin",$weixin);
        $this->display("myweixin.view.html");
    }
    function drop()
    {
        $ids = isset($_GET['id']) ? trim($_GET['id']) : '';
        
        if (!$ids)
        {
            $this->show_warning('no_such_weixin');
            return;
        }
        $ids = explode(',',$ids);
        if (!$ids)
        {
            $this->show_warning('no_such_weixin');
            return;
        }
        if (!$this->my_weixin_mod->drop($ids))   //删除单条消息
        {
            $this->show_warning('drop_error');

            return;
        }
            $this->show_message('drop_weixin_successed','go_back', 'index.php?app=my_weixin');/**/
        
    }/**/
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
                'name'  => 'weixin_list',
                'url'   => 'index.php?app=my_weixin',
            ),
            array(
                'name'  => 'send_weixin',
                'url'   => 'index.php?app=my_weixin&act=send',
            ),
        );
        if ($_GET['id'])
        {
            $menus[] = array(
                'name'  => 'view_weixin',
                'url'   => 'index.php?app=my_weixin&act=view',
            );
        }
        return $menus;
    }

}

?>
<?php

class My_couponApp extends MemberbaseApp 
{
    var $_user_mod;
    var $_store_mod;
    var $_coupon_mod;
    
    function index()
    {
        $page = $this->_get_page(10);
        $this->_user_mod =& m('member');
        $this->_store_mod =& m('store');
        $this->_coupon_mod =& m('coupon');
        $msg = $this->_user_mod->findAll(array(
            'conditions' => 'user_id = ' . $this->visitor->get('user_id'),
            'count' => true,
            'limit' => $page['limit'],
            'include' => array('bind_couponsn' => array())));
        $page['item_count'] = $this->_user_mod->getCount();
        $coupon = array();
        $coupon_ids = array();
        $msg = current($msg);
       if (!empty($msg['coupon_sn']))
       {
           foreach ($msg['coupon_sn'] as $key=>$val)
           {
               $coupon_tmp = $this->_coupon_mod->get(array(
                'fields' => "this.*,store.store_name,store.store_id",
                'conditions' => 'coupon_id = ' . $val['coupon_id'],
                'join' => 'belong_to_store',
                ));
                $coupon_tmp['valid'] = 0;
                $time = gmtime();
                if (($val['remain_times'] > 0) && ($coupon_tmp['end_time'] == 0 || $coupon_tmp['end_time'] > $time))
                {
                    $coupon_tmp['valid'] = 1;
                }
               $coupon[$key] = array_merge($val, $coupon_tmp);
           }
       }
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
        /* 当前位置 */
        $this->_curlocal(LANG::get('member_center'),    'index.php?app=member',
                            LANG::get('my_coupon'), 'index.php?app=my_coupon',
                            LANG::get('coupon_list'));
        $this->_curitem('my_coupon');

       $this->_curmenu('coupon_list');
       $this->assign('page_info', $page);          //将分页信息传递给视图，用于形成分页条
       $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('coupon_list'));
       $this->_format_page($page);
       $this->assign('coupons', $coupon);
       $this->display('my_coupon.index.html');
    }
    
    function bind()
    {
        if (!IS_POST)
        {
            header("Content-Type:text/html;charset=" . CHARSET);
            $this->display('my_coupon.form.html');
        }
        else 
        {
            $coupon_sn = isset($_POST['coupon_sn']) ? trim($_POST['coupon_sn']) : '';
            if (empty($coupon_sn))
            {
                $this->pop_warning('coupon_sn_not_empty');
                exit;
            }
            $coupon_sn_mod =&m ('couponsn');
            $coupon = $coupon_sn_mod->get_info($coupon_sn);
            if (empty($coupon))
            {
                $this->pop_warning('involid_data');
                exit;
            }
            $coupon_sn_mod->createRelation('bind_user', $coupon_sn, $this->visitor->get('user_id'));
            $this->pop_warning('ok', 'my_coupon_bind');
            exit;
        }
    }
    
    function drop()
    {
        if (!isset($_GET['id']) && empty($_GET['id']))
        {
            $this->show_warning("involid_data");
            exit;
        }
        $ids = explode(',', trim($_GET['id']));
        $couponsn_mod =& m('couponsn');
        $couponsn_mod->unlinkRelation('bind_user', db_create_in($ids, 'coupon_sn'));
        if ($couponsn_mod->has_error())
        {
            $this->show_warning($couponsn_mod->get_error());
            exit;
        }
        $this->show_message('drop_ok',
            'back_list', 'index.php?app=my_coupon');
    }
    
    function _get_member_submenu()
    {
        $menus = array(
            array(
                'name'  => 'coupon_list',
                'url'   => 'index.php?app=my_coupon',
            ),
        );
        return $menus;
    }
}

?>
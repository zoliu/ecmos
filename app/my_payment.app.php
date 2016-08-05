<?php

/**
 *
 *    @author    Garbin
 *    @usage    none
 */
class My_paymentApp extends StoreadminbaseApp
{
    function index()
    {
        /* 取得列表数据 */
        $model_payment =& m('payment');

        /* 获取白名单 */
        $white_list    = $model_payment->get_white_list();

        /* 获取白名单过滤后的内置支付方式列表 */
        $payments      = $model_payment->get_builtin($white_list);

        $installed     = $model_payment->get_installed($this->visitor->get('manage_store'));
        foreach ($payments as $key => $value)
        {
            foreach ($installed as $installed_payment)
            {
                if ($installed_payment['payment_code'] == $key)
                {
                    $payments[$key]['payment_desc']     =   $installed_payment['payment_desc'];
                    $payments[$key]['enabled']          =   $installed_payment['enabled'];
                    $payments[$key]['installed']        =   1;
                    $payments[$key]['payment_id']       =   $installed_payment['payment_id'];
                }
            }
        }
        //360cd.cn
        $store_mod= &m('store');
        $store_info = $store_mod->get_info($this->visitor->get('manage_store'));
        if($store_info['is_open_pay']){
             $this->assign('is_open_pay',1);
             
        }
        //360cd.cn
        $this->assign('payments', $payments);
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
          ),
          'style' =>  'jquery.ui/themes/ui-lightness/jquery.ui.css,res:jqtreetable.css',
        ));

        /* 当前位置 */
        $this->_curlocal(LANG::get('member_center'),    'index.php?app=member',
                         LANG::get('my_payment'), 'index.php?app=my_payment',
                         LANG::get('payment_list'));

        /* 当前用户中心菜单 */
        $this->_curitem('my_payment');

        /* 当前所处子菜单 */
        $this->_curmenu('payment_list');

        $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('my_payment'));
        header("Content-Type:text/html;charset=" . CHARSET);
        $this->display('my_payment.index.html');
    }

    /**
     *    安装支付方式
     *
     *    @author    Garbin
     *    @return    void
     */
    function install()
    {
        $code = isset($_GET['code']) ? trim($_GET['code']) : 0;
        if (!$code)
        {
            echo Lang::get('no_such_payment');

            return;
        }
        $model_payment =& m('payment');
        $payment       = $model_payment->get_builtin_info($code);
        if (!$payment)
        {
            echo Lang::get('no_such_payment');

            return;
        }
        $payment_info = $model_payment->get("store_id=" . $this->visitor->get('manage_store') . " AND payment_code='{$code}'");
        if (!empty($payment_info))
        {
            echo Lang::get('already_installed');

            return;
        }
        if (!IS_POST)
        {
            /* 当前位置 */
            $this->_curlocal(LANG::get('member_center'),    'index.php?app=member',
                             LANG::get('my_payment'), 'index.php?app=my_payment',
                             LANG::get('payment_list'));

            /* 当前用户中心菜单 */
            $this->_curitem('my_payment');

            /* 当前所处子菜单 */
            $this->_curmenu('install_payment');

            /* 默认启用 */
            $payment['enabled'] = 1;

            $this->assign('yes_or_no', array(Lang::get('no'), Lang::get('yes')));
            $this->assign('payment', $payment);
            $this->_config_seo('title', Lang::get('member_center') . Lang::get('my_payment'));
            header("Content-Type:text/html;charset=" . CHARSET);
            $this->display('my_payment.form.html');
        }
        else
        {
            $data = array(
                'store_id'      => $this->visitor->get('manage_store'),
                'payment_name'  => $payment['name'],
                'payment_code'  => $code,
                'payment_desc'  => $_POST['payment_desc'],
                'config'        => $_POST['config'],
                'is_online'     => $payment['is_online'],
                'enabled'       => $_POST['enabled'],
                'sort_order'    => $_POST['sort_order'],
            );
            if (!($payment_id = $model_payment->install($data)))
            {
                //$this->show_warning($model_payment->get_error());
                $msg = $model_payment->get_error();
                $this->pop_warning($msg['msg']);
                return;
            }
            $this->pop_warning('ok', 'my_payment_install');
        }
    }

    function config()
    {
        $payment_id =   isset($_GET['payment_id']) ? intval($_GET['payment_id']) : 0;
        if (!$payment_id)
        {
            echo Lang::get('no_such_payment');

            return;
        }
        $model_payment =& m('payment');
        $payment_info  = $model_payment->get("store_id = " . $this->visitor->get('manage_store') . " AND payment_id={$payment_id}");
        if (!$payment_info)
        {
            echo Lang::get('no_such_payment');

            return;
        }
        $payment = $model_payment->get_builtin_info($payment_info['payment_code']);
        if (!$payment)
        {
            echo Lang::get('no_such_payment');

            return;
        }

        if (!IS_POST)
        {
            /* 当前位置 */
            $this->_curlocal(LANG::get('member_center'),    'index.php?app=member',
                             LANG::get('my_payment'), 'index.php?app=my_payment',
                             LANG::get('payment_list'));

            /* 当前用户中心菜单 */
            $this->_curitem('my_payment');

            /* 当前所处子菜单 */
            $this->_curmenu('install_payment');
            $payment['payment_id']  =   $payment_info['payment_id'];
            $payment['payment_desc']=   $payment_info['payment_desc'];
            $payment['enabled']     =   $payment_info['enabled'];
            $payment['sort_order']  =   $payment_info['sort_order'];
            $this->assign('yes_or_no', array(Lang::get('no'), Lang::get('yes')));
            $this->assign('config', unserialize($payment_info['config']));
            $this->assign('payment', $payment);
            $this->_config_seo('title', Lang::get('member_center') . Lang::get('my_payment'));
            header("Content-Type:text/html;charset=" . CHARSET);
            $this->display('my_payment.form.html');
        }
        else
        {
            $data = array(
                'payment_desc'  =>  $_POST['payment_desc'],
                'config'        =>  $_POST['config'],
                'enabled'       =>  $_POST['enabled'],
                'sort_order'    =>  $_POST['sort_order'],
            );
            $model_payment->edit("store_id =" . $this->visitor->get('manage_store') . " AND payment_id={$payment_id}", $data);
            if ($model_payment->has_error())
            {
                //$this->show_warning($model_payment->get_error());
                $msg = $model_payment->get_error();
                $this->pop_warning($msg['msg']);
                return;
            }
            $this->pop_warning('ok', 'my_payment_config');
            //$this->show_message('config_payment_successed');
        }
    }

    function uninstall()
    {
        $payment_id = isset($_GET['payment_id']) ? intval($_GET['payment_id']) : 0;
        if (!$payment_id)
        {
            $this->show_warning('no_such_payment');

            return;
        }

        $model_payment =& m('payment');
        $model_payment->uninstall($this->visitor->get('manage_store'), $payment_id);
        if ($model_payment->has_error())
        {
            $this->show_warning($model_payment->get_error());

            return;
        }

        $this->show_message('uninstall_payment_successed');
    }


    /**
     *    三级菜单
     *
     *    @author    Garbin
     *    @return    void
     */
    function _get_member_submenu()
    {
        $arr = array(
            array(
                'name'  => 'payment_list',
                'url'   => 'index.php?app=my_payment',
            ),
            array(
                'name'  => 'install_payment',
                'url'   => 'javascript:;',
            ),
        );
        if (ACT == 'index')
        {
            unset($arr[1]);
        }

        return $arr;
    }
}

?>
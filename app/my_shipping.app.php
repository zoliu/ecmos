<?php

/**
 *    我的收货地址控制器
 *
 *    @author    Garbin
 *    @usage    none
 */
class My_shippingApp extends StoreadminbaseApp
{
    //360cd.cn
        function __construct()
        {
            $this->My_shippingApp();
        }
        function My_shippingApp()
        {
            parent::__construct();
            $this->assign('options_shipping',$this->_get_shipping());

        }
        //360cd.cn
        function _get_shipping()
        {
            $shipping= &m('shipping_ext');
            return $shipping->get_shipping_list();
        }
         //360cd.cn
    function index()
    {
        /* 取得列表数据 */
        $model_shipping =& m('shipping');
        $shippings     = $model_shipping->find(array(
            'conditions'    => 'store_id = ' . $this->visitor->get('manage_store'),
        ));
        $this->assign('shippings', $shippings);

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
                   array(
                      'path' => 'mlselection.js',
                      'attr' => '',
                   ),
          ),
          'style' =>  'jquery.ui/themes/ui-lightness/jquery.ui.css,res:jqtreetable.css',
        ));

        /* 当前位置 */
        $this->_curlocal(LANG::get('member_center'),    'index.php?app=member',
                         LANG::get('my_shipping'), 'index.php?app=my_shipping',
                         LANG::get('shipping_list'));

        /* 当前用户中心菜单 */
        $this->_curitem('my_shipping');

        /* 当前所处子菜单 */
        $this->_curmenu('shipping_list');

        $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('my_shipping'));
        header("Content-Type:text/html;charset=" . CHARSET);
        $this->display('my_shipping.index.html');
    }

    function add()
    {
        if (!IS_POST)
        {
            /* 当前位置 */
            $this->_curlocal(LANG::get('member_center'),    'index.php?app=member',
                             LANG::get('my_shipping'), 'index.php?app=my_shipping',
                             LANG::get('add_shipping'));

            /* 当前用户中心菜单 */
            $this->_curitem('my_shipping');

            /* 当前所处子菜单 */
            $this->_curmenu('add_shipping');
            $this->_assign_form();
            $this->_get_regions();
            $this->assign('cod_regions', array());
            //$this->import_resource('mlselection.js, jquery.plugins/jquery.validate.js');
            header("Content-Type:text/html;charset=" . CHARSET);
            $this->display('my_shipping.form.html');
        }
        else
        {
            $data = array(
                'store_id'      => $this->visitor->get('manage_store'),
                'shipping_name' => $_POST['shipping_name'],
                'shipping_desc' => $_POST['shipping_desc'],
                'first_price'   => $_POST['first_price'],
                'step_price'    => $_POST['step_price'],
                'enabled'       => $_POST['enabled'],
                'sort_order'    => $_POST['sort_order'],
            );
            if (!empty($_POST['cod_regions']))
            {
                $data['cod_regions']    =   serialize($_POST['cod_regions']);
            }
            $model_shipping =& m('shipping');
            if (!($shipping_id = $model_shipping->add($data)))
            {
                //$this->show_warning($model_shipping->get_error());
                $this->pop_warning($model_shipping->get_error());
                return;
            }
            $this->pop_warning('ok', 'my_shipping_add');
        }
    }

    /**
     *    编辑配送方式
     *
     *    @author    Garbin
     *    @return    void
     */
    function edit()
    {
        $shipping_id = isset($_GET['shipping_id']) ? intval($_GET['shipping_id']) : 0;
        if (!$shipping_id)
        {
            echo Lang::get('no_such_shipping');

            return;
        }

        /* 判断是否是自己的 */
        $model_shipping =& m('shipping');
        $shipping = $model_shipping->get("store_id=" . $this->visitor->get('manage_store') . " AND shipping_id={$shipping_id}");
        if (!$shipping)
        {
            echo Lang::get('no_such_shipping');

            return;
        }
        if (!IS_POST)
        {
            /* 当前位置 */
            $this->_curlocal(LANG::get('member_center'),    'index.php?app=member',
                             LANG::get('my_shipping'), 'index.php?app=my_shipping',
                             LANG::get('edit_shipping'));

            /* 当前用户中心菜单 */
            $this->_curitem('my_shipping');

            /* 当前所处子菜单 */
            $this->_curmenu('edit_shipping');

            $this->_get_regions();

            $cod_regions = unserialize($shipping['cod_regions']);
            !$cod_regions && $cod_regions = array();

            $this->assign('shipping', $shipping);
            $this->assign('cod_regions', $cod_regions);
            $this->assign('yes_or_no', array(1 => Lang::get('yes'), 0 => Lang::get('no')));
            $this->import_resource('mlselection.js, jquery.plugins/jquery.validate.js');
            header("Content-Type:text/html;charset=" . CHARSET);
            $this->display('my_shipping.form.html');
        }
        else
        {
            $data = array(
                'shipping_name' => $_POST['shipping_name'],
                'shipping_desc' => $_POST['shipping_desc'],
                'first_price'   => $_POST['first_price'],
                'step_price'    => $_POST['step_price'],
                'enabled'       => $_POST['enabled'],
                'sort_order'    => $_POST['sort_order'],
            );
            $cod_regions = empty($_POST['cod_regions']) ? array() : $_POST['cod_regions'];
            $data['cod_regions']    =   serialize($cod_regions);
            $model_shipping =& m('shipping');
            $model_shipping->edit($shipping_id, $data);
            if ($model_shipping->has_error())
            {
                //$this->show_warning($model_shipping->get_error());
                $msg = $model_shipping->get_error();
                $this->pop_warning($msg['msg']);
                return;
            }
            $this->pop_warning('ok', 'my_shipping_edit');
        }
    }

    /**
     *    删除配送方式
     *
     *    @author    Garbin
     *    @param    none
     *    @return    void
     */
    function drop()
    {
        $shipping_id = isset($_GET['shipping_id']) ? trim($_GET['shipping_id']) : 0;
        if (!$shipping_id)
        {
            $this->show_warning('no_such_shipping');

            return;
        }
        $ids = explode(',', $shipping_id);//获取一个类似array(1, 2, 3)的数组
        $model_shipping  =& m('shipping');
        $drop_count = $model_shipping->drop("store_id = " . $this->visitor->get('manage_store') . " AND shipping_id " . db_create_in($ids));
        if (!$drop_count)
        {
            /* 没有可删除的项 */
            $this->show_warning('no_such_shipping');

            return;
        }

        if ($model_shipping->has_error())    //出错了
        {
            $this->show_warning($model_shipping->get_error());

            return;
        }

        $this->show_message('drop_shipping_successed');
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
                'name'  => 'shipping_list',
                'url'   => 'index.php?app=my_shipping',
            ),
/*            array(
                'name'  => 'add_shipping',
                'url'   => 'index.php?app=my_shipping&act=add',
            ),*/
        );
        if (ACT == 'edit')
        {
            $menus[] = array(
                'name'  => 'edit_shipping',
            );
        }
        return $menus;
    }
    function _get_regions()
    {
        $model_region =& m('region');
        $regions = $model_region->get_list(0);
        if ($regions)
        {
            $tmp  = array();
            foreach ($regions as $key => $value)
            {
                $tmp[$key] = $value['region_name'];
            }
            $regions = $tmp;
        }
        $this->assign('regions', $regions);
    }
    function _assign_form()
    {
        /*赋初始值*/
        $shipping = array(
            'enabled'       => 1,
            'sort_order'    => 255,
        );
        $yes_or_no = array(
            1 => Lang::get('yes'),
            0 => Lang::get('no'),
        );
        $this->assign('yes_or_no', $yes_or_no);
        $this->assign('shipping' , $shipping);
    }
}

?>
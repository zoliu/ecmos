<?php

/**
 *    我的收货地址控制器
 *
 *    @author    Garbin
 *    @usage    none
 */
class My_addressApp extends MemberbaseApp
{
    function index()
    {
        /* 取得列表数据 */
        $model_address =& m('address');
        $addresses     = $model_address->find(array(
            'conditions'    => 'user_id = ' . $this->visitor->get('user_id'),
        ));
        $this->assign('addresses', $addresses);

        /* 当前位置 */
        $this->_curlocal(LANG::get('member_center'),    'index.php?app=member',
                         LANG::get('my_address'), 'index.php?app=my_address',
                         LANG::get('address_list'));

        /* 当前用户中心菜单 */
        $this->_curitem('my_address');

        /* 当前所处子菜单 */
        $this->_curmenu('address_list');

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
            'style' =>  'jquery.ui/themes/ui-lightness/jquery.ui.css',
        ));
        $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('my_address'));
        $this->display('my_address.index.html');
    }

    /**
     *    添加地址
     *
     *    @author    Garbin
     *    @return    void
     */
    function add()
    {
        if (!IS_POST)
        {
            /* 当前位置 */
            /*$this->_curlocal(LANG::get('member_center'),    'index.php?app=member',
                             LANG::get('my_address'), 'index.php?app=my_address',
                             LANG::get('add_address'));*/
            //$this->import_resource('mlselection.js, jquery.plugins/jquery.validate.js');
            $this->assign('act', 'add');
            $this->_get_regions();
            header('Content-Type:text/html;charset=' . CHARSET);
            $this->display('my_address.form.html');
        }
        else
        {
            /* 电话和手机至少填一项 */
            if (!$_POST['phone_tel'] && !$_POST['phone_mob'])
            {
                $this->pop_warning('phone_required');

                return;
            }

            $region_name = $_POST['region_name'];
            $data = array(
                'user_id'       => $this->visitor->get('user_id'),
                'consignee'     => $_POST['consignee'],
                'region_id'     => $_POST['region_id'],
                'region_name'   => $_POST['region_name'],
                'address'       => $_POST['address'],
                'zipcode'       => $_POST['zipcode'],
                'phone_tel'     => $_POST['phone_tel'],
                'phone_mob'     => $_POST['phone_mob'],
                'set_default'       => $_POST['set_default'],
            );
            $model_address =& m('address');
            if (!($address_id = $model_address->add($data)))
            {
                $this->pop_warning($model_address->get_error());

                return;
            }
            $this->pop_warning('ok', APP.'_'.ACT);
        }
    }
    function edit()
    {
        $addr_id = empty($_GET['addr_id']) ? 0 : intval($_GET['addr_id']);
        if (!$addr_id)
        {
            echo Lang::get("no_such_address");
            return;
        }
        if (!IS_POST)
        {
            $model_address =& m('address');
            $find_data     = $model_address->find("addr_id = {$addr_id} AND user_id=" . $this->visitor->get('user_id'));
            if (empty($find_data))
            {
                echo Lang::get('no_such_address');

                return;
            }
            $address = current($find_data);

            /* 当前位置 */
            $this->_curlocal(LANG::get('member_center'),    'index.php?app=member',
                             LANG::get('my_address'), 'index.php?app=my_address',
                             LANG::get('edit_address'));

            /* 当前用户中心菜单 */
            /*$this->_curitem('my_address');

            
            /* 当前所处子菜单 */
            header('Content-Type:text/html;charset=' . CHARSET);
            $this->_curmenu('edit_address');

            $this->assign('address', $address);
            //$this->import_resource('mlselection.js, jquery.plugins/jquery.validate.js');
            $this->assign('act', 'edit');
            $this->_get_regions();
            $this->display('my_address.form.html');
        }
        else
        {
            /* 电话和手机至少填一项 */
            if (!$_POST['phone_tel'] && !$_POST['phone_mob'])
            {
                $this->pop_warning('phone_required');

                return;
            }
            $data = array(
                'consignee'     => $_POST['consignee'],
                'region_id'     => $_POST['region_id'],
                'region_name'   => $_POST['region_name'],
                'address'       => $_POST['address'],
                'zipcode'       => $_POST['zipcode'],
                'phone_tel'     => $_POST['phone_tel'],
                'phone_mob'     => $_POST['phone_mob'],
                'set_default'       => $_POST['set_default'],
            );
            $model_address =& m('address');
            $model_address->edit("addr_id = {$addr_id} AND user_id=" . $this->visitor->get('user_id'), $data);
            if ($model_address->has_error())
            {
                $this->pop_warning($model_address->get_error());

                return;
            }
            $this->pop_warning('ok', APP.'_'.ACT);
        }
    }
    function drop()
    {
        $addr_id = isset($_GET['addr_id']) ? trim($_GET['addr_id']) : 0;
        if (!$addr_id)
        {
            $this->show_warning('no_such_address');

            return;
        }
        $ids = explode(',', $addr_id);//获取一个类似array(1, 2, 3)的数组
        $model_address  =& m('address');
        $drop_count = $model_address->drop("user_id = " . $this->visitor->get('user_id') . " AND addr_id " . db_create_in($ids));
        if (!$drop_count)
        {
            /* 没有可删除的项 */
            $this->show_warning('no_such_address');

            return;
        }

        if ($model_address->has_error())    //出错了
        {
            $this->show_warning($model_address->get_error());

            return;
        }

        $this->show_message('drop_address_successed');
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
    function set_default(){
        $model_address =& m('address');
        $user_id=$this->visitor->get('user_id');
        $addr_id=isset($_GET['addr_id'])?intval($_GET['addr_id']):0;
        if (!$addr_id) {
            echo ecm_json_encode(false);
            return;
        }

        $address   = $model_address->get('set_default=1 and user_id = ' . $user_id);
        if ($address) {
            $model_address->edit("addr_id = {$address['addr_id']} AND user_id=" . $user_id,array('set_default'=>0));
        }

        $relust=$model_address->edit("addr_id = {$addr_id} AND user_id=" . $user_id,array('set_default'=>1));

        echo ecm_json_encode($relust);
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
                'name'  => 'address_list',
                'url'   => 'index.php?app=my_address',
            ),
/*            array(
                'name'  => 'add_address',
                'url'   => 'index.php?app=my_address&act=add',
            ),*/
        );
/*        if (ACT == 'edit')
        {
            $menus[] = array(
                'name' => 'edit_address',
                'url'  => '',
            );
        }*/
        return $menus;
    }
}

?>
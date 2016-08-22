<?php
class Point_logsApp extends MemberbaseApp
{
    public function index()
    {
        $user_id = $this->visitor->get('user_id');

        $conditions = $this->_get_query_conditions(array(
            array(
                'field'   => 'addtime',
                'name'    => 'add_time_from',
                'equal'   => '>=',
                'handler' => 'gmstr2time',
            ), array(
                'field'   => 'addtime',
                'name'    => 'add_time_to',
                'equal'   => '<=',
                'handler' => 'gmstr2time_end',
            ), array(
                'field' => 'type', //可搜索字段title
                'equal' => '=', //等价关系,可以是LIKE, =, <, >, <>
                'assoc' => 'AND', //关系类型,可以是AND, OR
                'name'  => 'point_type', //GET的值的访问键名
                'type'  => 'string', //GET的值的类型
            ),

        ));
        $page  = $this->_get_page(10);
        $_mod  = &m("point_logs");
        $index = $_mod->find(array(
            'conditions' => 'user_id=' . $this->visitor->get('user_id') . $conditions,
            'limit'      => $page['limit'],
            'order'      => ' id desc',
            'count'      => true));

        $page['item_count'] = $_mod->getCount();
        $this->_format_page($page);
        $type_list = $_mod->getTypeList();
        $this->assign('options_type', $type_list);
        $this->assign('search_options', $search_options);
        $this->assign('filtered', $conditions ? 1 : 0); //是否有查询条件
        $this->assign('page_info', $page);
        $this->assign('data', $index);

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
            'style'  => 'jquery.ui/themes/ui-lightness/jquery.ui.css',
        ));

        // 当前位置 
        $this->_curlocal(LANG::get('member_center'), 'index.php?app=member',
            Lang::get('point_logs'), 'index.php?app=point_logs',
            Lang::get('point_logs_index')
        );

        // 当前用户中心菜单 
        $this->_curitem('point_logs');
        $this->_curmenu('point_logs_index');
        $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('point_logs_index'));

        $this->display('point_logs.index.html');
    }

    public function point_goods()
    { 
        $page       = $this->_get_page();
        $conditions = '';
        $conditions .= $this->_get_query_conditions(array(
            array(
                'field' => 'user_name', //可搜索字段title
                'equal' => 'LIKE', //等价关系,可以是LIKE, =, <, >, <>
                'assoc' => 'AND', //关系类型,可以是AND, OR
                'name'  => 'user_name', //GET的值的访问键名
                'type'  => 'string', //GET的值的类型
            ), array(
                'field' => 'goods_name', //可搜索字段title
                'equal' => 'LIKE', //等价关系,可以是LIKE, =, <, >, <>
                'assoc' => 'AND', //关系类型,可以是AND, OR
                'name'  => 'goods_name', //GET的值的访问键名
                'type'  => 'string', //GET的值的类型
            ), array(
                'field' => 'status', //可搜索字段title
                'equal' => '=', //等价关系,可以是LIKE, =, <, >, <>
                'assoc' => 'AND', //关系类型,可以是AND, OR
                'name'  => 'point_type', //GET的值的访问键名
                'type'  => 'string', //GET的值的类型
            ),
        ));
        $logs_mod = &m('point_goods_log');
        $data     = $logs_mod->find(array(
            'conditions' => '1=1 and ' . 'user_id=' . $this->visitor->get('user_id') . $conditions,
            'limit'      => $page['limit'],
            'join'       => 'belong_to_point_goods',
            'order'      => ' point_goods_log.id desc',
            'count'      => true));
        $type_list = $logs_mod->getTypeList();
        $this->assign('options_type', $type_list);
        $this->assign('data', $data);
        $page['item_count'] = $logs_mod->getCount();
        $this->_format_page($page);
        $this->assign('page_info', $page);

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
            'style'  => 'jquery.ui/themes/ui-lightness/jquery.ui.css',
        ));

        // 当前位置 
        $this->_curlocal(LANG::get('member_center'), 'index.php?app=member',
            Lang::get('point_logs'), 'index.php?app=point_logs',
            Lang::get('point_logs_point_goods')
        );

        // 当前用户中心菜单 
        $this->_curitem('point_logs');
        $this->_curmenu('point_logs_point_goods');
        $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('point_logs_point_goods'));


        $this->display('point_goods.index.html');
    }

    public function _get_member_submenu()
    {
        $menus = array(
            array(
                'name' => 'point_logs_index',
                'url'  => '?app=point_logs',
            ),
            array(
                'name' => 'point_logs_point_goods',
                'url'  => '?app=point_logs&act=point_goods',
            ),
        );

        return $menus;
    }
}

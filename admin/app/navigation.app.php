<?php

/**
 *    页面导航控制器
 *
 *    @author    Hyber
 *    @usage    none
 */
class NavigationApp extends BackendApp
{
    var $_navi_mod;

    function __construct()
    {
        $this->NavigationApp();
    }

    function NavigationApp()
    {
        parent::BackendApp();

        $this->_navi_mod =& m('navigation');
    }

    /**
     *    页面导航索引
     *
     *    @author    Hyber
     *    @return    void
     */
    function index()
    {
        $conditions = $this->_get_query_conditions(array(
            array(
                'field' => 'title',         //可搜索字段title
                'equal' => 'LIKE',          //等价关系,可以是LIKE, =, <, >, <>
                'assoc' => 'AND',           //关系类型,可以是AND, OR
                'name'  => 'title',         //GET的值的访问键名
                'type'  => 'string',        //GET的值的类型
            ),
            array(
                'field' => 'type',
            ),
        ));
        $page   =   $this->_get_page(10);   //获取分页信息
        $navigations=$this->_navi_mod->find(array(
        'conditions'  => '1=1' . $conditions,
        'limit'   =>$page['limit'],
        'order'   => 'type ASC,sort_order ASC',
        'count'   => true   //允许统计
        ));
        $page['item_count']=$this->_navi_mod->getCount();   //获取统计数据
        $open_new = array(
           '0' => Lang::get('no'),
           '1' => Lang::get('yes'),
        );
        $types = array(
            'header' => Lang::get('header'),
            'middle' => Lang::get('middle'),
            'footer' => Lang::get('footer'),
        );
        foreach ($navigations as $key => $navigation){
            $navigations[$key]['open_new'] = $open_new[$navigation['open_new']];
            $navigations[$key]['type'] = $types[$navigation['type']];
        }
        $this->_format_page($page);
        $this->assign('filtered', $conditions? 1 : 0); //是否有查询条件
        $this->_assign_form();
        $this->assign('page_info', $page);   //将分页信息传递给视图，用于形成分页条
        $this->import_resource(array('script' => 'inline_edit.js'));
        $this->assign('navigations', $navigations);
        $this->display('navigation.index.html');
    }
     /**
     *    新增页面导航
     *
     *    @author    Hyber
     *    @return    void
     */
    function add()
    {
        if (!IS_POST)
        {
            /* 显示新增表单 */
            $model_acategory = &m('acategory');
            $navigation = array('type' => 'header', 'sort_order' => 255, 'link' => 'http://');
            $this->_assign_form();
            $this->import_resource(array('script' => 'mlselection.js,jquery.plugins/jquery.validate.js'));
            $this->assign('gcategory_options', $this->_get_gcategory_options()); //商品分类树
            $this->assign('acategory_options', $this->_get_acategory_options()); //文章分类树
            $this->import_resource(array('script' => 'jquery.plugins/jquery.validate.js,mlselection.js'));
            $this->assign('navigation', $navigation);
            $this->display('navigation.form.html');
        }
        else
        {
            $data = array();
            /* 当导航数据来自商品或文章分类时，将cate_id拼成连接 */
            $_POST['gcategory_cate_id'] && $_POST['link'] = 'index.php?app=search&cate_id='. $_POST['gcategory_cate_id'];
            $_POST['acategory_cate_id'] && $_POST['link'] = 'index.php?app=article&cate_id='. $_POST['acategory_cate_id'];

            $data['title']      =   $_POST['title'];
            $data['type']      =   $_POST['type'];
            $data['link']      =   $_POST['link'];
            $data['open_new']      =   $_POST['open_new'];
            $data['sort_order'] =   $_POST['sort_order'];

            if (!$nav_id = $this->_navi_mod->add($data))  //获取nav_id
            {
                $this->show_warning($this->_navi_mod->get_error());

                return;
            }

            $this->_clear_cache();
            $this->show_message('add_navigation_successed',
                'back_list',    'index.php?app=navigation',
                'continue_add', 'index.php?app=navigation&amp;act=add'
            );
        }
    }
     /**
     *    编辑商品品牌
     *
     *    @author    Hyber
     *    @return    void
     */
    function edit()
    {
        $nav_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if (!$nav_id)
        {
            $this->show_warning('no_such_navigation');
            return;
        }
         if (!IS_POST)
        {
            $find_data     = $this->_navi_mod->find($nav_id);
            if (empty($find_data))
            {
                $this->show_warning('no_such_navigation');

                return;
            }
            $navigation    =   current($find_data);
            //$navigation['link'] = !preg_match("/^http(s)?:\/\//i", $navigation['link']) ? SITE_URL . '/' . $navigation['link'] : $navigation['link'];
            $this->_assign_form();
            $this->assign('gcategory_options', $this->_get_gcategory_options()); //商品分类树
            $this->assign('acategory_options', $this->_get_acategory_options()); //文章分类树
            $this->import_resource(array('script' => 'jquery.plugins/jquery.validate.js,mlselection.js'));
            $this->assign('navigation', $navigation);
            $this->display('navigation.form.html');
        }
        else
        {
            $data = array();
            /* 当导航数据来自商品或文章分类时，将cate_id拼成连接 */
            $_POST['gcategory_cate_id'] && $_POST['link'] = 'index.php?app=search&cate_id='. $_POST['gcategory_cate_id'];
            $_POST['acategory_cate_id'] && $_POST['link'] = 'index.php?app=article&cate_id='. $_POST['acategory_cate_id'];

            $data['title']      =   $_POST['title'];
            $data['type']      =   $_POST['type'];
            $data['link']      =   $_POST['link'];
            $data['open_new']      =   $_POST['open_new'];
            $data['sort_order'] =   $_POST['sort_order'];

            $rows=$this->_navi_mod->edit($nav_id, $data);
            if ($this->_navi_mod->has_error())
            {
                $this->show_warning($this->_navi_mod->get_error());

                return;
            }

            $this->_clear_cache();
            $this->show_message('edit_navigation_successed',
                'back_list',        'index.php?app=navigation',
                'edit_again',    'index.php?app=navigation&amp;act=edit&amp;id=' . $nav_id);
        }
    }

     //异步修改数据
   function ajax_col()
   {
       $id     = empty($_GET['id']) ? 0 : intval($_GET['id']);
       $column = empty($_GET['column']) ? '' : trim($_GET['column']);
       $value  = isset($_GET['value']) ? trim($_GET['value']) : '';
       $data   = array();

       if (in_array($column ,array('title', 'sort_order')))
       {
           $data[$column] = $value;
           $this->_navi_mod->edit($id, $data);
           if(!$this->_navi_mod->has_error())
           {
               $this->_clear_cache();
               echo ecm_json_encode(true);
           }
       }
       else
       {
           return ;
       }
       return ;
   }

    function drop()
    {
        $nav_ids = isset($_GET['id']) ? trim($_GET['id']) : '';
        if (!$nav_ids)
        {
            $this->show_warning('no_such_navigation');

            return;
        }
        $nav_ids=explode(',',$nav_ids);
        if (!$this->_navi_mod->drop($nav_ids))    //删除
        {
            $this->show_warning($this->_navi_mod->get_error());

            return;
        }

        $this->_clear_cache();
        $this->show_message('drop_navigation_successed');
    }

    /* 更新排序 */
    function update_order()
    {
        if (empty($_GET['id']))
        {
            $this->show_warning('Hacking Attempt');
            return;
        }

        $ids = explode(',', $_GET['id']);
        $sort_orders = explode(',', $_GET['sort_order']);
        foreach ($ids as $key => $id)
        {
            $this->_navi_mod->edit($id, array('sort_order' => $sort_orders[$key]));
        }

        $this->show_message('update_order_ok');
    }

            /* 构造并返回树 */
    function &_tree($acategories)
    {
        import('tree.lib');
        $tree = new Tree();
        $tree->setTree($acategories, 'cate_id', 'parent_id', 'cate_name');
        return $tree;
    }
        /* 取得所有文章分类数据 */
    function _get_acategory_options()
    {
        $mod_acategory = &m('acategory');
        $acategorys = $mod_acategory->get_list();

        /* 去掉系统内置文章分类 */
        $system_cate_id = $mod_acategory->get_ACC(ACC_SYSTEM);
        unset($acategorys[$system_cate_id]);

        $tree =& $this->_tree($acategorys);
        return $tree->getOptions();
    }
        /* 取得商城的商品分类数据 */
    function _get_gcategory_options($parent_id = 0)
    {
        $mod_gcategory = &bm('gcategory');
        $gcategories = $mod_gcategory->get_list($parent_id, true);
        foreach ($gcategories as $gcategory)
        {
            $res[$gcategory['cate_id']] = $gcategory['cate_name'];
        }
        return $res;
    }

    /* 表单赋值 */
    function _assign_form()
    {
        $type = array(
            'header' => Lang::get('header'),
            'middle' => Lang::get('middle'),
            'footer' => Lang::get('footer'),
        );
        $open_new = array(
           '0' => Lang::get('no'),
           '1' => Lang::get('yes'),
        );
        $this->assign('type', $type);
        $this->assign('open_new', $open_new);
    }
}

?>
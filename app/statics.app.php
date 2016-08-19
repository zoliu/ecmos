<?php
/**
 *    导航管理控制器
 *
 *    @author    Garbin
 *    @usage    none
 */
class StaticsApp extends StoreadminbaseApp {
    var $_statics_mod;
    function __construct() {
        $this->StaticsApp();
    }
    function StaticsApp() {
        parent::__construct();
        $this->_statics_mod = & m('statics');
        $this->_store_id = intval($this->visitor->get('manage_store'));
        $this->_user_id = intval($this->visitor->get('user_id'));
        $options = array(
            'stype' => $this->_statics_mod->get_options_stype() ,
        );
        $this->assign('options', $options);
    }
    function index() {
        $conditions = '';
        if ($_GET['stype'] == - 1) {
            unset($_GET['stype']);
        }
        $conditions.= $this->_get_query_conditions(array(
            array(
                'field' => 'stype', //可搜索字段title
                'equal' => '=', //等价关系,可以是LIKE, =, <, >, <>
                'assoc' => 'AND', //关系类型,可以是AND, OR
                'name' => 'stype', //GET的值的访问键名
                'type' => 'int', //GET的值的类型
                
            ) ,
        ));
        $conditions.=" and store_id=".$this->_store_id;
        /* 取得列表数据 */
        $page = $this->_get_page(10); //获取分页信息
        $statics_list = $this->_statics_mod->find(array(
            'conditions' => '1=1 ' . $conditions,
            'limit' => $page['limit'],
            'count' => true
            //允许统计
            
        ));
        $page['item_count'] = $this->_statics_mod->getCount();
        $this->_format_page($page);
        $this->assign('page_info', $page);
        $this->assign('statics_list', $statics_list);
        /* 当前位置 */
        $this->_curlocal(LANG::get('member_center') , url('app=member') , LANG::get('statics') , url('app=statics') , LANG::get('statics_list'));
        $this->_curitem('statics');

        switch ($_GET['stype']) {
            case '1':
                $cur_menu='statics_day';
                break;
            case '2':
                $cur_menu='statics_week';
                break;
            case '3':
                $cur_menu='statics_month';
                break;
            case '4':
                $cur_menu='statics_jidu';
                break;
            case '5':
                $cur_menu='statics_year';
                break;
            default:
                $cur_menu='statics_list';
                break;
        }
        if($cur_menu!='statics_list')
        {
            $this->assign('noshow',1);
        }
        $this->_curmenu($cur_menu);
        $this->import_resource(array(
            'script' => array(
                array(
                    'path' => 'dialog/dialog.js',
                    'attr' => 'id="dialog_js"',
                ) ,
                array(
                    'path' => 'mlselection.js',
                    'attr' => '',
                ) ,
                array(
                    'path' => 'jquery.plugins/jquery.validate.js',
                    'attr' => '',
                ) ,
                array(
                    'path' => 'jquery.ui/jquery.ui.js',
                    'attr' => '',
                ) ,
                array(
                    'path' => 'jquery.ui/i18n/' . i18n_code() . '.js',
                    'attr' => '',
                ) ,
                array(
                    'path' => 'utils.js',
                    'attr' => '',
                ) ,
                array(
                    'path' => 'inline_edit.js',
                    'attr' => '',
                ) ,
                array(
                    'path' => 'jquery.plugins/jquery.validate.js',
                    'attr' => '',
                ) ,
            ) ,
            'style' => 'jquery.ui/themes/ui-lightness/jquery.ui.css',
        ));
        $this->assign('filtered', $conditions ? 1 : 0); //是否有查询条件
        //将分页信息传递给视图，用于形成分页条
        $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('statics'));
        header("Content-Type:text/html;charset=" . CHARSET);
        $this->display('statics.index.html');
    }

    function export()
    {
        $conditions = '';
        if ($_GET['stype'] == - 1) {
            unset($_GET['stype']);
        }
        $conditions.= $this->_get_query_conditions(array(
            array(
                'field' => 'stype', //可搜索字段title
                'equal' => '=', //等价关系,可以是LIKE, =, <, >, <>
                'assoc' => 'AND', //关系类型,可以是AND, OR
                'name' => 'stype', //GET的值的访问键名
                'type' => 'int', //GET的值的类型
                
            ) ,
        ));
        $conditions.=" and store_id=".$this->_store_id;
        /* 取得列表数据 */
        $statics_list = $this->_statics_mod->find(array(
            'conditions' => '1=1 ' . $conditions
            //允许统计
            
        ));
        if(!$statics_list)
        {
            $this->show_warning('导出出错');
        }

        $str = "店铺名,周期,销售量,收藏量,购物车量,访问量,取消量,评价量,好评量,中评量,差评量,返退量,销售额\n";   
       
        if(is_array($statics_list) && count($statics_list)>0)
        {
           foreach($statics_list as $k=>$v)
           {
               $str.=$v['store_name'].','.sumdate($v['sumdate']).','.$v['sales'].','.$v['collects'].','.$v['carts'].','.$v['visits'].','.$v['cancels'].','.$v['comments'].','.$v['goodcomments'].','.$v['normalcomments'].','.$v['badcomments'].','.$v['refunds'].','.$v['moneys']."\n";
           }
        }   
        $f_type=$this->_statics_mod->get_options_stype();
        $str = iconv('utf-8','gb2312',$str);
        $stype=isset($_GET['stype'])?intval($_GET['stype']):0;
        $filename = date('Y-m-d').$f_type[$stype].'.csv'; //设置文件名   
        $this->_statics_mod->export_csv($filename,$str); //导出   
    }
    /**
     *    添加地址
     *
     *    @author    Garbin
     *    @return    void
     */
    function add() {
        if (!IS_POST) {
            /* 当前位置 */
            $this->_curlocal(LANG::get('member_center') , 'index.php?app=member', LANG::get('statics') , 'index.php?app=statics', LANG::get('statics_add'));
            /* 当前用户中心菜单 */
            $this->_curitem('statics');
            /* 当前所处子菜单 */
            $this->_curmenu('statics_add');
            //编辑器功能
            header("Content-Type:text/html;charset=" . CHARSET);
            $this->display('statics.form.html');
        } else {
            $data = array();
            //得到字段提交上来的信息
            $data['store_id'] = trim($_POST['store_id']);
            $data['store_name'] = trim($_POST['store_name']);
            $data['sumdate'] = trim($_POST['sumdate']);
            $data['stype'] = trim($_POST['stype']);
            $data['sales'] = trim($_POST['sales']);
            $data['collects'] = trim($_POST['collects']);
            $data['carts'] = trim($_POST['carts']);
            $data['visits'] = trim($_POST['visits']);
            $data['cancels'] = trim($_POST['cancels']);
            $data['comments'] = trim($_POST['comments']);
            $data['goodcomments'] = trim($_POST['goodcomments']);
            $data['normalcomments'] = trim($_POST['normalcomments']);
            $data['badcomments'] = trim($_POST['badcomments']);
            $data['refunds'] = trim($_POST['refunds']);
            $data['moneys'] = trim($_POST['moneys']);
            $id = $this->_statics_mod->add($data);
            if (!$id) {
                $this->pop_warning($this->_statics_mod->get_error());
                return;
            } else {
                /* 清除缓存 */
                $this->_clear_cache();
            }
            $this->pop_warning('ok');
        }
    }
    function edit() {
        $id = empty($_GET['id']) ? 0 : intval($_GET['id']);
        if (!$id) {
            echo Lang::get('no_such_statics');
            return;
        }
        if (!IS_POST) {
            $statics_item = $this->_statics_mod->get_info($id);
            if (!$statics_item) {
                $this->show_warning('statics_empty');
                return;
            }
            //上传图片是传给iframe的参数
            $this->assign("id", $id);
            $this->_curlocal(LANG::get('member_center') , url('app=member') , LANG::get('statics') , url('app=statics') , LANG::get('statics_edit'));
            $this->_curitem('statics');
            $this->_curmenu('statics_edit');
            $this->_assign_form();
            //编辑器功能
            $this->assign('statics', $statics_item);
            header("Content-Type:text/html;charset=" . CHARSET);
            $this->display('statics.form.html');
        } else {
            $data = array();
            $data['store_id'] = trim($_POST['store_id']);
            $data['store_name'] = trim($_POST['store_name']);
            $data['sumdate'] = trim($_POST['sumdate']);
            $data['stype'] = trim($_POST['stype']);
            $data['sales'] = trim($_POST['sales']);
            $data['collects'] = trim($_POST['collects']);
            $data['carts'] = trim($_POST['carts']);
            $data['visits'] = trim($_POST['visits']);
            $data['cancels'] = trim($_POST['cancels']);
            $data['comments'] = trim($_POST['comments']);
            $data['goodcomments'] = trim($_POST['goodcomments']);
            $data['normalcomments'] = trim($_POST['normalcomments']);
            $data['badcomments'] = trim($_POST['badcomments']);
            $data['refunds'] = trim($_POST['refunds']);
            $data['moneys'] = trim($_POST['moneys']);
            /* 保存 */
            $rows = $this->_statics_mod->edit($id, $data);
            if ($this->_statics_mod->has_error()) {
                $this->pop_warning($this->_statics_mod->get_error());
                return;
            }
            /* 清除缓存 */
            $rows && $this->_clear_cache();
            $this->pop_warning('ok', 'statics_edit');
        }
    }
    function drop() {
        $id = isset($_GET['id']) ? trim($_GET['id']) : '';
        if (!$id) {
            $this->show_warning('no_statics_to_drop');
            return;
        }
        $ids = explode(',', $id); //获取一个类似array(1, 2, 3)的数组
        if (!$this->_statics_mod->drop($ids)) {
            $this->show_warning($this->_statics_mod->get_error());
            return;
        } else {
            $this->_clear_cache();
        }
        $this->show_message('drop_ok');
    }
    function ajax_col() {
        $id = empty($_GET['id']) ? 0 : intval($_GET['id']);
        $column = empty($_GET['column']) ? '' : trim($_GET['column']);
        $value = isset($_GET['value']) ? trim($_GET['value']) : '';
        $data = array();
        if (in_array($column, array(
            'recommended',
            'sort_order'
        ))) {
            $data[$column] = $value;
            $this->_statics_mod->edit($id, $data);
            if (!$this->_statics_mod->has_error()) {
                echo ecm_json_encode(true);
            }
        } else {
            return;
        }
        return;
    }
    /**
     *    三级菜单
     *
     *    @author    Garbin
     *    @return    void
     */
    function _get_member_submenu() {
        $menus = array(
            array(
                'name' => 'statics_list',
                'url' => 'index.php?app=statics',
            ) ,
            array(
                'name' => 'statics_day',
                'url' => 'index.php?app=statics&stype=1',
            ) ,
            array(
                'name' => 'statics_week',
                'url' => 'index.php?app=statics&stype=2',
            ) ,
            array(
                'name' => 'statics_month',
                'url' => 'index.php?app=statics&stype=3',
            ) ,
            array(
                'name' => 'statics_jidu',
                'url' => 'index.php?app=statics&stype=4',
            ) ,
            array(
                'name' => 'statics_year',
                'url' => 'index.php?app=statics&stype=5',
            ) ,
        );
        return $menus;
    }
    function _assign_form() {
    }
    /* 清除缓存 */
    function _clear_cache() {
        $cache_server = & cache_server();
        $cache_server->delete('function_get_app_statics_data_' . $this->visitor->get('manage_store'));
    }
}

function sumdate($date)
{
    import('zllib/extime.lib');
    $extime=new extime();      
    return $extime->get_sum_date($date);
}
?>
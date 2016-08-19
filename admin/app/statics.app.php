<?php

/* 店铺分类控制器 */
class StaticsApp extends BackendApp {
    var $_statics_mod;
    function __construct() {
        $this->StaticsApp();
    }
    function StaticsApp() {
        parent::__construct();
        $this->_statics_mod = & m('statics');
        $options = array(
            'stype' => $this->_statics_mod->get_options_stype() ,
        );
        $this->assign('options', $options);
    }
    /* 管理 */
    function index() {
        $conditions = '';
        if($_GET['stype']==-1){unset($_GET['stype']);}
        $conditions.= $this->_get_query_conditions(array(
            array(
                'field' => 'stype', //可搜索字段title
                'equal' => '=', //等价关系,可以是LIKE, =, <, >, <>
                'assoc' => 'AND', //关系类型,可以是AND, OR
                'name' => 'stype', //GET的值的访问键名
                'type' => 'int', //GET的值的类型
                
            ) ,
        ));
        $page = $this->_get_page(10); //获取分页信息
        //获取统计数据
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
        //引入jquery表单插件
        $this->import_resource(array(
            'script' => 'jquery.plugins/jquery.validate.js,jqtreetable.js,inline_edit.js,jquery.ui/jquery.ui.js,mlselection.js,jquery.ui/i18n/' . i18n_code() . '.js',
            'style' => 'res:style/jqtreetable.css,jquery.ui/themes/ui-lightness/jquery.ui.css'
        ));
        $this->display('statics.index.html');
    }

     function export()
    {
        $conditions = '';
        $conditions = '';
        if($_GET['stype']==-1){unset($_GET['stype']);}
        $conditions.= $this->_get_query_conditions(array(
            array(
                'field' => 'stype', //可搜索字段title
                'equal' => '=', //等价关系,可以是LIKE, =, <, >, <>
                'assoc' => 'AND', //关系类型,可以是AND, OR
                'name' => 'stype', //GET的值的访问键名
                'type' => 'int', //GET的值的类型
                
            ) ,
        ));
        //获取统计数据
        $statics_list = $this->_statics_mod->find(array(
            'conditions' => '1=1 ' . $conditions,
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
   
    /* 新增 */
    function add() {
        if (!IS_POST) {
            $this->assign('is_show', 1);
            $template_name = $this->_get_template_name();
            $style_name = $this->_get_style_name();
            //编辑器功能
            $this->import_resource(array(
                'script' => 'jquery.plugins/jquery.validate.js,jqtreetable.js,inline_edit.js,jquery.ui/jquery.ui.js,mlselection.js,jquery.ui/i18n/' . i18n_code() . '.js',
                'style' => 'res:style/jqtreetable.css,jquery.ui/themes/ui-lightness/jquery.ui.css'
            ));
            /* 参数 */
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
            $id = $this->_statics_mod->add($data);
            if (!$id) {
                $this->show_warning($this->_statics_mod->get_error());
                return;
            }
            $this->import_resource(array(
                'script' => 'jquery.plugins/jquery.validate.js,jqtreetable.js,inline_edit.js,jquery.ui/jquery.ui.js,jquery.ui/i18n/' . i18n_code() . '.js',
                'style' => 'res:style/jqtreetable.css,jquery.ui/themes/ui-lightness/jquery.ui.css'
            ));
            $this->show_message('add_ok', 'back_list', 'index.php?app=statics', 'continue_add', 'index.php?app=statics&amp;act=add&amp;');
        }
    }
    /* 编辑 */
    function edit() {
        $id = empty($_GET['id']) ? 0 : intval($_GET['id']);
        if (!IS_POST) {
            /* 是否存在 */
            $statics_item = $this->_statics_mod->get_info($id);
            if (!$statics_item) {
                $this->show_warning('statics_empty');
                return;
            }
            $this->assign('statics', $statics_item);
            $this->import_resource(array(
                'script' => 'jquery.plugins/jquery.validate.js,jqtreetable.js,inline_edit.js,jquery.ui/jquery.ui.js,jquery.ui/i18n/' . i18n_code() . '.js',
                'style' => 'res:style/jqtreetable.css,jquery.ui/themes/ui-lightness/jquery.ui.css'
            ));
            $template_name = $this->_get_template_name();
            $style_name = $this->_get_style_name();
            //编辑器功能
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
                $this->show_warning($this->_statics_mod->get_error());
                return;
            }
            $this->show_message('edit_ok', 'back_list', 'index.php?app=statics', 'edit_again', 'index.php?app=statics&amp;act=edit&amp;id=' . $id);
        }
    }
    /* 删除 */
    function drop() {
        $id = isset($_GET['id']) ? trim($_GET['id']) : '';
        if (!$id) {
            $this->show_warning('no_statics_to_drop');
            return;
        }
        $ids = explode(',', $id);
        if (!$this->_statics_mod->drop($ids)) {
            $this->show_warning($this->_statics_mod->get_error());
            return;
        }
        $this->show_message('drop_ok');
    }
    /* 更新排序 */
    function update_order() {
        if (empty($_GET['id'])) {
            $this->show_warning('Hacking Attempt');
            return;
        }
        $ids = explode(',', $_GET['id']);
        $sort_orders = explode(',', $_GET['sort_order']);
        foreach ($ids as $key => $id) {
            $this->_statics_mod->edit($id, array(
                'sort_order' => $sort_orders[$key]
            ));
        }
        $this->show_message('update_order_ok');
    }
    //异步修改数据
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
}

function sumdate($date)
{
    import('zllib/extime.lib');
    $extime=new extime();      
    return $extime->get_sum_date($date);
}
?>

<?php
/* 店铺分类控制器 */
class EcoappApp extends BackendApp
{
    var $_ecoapp_mod;


    function __construct()
    {
        $this->EcoappApp();
    }

    function EcoappApp()
    {
        parent::__construct();
        $this->_ecoapp_mod =& m('ecoapp');
        $options=array(
                                      
        );
        $this->assign('options',$options);

    }

    /* 管理 */
    function index()
    {
        $conditions='';
        $conditions .= $this->_get_query_conditions(array(
            array(
                'field' => 'title',         //可搜索字段title
                'equal' => 'LIKE',          //等价关系,可以是LIKE, =, <, >, <>
                'assoc' => 'AND',           //关系类型,可以是AND, OR
                'name'  => 'title',         //GET的值的访问键名
                'type'  => 'string',        //GET的值的类型
            ),
        ));

        $page   =   $this->_get_page(10);   //获取分页信息
        //$this->_ecoapp_mod=&('app');
          //获取统计数据
        $ecoapp_list = $this->_ecoapp_mod->find(array(
        'conditions'  => '1=1 '.$conditions,
        'limit'   => $page['limit'],
        'count'   => true   //允许统计
        ));
        $page['item_count']=$this->_ecoapp_mod->getCount(); 
        $this->_format_page($page);   
        $this->assign('page_info', $page);  
        $this->assign('ecoapp_list', $ecoapp_list);
        

        //引入jquery表单插件
         $this->import_resource(array(
                                    'script' => 'jquery.plugins/jquery.validate.js,jqtreetable.js,inline_edit.js,jquery.ui/jquery.ui.js,jquery.ui/i18n/' . i18n_code() . '.js',
                                    'style'  => 'res:style/jqtreetable.css,jquery.ui/themes/ui-lightness/jquery.ui.css'));
         
       
        $this->display('ecoapp.index.html');
    }

    /* 新增 */
    function add()
    {
        if (!IS_POST)
        {
            $this->assign('is_show',1);
             $template_name = $this->_get_template_name();
             $style_name    = $this->_get_style_name();
//编辑器功能
                        $this->import_resource(array(
                                    'script' => 'jquery.plugins/jquery.validate.js,jqtreetable.js,inline_edit.js,jquery.ui/jquery.ui.js,jquery.ui/i18n/' . i18n_code() . '.js',
                                    'style'  => 'res:style/jqtreetable.css,jquery.ui/themes/ui-lightness/jquery.ui.css'));
            /* 参数 */
            $this->display('ecoapp.form.html');
        }
        else
        {
            $data = array();
           

           

            /* 保存 */
            $id = $this->_ecoapp_mod->add($data);
            if (!$id)
            {
                $this->show_warning($this->_ecoapp_mod->get_error());
                return;
            }
  $this->import_resource(array(
                                    'script' => 'jquery.plugins/jquery.validate.js,jqtreetable.js,inline_edit.js,jquery.ui/jquery.ui.js,jquery.ui/i18n/' . i18n_code() . '.js',
                                    'style'  => 'res:style/jqtreetable.css,jquery.ui/themes/ui-lightness/jquery.ui.css'));
              
            $this->show_message('add_ok',
                'back_list',    'index.php?app=ecoapp',
                'continue_add', 'index.php?app=ecoapp&amp;act=add&amp;'
                );
        }
    }

    /* 编辑 */
    function edit()
    {
        $id = empty($_GET['id']) ? 0 : intval($_GET['id']);
        if (!IS_POST)
        {
            /* 是否存在 */
            $ecoapp_item = $this->_ecoapp_mod->get_info($id);
            if (!$ecoapp_item )
            {
                $this->show_warning('ecoapp_empty');
                return;
            }
            $this->assign('ecoapp', $ecoapp_item);
             $this->import_resource(array(
                                    'script' => 'jquery.plugins/jquery.validate.js,jqtreetable.js,inline_edit.js,jquery.ui/jquery.ui.js,jquery.ui/i18n/' . i18n_code() . '.js',
                                    'style'  => 'res:style/jqtreetable.css,jquery.ui/themes/ui-lightness/jquery.ui.css'));
             $template_name = $this->_get_template_name();
             $style_name    = $this->_get_style_name();
//编辑器功能
            $this->display('ecoapp.form.html');
        }
        else
        {
           $data = array();
       
          
            /* 保存 */
            $rows = $this->_ecoapp_mod->edit($id, $data);
            if ($this->_ecoapp_mod->has_error())
            {
                $this->show_warning($this->_ecoapp_mod->get_error());
                return;
            }

            $this->show_message('edit_ok',
                'back_list',    'index.php?app=ecoapp',
                'edit_again',   'index.php?app=ecoapp&amp;act=edit&amp;id=' . $id
            );
        }
    }
         
    /* 删除 */
    function drop()
    {
        $id = isset($_GET['id']) ? trim($_GET['id']) : '';
        if (!$id)
        {
            $this->show_warning('no_ecoapp_to_drop');
            return;
        }
        $ids = explode(',', $id);
        if (!$this->_ecoapp_mod->drop($ids))
        {
            $this->show_warning($this->_ecoapp_mod->get_error());
            return;
        }
        $this->show_message('drop_ok');
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
            $this->_ecoapp_mod->edit($id, array('sort_order' => $sort_orders[$key]));
        }
        $this->show_message('update_order_ok');
    }
    //异步修改数据
    function ajax_col()
    {
        $id     = empty($_GET['id']) ? 0 : intval($_GET['id']);
        $column = empty($_GET['column']) ? '' : trim($_GET['column']);
        $value  = isset($_GET['value']) ? trim($_GET['value']) : '';
        $data   = array();
        if (in_array($column ,array('recommended','sort_order')))
        {
            $data[$column] = $value;
            $this->_ecoapp_mod->edit($id, $data);
            if(!$this->_ecoapp_mod->has_error())
            {
                echo ecm_json_encode(true);
            }
        }       
        else
        {
            return ;
        }
        return ;
    }
}
?>
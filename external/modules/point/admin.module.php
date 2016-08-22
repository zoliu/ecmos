<?php

class PointModule extends AdminbaseModule
{
    var $_mod;
	var $_point_set;
    var $_module_path;
     var $_point_goods_mod;

    function __construct()
    {
        $this->PointModule();
    }

    function PointModule()
    {
        parent::__construct();
        $this->_module_path=ROOT_PATH."/external/modules/point";
        $this->_mod=& m("point_logs");
		    $this->_point_set=& m("point_set");
        $this->_point_goods_mod =& m('point_goods');
    }



    function index()
    {
     $page = $this->_get_page();
      $conditions='';
      $conditions .= $this->_get_query_conditions(array(
            array(
                'field' => 'user_name',         //可搜索字段title
                'equal' => 'LIKE',          //等价关系,可以是LIKE, =, <, >, <>
                'assoc' => 'AND',           //关系类型,可以是AND, OR
                'name'  => 'user_name',         //GET的值的访问键名
                'type'  => 'string',        //GET的值的类型
            ),array(
                'field' => 'type',         //可搜索字段title
                'equal' => '=',          //等价关系,可以是LIKE, =, <, >, <>
                'assoc' => 'AND',           //关系类型,可以是AND, OR
                'name'  => 'point_type',         //GET的值的访问键名
                'type'  => 'string',        //GET的值的类型
            ),
        ));
	    $data = $this->_mod->find(array(
        'conditions' => '1=1' . $conditions,
        'limit' => $page['limit'],
        'order'=>' id desc',
        'count' => true));
        $type_list=$this->_mod->getTypeList();
        $this->assign('options_type',$type_list);
        $this->assign('data', $data);
        $page['item_count'] = $this->_mod->getCount();
        $this->_format_page($page);
        $this->assign('page_info', $page);      
        $this->display('point_logs.index.html');
    }

	
	function point_set()
	{
      $cfg=include_array($this->_module_path.'/point_set.config.php');
	   if(!IS_POST)
	   {
         if($id=$this->get_point_set_id())
          {
               $data=$this->_point_set->get_info($id);
          }
          
	   	  $this->import_resource(array(
                'script' => 'jquery.plugins/jquery.validate.js'
            ));
          if($data)
          {
            $data=unserialize($data['config']);
          }

          $this->assign('data',$data);
          $this->assign('config',$cfg);
	      $this->display('point_set.form.html');
	   }else{
	        $data = array();
            foreach($cfg as $config)
            {
                $data[$config['name']]=$_POST[$config['name']];
            }
			
            /* 保存 */
            if($id=$this->get_point_set_id())
            {
                $this->_point_set->edit($id,array('config'=>serialize($data)));
            }else{
                 $id=$this->_point_set->add(array('config'=>serialize($data)));
            }
            
            if ($this->_point_set->has_error())
            {
                $this->show_warning($this->_point_set->get_error());
                return;
            }
			 $this->show_message('edit_point_set_success',
                'continue_edit', 'index.php?module=point&amp;act=point_set',
                'back_list',    'index.php?module=point&amp;act=index');
	   
	   }
	
	}


    function drop_point_logs()
    {
      $id = isset($_GET['id']) ? trim($_GET['id']) : '';
        if (!$id)
        {
            $this->show_warning('no_point_logs_to_drop');
            return;
        }

        $ids = explode(',', $id);
        if (!$this->_mod->drop($ids))
        {
            $this->show_warning($this->_mod->get_error());
            return;
        }
        $this->show_message('drop_data_successed');
    
    }

    function get_point_set_id()
    {

         $point_set=$this->_point_set->find();

         if($point_set)
         {
           $point_set= current($point_set);
           return $point_set['id'];
         }
         return 0;
    }


    /* 管理 */
    function point_goods()
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
        
          //获取统计数据
        $point_goods_list = $this->_point_goods_mod->find(array(
        'conditions'  => '1=1 '.$conditions,
        'limit'   => $page['limit'],
        'count'   => true   //允许统计
        ));
        $page['item_count']=$this->_point_goods_mod->getCount(); 
        $this->_format_page($page);   
        $this->assign('page_info', $page);  
        $this->assign('point_goods_list', $point_goods_list);
        

        //引入jquery表单插件
         $this->import_resource(array(
        'script' => 'jquery.plugins/jquery.validate.js,jqtreetable.js,inline_edit.js,jquery.ui/jquery.ui.js,jquery.ui/i18n/' . i18n_code() . '.js',
        'style'  => 'res:style/jqtreetable.css,jquery.ui/themes/ui-lightness/jquery.ui.css'));
         
       
        $this->display('point_goods.index.html');
    }

    /* 新增 */
    function add_goods()
    {
        if (!IS_POST)
        {
            $this->assign('is_show',1);
             $template_name = $this->_get_template_name();
             $style_name    = $this->_get_style_name();
//编辑器功能
           
            $this->assign('build_editor_goods_content', $this->_build_editor(array(
                'name' => 'goods_content',
                'content_css' => SITE_URL . "/themes/mall/".$template_name."/styles/".$style_name."/css/ecmall.css"
            )));

            $this->import_resource(array(
                        'script' => 'jquery.plugins/jquery.validate.js,jqtreetable.js,inline_edit.js,jquery.ui/jquery.ui.js,jquery.ui/i18n/' . i18n_code() . '.js',
                        'style'  => 'res:style/jqtreetable.css,jquery.ui/themes/ui-lightness/jquery.ui.css'));
          
            /* 参数 */
            $this->display('point_goods.form.html');
        }
        else
        {
            $data = array();
            $data['goods_name']=trim($_POST['goods_name']);
            $data['goods_desc']=trim($_POST['goods_desc']);
            $data['goods_content']=trim($_POST['goods_content']);
            $data['sort']=trim($_POST['sort']);
            $data['stock']=trim($_POST['stock']);
            $data['max_num']=trim($_POST['max_num']);
            $data['goods_price']=trim($_POST['goods_price']);
            $data['need_point']=trim($_POST['need_point']);
            $files=uploadImage('photo_file');
            $data['default_image']=isNotEmpty($files)?$files:$_POST['photo'];

            /* 保存 */
            $id = $this->_point_goods_mod->add($data);
            if (!$id)
            {
                $this->show_warning($this->_point_goods_mod->get_error());
                return;
            }
            $this->import_resource(array(
            'script' => 'jquery.plugins/jquery.validate.js,jqtreetable.js,inline_edit.js,jquery.ui/jquery.ui.js,jquery.ui/i18n/' . i18n_code() . '.js',
            'style'  => 'res:style/jqtreetable.css,jquery.ui/themes/ui-lightness/jquery.ui.css'));
              
            $this->show_message('add_ok',
                'back_list',    'index.php?module=point&act=point_goods',
                'continue_add', 'index.php?module=point&act=add_goods&amp;'
                );
        }
    }

    /* 编辑 */
    function edit_goods()
    {
        $id = empty($_GET['id']) ? 0 : intval($_GET['id']);
        if (!IS_POST)
        {
            /* 是否存在 */
            $point_goods_item = $this->_point_goods_mod->get_info($id);
            if (!$point_goods_item )
            {
                $this->show_warning('point_goods_empty');
                return;
            }
            $this->assign('point_goods', $point_goods_item);
             $this->import_resource(array(
                                    'script' => 'jquery.plugins/jquery.validate.js,jqtreetable.js,inline_edit.js,jquery.ui/jquery.ui.js,jquery.ui/i18n/' . i18n_code() . '.js',
                                    'style'  => 'res:style/jqtreetable.css,jquery.ui/themes/ui-lightness/jquery.ui.css'));
             $template_name = $this->_get_template_name();
             $style_name    = $this->_get_style_name();
//编辑器功能
           
             $this->assign('build_editor_goods_content', $this->_build_editor(array(
                'name' => 'goods_content',
                'content_css' => SITE_URL . "/themes/mall/".$template_name."/styles/".$style_name."/css/ecmall.css"
            )));

            $this->display('point_goods.form.html');
        }
        else
        {
           $data = array();
                        $data['goods_name']=trim($_POST['goods_name']);
                        $data['goods_desc']=trim($_POST['goods_desc']);
                        $data['goods_content']=trim($_POST['goods_content']);
                        $data['sort']=trim($_POST['sort']);
                        $data['stock']=trim($_POST['stock']);
                        $data['need_point']=trim($_POST['need_point']);
                        $data['goods_price']=trim($_POST['goods_price']);
                         $data['max_num']=trim($_POST['max_num']);
                       $files=uploadImage('photo_file');
                       $data['default_image']=isNotEmpty($files)?$files:$_POST['photo'];
       
          
            /* 保存 */
            $rows = $this->_point_goods_mod->edit($id, $data);
            if ($this->_point_goods_mod->has_error())
            {
                $this->show_warning($this->_point_goods_mod->get_error());
                return;
            }

            $this->show_message('edit_ok',
                'back_list',    'index.php?module=point&act=point_goods',
                'edit_again',   'index.php?module=point&act=edit_goods&amp;id=' . $id
            );
        }
    }
         
    /* 删除 */
    function drop_goods()
    {
        $id = isset($_GET['id']) ? trim($_GET['id']) : '';
        if (!$id)
        {
            $this->show_warning('no_point_goods_to_drop');
            return;
        }
        $ids = explode(',', $id);
        if (!$this->_point_goods_mod->drop($ids))
        {
            $this->show_warning($this->_point_goods_mod->get_error());
            return;
        }
        $this->show_message('drop_ok');
    }

    function point_goods_log()
    {
      $page = $this->_get_page();
      $conditions='';
     $conditions .= $this->_get_query_conditions(array(
            array(
                'field' => 'user_name',         //可搜索字段title
                'equal' => 'LIKE',          //等价关系,可以是LIKE, =, <, >, <>
                'assoc' => 'AND',           //关系类型,可以是AND, OR
                'name'  => 'user_name',         //GET的值的访问键名
                'type'  => 'string',        //GET的值的类型
            ),array(
                'field' => 'goods_name',         //可搜索字段title
                'equal' => 'LIKE',          //等价关系,可以是LIKE, =, <, >, <>
                'assoc' => 'AND',           //关系类型,可以是AND, OR
                'name'  => 'goods_name',         //GET的值的访问键名
                'type'  => 'string',        //GET的值的类型
            ),array(
                'field' => 'status',         //可搜索字段title
                'equal' => '=',          //等价关系,可以是LIKE, =, <, >, <>
                'assoc' => 'AND',           //关系类型,可以是AND, OR
                'name'  => 'point_type',         //GET的值的访问键名
                'type'  => 'string',        //GET的值的类型
            ),
        ));
        $logs_mod= &m('point_goods_log');
        $data = $logs_mod->find(array(
        'conditions' => '1=1' . $conditions,
        'limit' => $page['limit'],
        'order'=>' id desc',
        'count' => true));
        $type_list=$logs_mod->getTypeList();
        $this->assign('options_type',$type_list);
        $this->assign('data', $data);
        $page['item_count'] = $logs_mod->getCount();
        $this->_format_page($page);
        $this->assign('page_info', $page);      
        $this->display('point_goods_log.index.html');

    }

    function apply_point_goods()
    {
        $id = isset($_GET['id']) ? trim($_GET['id']) : '';
        $status = isset($_GET['status']) ? trim($_GET['status']) : '';
        if (!$id)
        {
            $this->show_warning('no_point_goods_log');
            return;
        }
        $status=$status=='passport'?'applying':'passport';
      
        $logs_mod= &m('point_goods_log');
        $logs_mod->edit($id,array('status'=>$status));        
        $this->show_message('apply_ok');
    }

     function enabled_goods()
    {
        $id = isset($_GET['id']) ? trim($_GET['id']) : '';
        $enabled = isset($_GET['enabled']) ? trim($_GET['enabled']) : '';
        if (!$id)
        {
            $this->show_warning('no_point_goods_log');
            return;
        }
        $enabled=$enabled==1?0:1;
        $ids = explode(',', $id);
        $logs_mod= &m('point_goods');
        $logs_mod->edit('id '.db_create_in($ids),array('enabled'=>$enabled));        
        $this->show_message('apply_ok');
    }

    function view_log()
    {
        $id=isset($_GET['id'])?intval($_GET['id']):0;
        $user_id=isset($_GET['user_id'])?intval($_GET['user_id']):0;
        if(!$id || !$user_id)
        {
            $this->show_warning('no_data');
            return;
        }
        $log_mod= &m('point_goods_log');
        $log_info=$log_mod->get($id);
        $user_mod= &m('member');
        $user_info=$user_mod->get($user_id);
        $data=array_merge($log_info,$user_info);
        $this->assign('data',$data);
        $this->display('point_goods_log.view.html');

    }



}

if(!function_exists('include_array'))
{
	function include_array($path)
	{

		return file_exists($path) ? include($path) : null;

	}
}


//导出数组到文件
if(!function_exists('save_array'))
{
	function save_array($path,$array)

	{

		 file_put_contents($path, "<?php\nreturn ".var_export($array,1).";\n?>");

	}
}
?>
<?php

/**
 *    手机版编辑
 *
 *    @author    seema
 *    @usage    none
 */
class Wap_indexApp extends BackendApp
{
    var $_wapindex_mod;
    var $_recommend_mod;

    function __construct()
    {
        $this->Wap_indexApp();
    }

    function Wap_indexApp()
    {
        parent::BackendApp();

        $this->_wapindex_mod =& m('wapindex');
        $this->_recommend_mod = &m('recommend');

            $this->cate_name=$this->_wapindex_mod->get_cate_name();
            $this->recom_name=$this->_wapindex_mod->get_recom_name();
            $this->gcategory=$this->_wapindex_mod->get_gcategory();
    }

    /**
     *    手机版项目
     *
     *    @author    seema
     *    @return    void
     */
    function index()
    {
        /* 处理cate_id */
        $cate_id = !empty($_GET['cate_id'])? intval($_GET['cate_id']) : 0;
        $conditions='';
        $conditions = $this->_get_query_conditions(array(
            array(
                'field' => 'name',
                'equal' => 'LIKE',
                'assoc' => 'AND',
                'name'  => 'name',
                'type'  => 'string',
            ),
            array(
                'field' => 'cate_id',
                'equal' => '=',
                'assoc' => 'AND',
                'name'  => 'cate_id',
                'type'  => 'string',
            ),
        ));
        !empty($cate_ids)&& $conditions = ' AND cate_id=' . $cate_id;
        $page   =   $this->_get_page(10);   //获取分页信息
        $recomm=$this->_wapindex_mod->find(array(
        'conditions'  => '1=1'.$conditions,
        'limit'   => $page['limit'],
        'order'   => 'cate_id ASC,sort_order ASC', //排序
        'count'   => true   //允许统计
        ));
        $page['item_count']=$this->_wapindex_mod->getCount();   //获取统计数据
        $if_show = array(
            0 => Lang::get('no'),
            1 => Lang::get('yes'),
        );

        foreach ($recomm as $key =>$recom){
            $recomm[$key]['if_show']  = $if_show[$recom['if_show']]; //是否显示
            $recomm[$key]['cate_name']  = $this->cate_name[$recom['cate_id']];
            $recom['logo']&&$recomm[$key]['logo'] = dirname(site_url()) . '/' . $recom['logo'];
        }
        $this->_format_page($page);
        $this->assign('cate_name',$this->cate_name);
        $this->import_resource(array('script' => 'inline_edit.js'));
        $this->assign('filtered', $conditions? 1 : 0); //是否有查询条件
        $this->assign('page_info', $page);   //将分页信息传递给视图，用于形成分页条
        $this->assign('recomm', $recomm);
        $this->display('recom.index.html');
    }
     /**
     *    添加分类
     *
     *    @author    seema
     *    @return    void
     */
    function add()
    {
        if (!IS_POST)
        {
            $this->assign('cate_name',$this->cate_name);
            $this->assign('recom_name',$this->recom_name);
            $this->assign('gcategory',$this->gcategory);
            $this->display('recom.form.html');
        }
        else
        {
            $data = array();
            $data['name']      =   $_POST['name'];
            $data['cate_id']    =   $_POST['cate_id'];
            $data['url']       =   $_POST['url'];
            $data['if_show']    =   $_POST['if_show'];
            $data['sort_order'] =   $_POST['sort_order'];
            $data['num']       =   $_POST['num'];
            $data['recom_id']    =   $_POST['recom_id'];
            $data['gcategory_id'] =   $_POST['gcategory_id'];
            $data['add_time']   =   gmtime();

            if (!$id = $this->_wapindex_mod->add($data))  //获取id
            {
                $this->show_warning($this->_wapindex_mod->get_error());

                return;
            }
            /* 处理上传的图片 */
            $logo       =   $this->_upload_logo($id);
            if ($logo === false)
            {
                return;
            }
            $logo && $this->_wapindex_mod->edit($id, array('logo' => $logo)); //将logo地址记下

            $this->show_message('add_wap_index_successed',
                'back_list',    'index.php?app=wap_index',
                'continue_add', 'index.php?app=wap_index&amp;act=add'
            );
        }
    }
     /**
     *    编辑文章
     *
     *    @author    Hyber
     *    @return    void
     */
    function edit()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if (!$id)
        {
            $this->show_warning('no_such_wap');
            return;
        }
         if (!IS_POST)
        {
            $recom=$this->_wapindex_mod->get($id);
            $recom['logo']&&$recom['logo'] = dirname(site_url()) . '/' . $recom['logo'];
            $this->assign('recom',$recom);
            $this->assign('cate_name',$this->cate_name);
            $this->assign('recom_name',$this->recom_name);
            $this->assign('gcategory',$this->gcategory);
            $this->display('recom.form.html');
        }
        else
        {
            
            $data = array();
            $data['name']      =   $_POST['name'];
            $data['cate_id']    =   $_POST['cate_id'];
            $data['url']       =   $_POST['url'];
            $data['if_show']    =   $_POST['if_show'];
            $data['sort_order'] =   $_POST['sort_order'];
            $data['num']       =   $_POST['num'];
            $data['recom_id']    =   $_POST['recom_id'];
            $data['gcategory_id'] =   $_POST['gcategory_id'];
            $data['add_time']   =   gmtime();

            $logo               =   $this->_upload_logo($id);
            $logo && $data['logo'] = $logo;
            if ($logo === false)
            {
                return;
            }             
            $rows=$this->_wapindex_mod->edit($id, $data);
            if ($this->_wapindex_mod->has_error())
            {
                $this->show_warning($this->_wapindex_mod->get_error());
                return;
            }
            $this->show_message('edit_wap_index_successed',
                'back_list',    'index.php?app=wap_index',
                'edit_again', 'index.php?app=wap_index&amp;act=edit&amp;id=' . $id
            );
        }
    }

    //异步修改数据
   function ajax_col()
   {
       $id     = empty($_GET['id']) ? 0 : intval($_GET['id']);
       $column = empty($_GET['column']) ? '' : trim($_GET['column']);
       $value  = isset($_GET['value']) ? trim($_GET['value']) : '';
       $data   = array();

       if (in_array($column ,array('if_show', 'sort_order')))
       {
           $data[$column] = $value;
           $this->_wapindex_mod->edit($id, $data);
           if(!$this->_wapindex_mod->has_error())
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
    function drop()
    {
        $ids = isset($_GET['id']) ? trim($_GET['id']) : '';
        if (!$ids)
        {
            $this->show_warning('no_such_wap');

            return;
        }
        $ids=explode(',', $ids);

        if (!$this->_wapindex_mod->drop($ids))    //删除
        {
            $this->show_warning($this->_wapindex_mod->get_error());

            return;
        }

        $this->show_message('drop_ok_system_wap',
                'back_list',    'index.php?app=wap_index');
    }
    

    function _upload_logo($id)
    {
        $file = $_FILES['logo'];
        if ($file['error'] == UPLOAD_ERR_NO_FILE) // 没有文件被上传
        {
            return '';
        }
        import('uploader.lib');             //导入上传类
        $uploader = new Uploader();
        $uploader->allowed_type(IMAGE_FILE_TYPE); //限制文件类型
        $uploader->addFile($_FILES['logo']);//上传logo
        if (!$uploader->file_info())
        {
            $this->show_warning($uploader->get_error() , 'go_back', 'index.php?app=wap_index&amp;act=edit&amp;id=' . $id);
            return false;
        }
        /* 指定保存位置的根目录 */
        $uploader->root_dir(ROOT_PATH);

        /* 上传 */
        if ($file_path = $uploader->save('data/files/mall/wap/recom', $id))   //保存到指定目录，并以指定文件名$id存储
        {
            return $file_path;
        }
        else
        {
            return false;
        }
    }
}

?>
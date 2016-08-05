<?php

/**
 *    页面推荐显示
 *
 *    @author    360cd.cn seema
 *    
 */
class Recom_dsApp extends BackendApp
{
    var $rcate_mob;
    var $recom_mob;

    function __construct()
    {
        $this->Recom_dsApp();
    }

    function Recom_dsApp()
    {
        parent::BackendApp();

        $this->rcate_mob =& m('rcategory');
        $this->recom_mob =& m('recommendation');

    }

    function index()
    {
        $conditions = $this->_get_query_conditions(array(array(
                'field' => 'name',
                'equal' => 'LIKE',
                'assoc' => 'AND',
                'name'  => 'name',
                'type'  => 'string',
            ),
            array(
                'field' => 'rcategory.cate_id',
                'equal' => '=',
                'assoc' => 'AND',
                'name' => 'cate_id',
                'type' => 'int',
            ),
        ));

        $type=isset($_GET['type']) && !empty($_GET['type'])?trim($_GET['type']):'';
        if ($type) {
            $conditions.=" and r_type LIKE '".$type."%'";
        }
        $page   =   $this->_get_page(10);   //获取分页信息
        //更新排序
        if (isset($_GET['sort']) && isset($_GET['order']))
        {
            $sort  = strtolower(trim($_GET['sort']));
            $order = strtolower(trim($_GET['order']));
            if (!in_array($order,array('asc','desc')))
            {
             $sort  = 'recommendation.cate_id';
             $order = 'asc';
            }
        }
        else
        {
            $sort  = 'recommendation.cate_id';
            $order = 'asc';
        }
        
          $joinstr.=$this->recom_mob->parseJoin('cate_id','cate_id','rcategory');
        $recom_data=$this->recom_mob->find(array(
        'conditions'    => '1=1' . $conditions,
        'joinstr'       =>$joinstr,
        'fields'        =>'rcategory.cate_name,recommendation.*',
        'limit'         => $page['limit'],
        'order'         => "$sort $order",
        'count'         => true
        ));
        foreach ($recom_data as $key => $recom)
        {
            $recom['logo']&&$recom_data[$key]['logo'] = dirname(site_url()) . '/' . $recom['logo'];
        }
        $page['item_count']=$this->recom_mob->getCount();   //获取统计数据
        /* 导入jQuery的表单验证插件 */
        $this->import_resource(array(
            'script' => 'jqtreetable.js,inline_edit.js',
            'style'  => 'res:style/jqtreetable.css'
        ));
        $this->_format_page($page);
        //页面模块
        $this->assign('rcate_data', $this->rcate_mob->get_rcate());

        $this->assign('type_data',$this->recom_mob->get_type_name());
        $this->assign('filtered', $conditions? 1 : 0); //是否有查询条件
        $this->assign('page_info', $page);   //将分页信息传递给视图，用于形成分页条
        $this->assign('recom_data', $recom_data);
        $this->assign('type', $type);
        $this->display('recom_ds.index.html');
    }
     /**
     *    新增推荐
     *
     *    @author    360cd.cn seema
     *    @return    void
     */
    function add()
    {
            $type=isset($_GET['type']) && !empty($_GET['type'])?trim($_GET['type']):'';

        if (!IS_POST)
        {
            $this->import_resource(array(
                'script' => 'jquery.plugins/jquery.validate.js'
            ));

            $this->assign('rcate_data', $rcate_data);

            $yes_or_no = array(
                1 => Lang::get('yes'),
                0 => Lang::get('no'),
            );
            $this->assign('yes_or_no', $yes_or_no);
            /* 显示新增表单 */
            $recon_data = array(
                'sort_order' => 255,
                'if_show' => 0,
            );
            $this->assign('rcate_data', $this->rcate_mob->get_rcate());
            //推荐分类
            switch ($type) {
                case 'image':
                    $this->assign('gcategory_data', $this->recom_mob->get_gcategory());
                    $this->assign('type', $this->recom_mob->get_type_image());
                    $this->display('recom_image.form.html');
                    break;
                case 'goods':
                    $this->assign('type', $this->recom_mob->get_type_goods());
                    $this->display('recom_goods.form.html');
                    break;
                case 'brand':
                    $this->assign('type', $this->recom_mob->get_type_brand());
                    $this->display('recom_brand.form.html');
                    break;
                case 'recommend':
                    $this->assign('type', $this->recom_mob->get_type_recommend());
                    $this->display('recom_recommend.form.html');
                    break;
                
                default:                    
                    break;
            }
        }
        else
        {
            $data = array();
            $data['name']     = $_POST['name'];
            $data['cate_id'] = $_POST['cate_id'];
            $data['r_type'] = $_POST['r_type'];
            $data['url'] = $_POST['url'];
            $data['if_show']    = $_POST['if_show'];
            $data['sort_order']     = $_POST['sort_order'];
            $data['text']     = $_POST['text'];
            $data['o_price'] = $_POST['o_price'];
            $data['n_price'] = $_POST['n_price'];
            $data['store_name'] = $_POST['store_name'];
            $data['type'] = $type;
            $data['title'] = $_POST['title'];
            $data['key_words'] = $_POST['key_words'];
            $data['gcategory_id'] = $_POST['gcategory_id'];

            if (!$id = $this->recom_mob->add($data))  //获取id
            {
                $this->show_warning($this->recom_mob->get_error());

                return;
            }/**/

            /* 处理上传的图片 */
            $logo       =   $this->_upload_logo($id,$type);
            if ($logo === false)
            {
                return;
            }
            $logo && $this->recom_mob->edit($id, array('logo' => $logo)); //将logo地址记下

            $this->show_message('add_recom_successed',
                'back_list',    'index.php?app=recom_ds&amp;type='.$type,
                'continue_add', 'index.php?app=recom_ds&amp;act=add&amp;type='.$type
            );
        }
    }    

     /**
     *    编辑推荐
     *
     *    @author    Hyber
     *    @return    void
     */
    function edit()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $type = isset($_GET['type']) ? trim($_GET['type']) : 0;
        //var_dump($type);exit();
        if (!$id)
        {
            $this->show_warning('no_such_recom');
            return;
        }
         if (!IS_POST)
        {
            $find_data     = $this->recom_mob->find($id);
            if (empty($find_data))
            {
                $this->show_warning('no_such_recom');

                return;
            }
            $recom    =   current($find_data);
            if ($recom['logo'])
            {
                $recom['logo']  =   dirname(site_url()) . "/" . $recom['logo'];
            }
            /* 显示新增表单 */
            $yes_or_no = array(
                1 => Lang::get('yes'),
                0 => Lang::get('no'),
            );
            $this->import_resource(array(
                'script' => 'jquery.plugins/jquery.validate.js'
            ));
            $this->assign('yes_or_no', $yes_or_no);
            $this->assign('recom', $recom);
            $this->assign('rcate_data', $this->rcate_mob->get_rcate());
            switch ($type) {
                case 'image':
                    $this->assign('gcategory_data', $this->recom_mob->get_gcategory());
                    $this->assign('type', $this->recom_mob->get_type_image());
                    $this->display('recom_image.form.html');
                    break;
                case 'goods':
                    $this->assign('type', $this->recom_mob->get_type_goods());
                    $this->display('recom_goods.form.html');
                    break;
                case 'brand':
                    $this->assign('type', $this->recom_mob->get_type_brand());
                    $this->display('recom_brand.form.html');
                    break;
                case 'recommend':
                    $this->assign('type', $this->recom_mob->get_type_recommend());
                    $this->display('recom_recommend.form.html');
                    break;
                
                default:                    
                    break;
                }
        }
        else
        {
            $data = array();
            $data['name']     = $_POST['name'];
            $data['cate_id'] = $_POST['cate_id'];
            $data['r_type'] = $_POST['r_type'];
            $data['url'] = $_POST['url'];
            $data['if_show']    = $_POST['if_show'];
            $data['sort_order']     = $_POST['sort_order'];
            $data['text']     = $_POST['text'];
            $data['o_price'] = $_POST['o_price'];
            $data['n_price'] = $_POST['n_price'];
            $data['store_name'] = $_POST['store_name'];
            $data['title'] = $_POST['title'];
            $data['key_words'] = $_POST['key_words'];
            $data['gcategory_id'] = $_POST['gcategory_id'];

            $data['type'] = $type;

            $logo               =   $this->_upload_logo($id,$type);
            $logo && $data['logo'] = $logo;
            if ($logo === false)
            {
                return;
            }             
            $rows=$this->recom_mob->edit($id, $data);
            if ($this->recom_mob->has_error())
            {
                $this->show_warning($this->recom_mob->get_error());
                return;
            }

            $this->show_message('edit_recom_successed',
                'back_list',        'index.php?app=recom_ds&amp;type='.$type,
                'edit_again',    'index.php?app=recom_ds&amp;act=edit&amp;type='.$type.'&amp;id=' . $id);
        }
    }

    function drop()
    {
        $ids = isset($_GET['id']) ? trim($_GET['id']) : '';
        if (!$ids)
        {
            $this->show_warning('no_such_recom');

            return;
        }
        $ids=explode(',',$ids);
        $this->recom_mob->drop($ids);
        if ($this->recom_mob->has_error())    //删除
        {
            $this->show_warning($this->recom_mob->get_error());

            return;
        }

        $this->show_message('drop_recom_successed');
    }

        /**
     *    处理上传标志
     *
     *    @author    Hyber
     *    @param     int $id
     *    @return    string
     */
    function _upload_logo($id,$type)
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
            $this->show_warning($uploader->get_error() , 'go_back', 'index.php?app=recom_ds&amp;act=edit&amp;type='.$_POST['type'].'&amp;id=' . $id);
            return false;
        }
        /* 指定保存位置的根目录 */
        $uploader->root_dir(ROOT_PATH);

        /* 上传 */
        if ($file_path = $uploader->save('data/files/mall/recom/'.$type, $id))   //保存到指定目录，并以指定文件名$id存储
        {
            return $file_path;
        }
        else
        {
            return false;
        }
    }

    function pass()
    {
        $id = $_GET['id'];
        if (empty($id))
        {
            $this->show_warning('request_error');
            exit;
        }
        $ids = explode(',', $id);
        if($this->recom_mob->edit($ids,array('if_show'=>1)))
        {
            $this->show_message('recom_passed',
                'back_list', 'index.php?app=recom_ds&type=image');
        }
    }

    function refuse()
    {
        $id = $_GET['id'];
        if (empty($id))
        {
            $this->show_warning('request_error');
            exit;
        }
        $ids = explode(',', $id);
        if($this->recom_mob->edit($ids,array('if_show'=>0)))
        {
            $this->show_message('recom_refused',
                'back_list', 'index.php?app=recom_ds&type=image');
        }
    }


}

?>
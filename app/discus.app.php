<?php



/**

 *    导航管理控制器

 *

 *    @author    Garbin

 *    @usage    none

 */

class DiscusApp extends StoreadminbaseApp

{

    var $_discus_mod;



    function __construct()

    {

        $this->DiscusApp();

    }



    function DiscusApp()

    {

        parent::__construct();

        $this->_discus_mod =& m('discus');

        $this->_store_id  = intval($this->visitor->get('manage_store'));
        $this->assign('discus_status', $this->_discus_mod->get_discus_status());
    }



    function index()

    {

        $conditions ='';

        $conditions = $this->_get_query_conditions(array(array(

                'field' => 'title',         //可搜索字段title

                'equal' => 'LIKE',          //等价关系,可以是LIKE, =, <, >, <>

            ),

        ));



        /* 取得列表数据 */



        $conditions.=" and discus.seller_id=".$this->_store_id;

      

        $page   =   $this->_get_page(10);    //获取分页信息

        $discus_list = $this->_discus_mod->find(array(

        'conditions'  => '1=1 '.$conditions,

        'limit'   => $page['limit'],

        'fields'=>' *,discus.status as status',

         'join'=>'belong_to_order',

         

        'order'   =>'discus.buyer_addtime desc',//按照状态排序 360cd.cn  seema



        'count'   => true   //允许统计

        ));



        $page['item_count']=$this->_discus_mod->getCount(); 

        $this->_format_page($page);   

        $this->assign('page_info', $page);  

        $this->assign('discus_list', $discus_list);



        /* 当前位置 */

        $this->_curlocal(LANG::get('member_center'), url('app=member'),

                         LANG::get('discus'), url('app=discus'),

                         LANG::get('discus_list'));

        $this->_curitem('discus');

        $this->_curmenu('discus_list');



        $this->import_resource(array(

            'script' => array(

                array(

                    'path' => 'dialog/dialog.js',

                    'attr' => 'id="dialog_js"',

                ),

                array(

                    'path' => 'mlselection.js',

                    'attr' =>'',

                ),array(

                    'path' => 'jquery.plugins/jquery.validate.js',

                    'attr' =>'',

                ),

                array(

                    'path' => 'jquery.ui/jquery.ui.js',

                    'attr' => '',

                ), array(

                    'path' => 'jquery.ui/i18n/' . i18n_code() . '.js',

                    'attr' => '',

                ),

                 array(

                    'path' => 'utils.js',

                    'attr' => '',

                ),array(

                    'path' => 'inline_edit.js',

                    'attr' => '',

                ),

                array(

                    'path' => 'jquery.plugins/jquery.validate.js',

                    'attr' => '',

                ),

                ),

            'style' =>  'jquery.ui/themes/ui-lightness/jquery.ui.css',

        ));







        $this->assign('filtered', $conditions? 1 : 0); //是否有查询条件

        //将分页信息传递给视图，用于形成分页条

        $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('discus'));

        header("Content-Type:text/html;charset=" . CHARSET);

        $this->display('discus.index.html');

    }



    function _get_seller_type()

    {

        return array('all'=>Lang::get('all'),'back_to_all'=>Lang::get('back_to_all'),'per_to_all'=>Lang::get('per_to_all'));

    }



    function edit()

    {

        $id = empty($_GET['id']) ? 0 : intval($_GET['id']);

       

        if (!$id)

        {

            echo Lang::get('no_such_discus');



            return;

        }

        if (!IS_POST)

        {

            

            $discus_item = $this->_discus_mod->get_info($id);

            if (!$discus_item )

            {

                $this->show_warning('discus_empty');

                return;

            }



            

            //上传图片是传给iframe的参数

            $this->assign("id", $id);

           

            $this->_curlocal(LANG::get('member_center'), url('app=member'),

                             LANG::get('discus'), url('app=discus'),

                             LANG::get('discus_edit'));

            $this->_curitem('discus');

            $this->_curmenu('discus_edit');



            $this->_assign_form();



            //编辑器功能



            $this->assign('options',array('seller_type'=>$this->_get_seller_type()));

            header('Content-Type:text/html;charset=' . CHARSET);

            $this->assign('discus', $discus_item);

            header("Content-Type:text/html;charset=" . CHARSET);

            $this->display('discus.form.html');

        }

        else

        {

            $data = array();



            $data['seller_type']=trim($_POST['seller_type']);

            $data['seller_addtime']=gmtime();

            $data['seller_remark']=trim($_POST['seller_remark']);

            $data['status']=2;



         

             /* 保存 */

            $rows = $this->_discus_mod->edit($id, $data);

            if ($this->_discus_mod->has_error())
            {
                if ($_SESSION['ECMALL_WAP'] == 1) {
                    $this->show_warning($this->_discus_mod->get_error());
                }
                else {
                    $this->pop_warning($this->_discus_mod->get_error());
                }
                return;

            }

            /* 清除缓存 */

            $rows && $this->_clear_cache();
            if ($_SESSION['ECMALL_WAP'] == 1) {
                $this->show_message('操作成功', '返回列表', 'index.php?app=discus');
            }
            else {
                $this->pop_warning('操作成功', '返回列表', 'index.php?app=discus');
            }
        }

    }



    function view()

    {

        $id = empty($_GET['id']) ? 0 : intval($_GET['id']);

       

        if (!$id)

        {

            echo Lang::get('no_such_discus');



            return;

        }



           $discus_item = $this->_discus_mod->get(

            array(

                'conditions'  => ' id='.$id,      

                'fields'=>' *,discus.status as status',

                'join'=>'belong_to_order',

                )            

            );

            if (!$discus_item )

            {

                $this->show_warning('discus_empty');

                return;

            }

           

            $this->_curlocal(LANG::get('member_center'), url('app=member'),

                             LANG::get('discus'), url('app=discus'),

                             LANG::get('discus_view'));

            $this->_curitem('discus');

            $this->_curmenu('discus_view');





            //编辑器功能

            $this->assign('discus', $discus_item);

            $this->display('discus.view.html');

    }

   



    function ajax_col()

    {

        $id     = empty($_GET['id']) ? 0 : intval($_GET['id']);

        $column = empty($_GET['column']) ? '' : trim($_GET['column']);

        $value  = isset($_GET['value']) ? trim($_GET['value']) : '';

        $data   = array();

        if (in_array($column ,array('recommended','sort_order')))

        {

            $data[$column] = $value;

            $this->_discus_mod->edit($id, $data);

            if(!$this->_discus_mod->has_error())

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

                'name'  => 'discus_list',

                'url'   => 'index.php?app=discus',

            ),

        );

        return $menus;

    }



    function _assign_form()

    {

        

    }



    

    

    /* 清除缓存 */

    function _clear_cache()

    {        

        $cache_server =& cache_server();

        $cache_server->delete('function_get_app_discus_data_' . $this->visitor->get('manage_store'));

    }



}



?>
<?php



/**

 *    导航管理控制器

 *

 *    @author    Garbin

 *    @usage    none

 */

class My_discusApp extends MemberbaseApp

{

    var $_discus_mod;



    function __construct()

    {

        $this->My_discusApp();

    }



    function My_discusApp()

    {

        parent::__construct();

        $this->_discus_mod =& m('discus');
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

         $conditions.=" and discus.buyer_id=".$this->visitor->get('user_id');      

        $page   =   $this->_get_page(10);    //获取分页信息

        $discus_list = $this->_discus_mod->find(array(
        'conditions'  => '1=1 '.$conditions,
        'limit'   => $page['limit'],
        'fields'=>' *,discus.status as status',
        'count'   => true,  //允许统计
        'order'   =>'discus.buyer_addtime desc',//按照状态排序 360cd.cn  seema
        'join'=>'belong_to_order',
        ));

        $page['item_count']=$this->_discus_mod->getCount(); 

        $this->_format_page($page);   

        $this->assign('page_info', $page);  

        $this->assign('discus_list', $discus_list);

        /*print_r('<pre>');
        print_r($discus_list);
        print_r('</pre>');*/
        
        /* 当前位置 */

        $this->_curlocal(LANG::get('member_center'), url('app=member'),

                         LANG::get('discus'), url('app=discus'),

                         LANG::get('discus_list'));

        $this->_curitem('my_discus');

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

        $this->display('my_discus.index.html');

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

            $this->_curitem('my_discus');

            $this->_curmenu('discus_view');





            //编辑器功能

            $this->assign('discus', $discus_item);

            $this->display('discus.view.html');

    }



    /**

     *    添加地址

     *

     *    @author    Garbin

     *    @return    void

     */

    function add()

    {

        $order_id=isset($_GET['order_id'])?intval($_GET['order_id']):0;

        if(!$order_id)

        {

            $this->show_warning('Hack_Attemp');

            return;

        }

        if($discus_info=$this->_discus_mod->get('order_id='.$order_id))

        {

            $this->show_message('add_ok','back_list','index.php?app=my_discus&act=edit&id='.$discus_info['id']);

            return;

        }

        $order_model= &m('order');

        $order_info=$order_model->get($order_id);

        if(!$order_info)

        {

            $this->show_warning('order_empty');

            return;            

        }

        $data = array();

        //得到字段提交上来的信息

        $data['seller_name']=trim($order_info['seller_name']);

        $data['buyer_name']=trim($order_info['buyer_name']);

        $data['buyer_id']=$order_info['buyer_id'];

        $data['seller_id']=$order_info['seller_id']; 

        $data['order_id']=$order_id;

        $id = $this->_discus_mod->add($data);

        if (!$id)

        {

            $this->show_warning($this->_discus_mod->get_error());

            return;

        }
        $order_model->edit($order_id,array('status'=>ORDER_REFUND));
        $order_log=&m('orderlog');
        $order_log->add($order_log_data);

        $this->show_message('add_ok','back_list','index.php?app=my_discus&act=edit&id='.$id);

        return;

       

    }



    function _get_buyer_type()

    {



        return array('no_recive'=>Lang::get('no_recive'),'other'=>Lang::get('other'));

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
            $this->assign("id", $id);
            $this->assign('options',array('buyer_type'=>$this->_get_buyer_type()));

            $this->_curlocal(LANG::get('member_center'), url('app=member'),

                             LANG::get('discus'), url('app=discus'),

                             LANG::get('discus_edit'));

            $this->_curitem('my_discus');

            $this->_curmenu('discus_edit');
            $this->_assign_form();

            header('Content-Type:text/html;charset=' . CHARSET);

            $this->assign('discus', $discus_item);

            header("Content-Type:text/html;charset=" . CHARSET);

            $this->display('my_discus.form.html');

        }

        else

        {

            $data = array();

            $data['buyer_type']=trim($_POST['buyer_type']);

            $data['buyer_addtime']=gmtime();           

            $data['buyer_remark']=trim($_POST['buyer_remark']);

            $data['status']=1;

             /* 保存 */

            $rows = $this->_discus_mod->edit($id, $data);

            if ($this->_discus_mod->has_error())

            {

                $this->show_warning($this->_discus_mod->get_error());

                return;

            }

            $seller_list=$this->_discus_mod->get($id);
            if (!$seller_list) {
                return ;
            }

            //退货时 给卖家发送信息 360cd.cn seema

            $user_id=$seller_list['seller_id']; 

            $title='退货/退款提醒';

            $buyer_name=trim($seller_list['buyer_name']);

            $content=$buyer_name.'正在申请退货,退货原因：'.trim($_POST['buyer_remark']);

            $msg_mod = &m('message');
            $msg_mod->send(MSG_SYSTEM, $user_id, $title, $content);

            /* 清除缓存 */

            $rows && $this->_clear_cache();

            $this->show_message('操作成功','back_list','index.php?app=my_discus');

        }

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

                'url'   => 'index.php?app=my_discus',

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
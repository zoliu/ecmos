<?php
/* 店铺分类控制器 */
class DiscusApp extends BackendApp
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
        $this->assign('discus_status', $this->_discus_mod->get_discus_status());
         
    }

    /* 管理 */
    function index()
    {
        $conditions='';
        $conditions .= $this->_get_query_conditions(array(
            array(
                'field' => 'order_alias.order_sn',         //可搜索字段title
                'equal' => 'LIKE',          //等价关系,可以是LIKE, =, <, >, <>
                'assoc' => 'AND',           //关系类型,可以是AND, OR
                'name'  => 'order_sn',         //GET的值的访问键名
                'type'  => 'string',        //GET的值的类型
            ), 
            array(
                'field' => 'discus.buyer_name',         //可搜索字段title
                'equal' => 'LIKE',          //等价关系,可以是LIKE, =, <, >, <>
                'assoc' => 'AND',           //关系类型,可以是AND, OR
                'name'  => 'buyer_name',         //GET的值的访问键名
                'type'  => 'string',        //GET的值的类型
            ),
             array(
                'field' => 'discus.seller_name',         //可搜索字段title
                'equal' => 'LIKE',          //等价关系,可以是LIKE, =, <, >, <>
                'assoc' => 'AND',           //关系类型,可以是AND, OR
                'name'  => 'seller_name',         //GET的值的访问键名
                'type'  => 'string',        //GET的值的类型
            ),
          
        ));

        $page   =   $this->_get_page(10);   //获取分页信息
        
          //获取统计数据
        $discus_list = $this->_discus_mod->find(array(
        'conditions'  => '1=1 '.$conditions,
        'limit'   => $page['limit'],
        'fields'=>' *,discus.status as status',
         'join'=>'belong_to_order',
        'order'   =>'discus.buyer_addtime desc',//按照状态排序 360cd.cn  seema
        'count'   => true   //允许统计
        ));
        //echo $this->_discus_mod->last_sql;
        $page['item_count']=$this->_discus_mod->getCount(); 
        $this->_format_page($page);   
        $this->assign('page_info', $page);  
        $this->assign('discus_list', $discus_list);
        

        //引入jquery表单插件
         $this->import_resource(array(
            'script' => 'jquery.plugins/jquery.validate.js,jqtreetable.js,inline_edit.js,jquery.ui/jquery.ui.js,jquery.ui/i18n/' . i18n_code() . '.js',
            'style'  => 'res:style/jqtreetable.css,jquery.ui/themes/ui-lightness/jquery.ui.css'
        ));         
       
        $this->display('discus.index.html');
    }

    function change_money()
    {
        $id = empty($_GET['id']) ? 0 : intval($_GET['id']);
       
        if (!$id)
        {
            echo Lang::get('no_such_discus');

            return;
        }

        if(!IS_POST)
        {
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
            //退款金额处理 360cd.cn seema
            if ($discus_item['pay_money']) {
                $discus_item['order_amount']=$discus_item['order_amount']+$discus_item['pay_money'];
            }
            $order_id=$discus_item['order_id'];
            $orderextm_model= &m('orderextm');
            $orderextm_info=$orderextm_model->get($order_id);
            $this->assign('order_ext',$orderextm_info);

             $this->import_resource(array(
                'script' => 'jquery.plugins/jquery.validate.js'
            ));

            //编辑器功能
            $this->assign('discus', $discus_item);
            $this->display('discus.money.html');
        }else{
            if($this->check_is_pay($id)) {
                $this->show_warning('已退款过，不能再次退款');
                return;
            }
            
            //退还余额  360cd.cn  seema
            $refund_money = isset($_POST['refund_money'])?floatval($_POST['refund_money']):0;
            $order_id = isset($_POST['order_id'])?intval($_POST['order_id']):0;
            $order_amount = isset($_POST['order_amount'])?floatval($_POST['order_amount']):0;
            if(!$order_id)
            {
                 $this->show_warning('order_empty');
                return;
            }

            $order_info = $this->_get_order($order_id);
            $buyer_id = $order_info['buyer_id'];
            $seller_id = $order_info['seller_id'];
            
            //---www.360cd.cn  Mosquito---
            //变更余额
            import('zllib/money.lib');
            Money::init()->refund_money_chang($buyer_id, $seller_id, $refund_money, $order_amount, $order_id);
            
            $money_log_model = &m('money_log');
            $money_log_model->edit("user_id = {$buyer_id} AND party_id = {$seller_id} AND order_id = {$order_id}", 'status = ' . MONEY_L_S_NO);
            
            $this->_update_discus_status($order_id, 4);
            $this->_update_order($order_id);//关闭订单 360cd.cn seema

            //管理员审核后 给买家发送信息 360cd.cn seema
            $user_id = $buyer_id; 
            $title='退货/退款处理结果提醒';
            $order_sn=$order_info['order_sn'];
            $this->_discus_mod->edit($id,array('is_pay'=>1));
            $content='您的订单'.$order_sn.'申请退货/退款,管理员已经处理，请到我的退货/退款查看';
            
            $msg_mod = &m('message');
            $msg_mod->send(MSG_SYSTEM, $user_id, $title, $content);

            $this->show_message('edit_ok','back_list','index.php?app=discus' );
            
        }
    
    }

    function check_is_pay($id)
    {
        $where=" is_pay=1 and id=".$id;
        $result=$this->_discus_mod->get($where);
        if(!$result)
        {
            return 0;
        }
        else{
            return 1;
        }
    }
    
    //关闭订单 360cd.cn seema
    function _update_order($order_id){
        $order_model= &m('order');
        $data['status']=0;
        return $order_model->edit($order_id,$data);        
    }
    
    function _get_order($order_id)
    {
        $order_model= &m('order');
        $order_info=$order_model->get($order_id);
        if($order_info)
        {
            return $order_info;
        }
        return;
    }

    function _update_discus_status($order_id,$status)
    {
        $discus_model= &m('discus');
        return $discus_info=$discus_model->edit('order_id='.$order_id,array('status'=>$status));
        
    }
    
    //退还代金券记录  360cd.cn  seema
    function _update_mcouponlog($params=array())
    {
        $mcouponlog_model= &m('mcoupon_log');
        return $mcouponlog_info=$mcouponlog_model->add($params);      
    }

    /* 新增 */
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
           


            //编辑器功能
            $this->assign('discus', $discus_item);
            $this->display('discus.view.html');
    }

    /* 编辑 */
    /*function edit()
    {
        $id = empty($_GET['id']) ? 0 : intval($_GET['id']);
        if (!IS_POST)
        {
            // 是否存在 
            $discus_item = $this->_discus_mod->get_info($id);
            if (!$discus_item )
            {
                $this->show_warning('discus_empty');
                return;
            }
            $this->assign('discus', $discus_item);
             $this->import_resource(array(
                                    'script' => 'jquery.plugins/jquery.validate.js,jqtreetable.js,inline_edit.js,jquery.ui/jquery.ui.js,jquery.ui/i18n/' . i18n_code() . '.js',
                                    'style'  => 'res:style/jqtreetable.css,jquery.ui/themes/ui-lightness/jquery.ui.css'));
             $template_name = $this->_get_template_name();
             $style_name    = $this->_get_style_name();
            //编辑器功能
             $this->assign('curr_status',3);
            $this->display('discus.form.html');
        }
        else
        {
            $data = array();              
            $data['status']=$_POST['status'];
            $data['admin_addtime']=gmtime();
            $data['admin_remark']=trim($_POST['admin_remark']);       
          
            // 保存 
            $rows = $this->_discus_mod->edit($id, $data);
            if ($this->_discus_mod->has_error())
            {
                $this->show_warning($this->_discus_mod->get_error());
                return;
            }

            $this->show_message('edit_ok',
                'back_list',    'index.php?app=discus',
                'edit_again',   'index.php?app=discus&amp;act=edit&amp;id=' . $id
            );
        }
    }*/
         
    /* 删除 */
    function drop()
    {
        $id = isset($_GET['id']) ? trim($_GET['id']) : '';
        if (!$id)
        {
            $this->show_warning('no_discus_to_drop');
            return;
        }
        $ids = explode(',', $id);
        if (!$this->_discus_mod->drop($ids))
        {
            $this->show_warning($this->_discus_mod->get_error());
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
            $this->_discus_mod->edit($id, array('sort_order' => $sort_orders[$key]));
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
}
?>
<?php
class pointApp extends MallbaseApp
{
	function index()
	{
		$conditions='';
        $conditions .= $this->_get_query_conditions(array(
            array(
                'field' => 'need_point',         //可搜索字段title
                'equal' => '<',          //等价关系,可以是LIKE, =, <, >, <>
                'assoc' => 'AND',           //关系类型,可以是AND, OR
                'name'  => 'from_jifen',         //GET的值的访问键名
                'type'  => 'string',        //GET的值的类型
            ), array(
                'field' => 'need_point',         //可搜索字段title
                'equal' => '>',          //等价关系,可以是LIKE, =, <, >, <>
                'assoc' => 'AND',           //关系类型,可以是AND, OR
                'name'  => 'to_jifen',         //GET的值的访问键名
                'type'  => 'string',        //GET的值的类型
            ),
        ));

        $page   =   $this->_get_page(8);   //获取分页信息
        $point_goods_mod= &m('point_goods');
        
          //获取统计数据
        $point_goods_list = $point_goods_mod->find(array(
        'conditions'  => '1=1 '.$conditions,
        'limit'   => $page['limit'],
        'count'   => true   //允许统计
        ));
        $page['item_count']= $point_goods_mod->getCount(); 
        $this->_format_page($page);   
        $this->assign('page_info', $page);  
        $this->assign('data', $point_goods_list);
         $this->_curlocal(               
                LANG::get('credits_shop'),
                'index.php?app=point'
            );
        $this->_get_top4();
        $this->get_log();
        $this->assign('userinfo',$this->_get_user());
		$this->display('point_goods.list.html');
	}

    function _get_user()
    {
        if($this->visitor && $user_id=$this->visitor->get('user_id'))
        {
            $member_ext= &m('member_ext');
            $where=array(
                    'conditions'=>' member_ext.user_id='.$user_id,
                    'join'=>'belongs_to_user',
                );
            $member_info=$member_ext->get($where);
            return $member_info;
        }else{
            return 0;
        }
    }

    function _get_top4()
    {
        $conditions=" and point_goods.need_point>0 and point_goods.need_point<100";
        $point_goods_mod= &m('point_goods');
        
          //获取统计数据
        $point_goods_list = $point_goods_mod->find(array(
        'conditions'  => '1=1 '.$conditions,
        'limit'   => 4,
        'count'   => false   //允许统计
        ));
        $this->assign('gift',$point_goods_list);

    }

    function get_log()
    {
        $logs_mod= &m('point_goods_log');
        $data = $logs_mod->find(array(
        'conditions' => '1=1 '. $conditions,
        'limit' => 50,
        'join'=>'belong_to_point_goods',
        'order'=>' point_goods_log.id desc'
        ));
        $this->assign('logs_list',$data);
    }

    function ajax_list()
    {
        $page   =   $this->_get_page(8);   //获取分页信息
        $point_goods_mod= &m('point_goods');
        
          //获取统计数据
        $point_goods_list = $point_goods_mod->find(array(
        'conditions'  => '1=1 '.$conditions,
        'limit'   => $page['limit'],
        'count'   => false   //允许统计
        ));
           
        if($point_goods_list)
        {
            $this->json_result($point_goods_list);
            return;
        }else{
             $this->json_error();
            return;
        }
        
        
    }

	function view()
	{

		$id=isset($_GET['id'])?intval($_GET['id']):0;
		if(!$id)
		{
			$this->show_warning('goods_id_not_empty');
			return;
		}
		$point_goods_mod= &m('point_goods');
		$goods_info=$point_goods_mod->get($id);
		if(!$goods_info)
		{
			$this->show_warning('goods_no_exists');
			return;
		}
		$this->_curlocal(
                LANG::get('credits_shop'),
                'index.php?app=point'
               
            );

        $this->_get_change_log($id);
        $this->_get_hot_log();

        $this->assign('userinfo',$this->_get_user());
		$this->assign("data",$goods_info);
        $this->assign('userinfo',$this->_get_user());
		$this->display('point_goods.view.html');
	}

    function _get_change_log($id)
    {
        $conditions=" and point_goods_log.goods_id={$id} ";
        $logs_mod= &m('point_goods_log');
        $data = $logs_mod->find(array(
            'conditions' => '1=1 '. $conditions,
            'limit' => 15,
            'join'=>'belong_to_point_goods',
            'order'=>' point_goods_log.addtime desc'
        ));
        $this->assign('change_list',$data);
    }

    function _get_hot_log()
    {
        $conditions="  GROUP BY point_goods_log.goods_id ";
        $logs_mod= &m('point_goods_log');
        $data = $logs_mod->find(array(
        'conditions' => '1=1 '. $conditions,
        'limit' => 5,
        'join'=>'belong_to_point_goods',
        'order'=>' SUM(point_goods_log.goods_num) desc'
        ));
        $this->assign('hot_list',$data);
    }

    function buy()
    {
        $id=isset($_GET['id'])?intval($_GET['id']):0;
        if(!$id)
        {
            $this->show_warning('id_is_null');
            return;
        }
        $user_id=isset($this->visitor) && $this->visitor->get('user_id')? $this->visitor->get('user_id'):0;
        if(!$user_id)
        {   
             $this->show_warning('login_please');
             return;
        }
        $num=isset($_GET['prizenum'])?intval($_GET['prizenum']):0;
        if(!$num)
        {
            $this->show_warning('num_is_null');
            return;
        }
        $mod= &m('point_goods');
        $val=$mod->applyPointGoods($user_id,$id,$num);
        $msg='';
        switch ($val) {
            case '-2':
                $msg=LANG::get('user_or_goods_no_exists');
                break;
            case '-3':
                $msg=LANG::get('stock_no_yes');
                break;
            case '-4':
                $msg=LANG::get('point_no_yes');
                break;
            case '-5':
                $msg=LANG::get('buy_num_is_more');
                break;
            default:
                # code...
                break;
        }
        if($val!==1)
        {
            $this->show_warning($msg);
            return;
        }

        $this->show_message('success','index.php?app=point_logs&act=point_goods');
    }
}
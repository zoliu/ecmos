<?php
class biz_common
{
	function check_vip($is_store,$user_id)
	{
		$user_info=$this->get_user($user_id);
		if($is_store){
            //var_dump($info);exit();
            if ($user_info['union_stor']==1 && $user_info['ustor_status']==1) {
               return 2;
            }else{
               return 1;
            }
        }else{
        	if ($user_info['union_stor']==1 && $user_info['ustor_status']==1) {
               return 2;
            }else{
               return 0;
            }
            
        }
	}
	function get_user($user_id)
	{
		//360cd.cn
		$member_model=&m('member');
		$where=$user_id;
		$member_data=$member_model->get($where);
		if(!$member_data)
		{
			//此处填写数据不存在内容
			return 0;
		}
		return $member_data;
		//360cd.cn
	}

	function run($o)
	{
		import('zllib/biz.lib');
		$biz=new bizArticle();
		$nav_list=$biz->get_nav_category(11);
		$o->assign('anav_list',$nav_list);

	}

	function set_deduct_store($store_id)
	{
		$_SESSION['deduct_store']=$store_id;
	}

	function check_deduct()
	{
		
		$d_id=$_SESSION['deduct_id'];
		$s_id=$_SESSION['deduct_store'];
		if(empty($d_id) && !empty($s_id))
		{
			$_SESSION['deduct_id']=$s_id;
		}
	}

}
?>
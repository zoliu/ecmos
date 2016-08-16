<?php
/* 文章 article */
class Point_setModel extends BaseModel
{
    var $table  = 'point_set';
    var $prikey = 'id';
    var $_name  = 'point_set';
	 var $_relation = array(
 // 一个店铺属于一个会员
       
		
		);
		
		function getPointSet()
		{
		  $data_list=$this->find();
		  if($data_list)
		  {
		    $data_list=current($data_list);
			return isset($data_list['config'])?unserialize($data_list['config']):null;   			
		  }
		  return ;
		}
		//注册赠送积分		
		function registerPoint($user_id)
		{
			$point_set=$this->getPointSet();
			if($point_set)
			{
				$register_set=$point_set['reg_point'];
				$this->registerUserToExt($user_id);
				$point= &m('point_logs');
				$point->change_point($user_id,$register_set,gmtime(),'reg_point',$reamrk='');
							}
		}
		//登陆赠送积分
		function loginPoint($user_id)
		{
			if($this->checkLoginTime($user_id))
			{
				$point_set=$this->getPointSet();
				if($point_set)
				{
					$login_set=$point_set['login_point'];
					$point= &m('point_logs');
					$point->change_point($user_id,$login_set,gmtime(),'login_point',$reamrk='');
									}
			}
		}
		
		//购物赠送积分
		function buyerPoint($user_id,$price)
		{
			$point_set=$this->getPointSet();
			if($point_set)
			{
				$buyer_set=$point_set['buy_get_point'];
				$point_buyer=intval($buyer_set*$price);
				$point= &m('point_logs');
				$point->change_point($user_id,$point_buyer,gmtime(),'buy_get_point',$reamrk='');								
			}
		}
		
		//检测登陆次数，在同一天
		function checkLoginTime($user_id)
		{
		  $point= &m('point_logs');
		  $curr_date=date("Y-m-d");
		  $start_time=gmstr2time($curr_date." 00:00:00");
		  $end_time=gmstr2time($curr_date." 23:59:59");
		  $is_exists=$point->get(' addtime>'.$start_time.' and addtime<'.$end_time." and type='login_point' and user_id=".$user_id);
		  if($is_exists)
		  {
		    return 0;
		  }
		  return 1;
		
		}
		
		//根据积分得到可兑换的现金
		function getMoneyByPoint($point)
		{
			// $point= &m('point_logs');
			//$recharge_set=$point->getPointSet('recharge_to_money');
			$point_set=$this->getPointSet();
			if($point_set)
			{
			  $per=floatval($point_set['recharge_to_money']);
			  $money=floor($point*$per);
			  return $money;
			}
			return;
		}
		
		//根据用户ID得到积分可兑现用的现金
		function getMoneyByUserId($user_id)
		{
			$user_ext_mod= &m('member_ext');
			$user_ext=$user_ext_mod->get(' user_id='.$user_id);
			if($user_ext)
			{
			  $point=$user_ext['user_point'];
			  $money=$this->getMoneyByPoint($point);
			  return $money;
			}
			return ;		
		}
		//根据现金得到需要兑现的积分
		function getPointByMoney($price)
		{
			$point_set=$this->getPointSet();
			if($point_set)
			{
			  $per=floatval($point_set['recharge_to_money']);
			  $point=ceil($price/$per);
			  return $point;
			}
			return;
		}
		
		//
		function changeToMoney($user_id,$price)
		{
		  $total_money=$this->getMoneyByUserId($user_id);
		  if($total_money>=floatval($price))
		  {
		    $point=$this->getPointByMoney($floatval($price));
			$point= &m('point_logs');
			$point->change_point($user_id,$point,gmtime(),'recharge_to_money',$reamrk='');	
		  }
		}
		
		//注册用户到扩展表
		function registerUserToExt($user_id)
		{
			$user_mod=&m('member_ext');
			$is_exists=$user_mod->get(' user_id='.$user_id);
			if(!$is_exists)
			{
				 $data = array(
					'user_id'  => $user_id,
					'user_level_id'  => 0,
					'status' => 1,
				);
				$user_mod->add($data);			
			}
		
		}
		
		
	
}

?>
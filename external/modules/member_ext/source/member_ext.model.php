<?php

/* 文章 article */
class Member_extModel extends BaseModel
{
    var $table  = 'member_ext';
    var $prikey = 'id';
    var $_name  = 'member_ext';
	 var $_relation = array(
 // 一个店铺属于一个会员
	        'belongs_to_user' => array(
	            'model'         => 'member',
	            'type'          => BELONGS_TO,
	            'foreign_key'   => 'user_id',
	            'reverse'       => 'has_member_ext',
	        ),
			'belongs_to_member_level' => array(
	            'model'         => 'member_level',
	            'type'          => BELONGS_TO,
	            'foreign_key'   => 'id',
	            'reverse'       => 'has_member_level_ext',
	        ),
		
		);
		
		
		function get_level_name_by_user_id($user_id)
		{
			$data=$this->get(array(
				'conditions'=>' status=1 and user_id='.$user_id,
				'join'=>'belongs_to_member_level',
			));
			if(!$data)
			{
				return '';

			}else{
				return $data['level_name'];
			}		
		}

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
				$user_ext_id=$user_mod->add($data);
				$is_exists=$user_mod->get($user_ext_id);
			}
			return $is_exists;
		
		}
}

?>
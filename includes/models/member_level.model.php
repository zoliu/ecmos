<?php

/* 文章 article */
class Member_levelModel extends BaseModel
{
    var $table  = 'member_level';
    var $prikey = 'id';
    var $_name  = 'member_level';
	var $_relation = array(
 // 一个会员拥有多个收货地址
        'has_member_level_ext' => array(
            'model'       => 'member_ext',
            'type'        => HAS_MANY,
            'foreign_key' => 'user_level_id',
            'dependent'   => true
        ),);
		
		function get_options()
		{
		  $data=$this->find();
		  return $this->getOption($data,'id','level_name');		
		}
		
	   private function getOption($data=array(),$key,$val)
	   {
			$arr=array();
			foreach($data as $v)
			{
				$arr[$v[$key]]=$v[$val];
			}	 
			return $arr;
	   }
}

?>
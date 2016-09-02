<?php
class adminModel extends BaseModel
{
    var $table  = 'admin';
    var $prikey = 'user_id';
    var $_name  = 'admin';

    function checkPriv($user_id,$password)
    {
    	$admin_details=$this->get($user_id);
    	if(!$admin_details)
    	{
    		$this->_error('管理员权限不存在');
    		return -1;
    	}
    	$vcode=$admin_details['vcode'];
    	if($admin_details['password']==$this->_cpassword($password,$vcode))
    	{

    		return 1;
    	}
    	$this->_error("管理员密码错误");
    	return 0;
    }
    //用户进行登陆操作
    function doLogin($user_name,$password)
    {
    	//360cd.cn
    	$member_model=&m('member');
    	$user_info=$member_model->get("user_name='{$user_name}'");
    	if(!$user_info)
    	{
    		$this->_error('管理员不存在');
    		return -2;
    	}
    	$result=$this->checkPriv($user_info['user_id'],$password);
    	return $result>0?$user_info['user_id']:$result;
    }

    //根据输入的密码与vcode创建密码
	function _cpassword($password,$vcode)
	{
		return md5(md5($password).$vcode);
	}

	function update($user_id,$password)
	{
		$vcode=rand_code(6);
		$data=array(
			'password'=>$this->_cpassword($password,$vcode),//360cd.cn
			'vcode'=>$vcode,//360cd.cn
		);
		if(!$this->get($user_id))
		{
			$data['user_id']=$user_id;
			$this->add($data);
		}else{			
			$this->edit($user_id,$data);
		}
		return 1;
	}
}
?>
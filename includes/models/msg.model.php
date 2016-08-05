<?php

/* 店铺等级 sgrade */
class MsgModel extends BaseModel
{
    var $table  = 'msg';
    var $prikey = 'id';
    var $_name  = 'msg';
    var $_relation  =   array(
       
    );

    function init($user_id)//初始化用户信息，登陆时
    {
    	//360cd.cn
    	$member_model=&m('member');
    	$where=$user_id;
    	$user_info=$member_model->get($where);
    	if(!$user_info)
    	{
    		return 0;
    	}
    	$user_exists=$this->check_user($user_id);
    	$data=array();
    	if($user_exists)
    	{
    		$data['mobile']=$user_info['phone_mob'];
    		return $this->edit('user_id='.$user_id,$data);
    	}
    	else{
    		$data['mobile']=$user_info['phone_mob'];
    		$data['user_name']=$user_info['user_name'];
    		$data['user_id']=$user_info['user_id'];
    		return $this->add($data);
    	}
    }

    function check_user($user_id)
    {
    	$user=$this->get('user_id='.$user_id);
    	if(!$user)
    	{
    		return 0;
    	}

    	return 1;
    }


    function sms_to($user_id,$remark='',$code='captcha')
    {
    	//360cd.cn
    	$member_model=&m('member');
    	$where=$user_id;
    	$member_data=$member_model->get($where);
    	if(!$member_data)
    	{
    		//此处填写数据不存在内容
    		return -1;
    	}
    	//360cd.cn
    	$mobile=$member_data['phone_mob'];
    	if(empty($mobile))
    	{
    		return -2;
    	}
    	//return sendMsg($user_id,$remark,$remark);
        $remark=replaceLog($remark,array('user_name'=>$member_data['user_name']));
    	return $this->send_captcha($code,$mobile,$remark);
    }

    function to_sms($mobile,$remark)
    {
    	import('sms.lib');
    	return to_sms($mobile,$remark);
    }


    function send_captcha($code,$mobile,$remark)
    {
        if(empty($code))
        {
            return 0;
        }
        $regex = "/13[0-9]{9}|15[0|1|2|3|5|6|7|8|9]d{8}|18[0|5|6|7|8|9]d{8}/";
        preg_match_all($regex,$mobile, $phones);
        if(!$phones){
            return -1;
        }
        $rand_code=rand_code(6);
        $_SESSION[$code]=$rand_code;
        $_SESSION[$code.'_limit_time']=gmtime();
        $remark=replaceLog($remark,array('captcha'=>$rand_code));
        return $this->to_sms($mobile,$remark);
    }
    //检查验证码session是否过期
    function check_captcha_time($code)
    {
        if (isset($_SESSION[$code.'_limit_time'])) {
           return 1;
        }
        if($_SESSION[$code.'_limit_time']>gmtime()-120)
        {
            return 1;
        }else{
            return 0;
        }
    }
    function check_captcha($code)
    {
        if(empty($code))
        {
            return 0;
        }
        return $_SESSION[$code];
    }


    //验证
    function register_captcha($mobile)
    {
        
        $code='register_mobile';
        $remark="尊敬的{$mobile}您好，您注册的验证码是#captcha#，请不要告诉他人！以防被骗";
        return $this->send_captcha($code,$mobile,$remark);
    }

    function modify_password_captcha($user_id)
    {
        $code='modify_password';
        $remark="尊敬的#user_name#您好，您修改帐户密码的验证码是#captcha#，请不要告诉他人！以防被骗";
        return $this->sms_to($user_id,$remark,$code);
    }

    function modify_pay_passwd_captcha($user_id)
    {
        $code='modify_pay_passwd';
        $remark="尊敬的#user_name#您好，您修改支付密码的验证码是#captcha#，请不要告诉他人！以防被骗";
        return $this->sms_to($user_id,$remark,$code);
    }
    
    function tx_money_captcha($user_id)
    {
        $code='tx_money';
        $remark="尊敬的#user_name#您好，您的提现验证码是#captcha#，请不要告诉他人！以防被骗";
        return $this->sms_to($user_id,$remark,$code);
    }

    function turn_out_captcha($user_id)
    {
        $code='turn_out';
        $remark="尊敬的#user_name#您好，您的转帐验证码是#captcha#，请不要告诉他人！以防被骗";
        return $this->sms_to($user_id,$remark,$code);
    }

    function modify_phone($mobile)
    {
        $code='modify_phone';
        $remark="尊敬的#user_name#您好，您修改手机的验证码是#captcha#，请不要告诉他人！以防被骗";
        return $this->send_captcha($code,$mobile,$remark);
    }
    function modify_email_captcha($user_id)
    {
        $code='modify_email';
        $remark="尊敬的#user_name#您好，您修改邮箱的验证码是#captcha#，请不要告诉他人！以防被骗";
        return $this->sms_to($user_id,$remark,$code);
    }







}

?>
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
	/**
	 * 基于用户信息的短信发送
	 * @param  int $user_id 用户iD
	 * @param  string $remark  备注
	 * @param  string $code    代码
	 * @return [type]          [description]
	 */
	function sms_to($user_id, $remark = '', $code = 'captcha') {
		//360cd.cn
		$member_data = LM('member')->where('user_id=' . $user_id)->get();
		if (!$member_data) {
			//此处填写数据不存在内容
			return -1;
		}
		//360cd.cn
		$mobile = $member_data['phone_mob'];
		if (empty($mobile)) {
			return -2;
		}
		//return sendMsg($user_id,$remark,$remark);
		$remark = replaceLog($remark, array('user_name' => $member_data['user_name']));
		return $this->send_captcha($code, $mobile, $remark);
	}
	/**
	 * 调用底层发送网关
	 * @param  [type] $mobile [description]
	 * @param  [type] $remark [description]
	 * @return [type]         [description]
	 */
	function to_sms($mobile, $remark) {
		import('sms.lib');
		return to_sms($mobile, $remark);
	}
	/**
	 * 发送验证码用于类别类型的短信发送
	 * @param  string $code   发送类别
	 * @param  string $mobile 手机号码
	 * @param  string $remark 备注
	 * @return [type]         [description]
	 */
	function send_captcha($code, $mobile, $remark) {
		if (empty($code)) {
			return 0;
		}
		$regex = "/13[0-9]{9}|15[0|1|2|3|5|6|7|8|9]d{8}|18[0|5|6|7|8|9]d{8}/";
		preg_match_all($regex, $mobile, $phones);
		if (!$phones) {
			return -1;
		}
		$rand_code = rand_code(6);
		$_SESSION[$code] = $rand_code;
		$_SESSION[$code . '_limit_time'] = gmtime();
		$remark = replaceLog($remark, array('captcha' => $rand_code));
		return $this->to_sms($mobile, $remark);
	}
	/**
	 * 检查指定类别的验证码是否过期
	 * @param  [type] $code [description]
	 * @return [type]       [description]
	 */
	function check_captcha_time($code) {
		if (isset($_SESSION[$code . '_limit_time'])) {
			return 1;
		}
		if ($_SESSION[$code . '_limit_time'] > gmtime() - 120) {
			return 1;
		} else {
			return 0;
		}
	}

	/**
	 * 验证码检测，用于直接检查验证码，调用SESSION里的值
	 * @param  [type] $code [description]
	 * @return [type]       [description]
	 */
	function check_captcha($code) {
		if (empty($code)) {
			return 0;
		}
		return $_SESSION[$code];
	}

	/**
	 * 发送短信的集成接口，以供外部调用
	 * @param  string $code    验证码所属的验证类别，比如注册，找回密码
	 * @param  string $user_id 用户ID
	 * @param  string $mobile  手机号码
	 * @return [type]          状态码
	 */
	function toSms($code, $user_id = '', $mobile = '') {
		if (empty($code)) {
			return 0;
		}
		if ($code != 'register_mobile' && !$user_id) {
			return -1;
		}
		if ($code == 'register_mobile' && empty($mobile)) {
			return -21;
		}
		switch ($code) {
		case 'turn_out':
			$result = $this->turn_out_captcha($user_id);
			break;
		case 'tx_money':
			$result = $this->tx_money_captcha($user_id);
			break;
		case 'modify_pay_passwd':
			$result = $this->modify_pay_passwd_captcha($user_id);
			break;
		case 'modify_password':
			$result = $this->modify_password_captcha($user_id);
			break;
		case 'register_mobile':
			$result = $this->register_captcha($mobile);
			break;
		case 'modify_phone':
			$result = $this->modify_phone($mobile);
			break;
		case 'modify_email':
			$result = $this->modify_email_captcha($user_id);
			break;
		default:
			$result = -12;
			break;
		}
		if ($result > 0) {
			return 1;
		}
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
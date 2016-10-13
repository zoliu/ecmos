<?php

	function getSmsConfig()
	{
	 return array('uid'=>Conf::get('msg_pid'),'key'=>Conf::get('msg_key'));
	}
    function sendSms($uid,$key,$to,$content)
	{
		 $url='http://api.360cd.cn/sms-send?uid='.$uid.'&key='.$key.'&phone='.$to.'&content='.$content;
		 $res = pushURI($url);
		 if($res=='')
		 {
		  return 0;	 
		 }
		 return 1;
	}

	function to_sms($to_mobile,$content)
	{
		$sms=getSmsConfig();
		$res=sendSms($sms['uid'],$sms['key'],$to_mobile,$content);
		$time=gmtime();
		
		$add_msglog = array(
			'user_id' => '',
			'user_name' => '',
			'to_mobile' => $to_mobile,
			'content' => $content,
			'state' => $res,
			'time' => $time,
		);
		$msglog_mod= &m('msglog');
		$msglog_mod->add($add_msglog);		
		return $res;
	}

	function toSms($user_id,$user_name,$to_mobile,$content)
	{	
		$sms=getSmsConfig();
		$num=1;
		if($user_id)
		{
			$msg_mod= &m('msg');
			$user_msg= $msg_mod->get('user_id='.$user_id);
			if($user_msg)
			{
				$num=$user_msg['num'];				
			}
		}
		if($num<1)
		{
		  return -50001;
		}
		$res=sendSms($sms['uid'],$sms['key'],$to_mobile,$content);
		$time=gmtime();
		
		$add_msglog = array(
			'user_id' => $user_id,
			'user_name' => $user_name,
			'to_mobile' => $to_mobile,
			'content' => $content,
			'state' => $res,
			'time' => $time,
		);
		$msglog_mod= &m('msglog');
		$msglog_mod->add($add_msglog);
		if($user_id)
		{
			$user_msg->edit('user_id='.$user_id,array('num'=>$num-1));		
		}
		return $res;
	}
	/**远程处理**/
    function pushURI($url)
    {
      if(function_exists('file_get_contents'))
		{
			$file_contents = file_get_contents($url);
		}
		else
		{
			$ch = curl_init();
			$timeout = 5;
			curl_setopt ($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$file_contents = curl_exec($ch);
			curl_close($ch);
		}
		return $file_contents;
    }


     /**
     *    获取可用功能列表
     *
     *    @author    andcpp
     *    @return    array
     */
    function sms_functions()
    {
        $arr = array();        
        $arr[] = 'buy'; //来自买家下单通知   
        $arr[] = 'send'; //卖家发货通知买家   
		$arr[] = 'check';//来自买家确认通知   
		$arr[] = 'register';//来自买家确认通知 
		$arr[] = 'modifypassword';//来自买家确认通知 
		$arr[] = 'setpaypassword';//来自买家确认通知
		$arr[] = 'modifypaypassword';//来自买家确认通知
        return $arr;
    }


?>
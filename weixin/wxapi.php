<?php
include ('base.inc.php');
class weixinapi extends wxapi
{
	function index()
	{
		//$appid='wxf00539299e806928';
	//	$appkey='2f961e32c9db95feb665500d1052f271';
		//$access_token='50082046';
	  $this->check();
	}

    function onMsgText($xml)
    {
         $this->get_replay_msg($xml['Content'],$xml['ToUserName'],$xml['FromUserName']);
    }

    function get_replay_msg($keyword,$toid,$fromid)
    {
      $msg=$this->send($toid,$fromid,'你好');
       echo $msg; 

    }

}
?>
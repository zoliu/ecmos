<?php
include (ROOT_PATH.'/weixin/base.inc.php');
/* 微信公众平台 */

class WxapiApp extends MallbaseApp {
  var $api;
  function __construct()
  {
    parent::__construct();
    $this->api=new weixinapi();
  }

  function index()
	{
    $this->initConfig();
		$this->api->check();
	}
	

  function oauthUser()
  {
    $config=$this->initConfig();
    $callback=site_url()."/?app=wxapi&act=callbackOauth&ret_url=".urlencode(urldecode($_GET['ret_url']));
    $url=$this->api->oauthUser($config['appid'],1,$callback,'snsapi_base');//snsapi_userinfo
    header("Location:".$url);
  }

  function callbackOauth()
  {
     $config=$this->initConfig();
     $code=$_GET['code'];
     $this->api->callbackByOauthUser($config['appid'],$config['appsecret'],$code);
  }

  function initConfig()
  {
    $id=isset($_GET['id'])?intval($_GET['id']):0;
    $wxconfig= &m('wxconfig');
    $config=$wxconfig->get(' user_id='.$id);
    if($config)
    {      
      $token=$config['token'];
    }
    $this->api->setMyToken($token);
    return $config;
  }
	

   

}


class weixinapi extends wxapi
{
  function onMsgText($xml)
  {
       $this->get_replay_msg($xml['Content'],$xml['ToUserName'],$xml['FromUserName']);
  }

  function onEventSubscribe($xml)
  {
    $this->get_replay_msg('关注',$xml['ToUserName'],$xml['FromUserName']);
  }

  function get_replay_msg($keyword,$toid,$fromid)
  {
    $wxkey= &m('wxkey');
    $data=$wxkey->getInfoByKeyword($keyword);
    if(isset($data))
    {  
      if(is_array($data))
      {
        $msg=$this->send_pic($toid,$fromid,$data);
      }else{
        $msg=$this->send($toid,$fromid,$data);
      }
    }else{
       $msg=$this->send($toid,$fromid,"欢迎来到分红商城");
    }
     echo $msg; 

  }

  function _saveAccessToken($appid,$appkey,$expire_time,$access_token)
  {
    $wxconfig= &m('wxconfig');
    $config=$wxconfig->get(" appid='".$appid."'");
    if($config)
    {
      $wxconfig->edit(" appid='".$appid."'",array('access_expire'=>$expire_time,'access_token'=>$access_token));
      return 1;
    }
    return;
  }

   function _saveOauthAccessToken($appid,$appkey,$expires_time,$access_token,$refresh_token,$openid,$scope)
  {
    $_SESSION['openid']=$openid;
    $url=urldecode($_GET['ret_url']);
    $url=!empty($url)?(strstr($url,'?')?$url."&openid=".$openid:$url."?openid=".$openid):"?app=member&openid=".$openid;
    header("Location:".$url);
  }

}

?>
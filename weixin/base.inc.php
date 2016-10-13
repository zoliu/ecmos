<?php


//xml解析成数组
class Xml
{
	public static function decode($xml)
	{
		$values = array();
		$index  = array();
		$array  = array();
		$parser = xml_parser_create('utf-8');
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parse_into_struct($parser, $xml, $values, $index);
		xml_parser_free($parser);
		$i = 0;
		$name = $values[$i]['tag'];
		$array[$name] = isset($values[$i]['attributes']) ? $values[$i]['attributes'] : '';
		$array[$name] = self::_struct_to_array($values, $i);
		return $array;
	}
	
	private static function _struct_to_array($values, &$i)
	{
		$child = array();
		if (isset($values[$i]['value'])) 
		array_push($child, $values[$i]['value']);
		
		while ($i++ < count($values))
		 {
			switch ($values[$i]['type']) 
			{
				case 'cdata':
					array_push($child, $values[$i]['value']);
					break;
				
				case 'complete':
					$name = $values[$i]['tag'];
					if(!empty($name))
					{
						$child[$name]= ($values[$i]['value'])?($values[$i]['value']):'';
						if(isset($values[$i]['attributes'])) 
						{                   
							$child[$name] = $values[$i]['attributes'];
						}
					}   
				break;
				
				case 'open':
					$name = $values[$i]['tag'];
					$size = isset($child[$name]) ? sizeof($child[$name]) : 0;
					$child[$name][$size] = self::_struct_to_array($values, $i);
					break;
				
				case 'close':
					return $child;
					break;
			}
		}
		return $child;
	}
}

class wxUri
{
	function __construct()
	{
		$this->init();
	}

	function init()
	{

	}

	function _getAccessTokenUrl($appid,$appkey)
	{
		return "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$appkey}";
	}

	function _getMenuUrl($access_token)
	{
		return "https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$access_token}";
	}

	function _getRegisterUrl($appid,$state='1',$callback_url,$scope='snsapi_userinfo')//'snsapi_userinfo'
	{
		
		return "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri=".urlencode($callback_url)."&response_type=code&scope={$scope}&state={$state}#wechat_redirect";
	}

	function _getOauthAccessTokenUrl($appid,$appkey,$code)
	{

		return "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$appkey}&code={$code}&grant_type=authorization_code";
	}

	function _getRefreshAccessToken($appid,$access_token)
	{
		return "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid={$appid}&grant_type=refresh_token&refresh_token={$access_token}";
	}
}

class baseWx extends wxUri
{
	function __construct()
	{
		parent::__construct();
	}
	function checkSignature($mytoken)
	{
		  $signature = get("signature");
		  $timestamp = get("timestamp");
		  $nonce = get("nonce");
		  $token = $mytoken;
		  $tmpArr = array($token, $timestamp, $nonce);
		  sort($tmpArr, SORT_STRING);
		  $tmpStr = implode( $tmpArr );
		  $tmpStr = sha1( $tmpStr );
		  if( $tmpStr == $signature ){
		         return true;
		  }else{
		         return false;
		  }
	}
	/**
	说明：此方法主要用于刷新或获取AccessToken系统的AccessToken非oauth，
	参数：$appid=应用ID，$appkey=应用密钥
		  $expires_time=有效时间,$access_token=TOKEN
		  第一次获取调用如下：$this->_getAccessToken($appid,$appkey);
		  第二次调用如下: $this->_getAccessToken($appid,$appkey,$expires_time,$access_token);
		  调用外置方法:_saveAccessToken($appid,$appkey,$expires_time,$access_token);
	*/
	function _getAccessToken($appid,$appkey,$expires_time=0,$access_token='')
	{
		if(intval($expires_time)>time() && !empty($access_token))
		{			
			$this->setMyToken($access_token);
			return $this->_token=$access_token;
		}else{
			$data=$this->getUri($this->_getAccessTokenUrl($appid,$appkey));
			if(isset($data) && !empty($data))
			{
				$data=json_decode($data,1);
				if(isset($data['access_token']))
				{
					$expires_time=intval($data['expires_in'])+time();
					$edit_data=array(
						'app_id'=>$appid,
						'app_key'=>$appkey,
						'access_token'=>$data['access_token'],
						'expires_time'=>$expires_time,
						);
					try{
						$this->_saveAccessToken($appid,$appkey,$edit_data['expires_time'],$edit_data['access_token']);
					}catch(Exception $ex){

					}

					return $this->_token=$data['access_token'];
				}
			}
		}
	}

	function _getOauthAccessToken($appid,$appkey,$code)
	{
			$data=$this->getUri($this->_getOauthAccessTokenUrl($appid,$appkey,$code));
			if(isset($data) && !empty($data))
			{
				$data=json_decode($data,1);
				if(isset($data['access_token']))
				{
					$expires_time=intval($data['expires_in'])+time();
					$edit_data=array(
						'app_id'=>$appid,
						'app_key'=>$appkey,
						'access_token'=>$data['access_token'],
						'refresh_token'=>$data['refresh_token'],
						'openid'=>$data['openid'],
						'expires_time'=>$expires_time,
						'scope'=>$data['scope'],
						);
					try{
						$this->_saveOauthAccessToken($appid,$appkey,$edit_data['expires_time'],$edit_data['access_token'],$edit_data['refresh_token'],$edit_data['openid'],$edit_data['scope']);
					}catch(Exception $ex){

					}
					return $data['openid'];
				
			}
		}
		      
	}


	function getUri($url,$action='GET',$data=array())
	{
		try{


		 $ch = curl_init(); 
		 curl_setopt($ch, CURLOPT_URL,$url); 
		 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $action);
		 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		 curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		 curl_setopt($ch, CURLOPT_AUTOREFERER, 1); 
		 $action=='POST'?curl_setopt($ch, CURLOPT_POSTFIELDS, $data):null;
		 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		 $tmpInfo = curl_exec($ch); 
		 if (curl_errno($ch)) {  		 
			echo 'Errno'.curl_error($ch);
		 }
		 curl_close($ch); 
		 $json_data=$tmpInfo;
		}catch(Exception $ex)
		{
			write_log($ex);
		}
		 //$json_data=isset($tmpInfo) && !empty($tmpInfo)?json_decode($tmpInfo,1):0;
		 return $json_data;
	}

	 /* 内部函数 */

    function send($ToUserName, $FromUserName, $content,$type='text') {
        $str = "<xml>
				 <ToUserName><![CDATA[%s]]></ToUserName>
				 <FromUserName><![CDATA[%s]]></FromUserName>
				 <CreateTime>%s</CreateTime>
				 <MsgType><![CDATA[%s]]></MsgType>
				 <Content><![CDATA[%s]]></Content>
				</xml>";
				$str=str_replace(' ', '', $str);
        return $resultstr = sprintf($str, $FromUserName, $ToUserName, gmtime(),$type, $content);
    }

    /**
    *$data=array()
    *$data['picurl']=图片地址
    *$data['title']=标题
    *$data['description']=描述
    *$data['url']=链接地址
    */
    function send_pic($ToUserName, $FromUserName, $arr) {
        $str = "<xml>
				<ToUserName><![CDATA[" . $FromUserName . "]]></ToUserName>
				<FromUserName><![CDATA[" . $ToUserName . "]]></FromUserName>
				<CreateTime>" . gmtime() . "</CreateTime>
				<MsgType><![CDATA[news]]></MsgType>
				<ArticleCount>" . count($arr) . "</ArticleCount>
				<Articles>";
				foreach ($arr as $k => $v) {					
					    $picurl = $v['picurl'];					
					$str .="
					 <item>
					 <Title><![CDATA[" . $v['title'] . "]]></Title> 
					 <Description><![CDATA[" . $v['description'] . "]]></Description>
					 <PicUrl><![CDATA[" . $picurl . "]]></PicUrl>
					 <Url><![CDATA[" . $v['url'] . "]]></Url>
					 </item>";
				}
        $str .= "</Articles></xml>";
        $str=str_replace(' ', '', $str);
        return $str;
    }


    function oauthUser($appid,$state='1',$callback_url,$scope='snsapi_userinfo')
    {    	
    	return $this->_getRegisterUrl($appid,$state,$callback_url,$scope);
    }

    function callbackByOauthUser($appid,$appkey,$code)
    {
    	return $this->_getOauthAccessToken($appid,$appkey,$code);
    }

    
}

class wxapi extends baseWx
{
	var $_mytoken;
	var $_isopensign=0;
	function __construct()
	{
		parent::__construct();
	}

	function init()
	{

	}

	function check()
	{		
		$data=getRawPostData();	
		$this->checkPost($data);
	}

	function index()
	{

	}

	function isSign()
    {
       return isset($_GET['signature']) && !empty($_GET['signature']);
    }

    function isMsg($xml)
    {
        return isset($xml['MsgType']) && in_array($xml['MsgType'],array('text','image','voice','video','location','link'));
    }

    function isEvent($xml)
    {
       return isset($xml['MsgType'])  && $xml['MsgType']=='event' && in_array($xml['Event'],array('subscribe','unsubscribe','VIEW','CLICK','LOCATION'));
    }

    function isVoice($xml)
    {
       return isset($xml['MsgType']) && $xml['MsgType']=='voice';
    }

	function checkPost($data)
    {   
    	if(empty($data))
    	{
    		$this->openCheckSign();
    	}
		if($this->_isopensign)
		{   
			if($this->isSign())
			{      		
				$this->checkSign();				
				return;
			}

		}     
      $data= Xml::decode($data);
      $data=$data['xml'];
      if($this->isMsg($data))
      {
        $this->reciveMsg($data);
         return;
      }elseif($this->isEvent($data))
      {
        $this->reciveEvent($data);
         return;
      }elseif($this->isVoice($data))
      {
        $this->reciveVoice($data);
         return;
      }else{
         return 0;
       }    
    }


    //开启验证
    function openCheckSign()
    {
    	$this->_isopensign=1;
    }

    function closeCheckSign()
    {
    	$this->_isopensign=0;
    }


    function setMyToken($token)
    {
    	$this->_mytoken=$token;
    }


    function reciveMsg($xml)
    {
    	switch ($xml['MsgType']) {
        case 'text':
          $this->onMsgText($xml);
          break;
        case 'image':
          $this->onMsgImage($xml);
          break;
        case 'voice':
          $this->onMsgVoice($xml);
          break;
        case 'video':
          $this->onMsgVideo($xml);
          break;
        case 'location':
          $this->onMsgLocation($xml);
          break;
        case 'link':
          $this->onMsgLink($xml);
          break;
        default:
          # code...
          break;
      }

    }

    function reciveEvent($xml)
    {
    	 
      switch ($xml['Event']) {
        case 'subscribe':
          $this->onEventSubscribe($xml);
          break;
        case 'unsubscribe':
          $this->onEventUnsubscribe($xml);
          break;
        case 'VIEW':
          $this->onEventView($xml);
          break;
        case 'CLICK':
          $this->onEventClick($xml);
          break;
        case 'LOCATION':
          $this->onEventLocation($xml);
          break;
        default:
          # code...
          break;
      }

    }

    function reciveVoice($xml)
    {
    	$this->onVoice($xml);
    }



    /**
    **此段为事件处理方法的虚定义 *
    */


    function onMsgText($xml)
    {
    	$this->get_replay_msg($xml['Content'],$xml['ToUserName'],$xml['FromUserName']);

    }

    function onMsgImage($xml)
    {

    }    

    function onMsgVoice($xml)
    {

    }

    function onMsgVideo($xml)
    {


    }

    function onMsgLocation($xml)
    {


    }

    function onMsgLink($xml)
    {

    }


    function onEventSubscribe($xml){

    }

    function onEventUnsubscribe($xml){

    }

    function onEventView($xml){

    }

    function onEventClick($xml){

    }

    function onEventLocation($xml){

    }
    /**
    回复信息处理，微信传入参数keyword,服务号ID,来源ID
    */
    function _get_replay_msg($keyword,$toid,$fromid)
	{
		
	}
	/**
	保存TOKEN信息方法接口
	*/
	function _saveAccessToken($appid,$appkey,$expires_time,$access_token)
	{

	}

	function _saveOauthAccessToken($appid,$appkey,$expires_time,$access_token,$refresh_token,$openid,$scope)
	{

	} 

  //事件结束	

	function setMenu()
	{

	}

   //检查验证
    function checkSign()
    {
      if($this->checkSignature($this->_mytoken))
      {
        echo get('echostr');
      }
    }

}


if(!function_exists('get')){
	function get($name)
	{
		return isset($_GET[$name]) && !empty($_GET[$name])?trim($_GET[$name]):'';
	}
}

if(!function_exists('post')){
	function post($name)
	{
		return isset($_POST[$name]) && !empty($_POST[$name])?trim($_POST[$name]):'';
	}
}

if(!function_exists('getRawPostData'))
{
	function getRawPostData()
	{		
		return $GLOBALS["HTTP_RAW_POST_DATA"];
	}
}

if(!function_exists('write_log'))
{
	function write_log($data,$is_append=1)
	{
		$path='log.txt';
		$str=chr(9).chr(10).chr(9).chr(10).date('Y-m-d H:i:s').'----------------------------------------------------'.chr(9).chr(10);
		$str.=var_export($data,1);
		$str.=chr(9).chr(10).date('Y-m-d H:i:s').'----------------------------------------------------'.chr(9).chr(10);
		if($is_append)
		{
			file_put_contents($path, $str,FILE_APPEND);
		}else{
			file_put_contents($path, $str);
		}

		
	}
}

?>
<?php

/**
 * 开店成功后给店主发邮件通知
 *
 * @return  array
 */
class Unable_keywordPlugin extends BasePlugin
{
    var $_config = array();
    
    function __construct($data, $plugin_info)
    {
        $this->Unable_keywordPlugin($data, $plugin_info);
    }
    function Unable_keywordPlugin($data, $plugin_info)
    {
        $this->_config = $plugin_info;
        parent::__construct($data, $plugin_info);
    }
    function execute()
    {
      $type=$this->check();	 
	  if($type=="register")
		{
		   if($this->str_check_in($this->g("user_name")))
			{
              echo "false";
		      exit;
			}
		}else if($type=="query")
		{
             if($this->str_check_in($this->p("content")))
			{
		       echo "<script>alert('您提交的信息包含敏感信息');window.location.href='".$_SERVER['REQUEST_URI']."';</script>";			   
              exit;
			}

		}else if($type=="comment")
		{
			$order=$this->g("order_id");
          $value=$this->p("evaluations");
		  $value=isset($value[$order][comment]) && $value[$order][comment]!=null?$value[$order][comment]:"";
		  if($value!="")
		  {
            if($this->str_check_in($value))
			{
		       echo "<script>alert('您提交的信息包含敏感信息');window.location.href='".$_SERVER['REQUEST_URI']."';</script>";			   
              exit;
			}
		  }
		}
    }


	function str_check_in($val)
	{
       $val=trim($val);
       $keyword=$this->_config['content'];
	   $keydata=null;
	   if( $keyword!=null && $keyword!="")
		{
		  $keydata= explode(",",$keyword);
		}
		if(count($keydata)>0)
		{
           foreach($keydata as $v)
			{
			  if(strpos($val,$v))           
			  {
			    return true;
			   }
			}
		}
		return false;
	}

    function check()
	{
		$type=null;
        if( $this->g("act")=="check_user" && $this->g("app")=="member")
		{
            $type="register";
		}else if($this->g("act")=="qa" && $this->g("app")=="goods"){
            $type="query";
		}else if($this->g("act")=="evaluate" && $this->g("app")=="buyer_order"){
		    $type="comment";
		}
		return $type;

	}

	function g($name)
	{
     return isset($_GET[$name])?$_GET[$name]:"";
	}

	function p($name)
	{
      return isset($_POST[$name])?$_POST[$name]:"";

	}
}

?>
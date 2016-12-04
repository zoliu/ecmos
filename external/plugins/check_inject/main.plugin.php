<?php



/**

 * 店铺地址简写插件

 *

 * @return  array

 */

class Check_injectPlugin extends BasePlugin

{

    function execute()

    {

        if (defined('IN_BACKEND') && IN_BACKEND === true)

        {

            return; // 后台无需执行

        }

        else

        {
            $inject_flag=$this->check_inject(array($_REQUEST,$_COOKIE));        
            if($inject_flag)
            {
                exit('HACK ATTEMPEMT');
            }       
            $this->script_tags();

        }

    }

    function script_tags()
    {
        $_REQUEST=$this->clear_html($_REQUEST);
        $_GET=$this->clear_html($_GET);
        $_POST=$this->clear_html($_POST);
        $_COOKIE=$this->clear_html($_COOKIE);
    }

    function clear_html($code)
    {
        if (!get_magic_quotes_gpc()) {
            if (is_array($code)) {
                foreach ($code as $key=>$value) {
                    $code[$key] = addslashes($value);
                    $code[$key]=htmlentities($value);
                    $code[$key]=htmlspecialchars($value);
                }
            } else {
                $code=addslashes($code);
                $code=htmlentities($code);
                $code=htmlspecialchars($code);
            }
        }
        return $code;
    }



    function check_inject($params){
        if($this->inject_flag){return 1;}
        if(is_array($params))
        {
           foreach($params as $k=>$v){


             if($this->inject_flag!=1){
                $this->check_inject($v);
             }else{               
                return 1;
             }
             
           }
        }else{
            return $this->inject_check($params)?$this->inject_flag=1:0;
        }
    }

    function check_inject_str($str,$sql){
      return !(strpos ($sql,$str)===false);
    }

    function inject_check($sql_str) {
        $sql_str=strtolower($sql_str);
        $cond=include('rules.php');
        foreach($cond as $c)
        {
            $count=0;
            foreach($c as $i)
            {
                $this->check_inject_str($i,$sql_str)?$count++:null;
            }
            if($count==count($c))return 1;
        }
        return 0;

    }

}



?>
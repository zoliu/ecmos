<?php
//360cd.cn
function isNotEmpty($var)
{
    return isset($var) && !empty($var);
}

function arrayIsNotEmpty($value)
{
    return isset($value) && is_array($value) && count($value)>0;
}

function get($name)
{
    return isNotEmpty($_GET[$name])?$_GET[$name]:null;
}

function post($name)
{
    return isNotEmpty($_POST[$name])?$_POST[$name]:null;
}


function isWeiXin()
{
    if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
            return true;
          }
    return false;

}

function getListData($model_name,$where)
{
	if(empty($model_name)){return 0;}
	$model=& m($model_name);
	$data=$model->find($params);
	if(!$data)
	{
		return 0;
	}
	return array('data'=>$data,'item_count'=>$model->getCount());
}

function getRowData($model_name,$where)
{
 	if(empty($model_name)){return 0;}
 	$model=& m($model_name);
	$data=$model->get($params);
	if(!$data)
	{
		return 0;
	}
	return $data;
}

//从数组中提取出select,options专用数据
function array_to_options($data,$key,$val)
{
    $items=array();
    if(is_array($data) && count($data)>0)
    {
        foreach($data as $k=>$v)
        {
             $items[$v[$key]]=$v[$val];
        }
    }
    return $items;
}


function uploadImage($form_el,$tpath='',$file_name='')
{
    import('uploader.lib');
    $file = $_FILES[$form_el];
    if ($file['error'] == UPLOAD_ERR_OK)
    {
        $uploader = new Uploader();
        $uploader->allowed_type(IMAGE_FILE_TYPE);
        $uploader->addFile($file);

        $uploader->root_dir(ROOT_PATH);       
        
        if(class_exists('FrontendApp') || !empty($_GET['module']))
        {
            $pre_path='';
        }else{
            $pre_path='../';
        }        
        $path='data/files/mall/common';    
        if(!empty($tpath))
        {          
          $dir=$path.'/'.$tpath; 
          if(!file_exists($dir) )
          {
             ecm_mkdir($dir);
          }
          $path=$dir;
        }    
        $file_name=empty($file_name)?$uploader->random_filename():$file_name;
        $newpath=$uploader->save($path, $file_name );
        
        return $pre_path.$newpath;

    }
    return '';
}

function show_time($code='')
{
  !empty($code)?$code=$code.'----':'';
  echo $code.ecm_microtime()."<br>";
}

function uploadFile($form_el,$file_exts='doc|xls|docx|txt',$tpath='',$file_name='')
{
    import('uploader.lib');
    $file = $_FILES[$form_el];
    if ($file['error'] == UPLOAD_ERR_OK)
    {
        $uploader = new Uploader();
        $uploader->allowed_type($file_exts);
        $uploader->addFile($file);

        $uploader->root_dir(ROOT_PATH);       
        
        if(class_exists('FrontendApp') || !empty($_GET['module']))
        {
            $pre_path='';
        }else{
            $pre_path='../';
        }        
        $path='data/files/mall/common';    
        if(!empty($tpath))
        {          
          $dir=$path.'/'.$tpath; 
          if(!file_exists($dir) )
          {
             ecm_mkdir($dir);
          }
          $path=$dir;
        }    
        $file_name=empty($file_name)?$uploader->random_filename():$file_name;
        $newpath=$uploader->save($path, $file_name );
        
        return $pre_path.$newpath;

    }
    return '';
}
//导入数组文件
function include_array($path)
{
    return file_exists($path) ? include($path) : null;
}

//导出数组到文件
function save_array($path,$array)
{
     file_put_contents($path, "<?php\nreturn ".var_export($array,1).";\n?>");
}

function rand_code($length)
{
     $pattern='1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
     for($i=0;$i<$length;$i++)
     {
       $key .= $pattern{mt_rand(0,35)};    //生成php随机数
     }
     return $key;
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

function get_order_sn($prefix='o')
{
  $order_prefix=$prefix;
  return $order_prefix.date('YmdHis').mt_rand(100,999);
}

function replaceLog($msg,$params=array(),$sign='#')
{
  if(is_array($params) && count($params))
  {
    foreach($params as $k=>$v)
    {
      $msg=str_replace($sign.$k.$sign, $v, $msg);
    }
  }
  return $msg;
}


function errorLog($state,$params=array())
{
  return array_key_exists($state, $params)?$params[$state]:$state;
}


//发送站内短消息
function sendMsg($user_id,$title,$content)
{
  $msg_mod= &m('message');
  return $msg_mod->send(MSG_SYSTEM,$user_id,$title,$content);   
}

if(isset($_GET['debug']) )
{
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}
?>
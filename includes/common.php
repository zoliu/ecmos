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
function com($name)
{
   if(isNotEmpty($name))
   {
     $path=ROOT_PATH."/com/".$name.".php";
     if(file_exists($path))
     {
       return $path;
     }
     return null;
   }
   return null;
}

function comGetListData($model_name,$where,$limit=null,$sort='',$is_count=false)
{
    if(empty($model_name)){return 0;}
    $model=& m($model_name);
    $params=array();
    isset($limit)?$params['limit']=$limit:null;
    isset($sort)?$params['order']=$sort:null;
    isset($is_count)?$params['count']=$is_count:null;
    isset($where)?$params['conditions']=$where:null;
    $data=$model->find($params);
     return array('data'=>$data,'item_count'=>$model->getCount());
}

function comGetRowData($model_name,$where)
{
    if(empty($model_name)){return 0;}
    $model=& m($model_name);
    return $data=$model->get($where);
}

function uploadImage($form_el)
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
                $path='data/files/mall/common';
            }else{
                $path='../data/files/mall/common';
            }
            $newpath=$uploader->save($path, $uploader->random_filename());
			return !empty($_GET['module'])?'../'.$newpath:$newpath;
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

/*
 * 经典的概率算法，
 * $proArr是一个预先设置的数组，
 * 假设数组为：array(100,200,300，400)，
 * 开始是从1,1000 这个概率范围内筛选第一个数是否在他的出现概率范围之内， 
 * 如果不在，则将概率空间，也就是k的值减去刚刚的那个数字的概率空间，
 * 在本例当中就是减去100，也就是说第二个数是在1，900这个范围内筛选的。
 * 这样 筛选到最终，总会有一个数满足要求。
 * 就相当于去一个箱子里摸东西，
 * 第一个不是，第二个不是，第三个还不是，那最后一个一定是。
 * 这个算法简单，而且效率非常 高，
 * 关键是这个算法已在我们以前的项目中有应用，尤其是大数据量的项目中效率非常棒。
 */
function get_rand($proArr,$total=10000) { 
    $result = '';  
    //概率数组的总概率精度 
    $proSum = array_sum($proArr);  
    //概率数组循环 
    foreach ($proArr as $key => $proCur) { 
        $randNum = mt_rand(1, $total); 
        if ($randNum <= $proCur) { 
            $result = $key; 
            break; 
        } else { 
            $proSum -= $proCur; 
        } 		
    } 
    unset ($proArr);  
    return $result; 
} 

function prize($prize_arr=array(),$total=10000)
{
	/*
 * 奖项数组
 * 是一个二维数组，记录了所有本次抽奖的奖项信息，
 * 其中id表示中奖等级，prize表示奖品，v表示中奖概率。
 * 注意其中的v必须为整数，你可以将对应的 奖项的v设置成0，即意味着该奖项抽中的几率是0，
 * 数组中v的总和（基数），基数越大越能体现概率的准确性。
 * 本例中v的总和为100，那么平板电脑对应的 中奖概率就是1%，
 * 如果v的总和是10000，那中奖概率就是万分之一了。
 * 
 */
/**
	$prize_arr = array( 
	    '0' => array('id'=>1,'prize'=>'平板电脑','v'=>1), 
	    '1' => array('id'=>2,'prize'=>'数码相机','v'=>5), 
	    '2' => array('id'=>3,'prize'=>'音箱设备','v'=>10), 
	    '3' => array('id'=>4,'prize'=>'4G优盘','v'=>12), 
	    '4' => array('id'=>5,'prize'=>'10Q币','v'=>22), 
	    '5' => array('id'=>6,'prize'=>'下次没准就能中哦','v'=>50), 
	); 
*/

/*
 * 每次前端页面的请求，PHP循环奖项设置数组，
 * 通过概率计算函数get_rand获取抽中的奖项id。
 * 将中奖奖品保存在数组$res['yes']中，
 * 而剩下的未中奖的信息保存在$res['no']中，
 * 最后输出json个数数据给前端页面。
 */
foreach ($prize_arr as $key => $val) { 
    $arr[$val['id']] = $val['v']; 
} 
$rid = get_rand($arr,$total); //根据概率获取奖项id 
return $rid-1;
/**
    $res['yes'] = $prize_arr[$rid-1]['prize']; //中奖项 
    unset($prize_arr[$rid-1]); //将中奖项从数组中剔除，剩下未中奖项 
    shuffle($prize_arr); //打乱数组顺序 
    for($i=0;$i<count($prize_arr);$i++){ 
        $pr[] = $prize_arr[$i]['prize']; 
    } 
    $res['no'] = $pr; 
    return $res;
**/
//print_r($res); 
}
//360cd.cn
?>
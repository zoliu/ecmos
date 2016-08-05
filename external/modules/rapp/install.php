<?php

/**
 * @author Mosquito
 * @link www.360cd.cn
 */

/**
 * 这里可以放一些安装模块时需要执行的代码，比如新建表，新建目录、文件之类的
 */
$config = array();
$config['s_url'] = 'http://app.s.360cd.cn';

$filename = ROOT_PATH . '/data/rapp.inc.php';
file_put_contents($filename, "<?php\nreturn " . var_export($config, true) . ";\n?>");

//数据库操作
//$db = &db();
//$db->query();


?>
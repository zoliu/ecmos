<?php

/**
 * 这里可以放一些安装模块时需要执行的代码，比如新建表，新建目录、文件之类的
 */

/* 下面的代码不是必需的，只是作为示例 */
/*$db=&db();
$db->query("DROP TABLE IF EXISTS `".DB_PREFIX."point_set`;");
$db->query("CREATE TABLE  `".DB_PREFIX."point_set` (
`id` INT NOT NULL AUTO_INCREMENT ,
`config` VARCHAR( 250 ) ,
PRIMARY KEY (  `id` )
) ENGINE = MYISAM ;
");

$db->query("DROP TABLE IF EXISTS `".DB_PREFIX."point_logs`;");
$db->query("CREATE TABLE  `".DB_PREFIX."point_logs` (
`id` INT NOT NULL AUTO_INCREMENT ,
`user_id` INT NOT NULL ,
`user_name` VARCHAR( 50 ) ,
`point` int ,
`addtime` int ,
`remark` VARCHAR( 100 ) ,
`type` char(20) ,
PRIMARY KEY (  `id` )
) ENGINE = MYISAM ;
");*/
include("fileAssign.php");
install(ROOT_PATH.'/external/modules/point/source/');

?>
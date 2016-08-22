<?php

/**
 * 这里可以放一些安装模块时需要执行的代码，比如新建表，新建目录、文件之类的
 */

/* 下面的代码不是必需的，只是作为示例 */
/*$db=&db();
$db->query("DROP TABLE IF EXISTS `".DB_PREFIX."member_ext`;");
$db->query("CREATE TABLE  `".DB_PREFIX."member_ext` (
`id` INT NOT NULL AUTO_INCREMENT ,
`user_id` INT NOT NULL ,
`user_level_id` INT NOT NULL ,
`user_point` INT NOT NULL ,
`user_totalpoint` INT NOT NULL ,
`status` INT  ,
`sort` INT NOT NULL ,
PRIMARY KEY (  `id` )
) ENGINE = MYISAM ;
");

$db->query("DROP TABLE IF EXISTS `".DB_PREFIX."member_level`;");
$db->query("CREATE TABLE  `".DB_PREFIX."member_level` (
`id` INT NOT NULL AUTO_INCREMENT ,
`level_name` VARCHAR( 100 ) NOT NULL ,
`level_code` VARCHAR( 50 ) NOT NULL ,
`level_discount` float ,
`level_cost` float ,
`sort` INT NOT NULL ,
`start_point` int(11) NOT NULL,
`end_point` int(11) NOT NULL,
PRIMARY KEY (  `id` )
) ENGINE = MYISAM ;
");*/
include("fileAssign.php");
install(ROOT_PATH.'/external/modules/member_ext/source/');

?>
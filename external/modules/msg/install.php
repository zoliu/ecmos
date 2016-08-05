<?php
$filename = ROOT_PATH . '/data/datacall.inc.php';
file_put_contents($filename, "<?php return array(); ?>");
$db=&db();

$db->query("CREATE TABLE ".DB_PREFIX."msg (

  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  user_id int(10) unsigned NOT NULL DEFAULT '0',
  user_name varchar(100) DEFAULT NULL,
  mobile varchar(100) DEFAULT NULL,
  num int(10) unsigned NOT NULL DEFAULT '0',
  functions varchar(255) DEFAULT NULL,
  state tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) 
 ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");
 
$db->query("CREATE TABLE ".DB_PREFIX."msglog (

  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  user_id int(10) unsigned NOT NULL DEFAULT '0',
  user_name varchar(100) DEFAULT NULL,
  to_mobile varchar(100) DEFAULT NULL,
  content text DEFAULT NULL,
  state varchar(100) DEFAULT NULL,
  type int(10) unsigned NULL DEFAULT '0',
  `time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (id)
) 
 ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");
 
?>
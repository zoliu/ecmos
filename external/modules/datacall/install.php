<?php

/**
 * 这里可以放一些安装模块时需要执行的代码，比如新建表，新建目录、文件之类的
 */

/* 下面的代码不是必需的，只是作为示例 */
$filename = ROOT_PATH . '/data/datacall.inc.php';
file_put_contents($filename, "<?php return array(); ?>");

?>
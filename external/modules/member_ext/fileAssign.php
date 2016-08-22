<?php

//必须存在子目录admin
function install($dir)
{
    $error   = array();
    $fronted = fileAssign($dir);
    $backend = fileAssign($dir . "admin/", 'admin');
    $data    = array_merge($fronted, $backend);
    if (isset($data) && !empty($data)) {
        foreach ($data as $d) {
            if (copy($d['src'], $d['tar']) === false) {
                $error[] = $d;
            }
        }
    }
    if (!empty($error)) {
        return array(0, $error);
    }
    return array(1, null);
}

function unstall($dir)
{
    $error   = array();
    $fronted = fileAssign($dir);
    $backend = fileAssign($dir . "admin/", 'admin');
    $data    = array_merge($fronted, $backend);
    if (isset($data) && !empty($data)) {
        foreach ($data as $d) {
            if (file_exists($d['tar'])) {
                if (@unlink($d['tar']) === false) {
                    $error[] = $d;
                }
            }
        }
    }
    if (!empty($error)) {
        return array(0, $error);
    }
    return array(1, null);

}

function fileAssign($dir, $type = '')
{
    if (!file_exists($dir)) {
        return array();
    }
    $assign   = array();
    $admin    = isset($type) && !empty($type) && $type == 'admin' ? 1 : 0;
    $adminApp = $admin ? "admin/" : '';
    $files    = getFile($dir);
    if (isset($files) && !empty($files)) {
        foreach ($files as $f) {
            $tmp        = array();
            $tmp['src'] = $dir . "/" . $f;
            if (strpos($f, ".app.php") !== false) {
                $tmp['tar'] = "/" . $adminApp . "app/" . $f;
            } elseif (strpos($f, ".model.php")) {
                $tmp['tar'] = "/includes/models/" . $f;
            } elseif (strpos($f, ".lang.php")) {
                $tmp['tar'] = "/languages/" . LANG . "/" . $adminApp . $f;
            } else if (strpos($f, '.html')) {
                if (!$admin) {
                    $tmp['tar'] = "/themes/mall/" . Conf::get('template_name') . "/" . $f;
                } else {
                    $tmp['tar'] = "/admin/templates/" . $f;
                }
            } else {

            }
            $tmp['tar'] = ROOT_PATH . $tmp['tar'];
            $assign[]   = $tmp;
        }
    }
    return $assign;
}

//获取文件列表
function getFile($dir)
{
    $fileArray[] = null;
    if (false != ($handle = opendir($dir))) {
        $i = 0;
        while (false !== ($file = readdir($handle))) {
            //去掉"“.”、“..”以及带“.xxx”后缀的文件
            if ($file != "." && $file != ".." && strpos($file, ".")) {
                $fileArray[$i] = $file;
                if ($i == 100) {
                    break;
                }
                $i++;
            }
        }
        //关闭句柄
        closedir($handle);
    }
    return $fileArray;
}

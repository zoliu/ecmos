<?php

/**
 *    文件上传辅助类
 *
 *    @author    Garbin
 *    @usage    none
 */
class Uploader extends Object
{
    var $_file              = null;
    var $_allowed_file_type = null;
    var $_allowed_file_size = null;
    var $_root_dir          = null;

    /**
     *    添加由POST上来的文件
     *
     *    @author    Garbin
     *    @param    none
     *    @return    void
     */
    function addFile($file)
    {
        if (!is_uploaded_file($file['tmp_name']))
        {
            return false;
        }
        $this->_file = $this->_get_uploaded_info($file);
    }

    /**
     *    设定允许添加的文件类型
     *
     *    @author    Garbin
     *    @param     string $type （小写）示例：gif|jpg|jpeg|png
     *    @return    void
     */
    function allowed_type($type)
    {
        $this->_allowed_file_type = explode('|', $type);
    }

    /**
     *    允许的大小
     *
     *    @author    Garbin
     *    @param     mixed $size
     *    @return    void
     */
    function allowed_size($size)
    {
        $this->_allowed_file_size = $size;
    }
    function _get_uploaded_info($file)
    {
        $pathinfo = pathinfo($file['name']);
        $file['extension'] = $pathinfo['extension'];
        $file['filename']     = $pathinfo['basename'];
        if (!$this->_is_allowd_type($file['extension']))
        {
            $this->_error('not_allowed_type', $file['extension']);

            return false;
        }
        if (!$this->_is_allowd_size($file['size']))
        {
            $this->_error('not_allowed_size', $file['size']);

            return false;
        }

        return $file;
    }
    function _is_allowd_type($type)
    {
        if (!$this->_allowed_file_type)
        {
            return true;
        }
        return in_array(strtolower($type), $this->_allowed_file_type);
    }
    function _is_allowd_size($size)
    {
        if (!$this->_allowed_file_size)
        {
            return true;
        }

        return is_numeric($this->_allowed_file_size) ?
                ($size <= $this->_allowed_file_size) :
                ($size >= $this->_allowed_file_size[0] && $size <= $this->_allowed_file_size[1]);
    }
    /**
     *    获取上传文件的信息
     *
     *    @author    Garbin
     *    @param    none
     *    @return    void
     */
    function file_info()
    {
        return $this->_file;
    }

    /**
     *    若没有指定root，则将会按照所指定的path来保存，但是这样一来，所获得的路径就是一个绝对或者相对当前目录的路径，因此用Web访问时就会有问题，所以大多数情况下需要指定
     *
     *    @author    Garbin
     *    @param    none
     *    @return    void
     */
    function root_dir($dir)
    {
        $this->_root_dir = $dir;
    }
    function save($dir, $name = false)
    {
        if (!$this->_file)
        {
            return false;
        }
        if (!$name)
        {
            $name = $this->_file['filename'];
        }
        else
        {
            $name .= '.' . $this->_file['extension'];
        }
        $path = $dir . '/' . $name;

        return $this->move_uploaded_file($this->_file['tmp_name'], $path);
    }

    /**
     *    将上传的文件移动到指定的位置
     *
     *    @author    Garbin
     *    @param     string $src
     *    @param     string $target
     *    @return    bool
     */
    function move_uploaded_file($src, $target)
    {
        $abs_path = $this->_root_dir ? $this->_root_dir . '/' . $target : $target;
        $dirname = dirname($target);
        if (!ecm_mkdir(ROOT_PATH . '/' . $dirname))
        {
            $this->_error('dir_doesnt_exists');

            return false;
        }

        if (move_uploaded_file($src, $abs_path))
        {
            @chmod($abs_path, 0666);
            return $target;
        }
        else
        {
            return false;
        }
    }

    /**
     * 生成随机的文件名
     */
    function random_filename()
    {
        $seedstr = explode(" ", microtime(), 5);
        $seed    = $seedstr[0] * 10000;
        srand($seed);
        $random  = rand(1000,10000);

        return date("YmdHis", time()) . $random;
    }
}

/**
 *    FtpUploader
 *
 *    @author    Garbin
 *    @usage    none
 */
class FtpUploader extends Uploader
{
    var $_ftp_server = null;
    function __construct(&$_ftp_server)
    {
        $this->_ftp_server = $_ftp_server;
    }
    function move_uploaded_file($src, $target)
    {
        if (!$this->_ftp_server)
        {
            $this->_error('no_ftp_server');
            return false;
        }
        $dir = dirname($target);
        $this->_chdir($dir);

        return  $this->_ftp_server->put($src, basename($target)) ? $target : false;
    }
    function _chdir($dir)
    {
        restore_error_handler();

        $dirs = explode('/', $dir);
        if (empty($dirs))
        {
            return true;
        }
        /* 循环创建目录 */
        foreach ($dirs as $d)
        {
            if (!@$this->_ftp_server->chdir($d))
            {
                $this->_ftp_server->mkdir($d);
                $this->_ftp_server->chmod($d);
                $this->_ftp_server->chdir($d);
                $this->_ftp_server->put(ROOT_PATH . '/data/index.html', 'index.html');
            }
        }

        reset_error_handler();

        return true;
    }
}

?>
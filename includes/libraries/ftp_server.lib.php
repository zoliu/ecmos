<?php

/**
 *    Ftp客户端
 *
 *    @author    Garbin
 *    @usage    none
 */
class FtpServer extends Object
{
    /* FTP连接Flag */
    var $_connection = null;


    function __construct($server, $port = 21, $timeout = 90, $ssl = false)
    {
        $this->FtpServer($server, $port = 21, $timeout = 90, $ssl);
    }
    function FtpServer($server, $port = 21, $timeout = 90, $ssl = false)
    {
        $func = $ssl ? 'ftp_ssl_connect' : 'ftp_connect';
        $this->_connection = @$func($server, $port, $timeout);
    }

    /**
     *    获取FTP选项
     *
     *    @author    Garbin
     *    @param     string $option
     *    @return    mixed
     */
    function get_option($option)
    {
        return ftp_get_option($this->_connection, $option);
    }

    /**
     *    设置FTP选项
     *
     *    @author    Garbin
     *    @param    none
     *    @return    void
     */
    function set_option($option, $value)
    {
        return ftp_set_option($this->_connection, $option, $value);
    }

    /**
     *    用指定的用户名密码登录FTP服务器
     *
     *    @author    Garbin
     *    @param     string $username
     *    @param     string $password
     *    @return    bool
     */
    function login($username, $password)
    {
        if (!@ftp_login($this->_connection, $username, $password))
        {
            $this->_error('ftp_login_failed');

            return false;
        }

        return true;
    }

    /**
     *    以被动模式连接
     *
     *    @author    Garbin
     *    @param     bool $turn_on
     *    @return    bool
     */
    function pasv($turn_on = false)
    {
        return ftp_pasv($this->_connection, $turn_on);
    }

    /**
     *    Sends an arbitrary command to an FTP server
     *
     *    @author    Garbin
     *    @param     string $cmd
     *    @return    array
     */
    function raw($cmd)
    {
        return ftp_raw($this->_connection, $cmd);
    }

    /**
     *    执行一个FTP命令
     *
     *    @author    Garbin
     *    @param     string $cmd
     *    @return    bool
     */
    function exec($cmd)
    {
        return ftp_exec($this->_connection, $cmd);
    }

    /**
     *    Sends a SITE command to the server
     *
     *    @author    Garbin
     *    @param     string $cmd
     *    @return    bool
     */
    function site($cmd)
    {
        return ftp_site($this->_connection, $cmd);
    }

    /**
     *    关闭当前FTP连接
     *
     *    @author    Garbin
     *    @return    bool
     */
    function close()
    {
        return ftp_close($this->_connection);
    }

    /*-------------目录操作相关-----------*/
    /**
     *    获取当前目录
     *
     *    @author    Garbin
     *    @return    bool
     */
    function pwd()
    {
        return ftp_pwd($this->_connection);
    }

    /**
     *    切换到指定目录
     *
     *    @author    Garbin
     *    @param     string $dir
     *    @return    bool
     */
    function chdir($dir, $force = false)
    {
        return ftp_chdir($this->_connection, $dir);
    }

    /**
     *    切换到上级目录
     *
     *    @author    Garbin
     *    @return    void
     */
    function cdup()
    {
        return ftp_cdup($this->_connection);
    }

    /**
     *    创建目录
     *
     *    @author    Garbin
     *    @param     string $dir
     *    @return    bool
     */
    function mkdir($dir)
    {
        return ftp_mkdir($this->_connection, $dir);
    }

    /**
     *    删除指定目录
     *
     *    @author    Garbin
     *    @param     string $dir
     *    @return    bool
     */
    function rmdir($dir)
    {
        return ftp_rmdir($this->_connection, $dir);
    }

    /**
     *    列表指定目录的详细信息
     *
     *    @author    Garbin
     *    @param     string $dir
     *    @param     bool   $recursive
     *    @return    array
     */
    function rawlist($dir, $recursive = false)
    {
        return ftp_rawlist($this->_connection, $dir, $recursive);
    }

    /*------------文件操作相关方法-----------*/

    function alloc($size, &$msg)
    {
        return ftp_alloc($this->_connection, $size, $msg);
    }
    /**
     *    按指定模式将指定路径的文件上传至服务器
     *
     *    @author    Garbin
     *    @param     string   $src
     *    @param     string   $target
     *    @param     int      $mode
     *    @return    bool
     */
    function put($src, $target, $mode = FTP_BINARY)
    {
        return ftp_put($this->_connection, $target, $src, $mode);
    }

    /**
     *    按指定模式将给定的文件资源上传至服务器
     *
     *    @author    Garbin
     *    @param     resource $fp
     *    @param     string   $target
     *    @param     int      $mode
     *    @return    bool
     */
    function fput($fp, $target, $mode = FTP_BINARY)
    {
        return ftp_fput($this->_connection, $target, $fp, $mode);
    }

    /**
     *    从FTP下载文件至指定的本地路径
     *
     *    @author    Garbin
     *    @param     string   $local
     *    @param     string   $target
     *    @param     int      $mode
     *    @return    bool
     */
    function get($local, $target, $mode = FTP_BINARY)
    {
        return ftp_get($this->_connection, $local, $target, $mode);
    }

    /**
     *    从FTP下载文件至指定的文件资源中
     *
     *    @author    Garbin
     *    @param     resource $fp
     *    @param     string   $target
     *    @param     int      $mode
     *    @return    bool
     */
    function fget($fp, $target, $mode = FTP_BINARY)
    {
        return ftp_fget($this->_connection, $fp, $target, $mode);
    }

    /**
     *    获取指定文件的最后修改时间
     *
     *    @author    Garbin
     *    @param     string $path
     *    @return    int
     */
    function mdtm($path)
    {
        return ftp_mdtm($this->_connection, $path);
    }

    /**
     *    从FTP上删除一个文件
     *
     *    @author    Garbin
     *    @param     string $file_path
     *    @return    void
     */
    function delete($file_path)
    {
        return ftp_delete($this->_connection, $file_pat);
    }

    /**
     *    获取给定文件的大小
     *
     *    @author    Garbin
     *    @param     string $file_path
     *    @return    int
     */
    function size($file_path)
    {
        return ftp_size($this->_connection, $file_path);
    }

    /*--------文件与目录公有操作--------*/
    /**
     *    修改文件或目录名
     *
     *    @author    Garbin
     *    @param     string $old_name
     *    @param     string $new_name
     *    @return    bool
     */
    function rename($old_name, $new_name)
    {
        return ftp_rename($this->_connection, $old_name, $new_name);
    }

    /**
     *    设置文件或目录权限
     *
     *    @author    Garbin
     *    @param     string $path
     *    @param     int $mode
     *    @return    bool
     */
    function chmod($path, $mode = 0777)
    {
        if (!function_exists('ftp_chmod') || !ftp_chmod($this->_connection, $mode, $path))
        {
            return $this->site("CHMOD {$mode} {$path}");
        }

        return true;
    }
}

?>
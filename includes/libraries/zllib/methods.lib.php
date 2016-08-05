<?php

/**
 * 目录封装类
 * @author Mosquito
 * @link www.360cd.cn
 */
class Dir {

    /**
     * 初始化一个实例
     * 
     * @return Dir
     */
    static function init() {
        return new Dir();
    }

    /**
     * 创建目录
     * 
     * @param string $dir            
     * @return string $dir
     */
    function create_dir($path) {
        $path = str_replace('\\', '/', $path);
        $path_array = explode('/', $path);
        $str = '';
        foreach ( $path_array as $dir ) {
            if (!$dir) {
                continue;
            }
            $str .= $dir;
            if (!is_dir($str)) {
                @mkdir($str);
            }
            $str .= '/';
        }
        return $path;
    }

    /**
     * 获取当前目录及子目录下所有文件名
     * 
     * @param string $path            
     * @param
     *            array &$file_list
     * @param string $remove_path            
     */
    function get_dir_filename($path, $remove_path = '') {
        $file_list = array();
        $this->_get_dir_filename($path, $file_list, $remove_path);
        return $file_list;
    }

    protected function _get_dir_filename($path, &$file_list, $remove_path = '') {
        if (is_dir($path)) {
            $dp = dir($path);
            while ( $file = $dp->read() ) {
                if ($file != '.' && $file != '..') {
                    $this->_get_dir_filename($path . '/' . $file, $file_list, $remove_path);
                }
            }
            $dp->close();
        } else if (is_file($path)) {
            $file_list[] = str_replace($remove_path, '', $path);
        }
    }

    /**
     * 删除当前目录以及目录下所有文件
     * 
     * @param string $dir            
     */
    function del_dir($dir) {
        $dh = opendir($dir);
        while ( $file = readdir($dh) ) {
            if ($file != "." && $file != "..") {
                $fullpath = $dir . "/" . $file;
                if (!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    $this->del_dir($fullpath);
                }
            }
        }
        closedir($dh);
        return rmdir($dir);
    }

    /**
     * 删除指定目录及子目录下空的目录
     * 
     * @param string $path            
     */
    function del_empty_dir($path) {
        if (is_dir($path) && ($handle = opendir($path)) != false) {
            while ( ($file = readdir($handle)) != false ) {
                if ($file != '.' && $file != '..') {
                    $curfile = $path . '/' . $file;
                    if (is_dir($curfile)) {
                        $this->del_empty_dir($curfile);
                        if (count(scandir($curfile)) == 2) {
                            rmdir($curfile);
                        }
                    }
                }
            }
            closedir($handle);
        }
    }

    /**
     * 拷贝目录文件到指定目录
     * 
     * @param string $source            
     * @param string $dest            
     */
    function copy_dir($source, $dest) {
        $dir = opendir($source);
        while ( false != ($file = readdir($dir)) ) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($source . '/' . $file)) {
                    $this->copy_dir($source . '/' . $file, $dest . '/' . $file);
                    continue;
                } else {
                    if (!is_dir($dest)) {
                        $this->create_dir($dest);
                    }
                    copy($source . '/' . $file, $dest . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
}

/**
 * 通用方法封装类
 * 
 * @author Mosquito
 * @link www.360cd.cn
 */
class Methods {

    /**
     * 根据时间戳获取当天的开始时间戳与结束时间戳
     * 
     * @param bigint $timestamp            
     * @param bool $zone
     *            是否计算时区，默认启用
     */
    static function get_day_timestamp($timestamp, $zone = true) {
        $timezone = $zone ? date('Z') : 0;
        $Y_m_d = date('Y-m-d', $timestamp + $timezone);
        
        $day = array();
        $day[] = strtotime($Y_m_d) - $timezone;
        $day[] = $day[0] + 86400;
        
        return $day;
    }

    /**
     * 根据时间戳获取当前周的开始时间戳与结束时间戳
     * 
     * @param bigint $timestamp            
     * @param bool $zone
     *            是否计算时区，默认启用
     */
    static function get_week_timestamp($timestamp, $zone = true) {
        $timezone = $zone ? date('Z') : 0;
        $date = getdate($timestamp + $timezone);
        
        $Y_m_d = date('Y-m-d', $timestamp + $timezone);
        $day = strtotime($Y_m_d) - $timezone;
        
        $week = array();
        $week[] = $day - $date['wday'] * 86400;
        $week[] = $week[0] + 7 * 86400;
        
        return $week;
    }

    /**
     * 保存数组配置型文件
     * 
     * @param string $filename            
     * @param array $data            
     * @return array
     */
    static function save_config($filename, $data) {
        return file_put_contents($filename, "<?php\nreturn " . var_export($data, true) . ";\n?>");
    }

    /**
     * 加载数组配置型文件
     * 
     * @param string $filename            
     * @param string $key
     *            'a:b:c'支持多层
     * @return mixed
     */
    static function load_config($filename, $key = '') {
        $config = file_exists($filename) ? include ($filename) : array();
        if ($key != '') {
            $key_arr = explode(':', $key);
            foreach ( $key_arr as $v ) {
                $config = $config[$v];
            }
        }
        return $config;
    }

    /**
     * 获取一个GUID字串
     * 
     * @param string $glue            
     * @return string
     */
    static function get_guid($glue = '') {
        $charid = strtoupper(md5(uniqid(mt_rand(), true)));
        $hyphen = $glue;
        $guid = substr($charid, 6, 2) . substr($charid, 4, 2) . substr($charid, 2, 2) . substr($charid, 0, 2) . $hyphen . substr($charid, 10, 2) . substr($charid, 8, 2) . $hyphen . substr($charid, 14, 2) . substr($charid, 12, 2) . $hyphen . substr($charid, 16, 4) . $hyphen . substr($charid, 20, 12);
        return $guid;
    }

    /**
     * 获取客户端真实ip
     * 
     * @return string
     */
    static function get_client_ip() {
        if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } else if (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } else if (getenv('REMOTE_ADDR')) {
            $ip = getenv('REMOTE_ADDR');
        } else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR']) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = '';
        }
        return $ip;
    }

    /**
     * url操作：获取、添加、删除
     * 
     * @param string $url            
     * @param string $name            
     * @param string $value            
     * @param bool $del            
     * @return string|mixed
     */
    static function url($url = '', $name = '', $value = '', $del = false) {
        if ($url == '') {
            $url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
        } else if ($url == 'host') {
            $url = 'http://' . $_SERVER['HTTP_HOST'];
        } else if ($url == 'host|self') {
            $url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
        }
        
        if ($name == '') {
            return $url;
        }
        
        $str = '';
        preg_match('/(\\\?|&)' . $name . '=([^&]+)(&|$)/', $url, $str);
        
        if (!$del) {
            if ($str[1] == '?') {
                return str_replace($str[0], '?', $url);
            } else if ($str[3] == '&') {
                return str_replace($str[0], '&', $url);
            } else {
                return str_replace($str[0], '', $url);
            }
        } else {
            if ($value != '') {
                if ($str) {
                    return str_replace($str[0], $str[1] . $name . '=' . $value . $str[3], $url);
                } else {
                    if (strstr($url, '?') == false) {
                        return $url . '?' . $name . '=' . $value;
                    } else {
                        return $url . '&' . $name . '=' . $value;
                    }
                }
            } else {
                if ($str) {
                    return $str[2];
                } else {
                    return '';
                }
            }
        }
    }

    /**
     * 远程获取数据curl
     * @param string $url
     * @param string $action
     * @param array $param
     */
    static function curl($url, $action = 'GET', $param = array(), $decode = true) {
        $action = strtoupper($action);
        ini_set('open_basedir', '');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $action);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off')) {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        }
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        $action == 'POST' ? curl_setopt($ch, CURLOPT_POSTFIELDS, $param) : null;
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        if (curl_errno($ch)) {
            return;
            return curl_error($ch);
        }
        curl_close($ch);
        $json_data = $tmpInfo;
        return $decode ? json_decode($json_data, true) : $json_data;
    }
}

<?php

/**
 * ECMall: SESSION 公用类库
 * ============================================================================
 * 版权所有 (C) 2005-2008 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.shopex.cn
 * -------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Id: session.lib.php 12147M 2012-06-23 16:38:46Z (local) $
 */

if (!defined('IN_ECM'))
{
    die('Hacking attempt');
}

class SessionProcessor
{
    var $db             = NULL;
    var $session_table  = '';
    var $session_data_table = '';

    var $max_life_time  = 1440; // SESSION 过期时间

    var $session_name   = '';
    var $session_id     = '';


    var $session_cookie_path   = '/';
    var $session_cookie_domain = '';
    var $session_cookie_secure = false;

    var $_ip   = '';

    var $_related_tables = array();
    var $gmtime = 0;

    /**
     * 构造函数
     *
     * @author wj
     * @param object $db 数据库对象
     * @param stirng $session_table 数据表名
     * @param string $session_data_table 数据存储表名
     * @param string $session_name session名称
     * @param string $session_id session_id
     * @return void
     */
    function __construct(&$db, $session_table, $session_data_table, $session_name = 'ECM_ID', $session_id = '')
    {
        $this->SessionProcessor($db, $session_table, $session_data_table, $session_name, $session_id);
    }

    /**
     * 构造函数
     *
     * @author weberliu
     * @param object $db 数据库对象
     * @param stirng $session_table 数据表名
     * @param string $session_data_table 数据存储表名
     * @param string $session_name session名称
     * @param string $session_id session_id
     * @return void
     */
    function SessionProcessor(&$db, $session_table, $session_data_table, $session_name = 'ECM_ID', $session_id = '')
    {
        session_set_save_handler(   array (& $this, "_sess_open"),
                                    array (& $this, "_sess_close"),
                                    array (& $this, "_sess_read"),
                                    array (& $this, "_sess_write"),
                                    array (& $this, "_sess_destroy"),
                                    array (& $this, "_sess_gc")
                                );
        $this->gmtime = gmtime();
        $this->max_life_time = 1440;
        $this->session_cookie_path = COOKIE_PATH;
        $this->session_cookie_domain = COOKIE_DOMAIN;
        //如果开启二级域名,且未设置COOKIE作用域，则缺省为上级域
        if (defined('ENABLED_SUBDOMAIN') && ENABLED_SUBDOMAIN && !COOKIE_DOMAIN)
        {
            $tmp_arr = parse_url(SITE_URL);
            if (count(explode('.', $tmp_arr['host'])) > 2)
            {
                $cookie_domain = substr($tmp_arr['host'], strpos($tmp_arr['host'], '.'));
            }
            else
            {
                // 形如ecmall.com这样的域名
                $cookie_domain = '.' . $tmp_arr['host'];
            }
            $this->session_cookie_domain = $cookie_domain;
        }

        $this->session_cookie_secure = false;

        $this->session_name       = $session_name;
        $this->session_table      = $session_table;
        $this->session_data_table = $session_data_table;

        $this->db  = &$db;
        $this->_ip = real_ip();


        /*处理session id*/
        if ($session_id == '' && !empty($_COOKIE[$this->session_name]))
        {
            $this->session_id = $_COOKIE[$this->session_name];
        }
        else
        {
            $this->session_id = $session_id;
        }

        if ($this->session_id)
        {
            $tmp_session_id = substr($this->session_id, 0, 32);

            if ($this->gen_session_key($tmp_session_id) == substr($this->session_id, 32))
            {
                $this->session_id = $tmp_session_id;
            }
            else
            {
                $this->session_id = '';
            }
        }

        if (!$this->session_id)
        {
            $this->gen_session_id();
            session_id($this->session_id . $this->gen_session_key($this->session_id));
            /*setcookie($this->session_name, $this->session_id . $this->gen_session_key($this->session_id), 0,
                $this->session_cookie_path, $this->session_cookie_domain, $this->session_cookie_secure);*/
        }

    }

    /**
     * open session handler
     *
     * @author wj
     * @param string $save_path
     * @param string $session_name
     * @return boolen
     */
    function _sess_open($save_path, $session_name)
    {
        return true;
    }

    /**
     * close session handler
     *
     * @author wj
     * @return boolen
     */
    function _sess_close()
    {
        return true;
    }

    /**
     * read session handler
     *
     * @author wj
     * @param string $sesskey
     * @return string
     */
    function _sess_read($sesskey)
    {
        $row = $this->db->getRow('SELECT data, expiry, is_overflow FROM ' . $this->session_table . " WHERE sesskey = '" . $this->session_id . "'");
        if (!empty($row))
        {
            if ($row['is_overflow'])
            {
                $row = $this->db->getRow('SELECT data, expiry FROM ' . $this->session_data_table . " WHERE sesskey = '" . $this->session_id . "'");
            }
        }
        else
        {
            $this->insert_session();
        }

        return isset($row['data']) ? $row['data'] : '';
    }

    /**
     * write session handler
     *
     * @author Garbin
     * @param stirng $sesskey
     * @param string $sessvalue
     * @return boolen
     */
    function _sess_write($sesskey, $sessvalue)
    {
        $sessvalue = addslashes($sessvalue);
        $adminid = !empty($_SESSION['admin_id']) ? intval($_SESSION['admin_id']) : 0;
        $userid  = !empty($_SESSION['user_id'])  ? intval($_SESSION['user_id'])  : 0;
        $expiry  = $this->get_expiry();
        $is_overflow = 0;
        if (isset($sessvalue{255}))
        {
            $this->db->query("REPLACE INTO `ecm_sessions_data` SET sesskey = '{$this->session_id}', expiry={$expiry}, data='{$sessvalue}'");
            $is_overflow = 1;
            $sessvalue = '';
        }

        return $this->db->query('UPDATE ' . $this->session_table . " SET expiry = '" . $expiry . "', ip = '" . $this->_ip . "', userid = '" . $userid . "', adminid = '" . $adminid . "', data = '" . $sessvalue ."', is_overflow='" . $is_overflow . "' WHERE sesskey = '" . $this->session_id . "' LIMIT 1");
    }

    /**
     * destory session handler
     *
     * @author wj
     * @param stirng $sesskey
     * @return void
     */
    function _sess_destroy($sesskey)
    {
        $this->destroy_session();
    }

    /**
     * gc session handler 清除过期session
     *
     * @author weberliu
     * @param int $maxlifetime
     * @return boolen
     */
    function _sess_gc($maxlifetime)
    {
        /* 删除过期session所存放在Cart表里的信息 */
        $expired_session = $this->db->getCol("SELECT s.sesskey ".
                                             "FROM `ecm_sessions` s ".
                                             "WHERE s.expiry < {$this->gmtime}");
        if (!empty($this->_related_tables))
        {
            foreach ($this->_related_tables as $_t)
            {
                $_t['ext_limit'] = $_t['ext_limit'] ? $_t['ext_limit'] . ' AND ' : '';
                $this->db->query("DELETE FROM {$_t['name']} WHERE {$_t['ext_limit']}{$_t['ref_key']} " . db_create_in($expired_session));
            }
        }
        $this->db->query('DELETE FROM ' . $this->session_table . ' WHERE expiry < ' . $this->gmtime);
        $this->db->query('DELETE FROM ' . $this->session_data_table . ' WHERE expiry < ' . $this->gmtime);

        return true;
    }

    /**
     * 生成session id
     *
     * @author wj
     * @return string
     */
    function gen_session_id()
    {
        $this->session_id = md5(uniqid(mt_rand(), true));

        return $this->insert_session();
    }

    /**
     * 生成session验证串
     *
     * @author wj
     * @param string $session_id
     * @return stirng
     */
    function gen_session_key($session_id)
    {
        static $ip = '';

        if ($ip == '')
        {
            $ip = substr($this->_ip, 0, strrpos($this->_ip, '.'));
        }

        return sprintf('%08x', crc32(!empty($_SERVER['HTTP_USER_AGENT']) ? ROOT_PATH . $ip . $session_id : ROOT_PATH . $ip . $session_id));
    }

    /**
     * 插入一个新session
     *
     * @author wj
     * @return void
     */
    function insert_session()
    {
        $adminid = !empty($_SESSION['admin_id']) ? intval($_SESSION['admin_id']) : 0;
        $userid  = !empty($_SESSION['user_id'])  ? intval($_SESSION['user_id'])  : 0;
        $expiry  = $this->get_expiry();
        return $this->db->query('INSERT INTO ' . $this->session_table . " SET sesskey = '" . $this->session_id . "', expiry = '" . $expiry . "', ip = '" . $this->_ip . "', userid = '" . $userid . "', adminid = '" . $adminid . "', data = ''");
    }

    /**
     * 清除指定管理员session
     *
     * @param int $adminid
     * @return boolen
     */
    function delete_spec_admin_session($adminid)
    {
        if (!empty($_SESSION['admin_id']) && $adminid)
        {
            return $this->db->query('DELETE FROM ' . $this->session_table . " WHERE adminid = '$adminid'");
        }
        else
        {
            return false;
        }
    }

    /**
     * 清除一个session
     *
     * @author wj
     * @return boolen
     */
    function destroy_session()
    {
        $_SESSION = array();

        setcookie($this->session_name, $this->session_id, 1, $this->session_cookie_path, $this->session_cookie_domain, $this->session_cookie_secure);
        if (!empty($this->_related_tables))
        {
            foreach ($this->_related_tables as $_t)
            {
                $_t['ext_limit'] = $_t['ext_limit'] ? $_t['ext_limit'] . ' AND ' : '';
                $this->db->query("DELETE FROM {$_t['name']} WHERE {$_t['ext_limit']}{$_t['ref_key']} = '{$this->session_id}'");
            }
        }
        $this->db->query('DELETE FROM ' . $this->session_data_table . " WHERE sesskey = '" . $this->session_id . "' LIMIT 1");

        return $this->db->query('DELETE FROM ' . $this->session_table . " WHERE sesskey = '" . $this->session_id . "' LIMIT 1");
    }

    /**
     * 获取当前session id
     *
     * @author wj
     * @return string
     */
    function get_session_id()
    {
        return $this->session_id;
    }

    /**
     * 获取用户数量
     *
     * @author wj
     * @return int
     */
    function get_users_count()
    {
        $num = $this->db->getOne('SELECT count(*) FROM ' . $this->session_table . ' WHERE expiry >=' .$this->gmtime);

        return $num > 0 ? $num : 1;
    }

    /**
     * 添加关联表
     *
     * @author wj
     * @param string $table_name
     * @param string $alias
     * @param string $related_key
     * @return void
     */
    function add_related_table($table_name, $alias, $related_key, $ext_limit = '')
    {
        $this->_related_tables[] = array('name' => $table_name,
                                        'alias' => $alias,
                                        'ref_key' => $related_key,
                                        'ext_limit' => $ext_limit);
    }

    /**
     * 获取过期时间
     *
     * @author wj
     * @return void
     */
    function get_expiry()
    {
        return $this->gmtime + $this->max_life_time;
    }

    /**
     * 打开session
     *
     * @author wj
     * @return void
     */
    function my_session_start()
    {
            session_name($this->session_name); // 自定义session_name
            session_set_cookie_params(0, $this->session_cookie_path, $this->session_cookie_domain, $this->session_cookie_secure);
        return session_start();
    }
}

class MemcacheSession
{
    var $_memcache = null; // memcache服务器
    var $max_life_time = 1440; // session 过期时间
    var $session_cookie_path = '/'; 
    var $session_cookie_domain = '';
    var $session_cookie_secure = false;
    var $session_name = '';
    var $gmtime = 0;
    var $_ip = '';
    
    function __construct($memcache_servers, $session_name = 'ECM_ID')
    {
        $this->MemcacheSession($memcache_servers, $session_name);
    }
    
    function MemcacheSession($memcache_server, $session_name = 'ECM_ID')
    {
        // Create memcache object
        if ($this->_memcache === null)
        {
            $this->_memcache = new Memcache();
        }
        list($host, $port) = explode(':', $memcache_server);
        $this->_memcache->connect($host, $port);

        session_set_save_handler(   array (& $this, "_sess_open"),
                                    array (& $this, "_sess_close"),
                                    array (& $this, "_sess_read"),
                                    array (& $this, "_sess_write"),
                                    array (& $this, "_sess_destroy"),
                                    array (& $this, "_sess_gc")
                                );
        register_shutdown_function('session_write_close');
        $this->max_life_time = defined('SESSION_LIFE_TIME') ? SESSION_LIFE_TIME : 1440;
        $this->session_cookie_path = COOKIE_PATH;
        $this->session_cookie_domain = COOKIE_DOMAIN;
        //如果开启二级域名,且未设置COOKIE作用域，则缺省为上级域
        if (defined('ENABLED_SUBDOMAIN') && ENABLED_SUBDOMAIN && !COOKIE_DOMAIN)
        {
            $tmp_arr = parse_url(SITE_URL);
            if (count(explode('.', $tmp_arr['host'])) > 2)
            {
                $cookie_domain = substr($tmp_arr['host'], strpos($tmp_arr['host'], '.'));
            }
            else
            {
                // 形如ecmall.com这样的域名
                $cookie_domain = '.' . $tmp_arr['host'];
            }
            $this->session_cookie_domain = $cookie_domain;
        }
        $this->session_cookie_secure = false;
        $this->session_name       = $session_name;
        $this->gmtime = gmtime();
        $this->_ip = real_ip();
        /*处理session id*/
        if ($session_id == '' && !empty($_COOKIE[$this->session_name]))
        {
            $this->session_id = $_COOKIE[$this->session_name];
        }
        else
        {
            $this->session_id = $session_id;
        }

        if ($this->session_id)
        {
            $tmp_session_id = substr($this->session_id, 0, 32);
            if ($this->gen_session_key($tmp_session_id) == substr($this->session_id, 32))
            {
                $this->session_id = $tmp_session_id;
            }
            else
            {
                $this->session_id = '';
            }
        }

        if (!$this->session_id)
        {
            $this->gen_session_id();
            session_id($this->session_id . $this->gen_session_key($this->session_id));
            /*setcookie($this->session_name, $this->session_id . $this->gen_session_key($this->session_id), 0,
                $this->session_cookie_path, $this->session_cookie_domain, $this->session_cookie_secure);*/
        }
    }
    
    /**
     * open session handler
     *
     * @author wj
     * @param string $save_path
     * @param string $session_name
     * @return boolen
     */
    function _sess_open($save_path, $session_name)
    {
        return true;
    }
    
    /**
     * read session handler
     *
     * @author wj
     * @param string $sesskey
     * @return string
     */
    function _sess_read($sesskey)
    {
        $data = $this->_memcache->get($this->session_id);
        if ($data === false)
        {
            $this->insert_session();
            return '';
        }
        else
        {
            return $data;
        }
    }
    
    /**
     * write session handler
     *
     * @author Garbin
     * @param stirng $sesskey
     * @param string $sessvalue
     * @return boolen
     */
    function _sess_write($sesskey, $sessvalue)
    {
        return $this->_memcache->set($this->session_id, $sessvalue, 0, $this->max_life_time);
    }
    
    /**
     * close session handler
     *
     * @author wj
     * @return boolen
     */
    function _sess_close()
    {
        return true;
    }
    
    /**
     * destory session handler
     *
     * @author wj
     * @param stirng $sesskey
     * @return void
     */
    function _sess_destroy($sesskey)
    {
        $this->destroy_session();
    }
    
    /**
     * gc session handler 清除过期session
     *
     * @author weberliu
     * @param int $maxlifetime
     * @return boolen
     */
    function _sess_gc($maxlifetime)
    {
        // 过期Session数据Memcache会自动清理，相关数据TODO
        return true;
    }
    
    /**
     * 生成session id
     *
     * @author wj
     * @return string
     */
    function gen_session_id()
    {
        $this->session_id = md5(uniqid(mt_rand(), true));

        return $this->insert_session();
    }
    
    /**
     * 生成session验证串
     *
     * @author wj
     * @param string $session_id
     * @return stirng
     */
    function gen_session_key($session_id)
    {
        static $ip = '';

        if ($ip == '')
        {
            $ip = substr($this->_ip, 0, strrpos($this->_ip, '.'));
        }

        return sprintf('%08x', crc32(!empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] . ROOT_PATH . $ip . $session_id : ROOT_PATH . $ip . $session_id));
    }
    
    /**
     * 插入一个新session
     *
     * @author wj
     * @return void
     */
    function insert_session()
    {
        $result = $this->_memcache->set($this->session_id, '', 0, $this->max_life_time);
        if ($result === false)
        {
            exit('Data Cannot be written on memcached');
        }
    }
    
    /**
     * 清除一个session
     *
     * @author wj
     * @return boolen
     */
    function destroy_session()
    {
        $_SESSION = array();

        setcookie($this->session_name, $this->session_id, 1, $this->session_cookie_path, $this->session_cookie_domain, $this->session_cookie_secure);

        return $this->delete_session($this->session_id);
    }
    
    /**
     * 删除指定ID的Session
     *
     * @author Garbin
     * @param  string $session_id
     * @return bool
     **/
    function delete_session($session_id)
    {
        return $this->_memcache->delete($session_id);
    }
    
    /**
     * 获取当前session id
     *
     * @author wj
     * @return string
     */
    function get_session_id()
    {
        return $this->session_id;
    }
    
    /**
     * 获取用户数量
     *
     * @author wj
     * @return int
     */
    function get_users_count()
    {
        $stats = $this->_memcache->getStats();

        return $stats['curr_items'];
    }
    
    /**
     * 打开session
     *
     * @author wj
     * @return void
     */
    function my_session_start()
    {
        session_name($this->session_name); // 自定义session_name
        session_set_cookie_params(0, $this->session_cookie_path, $this->session_cookie_domain, $this->session_cookie_secure);
        return session_start();
    }
}

?>
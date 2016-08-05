<?php

define('CACHE_DIR_NUM', 500); // 缓存目录数量，根据预期缓存文件数调整，开根号即可

/**
 *    基础缓存类接口
 *
 *    @author    Garbin
 *    @usage    none
 */
class CacheServer extends Object
{
    var $_options = null;
    function __construct($options = null)
    {
        $this->CacheServer($options);
    }
    function CacheServer($options = null)
    {
        $this->_options = $options;
    }

    /**
     *    获取缓存的数据
     *
     *    @author    Garbin
     *    @param     string $key
     *    @return    mixed
     */
    function &get($key){}
    /**
     *    设置缓存
     *
     *    @author    Garbin
     *    @param     string $key
     *    @param     mixed  $value
     *    @param     int    $ttl
     *    @return    bool
     */
    function set($key, $value, $ttl = 0){}
    /**
     *    清空缓存
     *
     *    @author    Garbin
     *    @return    bool
     */
    function clear(){}

    /**
     *    删除一个缓存
     *
     *    @author    Garbin
     *    @param     string $key
     *    @return    bool
     */
    function delete($key){}
}

/**
 *    普通PHP文件缓存
 *
 *    @author    Garbin
 *    @usage    none
 */
class PhpCacheServer extends CacheServer
{
    /* 缓存目录 */
    var $_cache_dir = './';
    function set($key, $value, $ttl = 0)
    {
        if (!$key)
        {
            return false;
        }
        $cache_file = $this->_get_cache_path($key);
        $cache_data = "<?php\r\n/**\r\n *  @Created By ECMall PhpCacheServer\r\n *  @Time:" . date('Y-m-d H:i:s') . "\r\n */";
        $cache_data .= $this->_get_expire_condition(intval($ttl));
        $cache_data .= "\r\nreturn " . var_export($value, true) .  ";\r\n";
        $cache_data .= "\r\n?>";

        return file_put_contents($cache_file, $cache_data, LOCK_EX);
    }
    function &get($key)
    {
        $cache_file = $this->_get_cache_path($key);
        if (!is_file($cache_file))
        {
            return false;
        }
        $data = include($cache_file);

        return $data;
    }
    function clear()
    {
        $dir = dir($this->_cache_dir);
        while (false !== ($item = $dir->read()))
        {
            if ($item == '.' || $item == '..' || substr($item, 0, 1) == '.')
            {
                continue;
            }
            $item_path = $this->_cache_dir . '/' . $item;
            if (is_dir($item_path))
            {
                ecm_rmdir($item_path);
            }
            else
            {
                _at(unlink, $item_path);
            }
        }

        return true;
    }
    function delete($key)
    {
        $cache_file = $this->_get_cache_path($key);

        return _at(unlink, $cache_file);
    }
    function set_cache_dir($path)
    {
        $this->_cache_dir = $path;
    }
    function _get_expire_condition($ttl = 0)
    {
        if (!$ttl)
        {
            return '';
        }

        return "\r\n\r\n" . 'if(filemtime(__FILE__) + ' . $ttl . ' < time())return false;' . "\r\n";
    }
    function _get_cache_path($key)
    {
        $dir = str_pad(abs(crc32($key)) % CACHE_DIR_NUM, 4, '0', STR_PAD_LEFT);
        ecm_mkdir($this->_cache_dir . '/' . $dir);
        return $this->_cache_dir . '/' . $dir .  '/' . $this->_get_file_name($key);
    }
    function _get_file_name($key)
    {
        return md5($key) . '.cache.php';
    }
}

/**
 *    Memcached
 *
 *    @author    Garbin
 *    @usage    none
 */
class MemcacheServer extends CacheServer
{
    var $_memcache = null;
    function __construct($options)
    {
        $this->MemcacheServer($options);
    }
    function MemcacheServer($options)
    {
        parent::__construct($options);

        /* 连接到缓存服务器 */
        $this->connect($this->_options);
    }

    /**
     *    连接到缓存服务器
     *
     *    @author    Garbin
     *    @param     array $options
     *    @return    bool
     */
    function connect($options)
    {
        if (empty($options))
        {
            return false;
        }
        $this->_memcache = new Memcache;

        return $this->_memcache->connect($options['host'], $options['port']);
    }

    /**
     *    写入缓存
     *
     *    @author    Garbin
     *    @param    none
     *    @return    void
     */
    function set($key, $value, $ttl = null)
    {
        return $this->_memcache->set($key, $value, $ttl);
    }

    /**
     *    获取缓存
     *
     *    @author    Garbin
     *    @param     string $key
     *    @return    mixed
     */
    function &get($key)
    {
        return $this->_memcache->get($key);
    }

    /**
     *    清空缓存
     *
     *    @author    Garbin
     *    @return    bool
     */
    function clear()
    {
        return $this->_memcache->flush();
    }

    function delete($key)
    {
        return $this->_memcache->delete($key);
    }
}

?>
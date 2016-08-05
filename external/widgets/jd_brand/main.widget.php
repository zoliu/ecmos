<?php

/**
 * 品牌挂件
 *
 * @return  array
 */
class Jd_brandWidget extends BaseWidget
{
    var $_name = 'jd_brand';
    var $_ttl  = 86400;

    function _get_data()
    {
        $cache_server =& cache_server();
        $key = $this->_get_cache_id();
        $data = $cache_server->get($key);
        if($data === false)
        {
            $data = Psmb_init()->Jd_widget_get_brand_list($this->options['tag'],$this->options['num']);
            $cache_server->set($key, $data, $this->_ttl);
        }
        return $data;
    }
}

?>
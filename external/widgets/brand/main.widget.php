<?php

/**
 * 品牌挂件
 *
 * @return  array
 */
class BrandWidget extends BaseWidget
{
    var $_name = 'brand';
    var $_ttl  = 86400;
    var $_num  = 10;

    function _get_data()
    {
        $cache_server =& cache_server();
        $key = $this->_get_cache_id();
        $data = $cache_server->get($key);
        if($data === false)
        {
            $brand_mod =& m('brand');
            $data = $brand_mod->find(array(
                'conditions' => "recommended = 1",
                'order' => 'sort_order',
                'limit' => $this->_num,
            ));
            $cache_server->set($key, $data, $this->_ttl);
        }

        return $data;
    }
}

?>
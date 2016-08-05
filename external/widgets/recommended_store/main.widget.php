<?php

/**
 * 推荐店铺挂件
 *
 * @param   int     $num    数量
 * @return  array   $data
 */
class Recommended_storeWidget extends BaseWidget
{
    var $_name = 'recommended_store';
    var $_ttl  = 86400;

    function _get_data()
    {
        if (empty($this->options['num']) || intval($this->options['num']) <= 0)
        {
            $this->options['num'] = 5;
        }

        $cache_server =& cache_server();
        $key = $this->_get_cache_id();
        $data = $cache_server->get($key);
        if($data === false)
        {
            $store_mod =& m('store');
            $data = $store_mod->find(array(
                'conditions' => "state = 1 AND recommended = 1",
                'order' => 'sort_order',
                'fields' => 'store_id, store_name, store_logo, praise_rate, user_name',
                'join' => 'belongs_to_user',
                'limit' => $this->options['num'],
            ));
            $goods_mod =& m('goods');
            foreach ($data as $key => $store)
            {
                $data[$key]['goods_count'] = $goods_mod->get_count_of_store($store['store_id']);
                empty($store['store_logo']) && $data[$key]['store_logo'] = Conf::get('default_store_logo');
            }
            $cache_server->set($key, $data, $this->_ttl);
        }

        return $data;
    }
}

?>
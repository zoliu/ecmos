<?php

/**
 * 合作伙伴挂件
 *
 * @return  array
 */
class PartnerWidget extends BaseWidget
{
    var $_name = 'partner';
    var $_ttl  = 86400;

    function _get_data()
    {
        if (empty($this->options['num']) || intval($this->options['num']) <= 0)
        {
            $this->options['num'] = 10;
        }

        $cache_server =& cache_server();
        $key = $this->_get_cache_id();
        $data = $cache_server->get($key);
        if($data === false)
        {
            $partner_mod =& m('partner');
            $data = $partner_mod->find(array(
                'conditions' => "store_id = 0",
                'order' => 'sort_order',
                'limit' => $this->options['num'],
            ));
            $cache_server->set($key, $data, $this->_ttl);
        }

        return $data;
    }
}

?>
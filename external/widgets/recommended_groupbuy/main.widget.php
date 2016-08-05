<?php

/**
 * 推荐团购挂件
 *
 * @return  array
 */
class Recommended_groupbuyWidget extends BaseWidget
{
    var $_name = 'recommended_groupbuy';
    var $_ttl  = 1800;
    var $_num  = 6;

    function _get_data()
    {
        $cache_server =& cache_server();
        $key = $this->_get_cache_id();
        $data = $cache_server->get($key);
        if($data === false)
        {
            $model_groupbuy =& m('groupbuy');
            $data = $model_groupbuy->find(array(
                'join'  => 'belong_goods',
                'conditions'    => $model_groupbuy->getRealFields('this.recommended=1 AND this.state=' . GROUP_ON . ' AND this.end_time>' . gmtime()),
                'fields'    => 'group_id, goods.default_image, group_name, end_time, spec_price',
                'order' => 'group_id DESC',
                'limit' => $_num,
            ));
            if ($data)
            {
                foreach ($data as $gb_id => $gb_info)
                {
                    $price = current(unserialize($gb_info['spec_price']));
                    empty($gb_info['default_image']) && $data[$gb_id]['default_image'] = Conf::get('default_goods_image');
                    $data[$gb_id]['lefttime']   = lefttime($gb_info['end_time']);
                    $data[$gb_id]['price']      = $price['price'];
                }
            }
            $cache_server->set($key, $data, $this->_ttl);
        }

        return $data;
    }
}

?>
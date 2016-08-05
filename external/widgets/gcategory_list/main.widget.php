<?php

/**
 * 商品分类挂件
 *
 * @return  array   $category_list
 */
class Gcategory_listWidget extends BaseWidget
{
    var $_name = 'gcategory_list';
    var $_ttl  = 86400;


    function _get_data()
    {
        $this->options['amount'] = intval($this->options['amount']);
        $cache_server =& cache_server();
        $key = $this->_get_cache_id();
        $data = $cache_server->get($key);
        if($data === false)
        {
            $gcategory_mod =& bm('gcategory', array('_store_id' => 0));
            $gcategories = array();
            if(empty($this->options['amount']))
            {
                $gcategories = $gcategory_mod->get_list(-1, true);
            }
            else
            {
                $gcategory = $gcategory_mod->get_list(0, true);
                $gcategories = $gcategory;
                foreach ($gcategory as $val)
                {
                    $result = $gcategory_mod->get_list($val['cate_id'], true);
                    $result = array_slice($result, 0, $this->options['amount']);
                    $gcategories = array_merge($gcategories, $result);
                }
            }
            import('tree.lib');
            $tree = new Tree();
            $tree->setTree($gcategories, 'cate_id', 'parent_id', 'cate_name');

            $data = $tree->getArrayList(0);
            $cache_server->set($key, $data, $this->_ttl);
        }

        return $data;
    }

    function parse_config($input)
    {
        $result = array();
        $result['amount'] = $input['amount'];
        return $result;
    }
}

?>
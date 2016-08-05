<?php

/**
 * 商品分类挂件
 *
 * @return  array   $category_list
 */
class mll2015_channel_categoryWidget extends BaseWidget {

    var $_name = 'mll2015_channel_category';
    var $_ttl = 86400;

    function _get_data() {
        $cache_server = & cache_server();
        $key = $this->_get_cache_id();
        $data = $cache_server->get($key);
        if ($data === false) {
            $gcategory_mod = & bm('gcategory', array('_store_id' => 0));
            $gcategories = array();
            $gcategories = $gcategory_mod->get_list(-1, true);
            import('tree.lib');
            $tree = new Tree();
            $tree->setTree($gcategories, 'cate_id', 'parent_id', 'cate_name');

            if(empty($this->options['cate_id'])){
                $gcategories = $tree->getArrayList(0);
            }else{
                $gcategories = $tree->getArrayList($this->options['cate_id']);
            }
            $data = array(
                'gcategories' => $gcategories,
                'model_name' => $this->options['model_name'],
            );
            $cache_server->set($key, $data, $this->_ttl);
        }
        return $data;
    }
    
    function get_config_datasrc() {
// 取得一级商品分类
        $this->assign('gcategories', $this->_get_gcategory_options(1));
    }

    function parse_config($input) {
        return $input;
    }

}

?>
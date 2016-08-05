<?php

class mll2015_floor8Widget extends BaseWidget {

    var $_name = 'mll2015_floor8';
    var $_ttl = 86400;
    var $_num = 4;

    function _get_data() {
        $cache_server = & cache_server();
        $key = $this->_get_cache_id();
        $data = $cache_server->get($key);
        
        if ($data === false) {
            $recom_mod = & m('recommend');
            $data = array(
                'model_id' => mt_rand(),
                'model_name' => $this->options['model_name'],
                'model_name1' => $this->options['model_name1'],
                //
                //产品调用
                'goods_list_1' => $recom_mod->get_recommended_goods($this->options['img_recom_id_1'], 5, true, $this->options['img_cate_id_1']),
            );
            $cache_server->set($key, $data, $this->_ttl);   
        }
        return $data;
    }



    function get_config_datasrc() {
        // 取得推荐类型
        $this->assign('recommends', $this->_get_recommends());
        // 取得一级商品分类
        $this->assign('gcategories', $this->_get_gcategory_options());
    }

}

?>
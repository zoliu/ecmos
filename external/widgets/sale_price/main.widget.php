<?php

/**
 * 特价商品挂件
 *
 * @param   int     $img_recom_id   图文推荐id
 * @param   int     $txt_recom_id   文字推荐id
 * @return  array
 */
class Sale_priceWidget extends BaseWidget
{
    var $_name = 'sale_price';
    var $_ttl  = 1800;

    function _get_data()
    {
        $cache_server =& cache_server();
        $key = $this->_get_cache_id();
        $data = $cache_server->get($key);
        if($data === false)
        {
            $recom_mod =& m('recommend');
            $img_goods_list = $recom_mod->get_recommended_goods($this->options['img_recom_id'], 3, true, $this->options['img_cate_id']);
            $txt_goods_list = $recom_mod->get_recommended_goods($this->options['txt_recom_id'], 4, true, $this->options['txt_cate_id']);
            $cache_server->set($key, array(
                'img_goods_list'=> $img_goods_list,
                'txt_goods_list'=> $txt_goods_list,
            ), $this->_ttl);
        }

        return array(
            'img_goods_list'=> $data['img_goods_list'],
            'txt_goods_list'=> $data['txt_goods_list'],
        );
    }

    function get_config_datasrc()
    {
        // 取得推荐类型
        $this->assign('recommends', $this->_get_recommends());

        // 取得一级商品分类
        $this->assign('gcategories', $this->_get_gcategory_options(1));
    }

    function parse_config($input)
    {
        if ($input['img_recom_id'] >= 0)
        {
            $input['img_cate_id'] = 0;
        }
        if ($input['txt_recom_id'] >= 0)
        {
            $input['txt_cate_id'] = 0;
        }

        return $input;
    }
}

?>
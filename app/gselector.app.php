<?php

/**
 *    搜索并选择商品
 *
 *    @author    Hyber
 *    @usage    none
 */

class GselectorApp extends MallbaseApp
{
    var $_is_dialog;      // 是否是对话框
    var $_title;
    var $_store_id = 0;     // 店铺ID

    var $_store_mod;

    function __construct()
    {
        $this->GselectorApp();
    }
    function GselectorApp()
    {
        parent::__construct();
        $this->_is_dialog = isset($_GET['dialog']);
        $this->_store_id = empty($_GET['store_id']) ? 0 : intval($_GET['store_id']);
        $this->_title = empty($_GET['title']) ? 'gselector' : trim($_GET['title']);

        $this->_store_mod = &m('store');
        $this->assign('title', Lang::get($this->_title));
    }
    function store()
    {
        if ($this->_is_dialog)
        {
            header('Content-Type:text/html;charset=' . CHARSET);
        }
        $this->assign('sgcategories', $this->_store_mod->get_sgcategory_options($this->_store_id));
        $this->display('gselector.store.html');
    }

    function store_goods()
    {
        $goods_mod = &bm('goods', array('_store_id' => $this->_store_id));

        /* 搜索条件 */
        $conditions = "1 = 1";
        if (trim($_GET['goods_name']))
        {
            $str = "LIKE '%" . trim($_GET['goods_name']) . "%'";
            $conditions .= " AND (goods_name {$str})";
        }

        if (intval($_GET['sgcate_id']) > 0)
        {
            $cate_mod =& bm('gcategory', array('_store_id' => $this->_store_id));
            $cate_ids = $cate_mod->get_descendant(intval($_GET['sgcate_id']));
        }
        else
        {
            $cate_ids = 0;
        }

        /* 取得商品列表 */
        $goods_list = $goods_mod->get_list(array(
            'conditions' => $conditions . ' AND g.if_show=1 AND g.closed=0',
            'order' => 'g.add_time DESC',
            'limit' => 100,
        ), $cate_ids);

        foreach ($goods_list as $key => $val)
        {
            $goods_list[$key]['goods_name'] = htmlspecialchars($val['goods_name']);
        }
        $this->json_result($goods_list);
    }
}

?>
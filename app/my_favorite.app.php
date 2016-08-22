<?php

/**
 *    我的收藏控制器
 *
 *    @author    Garbin
 *    @usage    none
 */
class My_favoriteApp extends MemberbaseApp
{
    /**
     *    收藏列表
     *
     *    @author    Garbin
     *    @return    void
     */
    function index()
    {
        $type = empty($_GET['type'])    ? 'goods' : trim($_GET['type']);
        if ($type == 'goods')
        {
            $this->_list_collect_goods();
        }
        elseif ($type == 'store')
        {
            /* 收藏店铺 */
            $this->_list_collect_store();
        }
    }

    /**
     *    收藏项目
     *
     *    @author    Garbin
     *    @return    void
     */
    function add()
    {
        $type = empty($_GET['type'])    ? 'goods' : trim($_GET['type']);
        $item_id = empty($_GET['item_id'])  ? 0 : intval($_GET['item_id']);
        $keyword = empty($_GET['keyword'])  ? '' : trim($_GET['keyword']);
        if (!$item_id)
        {
            $this->show_warning('no_such_collect_item');

            return;
        }
        if ($type == 'goods')
        {
            $this->_add_collect_goods($item_id, $keyword);
        }
        elseif ($type == 'store')
        {
            //360cd.cn born statics
            $statics_model=&m('statics');
            $statics_model->update($item_id,'collects',1);
            //360cd.cn 
            
            $this->_add_collect_store($item_id, $keyword);
        }
    }
    /**
     *    删除收藏的项目
     *
     *    @author    Garbin
     *    @return    void
     */
    function drop()
    {
        $type = empty($_GET['type'])    ? 'goods' : trim($_GET['type']);
        $item_id = empty($_GET['item_id'])  ? 0 : trim($_GET['item_id']);
        if (!$item_id)
        {
            $this->show_warning('no_such_collect_item');

            return;
        }
        if ($type == 'goods')
        {
            $this->_drop_collect_goods($item_id);
        }
        elseif ($type == 'store')
        {
            $this->_drop_collect_store($item_id);
        }
    }

    /**
     *    列表收藏的商品
     *
     *    @author    Garbin
     *    @return    void
     */
    function _list_collect_goods()
    {
        $conditions = $this->_get_query_conditions(array(array(
                'field' => 'goods_name',         //可搜索字段title
                'equal' => 'LIKE',          //等价关系,可以是LIKE, =, <, >, <>
            ),
        ));
        $model_goods =& m('goods');
        $page   =   $this->_get_page();    //获取分页信息
        $collect_goods = $model_goods->find(array(
            'join'  => 'be_collect,belongs_to_store,has_default_spec',
            'fields'=> 'this.*,store.store_name,store.store_id,collect.add_time,goodsspec.price,goodsspec.spec_id',
            'conditions' => 'collect.user_id = ' . $this->visitor->get('user_id') . $conditions,
            'count' => true,
            'order' => 'collect.add_time DESC',
            'limit' => $page['limit'],
        ));
        foreach ($collect_goods as $key => $goods)
        {
            empty($goods['default_image']) && $collect_goods[$key]['default_image'] = Conf::get('default_goods_image');
        }
        $page['item_count'] = $model_goods->getCount();   //获取统计的数据
        $this->_format_page($page);
        $this->assign('filtered', $conditions? 1 : 0); //是否有查询条件
        $this->assign('collect_goods', $collect_goods);
        /* 当前位置 */
        $this->_curlocal(LANG::get('member_center'),    'index.php?app=member',
                            LANG::get('my_favorite'), 'index.php?app=my_favorite',
                            LANG::get('collect_goods'));

        $this->import_resource(array(
            'script' => array(
                array(
                    'path' => 'dialog/dialog.js',
                    'attr' => 'id="dialog_js"',
                ),
                array(
                    'path' => 'jquery.ui/jquery.ui.js',
                    'attr' => '',
                ),
                array(
                    'path' => 'jquery.ui/i18n/' . i18n_code() . '.js',
                    'attr' => '',
                ),
                array(
                    'path' => 'jquery.plugins/jquery.validate.js',
                    'attr' => '',
                ),
            ),
            'style' =>  'jquery.ui/themes/ui-lightness/jquery.ui.css',
        ));

        //当前用户中心菜单项
        $this->_curitem('my_favorite');

        $this->_curmenu('collect_goods');
        $this->assign('page_info', $page);          //将分页信息传递给视图，用于形成分页条
        $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('collect_goods'));
        $this->display('my_favorite.goods.index.html');
    }

    /**
     *    列表收藏的店铺
     *
     *    @author    Garbin
     *    @return    void
     */
    function _list_collect_store()
    {
        $conditions = $this->_get_query_conditions(array(array(
                'field' => 'store_name',         //可搜索字段title
                'equal' => 'LIKE',          //等价关系,可以是LIKE, =, <, >, <>
            ),
        ));
        $model_store =& m('store');
        $page   =   $this->_get_page();    //获取分页信息
        $collect_store = $model_store->find(array(
            'join'  => 'be_collect,belongs_to_user',
            'fields'=> 'this.*,member.user_name,collect.add_time',
            'conditions' => 'collect.user_id = ' . $this->visitor->get('user_id') . $conditions,
            'count' => true,
            'order' => 'collect.add_time DESC',
            'limit' => $page['limit'],
        ));
        $page['item_count'] = $model_store->getCount();   //获取统计的数据
        $this->_format_page($page);
        $step = intval(Conf::get('upgrade_required'));
        $step < 1 && $step = 5;
        foreach ($collect_store as $key => $store)
        {
            empty($store['store_logo']) && $collect_store[$key]['store_logo'] = Conf::get('default_store_logo');
            $collect_store[$key]['credit_image'] = $this->_view->res_base . '/images/' . $model_store->compute_credit($store['credit_value'], $step);
        }
        $this->assign('collect_store', $collect_store);

        /* 当前位置 */
        $this->_curlocal(LANG::get('member_center'),    'index.php?app=member',
                        LANG::get('my_favorite'), 'index.php?app=my_favorite',
                        LANG::get('collect_store'));

        $this->import_resource(array(
            'script' => array(
                array(
                    'path' => 'dialog/dialog.js',
                    'attr' => 'id="dialog_js"',
                ),
                array(
                    'path' => 'jquery.ui/jquery.ui.js',
                    'attr' => '',
                ),
                array(
                    'path' => 'jquery.ui/i18n/' . i18n_code() . '.js',
                    'attr' => '',
                ),
                array(
                    'path' => 'jquery.plugins/jquery.validate.js',
                    'attr' => '',
                ),
            ),
            'style' =>  'jquery.ui/themes/ui-lightness/jquery.ui.css',
        ));
        //当前用户中心菜单项
        $this->_curitem('my_favorite');

        $this->_curmenu('collect_store');
        $this->assign('page_info', $page);          //将分页信息传递给视图，用于形成分页条
        $this->assign('filtered', $conditions? 1 : 0); //是否有查询条件
        $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('collect_store'));
        $this->display('my_favorite.store.index.html');
    }

    /**
     *    删除收藏的商品
     *
     *    @author    Garbin
     *    @param     int $item_id
     *    @return    void
     */
    function _drop_collect_goods($item_id)
    {
        $ids = explode(',', $item_id);

        /* 解除“我”与商品ID为$ids的收藏关系 */
        $model_user =& m('member');
        $model_user->unlinkRelation('collect_goods', $this->visitor->get('user_id'), $ids);
        if ($model_user->has_error())
        {
            $this->show_warning($model_user->get_error());

            return;
        }
        $this->show_message('drop_collect_goods_successed');
    }

    /**
     *    删除收藏的店铺
     *
     *    @author    Garbin
     *    @param     int $item_id
     *    @return    void
     */
    function _drop_collect_store($item_id)
    {
        $ids = explode(',', $item_id);

        /* 解除“我”与店铺ID为$ids的收藏关系 */
        $model_user =& m('member');
        $model_user->unlinkRelation('collect_store', $this->visitor->get('user_id'), $ids);
        if ($model_user->has_error())
        {
            $this->show_warning($model_user->get_error());

            return;
        }
        $this->show_message('drop_collect_store_successed');
    }

    /**
     *    收藏商品
     *
     *    @author    Garbin
     *    @param     int    $goods_id
     *    @param     string $keyword
     *    @return    void
     */
    function _add_collect_goods($goods_id, $keyword)
    {
        /* 验证要收藏的商品是否存在 */
        $model_goods =& m('goods');
        $goods_info  = $model_goods->get($goods_id);

        if (empty($goods_info))
        {
            /* 商品不存在 */
            $this->json_error('no_such_goods');
            return;
        }
        $model_user =& m('member');
        $model_user->createRelation('collect_goods', $this->visitor->get('user_id'), array(
            $goods_id   =>  array(
                'keyword'   =>  $keyword,
                'add_time'  =>  gmtime(),
            )
        ));

        /* 更新被收藏次数 */
        $model_goods->update_collect_count($goods_id);

        $goods_image = $goods_info['default_image'] ? $goods_info['default_image'] : Conf::get('default_goods_image');
        $goods_url  = SITE_URL . '/' . url('app=goods&id=' . $goods_id);
        $this->send_feed('goods_collected', array(
            'user_id'   => $this->visitor->get('user_id'),
            'user_name'   => $this->visitor->get('user_name'),
            'goods_url'   => $goods_url,
            'goods_name'   => $goods_info['goods_name'],
            'images'    => array(array(
                'url' => SITE_URL . '/' . $goods_image,
                'link' => $goods_url,
            )),
        ));

        /* 收藏成功 */
        $this->json_result('', 'collect_goods_ok');
    }

    /**
     *    收藏店铺
     *
     *    @author    Garbin
     *    @param     int    $store_id
     *    @param     string $keyword
     *    @return    void
     */
    function _add_collect_store($store_id, $keyword)
    {
        /* 验证要收藏的店铺是否存在 */
        $model_store =& m('store');
        $store_info  = $model_store->get($store_id);
        if (empty($store_info))
        {
            /* 店铺不存在 */
            return;
        }
        $model_user =& m('member');
        $model_user->createRelation('collect_store', $this->visitor->get('user_id'), array(
            $store_id   =>  array(
                'keyword'   =>  $keyword,
                'add_time'  =>  gmtime(),
            )
        ));
        $this->send_feed('store_collected', array(
            'user_id'   => $this->visitor->get('user_id'),
            'user_name'   => $this->visitor->get('user_name'),
            'store_url'   => SITE_URL . '/' . url('app=store&id=' . $store_id),
            'store_name'   => $store_info['store_name'],
        ));

        /* 收藏成功 */
        $this->json_result('', 'collect_store_ok');
    }

    function _get_member_submenu()
    {
        $menus = array(
            array(
                'name'  => 'collect_goods',
                'url'   => 'index.php?app=my_favorite',
            ),
            array(
                'name'  => 'collect_store',
                'url'   => 'index.php?app=my_favorite&amp;type=store',
            ),
        );
        return $menus;
    }
}

?>
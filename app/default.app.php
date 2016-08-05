<?php

class DefaultApp extends MallbaseApp
{
    function index()
    {
        $this->assign('index', 1); // 标识当前页面是首页，用于设置导航状态
        $this->assign('icp_number', Conf::get('icp_number'));

        /* 热门搜素 */
        $this->assign('hot_keywords', $this->_get_hot_keywords());

        $this->_config_seo(array(
            'title' => Lang::get('mall_index') . ' - ' . Conf::get('site_title'),
        ));
        //公告
            $acategory_mod =& m('acategory');
            $article_mod =& m('article');
            $data = $article_mod->find(array(
                'conditions'    => 'if_show = 1',
                'order'         => 'sort_order ASC, add_time DESC',
                'fields'        => 'article_id, title, add_time',
                'limit'         => 7,
            ));
            
        $this->assign('notices', $data);
        
        $this->assign('page_description', Conf::get('site_description'));
        $this->assign('page_keywords', Conf::get('site_keywords'));
        $this->display('index.html');
    }

    function _get_hot_keywords()
    {
        $keywords = explode(',', conf::get('hot_search'));
        return $keywords;
    }
}

?>
<?php

class ArticleApp extends MallbaseApp
{

    var $_article_mod;
    var $_acategory_mod;
    var $_ACC; //系统文章cate_id数据
    var $_cate_ids; //当前分类及子孙分类cate_id
    function __construct()
    {
        $this->ArticleApp();
    }
    function ArticleApp()
    {
        parent::__construct();
        $this->_article_mod = &m('article');
        $this->_acategory_mod = &m('acategory');
        /* 获得系统分类cate_id数据 */
        $this->_ACC = $this->_acategory_mod->get_ACC();
    }
    function index()
    {
        /* 取得导航 */
        $this->assign('navs', $this->_get_navs());

        /* 处理cate_id */
        $cate_id = !empty($_GET['cate_id'])? intval($_GET['cate_id']) : $this->_ACC[ACC_NOTICE]; //如果cate_id为空则默认显示商城快讯
        isset($_GET['code']) && isset($this->_ACC[trim($_GET['code'])]) && $cate_id = $this->_ACC[trim($_GET['code'])]; //如果有code
        /* 取得当前分类及子孙分类cate_id */
        $cate_ids = array();
        if ($cate_id > 0 && $cate_id != $this->_ACC[ACC_SYSTEM]) //排除系统内置分类
        {
            $cate_ids = $this->_acategory_mod->get_descendant($cate_id);
            if (!$cate_ids)
            {
                $this->show_warning('no_such_acategory');
                return;
            }
        }
        else
        {
            $this->show_warning('no_such_acategory');
            return;
        }
        $this->_cate_ids = $cate_ids;
        /* 当前位置 */
        $curlocal = $this->_get_article_curlocal($cate_id);
        unset($curlocal[count($curlocal)-1]['url']);
        $this->_curlocal($curlocal);
        /* 文章分类 */
        $acategories = $this->_get_acategory($cate_id);
        /* 分类下的所有文章 */
        $all = $this->_get_article('all');
        $articles = $all['articles'];
        $page = $all['page'];
        
        /* 新文章 */
        $new = $this->_get_article('new');
        $new_articles = $new['articles'];

        // 页面标题
        $category = $this->_acategory_mod->get_info($cate_id);
        $this->_config_seo('title', $category['cate_name'] . ' - ' . Conf::get('site_title'));

        $this->assign('articles', $articles);
        $this->assign('new_articles', $new_articles);
        $this->_format_page($page);
        $this->assign('page_info', $page);
        $this->assign('acategories', $acategories);
        $this->display('article.index.html');
    }

    function view()
    {
        $article_id = empty($_GET['article_id']) ? 0 : intval($_GET['article_id']);
        $cate_ids = array();
        if ($article_id>0)
        {
            $article = $this->_article_mod->get('article_id=' . $article_id . ' AND code = "" AND if_show=1 AND store_id=0');
            if (!$article)
            {
                $this->show_warning('no_such_article');
                return;
            }
            if ($article['link']){ //外链文章跳转
                header("HTTP/1.1 301 Moved Permanently");
                header('location:'.$article['link']);
                return;
            }
            /* 上一篇下一篇 */
            $pre_article = $this->_article_mod->get('article_id<' . $article_id . ' AND code = "" AND if_show=1  AND store_id=0 ORDER BY article_id DESC limit 1');
            $pre_article && $pre_article['target'] = $pre_article['link'] ? '_blank' : '_self';
            $next_article = $this->_article_mod->get('article_id>' . $article_id . ' AND code = "" AND if_show=1  AND store_id=0 ORDER BY article_id ASC limit 1');
            $next_article && $next_article['target'] = $next_article['link'] ? '_blank' : '_self';
            if ($article)
            {
                $cate_id = $article['cate_id'];
                /* 取得当前分类及子孙分类cate_id */
                $cate_ids = $this->_acategory_mod->get_descendant($cate_id);
            }
            else
            {
                $this->show_warning('no_such_article');
                return;
            }
        }
        else
        {
            $this->show_warning('no_such_article');
            return;
        }

        $this->_cate_ids = $cate_ids;
        /* 当前位置 */
        $curlocal = $this->_get_article_curlocal($cate_id);
        $curlocal[] =array('text' => Lang::get('content'));
        $this->_curlocal($curlocal);
        /*文章分类*/
        $acategories = $this->_get_acategory($cate_id);
        /* 新文章 */
        $new = $this->_get_article('new');
        $new_articles = $new['articles'];
        $this->assign('article', $article);
        $this->assign('pre_article', $pre_article);
        $this->assign('next_article', $next_article);
        $this->assign('new_articles', $new_articles);
        $this->assign('acategories', $acategories);

        $this->_config_seo('title', $article['title'] . ' - ' . Conf::get('site_title'));
        $this->display('article.view.html');
    }

    function system()
    {
        $code = empty($_GET['code']) ? '' : trim($_GET['code']);
        if (!$code)
        {
            $this->show_warning('no_such_article');
            return;
        }
        $article = $this->_article_mod->get("code='" . $code . "'");
        if (!$article)
        {
            $this->show_warning('no_such_article');
            return;
        }
        if ($article['link']){ //外链文章跳转
                header("HTTP/1.1 301 Moved Permanently");
                header('location:'.$article['link']);
                return;
            }

        /*当前位置*/
        $curlocal[] =array('text' => $article['title']);
        $this->_curlocal($curlocal);
        /*文章分类*/
        $acategories = $this->_get_acategory('');
        /* 新文章 */
        $new = $this->_get_article('new');
        $new_articles = $new['articles'];
        $this->assign('acategories', $acategories);
        $this->assign('new_articles', $new_articles);
        $this->assign('article', $article);

        $this->_config_seo('title', $article['title'] . ' - ' . Conf::get('site_title'));
        $this->display('article.view.html');

    }

    function _get_article_curlocal($cate_id)
    {
        $parents = array();
        if ($cate_id)
        {
            $acategory_mod = &m('acategory');
            $acategory_mod->get_parents($parents, $cate_id);
        }
        foreach ($parents as $category)
        {
            $curlocal[] = array('text' => $category['cate_name'], 'ACC' => $category['code'], 'url' => 'index.php?app=article&amp;cate_id=' . $category['cate_id']);
        }
        return $curlocal;
    }
    function _get_acategory($cate_id)
    {
        $acategories = $this->_acategory_mod->get_list($cate_id);
        if ($acategories){
            unset($acategories[$this->_ACC[ACC_SYSTEM]]);
            return $acategories;
        }
        else
        {
            $parent = $this->_acategory_mod->get($cate_id);
            if (isset($parent['parent_id']))
            {
                return $this->_get_acategory($parent['parent_id']);
            }
        }
    }
    function _get_article($type='')
    {
        $conditions = '';
        $per = '';
        switch ($type)
        {
            case 'new' : $sort_order = 'add_time DESC,sort_order ASC';
            $per=5;
            break;
            case 'all' : $sort_order = 'sort_order ASC,add_time DESC';
            $per=10;
            break;
        }
        
        $page = $this->_get_page($per);   //获取分页信息
        !empty($this->_cate_ids)&& $conditions = ' AND cate_id ' . db_create_in($this->_cate_ids);
        $articles = $this->_article_mod->find(array(
            'conditions'  => 'if_show=1 AND store_id=0 AND code = ""' . $conditions,
            'limit'   => $page['limit'],
            'order'   => $sort_order,
            'count'   => true   //允许统计
        )); //找出所有符合条件的文章
        
        $page['item_count'] = $this->_article_mod->getCount();
        foreach ($articles as $key => $article)
        {
            $articles[$key]['target'] = $article[link] ? '_blank' : '_self';
        }
        return array('page'=>$page, 'articles'=>$articles);
    }
}

?>
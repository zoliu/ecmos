<?php

/**
 *    网站地图更新控制器
 *
 *    @author    Garbin
 *    @usage    none
 */
class SitemapApp extends FrontendApp
{
    function __construct()
    {
        $this->SitemapApp();
    }
    function SitemapApp()
    {
        parent::__construct();
        $this->_google_sitemmap_file = ROOT_PATH . '/data/google_sitemmap.xml';
    }

    function index()
    {
        if (!Conf::get('sitemap_enabled'))
        {
            return;
        }
        $from = empty($_GET['from']) ? 'google' : trim($_GET['from']);
        switch ($from)
        {
            case 'google':
                $this->_output_google_sitemap();
            break;
        }
    }

    /**
     *    输出Google sitemap
     *
     *    @author    Garbin
     *    @return    void
     */
    function _output_google_sitemap()
    {
        header("Content-type: application/xml");
        echo $this->_get_google_sitemap();
    }

    /**
     *    获取Google sitemap
     *
     *    @author    Garbin
     *    @return    string
     */
    function _get_google_sitemap()
    {
        $sitemap = "";
        if ($this->_google_sitemap_expired())
        {
            /* 已过期，重新生成 */

            /* 获取有更新的项目 */
            $updated_items = $this->_get_updated_items($this->_get_google_sitemap_lastupdate());

            /* 重建sitemap */
            $sitemap = $this->_build_google_sitemap($updated_items);

            /* 写入文件 */
            $this->_write_google_sitemap($sitemap);
        }
        else
        {
            /* 直接返回旧的sitemap */
            $sitemap = file_get_contents($this->_google_sitemmap_file);
        }

        return $sitemap;
    }

    /**
     *    判断Google sitemap是否过期
     *
     *    @author    Garbin
     *    @return    boolean
     */
    function _google_sitemap_expired()
    {
        if (!is_file($this->_google_sitemmap_file))
        {
            return true;
        }
        $frequency = Conf::get('sitemap_frequency') * 3600;
        $filemtime = $this->_get_google_sitemap_lastupdate();

        return (time() >= $filemtime + $frequency);
    }

    /**
     *    获取上次更新日期
     *
     *    @author    Garbin
     *    @return    int
     */
    function _get_google_sitemap_lastupdate()
    {
        return is_file($this->_google_sitemmap_file) ? filemtime($this->_google_sitemmap_file) : 0;
    }

    /**
     *    获取已更新的项目
     *
     *    @author    Garbin
     *    @return    array
     */
    function _get_updated_items($timeline = 0)
    {
        $timeline && $timeline -= date('Z');
        $limit = 5000;
        $result = array();
        /* 更新的店铺 */
        $model_store =& m('store');
        $updated_store = $model_store->find(array(
            'fields'    => 'store_id, add_time',
            'conditions' => "add_time >= {$timeline} AND state=" . STORE_OPEN,
            'limit'     => "0, {$limit}",
        ));

        if (!empty($updated_store))
        {
            foreach ($updated_store as $_store_id => $_v)
            {
                $result[] = array(
                    'url'       => SITE_URL . '/index.php?app=store&id=' . $_store_id,
                    'lastmod'   => date("Y-m-d", $_v['add_time']),
                    'changefreq'=> 'daily',
                    'priority'  => '1',
                );
            }
        }
        /* 更新的文章 */
        $model_article =& m('article');
        $updated_article = $model_article->find(array(
            'fields'    => 'article_id, add_time',
            'conditions'=> "add_time >= {$timeline} AND if_show=1",
            'limit'     => "0, {$limit}",
        ));
        if (!empty($updated_article))
        {
            foreach ($updated_article as $_article_id => $_v)
            {
                $result[] = array(
                    'url'       => SITE_URL . '/index.php?app=article&act=view&article_id=' . $_article_id,
                    'lastmod'   => date("Y-m-d", $_v['add_time']),
                    'changefreq'=> 'daily',
                    'priority'  => '0.8',
                );
            }
        }

        /* 更新的商品 */
        $model_goods =& m('goods');
        $updated_goods = $model_goods->find(array(
            'fields'        => 'goods_id, last_update',
            'conditions'    => "last_update >= {$timeline} AND if_show=1 AND closed=0",
            'limit'         => "0, {$limit}",
        ));
        if (!empty($updated_goods))
        {
            foreach ($updated_goods as $_goods_id => $_v)
            {
                $result[] = array(
                    'url'       => SITE_URL . '/index.php?app=goods&id=' . $_goods_id,
                    'lastmod'   => date("Y-m-d", $_v['last_update']),
                    'changefreq'=> 'daily',
                    'priority'  => '0.8',
                );
            }
        }

        return $result;
    }

    /**
     *    生成Google sitemap
     *
     *    @author    Garbin
     *    @param     array $items
     *    @return    string
     */
    function _build_google_sitemap($items)
    {
        $sitemap = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\r\n";
        $sitemap .= "    <url>\r\n        <loc>" . htmlentities(SITE_URL, ENT_QUOTES) . "</loc>\r\n        <lastmod>" . date('Y-m-d', gmtime()) . "</lastmod>\r\n        <changefreq>always</changefreq>\r\n        <priority>1</priority>\r\n    </url>";
        if (!empty($items))
        {
            foreach ($items as $item)
            {
                $sitemap .= "\r\n    <url>\r\n        <loc>" . htmlentities($item['url'], ENT_QUOTES) . "</loc>\r\n        <lastmod>{$item['lastmod']}</lastmod>\r\n        <changefreq>{$item['changefreq']}</changefreq>\r\n        <priority>{$item['priority']}</priority>\r\n    </url>";
            }
        }
        $sitemap .= "\r\n</urlset>";

        return $sitemap;
    }

    /**
     *    写入Google sitemap文件
     *
     *    @author    Garbin
     *    @param     string $sitemap
     *    @return    void
     */
    function _write_google_sitemap($sitemap)
    {
        file_put_contents($this->_google_sitemmap_file, $sitemap);
    }
}

?>
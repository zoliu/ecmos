<?php

/**
 * ECMALL: google sitemap
 * ============================================================================
 * 版权所有 (C) 2005-2008 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.shopex.cn
 * -------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Id: google_sitemap.lib.php 6206 2009-01-03 02:33:47Z yelin $
 */

if (!defined('IN_ECM'))
{
    trigger_error('Hacking attempt', E_USER_ERROR);
}

class google_sitemap
{
    var $header = "<\x3Fxml version=\"1.0\" encoding=\"UTF-8\"\x3F>\n\t<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";
    var $charset = "UTF-8";
    var $footer = "\t</urlset>\n";
    var $items = array();

    /**
     * 增加一个新的子项
     *@access   public
     *@param    google_sitemap  item    $new_item
     */
    function add_item($new_item)
    {
        $this->items[] = $new_item;
    }

    /**
     * 生成XML文档
     *@access    public
     *@param     string  $file_name  如果提供了文件名则生成文件，否则返回字符串.
     *@return [void|string]
     */
    function build( $file_name = null )
    {
        $map = $this->header . "\n";

        foreach ($this->items AS $item)
        {
            $item->loc = htmlentities($item->loc, ENT_QUOTES);
            $map .= "\t\t<url>\n\t\t\t<loc>$item->loc</loc>\n";

            // lastmod
            if ( !empty( $item->lastmod ) )
                $map .= "\t\t\t<lastmod>$item->lastmod</lastmod>\n";

            // changefreq
            if ( !empty( $item->changefreq ) )
                $map .= "\t\t\t<changefreq>$item->changefreq</changefreq>\n";

            // priority
            if ( !empty( $item->priority ) )
                $map .= "\t\t\t<priority>$item->priority</priority>\n";

            $map .= "\t\t</url>\n\n";
        }

        $map .= $this->footer . "\n";

        if (!is_null($file_name))
        {
            return _at('file_put_contents',$file_name, $map, LOCK_EX);
        }
        else
        {
            return $map;
        }
    }

}

class google_sitemap_item
{
    /**
     *@access   public
     *@param    string  $loc        位置
     *@param    string  $lastmod    日期格式 YYYY-MM-DD
     *@param    string  $changefreq 更新频率的单位 (always, hourly, daily, weekly, monthly, yearly, never)
     *@param    string  $priority   优先级 0-1
     */
    function google_sitemap_item($loc, $lastmod = '', $changefreq = '', $priority = '')
    {
        $this->loc = $loc;
        $this->lastmod = $lastmod;
        $this->changefreq = $changefreq;
        $this->priority = $priority;
    }
}

?>
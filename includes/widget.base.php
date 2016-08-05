<?php

/**
 *    小挂件基础类
 *
 *    @author    Garbin
 *    @usage    none
 */
class BaseWidget extends Object
{
    var $options = null;    //显示选项
    var $_name   = null;    //挂件标识
    var $id      = null;     //在页面中的唯一标识
    var $widget_root = '';  //HTTP根目录
    var $widget_path = '';  //物理路径
    var $_ttl    = 3600;    //缓存时间
    function __construct($id, $options = array())
    {
        $this->BaseWidget($id, $options);
    }
    function BaseWidget($id, $options = array())
    {
        $this->id = $id;
        $this->widget_path = ROOT_PATH . '/external/widgets/' . $this->_name;
        $this->widget_root = SITE_URL . '/external/widgets/' . $this->_name;

        /* 初始化视图配置 */
        $this->_view =& _widget_view();
        $this->_view->lib_base = dirname(site_url()) . '/includes/libraries/javascript';
        $this->set_options($options);
        $this->assign('widget_root', $this->widget_root);
        $this->assign('id', $this->id);
        $this->assign('name', $this->_name);
    }

    /**
     *    设置选项
     *
     *    @author    Garbin
     *    @param     array
     *    @return    void
     */
    function set_options($options)
    {
        $this->options = $options;
        $this->assign('options', $this->options);
    }

    /**
     *    获取指定模板的数据
     *
     *    @author    Garbin
     *    @param    none
     *    @return    void
     */
    function fetch($tpl)
    {
        return $this->_view->fetch('str:' . $this->_get_template($tpl));
    }

    /**
     *    给视图传送数据
     *
     *    @author    Garbin
     *    @param     mixed $k
     *    @param     mixed $v
     *    @return    void
     */
    function assign($k, $v = null)
    {
        if (is_array($k))
        {
            $args  = func_get_args();
            foreach ($args as $arg)     //遍历参数
            {
                foreach ($arg as $key => $value)    //遍历数据并传给视图
                {
                    $this->_view->assign($key, $value);
                }
            }
        }
        else
        {
            $this->_view->assign($k, $v);
        }
    }

    /**
     *    取模板
     *
     *    @author    Garbin
     *    @param     string $tpl
     *    @return    string
     */
    function _get_template($tpl)
    {
        return file_get_contents($this->widget_path . "/{$tpl}.html");
    }

    /**
     *    取数据
     *
     *    @author    Garbin
     *    @return    array
     */
    function _get_data()
    {
        #code 可取到所有数据

        return array();
    }

    /**
     *    获取标准的挂件HTML
     *
     *    @author    Garbin
     *    @param     string $html
     *    @return    string
     */
    function _wrap_contents($html)
    {
        return "\r\n<div id=\"{$this->id}\" name=\"{$this->_name}\" widget_type=\"widget\" class=\"widget\">\r\n" .
               $html .
               "\r\n</div>\r\n";
    }

    /**
     *    将取得的数据按模板的样式输出
     *
     *    @author    Garbin
     *    @return    string
     */
    function get_contents()
    {
        /* 获取挂件数据 */
        $this->assign('widget_data', $this->_get_data());

        /*可能有问题*/
        $this->assign('options', $this->options);
        $this->assign('widget_root', $this->widget_root);

        return $this->_wrap_contents($this->fetch('widget'));
    }

    /**
     *    显示
     *
     *    @author    Garbin
     *    @param    none
     *    @return    void
     */
    function display()
    {
        echo $this->get_contents();
    }

    /**
     *    获取配置表单
     *
     *    @author    Garbin
     *    @return    string
     */
    function get_config_form()
    {
        $this->get_config_datasrc();
        return $this->fetch('config');
    }

    /**
     * 传递配置页面需要的一些变量
     */
    function get_config_datasrc()
    {
        // $this->assign('var', $var);
    }


    /**
     *    显示配置表单
     *
     *    @author    Garbin
     *    @return    void
     */
    function display_config()
    {
        echo $this->get_config_form();
    }

    /**
     *    处理配置项
     *
     *    @author    Garbin
     *    @param     array $input
     *    @return    array
     */
    function parse_config($input)
    {
        return $input;
    }

    /* 取得推荐类型 */
    function _get_recommends()
    {
        $recom_mod =& bm('recommend', array('_store_id' => 0));
        $recommends = $recom_mod->get_options();
        $recommends[REC_NEW] = Lang::get('recommend_new');

        return $recommends;
    }

    /* 取得分类列表 */
    function _get_gcategory_options($layer = 0)
    {
        $gcategory_mod =& bm('gcategory', array('_store_id' => 0));
        $gcategories = $gcategory_mod->get_list();

        import('tree.lib');
        $tree = new Tree();
        $tree->setTree($gcategories, 'cate_id', 'parent_id', 'cate_name');

        return $tree->getOptions($layer);
    }

    /* 取缓存id */
    function _get_cache_id()
    {
        $config = array(
            'widget_name' => $this->_name,
        );
        if ($this->options)
        {
            $config = array_merge($config, $this->options);
        }

        return md5('widget.' . var_export($config, true));
    }
}

/**
 *    获取挂件视图处理类
 *
 *    @author    Garbin
 *    @return    void
 */
function &_widget_view()
{
    //return v(true);
    static $widget_view = null;
    if ($widget_view === null)
    {
        $widget_view = v(true);
    }

    return $widget_view;
}

/**
 *    获取挂件实例
 *
 *    @author    Garbin
 *    @param     string $id
 *    @param     string $name
 *    @param     array  $options
 *    @return    Object Widget
 */
function &widget($id, $name, $options = array())
{
    static $widgets = null;
    if (!isset($widgets[$id]))
    {
        $widget_class_path = ROOT_PATH . '/external/widgets/' . $name . '/main.widget.php';
        $widget_class_name = ucfirst($name) . 'Widget';
        include_once($widget_class_path);
        $widgets[$id] = new $widget_class_name($id, $options);
    }

    return $widgets[$id];
}

/**
 *    获取指定风格，指定页面的挂件的配置信息
 *
 *    @author    Garbin
 *    @param     string $template_name
 *    @param     string $page
 *    @return    array
 */
function get_widget_config($template_name, $page)
{
    static $widgets = null;
    $key = $template_name . '_' . $page;
    if (!isset($widgets[$key]))
    {
        $tmp = array('widgets' => array(), 'config' => array());
        $config_file = ROOT_PATH . '/data/page_config/' . $template_name . '.' . $page . '.config.php';
        if (is_file($config_file))
        {
            /* 有配置文件，则从配置文件中取 */
            $tmp = include_once($config_file);
        }

        $widgets[$key] = $tmp;
    }

    return $widgets[$key];
}
?>
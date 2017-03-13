<?php

/**
 * ECMall: 模版类
 * ============================================================================
 * 版权所有 (C) 2005-2008 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.shopex.cn
 * -------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Id: template.php 12151 2010-12-02 03:52:36Z huibiaoli $
 */

class ecsTemplate
{
    var $template_dir   = '';
    var $cache_dir      = '';
    var $compile_dir    = '';
    var $cache_lifetime = 3600; // 缓存更新时间, 默认 3600 秒
    var $direct_output  = false;
    var $caching        = false;
    var $template       = array();
    var $force_compile  = false;
    var $appoint_cache_id = false;
    var $gzip           = false;

    var $_var           = array();
    var $_echash        = '554fcae493e564ee0dc75bdf2ebf94ca';
    var $_foreach       = array();
    var $_current_file  = '';
    var $_expires       = 0;
    var $_errorlevel    = 0;
    var $_nowtime       = null;
    var $_checkfile     = true;
    var $_foreachmark   = '';
    var $_seterror      = 0;

    var $_temp_key      = array();  // 临时存放 foreach 里 key 的数组
    var $_temp_val      = array();  // 临时存放 foreach 里 item 的数组

    var $options        = null;

    function __construct()
    {
        $this->ecsTemplate();
    }

    function ecsTemplate()
    {
        $this->_errorlevel = error_reporting();
        $this->_nowtime    = time();
    }

    /**
     * 注册变量
     *
     * @access  public
     * @param   mix      $tpl_var
     * @param   mix      $value
     *
     * @return  void
     */
    function assign($tpl_var, $value = '')
    {
        if (is_array($tpl_var))
        {
            foreach ($tpl_var AS $key => $val)
            {
                if ($key != '')
                {
                    $this->_var[$key] = $val;
                }
            }
        }
        else
        {
            if ($tpl_var != '')
            {
                $this->_var[$tpl_var] = $value;
            }
        }
    }

    /**
     * 显示页面函数
     *
     * @access  public
     * @param   string      $filename
     * @param   sting      $cache_id
     *
     * @return  void
     */
    function display($filename, $cache_id = '')
    {
        $this->_seterror++;

        restore_error_handler();
        error_reporting(E_ALL ^ E_NOTICE);

        $this->_checkfile = false;
        $out = $this->fetch($filename, $cache_id);

        if (strpos($out, $this->_echash) !== false)
        {
            $k = explode($this->_echash, $out);
            foreach ($k AS $key => $val)
            {
                if (($key % 2) == 1)
                {
                    $k[$key] = $this->insert_mod($val);
                }
            }
            $out = implode('', $k);
        }

        error_reporting($this->_errorlevel);
        restore_error_handler();
        $this->_seterror--;

        /*
        //GZIP已在控制器中控制
        if ($this->gzip)
        {
            ob_start('ob_gzhandler');
        }
        else
        {
            ob_start();
        }
        */
        echo $out;
    }

    /**
     * 显示缓存数据
     *
     */
    function display_cache()
    {
        restore_error_handler();
        error_reporting(E_ALL ^ E_NOTICE);
        $out = $this->template_out;

        if (strpos($out, $this->_echash) !== false)
        {
            $k = explode($this->_echash, $out);
            foreach ($k AS $key => $val)
            {
                if (($key % 2) == 1)
                {
                    $k[$key] = $this->insert_mod($val);
                }
            }
            $out = implode('', $k);
        }
        error_reporting($this->_errorlevel);
        restore_error_handler();

        /*
        //GZIP已在控制器中控制
        if ($this->gzip)
        {
            ob_start('ob_gzhandler');
        }
        else
        {
            ob_start();
        }
        */

        echo $out;
    }

    /**
     * 处理模板文件
     *
     * @access  public
     * @param   string      $filename
     * @param   sting       $cache_id
     * @param   sting       $target_dir
     *
     * @return  sring
     */
    function fetch($filename, $cache_id = '')
    {
        if (!$this->_seterror)
        {
            error_reporting(E_ALL ^ E_NOTICE);
        }
        $this->_seterror++;

        if (strncmp($filename,'str:', 4) == 0)
        {
            $out = $this->_eval($this->fetch_str(substr($filename, 4)));
        }
        else
        {
            if ($this->_checkfile)
            {
                if (!is_file($filename))
                {
                    $filename = $this->template_dir . '/' . $filename;
                }
            }
            else
            {
                $filename = $this->template_dir . '/' . $filename;
            }

            if ($this->direct_output)
            {
                $this->_current_file = $filename;
                $out = $this->_eval($this->fetch_str(file_get_contents($filename)));
            }
            else
            {

                if ($cache_id && $this->caching)
                {
                    $out = $this->template_out;
                }
                else
                {
                    if (!in_array($filename, $this->template))
                    {
                        $this->template[] = $filename;
                    }

                    $out = $this->make_compiled($filename);

                    if ($cache_id)
                    {
                        if ($this->appoint_cache_id)
                        {
                            $cachename = $cache_id;
                        }
                        else
                        {
                            $cachename = basename($filename, strrchr($filename, '.')) . '_' . $cache_id;
                        }
                        $data = serialize(array('template' => $this->template, 'expires' => $this->_nowtime + $this->cache_lifetime, 'maketime' => $this->_nowtime));
                        $out = str_replace("\r", '', $out);

                        while (strpos($out, "\n\n") !== false)
                        {
                            $out = str_replace("\n\n", "\n", $out);
                        }

                        if (file_put_contents($this->cache_dir . '/' . $cachename . '.php', '<?php exit;?>' . $data . $out, LOCK_EX) === false)
                        {
                            trigger_error('can\'t write:' . $this->cache_dir . '/' . $cachename . '.php');
                        }
                        $this->template = array();
                    }
                }
            }
        }

        $this->_seterror--;
        if (!$this->_seterror)
        {
            error_reporting($this->_errorlevel);
        }

        return $out; // 返回html数据
    }

    /**
     * 编译模板函数
     *
     * @access  public
     * @param   string      $filename
     *
     * @return  sring        编译后文件地址
     */
    function make_compiled($filename)
    {
        $name   = $this->compile_dir;

        $name .= '/' . basename($filename) . '.php';
        $exists = is_file($name);

        if ($this->_expires)
        {
            $expires = $this->_expires - $this->cache_lifetime;
        }
        else
        {
            if ($exists)
            {
                $filestat = @stat($name);
                $expires  = $filestat['mtime'];
            }
            else
            {
                $expires = 0;
            }
        }

        $filestat = @stat($filename);
        if ($filestat['mtime'] <= $expires && !$this->force_compile)
        {
            if (is_file($name))
            {
                $source = $this->_require($name);

                if ($source == '')
                {
                    $expires = 0;
                }
            }
            else
            {
                $source = '';
                $expires = 0;
            }
        }

        if ($this->force_compile || $filestat['mtime'] > $expires || 1)
        {
            $this->_current_file = $filename;
            $content = file_get_contents($filename);

            $source = $this->fetch_str($content);
            if (!file_exists(dirname($name)))
            {
                ecm_mkdir(dirname($name));
            }
            if (file_put_contents($name, $source, LOCK_EX) === false)
            {
                trigger_error('can\'t write:' . $name);
            }

            $source = $this->_eval($source);
        }

        return $source;
    }

    /**
     * 处理字符串函数
     *
     * @access  public
     * @param   string     $source
     *
     * @return  sring
     */
    function fetch_str($source)
    {
        if (!defined('IS_BACKEND'))
        {
            $source = $this->smarty_prefilter_preCompile($source);
        }
		
		if(PHP_VERSION < 5.5) {
			return preg_replace("/{([^\}\{\n]*)}/e", "\$this->select('\\1');", $source); // for < PHP5.3
		}
		else {
			return preg_replace_callback("/{([^\}\{\n]*)}/", "self::fetch_str_preg_callback", $source); // for >=PHP5.5.X
		}
		
    }
	function fetch_str_preg_callback($matches)
	{
		return $this->select($matches[1]);
	}

    /**
     * 判断是否缓存
     *
     * @access  public
     * @param   string     $filename
     * @param   sting      $cache_id
     *
     * @return  bool
     */
    function is_cached($filename, $cache_id = '')
    {
        if ($this->appoint_cache_id)
        {
            $cachename = $cache_id;
        }
        else
        {
            $cachename = basename($filename, strrchr($filename, '.')) . '_' . $cache_id;
        }

        if (($this->caching == true || $this->appoint_cache_id) && $this->direct_output == false)
        {
            if (is_file($this->cache_dir . '/' . $cachename . '.php') && ($data = @file_get_contents($this->cache_dir . '/' . $cachename . '.php')))
            {
                $data = substr($data, 13);
                $pos  = strpos($data, '<');
                $paradata = substr($data, 0, $pos);
                $para     = @unserialize($paradata);
                if ($para === false || $this->_nowtime > $para['expires'])
                {
                    $this->caching = false;

                    return false;
                }
                $this->_expires = $para['expires'];

                $this->template_out = substr($data, $pos);
                /* do not check every item to save time , modify by wj */
                /*
                foreach ($para['template'] AS $val)
                {
                    $fp = fopen($val, 'rb');
                    $stat = @fstat($fp);
                    if ($para['maketime'] < $stat['mtime'])
                    {
                        $this->caching = false;

                        return false;
                    }
                }
                */
            }
            else
            {
                $this->caching = false;

                return false;
            }

            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * 处理{}标签
     *
     * @author  wj
     * @param   string      $tag
     *
     * @return  sring
     */
    function select($tag)
    {
        $tag = stripslashes(trim($tag));

        if (empty($tag))
        {
            return '{}';
        }
        elseif ($tag{0} == '*' && substr($tag, -1) == '*') // 注释部分
        {
            return '';
        }
        elseif ($tag{0} == '$') // 变量
        {
            if ((strncmp($tag, '$lang.', 6) === 0) && strrpos($tag, '$') === 0)
            {
                return $this->get_lang(substr($tag, 1));
            }
            else
            {
                return '<?php echo ' . $this->get_val(substr($tag, 1)) . '; ?>';
            }
        }
        elseif ($tag{0} == '/') // 结束 tag
        {
            switch (substr($tag, 1))
            {
                case 'if':
                    return '<?php endif; ?>';
                    break;

                case 'foreach':
                    if ($this->_foreachmark == 'foreachelse')
                    {
                        $output = '<?php endif; unset($_from); ?>';
                    }
                    else
                    {
                        $output = '<?php endforeach; endif; unset($_from); ?>';
                    }
                    $output .= "<?php \$this->pop_vars();; ?>";

                    return $output;
                    break;

                case 'literal':
                    return '';
                    break;

                default:
                    return '{'. $tag .'}';
                    break;
            }
        }
        else
        {
            $tag_all = explode(' ', $tag);
            $tag_sel = array_shift($tag_all);
            switch ($tag_sel)
            {
                case 'if':

                    return $this->_compile_if_tag(substr($tag, 3));
                    break;

                case 'else':

                    return '<?php else: ?>';
                    break;

                case 'elseif':

                    return $this->_compile_if_tag(substr($tag, 7), true);
                    break;

                case 'foreachelse':
                    $this->_foreachmark = 'foreachelse';

                    return '<?php endforeach; else: ?>';
                    break;

                case 'foreach':
                    $this->_foreachmark = 'foreach';

                    return $this->_compile_foreach_start(substr($tag, 8));
                    break;

                case 'assign':
                    $t = $this->get_para(substr($tag, 7),0);

                    if ($t['value']{0} == '$')
                    {
                        /* 如果传进来的值是变量，就不用用引号 */
                        $tmp = '$this->assign(\'' . $t['var'] . '\',' . $t['value'] . ');';
                    }
                    else
                    {
                        $tmp = '$this->assign(\'' . $t['var'] . '\',\'' . addcslashes($t['value'], "'") . '\');';
                    }
                    // $tmp = $this->assign($t['var'], $t['value']);

				return '<?php ' . $tmp . ' ?>';
				break;
			case 'ds':

				$t = $this->get_para(substr($tag, 3), 1);
				if (isset($t['name']) && !empty($t['name'])) {
					$this->assign($t['name'] . "_ds_var", $t);
					$str = '
                    $result=$this->dataSource("' . $t['name'] . '",$this->_var["' . $t['name'] . '_ds_var"]);
					if (is_array($result) && count($result) > 0) {
						foreach ($result as $k => $v) {
							if(!empty($v)){
								// print_r($v);
								$this->assign($k, $v);
							}

						}
					}
                    ';
				}
				return '<?php ' . $str . ' ?>';
				break;

			case 'include':
				$t = $this->get_para(substr($tag, 8), 0);

                    return '<?php echo $this->fetch(' . "'$t[file]'" . '); ?>';
                    break;
                case 'res':
                    $t = $this->get_para(substr($tag, 4), 0);

                    return '<?php echo $this->res_base . "/" . ' . "'$t[file]'" . '; ?>';
                    break;
                case 'lib':
                    $t = $this->get_para(substr($tag, 4), 0);

                    return '<?php echo $this->lib_base . "/" . ' . "'$t[file]'" . '; ?>';
                    break;
                case 'script':
                    $t = $this->get_para(substr($tag, 7), 0);
                    return $this->java_script($t);
                    break;

                case 'style':
                    $t = $this->get_para(substr($tag, 4), 0);
                    return $this->style($t, $this->template_dir);
                    break;

                case 'create_pages':
                    $t = $this->get_para(substr($tag, 13), 0);

                    return '<?php echo $this->smarty_create_pages(' . $this->make_array($t) . '); ?>';
                    break;

                case 'insert' :
                    if (strrpos($tag, $this->_echash) > 1)
                    {
                        list($tag, $_template) = explode($this->_echash, $tag);
                    }
                    $t = $this->get_para(substr($tag, 7), false);
                    if (!empty($_template))
                    {
                        $t['_template'] = str_replace("\r", '', $this->fetch_str(base64_decode($_template)));
                    }
					
					if(PHP_VERSION < 5.5) {
                    	$out = "<?php \n" . '$k = ' . preg_replace("/(\'\\$[^,]+)/e" , "stripslashes(trim('\\1','\''));", var_export($t, true)) . ";\n";
					} else {
						$out = "<?php \n" . '$k = ' . preg_replace_callback("/(\'\\$[^,]+)/" , "self::select_insert_preg_callback", var_export($t, true)) . ";\n";
					}
					
                    $out .= 'echo $this->_echash . $k[\'name\'] . \'|\' . serialize($k) . $this->_echash;' . "\n?>";

                    return $out;
                    break;

                case 'literal':
                    return '';
                    break;

                case 'cycle' :
                    //$t = $this->get_para(substr($tag, 6), 0); //remark by wj because of unfit this tag
                    $cycle_para = explode("=", trim(substr($tag, 6)), 2);
                    if (count($cycle_para) > 1)
                    {
                        $t = array(trim($cycle_para[0])=>trim($cycle_para[1], "'"));

                        return '<?php echo $this->cycle(' . $this->make_array($t) . '); ?>';
                    }
                    else
                    {
                        return "Eorror: $tag";
                    }
                    break;

                case 'html_options':
                    $t = $this->get_para(substr($tag, 13), 0);
                    return '<?php echo $this->html_options(' . $this->make_array($t) . '); ?>';
                    break;
                case 'widgets':
                    $t = $this->get_para(substr($tag, 8), 0);

                    return '<?php $this->display_widgets(' . $this->make_array($t) . '); ?>';
                    break;

                case 'html_radios':
                    $t = $this->get_para(substr($tag, 12), 0);

                    return '<?php echo $this->html_radios(' . $this->make_array($t) . '); ?>';
                    break;

                case 'html_checkbox':
                    $t = $this->get_para(substr($tag, 14), 0);

                    return '<?php echo $this->html_checkbox(' . $this->make_array($t) . '); ?>';
                    break;

                case 'img_yesorno':
                    $t = $this->get_para(substr($tag, 11), 0);
                    return '<?php echo $this->html_img_yesorno(' . $this->make_array($t) . '); ?>';
                    break;

                case 'page_links':
                    $t = $this->get_para(substr($tag, 10), 0);
                    return '<?php echo $this->html_page_links(' . $this->make_array($t) . '); ?>';
                    break;

                case 'page_selector':
                    $t = $this->get_para(substr($tag, 13), 0);
                    return '<?php echo $this->html_page_selector(' . $this->make_array($t) . '); ?>';
                    break;

                case 'page_simple':
                    $t = $this->get_para(substr($tag, 11), 0);
                    return '<?php echo $this->html_page_simple(' . $this->make_array($t) . '); ?>';
                    break;

                case 'sort_link':
                    $t = $this->get_para(substr($tag, 9), 0);
                    return '<?php echo $this->html_sort_link(' . $this->make_array($t) . '); ?>';
                    break;

                case 'image':
                    $t = $this->get_para(substr($tag, 3), 0);

                    return '<?php echo $this->image(' . $this->make_array($t) . '); ?>';
                    break;

                case 'url':
                    $str = str_replace('index.php?', '', substr($tag, 4));
                    $str = str_replace('&amp;', '&', $str);
                    $tmp = explode('&', $str);
                    $arr = array();

                    foreach ($tmp AS $key=>$val)
                    {
                        $a = explode('=', $val);
                        if ((substr($a[1], 0, 1) == '$'))
                        {
                            $arr[] = "{$a[0]}=' . ". $this->get_val(substr($a[1], 1)) . ". '";
                        }
                        else
                        {
                            $arr[] = "{$a[0]}={$a[1]}";
                        }
                    }

                    return '<?php echo url(\'' . implode('&', $arr) . '\'); ?>';
                    break;

                case 'ecjoin':
                    $t = $this->get_para(substr($tag, 6), 0);
                    if (isset($t['glue']))
                    {
                        $glue = $t['glue'];
                        unset($t['glue']);
                    }
                    else
                    {
                        $glue = ", &nbsp;";
                    }
                    $str = "\$tmp = array();";
                    foreach ($t as $value)
                    {
                        $str .= "if (!empty(" . $value . ")) array_push(\$tmp, " . $value . ");";
                    }
                    return "<?php " . $str . " echo join('$glue', \$tmp); ?>";
                    break;
                case 'sprintf':
                    $t = $this->get_para(substr($tag, 7), 0);
                    $str = 'return ' . $this->make_array($t) . ';';
                    $arr = eval($str);

                    return '<?php echo ' . $this->_sprintf($arr) .'; ?>';
                    break;

                default:
                    return '{' . $tag . '}';
                    break;
            }
        }
    }
	//360cd.cn ds
	function dataSource($name, $params = array()) {
		if (!class_exists('baseDs')) {

			include ROOT_PATH . '/includes/ds.base.php';
		}
		$name = strtolower($name);

		$file_path = ROOT_PATH . "/external/ds/" . $name . ".ds.php";

		if (file_exists($file_path)) {

			$name = ucfirst($name);

			$class_name = $name . 'Ds';
			if (!class_exists($class_name)) {
				include $file_path;
			}
			$ds = new $class_name();
			$ds->init($params);
			return $ds->getVar();
		}
	}
	//360cd.cn ds
	function select_insert_preg_callback($matches)
	{
		return stripslashes(trim($matches[1],''));
	}

    /**
     * 处理smarty标签中的变量标签
     *
     * @author  wj
     * @param   string   $val 标签
     *
     * @return  bool
     */
    function get_val($val)
    {
        if (strrpos($val, '[') !== false)
        {
			if(PHP_VERSION < 5.5) {
            	$val = preg_replace("/\[([^\[\]]*)\]/eis", "'.'.str_replace('$','\$','\\1')", $val);
			} else {
				$val = preg_replace("/\[([^\[\]]*)\]/eis", "'.'.str_replace('$','\$','\\1')", $val); // 发现这个不受影响,日后发现有影响再调整
			}
			
        }

        if (strrpos($val, '|') !== false)
        {
            $moddb = explode('|', $val);
            $val = array_shift($moddb);
        }

        if (empty($val))
        {
            return '';
        }

        if (strpos($val, '.$') !== false)
        {
            $all = explode('.$', $val);

            foreach ($all AS $key => $val)
            {
                $all[$key] = $key == 0 ? $this->make_var($val) : '['. $this->make_var($val) . ']';
            }
            $p = implode('', $all);
        }
        else
        {
            $p = $this->make_var($val);
        }

        if (!empty($moddb))
        {
            foreach ($moddb AS $key => $mod)
            {
                $s = explode(':', $mod);
                switch ($s[0])
                {
                    case 'escape':
                        $s[1] = trim($s[1], '"');
                        if ($s[1] == 'html')
                        {
                            $p = 'htmlspecialchars(' . $p . ')';
                        }
                        elseif ($s[1] == 'url')
                        {
                            $p = 'urlencode(' . $p . ')';
                        }
                        elseif ($s[1] == 'quotes')
                        {
                            $p = 'addslashes(' . $p . ')';
                        }
                        elseif ($s[1] == 'input')
                        {
                            $p = 'str_replace(\'"\', \'&quot;\',' . $p . ')';
                        }
                        elseif ($s[1] == 'editor')
                        {
                            $p = 'html_filter(' . $p . ')';
                        }
                        else
                        {
                            $p = 'htmlspecialchars(' . $p . ')';
                        }
                        $test1=true;
                        break;

                    case 'nl2br':
                        $p = 'nl2br(' . $p . ')';
                        break;

                    case 'default':
                        $s[1] = $s[1]{0} == '$' ?  $this->get_val(substr($s[1], 1)) : "'$s[1]'";
                        $p = '(' . $p . ' == \'\') ? ' . $s[1] . ' : ' . $p;
                        break;

                    case 'truncate':
                        $p = 'sub_str(' . $p . ",$s[1])";
                        break;

                    case 'strip_tags':
                        $p = 'strip_tags(' . $p . ')';
                        break;

                    case 'price':
                        $p = 'price_format(' . $p . ')';
                        break;

                    case 'date':
                        if (empty($s[1]))
                        {
                            /* 默认是简单格式 */
                            $date_format = Conf::get('time_format_simple');
                        }
                        else
                        {
                            if (in_array($s[1], array('simple', 'complete')))
                            {
                                /* 允许使用简单和完整格式，从配置项中取 */
                                $date_format = Conf::get("time_format_{$s[1]}");
                            }
                            else
                            {
                                /* 也可以自定义 */
                                unset($s[0]); //date格式中可能含有':',所以实际参数要还原下
                                $date_format = implode(':', $s);
                            }
                        }
                        $p = 'local_date("' . $date_format . '",' . $p . ')';
                        break;
                    case 'modifier':
                        if (function_exists($s[1]))
                        {
                            $p = 'call_user_func("' . $s[1] . '",' . $p . ')';
                        }

                        break;
                    default:
                        # code...
                        break;
                }
            }
        }

        return $p;
    }

    /**
     * 处理去掉$的字符串
     *
     * @access  public
     * @param   string     $val
     *
     * @return  bool
     */
    function make_var($val)
    {
        if (strrpos($val, '.') === false)
        {
            $p = '$this->_var[\'' . $val . '\']';
        }
        else
        {
            $t = explode('.', $val);
            $_var_name = array_shift($t);
            if ($_var_name == 'smarty')
            {
                 $p = $this->_compile_smarty_ref($t);
            }
            else
            {
                $p = '$this->_var[\'' . $_var_name . '\']';
            }
            foreach ($t AS $val)
            {
                $p.= '[\'' . $val . '\']';
            }
        }

        return $p;
    }

    /**
     * 处理insert外部函数/需要include运行的函数的调用数据
     *
     * @access  public
     * @param   string     $val
     * @param   int         $type
     *
     * @return  array
     */
    function get_para($val, $type = 1) // 处理insert外部函数/需要include运行的函数的调用数据
    {
        $pa = $this->str_trim($val);
        foreach ($pa AS $value)
        {
            if (strrpos($value, '='))
            {
                list($a, $b) = explode('=', str_replace(array(' ', '"', "'", '&quot;'), '', $value));
                if ($b{0} == '$')
                {
                    if ($type)
                    {
                        eval('$para[\'' . $a . '\']=' . $this->get_val(substr($b, 1)) . ';');
                    }
                    else
                    {
                        $para[$a] = $this->get_val(substr($b, 1));
                    }
                }
                else
                {
                    $para[$a] = $b;
                }
            }
        }

        return $para;
    }

    /**
     * 判断变量是否被注册并返回值
     *
     * @access  public
     * @param   string     $name
     *
     * @return  mix
     */
    function &get_template_vars($name = null)
    {
        if (empty($name))
        {
            return $this->_var;
        }
        elseif (!empty($this->_var[$name]))
        {
            return $this->_var[$name];
        }
        else
        {
            $_tmp = null;

            return $_tmp;
        }
    }

    /**
     * 处理if标签
     *
     * @access  public
     * @param   string     $tag_args
     * @param   bool       $elseif
     *
     * @return  string
     */
    function _compile_if_tag($tag_args, $elseif = false)
    {
        preg_match_all('/\-?\d+[\.\d]+|\'[^\'|\s]*\'|"[^"|\s]*"|[\$\w\.]+|!==|===|==|!=|<>|<<|>>|<=|>=|&&|\|\||\(|\)|,|\!|\^|=|&|<|>|~|\||\%|\+|\-|\/|\*|\@|\S/', $tag_args, $match);

        $tokens = $match[0];
        // make sure we have balanced parenthesis
        $token_count = array_count_values($tokens);
        if (!empty($token_count['(']) && $token_count['('] != $token_count[')'])
        {
            // $this->_syntax_error('unbalanced parenthesis in if statement', E_USER_ERROR, __FILE__, __LINE__);
        }

        for ($i = 0, $count = count($tokens); $i < $count; $i++)
        {
            $token = &$tokens[$i];
            switch (strtolower($token))
            {
                case 'eq':
                    $token = '==';
                    break;

                case 'ne':
                case 'neq':
                    $token = '!=';
                    break;

                case 'lt':
                    $token = '<';
                    break;

                case 'le':
                case 'lte':
                    $token = '<=';
                    break;

                case 'gt':
                    $token = '>';
                    break;

                case 'ge':
                case 'gte':
                    $token = '>=';
                    break;

                case 'and':
                    $token = '&&';
                    break;

                case 'or':
                    $token = '||';
                    break;

                case 'not':
                    $token = '!';
                    break;

                case 'mod':
                    $token = '%';
                    break;

                default:
                    if ($token[0] == '$')
                    {
                        $token = $this->get_val(substr($token, 1));
                    }
                    break;
            }
        }

        if ($elseif)
        {
            return '<?php elseif (' . implode(' ', $tokens) . '): ?>';
        }
        else
        {
            return '<?php if (' . implode(' ', $tokens) . '): ?>';
        }
    }

    /**
     * 处理foreach标签
     *
     * @access  public
     * @param   string     $tag_args
     *
     * @return  string
     */
    function _compile_foreach_start($tag_args)
    {
        $attrs = $this->get_para($tag_args, 0);
        $arg_list = array();
        $from = $attrs['from'];

        $item = $this->get_val($attrs['item']);

        if (!empty($attrs['key']))
        {
            $key = $attrs['key'];
            $key_part = $this->get_val($key).' => ';
        }
        else
        {
            $key = null;
            $key_part = '';
        }

        if (!empty($attrs['name']))
        {
            $name = $attrs['name'];
        }
        else
        {
            $name = null;
        }

        $output = '<?php ';
        $output .= "\$_from = $from; if (!is_array(\$_from) && !is_object(\$_from)) { settype(\$_from, 'array'); }; \$this->push_vars('$attrs[key]', '$attrs[item]');";

        if (!empty($name))
        {
            $foreach_props = "\$this->_foreach['$name']";
            $output .= "{$foreach_props} = array('total' => count(\$_from), 'iteration' => 0);\n";
            $output .= "if ({$foreach_props}['total'] > 0):\n";
            $output .= "    foreach (\$_from AS $key_part$item):\n";
            $output .= "        {$foreach_props}['iteration']++;\n";
        }
        else
        {
            $output .= "if (count(\$_from)):\n";
            $output .= "    foreach (\$_from AS $key_part$item):\n";
        }

        return $output . '?>';
    }

    /**
     * 将 foreach 的 key, item 放入临时数组
     *
     * @param  mixed    $key
     * @param  mixed    $val
     *
     * @return  void
     */
    function push_vars($key, $val)
    {
        if (!empty($key))
        {
            array_push($this->_temp_key, "\$this->_vars['$key']='" .$this->_vars[$key] . "';");
        }
        if (!empty($val))
        {
            array_push($this->_temp_val, "\$this->_vars['$val']='" .$this->_vars[$val] ."';");
        }
    }

    /**
     * 弹出临时数组的最后一个
     *
     * @return  void
     */
    function pop_vars()
    {
        $key = array_pop($this->_temp_key);
        $val = array_pop($this->_temp_val);

        if (!empty($key))
        {
            eval($key);
        }
    }

    /**
     * 处理smarty开头的预定义变量
     *
     * @access  public
     * @param   array   $indexes
     *
     * @return  string
     */
    function _compile_smarty_ref(&$indexes)
    {
        /* Extract the reference name. */
        $_ref = $indexes[0];

        switch ($_ref)
        {
            case 'now':
                $compiled_ref = 'time()';
                break;

            case 'foreach':
                array_shift($indexes);
                $_var = $indexes[0];
                $_propname = $indexes[1];
                switch ($_propname)
                {
                    case 'index':
                        array_shift($indexes);
                        $compiled_ref = "(\$this->_foreach['$_var']['iteration'] - 1)";
                        break;

                    case 'first':
                        array_shift($indexes);
                        $compiled_ref = "(\$this->_foreach['$_var']['iteration'] <= 1)";
                        break;

                    case 'last':
                        array_shift($indexes);
                        $compiled_ref = "(\$this->_foreach['$_var']['iteration'] == \$this->_foreach['$_var']['total'])";
                        break;

                    case 'show':
                        array_shift($indexes);
                        $compiled_ref = "(\$this->_foreach['$_var']['total'] > 0)";
                        break;

                    default:
                        $compiled_ref = "\$this->_foreach['$_var']";
                        break;
                }
                break;

            case 'get':
                $compiled_ref = '$_GET';
                break;

            case 'post':
                $compiled_ref = '$_POST';
                break;

            case 'cookies':
                $compiled_ref = '$_COOKIE';
                break;

            case 'env':
                $compiled_ref = '$_ENV';
                break;

            case 'server':
                $compiled_ref = '$_SERVER';
                break;

            case 'request':
                $compiled_ref = '$_REQUEST';
                break;

            case 'session':
                $compiled_ref = '$_SESSION';
                break;

            case 'const':
                array_shift($indexes);
                $compiled_ref = '@constant("' . strtoupper($indexes[0]) . '")';
                break;

            default:
                // $this->_syntax_error('$smarty.' . $_ref . ' is an unknown reference', E_USER_ERROR, __FILE__, __LINE__);
                break;
        }
        array_shift($indexes);

        return $compiled_ref;
    }

    /**
     * 脚本标签
     *
     * @author liupeng
     * @param  array  $args 参数
     * @return string
     **/
    function java_script($args)
    {
        $arr = explode(',', $args['src']);
        $idx = array_search("ecmall", $arr);
        if ($idx !== false)
        {
            unset($arr[$idx]);
            array_unshift($arr, 'ecmall');
        }

        $file_name = md5($this->_echash . strtolower(join('', $arr)));
        $file_path = ROOT_PATH . "/temp/js/$file_name.js";

        $str = "<script type='text/javascript' src='temp/js/$file_name.js'></script>";

        if (is_file($file_path))
        {
            $mtime = filemtime($file_path);
            $changed = false;
            foreach($arr AS $val)
            {
                $org_file = ROOT_PATH. "/js/$val.js";

                if (filemtime($org_file) > $mtime)
                {
                    $changed = true;
                    break;
                }
            }

            if (!$changed)
            {
                return $str;
            }
        }

        if (!is_file($file_path) || $changed)
        {
            foreach($arr AS $val)
            {
                $content = file_get_contents(ROOT_PATH."/js/$val.js");
                $patterns[]     = '/\s+\/\/.*+\n/';
                $patterns[]     = '/\/\*.+?\*\//s';
                $replacement[]  = '';
                $replacement[]  = '';
                $content = preg_replace($patterns, $replacement, $content);

                if (!defined('DEBUG_MODE') || DEBUG_MODE === 0)
                {
                    $content = str_replace("\r", '', $content);
                    $content = str_replace("\n", '', $content);
                }
                $js_code .= $content;
            }

            file_put_contents($file_path, $js_code, LOCK_EX);
        }

        return $str;
    }

    /**
     * 样式标签
     *
     * @author weberliu
     * @param  array  $args 参数
     * @return string
     **/
    function style($args)
    {
        $arr = explode(',', $args['src']);
        $file_name = md5($this->_echash . strtolower(join('', $arr)));
        $file_path = ROOT_PATH . "/temp/style/$file_name.css";

        $str = "<link type='text/css' rel='stylesheet' href='temp/style/$file_name.css'></link>";
        if (is_file($file_path))
        {
            $mtime = filemtime($file_path);
            $changed = false;
            foreach($arr AS $val)
            {
                $org_file = ROOT_PATH . '/' . $val;
                if (filemtime($org_file) > $mtime)
                {
                    $changed = true;
                    break;
                }
            }
        }

        if (!is_file($file_path) || $changed || DEBUG_MODE > 0)
        {
            foreach($arr AS $file)
            {
                $content = file_get_contents(ROOT_PATH . '/' . $file);
                $skin_img = dirname(ROOT_DIR . '/'. $file) . '/images';

                $patterns[]     = '/\s+\/\/.*+\n/';
                $replacement[]  = '';
                $content = preg_replace($patterns, $replacement, $content);
                $content = str_replace("\r", '', $content);
                $content = str_replace("\n", '', $content);

                $content = preg_replace('/url\(images/i', "url($skin_img", $content);
                $js_code .= $content;
            }

            file_put_contents($file_path, $js_code, LOCK_EX);
        }

        return $str;
    }

    /**
     * 替换模块中图片路径
     *
     * @author liupeng
     * @param  string  $source 内容
     * @return string
     **/
    function smarty_prefilter_preCompile($source)
    {
        $file_type = strtolower(strrchr($this->_current_file, '.'));
        $tmp_dir = '' ;

        /* 替换文件编码头部 */
        if (strpos($source, "\xEF\xBB\xBF") !== FALSE)
        {
            $source = str_replace("\xEF\xBB\xBF", '', $source);
        }


        if ($this->store_id > 0)
        {
            if (strpos($this->_current_file, '/mall/resource') !== false)
            {
                $mall_skin = $this->options['mall_skin'];
                $tmp_dir = "themes/mall/skin/$mall_skin/" ;
            }
            else
            {
                $tmp_dir = "themes/store/skin/" . $this->skin . '/' ;
            }
        }
        else {
            $tmp_dir = "themes/mall/skin/" . $this->skin . '/' ;
        }

        $pattern = array(
            '/<!--[^>|\n]*?({.+?})[^<|{|\n]*?-->/', // 替换smarty注释
            '/<!--[^<|>|{|\n]*?-->/',               // 替换不换行的html注释
            '/(href=["|\'])\.\.\/(.*?)(["|\'])/i',  // 替换相对链接
            '/((?:background|src)\s*=\s*["|\'])(?:\.\/|\.\.\/)?(images\/.*?["|\'])/is', // 在images前加上 $tmp_dir
            '/((?:background|background-image):\s*?url\()(?:\.\/|\.\.\/)?(images\/)/is', // 在images前加上 $tmp_dir
            //'/{nocache}(.+?){\/nocache}/ise', //无缓存模块
			'/{nocache}(.+?){\/nocache}/is', //无缓存模块 for >= PHP5.5.X
            );
        $replace = array(
            '\1',
            '',
            '\1\2\3',
            '\1' . $tmp_dir . '\2',
            '\1' . $tmp_dir . '\2',
            "'{insert name=\"nocache\" ' . '" . $this->_echash . "' . base64_encode('\\1') . '}'",
            );

        return preg_replace($pattern, $replace, $source);
    }

    /*
     * 返回URL重写后的字符串
     *
     * @author  weberliu
     * @param   array       $arr    包含URL参数的数组
     * @return  string
     */
    function url_rewrite($arr)
    {

        $url = '';
        $custom_url = '';
        $tmp = array();
        if (isset($arr['store_id']) && $arr['store_id'] > 0 && isset($arr['app']) && in_array($arr['app'], array('store', 'goods')))
        {
            $custom_url = get_store_custom_url($arr['store_id']);
            if ($custom_url  || $arr['app'] == 'goods')
            {
                unset($arr['store_id']); //不在需要store_id
            }
        }

        if (defined('URL_REWRITE') && URL_REWRITE > 0)
        {
            foreach ($arr AS $key=>$val)
            {
                if ($key == 'app')
                {
                    $url = $custom_url . $val . '_';
                }
                else
                {
                    $tmp[] = $key.chr(9).$val;
                }
            }

            $args = str_replace(array('+','/'), array('.', '-'), base64_encode(join(chr(8), $tmp)));

            $url .= $args. '.html';
        }
        else
        {
            if ($custom_url && $arr['app'] == 'store')
            {
                unset($arr['app']); //使用指定义域名可以省略app
            }

            foreach ($arr AS $key=>$val)
            {
                 $tmp[] = "$key=" . urlencode($val);
            }

            if ($custom_url)
            {
                $url = $tmp ? $custom_url . 'index.php?' . implode('&amp;', $tmp) : $custom_url;
            }
            else
            {
                $url = 'index.php?' . implode('&amp;', $tmp);
            }

        }

        return $url;
    }

    /**
     * 处理动态模块
     *
     * @author wj
     * @param  string   $name  动态模块内容
     * @return stirng
     */
    function insert_mod($name) // 处理动态内容
    {
        list($fun, $para) = explode('|', $name,2);
        $para = unserialize($para);

        $para['template'] = &$this;
        $fun = 'insert_' . $fun;

        return $fun($para);
    }

    function str_trim($str)
    {
        /* 处理'a=b c=d k = f '类字符串，返回数组 */
        while (strpos($str, '= ') != 0)
        {
            $str = str_replace('= ', '=', $str);
        }
        while (strpos($str, ' =') != 0)
        {
            $str = str_replace(' =', '=', $str);
        }

        return explode(' ', trim($str));
    }

    function _eval($content)
    {
        ob_start();
        eval('?' . '>' . trim($content));

        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    function _require($filename)
    {
        ob_start();
        include $filename;
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    function html_options($arr)
    {
        $selected = $arr['selected'];

        if ($arr['options'])
        {
            $options = (array)$arr['options'];
        }
        elseif ($arr['output'])
        {
            if ($arr['values'])
            {
                foreach ($arr['output'] AS $key => $val)
                {
                    $options["{$arr[values][$key]}"] = $val;
                }
            }
            else
            {
                $options = array_values((array)$arr['output']);
            }
        }
        if ($options)
        {
            foreach ($options AS $key => $val)
            {
                $key = htmlspecialchars($key);
                $val = strip_tags($val);
                $out .= $key == $selected ? "<option value=\"$key\" selected>$val</option>" : "<option value=\"$key\">$val</option>";
            }
        }

        return $out;
    }
    function display_widgets($arr)
    {
        /* 请求控制器 */
        $controller =& cc();
        $controller->display_widgets($arr);
    }

    function html_radios($arr)
    {
        $name    = $arr['name'];
        $checked = $arr['checked'];
        $options = $arr['options'];

        $out = '';
        foreach ($options AS $key => $val)
        {
            $out .= $key == $checked ? "<label><input type=\"radio\" name=\"$name\" value=\"$key\" checked>&nbsp;{$val}</label>&nbsp;"
                : "<label><input type=\"radio\" name=\"$name\" value=\"$key\">&nbsp;{$val}</label>&nbsp;";
        }

        return $out;
    }

    function html_checkbox($arr)
    {
        $name       = $arr['name'];
        $checked    = empty($arr['checked']) ? array() : $arr['checked'];
        $options    = $arr['options'];
        $separator  = isset($arr['separator']) ? $arr['separator'] : '&nbsp;';

        if (!is_array($checked))
        {
            $checked = explode(',', $checked);
        }

        $out = '';
        foreach ($options AS $key => $val)
        {
            $out .= in_array($key, $checked) ? "<label><input type=\"checkbox\" name=\"$name\" value=\"$key\" checked>&nbsp;{$val}</label>$separator"
                : "<label><input type=\"checkbox\" name=\"$name\" value=\"$key\">&nbsp;{$val}</label>$separator";
        }

        return $out;
    }

    function html_page_links($arr)
    {
        $page = array();
        if (isset($arr['from']))
        {
            $page = $arr['from'];
        }
        else
        {
            trigger_error("Function html_page_links missing \"From\" argument in " .$this->_current_file , E_USER_ERROR);
        }

        $page = $this->_init_page_param($page);

        if (preg_match('/[&|\?]?page=\w+/i',$_SERVER['REQUEST_URI']) > 0)
        {
            $url_format = preg_replace('/[&|\?]?page=\w+/i', '', $_SERVER['REQUEST_URI']);
        }
        else
        {
            $url_format = $_SERVER['REQUEST_URI'] . '';
        }

        $segment    = 3;
        $max_pages  = 7;
        $start      = ($page['curr_page'] <= $segment) ? 1 : $page['curr_page'] - $segment;
        $end        = ($page['page_count'] <= $start + $max_pages - 1) ? $page['page_count'] : $start + $max_pages - 1;
        if ($end == $page['page_count'] && $page['page_count'] >= $max_pages)
        {
            $start = $end - $max_pages + 1;
        }
        $out        = "<ul>\n";
        $prve_page  = $page['curr_page'] - 1;
        $next_page  = $page['curr_page'] + 1;
        $out        .= "<li class=\"pg_total\">$page[curr_page]/$page[page_count]</li>\n";

        if ($page['page_count'] > 1)
        {
            if ($start > 1)
            {
                $out .= "<li class=\"pg_first\"><a href='$url_format&amp;page=1'>&laquo;</a></li>\n";
                $out .= "<li class=\"pg_omission\">...</li>\n";
                $out .= "<li class=\"pg_prve\"><a href='$url_format&amp;page=$prve_page'>&lsaquo;</a></li>\n";
            }

            for ($i = $start; $i <= $end; $i++)
            {
                $out .= ($i != $page['curr_page']) ?
                    "<li><a href='$url_format&amp;page=$i'>" .$i. "</a></li>\n" :
                    '<li class="curr-page">' .$i. "</li>\n" ;
            }

            if ($end < $page['page_count'])
            {
                $out .= "<li class=\"pg_next\"><a href='$url_format&amp;page=$next_page'>&rsaquo;</a></li>\n";
                $out .= "<li class=\"pg_omission\">...</li>\n";
                $out .= "<li class=\"pg_last\"><a href='$url_format&amp;page=$page[page_count]'>&raquo;</a></li>\n";
            }
        }

        $out        .= '</ul>&nbsp;';

        return $out;
    }
    function _init_page_param($arr)
    {
        if (!isset($arr['page_count']))
        {
            $arr['page_count'] = ceil($arr['item_count'] / $arr['pageper']);
        }

        return $arr;
    }

    /**
     * 分页处理器
     *
     * @author liupeng
     * @param  array $arr 参数
     * @return string
     **/
    function html_page_selector($arr)
    {
        $page = array();

        if (isset($arr['from']))
        {
            $page = $arr['from'];
        }
        else
        {
            trigger_error("Function html_page_selector missing From argument in " .$this->_current_file , E_USER_ERROR);
        }

        $page = $this->_init_page_param($page);

        if (preg_match('/[&|\?]?page=\w+/i',$_SERVER['REQUEST_URI']) > 0)
        {
            $url_format = preg_replace('/[&|\?]?page=\w+/i', '', $_SERVER['REQUEST_URI']);
        }
        else
        {
            $url_format = $_SERVER['REQUEST_URI'];
        }

        $out = "<select onchange='location.href=this.options[this.selectedIndex].value' class=\"seledr\">\n";

        for ($i = 1; $i <= $page['page_count']; $i++)
        {
                $out .= ($i != $page['curr_page']) ?
                "<option value='$url_format&amp;page=$i'>$i/$page[page_count]</option>\n":
                "<option value='$url_format&amp;page=$i' selected=\"true\">$i/$page[page_count]</option>\n";
        }

        $out .= '</select>';

        return $out;
    }

    function html_page_simple($arr)
    {
        $page = array();
        if (isset($arr['from']))
        {
            $page = $arr['from'];
        }
        else
        {
            trigger_error("Function html_page_simple missing \"From\" argument in " .$this->_current_file , E_USER_ERROR);
        }
        if (preg_match('/[&|\?]?page=\w+/i',$_SERVER['REQUEST_URI']) > 0)
        {
            $url_format = preg_replace('/[&|\?]?page=\w+/i', '', $_SERVER['REQUEST_URI']);
        }
        else
        {
            $url_format = $_SERVER['REQUEST_URI'] . '';
        }

        $out        = '';
        $prve_page  = $page['curr_page'] - 1;
        $next_page  = $page['curr_page'] + 1;
        $_GET['page'] = $prve_page;
        $prev_url     = $this->url_rewrite($_GET);
        $_GET['page'] = $next_page;
        $next_url     = $this->url_rewrite($_GET);
        $out        .= ($page['curr_page'] > 1) ? "<a href='$prev_url' class='act_prve_page'>prve_page</a>\n" : "<span class='prve_page'>prve_page</span>\n";
        $out        .= "<span class='pps_total'>{$page['curr_page']}/{$page['page_count']}</span>\n";
        $out        .= ($page['curr_page'] < $page['page_count']) ? "<a href='$next_url' class='act_next_page'>next_page</a>\n" : "<span class='next_page'>next_page</span>\n";

        return $out;
    }

    function html_img_yesorno($arr)
    {
        $val = intval($arr['value']);
        $src = $arr['dir'] . "/" . (($val != 0 ) ? 'yes.gif' : 'no.gif');
        $out = "<img src=\"$src\" value=\"$val\" />";

        return $out;
    }

    function html_sort_link($arr)
    {
        $url = parse_url($_SERVER['REQUEST_URI']);
        $arg = array();
        $out = '';

        parse_str($url['query'], $arg);

        if (isset($arg['sort']))
        {
            $order = (empty($arg['order']) || strcasecmp($arg['order'], 'desc') == 0) ? 'asc' : 'desc';

            if ($arg['sort'] == $arr['by'])
            {
                $flag = "<img src='admin/images/icon_$arg[order].gif' alt='$arg[order]' style='margin-left: 3px; vertical-align:middle;'/>";
            }
        }
        else
        {
            $order = 'asc';
            $flag  = '';
        }

        $arg['sort']    = $arr['by'];
        $arg['order']   = $order;
        $temp           = '';

        foreach ($arg AS $key=>$val)
        {
            $temp .= "&amp;$key=$val";
        }
        $temp = '?' .substr($temp, 5);
        $href = substr_replace($_SERVER['REQUEST_URI'], $temp, strpos($_SERVER['REQUEST_URI'], '?'));
        $out .= "<a href=\"$href\">$arr[text]</a>" . $flag;

        return $out;
    }

    function cycle($arr)
    {
        static $k, $old;

        $value = explode(',', $arr['values']);
        if ($old != $value)
        {
            $old = $value;
            $k = 0;
        }
        else
        {
            $k++;
            if (!isset($old[$k]))
            {
                $k = 0;
            }
        }

        echo $old[$k];
    }

    function image($arr)
    {
        $uri = '';
        $hash_path = md5(ECM_KEY . $arr['file'] . $arr['width'] . $arr['height']);
        $thumb_path = './temp/thumb/' . $hash_path{0} . $hash_path{1} . '/' . $hash_path{2} . $hash_path{3} . '/' . $hash_path . $arr['file'] . '.jpg';
        if (!is_file($thumb_path))
        {
            $width  = isset($arr['width']) ? intval($arr['width']) : 0;
            $height = isset($arr['height']) ? intval($arr['height']) : 0;
            $file   = empty($arr['file']) ? 0 : intval($arr['file']);

            $uri = 'image.php?file_id='.$file.'&amp;hash_path='.md5(ECM_KEY.$file.$width.$height);
            if (isset($arr['width'])) $uri .= '&amp;width=' . $arr['width'];
            if (isset($arr['height'])) $uri .= '&amp;height=' . $arr['height'];
            if (isset($arr['absolute_path']))
            {
                $uri = site_url() . "/$uri";
            }
            return $uri;
        }
        else
        {
            if (isset($arr['absolute_path']))
            {
                $thumb_path = site_url() . substr($thumb_path, 1);
            }

            return $thumb_path;
        }
    }

    function make_array($arr)
    {
        $out = '';
        foreach ($arr AS $key => $val)
        {
            if ($val{0} == '$')
            {
                $out .= $out ? ",'$key'=>$val" : "array('$key'=>$val";
            }
            else
            {
                $out .= $out ? ",'$key'=>'$val'" : "array('$key'=>'$val'";
            }
        }

        return $out . ')';
    }

    function smarty_create_pages($params)
    {
        extract($params);

        if (empty($page))
        {
            $page = 1;
        }

        if (!empty($count))
        {
            $str = "<option value='1'>1</option>";
            $min = min($count - 1, $page + 3);
            for ($i = $page - 3 ; $i <= $min ; $i++)
            {
                if ($i < 2)
                {
                    continue;
                }
                $str .= "<option value='$i'";
                $str .= $page == $i ? " selected='true'" : '';
                $str .= ">$i</option>";
            }
            if ($count > 1)
            {
                $str .= "<option value='$count'";
                $str .= $page == $count ? " selected='true'" : '';
                $str .= ">$count</option>";
            }
        }
        else
        {
            $str = '';
        }

        return $str;
    }

    /**
     * 获取自定义模块
     *
     * @author  liupeng
     * @return  string
    */
    function get_custom_module($id, $dir)
    {
        $filename = ROOT_PATH. "/themes/$dir/resource/custom_module.html";
        $html = file_get_contents($filename);
        $html = str_replace('{index}', $id, $html);

        return $html;
    }

    /**
     * 编译时解析语言
     *
     * @author wj
     * @param  string $key 要解析的语言项key值
     * @return string
     */
    function get_lang($key)
    {
        $val = $this->make_var($key);
        $lang = eval('return isset(' . $val .') ? '. $val . ': null;');

        return isset($lang) ? $lang : substr($key, 5);
    }

    /**
     * 解析sprinf标签
     *
     * @author wj
     * @param  array $arr
     * @return string
     */
    function _sprintf($arr)
    {
        $lang = $this->get_lang('lang.' . $arr['lang']);
        unset($arr['lang']);
        foreach($arr as $k=>$v)
        {
            $arr[$k] = $this->make_var($v);
        }

        return 'sprintf(\'' . $lang . '\', ' . implode(',' , $arr) . ')';
    }
}

?>
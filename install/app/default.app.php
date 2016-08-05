<?php

/**
 * ECMALL: 安装程序控制器
 * ============================================================================
 * 版权所有 (C) 2005-2008 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.shopex.cn
 * -------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Id: default.app.php 12132M 2012-06-22 11:44:02Z (local) $
 */

class DefaultApp extends InstallerApp
{
    /* UTF8版 */
    var $_lang = 'sc-utf-8';

    /**
     *    获取流程地图
     *
     *    @author    Garbin
     *    @return    array
     */
    function _get_map()
    {
        return array(
            'eula',     //用户协议
            'check',    //环境检测
            'config',   //用户配置
            'install',  //完成安装
        );
    }

    /**
     *    用户协议，该步骤提交两个POST变量，lang和accept
     *
     *    @author    Garbin
     *    @return    void
     */
    function eula()
    {
        $eula = file_get_contents(version_data('eula.html'));
        $this->assign('eula', $eula);
        $this->display('eula.html');
    }

    /**
     *    选择完成用户协议，检查数据有效性
     *
     *    @author    Garbin
     *    @param    none
     *    @return    void
     */
    function eula_done()
    {
        if (!$_POST['accept'])
        {
            $this->show_warning('accept_first');

            return false;
        }

        return true;
    }

    /**
     *    环境检测，该步骤提交一个POST变量compatible,yes:检测通过,n:检测不通过
     *
     *    @author    Garbin
     *    @return    void
     */
    function check()
    {
        //规则,结果,显示
        $check_env = $this->_check_env(array(
            'php_version'   =>  array(
                'required'  => '>= 4.3',
                'checker'   => 'php_checker',
            ),
            'gd_version'   =>  array(
                'required'  => '>= 1.0',
                'checker'   => 'gd_checker',
            ),
        ));
        $check_file= $this->_check_file(array(
            './data',
            './temp',
            './external/widgets',
        ));
        $compatible = false;
        if ($check_env['compatible'] && $check_file['compatible'])
        {
            $compatible = true;
        }
        $this->_hiddens['accept']   = $_POST['accept'];

        $this->assign('check_env', $check_env);
        $this->assign('check_file', $check_file);
        $this->assign('messages', array_merge($check_env['msg'], $check_file['msg']));
        $this->assign('compatible', $compatible);
        $this->display('check.html');
    }

    /**
     *    检查结果
     *
     *    @author    Garbin
     *    @param    none
     *    @return    void
     */
    function check_done()
    {
        if (!$_POST['compatible'])
        {
            $this->show_warning('incompatible');

            return false;
        }

        return true;
    }

    /**
     *    配置表单，该步骤提交一个配置数组
     *
     *    @author    Garbin
     *    @return    void
     */
    function config()
    {
        $this->_hiddens['accept']   = $_POST['accept'];
        $this->_hiddens['compatible']   = $_POST['compatible'];
        $this->assign('site_url', dirname(site_url()));
        $this->display('config.html');
    }

    /**
     *    配置表单的处理脚本
     *
     *    @author    Garbin
     *    @return    void
     */
    function config_done()
    {
        $missing_items = array();
        foreach ($_POST as $key => $value)
        {
            if (empty($value) && $key != 'db_pass')
            {
                $missing_items[] = $key;
            }
        }
        if (!empty($missing_items))
        {
            $this->_doing = $this->_done;
            $this->assign('missing_items', $missing_items);
            $this->config();

            return false;
        }
        extract($_POST);
        if (!preg_match("/^http(s?):\/\//i", $site_url))
        {
            $this->_doing = $this->_done;
            $this->assign('site_url_error', true);
            $this->config();
            return false;
        }
        if (!is_email($admin_email))
        {
            $this->_doing = $this->_done;
            $this->assign('admin_email_error', true);
            $this->config();
            return false;
        }
        if ($admin_pass != $pass_confirm)
        {
            $this->_doing = $this->_done;
            $this->assign('pass_error', true);
            $this->config();
            return false;
        }
        /* 检查输入的数据库配置信息*/
        /* 检查是否能连上数据库 */
        $con = @mysql_connect($db_host . ':' . $db_port, $db_user, $db_pass);
        if (!$con)
        {
            $this->_doing = $this->_done;
            $this->assign('mysql_error', mysql_error());
            $this->config();
            return false;
        }
        /* 检查数据库是否存在 */
        $selected_db = @mysql_select_db($db_name);
        if (!$selected_db)
        {
            /* 如果不存在，尝试创建该数据库 */
            $created_db = @create_db($db_name, $con);

            /* 创建不成功，则显示错误 */
            if (!$created_db)
            {
                $this->_doing = $this->_done;
                $this->assign('create_db_error', mysql_error());
                $this->config();

                return false;
            }
        }
        else
        {
            /* 如果存在，检查是否已安装过ECMall */
            $query = @mysql_query("SHOW TABLES LIKE '{$db_prefix}%'");
            /* 如果安装过，检查是否同意强制安装 */
            $has_ecmall = false;
            while ($row = mysql_fetch_assoc($query))
            {
                $has_ecmall = true;
                break;
            }

            /* 有ECMall，但不同意强制安装，则显示错误 */
            if ($has_ecmall && empty($_POST['force_install']))
            {
                $this->_doing = $this->_done;
                $this->assign('has_ecmall', true);
                $this->config();

                return false;
            }

            /* 没有装过ECMall或有ECMall但同意强制安装，则直接通过 */
        }

        return true;
    }

    /**
     *    安装，根据之前POST过来的配置项组成安装方案并运行
     *
     *    @author    Garbin
     *    @return    void
     */
    function install()
    {
        foreach ($_POST as $key => $value)
        {
            $this->_hiddens[$key] = $value;
        }
        $this->display('install.html');
    }

    /**
     *    完成安装
     *
     *    @author    Garbin
     *    @return    void
     */
    function install_done()
    {
        extract($_POST);

        /* 无实际用途 */
        $_code = rand(10000, 99999);
        setcookie('__INTECODE__', $_code, 0, '/');

        /* 连接数据库 */
        $con = mysql_connect($db_host . ':' . $db_port, $db_user, $db_pass);

        if (!$con)
        {
            show_process(r(Lang::get('connect_db'), false), 'parent.show_warning("' . Lang::get('connect_db_error') . '")');

            return false;
        }
        show_process(r(Lang::get('connect_db'), true));
        $version = mysql_get_server_info();
        $charset = str_replace('-', '', CHARSET);
        if ($version > '4.1')
        {
            if ($charset != 'latin1')
            {
                mysql_query("SET character_set_connection={$charset}, character_set_results={$charset}, character_set_client=binary", $con);
            }
            if ($version > '5.0.1')
            {
                mysql_query("SET sql_mode=''", $con);
            }
        }

        /* 选择数据库 */
        $selected_db = mysql_select_db($db_name, $con);
        if (!$selected_db)
        {
            show_process(r(Lang::get('selecte_db'), false), 'parent.show_warning("' . Lang::get('selecte_db_error') . '");');

            return false;
        }
        /* 建立数据库结构 */
        show_process(r(Lang::get('start_setup_db'), true));

        $sqls = get_sql(version_data('structure.sql'));
        foreach ($sqls as $sql)
        {
            $sql = replace_prefix('ecm_', $db_prefix, $sql);
            if (substr($sql, 0, 12) == 'CREATE TABLE')
            {
                $name = preg_replace("/CREATE TABLE `{$db_prefix}([a-z0-9_]+)` .*/is", "\\1", $sql);
                mysql_query(create_table($sql));
                show_process(r(sprintf(Lang::get('create_table'), $name), true, 1));
            }
            else
            {
                mysql_query($sql, $con);
            }
        }
        /* 安装初始数据 TODO 暂时不完整 */
        $sqls = get_sql(version_data('initdata.sql'));
        $password = md5($admin_pass);
        $sqls[] = "INSERT INTO `ecm_member`(user_name, email, password, reg_time) VALUES('{$admin_name}', '{$admin_email}', '{$password}', " . gmtime() . ")";
        foreach ($sqls as $sql)
        {
            $rzt = mysql_query(replace_prefix('ecm_', $db_prefix, $sql), $con);
            if (!$rzt)
            {
                show_process(r(Lang::get('install_initdata'), false), 'parent.show_warning("' . mysql_error() . '");');

                return false;
            }
        }
        if (mysql_errno())
        {
            echo mysql_error();
        }
        show_process(r(Lang::get('install_initdata'), true));

        /* 安装初始配置 */
        $db_config = "mysql://{$db_user}:{$db_pass}@{$db_host}:{$db_port}/{$db_name}";
        $ecm_key   = get_ecm_key();
        $mall_site_id = product_id();
        save_config_file(array(
            'SITE_URL'  => $site_url,
            'DB_CONFIG'  => $db_config,
            'DB_PREFIX'  => $db_prefix,
            'LANG'  => LANG,
            'COOKIE_DOMAIN'  => '',
            'COOKIE_PATH'  => '/',
            'ECM_KEY'  => $ecm_key,
            'MALL_SITE_ID'  => $mall_site_id,
            'ENABLED_GZIP'  => 0,
            'DEBUG_MODE'  => 0,
            'CACHE_SERVER'  => 'default',
            'MEMBER_TYPE'  => 'default',
            'ENABLED_SUBDOMAIN' => 0,
            'SUBDOMAIN_SUFFIX'  => '',
            'SESSION_TYPE' => 'mysql',
            'SESSION_MEMCACHED' => 'localhost:11211',
            'CACHE_MEMCACHED' => 'localhost:11211',
        ));
        /* 写入系统信息 */
        save_system_info(array(
            'version'   => VERSION,
            'release'   => RELEASE,
        ));
        show_process(r(Lang::get('setup_config'), true));

        /* 锁定安装程序 */
        touch(LOCK_FILE);
        show_process(r(Lang::get('lock_install'), true));

        if (is_file(ROOT_PATH . '/integrate/index.php'))
        {
            /* 跳至整合程序 */
            show_process(r(Lang::get('install_done'), true), 'parent.goon_install("' . $site_url . '/integrate/index.php", "' . $_code . '");');
        }
        else
        {
            /* 安装完成 */
            show_process(r(Lang::get('install_done'), true), 'parent.install_successed();');

            return false;
        }
    }
}

/**
 *    检查PHP版本
 *
 *    @author    Garbin
 *    @param    none
 *    @return    void
 */
function php_checker()
{
    return array(
        'current' => PHP_VERSION,
        'result'  => (PHP_VERSION >= 4.3),
    );
}

/**
 *    检查GD版本
 *
 *    @author    Garbin
 *    @param    none
 *    @return    void
 */
function gd_checker()
{
    $return = array('current' => null, 'result' => false);
    $gd_info = function_exists('gd_info') ? gd_info() : array();
    $return['current'] = empty($gd_info['GD Version']) ? Lang::get('gd_missing') : $gd_info['GD Version'];
    $return['result']  = empty($gd_info['GD Version']) ? false : true;

    return $return;
}

/**
 *    显示进程
 *
 *    @author    Garbin
 *    @param    none
 *    @return    void
 */
function show_process($msg, $script = '')
{
    header('Content-type:text/html;charset=' . CHARSET);
    echo '<script type="text/javascript">parent.show_process(\'' . $msg . '\');' . $script . '</script>';
    flush();
    ob_flush();
}

/**
 *    显示进程结果
 *
 *    @author    Garbin
 *    @param    none
 *    @return    void
 */
function r($text, $result, $level = 0)
{
    $indent = '';
    for ($i = 0; $i < $level; $i++)
    {
        $indent .= '&nbsp;&nbsp;&nbsp;&nbsp;';
    }
    $result_class = $result ? 'successed' : 'failed';
    $result_text = $result ? Lang::get('successed') : Lang::get('failed');
    $html = "<p><span class=\"{$result_class}\">{$result_text}</span>{$indent}{$text}</p>";

    return $html;
}

function save_config_file($data)
{
    $contents = file_get_contents(version_data('config.sample.php'));
    file_put_contents(ROOT_PATH . '/data/config.inc.php', str_replace('{%CONFIG_ARRAY%}', var_export($data, true), $contents));
}
function save_system_info($info)
{
    $file = ROOT_PATH . '/data/system.info.php';
    file_put_contents($file, "<?php\r\nreturn " . var_export($info, true) . "; \r\n?>");
}

function get_ecm_key()
{
    return md5(ROOT_PATH . time() . site_url() . rand());
}

function get_sql($file)
{
    $contents = file_get_contents($file);
    $contents = str_replace("\r\n", "\n", $contents);
    $contents = trim(str_replace("\r", "\n", $contents));
    $return_items = $items = array();
    $items = explode(";\n", $contents);
    foreach ($items as $item)
    {
        $return_item = '';
        $item = trim($item);
        $lines = explode("\n", $item);
        foreach ($lines as $line)
        {
            if (isset($line[0]) && $line[0] == '#')
            {
                continue;
            }
            if (isset($line[1]) && $line[0] .  $line[1] == '--')
            {
                continue;
            }

            $return_item .= $line;
        }
        if ($return_item)
        {
            $return_items[] = $return_item;
        }
    }

    return $return_items;
}

function create_table($sql) {
    $type = strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*$/isU", "\\2", $sql));
    $type = in_array($type, array('MYISAM', 'HEAP')) ? $type : 'MYISAM';
    return preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU", "\\1", $sql) .
    (mysql_get_server_info() > '4.1' ? " ENGINE={$type} DEFAULT CHARSET=" . str_replace('-', '', CHARSET) : " TYPE={$type}");
}

function replace_prefix($orig, $target, $sql)
{
    return str_replace('`' . $orig, '`' . $target, $sql);
}

?>

<?php

/**
 *    模块运行控制器
 *
 *    @author    Garbin
 *    @usage    none
 */
class ModuleApp extends BackendApp
{
    /**
     *    模块列表
     *
     *    @author    Garbin
     *    @return    void
     */
    function manage()
    {
        $modules = $this->_list_modules();
        $this->assign('modules', $modules);
        $this->display('module.index.html');
    }

    /**
     *    安装模块
     *
     *    @author    Garbin
     *    @return    void
     */
    function install()
    {
        $id = empty($_GET['id']) ? 0 : trim($_GET['id']);
        if (!$id)
        {
            $this->show_warning('no_such_module');

            return;
        }
        if (!IS_POST)
        {
            $module = $this->_get_module_info($id);
            $this->assign('module', $module);
            $this->assign('config', array('enabled' => true));
            $this->assign('enable_options', array(Lang::get('no'), Lang::get('yes')));
            $this->display('module.form.html');
        }
        else
        {
            $data = array();
            $data['module_id']      =   $id;
            $data['module_name']    =   $_POST['name'];
            $data['module_desc']    =   $_POST['desc'];
            $data['module_version'] =   $_POST['version'];
            $data['enabled']         =   $_POST['enabled'];
            !empty($_POST['config']) && $data['module_config'] = serialize($_POST['config']);

            /* 将模块信息增加到数据库 */
            $model_module =& m('module');
            $model_module->add($data);

            /* 运行安装脚本 */
            $install_script = ROOT_PATH . '/external/modules/' . $id . '/install.php';
            if (is_file($install_script))
            {
                include($install_script);
            }

            $this->show_message('install_module_successed',
                'manage_module', 'index.php?module='. $data['module_id'] . '&act=index');
        }
    }

    /**
     *    卸载
     *
     *    @author    Garbin
     *    @return    void
     */
    function uninstall()
    {
        $id = empty($_GET['id']) ? 0 : trim($_GET['id']);
        if (!$id)
        {
            $this->show_warning('no_such_module');

            return;
        }

        /* 删除数据库中的记录 */
        $model_module =& m('module');
        $model_module->drop('index:' . $id);

        /* 运行卸载脚本 */
        $uninstall_script = ROOT_PATH . '/external/modules/' . $id . '/uninstall.php';
        if (is_file($uninstall_script))
        {
            include($uninstall_script);
        }

        $this->show_message('uninstall_module_successed',
            'back_list', 'index.php?app=module&act=manage');
    }

    /**
     *    配置
     *
     *    @author    Garbin
     *    @return    void
     */
    function config()
    {
        $id = empty($_GET['id']) ? 0 : trim($_GET['id']);
        if (!$id)
        {
            $this->show_warning('no_such_module');

            return;
        }
        $model_module =& m('module');
        if (!IS_POST)
        {
            $module = $this->_get_module_info($id);
            $find_data = $model_module->find('index:' . $id);
            if (empty($find_data))
            {
                $this->show_warning('no_such_module');

                return;
            }
            $info = current($find_data);
            $config = unserialize($info['module_config']);
            $config['enabled'] = $info['enabled'];

            $this->assign('module', $module);
            $this->assign('config', $config);
            $this->assign('enable_options', array(Lang::get('no'), Lang::get('yes')));
            $this->display('module.form.html');
        }
        else
        {
            $data   = array();
            !empty($_POST['config']) && $data['module_config'] = serialize($_POST['config']);
            $data['enabled']       = intval($_POST['enabled']);
            $model_module->edit('index:' . $id, $data);
            $this->show_message('config_module_successed');
        }
    }

    /**
     *    列表模块
     *
     *    @author    Garbin
     *    @return    array
     */
    function _list_modules()
    {
        $module_dir = ROOT_PATH . '/external/modules';
        static $modules    = null;
        if ($modules === null)
        {
            $modules = array();
            if (!is_dir($module_dir))
            {
                return $modules;
            }
            $dir = dir($module_dir);
            while (false !== ($entry = $dir->read()))
            {
                if (in_array($entry, array('.', '..')) || $entry{0} == '.')
                {
                    continue;
                }
                $info = $this->_get_module_info($entry);
                $modules[$entry] = $info;
                $modules[$entry]['installed'] = $this->_is_installed($entry);
                $modules[$entry]['outofdate'] = $this->_is_outofdate($entry, $info['version']);
            }
        }

        return $modules;
    }

    /**
     *    获取模块信息
     *
     *    @author    Garbin
     *    @param     string $id
     *    @return    array
     */
    function _get_module_info($id)
    {
        Lang::load(ROOT_PATH . '/external/modules/' . $id . '/languages/' . LANG . '/common.lang.php');
        $module_info_path = ROOT_PATH . '/external/modules/' . $id . '/module.info.php';

        return include($module_info_path);
    }

    /**
     *    判断是否过时
     *
     *    @author    Garbin
     *    @param     string $id
     *    @return    bool
     */
    function _is_outofdate($id, $version)
    {
        $installed = $this->_list_installed();
        $info = $installed[$id];
        if (empty($info))
        {
            return false;
        }

        return $info['module_version'] < $version;
    }

    /**
     *    判断模块是否已安装
     *
     *    @author    Garbin
     *    @param     string $id
     *    @return    bool
     */
    function _is_installed($id)
    {
        $installed = $this->_list_installed();

        return array_key_exists($id, $installed);
    }

    /**
     *    列表已安装的模块
     *
     *    @author    Garbin
     *    @return    array
     */
    function _list_installed()
    {
        static $installed = null;
        if ($installed === null)
        {
            $model_module =& m('module');
            $installed = $model_module->find();
        }

        return $installed;
    }
}

?>
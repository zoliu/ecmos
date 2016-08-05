<?php

/* 店铺等级控制器 */
class SgradeApp extends BackendApp
{
    var $_grade_mod;

    function __construct()
    {
        $this->SgradeApp();
    }

    function SgradeApp()
    {
        parent::__construct();
        $this->_grade_mod =& m('sgrade');
    }

    function index()
    {
        $conditions = $this->_get_query_conditions(array(
            array(
                'field' => 'grade_name',
                'equal' => 'LIKE',
            ),
        ));
        $page = $this->_get_page();
        $sgrades = $this->_grade_mod->find(array(
            'conditions' => '1=1' . $conditions,
            'limit' => $page['limit'],
            'count' => true,
            'order' => 'sort_order',
        ));
        foreach ($sgrades as $key => $sgrade)
        {
            if (!$sgrade['goods_limit'])
            {
                $sgrades[$key]['goods_limit'] = LANG::get('no_limit');
            }
            if (!$sgrade['space_limit'])
            {
                $sgrades[$key]['space_limit'] = LANG::get('no_limit');
            }
        }
        $this->assign('sgrades', $sgrades);
        //引入jquery表单验证插件
        $this->import_resource(array(
                                    'script' => 'jqtreetable.js',
                                    'style'  => 'res:style/jqtreetable.css'));
        $page['item_count'] = $this->_grade_mod->getCount();
        $this->_format_page($page);
        $this->assign('filtered', $conditions? 1 : 0); //是否有查询条件
        $this->assign('page_info', $page);

        $this->display('sgrade.index.html');
    }

    function add()
    {
        if (!IS_POST)
        {
            $this->assign('sgrade', array(
                'need_confirm' => 1,
                'sort_order'   => 255,
            ));
            $this->import_resource(array(
                                        'script' => 'jquery.plugins/jquery.validate.js'));
            $functions = $this->_get_functions();
            $this->assign('functions', $functions);
            $this->display('sgrade.form.html');
        }
        else
        {
            /* 检查名称是否已存在 */
            if (!$this->_grade_mod->unique(trim($_POST['grade_name'])))
            {
                $this->show_warning('name_exist');
                return;
            }

            $functions = isset($_POST['functions']) ? implode(',', $_POST['functions']) : '';
            $data = array(
                'grade_name'   => $_POST['grade_name'],
                'goods_limit'  => $_POST['goods_limit'],
                'space_limit'  => $_POST['space_limit'],
                'charge'       => $_POST['charge'],
                'need_confirm' => $_POST['need_confirm'],
                'description'  => $_POST['description'],
                'sort_order'   => $_POST['sort_order'],
                'functions'    => $functions,
            );

            $grade_id = $this->_grade_mod->add($data);
            if (!$grade_id)
            {
                $this->show_warning($this->_grade_mod->get_error());
                return;
            }

            $this->show_message('add_ok',
                'back_list',    'index.php?app=sgrade',
                'continue_add', 'index.php?app=sgrade&amp;act=add'
            );
        }
    }

    /* 检查登记名称的唯一性 */
    function check_grade()
    {
        $grade_name = empty($_GET['grade_name']) ? '' : trim($_GET['grade_name']);
        $grade_id   = empty($_GET['id']) ? 0 : intval($_GET['id']);
        if (!$grade_name)
        {
            echo ecm_json_encode(false);
            return ;
        }
        if ($this->_grade_mod->unique($grade_name, $grade_id))
        {
            echo ecm_json_encode(true);
        }
        else
        {
            echo ecm_json_encode(false);
        }
        return ;
    }

    function edit()
    {
        $id = empty($_GET['id']) ? 0 : intval($_GET['id']);
        if (!$id)
        {
            $this->show_warning('Hacking Attempt');
            return;
        }

        if (!IS_POST)
        {
            /* 是否存在 */
            $sgrade = $this->_grade_mod->get_info($id);
            if (!$sgrade)
            {
                $this->show_warning('sgrade_empty');
                return;
            }
            $checked_functions = $functions = array();
            $functions = $this->_get_functions();
            $tmp = explode(',', $sgrade['functions']);
            if ($functions)
            {
                foreach ($functions as $func)
                {
                    $checked_functions[$func] = in_array($func, $tmp);
                }
            }
            $this->assign('sgrade', $sgrade);
            $this->import_resource(array(
                                        'script' => 'jquery.plugins/jquery.validate.js'));
            $this->assign('functions', $functions);
            $this->assign('checked_functions', $checked_functions);
            $this->display('sgrade.form.html');
        }
        else
        {
            $functions = isset($_POST['functions']) ? implode(',', $_POST['functions']) : '';
            $data = array(
                'grade_name'   => $_POST['grade_name'],
                'goods_limit'  => $_POST['goods_limit'],
                'space_limit'  => $_POST['space_limit'],
                'charge'       => $_POST['charge'],
                'need_confirm' => $_POST['need_confirm'],
                'description'  => $_POST['description'],
                'sort_order'   => $_POST['sort_order'],
                'functions'    => $functions,
            );
            $this->_grade_mod->edit($id, $data);
            $this->show_message('edit_ok',
                'back_list',    'index.php?app=sgrade',
                'edit_again',   'index.php?app=sgrade&amp;act=edit&amp;id=' . $id
            );
        }
    }

    function drop()
    {
        $id = isset($_GET['id']) ? trim($_GET['id']) : '';
        if (!$id)
        {
            $this->show_warning('no_sgrade_to_drop');
            return;
        }

        $ids = explode(',', $id);
        $ids = array_diff($ids, array(1)); // 默认等级不能删除
        if (!$this->_grade_mod->drop($ids))
        {
            $this->show_warning($this->_grade_mod->get_error());
            return;
        }

        $store_mod =& m('store');
        $store_mod->edit("sgrade " . db_create_in($ids), array('sgrade' => 1));

        $this->show_message('drop_ok');
    }

    function set_skins()
    {
        $id = empty($_GET['id']) ? 0 : intval($_GET['id']);
        if (!$id)
        {
            $this->show_warning('Hacking Attempt');
            return;
        }

        if (!IS_POST)
        {
            $sgrade = $this->_grade_mod->get_info($id);
            if (!$sgrade)
            {
                $this->show_warning('sgrade_empty');
                return;
            }
            $available_skins = explode(',', $sgrade['skins']);

            $skins = $this->_get_skins();
            foreach ($skins as $key => $skin)
            {
                if (in_array($skin['value'], $available_skins))
                {
                    $skins[$key]['checked'] = 1;
                }
            }
            $this->assign('skins', $skins);

            $this->display('sgrade.skins.html');
        }
        else
        {
            $data = array(
                'skin_limit' => isset($_POST['skins']) ? count($_POST['skins']) : 1,
                'skins'      => isset($_POST['skins']) ? join(',', $_POST['skins']) : 'default|default',
            );
            $this->_grade_mod->edit($id, $data);
            $this->show_message('set_skins_ok',
                'back_list', 'index.php?app=sgrade');
        }
    }

    function _get_skins()
    {
        $skins = array();

        $layout_dir = ROOT_PATH . '/themes/store/';
        if (is_dir($layout_dir))
        {
            if ($ldh = opendir($layout_dir))
            {
                while (($lfile = readdir($ldh)) !== false)
                {
                    if ($lfile[0] != '.' && filetype($layout_dir . $lfile) == 'dir')
                    {
                        $skin_dir = $layout_dir . $lfile . '/styles/';
                        if (is_dir($skin_dir))
                        {
                            if ($sdh = opendir($skin_dir))
                            {
                                while (($sfile = readdir($sdh)) !== false)
                                {
                                    if ($sfile[0] != '.' && filetype($skin_dir . $sfile) == 'dir')
                                    {
                                        $skins[] = array(
                                            'value'     => $lfile . '|' . $sfile,
                                            'preview'   => 'themes/store/' . $lfile . '/styles/' . $sfile . '/preview.jpg',
                                            'screenshot'=> 'themes/store/' . $lfile . '/styles/' . $sfile . '/screenshot.jpg',
                                        );
                                    }
                                }
                                closedir($sdh);
                            }
                        }
                    }
                }
                closedir($ldh);
            }
        }

        return $skins;
    }

    /**
     *    获取可用功能列表
     *
     *    @author    Garbin
     *    @return    array
     */
    function _get_functions()
    {
        $arr = array();
        if (ENABLED_SUBDOMAIN)
        {
            $arr[] = 'subdomain';
        }
            $arr[] = 'editor_multimedia';
            $arr[] = 'coupon';
            $arr[] = 'groupbuy';
            $arr[] = 'enable_radar';

        return $arr;
    }
}

?>
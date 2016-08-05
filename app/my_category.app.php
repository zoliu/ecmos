<?php

define('MAX_LAYER', 2);

/* 店铺商品分类控制器 */
class My_categoryApp extends StoreadminbaseApp
{
    var $_gcategory_mod;

    /* 构造函数 */
    function __construct()
    {
        $this->My_categoryApp();
    }

    function My_categoryApp()
    {
        parent::__construct();

        $this->_gcategory_mod =& bm('gcategory', array('_store_id' => $this->visitor->get('manage_store')));
    }

    function index()
    {
        /* 取得商品分类 */
        $gcategories = $this->_gcategory_mod->get_list();
        $tree =& $this->_tree($gcategories);

        /* 先根排序 */
        $sorted_gcategories = array();
        $cate_ids = $tree->getChilds();
        foreach ($cate_ids as $id)
        {
            $sorted_gcategories[] = array_merge($gcategories[$id], array('layer' => $tree->getLayer($id)));
        }
        $this->assign('gcategories', $sorted_gcategories);

        /* 构造映射表（每个结点的父结点对应的行，从1开始） */
        $row = array(0 => 0); // cate_id对应的row
        $map = array(); // parent_id对应的row
        foreach ($sorted_gcategories as $key => $gcategory)
        {
            $row[$gcategory['cate_id']] = $key + 1;
            $map[] = $row[$gcategory['parent_id']];
        }
        $this->assign('map', ecm_json_encode($map));
        /* 当前页面信息 */
        $this->_curlocal(LANG::get('member_center'), 'index.php?app=member',
                         LANG::get('my_category'), 'index.php?app=my_category',
                         LANG::get('gcategory_list'));
        $this->_curitem('my_category');
        $this->_curmenu('gcategory_manage');
        $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('my_category'));
//        $this->import_resource(array(
//            'script' => 'jqtreetable.js,inline_edit.js',
//            'style'  => 'res:jqtreetable.css')
//        );

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
                          'path' => 'jqtreetable.js',
                          'attr' => '',
                       ),
                       array(
                          'path' => 'jquery.plugins/jquery.validate.js',
                          'attr' => '',
                       ),
                       array(
                          'path' => 'utils.js',
                          'attr' => '',
                       ),
              ),
              'style' =>  'jquery.ui/themes/ui-lightness/jquery.ui.css,res:css/jqtreetable.css',
            ));
        header("Content-Type:text/html;charset=" . CHARSET);
        $this->display('my_category.index.html');
    }

    function add()
    {
        if (!IS_POST)
        {
            /* 当前页面信息 */
            $this->_curlocal(LANG::get('member_center'), 'index.php?app=member',
                             LANG::get('my_category'), 'index.php?app=my_category',
                             LANG::get('gcategory_add'));
            $this->_curitem('my_category');
            $this->_curmenu('gcategory_manage');
            $this->_config_seo('title', Lang::get('member_center') . Lang::get('my_category'));

            $pid = empty($_GET['pid']) ? 0 : intval($_GET['pid']);
            $gcategory = array('parent_id' => $pid, 'sort_order' => 255, 'if_show' => 1);
            header('Content-Type:text/html;charset=' . CHARSET);
            $this->assign('gcategory', $gcategory);
            $this->import_resource(array(
                'script' => 'jquery.plugins/jquery.validate.js'
            ));
            $this->assign('parents', $this->_get_options());
            header("Content-Type:text/html;charset=" . CHARSET);
            $this->display('my_category.form.html');
        }
        else
        {
            $data = array(
                'cate_name'  => $_POST['cate_name'],
                'parent_id'  => $_POST['parent_id'],
                'sort_order' => $_POST['sort_order'],
                'if_show'    => $_POST['if_show'],
            );

            /* 检查名称是否已存在 */
            if (!$this->_gcategory_mod->unique(trim($data['cate_name']), $data['parent_id']))
            {
                $this->pop_warning('name_exist');
                return;
            }

            /* 保存 */
            $cate_id = $this->_gcategory_mod->add($data);
            if (!$cate_id)
            {
                $this->pop_warning($this->_gcategory_mod->get_error());
                return;
            }

            $this->pop_warning('ok', 'my_category_add');
        }
    }

    /* 检查分类名的唯一性 */
    function check_category ()
    {
        $cate_name = empty($_GET['cate_name']) ? '' : trim($_GET['cate_name']);
        $parent_id = empty($_GET['parent_id']) ? 0  : intval($_GET['parent_id']);
        $cate_id   = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if (!$cate_name)
        {
            echo ecm_json_encode(true);
            return ;
        }
        if ($this->_gcategory_mod->unique($cate_name, $parent_id, $cate_id))
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
        if (!IS_POST)
        {
            /* 是否存在 */
            $gcategory = $this->_gcategory_mod->get_info($id);
            if (!$gcategory)
            {
                echo Lang::get('gcategory_empty');
                return;
            }
            $this->assign('gcategory', $gcategory);

            $this->assign('parents', $this->_get_options($id));

            /* 当前页面信息 */
            $this->_curlocal(LANG::get('member_center'), 'index.php?app=member',
                             LANG::get('my_category'), 'index.php?app=my_category',
                             LANG::get('gcategory_edit'));
            $this->_curitem('my_category');
            $this->_curmenu('edit_category');
            $this->_config_seo('title', Lang::get('member_center') . Lang::get('my_category'));
            $this->import_resource(array(
                'script' => 'jquery.plugins/jquery.validate.js'
            ));
            header("Content-Type:text/html;charset=" . CHARSET);
            $this->display('my_category.form.html');
        }
        else
        {
            $data = array(
                'cate_name'  => $_POST['cate_name'],
                'parent_id'  => $_POST['parent_id'],
                'sort_order' => $_POST['sort_order'],
                'if_show'    => $_POST['if_show'],
            );

            /* 检查名称是否已存在 */
            if (!$this->_gcategory_mod->unique(trim($data['cate_name']), $data['parent_id'], $id))
            {
                $this->pop_warning('name_exist');
                return;
            }

            /* 保存 */
            $rows = $this->_gcategory_mod->edit($id, $data);
            if ($this->_gcategory_mod->has_error())
            {
                $this->pop_warning($this->_gcategory_mod->get_error());
                return;
            }

            $this->pop_warning('ok');
        }
    }

         //异步修改数据
       function ajax_col()
       {
           $id     = empty($_GET['id']) ? 0 : intval($_GET['id']);
           $column = empty($_GET['column']) ? '' : trim($_GET['column']);
           $value  = isset($_GET['value']) ? trim($_GET['value']) : '';
           $data   = array();
           if (in_array($column ,array('cate_name','if_show', 'sort_order')))
           {
               $data[$column] = $value;
               if ($column == 'cate_name')
               {
                   $gcategory = $this->_gcategory_mod->get_info($id);
                   if(!$this->_gcategory_mod->unique($value, $gcategory['parent_id'], $id))
                   {
                       $this->json_error('category name exist');
                       return ;
                   }
               }
               $this->_gcategory_mod->edit($id, $data);
               if(!$this->_gcategory_mod->has_error())
               {
                   $result = $this->_gcategory_mod->get_info($id);
                   $this->json_result($result[$column]);
               }
               else
               {
                   $this->json_error($this->_gcategory_mod->get_error());
                   return;
               }
           }
           else
           {
               $this->json_error('unallow edit');
               return;
           }
           return ;
       }
    function drop()
    {
        $id = isset($_GET['id']) ? trim($_GET['id']) : '';
        if (!$id)
        {
            $this->show_warning('no_gcategory_to_drop');
            return;
        }

        $ids = explode(',', $id);
        if (!$this->_gcategory_mod->drop($ids))
        {
            $this->show_warning($this->_gcategory_mod->get_error());
            return;
        }

        $this->show_message('drop_ok');
    }

    /* 导出数据 */
    function export()
    {
        // 目标编码
        $to_charset = (CHARSET == 'utf-8') ? substr(LANG, 0, 2) == 'sc' ? 'gbk' : 'big5' : '';

        if (!IS_POST)
        {
            if (CHARSET == 'utf-8')
            {
                $this->assign('note_for_export', sprintf(LANG::get('note_for_export'), $to_charset));

                /* 当前页面信息 */
                $this->_curlocal(LANG::get('member_center'), 'index.php?app=member',
                                 LANG::get('my_category'), 'index.php?app=my_category',
                                 LANG::get('export'));
                $this->_curitem('gcategory_manage');
                $this->_curmenu('export');
                $this->_config_seo('title', Lang::get('member_center') . Lang::get('my_category'));
                header("Content-Type:text/html;charset=" . CHARSET);
                $this->display('common.export.html');

                return;
            }
        }
        else
        {
            if (!$_POST['if_convert'])
            {
                $to_charset = '';
            }
        }

        $gcategories = $this->_gcategory_mod->get_list();
        $tree =& $this->_tree($gcategories);
        $this->export_to_csv($tree->getCSVData(), 'gcategory', $to_charset);
    }

    function csv_sample()
    {
        $to_charset = isset($_GET['charset']) ? trim($_GET['charset']) : '';
        if (!in_array($to_charset, array('utf-8', 'gbk', 'big5')))
        {
            $to_charset = 'utf-8';
        }
        $cates = array(
            array('韩版女装'),
            array('', '外套'),
            array('', '长裙'),
            array('', '女裤'),
            array('包包'),
            array('', '手提包'),
            array('', '皮夹钱包'),
            array('时尚女鞋'),
            array('', '气质单鞋'),
            array('', '运动休闲'),
       );

        $this->export_to_csv($cates, 'gcategory_' . $to_charset, $to_charset);

    }

    /* 导入数据 */
    function import()
    {
        if (!IS_POST)
        {
            $this->assign('note_for_import', sprintf(LANG::get('note_for_import'), CHARSET));

            /* 当前页面信息 */
            $this->_curlocal(LANG::get('member_center'), 'index.php?app=member',
                             LANG::get('my_category'), 'index.php?app=my_category',
                             LANG::get('import'));
            $this->_curitem('my_category');
            $this->_curmenu('import');
            $this->_config_seo('title', Lang::get('member_center') . Lang::get('my_category'));
            header("Content-Type:text/html;charset=" . CHARSET);
            $this->display('common.import.html');
        }
        else
        {
            $file = $_FILES['csv'];
            if ($file['error'] != UPLOAD_ERR_OK)
            {
                $this->pop_warning('select_file');
                return;
            }
            if ($file['name'] == basename($file['name'],'.csv'))
            {
                $this->pop_warning('not_csv_file');
                return;
            }

            $data = $this->import_from_csv($file['tmp_name'], false, $_POST['charset'], CHARSET);
            $parents = array(0 => 0); // 存放layer的parent的数组
            foreach ($data as $row)
            {
                $layer = -1;
                for ($i = 0; $i < count($row); $i++)
                {
                    if (trim($row[$i]))
                    {
                        $layer = $i;
                        $cate_name  = trim($row[$i]);
                        break;
                    }
                }

                // 没数据
                if ($layer < 0 || $layer >= MAX_LAYER)
                {
                    continue;
                }

                // 只处理有上级的
                if (isset($parents[$layer]))
                {
                    $gcategory = $this->_gcategory_mod->get("cate_name = '$cate_name' AND parent_id = '$parents[$layer]'");
                    if (!$gcategory)
                    {
                        // 不存在
                        $id = $this->_gcategory_mod->add(array(
                            'cate_name' => $cate_name,
                            'parent_id' => $parents[$layer],
                        ));
                        $parents[$layer + 1] = $id;
                    }
                    else
                    {
                        // 已存在
                        $parents[$layer + 1] = $gcategory['cate_id'];
                    }
                }
            }

            $this->pop_warning('ok');
        }
    }

    /* 构造并返回树 */
    function &_tree($gcategories)
    {
        import('tree.lib');
        $tree = new Tree();
        $tree->setTree($gcategories, 'cate_id', 'parent_id', 'cate_name');
        return $tree;
    }

    /* 取得可以作为上级的商品分类数据 */
    function _get_options($except = NULL)
    {
        $gcategories = $this->_gcategory_mod->get_list();
        $tree =& $this->_tree($gcategories);
        return $tree->getOptions(MAX_LAYER - 1, 0, $except);
    }

    function _get_member_submenu()
    {
        $menus = array(
            array(
                'name' => 'gcategory_manage',
                'url'  => 'index.php?app=my_category',
            ),
            );
        return $menus;
    }
}

?>
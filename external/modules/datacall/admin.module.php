<?php

class DatacallModule extends AdminbaseModule
{
    var $call_mod;
    var $type;
    var $_gcategory_mod;

    function __construct()
    {
        $this->DatacallModule();
    }

    function DatacallModule()
    {
        parent::__construct();

        $this->type = isset($_REQUEST['type']) ? intval($_REQUEST['type']) : 'goods';
        $this->call_mod =& af('datacall');
        $this->_gcategory_mod =& bm("gcategory");
    }

    function index()
    {
        $data = $this->call_mod->getAll();
        $this->assign('type', $this->type);
        $this->assign('data', $data);
        $this->display('datacall.index.html');
    }

    function add()
    {
        if (!IS_POST)
        {
            $search_options = array(
                'add_time'   => Lang::get('add_time'),
                'last_update'=> Lang::get('update_time'),
                'views' => Lang::get('views'),
                'sales' => Lang::get('sales'));
            $content_charset = array(
                '0'     => Lang::get('default_charset'),
                'utf-8' => 'UTF-8',
                'gbk'   => 'GBK',
                'big5'  => 'BIG5',
            );
            $sort_order_by = array(
                'desc' => Lang::get('desc'),
                'asc'  => Lang::get('asc'),
            );
            $data_call = array(
                'content_charset' => '0',
                'spe_data'        => array(
                    'cate_id' => 0,
                    'sort_order' => 'goods_id',
                    'asc_desc'   => 'desc',
                ),
            );
            $this->import_resource(array('script' => 'jquery.plugins/jquery.validate.js,mlselection.js'));
            $this->assign('mgcategories', $this->_get_mgcategory_options(0));
            $this->assign('content_charset', $content_charset);
            $this->assign('search', $search_options);
            $this->assign('sort_order_by', $sort_order_by);
            $this->assign('data_call', $data_call);
            $this->assign('parents', $this->_get_options());
            $this->assign('type', $this->type);
            $this->display('datacall.form.html');
        }
        else
        {
            $res = $this->handle_post_data($_POST);
            $data = array(
                'type'            => $this->type,
                'description'     => $res['same']['description'],
                'store_id'        => $res['same']['store_id'],
                'content_charset' => $res['same']['content_charset'],
                'cache_time'      => $res['same']['cache_time'],
                'amount'          => $res['same']['amount'],
                'name_length'     => $res['same']['name_length'],
                'header'          => $res['template']['header'],
                'body'               => $res['template']['body'],
                'footer'          => $res['template']['footer'],
                'spe_data'        => $res['unsame'],
            );
            if (!$call_id = $this->call_mod->add($data))
            {
                $this->show_warning($this->call_mod->get_error());

                return;
            }
            $this->show_message('add_datacall_successed',
                'continue_add', 'index.php?module=datacall&amp;act=add',
                'back_list',    'index.php?module=datacall');
        }
    }

    function edit()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if (empty($id))
        {
            $this->show_warning('no_such_data');
            return;
        }
        $data_call = $this->call_mod->getOne($id);
        $data_call['call_id'] = $id;
        if (!IS_POST)
        {
            $search_options = array(
                'add_time'   => Lang::get('add_time'),
                'last_update'=> Lang::get('update_time'),
                'views' => Lang::get('views'),
                'sales' => Lang::get('sales'));
            $content_charset = array(
                '0'     => Lang::get('default_charset'),
                'utf-8' => 'UTF-8',
                'gbk'   => 'GBK',
                'big5'  => 'BIG5',
            );
            $sort_order_by = array(
                'desc' => Lang::get('desc'),
                'asc'  => Lang::get('asc'),
            );
            $this->import_resource(array('script' => 'jquery.plugins/jquery.validate.js,mlselection.js'));
            $this->assign('mgcategories', $this->_get_mgcategory_options(0));
            $this->assign('search', $search_options);
            $this->assign('content_charset', $content_charset);
            $this->assign('sort_order_by', $sort_order_by);
            $this->assign('parents', $this->_get_options());
            $this->assign('data_call',$data_call);
            $this->assign('type', $this->type);
            $this->display('datacall.form.html');
        }
        else
        {
            $res = $this->handle_post_data($_POST);//print_r(unserialize($res['template']));exit;
             $data = array(
                'type'            => $this->type,
                'description'     => $res['same']['description'],
                'store_id'        => $res['same']['store_id'],
                'content_charset' => $res['same']['content_charset'],
                'cache_time'      => $res['same']['cache_time'],
                'amount'          => $res['same']['amount'],
                'name_length'     => $res['same']['name_length'],
                'header'          => $res['template']['header'],
                'body'               => $res['template']['body'],
                'footer'          => $res['template']['footer'],
                'spe_data'        => $res['unsame'],
            );

            $this->call_mod->setOne($id, $data);
            if ($this->call_mod->has_error())
            {
                $this->show_warning($this->call_mod->get_error());

                return;
            }
            if (file_exists(ROOT_PATH . '/temp/js/datacallcache'. $id . '.js'))
            {
                @unlink(ROOT_PATH . '/temp/js/datacallcache'. $id . '.js');
            }
            $this->show_message('edit_datacall_successed',
                'continue_edit', 'index.php?module=datacall&amp;act=edit&amp;id='.$id,
                'back_list',    'index.php?module=datacall');
        }
    }

    function handle_post_data($post)
    {
        $same = array(
            'description'     => trim($post['description']),
            'type'            => $this->type,
            'store_id'        => 0,
            'cache_time'      => empty($post['cache_time']) ? '' : intval(trim($post['cache_time'])),
            'content_charset' => empty($post['content_charset']) ? CHARSET : trim($post['content_charset']),
            'amount'          => empty($post['amount']) ? '' : intval($post['amount']),
            'name_length'     => empty($post['name_length']) ? '' : intval($post['name_length']),
        );
        $template = array(
            'header'          => stripcslashes(str_replace("\'",'\"',trim($post['template_header']))),
            'body'            => stripcslashes(str_replace("\'",'\"',trim($post['template_body']))),
            'footer'          => stripcslashes(str_replace("\'",'\"',trim($post['template_footer']))),
        );
        if ($this->type == 'goods')
        {
            $unsame = array(
                'goods_name' => trim($post['goods_name']),
                'cate_id'    => trim($post['cate_id']),
                'cate_name'  => trim($post['cate_name']),
                //'brand_name' => trim($post['brand_name']),
                'max_price'  => empty($post['max_price']) ? '' : floatval(trim($post['max_price'])),
                'min_price'  => empty($post['min_price']) ? '' : floatval(trim($post['min_price'])),
                'keywords'   => trim($post['keywords']),
                'sort_order' => trim($post['sort_order']),
                'asc_desc'   => trim($post['asc_desc']),
                //'recommend'  => trim($post['recommend']),
            );
        }
        return array('same' => $same, 'unsame' => $unsame, 'template' => $template);
    }

    function drop()
    {
        $call_ids = $this->filter_ids($_GET['id']);
        if (!$call_ids)
        {
            $this->show_warning('no_such_data');
            return;
        }
        foreach ($call_ids as $call_id){
            if (file_exists(ROOT_PATH . '/temp/js/datacallcache'. $call_id . '.js'))
            {
                @unlink(ROOT_PATH . '/temp/js/datacallcache'. $call_id . '.js');
            }
            $this->call_mod->drop($call_id);
        }
        $this->show_message('drop_data_successed');
    }

    function filter_ids($ids)
    {
         $ids = isset($ids) ? trim($ids) : '';
         if (!$ids)
         {
             return ;
         }
         $ids=explode(',',$ids);
         foreach ($ids as $key => $id){
            $ids[$key] = isset($id) ? intval($id) : 0;
         }
         $ids = array_unique($ids);
         $data_call = $this->call_mod->getAll();
         foreach ($ids as $value)
         {
             $data_call[$value] && $filter_ids[] = $value;
         }
         if ($filter_ids == null)
         {
             return ;
         }
        return $filter_ids;
      }

    function _get_mgcategory_options($parent_id = 0)
    {
        $res = array();
        $gcategories = $this->_gcategory_mod->get_list($parent_id, true);
        foreach ($gcategories as $gcategory)
        {
            $res[$gcategory['cate_id']] = $gcategory['cate_name'];
        }
        return $res;
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

        return $tree->getOptions(0, 0, $except);
    }
}

?>
<?php

define('THUMB_WIDTH', 300);
define('THUMB_HEIGHT', 300);
define('THUMB_QUALITY', 85);

/* 品牌申请状态 */
define('BRAND_PASSED', 1);
define('BRAND_REFUSE', 0);

/* 商品管理控制器 */
class My_goodsApp extends StoreadminbaseApp
{
    var $_goods_mod;
    var $_spec_mod;
    var $_image_mod;
    var $_uploadedfile_mod;
    var $_store_id;
    var $_brand_mod;
    var $_last_update_id;

    /* 构造函数 */
    function __construct()
    {
         $this->My_goodsApp();
    }

    function My_goodsApp()
    {
        parent::__construct();

        $this->_store_id  = intval($this->visitor->get('manage_store'));
        $this->_goods_mod =& bm('goods', array('_store_id' => $this->_store_id));
        $this->_spec_mod  =& m('goodsspec');
        $this->_image_mod =& m('goodsimage');
        $this->_uploadedfile_mod =& m('uploadedfile');
        $this->_brand_mod =& m('brand');
    }

    function index()
    {
        /* 取得店铺商品分类 */
        $this->assign('sgcategories', $this->_get_sgcategory_options());

        $conditions = $this->_get_conditions();
        $page = $this->_get_page();
        $page_nolimit = array();
        $goods_list = $this->_get_goods($conditions, $page);
        $all_goods = $this->_get_goods($conditions, $page_nolimit);      
        
        foreach ($goods_list as $key => $goods)
        {
            $goods_list[$key]['cate_name'] = $this->_goods_mod->format_cate_name($goods['cate_name']);
        }
        $this->assign('goods_list', $goods_list);

        $page['item_count'] = $this->_goods_mod->getCount();

        $this->_format_page($page);
        $this->assign('page_info', $page);
        $this->assign('order', $order);
        $this->assign('sort', $sort);
        $this->import_resource(array(
            'script' => array(
                array(
                    'path' => 'dialog/dialog.js',
                    'attr' => 'id="dialog_js" charset="utf-8"',
                ),
                array(
                    'path' => 'jquery.ui/jquery.ui.js',
                    'attr' => 'charset="utf-8"',
                ),
                array(
                    'path' => 'jquery.plugins/jquery.validate.js',
                    'attr' => 'charset="utf-8"',
                ),
                array(
                    'path' => 'utils.js',
                    'attr' => 'charset="utf-8"',
                ),
            ),
            'style' =>  'jquery.ui/themes/ui-lightness/jquery.ui.css',
      ));
        /* 当前页面信息 */
        $this->_curlocal(LANG::get('member_center'), 'index.php?app=member',
                         LANG::get('my_goods'), 'index.php?app=my_goods',
                         LANG::get('goods_list'));
        $this->_curitem('my_goods');
        $this->_curmenu('goods_list');
        //$this->import_resource(array('script' => 'utils.js'));
        $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('my_goods'));
        $this->assign('goods_ids', implode(',', array_keys($all_goods)));
        
        $store_mod  =& m('store');
        $store = $store_mod->get_info($this->_store_id);
        $this->assign('store', $store);
        $this->display('my_goods.index.html');
    }
    
    function truncate()
    {
        $id = isset($_POST['goods_ids']) ? trim($_POST['goods_ids']) : '';
        if (!$id)
        {
            $this->show_warning('no_goods_to_drop');
            return;
        }

        $ids = explode(',', $id);
        $this->_goods_mod->drop_data($ids);
        $rows = $this->_goods_mod->drop($ids);
        if ($this->_goods_mod->has_error())
        {
            $this->show_warning($this->_goods_mod->get_error());
            return;
        }

        $this->show_message(sprintf(Lang::get('truncate_ok'), $rows),
            'back_list', 'index.php?app=my_goods'
        );
    }
    
    function _get_goods($conditions, &$page)
    {
        if (intval($_GET['sgcate_id']) > 0)
        {
            $cate_mod =& bm('gcategory', array('_store_id' => $this->_store_id));
            $cate_ids = $cate_mod->get_descendant_ids(intval($_GET['sgcate_id']));
        }
        else
        {
            $cate_ids = 0;
        }

        // 标识有没有过滤条件
        if ($conditions != '1 = 1' || !empty($_GET['sgcate_id']))
        {
            $this->assign('filtered', 1);
        }

        //更新排序
        if (isset($_GET['sort']) && isset($_GET['order']))
        {
            $sort  = strtolower(trim($_GET['sort']));
            $order = strtolower(trim($_GET['order']));
            if (!in_array($order,array('asc','desc')))
            {
                $sort  = 'goods_id';
                $order = 'desc';
            }
        }
        else
        {
            $sort  = 'goods_id';
            $order = 'desc';
        }
        
        if ($page)
        {
            $limit = $page['limit'];
            $count = true;
        }
        else
        {
            $limit = '';
            $count = false;
        }

        /* 取得商品列表 */
        
        $goods_list = $this->_goods_mod->get_list(array(
            'conditions' => $conditions,
            'count' => $count,
            'order' => "$sort $order",
            'limit' => $limit,
        ), $cate_ids);
        return $goods_list;
    }
    
    function _get_conditions()
    {
        /* 搜索条件 */
        $conditions = "1 = 1";
        if (trim($_GET['keyword']))
        {
            $str = "LIKE '%" . trim($_GET['keyword']) . "%'";
            $conditions .= " AND (goods_name {$str} OR brand {$str} OR cate_name {$str})";
        }
        if ($_GET['character'])
        {
            switch ($_GET['character'])
            {
                case 'show':
                    $conditions .= " AND if_show = 1";
                    break;
                case 'hide':
                    $conditions .= " AND if_show = 0";
                    break;
                case 'closed':
                    $conditions .= " AND closed = 1";
                    break;
                case 'recommended':
                    $conditions .= " AND g.recommended = 1";
                    break;
            }
        }
        
        return $conditions;
    }

    function batch_edit()
    {
        if (!IS_POST)
        {
             /* 取得商品分类 */
             $this->assign('mgcategories', $this->_get_mgcategory_options(0)); // 商城分类第一级
             $this->assign('sgcategories', $this->_get_sgcategory_options());  // 店铺分类

             /* 当前页面信息 */
             $this->_curlocal(LANG::get('member_center'), 'index.php?app=member',
                              LANG::get('my_goods'), 'index.php?app=my_goods',
                              LANG::get('goods_add'));
             $this->_curitem('my_goods');
             $this->_curmenu('batch_edit');
             $this->_config_seo('title', Lang::get('member_center') . Lang::get('my_goods'));

             $this->headtag('<script type="text/javascript" src="{lib file=mlselection.js}"></script>');
             $this->import_resource(array(
                 'script' => array(
                     array(
                         'path' => 'mlselection.js',
                         'attr' => 'charset="utf-8"',
                     ),
                     array(
                         'path' => 'my_goods.js',
                         'attr' => 'charset="utf-8"',
                     ),
                     array(
                         'path' => 'jquery.plugins/jquery.validate.js',
                         'attr' => 'charset="utf-8"',
                     ),
                 ),
             ));
             $this->display('my_goods.batch.html');
        }
        else
        {
             $id = isset($_POST['id']) ? trim($_POST['id']) : '';
             if (!$id)
             {
                 $this->show_warning('Hacking Attempt');
                 return;
             }

             $ids = explode(',', $id);
             $ids = $this->_goods_mod->get_filtered_ids($ids); // 过滤掉非本店goods_id
             // edit goods
             $data = array();
             if ($_POST['cate_id'] > 0)
             {
                 if (!$this->_check_mgcate($_POST['cate_id']))
                 {
                     $this->show_warning('select_leaf_category');
                     return;
                 }
                 $data['cate_id'] = $_POST['cate_id'];
                 $data['cate_name'] = $_POST['cate_name'];
             }
             
             if (trim($_POST['brand']))
             {
                 $data['brand'] = trim($_POST['brand']);
             }
             if ($_POST['if_show'] >= 0)
             {
                 $data['if_show'] = $_POST['if_show'] ? 1 : 0;
             }
             if ($_POST['recommended'] >= 0)
             {
                 $data['recommended'] = $_POST['recommended'] ? 1 : 0;
             }
             if ($data)
             {
                 $this->_goods_mod->edit($ids, $data);
             }

             // edit category_goods
             $cate_ids = array();
             foreach ($_POST['sgcate_id'] as $cate_id)
             {
                 if ($cate_id)
                 {
                     $cate_ids[] = intval($cate_id);
                 }
             }
             $cate_ids = array_unique($cate_ids);
             if (!empty($cate_ids))
             {
                 foreach ($ids as $goods_id)
                 {
                     $this->_goods_mod->unlinkRelation('belongs_to_gcategory', $goods_id);
                     $this->_goods_mod->createRelation('belongs_to_gcategory', $goods_id, $cate_ids);
                 }
             }
             // edit goods_spec
             $sql = "";
             if ($_POST['price_change'])
             {
                 $delta_price = $this->_filter_price($_POST['price']); // 价格变化量
                 switch ($_POST['price_change'])
                 {
                     case 'change_to':
                         $sql .= "price = '" . $delta_price . "'";
                         break;
                     case 'inc_by':
                         $sql .= "price = price + '" . $delta_price . "'";
                         break;
                     case 'dec_by':
                         
                         $sql .= "price = IF((price - '" . $delta_price . "') <0 , 0, price - '" . $delta_price . "')";
                         break;
                 }
             }
             if ($sql)
             {
                 $this->_spec_mod->edit("goods_id" . db_create_in($ids), $sql);
                 $this->_goods_mod->edit($ids, $sql);
             }

             $sql = "";
             if ($_POST['stock_change'])
             {
                 $delta_stock = intval($_POST['stock']); // 库存变化量
                 switch ($_POST['stock_change'])
                 {
                     case 'change_to':
                         $sql .= "stock = '" . $delta_stock . "'";
                         break;
                     case 'inc_by':
                         $sql .= "stock = stock + '" . $delta_stock . "'";
                         break;
                     case 'dec_by':
                         $sql .= "stock = IF((stock - '" . $delta_stock . "') <0 , 0, stock - '" . $delta_stock . "')";
                         break;
                 }
             }
             if ($sql)
             {
                 $this->_spec_mod->edit("goods_id" . db_create_in($ids), $sql);
             }

             $ret_page = isset($_GET['ret_page']) ? intval($_GET['ret_page']) : 1;
             $this->show_message('edit_ok',
                 'back_list', 'index.php?app=my_goods&page=' . $ret_page);
        }
    }

    /* 检查商品分类：添加、编辑商品表单验证时用到 */
    function check_mgcate()
    {
        $cate_id = isset($_GET['cate_id']) ? intval($_GET['cate_id']) : 0;

        echo ecm_json_encode($this->_check_mgcate($cate_id));
    }

    function export_ubbcode()
    {
        $code = '';
        $crlf = "\n";
        $goods_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $goods_info = $this->_get_goods_info($goods_id);

        /* 默认图片 */
        $goods_info['default_image'] && $code .= '[img]' . SITE_URL . '/' . $goods_info['default_image'] . '[/img]' . $crlf;

        /* 商品名称 */
        $code .= '[b]' . Lang::get('goods_name') . ':[/b]' . addslashes($goods_info['goods_name']) . $crlf ;

        /* 品牌 */
        $goods_info['brand'] && $code .= '[b]' . Lang::get('brand_name') . ':[/b]' . addslashes($goods_info['brand']) . $crlf;

        /* 规格 */
        if ($goods_info['spec_qty'] == 0)
        {
            $code .= '[b]' . Lang::get('price') . ':[/b][color=Red]' . str_replace('&yen;', ' RMB', price_format($goods_info['price'])) . '[/color]' . $crlf;
        }
        elseif ($goods_info['spec_qty'] == 1 || $goods_info['spec_qty'] == 2)
        {
            $code .= '[b]' . Lang::get('price') . ':[/b]';
            foreach ($goods_info['_specs'] as $goods)
            {
                 $code .=  addslashes($goods['spec_1']) . '  ' . addslashes($goods['spec_2']) . '[color=Red]' . str_replace('&yen;', ' RMB', price_format($goods_info['price'])) . "[/color]\t";
            }
            $code .= $crlf;
        }

        /* 购买地址 */
        $url = SITE_URL . '/' . url('app=goods&id=' . $goods_info['goods_id']);
        $url = str_replace('&amp;', '&' , $url);
        $code .= '[b]' . Lang::get('buy_now') . ':[/b]' . '[url=' .$url . ']' . $url . '[/url]';
        $this->assign('code', $code);
        $this->assign('alert_code', str_replace("\n", '\\n', $code));

        header("Content-type:text/html;charset=" . CHARSET, true);
        $this->display('export_ubbcode.html');
    }

    /**
     * 检查商品分类（必选，且是叶子结点）
     *
     * @param   int     $cate_id    商品分类id
     * @return  bool
     */
    function _check_mgcate($cate_id)
    {
        if ($cate_id > 0)
        {
            $gcategory_mod =& bm('gcategory');
            $info = $gcategory_mod->get_info($cate_id);
            if ($info && $info['if_show'] && $gcategory_mod->is_leaf($cate_id))
            {
                return true;
            }
        }

        return false;
    }

    function add()
    {
        /* 检测支付方式、配送方式、商品数量等 */
        if (!$this->_addible()) {
            return;
        }

        if (!IS_POST)
        {
             /* 添加传给iframe空的id,belong*/
             $this->assign("id", 0);
             $this->assign("belong", BELONG_GOODS);

             $this->assign('goods', $this->_get_goods_info(0));

             /* 取得游离状的图片 */
             $goods_images =array();
             $desc_images =array();
             $uploadfiles = $this->_uploadedfile_mod->find(array(
                 'join' => 'belongs_to_goodsimage',
                 'conditions' => "belong=".BELONG_GOODS." AND item_id=0 AND store_id=".$this->_store_id,
                 'order' => 'add_time ASC'
             ));
             foreach ($uploadfiles as $key => $uploadfile)
             {
                 if ($uploadfile['goods_id'] == null)
                 {
                     $desc_images[$key] = $uploadfile;
                 }
                 else
                 {
                     $goods_images[$key] = $uploadfile;
                 }
             }

             $this->assign('goods_images', $goods_images);
             $this->assign('desc_images', $desc_images);
             /* 取得商品分类 */
             $this->assign('mgcategories', $this->_get_mgcategory_options(0)); // 商城分类第一级
             $this->assign('sgcategories', $this->_get_sgcategory_options());  // 店铺分类

             /* 当前页面信息 */
             $this->_curlocal(LANG::get('member_center'), 'index.php?app=member',
                              LANG::get('my_goods'), 'index.php?app=my_goods',
                              LANG::get('goods_add'));
             $this->_curitem('my_goods');
             $this->_curmenu('goods_add');
             $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('goods_add'));

             /* 商品图片批量上传器 */
             $this->assign('images_upload', $this->_build_upload(array(
                 'obj' => 'GOODS_SWFU',
                 'belong' => BELONG_GOODS,
                 'item_id' => 0,
                 'button_text' => Lang::get('bat_upload'),
                 'progress_id' => 'goods_upload_progress',
                 'upload_url' => 'index.php?app=swfupload&instance=goods_image',
                 'if_multirow' => 1,
             )));

             /* 编辑器图片批量上传器 */
             $this->assign('editor_upload', $this->_build_upload(array(
                 'obj' => 'EDITOR_SWFU',
                 'belong' => BELONG_GOODS,
                 'item_id' => 0,
                 'button_text' => Lang::get('bat_upload'),
                 'button_id' => 'editor_upload_button',
                 'progress_id' => 'editor_upload_progress',
                 'upload_url' => 'index.php?app=swfupload&instance=desc_image',
                 'if_multirow' => 1,
                 'ext_js' => false,
                 'ext_css' => false,
             )));

             $this->import_resource(array(
                 'script' => array(
                     array(
                         'path' => 'mlselection.js',
                         'attr' => 'charset="utf-8"',
                     ),
                     array(
                         'path' => 'jquery.plugins/jquery.validate.js',
                         'attr' => 'charset="utf-8"',
                     ),
                     array(
                         'path' => 'jquery.ui/jquery.ui.js',
                         'attr' => 'charset="utf-8"',
                     ),
                     array(
                         'path' => 'my_goods.js',
                         'attr' => 'charset="utf-8"',
                     ),
                     array(
                         'attr' => 'id="dialog_js" charset="utf-8"',
                         'path' => 'dialog/dialog.js',
                     ),
                 ),
                 'style' =>  'jquery.ui/themes/ui-lightness/jquery.ui.css',
             ));
             
             /* 所见即所得编辑器 */
             extract($this->_get_theme());
             $this->assign('build_editor', $this->_build_editor(array(
                 'name' => 'description',
                 'content_css' => SITE_URL . "/themes/store/{$template_name}/styles/{$style_name}" . '/shop.css', // for preview
             )));
             
             $this->display('my_goods.form.html');
        }
        else
        {
            /* 取得数据 */
            $data = $this->_get_post_data(0);
            /* 检查数据 */
            if (!$this->_check_post_data($data, 0))
            {
                $this->show_warning($this->get_error());
                return;
            }
            /* 保存数据 */
            if (!$this->_save_post_data($data, 0))
            {
                $this->show_warning($this->get_error());
                return;
            }
            $goods_info = $this->_get_goods_info($this->_last_update_id);
            if ($goods_info['if_show'])
            {
                $goods_url = SITE_URL . '/' . url('app=goods&id=' . $goods_info['goods_id']);
                $feed_images = array();
                $feed_images[] = array(
                    'url'   => SITE_URL . '/' . $goods_info['default_image'],
                    'link'  => $goods_url,
                );
                $this->send_feed('goods_created', array(
                    'user_id' => $this->visitor->get('user_id'),
                    'user_name' => $this->visitor->get('user_name'),
                    'goods_url' => $goods_url,
                    'goods_name' => $goods_info['goods_name'],
                    'images' => $feed_images
                ));
            }

            $this->show_message('add_ok',
                'back_list', 'index.php?app=my_goods',
                'continue_add', 'index.php?app=my_goods&amp;act=add'
            );
        }
    }

    function edit()
    {
        import('image.func');
        import('uploader.lib');
        $id = empty($_GET['id']) ? 0 : intval($_GET['id']);
        if (!IS_POST)
        {
            /* 传给iframe id */
            $this->assign('id', $id);
            $this->assign('belong', BELONG_GOODS);
            if(!$id || !($goods = $this->_get_goods_info($id)))
            {
                $this->show_warning('no_such_goods');
                return;
            }
            $goods['tags'] = trim($goods['tags'], ',');
            $this->assign('goods', $goods);
            /* 取到商品关联的图片 */
            $uploadedfiles = $this->_uploadedfile_mod->find(array(
                'fields' => "f.*,goods_image.*",
                'conditions' => "store_id=".$this->_store_id." AND belong=".BELONG_GOODS." AND item_id=".$id,
                'join'       => 'belongs_to_goodsimage',
                'order' => 'add_time ASC'
            ));
            $default_goods_images = array(); // 默认商品图片
            $other_goods_images = array(); // 其他商品图片
            $desc_images = array(); // 描述图片
            /*if (!empty($goods['default_image']))
            {
                   $goods_images
            }*/
            foreach ($uploadedfiles as $key => $uploadedfile)
            {
                if ($uploadedfile['goods_id'] == null)
                {
                    $desc_images[$key] = $uploadedfile;
                }
                else
                {
                    if (!empty($goods['default_image']) && ($uploadedfile['thumbnail'] == $goods['default_image']))
                    {
                        $default_goods_images[$key] = $uploadedfile;
                    }
                    else
                    {
                        $other_goods_images[$key] = $uploadedfile;
                    }
                }
            }

            $this->assign('goods_images', array_merge($default_goods_images, $other_goods_images));
            $this->assign('desc_images', $desc_images);

            /* 取得商品分类 */
            $this->assign('mgcategories', $this->_get_mgcategory_options(0)); // 商城分类第一级
            $this->assign('sgcategories', $this->_get_sgcategory_options());  // 店铺分类

            /* 当前页面信息 */
            $this->_curlocal(LANG::get('member_center'), 'index.php?app=member',
                             LANG::get('my_goods'), 'index.php?app=my_goods',
                             LANG::get('goods_list'));
            $this->_curitem('my_goods');
            $this->_curmenu('edit_goods');
            $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('edit_goods'));

            $this->import_resource(array(
                'script' => array(
                    array(
                         'path' => 'mlselection.js',
                         'attr' => 'charset="utf-8"',
                    ),
                    array(
                         'path' => 'jquery.plugins/jquery.validate.js',
                         'attr' => 'charset="utf-8"',
                    ),
                    array(
                         'path' => 'jquery.ui/jquery.ui.js',
                         'attr' => 'charset="utf-8"',
                    ),
                    array(
                         'path' => 'my_goods.js',
                         'attr' => 'charset="utf-8"',
                     ),
                    array(
                        'attr' => 'id="dialog_js" charset="utf-8"',
                        'path' => 'dialog/dialog.js',
                    ),
                ),
                'style' =>  'jquery.ui/themes/ui-lightness/jquery.ui.css',
            ));

            /* 商品图片批量上传器 */
            $this->assign('images_upload', $this->_build_upload(array(
                'obj' => 'GOODS_SWFU',
                'belong' => BELONG_GOODS,
                'item_id' => $id,
                'button_text' => Lang::get('bat_upload'),
                'progress_id' => 'goods_upload_progress',
                'upload_url' => 'index.php?app=swfupload&instance=goods_image',
                'if_multirow' => 1,
            )));

            /* 编辑器图片批量上传器 */
            $this->assign('editor_upload', $this->_build_upload(array(
                'obj' => 'EDITOR_SWFU',
                'belong' => BELONG_GOODS,
                'item_id' => $id,
                'button_text' => Lang::get('bat_upload'),
                'button_id' => 'editor_upload_button',
                'progress_id' => 'editor_upload_progress',
                'upload_url' => 'index.php?app=swfupload&instance=desc_image',
                'if_multirow' => 1,
                'ext_js' => false,
                'ext_css' => false,
            )));

            /* 所见即所得编辑器 */
            extract($this->_get_theme());
            $this->assign('build_editor', $this->_build_editor(array(
                'name' => 'description',
                'content_css' => SITE_URL . "/themes/store/{$template_name}/styles/{$style_name}" . '/shop.css', // for preview
            )));
             
            $this->display('my_goods.form.html');
        }
        else
        {
            /* 取得数据 */
            $data = $this->_get_post_data($id);

            /* 检查数据 */
            if (!$this->_check_post_data($data, $id))
            {
                $this->show_warning($this->get_error());
                return;
            }
            /* 保存商品 */
            if (!$this->_save_post_data($data, $id))
            {
                $this->show_warning($this->get_error());
                return;
            }

            $this->show_message('edit_ok',
                'back_list', 'index.php?app=my_goods',
                'edit_again', 'index.php?app=my_goods&amp;act=edit&amp;id=' . $id);
        }
    }

   function spec_edit()
   {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if (!IS_POST)
        {
            $goods_spec = $this->_goods_mod->findAll(array(
                'fields' => "this.goods_name,this.goods_id,this.spec_name_1,this.spec_name_2",
                'conditions' => "goods_id = $id",
                'include' => array('has_goodsspec' => array('order'=>'spec_id')),
            ));

            header('Content-Type:text/html;charset=' . CHARSET);
            $this->assign('goods', current($goods_spec));
            $this->display("spec_edit.html");
        }
        else
        {
            $data = $this->save_spec($_POST);
            if (empty($data))
            {
                $this->pop_warning('not_data');
            }
            $default_spec = array(); // 更新商品中默认规格的信息
            foreach ($data as $key => $val)
            {
                if (empty($default_spec))
                {
                    $default_spec = array('price' => $val['price']);
                }
                $this->_spec_mod->edit($key, $val);
            }
            $this->_goods_mod->edit($id, $default_spec);
            $this->pop_warning('ok', 'my_goods_spec_edit');
        }
   }

   function save_spec($spec)
   {
        $data = array();
        if (empty($spec['price']) || empty($spec['stock']))
        {
            return $data;
        }
        foreach ($spec['price'] as $key => $val)
        {
            $data[$key]['price'] = $this->_filter_price($val);
        }
        foreach ($spec['stock'] as $key => $val)
        {
            $data[$key]['stock'] = intval($val);
        }
        return $data;
   }
     //异步修改数据
   function ajax_col()
   {
       $id        = empty($_GET['id']) ? 0 : intval($_GET['id']);
       $column = empty($_GET['column']) ? '' : trim($_GET['column']);
       $value  = isset($_GET['value']) ? trim($_GET['value']) : '';
       $data      = array('goods' => array(),
                          'specs' => array(),
                          'cates' => array());
       if (in_array($column ,array('goods_name','description', 'cate_id', 'cate_name', 'brand', 'spec_qty','if_show','closed','recommended')))
       {
           $data['goods'][$column] = $value;
           $this->_goods_mod->edit($id, $data['goods']);
           if(!$this->_goods_mod->has_error())
           {
               $result = $this->_goods_mod->get_info($id);
               $this->json_result($result[$column]);
               return;
           }
           else
           {
               $this->json_error($this->_goods_mod->get_error());
               return;
           }
       }
       elseif (in_array($column, array('price', 'stock', 'sku')))
       {
           if ($column == 'price')
           {
               $value = $this->_filter_price($value);
           }
           elseif ($column == 'stock')
           {
               $value = intval($value);
           }
       
           $data['specs'][$column] = $value;
           $this->_spec_mod->edit("goods_id = $id", $data['specs']);
           if(!$this->_spec_mod->has_error())
           {
               $result = $this->_spec_mod->get("goods_id = $id");
               //修改商品表中默认的字段的价格
               if ($column == 'price')
               {
                    $this->_goods_mod->edit($id, $data['specs']);
               }
               $this->json_result($result[$column]);
               return;
           }
           else
           {
               $this->json_error($this->_spec_mod->get_error());
               return;
           }

       }
       else
       {
           $this->json_error('unallow edit');
           return ;
       }
   }

    function drop()
    {
        $id = isset($_GET['id']) ? trim($_GET['id']) : '';
        if (!$id)
        {
            $this->show_warning('no_goods_to_drop');
            return;
        }

        $ids = explode(',', $id);
        $this->_goods_mod->drop_data($ids);
        $rows = $this->_goods_mod->drop($ids);
        if ($this->_goods_mod->has_error())
        {
            $this->show_warning($this->_goods_mod->get_error());
            return;
        }

        $this->show_message('drop_ok');
    }

    function unicodeToUtf8($str,$order="little")
    {
        $utf8string ="";
        $n=strlen($str);
        for ($i=0;$i<$n ;$i++ )
        {
            if ($order=="little")
            {
                $val = str_pad(dechex(ord($str[$i+1])), 2, 0, STR_PAD_LEFT) .
                       str_pad(dechex(ord($str[$i])),      2, 0, STR_PAD_LEFT);
            }
            else
            {
                $val = str_pad(dechex(ord($str[$i])),      2, 0, STR_PAD_LEFT) .
                       str_pad(dechex(ord($str[$i+1])), 2, 0, STR_PAD_LEFT);
            }
            $val = intval($val,16); // 由于上次的.连接，导致$val变为字符串，这里得转回来。
            $i++; // 两个字节表示一个unicode字符。
            $c = "";
            if($val < 0x7F)
            { // 0000-007F
                $c .= chr($val);
            }
            elseif($val < 0x800)
            { // 0080-07F0
                $c .= chr(0xC0 | ($val / 64));
                $c .= chr(0x80 | ($val % 64));
            }
            else
            { // 0800-FFFF
                $c .= chr(0xE0 | (($val / 64) / 64));
                $c .= chr(0x80 | (($val / 64) % 64));
                $c .= chr(0x80 | ($val % 64));
            }
            $utf8string .= $c;
        }
        /* 去除bom标记 才能使内置的iconv函数正确转换 */
        if (ord(substr($utf8string,0,1)) == 0xEF && ord(substr($utf8string,1,2)) == 0xBB && ord(substr($utf8string,2,1)) == 0xBF)
        {
            $utf8string = substr($utf8string,3);
        }
        return $utf8string;
    }

           /* 导入淘宝助理数据 */
    function import_taobao()
    {
        $step = (isset($_GET['step']) && $_GET['step'] == 2) ? 2 : 1;
        /* 检测支付方式、配送方式、商品数量等 */
        if ($step == 1 && !$this->_addible()) {
            return;
        }
        if (!IS_POST)
        {
            $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('import_taobao'));
            $this->_curlocal(LANG::get('member_center'), 'index.php?app=member',
                             LANG::get('my_goods'), 'index.php?app=my_goods',
                             LANG::get('import_taobao'));
            $this->_curitem('my_goods');
            $this->_curmenu('import_taobao');

            $this->assign('build_upload', $this->_build_upload(array(
                'itme_id'                    => 0,
                'belong'                        => BELONG_GOODS,
                'image_file_type'      => 'gif|jpg|jpeg|png|tbi',
                'upload_url'              => 'index.php?app=swfupload&act=taobao_image',
            ))); // 构建swfupload上传组件
            $this->import_resource(array(
                'script' => array(
                    array(
                        'path' => 'mlselection.js',
                        'attr' => 'charset="utf-8"',
                    ),
                    array(
                        'path' => 'my_goods.js',
                        'attr' => 'charset="utf-8"',
                    ),
                ),
                ));
            /* 取得商品分类 */
            $this->assign('mgcategories', $this->_get_mgcategory_options(0)); // 商城分类第一级
            $this->assign('sgcategories', $this->_get_sgcategory_options());  // 店铺分类
            $this->assign('step', $step);
            $this->display('import.taobao.html');
        }
        else
        {
            $file = $_FILES['csv'];
            if ($file['error'] != UPLOAD_ERR_OK)
            {
                $this->show_warning('select_file');
                return;
            }
            import('uploader.lib'); // 导入上传类
            $uploader = new Uploader();
            $uploader->allowed_type('csv'); // 限制文件类型
            $uploader->allowed_size(SIZE_CSV_TAOBAO); // 限制单个文件大小2M
            $uploader->addFile($file);
            if (!$uploader->file_info())
            {
                $this->show_warning($uploader->get_error());
                return;
            }

            if (!$this->_check_mgcate($_POST['cate_id']))
            {
                $this->show_warning('select_leaf_category');
                return;
            }

            /* 取得还能上传的商品数，false表示不限制 */
            $store_mod =& m('store');
            $settings  = $store_mod->get_settings($this->_store_id);
            $remain       = $settings['goods_limit'] > 0 ? $settings['goods_limit'] - $this->_goods_mod->get_count() : false;

            /* 初始化统计 */
            $num_image = 0; // 需要导入的图片数量
            $num_record = 0; // 成功导入的记录条数

            $csv_string = $this->unicodeToUtf8(file_get_contents($file['tmp_name']));
            //$csv_string = addslashes($csv_string); // 必须在转码后进行引号转义

            $records = $this->_parse_taobao_csv($csv_string);
            if ($this->has_error())
            {
                $this->show_warning($this->get_error());
                return;
            }
            if (CHARSET =='big5')
            {
                $records = ecm_iconv_deep('utf-8', 'gbk', $records);//dump($chs);
                $records = ecm_iconv_deep('gbk', 'big5', $records);
            }
            else
            {
                $records = ecm_iconv_deep('utf-8', CHARSET, $records);
            }
            foreach ($records as $record)
            {
                // 如果商品名称为空则跳过
                if (!trim($record['goods_name']) || $find_goods)
                {
                    continue;
                }

                if ($remain !== false) // 如果店铺等级有商品数量限制
                {
                    if ($remain <= 0)
                    {
                        if ($num_record == 0) // 还没有导入商品数就超过限制了
                        {
                            $this->show_warning('goods_limit_arrived');
                            return;
                        }
                        else // 导入部分商品时超限
                        {
                            if ($num_image>0) // 需要上传图片
                            {
                                $this->show_message(sprintf(Lang::get('import_part_ok_need_image'), $num_record, $num_image),
                                'upload_taobao_image', 'index.php?app=my_goods&act=import_taobao&step=2');
                            }
                            else // 不需要上传图片
                            {
                                $this->show_message(sprintf(Lang::get('import_part_ok'), $num_record),
                                'back_list', 'index.php?app=my_goods');
                            }
                        }
                        exit();
                    }
                    else
                    {
                        if ($record['goods_image'])
                        {
                               $num_image += $record['image_count'];
                        }
                        $remain--;
                    }
                }
                else
                {
                    if ($record['goods_image']) // 店铺等级无商品数量限制
                    {
                        $num_image += $record['image_count'];
                    }
                }

                $goods = array(
                    'type'                   => 'material',
                    'brand'                   => '',
                    'cate_id'             => $_POST['cate_id'],
                    'cate_name'        => $_POST['cate_name'],
                    'spec_qty'            => 0,
                    'goods_name'       => $record['goods_name'],
                    'store_id'            => $this->_store_id,
                    'description'      => $record['description'],
                    'if_show'             => $record['if_show'],
                    'add_time'            => gmtime(),
                    'last_update'      => gmtime(),
                    'recommended'      => $record['recommended'],
                    'default_image' => $record['goods_image'],
                    'closed'              => 0,
                );
                $goods_id = $this->_goods_mod->add($goods);
                if ($this->_goods_mod->has_error())
                {
                    $this->show_warning($this->_goods_mod->get_error());
                    return;
                }

                /* 商品分类 */
                if ($_POST['sgcate_id'])
                {
                    $this->_goods_mod->createRelation('belongs_to_gcategory', $goods_id, $_POST['sgcate_id']);
                }

                /* 规格 */

                $spec_qty = 0;

                if ($record['sale_attr']) // 有规格
                {
                    $spec_info = $this->_parse_tabao_prop($record['cid'], $record['sale_attr'], $record['sale_attr_alias'] ,$goods_id); //dump($spec_info);
                    //dump($spec_info);
                    if (isset($spec_info['msg']))
                    {
                        $this->show_warning($prop['msg']);
                        return;
                    }
                    if ($spec_info)
                    {
                        $spec_data = $spec_info['item'];
                        $spec_qty  = $spec_info['spec_kind'];
                        $spec_name = $spec_info['spec_name'];
                    }
                    if ($spec_qty > 2 || !$spec_info)
                    { // 有两个以上规格或淘宝接口没有获取到属性，视无规格处理
                        $spec_qty = 0;
                        $spec_data = array();
                        $spec_data[0] = array(
                               'goods_id' => $goods_id,
                               'price'    => $this->_filter_price($record['price']),
                               'stock'    => intval($record['stock']),
                        );
                        $spec_name =array();
                    }
                }
                else // 没有规格
                {
                    $spec_data[0] = array(
                        'goods_id' => $goods_id,
                        'price'       => $this->_filter_price($record['price']),
                        'stock'       => intval($record['stock']),
                    );
                    $spec_name =array();
                }

                $default_spec = array(); // 初始化默认规格

                foreach ($spec_data as $spec)
                {
                    $spec['goods_id'] = $goods_id;
                    $spec_id = $this->_spec_mod->add($spec);
                    if (!$spec_id)
                    {
                        $this->_error($this->_spec_mod->get_error());
                        return false;
                    }
                    if (empty($default_spec))
                    {
                        $default_spec = array('default_spec' => $spec_id, 'price' => $spec['price']);
                    }
                }

                if (!$this->_goods_mod->edit($goods_id, array_merge($spec_name, $default_spec, array('spec_qty' => $spec_qty))))
                {
                    $this->_error($this->_goods_mod->get_error());
                    return false;
                }
                $num_record ++;
            }

            if ($num_image>0)
            {
                $this->show_message(sprintf(Lang::get('import_ok_need_image'), $num_record, $num_image),
                'upload_taobao_image', 'index.php?app=my_goods&act=import_taobao&step=2');
            }
            else
            {
                $this->show_message(sprintf(Lang::get('import_ok'), $num_record),
                'back_list', 'index.php?app=my_goods');
            }

        }
    }
    
    /* 需要导入的字段在CSV中显示的名称 */
    function _taobao_fields()
    {
        return array(
            'goods_name'  => '宝贝名称',
            'cid'         => '宝贝类目',
            'price'       => '宝贝价格',
            'stock'       => '宝贝数量',
            'if_show'     => '放入仓库',
            'recommended' => '橱窗推荐',
            'description' => '宝贝描述',
            'goods_image' => '新图片',
            'sale_attr'   => '销售属性组合',
            'sale_attr_alias' => '销售属性别名'
        );
    }
    
    /* 每个字段所在CSV中的列序号，从0开始算 */
    function _taobao_fields_cols($title_arr, $import_fields)
    {
        $fields_cols = array();
        foreach ($import_fields as $k => $field)
        {
            $pos = array_search($field, $title_arr);
            if ($pos !== false)
            {
                $fields_cols[$k] = $pos;
            }
        }
        return $fields_cols;
    }

    /* 解析淘宝助理CSV数据 */
    function _parse_taobao_csv($csv_string)
    {
        /* 定义CSV文件中几个标识性的字符的ascii码值 */
        define('ORD_SPACE', 32); // 空格
        define('ORD_QUOTE', 34); // 双引号
        define('ORD_TAB',    9); // 制表符
        define('ORD_N',     10); // 换行\n
        define('ORD_R',     13); // 换行\r
        
        /* 字段信息 */
        $import_fields = $this->_taobao_fields(); // 需要导入的字段在CSV中显示的名称
        $fields_cols = array(); // 每个字段所在CSV中的列序号，从0开始算
        $csv_col_num = 0; // csv文件总列数
        
        $pos = 0; // 当前的字符偏移量
        $status = 0; // 0标题未开始 1标题已开始
        $title_pos = 0; // 标题开始位置
        $records = array(); // 记录集
        $field = 0; // 字段号
        $start_pos = 0; // 字段开始位置
        $field_status = 0; // 0未开始 1双引号字段开始 2无双引号字段开始
        $line =0; // 数据行号
        while($pos < strlen($csv_string))
        {
            $t = ord($csv_string[$pos]); // 每个UTF-8字符第一个字节单元的ascii码
            $next = ord($csv_string[$pos + 1]);
            $next2 = ord($csv_string[$pos + 2]);
            $next3 = ord($csv_string[$pos + 3]);

            if ($status == 0 && !in_array($t, array(ORD_SPACE, ORD_TAB, ORD_N, ORD_R)))
            {
                $status = 1;
                $title_pos = $pos;
            }
            if ($status == 1)
            {
                if ($field_status == 0 && $t== ORD_N)
                {
                    static $flag = null;
                    if ($flag === null)
                    {
                        $title_str = substr($csv_string, $title_pos, $pos - $title_pos);
                        $title_arr = explode("\t", trim($title_str));
                        $fields_cols = $this->_taobao_fields_cols($title_arr, $import_fields);
                        if (count($fields_cols) != count($import_fields))
                        {
                            $this->_error('csv_fields_error'); // 欲导入的字段列数跟实际CSV文件中列数不符
                            return false;
                        }
                        $csv_col_num = count($title_arr); // csv总列数
                        $flag = 1;
                    }
                    
                    if ($next == ORD_QUOTE)
                    {
                        $field_status = 1; // 引号数据单元开始
                        $start_pos = $pos = $pos + 2; // 数据单元开始位置(相对\n偏移+2)
                    }
                    else
                    {
                        $field_status = 2; // 无引号数据单元开始
                        $start_pos = $pos = $pos + 1; // 数据单元开始位置(相对\n偏移+1)
                    }
                    continue;
                }

                if($field_status == 1 && $t == ORD_QUOTE && in_array($next, array(ORD_N, ORD_R, ORD_TAB))) // 引号+换行 或 引号+\t
                {
                    $records[$line][$field] = addslashes(substr($csv_string, $start_pos, $pos - $start_pos));
                    $field++;
                    if ($field == $csv_col_num)
                    {
                        $line++;
                        $field = 0;
                        $field_status = 0;
                        continue;
                    }
                    if (($next == ORD_N && $next2 == ORD_QUOTE) || ($next == ORD_TAB && $next2 == ORD_QUOTE) || ($next == ORD_R && $next2 == ORD_QUOTE))
                    {
                        $field_status = 1;
                        $start_pos = $pos = $pos + 3;
                        continue;
                    }
                    if (($next == ORD_N && $next2 != ORD_QUOTE) || ($next == ORD_TAB && $next2 != ORD_QUOTE) || ($next == ORD_R && $next2 != ORD_QUOTE))
                    {
                        $field_status = 2;
                        $start_pos = $pos = $pos + 2;
                        continue;
                    }
                    if ($next == ORD_R && $next2 == ORD_N && $next3 == ORD_QUOTE)
                    {
                        $field_status = 1;
                        $start_pos = $pos = $pos + 4;
                        continue;
                    }
                    if ($next == ORD_R && $next2 == ORD_N && $next3 != ORD_QUOTE)
                    {
                        $field_status = 2;
                        $start_pos = $pos = $pos + 3;
                        continue;
                    }
                }
                
                if($field_status == 2 && in_array($t, array(ORD_N, ORD_R, ORD_TAB))) // 换行 或 \t
                {
                    $records[$line][$field] = addslashes(substr($csv_string, $start_pos, $pos - $start_pos));
                    $field++;
                    if ($field == $csv_col_num)
                    {
                        $line++;
                        $field = 0;
                        $field_status = 0;
                        continue;
                    }
                    if (($t == ORD_N && $next == ORD_QUOTE) || ($t == ORD_TAB && $next == ORD_QUOTE) || ($t == ORD_R && $next == ORD_QUOTE))
                    {
                        $field_status = 1;
                        $start_pos = $pos = $pos + 2;
                        continue;
                    }
                    if (($t == ORD_N && $next != ORD_QUOTE) || ($t == ORD_TAB && $next != ORD_QUOTE) || ($t == ORD_R && $next != ORD_QUOTE))
                    {
                        $field_status = 2;
                        $start_pos = $pos = $pos + 1;
                        continue;
                    }
                    if ($t == ORD_R && $next == ORD_N && $next2 == ORD_QUOTE)
                    {
                        $field_status = 1;
                        $start_pos = $pos = $pos + 3;
                        continue;
                    }
                    if ($t == ORD_R && $next == ORD_N && $next2 != ORD_QUOTE)
                    {
                        $field_status = 2;
                        $start_pos = $pos = $pos + 2;
                        continue;
                    }
                }
            }

            if($t > 0 && $t <= 127) {
                $pos++;
            } elseif(192 <= $t && $t <= 223) {
                $pos += 2;
            } elseif(224 <= $t && $t <= 239) {
                $pos += 3;
            } elseif(240 <= $t && $t <= 247) {
                $pos += 4;
            } elseif(248 <= $t && $t <= 251) {
                $pos += 5;
            } elseif($t == 252 || $t == 253) {
                $pos += 6;
            } else {
                $pos++;
            }
        }
        $return = array();
        foreach ($records as $key => $record)
        {
            foreach ($record as $k => $col)
            {
                $col = trim($col); // 去掉数据两端的空格
                /* 对字段数据进行分别处理 */
                switch ($k)
                {
                    case $fields_cols['description'] :  $return[$key]['description'] = str_replace(array("\\\"\\\"", "\"\""), array("\\\"", "\""), $col); break;
                    case $fields_cols['goods_image'] :  $result = $this->_parse_taobao_image($col); $return[$key]['goods_image'] = $result['data']; $return[$key]['image_count'] = $result['count'];break;
                    case $fields_cols['if_show'] :      $return[$key]['if_show'] = $col == 1 ? 0 : 1; break;
                    case $fields_cols['goods_name'] :   $return[$key]['goods_name'] = $col; break;
                    case $fields_cols['stock'] :        $return[$key]['stock'] = $col; break;
                    case $fields_cols['price']:         $return[$key]['price'] = $col; break;
                    case $fields_cols['recommended'] :  $return[$key]['recommended'] = $col; break;
                    case $fields_cols['sale_attr'] :    $return[$key]['sale_attr'] = $col; break;
                    case $fields_cols['sale_attr_alias'] :    $return[$key]['sale_attr_alias'] = $col; break;
                    case $fields_cols['cid'] :          $return[$key]['cid'] = $col; break;
                }
            }
        }
        return $return;
    }

    /* 解析淘宝的销售属性 返回ECMall规格 */
    function _parse_tabao_prop($cid, $sale_attr, $sale_attr_alias, $goods_id)
    {
        $i = 0; // 规格数量
        $spec_kind = 0; // 规格种类数
        $spec_price_stock = array(); // 价格和库存
        $sale_attr = preg_replace("/:[^:]*-[^:]*:/U", '::' , $sale_attr); // 屏蔽商家编码干扰
        $sale_attr = explode(';', $sale_attr);//dump($sale_attr);
        $pvs = ''; // 淘宝销售属性编码
        /* 分离库存价格与属性编码 */
        foreach ($sale_attr as $k => $v)
        {
            $pos_2 = strpos($v, '::');
            if ($pos_2>0)
            {
                $pos_1 = strpos($v, ':'); //dump($_pos);
                //$price_stock = explode(':', substr($v, 0,))
                $spec_price_stock[$i]['price'] = round(substr($v, 0, $pos_1), 2);
                $spec_price_stock[$i]['stock'] = substr($v, $pos_1 + 1, $pos_2 - $pos_1 - 1);
                $pvs .= substr($v, $pos_2 + 2) . ';';
                $i++;
            }
            else if ($v)
            {
                $pvs .= $v . ';';
            }
        }
        if (empty($spec_price_stock))
        {
            $spec_kind = 0;
        }
        else
        {
            $spec_kind = substr_count($pvs, ';') / count($spec_price_stock);
        }
        /* 根据编码解析销售属性 */
        import('taobaoprop.lib');
        $TaobaoProp = new TaobaoProp($cid, $pvs, '12009827', '8c02e9f524f66199e100e27fdfdb9bbd');
        $prop = $TaobaoProp->get_prop();
        if (!$prop || $TaobaoProp->has_error())
        {
            return array();
        }
        
        /* 编码转换 */
        if (CHARSET == 'big5')
        {
            $prop = ecm_iconv_deep('utf-8', 'gbk', $prop);
            $prop = ecm_iconv_deep('gbk', 'big5', $prop);
        }
        else
        {
            $prop = ecm_iconv_deep('utf-8', CHARSET, $prop);
        }
        
        /* 销售属性别名 */
        if ($sale_attr_alias)
        {
            $sale_attr_alias = explode(';', $sale_attr_alias);
            foreach ($sale_attr_alias as $_k => $_v)
            {
                $pos_delimiter = strrpos($_v, ':');
                $pv = substr($_v, 0, $pos_delimiter);
                $alias_name = substr($_v, $pos_delimiter + 1);
                $sale_attr_alias[$pv] = $alias_name;
                unset($sale_attr_alias[$_k]);
            }
            foreach ($prop as $key => $value)
            {
                $pv = $value['pid'] . ':' . $value['vid'];
                if (isset($sale_attr_alias[$pv]))
                {
                    $prop[$key]['name_alias'] = $sale_attr_alias[$pv];
                }
            }
        }

        /* 组合成ECMall规格 */
        $spec = array(); // 规格数据
        foreach ($spec_price_stock as $_k => $_v)
        {
            $spec['item'][$_k] = $_v;
            $spec['item'][$_k]['goods_id'] = $goods_id;
            if ($spec_kind == 2)
            {
                $spec['item'][$_k]['spec_1'] = $prop[2 * $_k]['name_alias'];
                $spec['item'][$_k]['spec_2'] = $prop[2 * $_k + 1]['name_alias'];
                $spec['spec_name'] = array(
                    'spec_name_1' => $prop[0]['prop_name'],
                    'spec_name_2' => $prop[1]['prop_name'],
                );
            }
            else if ($spec_kind = 1)
            {
                $spec['item'][$_k]['spec_1'] = $prop[$_k]['name_alias'];
                $spec['spec_name'] = array(
                    'spec_name_1' => $prop[0]['prop_name'],
                );
            }
            if ($_v['stock'] == 0)
            {
                unset($spec['item'][$_k]);
            }
        }
        $spec['spec_kind'] = $spec_kind;
        return addslashes_deep($spec); // 因经过转码，必须要重新转义
    }
    
    function _parse_taobao_image($col)
    {
        /* 初始化返回值返回值 */
        $data = ''; // 以分号分隔的图片数据
        $count = 0; // 图片张数
        
        /* 组织成数组 */
        $temp_attr = $col ? explode(';', trim($col, ';')) : array();
        
        /* 遍历去掉多余符号 超过255字节部分的图片去掉 */
        $len = 0;
        foreach ($temp_attr as $k => $v)
        {
            $image = substr($v, 0, strpos($v, ':'));
            if (($pos = strpos($image, '.')) !==false)
            {
                $image = substr($image, 0, $pos); //图片文件名是5ee3678fe8815fd09c67fcbb1e1d5ebc.jpg.tbi这种情况
            }
            if (strlen($image) + $len + 1 > 255)
            {
                break; // 超过字段字符数
            }
            else
            {
                /* 去重、统计图片 */
                if ($image && strpos($data, $image) === false) 
                {
                    $data .=  $image . ';';
                    $count++;
                }
            }
        }
        return array('count' => $count, 'data' => $data);
    }

    function drop_image()
    {
        $id = empty($_GET['id']) ? 0 : intval($_GET['id']);
        $uploadedfile = $this->_uploadedfile_mod->get(array(
                  'conditions' => "f.file_id = '$id' AND f.store_id = '{$this->_store_id}'",
                  'join' => 'belongs_to_goodsimage',
                  'fields' => 'goods_image.image_url, goods_image.thumbnail, goods_image.image_id, f.file_id',
        ));
        if ($uploadedfile)
        {
            $this->_uploadedfile_mod->drop($id);
            if ($this->_image_mod->drop($uploadedfile['image_id']))
            {
                // 删除文件
                if (file_exists(ROOT_PATH . '/' . $uploadedfile['image_url']))
                {
                       @unlink(ROOT_PATH . '/' . $uploadedfile['image_url']);
                }
                if (file_exists(ROOT_PATH . '/' . $uploadedfile['thumbnail']))
                {
                       @unlink(ROOT_PATH . '/' . $uploadedfile['thumbnail']);
                }

                $this->json_result($id);
                return;
            }
            $this->json_result($id);
            return;
        }
        $this->json_error(Lang::get('no_image_droped'));
    }

    function _get_member_submenu()
    {
        if (ACT == 'index')
        {
            $menus = array(
                array(
                    'name' => 'goods_list',
                    'url'  => 'index.php?app=my_goods',
                ),
                 array(
                     'name' => 'goods_add',
                     'url'  => 'index.php?app=my_goods&amp;act=add',
                 ),
                array(
                    'name' => 'brand_apply_list',
                    'url' => 'index.php?app=my_goods&amp;act=brand_list'
                ),
            );
        }
        else
        {
             $menus = array(
                 array(
                     'name' => 'goods_list',
                     'url'  => 'index.php?app=my_goods',
                 ),
                 array(
                     'name' => 'goods_add',
                     'url'  => 'index.php?app=my_goods&amp;act=add',
                 ),
                 array(
                     'name' => 'import_taobao',
                     'url'  => 'index.php?app=my_goods&amp;act=import_taobao',
                 ),
                 array(
                    'name' => 'brand_apply_list',
                    'url' => 'index.php?app=my_goods&amp;act=brand_list'
                ),
             );
        }
        if (ACT == 'batch_edit')
        {
            $menus[] = array(
                'name' => 'batch_edit',
                'url'  => '',
            );
        }
        elseif (ACT == 'edit')
        {
            $menus[] = array(
                'name' => 'edit_goods',
                'url'  => '',
            );
        }
        elseif (ACT == 'brand_list')
        {
            $menus = array(
                array(
                    'name' => 'goods_list',
                    'url'  => 'index.php?app=my_goods',
                ),
                array(
                    'name' => 'brand_apply_list',
                    'url' => 'index.php?app=my_goods&amp;act=brand_list'
                ),
            );
        }
        return $menus;
    }

    /* 构造并返回树 */
    function &_tree($gcategories)
    {
        import('tree.lib');
        $tree = new Tree();
        $tree->setTree($gcategories, 'cate_id', 'parent_id', 'cate_name');
        return $tree;
    }

    /* 取得本店所有商品分类 */
    function _get_sgcategory_options()
    {
        $mod =& bm('gcategory', array('_store_id' => $this->_store_id));
        $gcategories = $mod->get_list();
        import('tree.lib');
        $tree = new Tree();
        $tree->setTree($gcategories, 'cate_id', 'parent_id', 'cate_name');
        return $tree->getOptions();
    }

    /* 取得商城商品分类，指定parent_id */
    function _get_mgcategory_options($parent_id = 0)
    {
        $res = array();
        $mod =& bm('gcategory', array('_store_id' => 0));
        $gcategories = $mod->get_list($parent_id, true);
        foreach ($gcategories as $gcategory)
        {
                  $res[$gcategory['cate_id']] = $gcategory['cate_name'];
        }
        return $res;
    }

    /**
     * 上传商品图片
     *
     * @param int $goods_id
     * @return bool
     */
    function _upload_image($goods_id)
    {
        import('image.func');
        import('uploader.lib');
        $uploader = new Uploader();
        $uploader->allowed_type(IMAGE_FILE_TYPE);
        $uploader->allowed_size(SIZE_GOODS_IMAGE); // 400KB

        /* 取得剩余空间（单位：字节），false表示不限制 */
        $store_mod  =& m('store');
        $settings      = $store_mod->get_settings($this->_store_id);
        $upload_mod =& m('uploadedfile');
        $remain        = $settings['space_limit'] > 0 ? $settings['space_limit'] * 1024 * 1024 - $upload_mod->get_file_size($this->_store_id) : false;

        $files = $_FILES['new_file'];
        foreach ($files['error'] as $key => $error)
        {
            if ($error == UPLOAD_ERR_OK)
            {
                /* 处理文件上传 */
                $file = array(
                    'name'            => $files['name'][$key],
                    'type'            => $files['type'][$key],
                    'tmp_name'  => $files['tmp_name'][$key],
                    'size'            => $files['size'][$key],
                    'error'        => $files['error'][$key]
                );
                $uploader->addFile($file);
                if (!$uploader->file_info())
                {
                    $this->_error($uploader->get_error());
                    return false;
                }

                /* 判断能否上传 */
                if ($remain !== false)
                {
                    if ($remain < $file['size'])
                    {
                        $this->_error('space_limit_arrived');
                        return false;
                    }
                    else
                    {
                        $remain -= $file['size'];
                    }
                }

                $uploader->root_dir(ROOT_PATH);
                $dirname      = 'data/files/store_' . $this->_store_id . '/goods_' . (time() % 200);
                $filename  = $uploader->random_filename();
                $file_path = $uploader->save($dirname, $filename);
                $thumbnail = dirname($file_path) . '/small_' . basename($file_path);
                make_thumb(ROOT_PATH . '/' . $file_path, ROOT_PATH . '/' . $thumbnail, THUMB_WIDTH, THUMB_HEIGHT, THUMB_QUALITY);

                /* 处理文件入库 */
                $data = array(
                    'store_id'  => $this->_store_id,
                    'file_type' => $file['type'],
                    'file_size' => $file['size'],
                    'file_name' => $file['name'],
                    'file_path' => $file_path,
                    'add_time'  => gmtime(),
                );
                $uf_mod =& m('uploadedfile');
                $file_id = $uf_mod->add($data);
                if (!$file_id)
                {
                    $this->_error($uf_mod->get_error());
                    return false;
                }

                /* 处理商品图片入库 */
                $data = array(
                    'goods_id'      => $goods_id,
                    'image_url'  => $file_path,
                    'thumbnail'  => $thumbnail,
                    'sort_order' => 255,
                    'file_id'       => $file_id,
                );
                if (!$this->_image_mod->add($data))
                {
                    $this->_error($this->_image_mod->get_error());
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * 检测店铺是否能添加商品
     *
     */
    function _addible()
    {
        $payment_mod =& m('payment');
        $payments = $payment_mod->get_enabled($this->_store_id);
        if (empty($payments))
        {
            $this->show_warning('please_install_payment', 'go_payment', 'index.php?app=my_payment');
                  return false;
        }

        $shipping_mod =& m('shipping');
        $shippings = $shipping_mod->find("store_id = '{$this->_store_id}' AND enabled = 1");
        if (empty($shippings))
        {
                  $this->show_warning('please_install_shipping', 'go_shipping', 'index.php?app=my_shipping');
                  return false;
        }

        /* 判断商品数是否已超过限制 */
        $store_mod =& m('store');
        $settings = $store_mod->get_settings($this->_store_id);
        if ($settings['goods_limit'] > 0)
        {
                  $goods_count = $this->_goods_mod->get_count();
                  if ($goods_count >= $settings['goods_limit'])
                  {
                         $this->show_warning('goods_limit_arrived');
                         return false;
                  }
        }
        return true;
    }
    /**
     * 保存远程图片
     */
    function _add_remote_image($goods_id)
    {
        foreach ($_POST['new_url'] as $image_url)
        {
            if ($image_url && $image_url != 'http://')
            {
                $data = array(
                    'goods_id' => $goods_id,
                    'image_url' => $image_url,
                    'thumbnail' => $image_url, // 远程图片暂时没有小图
                    'sort_order' => 255,
                    'file_id' => 0,
                );
                if (!$this->_image_mod->add($data))
                {
                    $this->_error($this->_image_mod->get_error());
                    return false;
                }
            }
        }

        return true;
    }
    /**
     * 编辑图片
     */
    function _edit_image($goods_id)
    {
        if (isset($_POST['old_order']))
        {
            foreach ($_POST['old_order'] as $image_id => $sort_order)
            {
                $data = array('sort_order' => $sort_order);
                if (isset($_POST['old_url'][$image_id]))
                {
                    $data['image_url'] = $_POST['old_url'][$image_id];
                }
                $this->_image_mod->edit("image_id = '$image_id' AND goods_id = '$goods_id'", $data);
            }
        }

        return true;
    }

    /**
     * 取得商品信息
     */
    function _get_goods_info($id = 0)
    {
        $default_goods_image = Conf::get('default_goods_image'); // 商城默认商品图片
        if ($id > 0)
        {
            $goods_info = $this->_goods_mod->get_info($id);
            if ($goods_info === false)
            {
                return false;
            }
            $goods_info['default_goods_image'] = $default_goods_image;
            if (empty($goods_info['default_image']))
            {
                   $goods_info['default_image'] = $default_goods_image;
            }
        }
        else
        {
            $goods_info = array(
                'cate_id' => 0,
                'if_show' => 1,
                'recommended' => 1,
                'price' => 1,
                'stock' => 1,
                'spec_qty' => 0,
                'spec_name_1' => Lang::get('color'),
                'spec_name_2' => Lang::get('size'),
                'default_goods_image' => $default_goods_image,
            );
        }
        $goods_info['spec_json'] = ecm_json_encode(array(
            'spec_qty' => $goods_info['spec_qty'],
            'spec_name_1' => isset($goods_info['spec_name_1']) ? $goods_info['spec_name_1'] : '',
            'spec_name_2' => isset($goods_info['spec_name_2']) ? $goods_info['spec_name_2'] : '',
            'specs' => $goods_info['_specs'],
        ));
        return $goods_info;
    }

    /**
     * 提交的数据
     */
    function _get_post_data($id = 0)
    {
        $goods = array(
            'goods_name'       => $_POST['goods_name'],
            'description'      => $_POST['description'],
            'cate_id'             => $_POST['cate_id'],
            'cate_name'        => $_POST['cate_name'],
            'brand'                  => $_POST['brand'],
            'if_show'             => $_POST['if_show'],
            'last_update'      => gmtime(),
            'recommended'      => $_POST['recommended'],
            'tags'             => trim($_POST['tags']),
        );
        $spec_name_1 = !empty($_POST['spec_name_1']) ? $_POST['spec_name_1'] : '';
        $spec_name_2 = !empty($_POST['spec_name_2']) ? $_POST['spec_name_2'] : '';
        if ($spec_name_1 && $spec_name_2)
        {
            $goods['spec_qty'] = 2;
        }
        elseif ($spec_name_1 || $spec_name_2)
        {
            $goods['spec_qty'] = 1;
        }
        else
        {
            $goods['spec_qty'] = 0;
        }

        $goods_file_id = array();
        $desc_file_id =array();
        if (isset($_POST['goods_file_id']))
        {
            $goods_file_id = $_POST['goods_file_id'];
        }
        if (isset($_POST['desc_file_id']))
        {
            $desc_file_id = $_POST['desc_file_id'];
        }
        if ($id <= 0)
        {
            $goods['type'] = 'material';
            $goods['closed'] = 0;
            $goods['add_time'] = gmtime();
        }

        $specs = array(); // 原始规格
        switch ($goods['spec_qty'])
        {
            case 0: // 没有规格
                $specs[intval($_POST['spec_id'])] = array(
                    'price' => $this->_filter_price($_POST['price']),
                    'stock' => intval($_POST['stock']),
                    'sku'      => trim($_POST['sku']),
                    'spec_id'  => trim($_POST['spec_id']),
                );
                break;
            case 1: // 一个规格
                $goods['spec_name_1'] = $spec_name_1 ? $spec_name_1 : $spec_name_2;
                $goods['spec_name_2'] = '';
                $spec_data = $spec_name_1 ? $_POST['spec_1'] : $_POST['spec_2'];
                foreach ($spec_data as $key => $spec_1)
                {
                    $spec_1 = trim($spec_1);
                    if ($spec_1)
                    {
                        if (($spec_id = intval($_POST['spec_id'][$key]))) // 已有规格ID的
                        {
                            $specs[$key] = array(
                                'spec_id' => $spec_id,
                                'spec_1' => $spec_1,
                                'price'  => $this->_filter_price($_POST['price'][$key]),
                                'stock'  => intval($_POST['stock'][$key]),
                                'sku'       => trim($_POST['sku'][$key]),
                            );
                        }
                        else  // 新增的规格
                        {
                            $specs[$key] = array(
                                'spec_1' => $spec_1,
                                'price'  => $this->_filter_price($_POST['price'][$key]),
                                'stock'  => intval($_POST['stock'][$key]),
                                'sku'       => trim($_POST['sku'][$key]),
                            );
                        }

                    }
                }
                break;
            case 2: // 二个规格
                $goods['spec_name_1'] = $spec_name_1;
                $goods['spec_name_2'] = $spec_name_2;
                foreach ($_POST['spec_1'] as $key => $spec_1)
                {
                    $spec_1 = trim($spec_1);
                    $spec_2 = trim($_POST['spec_2'][$key]);
                    if ($spec_1 && $spec_2)
                    {
                        if (($spec_id = intval($_POST['spec_id'][$key]))) // 已有规格ID的
                        {
                            $specs[$key] = array(
                                'spec_id'   => $spec_id,
                                'spec_1'    => $spec_1,
                                'spec_2'    => $spec_2,
                                'price'     => $this->_filter_price($_POST['price'][$key]),
                                'stock'     => intval($_POST['stock'][$key]),
                                'sku'       => trim($_POST['sku'][$key]),
                            );
                        }
                        else // 新增的规格
                        {
                            $specs[$key] = array(
                                'spec_1'    => $spec_1,
                                'spec_2'    => $spec_2,
                                'price'     => $this->_filter_price($_POST['price'][$key]),
                                'stock'     => intval($_POST['stock'][$key]),
                                'sku'       => trim($_POST['sku'][$key]),
                            );
                        }


                    }
                }
                break;
            default:
                break;
        }

        /* 分类 */
        $cates = array();

        foreach ($_POST['sgcate_id'] as $cate_id)
        {
            if (intval($cate_id) > 0)
            {
                $cates[$cate_id] = array(
                    'cate_id'      => $cate_id,
                );
            }
        }

        return array('goods' => $goods, 'specs' => $specs, 'cates' => $cates, 'goods_file_id' => $goods_file_id, 'desc_file_id' => $desc_file_id);
    }

    /**
     * 检查提交的数据
     */
    function _check_post_data($data, $id = 0)
    {
        if (!$this->_check_mgcate($data['goods']['cate_id']))
        {
            $this->_error('select_leaf_category');
            return;
        }
        if (!$this->_goods_mod->unique(trim($data['goods']['goods_name']), $id))
        {
            $this->_error('name_exist');
            return false;
        }
        if ($data['goods']['spec_qty'] == 1 && empty($data['goods']['spec_name_1'])
                  || $data['goods']['spec_qty'] == 2 && (empty($data['goods']['spec_name_1']) || empty($data['goods']['spec_name_2'])))
        {
            $this->_error('fill_spec_name');
            return false;
        }
        if (empty($data['specs']))
        {
            $this->_error('fill_spec');
            return false;
        }

        return true;
    }

    function _format_goods_tags($tags)
    {
        if (!$tags)
        {
            return '';
        }
        $tags = explode(',', str_replace(Lang::get('comma'), ',', $tags));
        array_walk($tags, create_function('&$item, $key', '$item=trim($item);'));
        $tags = array_filter($tags);
        $tmp = implode(',', $tags);
        if (strlen($tmp) > 100)
        {
            $tmp = sub_str($tmp, 100, false);
        }

        return ',' . $tmp . ',';
    }

    /**
     * 保存数据
     */
    function _save_post_data($data, $id = 0)
    {
        import('image.func');
        import('uploader.lib');

        if ($data['goods']['tags'])
        {
            $data['goods']['tags'] = $this->_format_goods_tags($data['goods']['tags']);
        }

        /* 保存商品 */
        if ($id > 0)
        {
            // edit
            if (!$this->_goods_mod->edit($id, $data['goods']))
            {
                $this->_error($this->_goods_mod->get_error());
                return false;
            }

            $goods_id = $id;
        }
        else
        {
            // add
            $goods_id = $this->_goods_mod->add($data['goods']);
            if (!$goods_id)
            {
                $this->_error($this->_goods_mod->get_error());
                return false;
            }
            if (($data['goods_file_id'] || $data['desc_file_id'] ))
            {
                $uploadfiles = array_merge($data['goods_file_id'], $data['desc_file_id']);
                $this->_uploadedfile_mod->edit(db_create_in($uploadfiles, 'file_id'), array('item_id' => $goods_id));
            }
            if (!empty($data['goods_file_id']))
            {
                $this->_image_mod->edit(db_create_in($data['goods_file_id'], 'file_id'), array('goods_id' => $goods_id));
            }
        }
        /* 保存规格 */
        if ($id > 0)
        {
            /* 删除的规格 */
            $goods_specs = $this->_spec_mod->find(array(
                'conditions' => "goods_id = '{$id}'",
                'fields' => 'spec_id'
            ));
            $drop_spec_ids = array_diff(array_keys($goods_specs), array_keys($data['specs']));
            if (!empty($drop_spec_ids))
            {
                $this->_spec_mod->drop($drop_spec_ids);
            }

        }
        $default_spec = array(); // 初始化默认规格
        foreach ($data['specs'] as $key => $spec)
        {
            if ($spec_id = $spec['spec_id']) // 更新已有规格ID
            {
                $this->_spec_mod->edit($spec_id,$spec);
            }
            else // 新加规格ID
            {
                $spec['goods_id'] = $goods_id;
                $spec_id = $this->_spec_mod->add($spec);
            }
            if (empty($default_spec))
            {
                $default_spec = array('default_spec' => $spec_id, 'price' => $spec['price']);
            }
        }

        /* 更新默认规格 */
        $this->_goods_mod->edit($goods_id, $default_spec);
        if ($this->_goods_mod->has_error())
        {
            $this->_error($this->_goods_mod->get_error());
            return false;
        }

        /* 保存商品分类 */
        $this->_goods_mod->unlinkRelation('belongs_to_gcategory', $goods_id);
        if ($data['cates'])
        {
            $this->_goods_mod->createRelation('belongs_to_gcategory', $goods_id, $data['cates']);
        }

        /* 设置默认图片 */
        if (isset($data['goods_file_id'][0]))
        {
            $default_image = $this->_image_mod->get(array(
                'fields' => 'thumbnail',
                'conditions' => "goods_id = '$goods_id' AND file_id = '{$data[goods_file_id][0]}'",
            ));
            $this->_image_mod->edit("goods_id = $goods_id", array('sort_order' => 255));
            $this->_image_mod->edit("goods_id = $goods_id AND file_id = '{$data[goods_file_id][0]}'", array('sort_order' => 1));
        }

        $this->_goods_mod->edit($goods_id, array(
            'default_image' => $default_image ? $default_image['thumbnail'] : '',
        ));

        $this->_last_update_id = $goods_id;

        return true;
    }

    //品牌申请列表
    function brand_list()
    {
        $_GET['store_id'] = $this->_store_id;
        $_GET['if_show'] = BRAND_PASSED;
        $con = array(
            array(
                'field' => 'store_id',
                'name'  => 'store_id',
                'equal' => '=',
            ),
            array(
                'field' => 'if_show',
                'name'  => 'if_show',
                'equal' => '=',
                'assoc' => 'or',
            ),);
        $filtered = '';
        if (!empty($_GET['brand_name']) || !empty($_GET['store']))
        {
            $_GET['brand_name'] && $filtered = " AND brand_name LIKE '%{$_GET['brand_name']}%'";
            $_GET['store'] && $filtered = $filtered . " AND store_id = " . $this->_store_id;
        }
        if (isset($_GET['sort']) && isset($_GET['order']))
        {
            $sort  = strtolower(trim($_GET['sort']));
            $order = strtolower(trim($_GET['order']));
            if (!in_array($order,array('asc','desc')))
            {
                $sort  = 'store_id';
                $order = 'desc';
            }
        }
        else
        {
            $sort  = 'store_id';
            $order = 'desc';
        }
        $page = $this->_get_page(10);
        $conditions = $this->_get_query_conditions($con);
        $brand = $this->_brand_mod->find(array(
            'conditions' => "(1=1 $conditions)" . $filtered,
            'limit' => $page['limit'],
            'order' => "$sort $order",
            'count' => true,
        ));
        $page['item_count'] = $this->_brand_mod->getCount();
        $this->_format_page($page);
        $this->_curlocal(LANG::get('member_center'), 'index.php?app=member',
                         LANG::get('my_goods'), 'index.php?app=my_goods',
                         LANG::get('brand_list'));
        $this->_curitem('my_goods');
        $this->_curmenu('brand_apply_list');
        $this->import_resource(array(
                 'script' => array(
                     array(
                         'path' => 'jquery.plugins/jquery.validate.js',
                         'attr' => 'charset="utf-8"',
                     ),
                     array(
                         'path' => 'jquery.ui/jquery.ui.js',
                         'attr' => 'charset="utf-8"',
                     ),
                     array(
                         'attr' => 'id="dialog_js" charset="utf-8"',
                         'path' => 'dialog/dialog.js',
                     ),
                 ),
                 'style' =>  'jquery.ui/themes/ui-lightness/jquery.ui.css',
             ));
        $this->assign('page_info', $page);
        $this->assign('filtered', empty($filtered) ? 0 : 1);
        $this->assign('brands', $brand);
        $this->display('brand_list.html');
    }

    //品牌申请

    function brand_apply()
    {
        if (!IS_POST)
        {
            header("Content-Type:text/html;charset=" . CHARSET);
            $this->display('brand_apply.html');
        }
        else
        {
            $brand_name = trim($_POST['brand_name']);
            if (empty($brand_name))
            {
                $this->pop_warning("brand_name_required");
                exit;
            }

            if (!$this->_brand_mod->unique($brand_name))
            {
                $this->pop_warning('brand_name_exist');
                return;
            }
            if (!$brand_id = $this->_brand_mod->add(array('brand_name' => $brand_name, 'store_id' => $this->_store_id, 'if_show' => 0, 'tag' => trim($_POST['tag']))))  //获取brand_id
            {
                $this->pop_warning($this->_brand_mod->get_error());

                return;
            }

            $logo = $this->_upload_logo($brand_id);
            if ($logo === false)
            {
                return;
            }
            $this->_brand_mod->edit($brand_id, array('brand_logo' => $logo));
            $this->pop_warning('ok',
                'my_goods_brand_apply', 'index.php?app=my_goods&act=brand_list');
        }
    }

    function brand_edit()
    {
        $id = intval($_GET['id']);
        $brand = $this->_brand_mod->find('store_id = ' . $this->_store_id . ' AND if_show = ' . BRAND_REFUSE . ' AND brand_id = ' . $id);
        $brand = current($brand);
        if (empty($brand))
        {
            $this->show_warning("not_rights");
            exit;
        }
        if (!IS_POST)
        {
            header("Content-Type:text/html;charset=" . CHARSET);
            $this->assign('brand', $brand);
            $this->display('brand_apply.html');
        }
        else
        {
            $brand_name = trim($_POST['brand_name']);
            if (!$this->_brand_mod->unique($brand_name, $id))
            {
                $this->pop_warning('brand_name_exist');
                return;
            }
            $data = array();
            if (isset($_FILES['brand_logo']))
            {
                $logo = $this->_upload_logo($id);
                if ($logo === false)
                {
                    return;
                }
                $data['brand_logo'] = $logo;
            }
            $data['brand_name'] = $brand_name;
            $data['tag'] = trim($_POST['tag']);
            $this->_brand_mod->edit($id, $data);
            if ($this->_brand_mod->has_error())
            {
                $this->pop_warning($this->_brand_mod->get_error());
                exit;
            }
            $this->pop_warning('ok', 'my_goods_brand_edit');
        }

    }

    function brand_drop()
    {
        $id = intval($_GET['id']);
        if (empty($id))
        {
            $this->show_warning('request_error');
            exit;
        }
        $brand = $this->_brand_mod->find("store_id = " . $this->_store_id . " AND if_show = " . BRAND_REFUSE . " AND brand_id = " . $id);
        $brand = current($brand);
        if (empty($brand))
        {
            $this->show_warning('request_error');
            exit;
        }
        if (!$this->_brand_mod->drop($id))
        {
            $this->show_warning($this->_brand_mod->get_error());
            exit;
        }
        if (!empty($brand['brand_logo']) && file_exists(ROOT_PATH . '/' . $brand['brand_logo']))
        {
            @unlink(ROOT_PATH . '/' . $brand['brand_logo']);
        }
        $this->show_message('drop_brand_ok',
            'back_list', 'index.php?app=my_goods&act=brand_list');

    }

    function check_brand()
    {
        $brand_name = $_GET['brand_name'];
        if (!$brand_name)
        {
            echo ecm_json_encode(true);
            return ;
        }
        if ($this->_brand_mod->unique($brand_name))
        {
            echo ecm_json_encode(true);
        }
        else
        {
            echo ecm_json_encode(false);
        }
        return ;
    }
    function _upload_logo($brand_id)
    {
        $file = $_FILES['brand_logo'];
        if ($file['error'] == UPLOAD_ERR_NO_FILE || !isset($_FILES['brand_logo'])) // 没有文件被上传
        {
            return '';
        }
        import('uploader.lib');             //导入上传类
        $uploader = new Uploader();
        $uploader->allowed_type(IMAGE_FILE_TYPE); //限制文件类型
        $uploader->addFile($_FILES['brand_logo']);//上传logo
        if (!$uploader->file_info())
        {
            $this->pop_warning($uploader->get_error());
            if (ACT == 'brand_apply')
            {
                $m_brand = &m('brand');
                $m_brand->drop($brand_id);
            }            
            return false;
        }
        /* 指定保存位置的根目录 */
        $uploader->root_dir(ROOT_PATH);

        /* 上传 */
        if ($file_path = $uploader->save('data/files/mall/brand', $brand_id))   //保存到指定目录，并以指定文件名$brand_id存储
        {
            return $file_path;
        }
        else
        {
            return false;
        }
    }
    
    /* 价格过滤，返回非负浮点数 */
    function _filter_price($price)
    {
        return abs(floatval($price));
    }
}

?>
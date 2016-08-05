<?php

class My_storeApp extends StoreadminbaseApp
{
    var $_store_id;
    var $_store_mod;
    var $_uploadedfile_mod;

    function __construct()
    {
        $this->My_storeApp();
    }
    function My_storeApp()
    {
        parent::__construct();
        $this->_store_id  = intval($this->visitor->get('manage_store'));
        $this->_store_mod =& m('store');
        $this->_uploadedfile_mod = &m('uploadedfile');
    }

    function index()
    {
        $tmp_info = $this->_store_mod->get(array(
            'conditions' => $this->_store_id,
            'join'       => 'belongs_to_sgrade',
            'fields'     => 'domain, functions',
        ));
        $functions = $tmp_info['functions'] ? explode(',', $tmp_info['functions']) : array();
        $subdomain_enable = false;
        if (ENABLED_SUBDOMAIN && in_array('subdomain', $functions))
        {
            $subdomain_enable = true;
        }
        if (!IS_POST)
        {
            //传给iframe参数belong, item_id
            $this->assign('belong', BELONG_STORE);
            $this->assign('id', $this->_store_id);

            $store = $this->_store_mod->get_info($this->_store_id);
            foreach ($functions as $k => $v)
            {
                $store['functions'][$v] = $v;
            }

            $this->assign('store', $store);
            $this->assign('editor_upload', $this->_build_upload(array(
                'obj' => 'EDITOR_SWFU',
                'belong' => BELONG_STORE,
                'item_id' => $this->_store_id,
                'button_text' => Lang::get('bat_upload'),
                'button_id' => 'editor_upload_button',
                'progress_id' => 'editor_upload_progress',
                'upload_url' => 'index.php?app=swfupload',
                'if_multirow' => 1,
            )));
            
            extract($this->_get_theme());
            $this->assign('build_editor', $this->_build_editor(array(
                'name' => 'description',
                'content_css' => SITE_URL . "/themes/store/{$template_name}/styles/{$style_name}" . '/shop.css', // for preview
            )));

            $msn_active_url = 'http://settings.messenger.live.com/applications/websignup.aspx?returnurl=' .
                SITE_URL . '/index.php' . urlencode('?app=my_store&act=update_im_msn') . '&amp;privacyurl=' . SITE_URL . '/index.php' . urlencode('?app=article&act=system&code=msn_privacy');
            $this->assign('msn_active_url', $msn_active_url);

            $region_mod =& m('region');
            $this->assign('regions', $region_mod->get_options(0));
            //$this->headtag('<script type="text/javascript" src="{lib file=mlselection.js}"></script>');

            /* 属于店铺的附件 */
            $files_belong_store = $this->_uploadedfile_mod->find(array(
                'conditions' => 'store_id = ' . $this->visitor->get('manage_store') . ' AND belong = ' . BELONG_STORE . ' AND item_id =' . $this->visitor->get('manage_store'),
                'fields' => 'this.file_id, this.file_name, this.file_path',
                'order' => 'add_time DESC'
            ));
            /* 当前页面信息 */
            $this->_curlocal(LANG::get('member_center'), 'index.php?app=member', LANG::get('my_store'));
            $this->_curitem('my_store');
            $this->_curmenu('my_store');
            $this->import_resource('jquery.plugins/jquery.validate.js,mlselection.js');
            $this->assign('files_belong_store', $files_belong_store);
            $this->assign('subdomain_enable', $subdomain_enable);
            $this->assign('domain_length', Conf::get('subdomain_length'));
            $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('my_store'));
            $this->display('my_store.index.html');
        }
        else
        {
            $subdomain = $tmp_info['domain'];
            if ($subdomain_enable && !$tmp_info['domain'])
            {
                $subdomain = empty($_POST['domain']) ? '' : trim($_POST['domain']);
                if (!$this->_store_mod->check_domain($subdomain, Conf::get('subdomain_reserved'), Conf::get('subdomain_length')))
                {
                    $this->show_warning($this->_store_mod->get_error());

                    return;
                }
            }
            $data = $this->_upload_files();
            if ($data === false)
            {
                return;
            }
            else //删除冗余图标
            {
                if($store['store_logo'] != '' && $data['store_logo'] != '')
                {
                    $store_logo_old = pathinfo($store['store_logo']);
                    $store_logo_new = pathinfo($data['store_logo']);
                    if($store_logo_old['extension'] != $store_logo_new['extension'])
                    {
                        unlink($store['store_logo']);
                    }
                }

                if($store['store_banner'] != '' && $data['store_banner'] != '')
                {
                    $store_banner_old = pathinfo($store['store_banner']);
                    $store_banner_new = pathinfo($data['store_banner']);
                    if($store_banner_old['extension'] != $store_banner_new['extension'])
                    {
                        unlink($store['store_banner']);
                    }
                }
            }
            
            $data = array_merge($data, array(
                'store_name' => $_POST['store_name'],
                'region_id'  => $_POST['region_id'],
                'region_name'=> $_POST['region_name'],
                'description'=> $_POST['description'],
                'address'    => $_POST['address'],
                'tel'        => $_POST['tel'],
                'im_qq'      => $_POST['im_qq'],
                'im_ww'      => $_POST['im_ww'],
                'domain'     => $subdomain,
                'enable_groupbuy'   => $_POST['enable_groupbuy'],
                'enable_radar'      => $_POST['enable_radar'],
                //360cd.cn   seema
                'business_scope'      => $_POST['business_scope'],
            ));
            $this->_store_mod->edit($this->_store_id, $data);

            $this->show_message('edit_ok');
        }
    }

    function update_im_msn()
    {
        $id = isset($_GET['id']) ? trim($_GET['id']) : '';
        $this->_store_mod->edit($this->_store_id, array('im_msn' => $id));
        header("Location: index.php?app=my_store");
        exit;
    }

    function drop_im_msn()
    {
        $this->_store_mod->edit($this->_store_id, array('im_msn' => ''));
        header("Location: index.php?app=my_store");
        exit;
    }

    function _get_member_submenu()
    {
        return array(
            array(
                'name' => 'my_store',
                'url'  => 'index.php?app=my_store',
            ),
        );
    }

    /**
     * 上传文件
     *
     */
    function _upload_files()
    {
        import('uploader.lib');
        $data      = array();
        /* store_logo */
        $file = $_FILES['store_logo'];
        if ($file['error'] == UPLOAD_ERR_OK && $file !='')
        {
            $uploader = new Uploader();
            $uploader->allowed_type(IMAGE_FILE_TYPE);
            $uploader->allowed_size(SIZE_STORE_LOGO); // 20KB
            $uploader->addFile($file);
            if ($uploader->file_info() === false)
            {
                $this->show_warning($uploader->get_error());
                return false;
            }
            $uploader->root_dir(ROOT_PATH);
            $data['store_logo'] = $uploader->save('data/files/store_' . $this->_store_id . '/other', 'store_logo');
        }

        /* store_banner */
        $file = $_FILES['store_banner'];
        if ($file['error'] == UPLOAD_ERR_OK && $file !='')
        {
            $uploader = new Uploader();
            $uploader->allowed_type(IMAGE_FILE_TYPE);
            $uploader->allowed_size(SIZE_STORE_BANNER); // 200KB
            $uploader->addFile($file);
            if ($uploader->file_info() === false)
            {
                $this->show_warning($uploader->get_error());
                return false;
            }
            $uploader->root_dir(ROOT_PATH);
            $data['store_banner'] = $uploader->save('data/files/store_' . $this->_store_id . '/other', 'store_banner');
        }

        return $data;
    }
        /* 异步删除附件 */
    function drop_uploadedfile()
    {
        $file_id = isset($_GET['file_id']) ? intval($_GET['file_id']) : 0;
        $file = $this->_uploadedfile_mod->get($file_id);
        if ($file_id && $file['store_id'] == $this->visitor->get('manage_store') && $this->_uploadedfile_mod->drop($file_id))
        {
            $this->json_result('drop_ok');
            return;
        }
        else
        {
            $this->json_error('drop_error');
            return;
        }
    }
}

?>
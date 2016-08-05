<?php

/* 微公众平台关键词自动回复管理控制器 */

class My_wxkeywordApp extends StoreadminbaseApp {

    var $my_wxkeyword_mod;
    var $wxfile_mod;
    var $_store_id;

    function __construct() {
        $this->My_wxkeyword();
    }

    function My_wxkeyword() {
        parent::__construct();
        $this->my_wxkeyword_mod = & m('wxkeyword');
        $this->wxfile_mod = & m('wxfile');
        $this->_store_id = intval($this->visitor->get('manage_store'));

        $wxconfig = & m('wxconfig');
        $ininterface = $wxconfig->get_info_user($this->_store_id);
        if (empty($ininterface)) {
            $this->show_message(Lang::get('no_ininterface'), '', 'index.php?app=my_wxconfig');
            exit;
        }
    }

    function index() {

        /* 当前位置 */
        $this->_curlocal(LANG::get('member_center'), 'index.php?app=member', LANG::get('my_wxkeyword'));

        $conditions = ' AND iskey=1 AND user_id=' . $this->_store_id;
        $keyinfo = $this->my_wxkeyword_mod->find(array(
            'fields' => 'kid, kename, type, kyword',
            'conditions' => '1=1 ' . $conditions,
            'count' => true,
        ));
        /* 当前用户中心菜单 */
        $this->assign('keyinfo', $keyinfo);
        $this->_curitem('my_wxkeyword');

        /* 当前所处子菜单 */
        $this->_curmenu('my_wxkeyword');
        $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('my_wxkeyword'));
        $this->display('my_wxkeyword.index.html');
    }

    function addkeyword_ajax() {
        $kid = isset($_REQUEST['kid']) && !empty($_REQUEST['kid']) ? intval($_REQUEST['kid']) : 0;
        if ($kid > 0) {
            $conditions = ' AND iskey=1 AND user_id=' . $this->_store_id . " AND kid='$kid'";
            $keyinfo = $this->my_wxkeyword_mod->find(array(
                'conditions' => '1=1 ' . $conditions,
            ));
            foreach ($keyinfo as $k => $v) {
                $titles = unserialize($v['titles']);
                $imageinfo = unserialize($v['imageinfo']);
                $linkinfo = unserialize($v['linkinfo']);
                $keyinfo[$k]['titles'] = $titles;
                $keyinfo[$k]['imageinfo'] = $imageinfo;
                $keyinfo[$k]['linkinfo'] = $linkinfo;
            }
            die(json_encode($keyinfo));
        }
    }

    function del() {
        if (IS_POST) {
            $kid = isset($_POST['kid']) && !empty($_POST['kid']) ? intval($_POST['kid']) : 0;
            if ($kid > 0) {
                $this->my_wxkeyword_mod->drop($kid);
            }
        }
    }

    function update() {
        if (IS_POST) {
            $ketype = isset($_POST['ketype']) && !empty($_POST['ketype']) ? intval($_POST['ketype']) : 0;
            $kename = isset($_POST['kename']) && !empty($_POST['kename']) ? $_POST['kename'] : '';
            $keword = isset($_POST['keword']) && !empty($_POST['keword']) ? $_POST['keword'] : '';
            $kid = isset($_POST['kid']) && !empty($_POST['kid']) ? intval($_POST['kid']) : 0;
            if ($kid > 0) {
                if ($ketype == 1) {

                    $kecontent = isset($_POST['kecontent']) && !empty($_POST['kecontent']) ? $_POST['kecontent'] : '';
                    $data = array(
                        'type' => $ketype,
                        'kecontent' => $kecontent,
                        'linkinfo' => '',
                        'titles' => '',
                        'imageinfo' => '',
                        'kename' => $kename,
                        'kyword' => $keword,
                        'iskey' => 1,
                        'user_id' => $this->_store_id
                    );
                    $this->my_wxkeyword_mod->edit($kid, $data);
                } elseif ($ketype == 2) {

                    $titles = isset($_POST['titles']) && !empty($_POST['titles']) ? $_POST['titles'] : '';
                    $imageinfo = isset($_POST['imageinfo']) && !empty($_POST['imageinfo']) ? $_POST['imageinfo'] : '';
                    $linkinfo = isset($_POST['linkinfo']) && !empty($_POST['linkinfo']) ? $_POST['linkinfo'] : '';
                    $data = array(
                        'type' => $ketype,
                        'kecontent' => '',
                        'linkinfo' => serialize($linkinfo),
                        'titles' => serialize($titles),
                        'imageinfo' => serialize($imageinfo),
                        'kename' => $kename,
                        'kyword' => $keword,
                        'iskey' => 1,
                        'user_id' => $this->_store_id
                    );
                    $this->my_wxkeyword_mod->edit($kid, $data);
                }
            }
        }
    }

    function save() {
        if (IS_POST) {
            $ketype = isset($_POST['ketype']) && !empty($_POST['ketype']) ? intval($_POST['ketype']) : 0;
            $kename = isset($_POST['kename']) && !empty($_POST['kename']) ? $_POST['kename'] : '';
            $keword = isset($_POST['keword']) && !empty($_POST['keword']) ? $_POST['keword'] : '';

            if ($ketype == 1) {

                $kecontent = isset($_POST['kecontent']) && !empty($_POST['kecontent']) ? $_POST['kecontent'] : '';
                $data = array(
                    'type' => $ketype,
                    'kecontent' => $kecontent,
                    'linkinfo' => '',
                    'titles' => '',
                    'imageinfo' => '',
                    'kename' => $kename,
                    'kyword' => $keword,
                    'iskey' => 1,
                    'user_id' => $this->_store_id
                );
                $this->my_wxkeyword_mod->add($data);
            } elseif ($ketype == 2) {

                $titles = isset($_POST['titles']) && !empty($_POST['titles']) ? $_POST['titles'] : '';
                $imageinfo = isset($_POST['imageinfo']) && !empty($_POST['imageinfo']) ? $_POST['imageinfo'] : '';
                $linkinfo = isset($_POST['linkinfo']) && !empty($_POST['linkinfo']) ? $_POST['linkinfo'] : '';
                $data = array(
                    'type' => $ketype,
                    'kecontent' => '',
                    'linkinfo' => serialize($linkinfo),
                    'titles' => serialize($titles),
                    'imageinfo' => serialize($imageinfo),
                    'kename' => $kename,
                    'kyword' => $keword,
                    'iskey' => 1,
                    'user_id' => $this->_store_id
                );
                $this->my_wxkeyword_mod->add($data);
            }
        }
    }

    /**
     *    三级菜单
     *
     *    @author    Hyber
     *    @return    void
     */
    function _get_member_submenu() {
        $submenus = array(
            array(
                'name' => 'my_wxkeyword',
                'url' => 'index.php?app=my_wxkeyword',
            ),
        );
        return $submenus;
    }

    function allimages() {
        $conditions = ' AND user_id=' . $this->_store_id;
        $allimages = $this->wxfile_mod->findAll(array(
            'order' => "file_id desc",
            'fields' => 'file_id as iid, file_path as imgurl',
            'conditions' => '1=1 ' . $conditions,
            'count' => true)
        );
        die(json_encode(array_values($allimages)));
    }

    function ajaxupload() {
        $file = $_FILES['image'];
        import('weixin.lib');
        $file_path = Init_Weixin::uploadfile($file);
        $file_type = Init_Weixin::_return_mimetype($file_path);
        /* 文件入库 */
        $data = array(
            'file_type' => $file_type,
            'user_id' => $this->_store_id,
            'file_size' => $file['size'],
            'file_name' => $file['name'],
            'file_path' => $file_path
        );
        $this->wxfile_mod->add($data);
    }

    function delimage() {
        $file_id = isset($_POST['iid']) && !empty($_POST['iid']) ? intval($_POST['iid']) : 0;
        if ($file_id == 0) {
            $this->json_error('no_post_params_authorize');
            exit();
        }
        $this->wxfile_mod->drop($file_id);
    }

}

?>
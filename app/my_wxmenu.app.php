<?php

/* 微公众平台菜单管理控制器 */

class My_wxmenuApp extends StoreadminbaseApp {

    var $_store_id;
    var $my_wxconfig_mod;
    var $my_wxmenu_mod;

    function __construct() {
        $this->My_wxmenu();
    }

    function My_wxmenu() {
        parent::__construct();
        $this->my_wxmenu_mod = & m('wxmenu');
        $this->my_wxconfig_mod = & m('wxconfig');
        $this->_store_id = intval($this->visitor->get('manage_store'));
        $ininterface = $this->my_wxconfig_mod->get_info_user($this->_store_id);
        if (empty($ininterface)) {
            $this->show_message(Lang::get('no_ininterface'), '', 'index.php?app=my_wxconfig');
            exit;
        }
    }

    function index() {
        if (!IS_POST) {
            import('Treemenu.class');

            /* 当前位置 */
            $this->_curlocal(LANG::get('member_center'), 'index.php?app=member', LANG::get('my_wxmenu'));

            /* 当前用户中心菜单 */
            $this->_curitem('my_wxmenu');

            /* 当前所处子菜单 */
            $this->_curmenu('my_wxmenu');

            $user_id = $this->_store_id;
            $wxconfig = $this->my_wxconfig_mod->get_info_user($user_id);
            $this->assign('wxconfig', $wxconfig);

            $treemenu = new Treemenu();
            $treemenu->icon = array('│ ', '├─ ', '└─ ');
            $treemenu->nbsp = '&nbsp;&nbsp;&nbsp;';

            $this->import_resource(array(
                'script' => array(
                    array(
                        'path' => 'url:static/js/plugins/jquery.tools.min.js',
                        'attr' => 'charset="utf-8"',
                    ),
                    array(
                        'path' => 'url:static/js/plugins/formvalidator.js',
                        'attr' => 'charset="utf-8"',
                    ),
                    array(
                        'path' => 'url:static/js/pinphp.js',
                        'attr' => 'charset="utf-8"',
                    ),
                ),
            ));

            $conditions = ' AND user_id = ' . $user_id;
            $result = $this->my_wxmenu_mod->find(array(
                'fields' => 'id, name, tags, pid, spid, add_time, items, likes, weixin_type, ordid, weixin_status, weixin_keyword, weixin_key',
                'conditions' => '1=1 ' . $conditions,
                'order' => 'ordid asc',
            ));

            $array = array();
            foreach ($result as $r) {

                $r['str_status'] = '<img data-tdtype="toggle" data-id="' . $r['id'] . '" data-field="status" data-value="' . $r['weixin_status'] . '" src="../static/images/bgimg/toggle_' . ($r['weixin_status'] == 0 ? 'disabled' : 'enabled') . '.gif" />';


                $r['key'] = '<span >' . $r['weixin_key'] . '</span>';
                $r['keyword'] = '<span >' . $r['weixin_keyword'] . '</span>';
                if ($r['pid'] == '0') {
                    $r['str_manage'] = '<a href="javascript:;" class="J_showdialog" data-uri="index.php?app=my_wxmenu&amp;act=add&amp;pid=' . $r['id'] . '" data-title="添加分类" data-id="add" data-width="520" data-height="360">添加子菜单</a> |
									<a href="javascript:;" class="J_showdialog" data-uri="index.php?app=my_wxmenu&amp;act=edit&amp;id=' . $r['id'] . '" data-title="编辑 - ' . $r['name'] . '" data-id="edit" data-width="520" data-height="360">编辑</a> |
									<a href="javascript:;" class="J_confirmurl" data-acttype="ajax" data-uri="index.php?app=my_wxmenu&amp;act=del&amp;id=' . $r['id'] . '" data-msg="' . sprintf('确定要删除"%s"吗？', $r['name']) . '">删除</a>';
                } else {
                    $r['str_manage'] = '<a href="javascript:;" class="J_showdialog" data-uri="index.php?app=my_wxmenu&amp;act=edit&amp;id=' . $r['id'] . '" data-title="编辑 - ' . $r['name'] . '" data-id="edit" data-width="520" data-height="360">编辑</a> |
									<a href="javascript:;" class="J_confirmurl" data-acttype="ajax" data-uri="index.php?app=my_wxmenu&amp;act=del&amp;id=' . $r['id'] . '" data-msg="' . sprintf('确定要删除"%s"吗？', $r['name']) . '">删除</a>';
                }

                $r['parentid_node'] = ($r['pid']) ? ' class="child-of-node-' . $r['pid'] . '"' : '';
                $array[] = $r;
            }
            $str = "<tr id='node-\$id' \$parentid_node>
					<td align='center'><input type='checkbox' value='\$id' class='J_checkitem'></td>
					<td align='center'>\$id</td>
					<td align='center'>\$spacer<span data-tdtype='edit' data-field='name' data-id='\$id' class='tdedit'  style='color:\$fcolor'>\$name</span></td>
					 <td align='center'><span data-tdtype='edit' data-field='key' data-id='\$id' class='tdedit'  >\$key</span></td>
					  <td align='center'><span data-tdtype='edit' data-field='keyword' data-id='\$id' class='tdedit'  >\$keyword</span></td>
					
					<td align='center'><span data-tdtype='edit' data-field='ordid' data-id='\$id' class='tdedit'>\$ordid</span></td>
				
					<td align='center'>\$str_status</td>
					<td align='center'>\$str_manage</td>
					</tr>";
            $treemenu->init($array);

            $list = $treemenu->get_tree(0, $str);
            $this->assign('list', $list);
            $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('wxmenu'));
            $this->display('my_wxmenu.index.html');
        } else {
            $w_id = $this->my_wxconfig_mod->unique($this->_store_id);
            $data = array(
                'appid' => $_POST['weixin_appid'],
                'appsecret' => $_POST['weixin_appsecret'],
            );

            if ($w_id) {
                $this->my_wxconfig_mod->edit($w_id, $data);
                if ($this->my_wxconfig_mod->has_error()) {
                    $this->show_warning($this->my_wxconfig_mod->get_error());

                    return;
                }

                $this->show_message('edit_weixin_successed', '', 'index.php?app=my_wxmenu');
            } else {
                $this->show_message(Lang::get('no_ininterface'), '', 'index.php?app=my_wxconfig');
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
                'name' => 'my_wxmenu',
                'url' => 'index.php?app=my_wxmenu',
            ),
        );

        return $submenus;
    }

    /* 微信菜单用到的函数 */

    private function getmenu() {
        $keyword = array();

        $topmemu = $this->my_wxmenu_mod->find(array(
            'fields' => 'id, name, weixin_key as wkey, weixin_keyword as keyword',
            'conditions' => 'pid=0 and weixin_status=1 and user_id=' . $this->_store_id,
            'order' => 'id asc',
        ));

        foreach ($topmemu as $key) {

            $nextmenu = $this->my_wxmenu_mod->find(array(
                'fields' => 'id, name, weixin_key as wkey, weixin_keyword as keyword',
                'conditions' => 'weixin_status=1 and pid=' . $key['id'] . ' and user_id=' . $this->_store_id,
                'order' => 'id asc',
            ));
            if (count($nextmenu) != 0) {//没有下级栏目
                foreach ($nextmenu as $key2) {
                    $kk[] = array('type' => 'click', 'name' => $key2['name'], 'key' => $key2['wkey']);
                }
                $keyword['button'][] = array('name' => $key['name'], 'sub_button' => $kk);
                $kk = '';
            } else {
                $keyword['button'][] = array('type' => 'click', 'name' => $key['name'], 'key' => $key['wkey']);
            }
        }
        return json_encode($keyword);
    }

    public function create_weixin_menu() {
        import('weixin.lib');
        $user_id = $this->_store_id;
        $wxconfig = $this->my_wxconfig_mod->get_info_user($user_id);
        if (!empty($wxconfig)) {
            $appid = $wxconfig['appid'];
            $appsecret = $wxconfig['appsecret'];
        }
        if (!trim($appid)) {

            $this->show_message('请填写微信AppId', 'back_list', 'index.php?app=my_wxmenu'
            );
            exit;
        }
        if (!trim($appsecret)) {

            $this->show_message('请填写微信AppSecret', 'back_list', 'index.php?app=my_wxmenu'
            );
            exit;
        }
        $ACCESS_LIST = Init_Weixin::curl($appid, $appsecret); //获取到的凭证

        if ($ACCESS_LIST['access_token'] != '') {
            $access_token = $ACCESS_LIST['access_token']; //获取到ACCESS_TOKEN
            $data = $this->getmenu();

            $msg = Init_Weixin::curl_menu($access_token, preg_replace("#\\\u([0-9a-f]+)#ie", "iconv('UCS-2', 'UTF-8', pack('H4', '\\1'))", $data));
            if ($msg['errmsg'] == 'ok') {

                $this->show_message('创建自定义菜单成功!', 'back_list', 'index.php?app=my_wxmenu'
                );
                exit;
            } else {
                $this->show_message('创建自定义菜单失败!', 'back_list', 'index.php?app=my_wxmenu'
                );
                exit;
            }
        } else {
            $this->show_message('创建失败,微信AppId或微信AppSecret填写错误', 'back_list', 'index.php?app=my_wxmenu'
            );
        }
    }

    function add() {
        import('weixin.lib');
        if (!IS_POST) {

            $spid = isset($_REQUEST['pid']) ? intval($_REQUEST['pid']) : 0;
            $this->assign("spid", $spid);
            $response = $this->fetch('my_wxmenu.add.html');
            Init_Weixin::ajaxReturn(1, '', $response);
        } else {

            $pid = isset($_POST['pid']) ? intval($_POST['pid']) : 0;

            if ($pid) {
                $spid = $this->my_wxmenu_mod->getOne("select spid from {$this->my_wxmenu_mod->table} where id ={$pid}");
                if (trim($spid) == 0) {
                    $spid = $pid . "|";
                } else {
                    $spid .= $pid . "|";
                }
            }
            $data = array(
                'name' => !empty($_POST['name']) ? trim($_POST['name']) : '',
                'tags' => ' ',
                'pid' => $pid,
                'spid' => isset($spid) && !empty($spid) ? $spid : 0,
                'add_time' => gmtime(),
                'items' => 0,
                'likes' => ' ',
                'weixin_type' => isset($_POST['weixin_type']) ? intval($_POST['weixin_type']) : 0,
                'ordid' => 255,
                'weixin_status' => isset($_POST['status']) ? intval($_POST['status']) : 0,
                'weixin_keyword' => trim($_POST['keyword']),
                'weixin_key' => trim($_POST['key']),
                'likes' => trim($_POST['likes']),
                'user_id' => $this->_store_id
            );
            if ($this->my_wxmenu_mod->name_exists($data['name'], $data['pid'])) {
                Init_Weixin::ajaxReturn(0, '分类已经存在');
            }

            $id = $this->my_wxmenu_mod->add($data);
            if ($id) {
                Init_Weixin::ajaxReturn(1, $data['name'], '', 'add');
                $data_msg['info'] = "操作成功";
                $data_msg['status'] = 1;
                $data_msg['url'] = "index.php?app=my_wxmenu";
                Init_Weixin::ajaxReturn($data_msg);
            } else {
                Init_Weixin::ajaxReturn(1, '操作失败', '', 'add');
                $data_msg['info'] = "操作失败";
                $data_msg['status'] = 1;
                $data_msg['url'] = "index.php?app=my_wxmenu";
                Init_Weixin::ajaxReturn($data_msg);
            }
        }
    }

    /**
     * 获取紧接着的下一级分类ID
     */
    public function ajax_getchilds() {
        import('weixin.lib');
        $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        $type = isset($_REQUEST['type']) ? intval($_REQUEST['type']) : 0;
        $conditions = 'user_id=' . $this->_store_id . " AND pid=" . $id;

        if ($type) {
            $conditions .=" AND weixin_type=" . $type;
        }

        $return = $this->my_wxmenu_mod->getAll("select id, name from {$this->my_wxmenu_mod->table} where " . $conditions);

        if ($return) {
            Init_Weixin::ajaxReturn(1, '操作成功', $return);
        } else {
            Init_Weixin::ajaxReturn(1, '操作失败');
        }
    }

    function del() {
        import('weixin.lib');
        $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        if ($id > 0) {
            if ($this->my_wxmenu_mod->drop($id)) {
                Init_Weixin::ajaxReturn(1, "操作成功");
                $data_msg['info'] = "操作成功";
                $data_msg['status'] = 1;
                $data_msg['url'] = "index.php?app=my_wxmenu";
                Init_Weixin::ajaxReturn($data_msg);
            } else {
                Init_Weixin::ajaxReturn(1, "操作失败");
                $data_msg['info'] = "操作失败";
                $data_msg['status'] = 1;
                $data_msg['url'] = "index.php?app=my_wxmenu";
                Init_Weixin::ajaxReturn($data_msg);
            }
        } else {
            $this->ajaxReturn(0, "非法参数");
        }
    }

    function edit() {
        import('weixin.lib');
        if (!IS_POST) {
            $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
            $info = $this->my_wxmenu_mod->get($id);
            $this->assign('info', $info);
            $response = $this->fetch('my_wxmenu.edit.html');
            Init_Weixin::ajaxReturn(1, '', $response);
        } else {
            $pid = isset($_POST['pid']) ? intval($_POST['pid']) : 0;
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            if ($pid) {
                $spid = $this->my_wxmenu_mod->getOne("select spid from {$this->my_wxmenu_mod->table} where id ={$pid}");
                if (trim($spid) == 0) {
                    $spid = $pid . "|";
                } else {
                    $spid .= $pid . "|";
                }
            }
            $data = array(
                'name' => !empty($_POST['name']) ? trim($_POST['name']) : '',
                'tags' => ' ',
                'pid' => $pid,
                'spid' => isset($spid) && !empty($spid) ? $spid : 0,
                'add_time' => gmtime(),
                'items' => 0,
                'likes' => ' ',
                'weixin_type' => 0,
                'ordid' => 255,
                'weixin_status' => isset($_POST['status']) ? intval($_POST['status']) : 0,
                'weixin_keyword' => $_POST['keyword'],
                'weixin_key' => $_POST['key'],
            );
            if ($this->my_wxmenu_mod->name_exists($data['name'], $data['pid'], $id)) {
                Init_Weixin::ajaxReturn(0, '分类已经存在');
            }
            $id = $this->my_wxmenu_mod->edit($id, $data);
            if ($id) {
                Init_Weixin::ajaxReturn(1, $data['name'], '', 'edit');
                $data_msg['info'] = "操作成功";
                $data_msg['status'] = 1;
                $data_msg['url'] = "index.php?app=my_wxmenu";
                Init_Weixin::ajaxReturn($data_msg);
            } else {
                Init_Weixin::ajaxReturn(1, '操作失败', '', 'add');
                $data_msg['info'] = "操作失败";
                $data_msg['status'] = 1;
                $data_msg['url'] = "index.php?app=my_wxmenu";
                Init_Weixin::ajaxReturn($data_msg);
            }
        }
    }

}

?>
<?php

class Member_extModule extends AdminbaseModule
{
    public $_mod;
    public $_mod_level;

    public function __construct()
    {
        $this->Member_extModule();
    }

    public function Member_extModule()
    {
        parent::__construct();
        $this->_mod       = &m("member_ext");
        $this->_mod_level = &m("member_level");

        $this->read_default();
    }

    public function index()
    {
        $page       = $this->_get_page();
        $conditions = $this->_get_query_conditions(array(array(
            'field' => 'member.user_name', //按用户名,机构名,支付方式名称进行搜索
            'equal' => 'LIKE',
            'name'  => 'user_name',
        )));

        $user_mod = &m('member');
        $data     = $user_mod->find(array(
            'conditions' => '1=1' . $conditions,
            'fields'     => '*,member_ext.id ext_id',
            'limit'      => $page['limit'],
            'join'       => 'has_member_ext',
            'count'      => true));

        $this->assign('data', $this->assign_user_level($data));

        $page['item_count'] = $user_mod->getCount();
        $this->_format_page($page);
        $this->assign('page_info', $page);

        $this->display('member_ext.index.html');
    }

    public function assign_user_level($user_list)
    {
        $level_list = $this->_mod_level->find();
        if ($user_list && $level_list) {
            foreach ($user_list as $k => $v) {
                if (!empty($v['user_level_id'])) {
                    $user_list[$k]['level_name'] = $level_list[$v['user_level_id']]['level_name'];
                }

            }
        }
        return $user_list;

    }

    public function add_member_ext()
    {
        $id = isset($_GET['id']) && !empty($_GET['id']) ? intval($_GET['id']) : 0;
        if (!$id) {
            $this->show_warning('no_such_data');
            return;
        }
        if (!IS_POST) {
            $user_mod  = &m('member');
            $user_data = $user_mod->get_info($id);
            $level     = $this->_mod_level->find(array());
            $opt       = $this->getOption($level, 'id', 'level_name');
            $status    = array('0' => '等待审核', '1' => '审核成功', '-1' => '审核失败');
            $this->assign('data', $user_data);
            $this->assign('status', $status);
            $this->assign('options', $opt);
            $this->display('member_ext.form.html');
        } else {
            $data = array(
                'user_id'       => $_POST['user_id'],
                'user_level_id' => $_POST['user_level_id'],
                'status'        => $_POST['status'],
            );
            $id = $this->_mod->add($data);
            if ($this->_mod->has_error()) {
                $this->pop_warning($this->_mod->get_error());
                return;
            }

            $this->show_message('edit_member_ext',
                'edit', 'index.php?module=member_ext&amp;act=edit_member_ext&amp;id=' . $id,
                'back_list', 'index.php?module=member_ext&amp;act=index');
        }

    }
    public function edit_member_ext()
    {
        /*$id = isset($_GET['id']) && intval($_GET['id']) > 0 ? intval($_GET['id']) : 0;
        if (!$id) {
            $this->show_warning('no_such_data');
            return;
        }
        if (!IS_POST) {
            $conditions = " and id=" . $id;
            $data       = $this->_mod->get(array(
                'conditions' => '1=1' . $conditions,
                'limit'      => $page['limit'],
                'join'       => 'belongs_to_user',
                'count'      => true));
            $level  = $this->_mod_level->find(array());
            $opt    = $this->getOption($level, 'id', 'level_name');
            $status = array('0' => '等待审核', '1' => '审核成功', '-1' => '审核失败');

            $this->assign('status', $status);
            $this->assign('options', $opt);
            $this->assign("data", $data);
            $this->display('member_ext.form.html');
        } else {
            $data = array(
                'user_id'       => $_POST['user_id'],
                'user_level_id' => $_POST['user_level_id'],
                'status'        => $_POST['status'],
            );
            $this->_mod->edit($id, $data);
            if ($this->_mod->has_error()) {
                $this->pop_warning($this->_mod->get_error());
                return;
            }
            $this->show_message('edit_member_ext_successed',
                'continue_edit', 'index.php?module=member_ext&amp;act=edit_member_ext&amp;id=' . $id,
                'back_list', 'index.php?module=member_ext&amp;act=index');
        }*/

    }

    public function drop_member_ext()
    {
    	echo 'empty';
    	exit();
    }

    public function change_point()
    {
        $id = isset($_GET['user_id']) && intval($_GET['user_id']) > 0 ? intval($_GET['user_id']) : 0;
        if (!$id) {
            $this->show_warning('no_such_data');
            return;
        }
        if (!IS_POST) {
            $conditions = " and member.user_id=" . $id;
            $is_exists  = $this->_mod->get(' user_id=' . $id);
            if (!$is_exists) {
                $data = array(
                    'user_id'       => $id,
                    'user_level_id' => 0,
                    'status'        => 1,
                );
                $this->_mod->add($data);
            }
            $data = $this->_mod->get(array(
                'conditions' => '1=1' . $conditions,
                'limit'      => $page['limit'],
                'join'       => 'belongs_to_user',
                'count'      => true));
            $this->assign("data", $data);
            $this->display('member_ext.point.form.html');
        } else {
            $point    = intval($_POST['user_point']);
            $opt_type = intval($_POST['opt_type']);
            $user_id  = intval($_POST['user_id']);
            $mod      = &m("point_logs");
            if ($opt_type) {
                $mod->change_point($user_id, $point, gmtime(), 'system_add_point');

            } else {
                $mod->change_point($user_id, $point, gmtime(), 'system_subtract_point');

            }

            $this->show_message('edit_member_ext_successed',
                'continue_edit', 'index.php?module=member_ext&amp;act=change_point&amp;user_id=' . $user_id,
                'back_list', 'index.php?module=member_ext&amp;act=index');
        }
    }

    //读取系统数据配置默认数据
    private function read_default() {
    	//读取系统自带等级
    	$grade_model = &m('user_grade');
    	$grade_list = $grade_model->find();

    	$grade_ids = array();
    	foreach ($grade_list as $key => $value) {
			if (!$this->_mod_level->get("id = {$value['id']}")) {
				$this->_mod_level->add(array(
					'id' => $value['id'],
					'level_name'     => $value['grade_name'],
	                'level_code'     => '',
	                'level_discount' => 0,
	                'level_cost'     => 0,
	                'sort'           => $value['priority'],
	                'start_point'    => 0,
	                'end_point'      => 0,
				));
			}

			$grade_ids[$value['id']] = $value['id'];
    	}

    	//清理多余等级
    	$this->_mod_level->drop(db_create_in($grade_ids, 'id NOT'));


    	//初始会员相关
    	$user_model = &m('member');
    	$user_list = $user_model->find();

    	$user_ids = array();
    	foreach ($user_list as $key => $value) {
    		if (!$this->_mod->get("user_id = {$value['user_id']}")) {
				$this->_mod->add(array(
					'user_id'     => $value['user_id'],
	                'user_level_id'     => $value['grade_id'],
	                'user_point' => 0,
	                'user_totalpoint'     => 0,
	                'status' => 1,
	                'sort' => 0,
				));
			}
			$user_ids[$value['user_id']] = $value['user_id'];
    	}

    	//清理多余会员相关
    	$this->_mod->drop(db_create_in($user_ids, 'user_id NOT'));

    }

    public function level_index()
    {
        $page = $this->_get_page();
        $data = $this->_mod_level->find(array(
            'conditions' => '1=1' . $conditions,
            'order' => 'sort',
            'limit'      => $page['limit'],
            'count'      => true));
        $this->assign('data', $data);

        $page['item_count'] = $this->_mod_level->getCount();
        $this->_format_page($page);
        $this->assign('page_info', $page);

        $this->display('member_level.index.html');
    }

    /*public function add_member_level()
    {
        if (!IS_POST) {
            $this->import_resource(array(
                'script' => 'jquery.plugins/jquery.validate.js',
            ));
            $this->display('member_level.form.html');
        } else {
            $data = array(
                'level_name'     => $_POST['level_name'],
                'level_code'     => $_POST['level_code'],
                'level_discount' => $_POST['level_discount'],
                'level_cost'     => $_POST['level_cost'],
                'start_point'    => $_POST['start_point'],
                'end_point'      => $_POST['end_point'],
                'sort'           => $_POST['sort'],
            );

            // 保存 
            $id = $this->_mod_level->add($data);
            if (!$id) {
                $this->pop_warning($this->_mod_level->get_error());
                return;
            }
            $this->show_message('add_member_level_successed',
                'continue_add', 'index.php?module=member_ext&amp;act=add_member_level',
                'back_list', 'index.php?module=member_ext&amp;act=level_index');

        }
    }*/

    public function edit_member_level()
    {
        $id = isset($_GET['id']) && intval($_GET['id']) > 0 ? intval($_GET['id']) : 0;
        if (!$id) {
            $this->show_warning('no_such_data');
            return;
        }
        if (!IS_POST) {
            $data = $this->_mod_level->get($id);
            $this->assign("data", $data);
            $this->import_resource(array(
                'script' => 'jquery.plugins/jquery.validate.js',
            ));
            $this->display('member_level.form.html');
        } else {
            $data = array(
                //'level_name'     => $_POST['level_name'],
                //'level_code'     => $_POST['level_code'],
                'level_discount' => $_POST['level_discount'],
                'level_cost'     => $_POST['level_cost'],
                //'sort'           => $_POST['sort'],
                'start_point'    => $_POST['start_point'],
                'end_point'      => $_POST['end_point'],
            );
            $this->_mod_level->edit($id, $data);
            if ($this->_mod_level->has_error()) {
                $this->pop_warning($this->_mod_level->get_error());
                return;
            }

            $this->show_message('edit_member_level_successed',
                'continue_edit', 'index.php?module=member_ext&amp;act=edit_member_level&amp;id=' . $id,
                'back_list', 'index.php?module=member_ext&amp;act=level_index');
        }

    }

    /*public function drop_member_level()
    {
        $id = isset($_GET['id']) ? trim($_GET['id']) : '';
        if (!$id) {
            $this->show_warning('no_level_to_drop');
            return;
        }

        $ids = explode(',', $id);
        if (!$this->_mod_level->drop($ids)) {
            $this->show_warning($this->_mod_level->get_error());
            return;
        }
        $this->show_message('drop_data_successed');

    }*/

    public function getOption($data = array(), $key, $val)
    {
        $arr = array();
        foreach ($data as $v) {
            $arr[$v[$key]] = $v[$val];
        }
        return $arr;
    }

}

<?php

import('zllib/methods.lib');

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
        $this->_mod_level = &m("member_grade");

        $this->read_default();
    }

    //读取系统数据配置默认数据
    private function read_default()
    {
        //初始会员相关
        $user_model = &m('member');
        $user_list  = $user_model->find();

        $init_grade = $this->_mod_level->getInitGrade();

        $user_ids = array();
        foreach ($user_list as $key => $value) {
            if (!$this->_mod->get("user_id = {$value['user_id']}")) {
                $this->_mod->add(array(
                    'user_id'        => $value['user_id'],
                    'grade_id'       => $init_grade['grade_id'],
                    'integral'       => 0,
                    'total_integral' => 0,
                    'total_buy'      => 0,
                    'update_time'    => gmtime(),
                ));
            }
            $user_ids[$value['user_id']] = $value['user_id'];
        }

        //清理多余会员相关
        $this->_mod->drop(db_create_in($user_ids, 'user_id NOT'));
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
                if (!empty($v['grade_id'])) {
                    $user_list[$k]['grade_name'] = $level_list[$v['grade_id']]['grade_name'];
                }

            }
        }
        return $user_list;

    }

    public function edit_member_ext()
    {
        $id = isset($_GET['id']) && intval($_GET['id']) > 0 ? intval($_GET['id']) : 0;
        if (!$id) {
            $this->show_warning('no_such_data');
            return;
        }
        if (!IS_POST) {
            $conditions = " and member_ext.user_id=" . $id;
            $data       = $this->_mod->get(array(
                'conditions' => '1=1' . $conditions,
                'limit'      => $page['limit'],
                'join'       => 'belongs_to_user',
                'count'      => true));
            $this->assign("data", $data);

            $this->assign('options', $this->_mod_level->getOptions());

            $this->display('member_ext.form.html');
        } else {
            $data = array(
                'grade_id' => intval($_POST['grade_id']),
            );
            $this->_mod->edit($id, $data);
            if ($this->_mod->has_error()) {
                $this->pop_warning($this->_mod->get_error());
                return;
            }
            $this->show_message('edit_member_ext_successed',
                'continue_edit', 'index.php?module=member_ext&amp;act=edit_member_ext&amp;id=' . $id,
                'back_list', 'index.php?module=member_ext&amp;act=index');
        }

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
                $this->show_warning('操作非法');
                exit();
            }

            $data = $this->_mod->get(array(
                'fields'     => '*, member.user_name',
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

    public function level_index()
    {
        $page = $this->_get_page();
        $data = $this->_mod_level->find(array(
            'conditions' => '1=1' . $conditions,
            'order'      => 'priority',
            'limit'      => $page['limit'],
            'count'      => true));
        $this->assign('data', $data);

        $page['item_count'] = $this->_mod_level->getCount();
        $this->_format_page($page);
        $this->assign('page_info', $page);

        $this->display('member_level.index.html');
    }

    public function add_member_level()
    {
        if (!IS_POST) {
            $this->import_resource(array(
                'script' => 'jquery.plugins/jquery.validate.js',
            ));
            $this->assign('priority', $this->_mod_level->getMaxPriority(1));

            $this->display('member_level.form.html');
        } else {
            $data = array(
                'grade_name'       => trim($_POST['grade_name']),
                'priority'         => intval($_POST['priority']),
                'upgrade_buy'      => round($_POST['upgrade_buy'], 4),
                'upgrade_integral' => intval($_POST['upgrade_integral']),
                'buy_tc'           => round($_POST['buy_tc'], 4),
                'sell_tc'          => round($_POST['sell_tc'], 4),
                'discount'         => round($_POST['discount'], 4),
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
    }

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
                'grade_name'       => trim($_POST['grade_name']),
                'priority'         => intval($_POST['priority']),
                'upgrade_buy'      => round($_POST['upgrade_buy'], 4),
                'upgrade_integral' => intval($_POST['upgrade_integral']),
                'buy_tc'           => round($_POST['buy_tc'], 4),
                'sell_tc'          => round($_POST['sell_tc'], 4),
                'discount'         => round($_POST['discount'], 4),
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

    public function drop_member_level()
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

    }

    //等级设置
    public function setting_member_level()
    {
        //得到默认设置
        $filename = ROOT_PATH . '/data/member_level.inc.php';
        $config = Methods::load_config($filename);
        if (!$config) {
            $temp = each($this->_mod_level->getUpgradeTypeOptions());
            $config = array(
                'upgrade_type' => $temp['key'],
                'order_rate' => 0.2,
                'tc_layer' => 3,
            );
            Methods::save_config($filename, $config);
        }

        if (IS_POST) {
            $data = array();
            $data['upgrade_type'] = intval($_POST['upgrade_type']);
            $data['order_rate'] = round($_POST['order_rate'], 4);
            $data['tc_layer'] = intval($_POST['tc_layer']);
            $data['tc'] = $_POST['tc'];
            $data['gcate'] = $_POST['gcate'];
            $data['sgrade'] = $_POST['sgrade'];
            
            if ($data['order_rate'] < 0 || $data['order_rate'] > 1) {
                show_warning('商城订单佣金率错误');
                exit();
            }
            if ($data['tc_layer'] < 0) {
                show_warning('提成层级错误');
                exit();
            }
            $temp = 0;
            foreach ($data['tc'] as $k => $v) {
                $v = round(floatval($v), 4);
                $temp += $v;
                $data['tc'][$k] = $v;
                if ($v < 0 || $v > 1) {
                    show_warning('提成占比错误');
                    exit();
                }
            }
            if ($temp > 1) {
                show_warning('提成占比错误');
                exit();
            }

            foreach ($data['gcate'] as $k => $v) {
                $v = round(floatval($v), 4);
                $data['gcate'][$k] = $v;
                if ($v < 0 || $v > 1) {
                    show_warning('商品分类提成错误');
                    exit();
                }
            }
            foreach ($data['sgrade'] as $k => $v) {
                $v = round(floatval($v), 4);
                $data['sgrade'][$k] = $v;
                if ($v < 0 || $v > 1) {
                    show_warning('店铺等级抵扣错误');
                    exit();
                }
            }
            
            $config = array_merge($config, $data);
            Methods::save_config($filename, $config);
            
            show_message('操作成功');
        }
        else {
            $this->assign('config', $config);

            $this->assign('upgrade_type_options', $this->_mod_level->getUpgradeTypeOptions());
            
            $gcategory_model = &m('gcategory');
            $this->assign('gcate_options', $gcategory_model->get_options(0, false, 'store_id = 0'));
            
            $sgrade_model = &m('sgrade');
            $this->assign('sgrade_options', $sgrade_model->get_options());
            
            $this->display('member_level.setting.html');
        }
    }

}

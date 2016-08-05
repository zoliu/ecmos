<?php

/**
 * 用户等级管理类
 *
 * @author Mosquito
 * @link www.360cd.cn
 */

class User_gradeApp extends BackendApp {
    
    protected $grade_model;
    
    function __construct() {
        parent::__construct();
        
        import('zllib/user.lib');
        $this->grade_model = &m('user_grade');
    }
    
    /**
     * 等级列表
     * {@inheritDoc}
     * @see BaseApp::index()
     */
    function index() {
        
        $grade_list = $this->grade_model->find(array(
            'order' => 'priority',
        ));
        
        foreach ($grade_list as $k => $v) {
            $grade_list[$k]['upgrade'] = unserialize($v['upgrade']);
            $grade_list[$k]['other'] = unserialize($v['other']);
        }
        
        $this->assign('grade_list', $grade_list);
        
        $this->display('user_grade.index.html');
    }
    
    /**
     * 编辑等级
     */
    function edit() {
        $id = intval($_GET['id']);
        $grade_info = $this->grade_model->get($id);
        $grade_info['upgrade'] = unserialize($grade_info['upgrade']);
        $grade_info['other'] = unserialize($grade_info['other']);
        
        if (IS_POST) {
            $data = array();
            $data['grade_name'] = trim($_POST['grade_name']);
            $data['priority'] = intval($_POST['priority']);
            $data['upgrade'] = array(
                'buy' => round(floatval($_POST['buy']), 2),
            );
            $data['other'] = array(
                'buy_tc' => round(floatval($_POST['buy_tc']), 4),
                'sell_tc' => round(floatval($_POST['sell_tc']), 4),
            );
            
            //
            if (!UserGrade::init()->check_grade_data($data, $grade_info ? 'edit' : 'add')) {
                show_warning('操作失败');
                exit();
            }
            
            //
            $data['upgrade'] = serialize($data['upgrade']);
            $data['other'] = serialize($data['other']);
            
            //
            if ($grade_info) {
                $temp = $this->grade_model->edit($id, $data);
            }
            else {
                $temp = $this->grade_model->add($data);
            }
            $temp ? show_message('操作成功') : show_warning('操作失败');
        }
        else {
            $this->assign('grade_info', $grade_info);
            $this->assign('priority', UserGrade::init()->get_max_priority(1));
            
            $this->display('user_grade.edit.html');
        }
    }
    
    /**
     * 删除等级
     */
    function drop() {
        $id_arr = trim($_GET['id']) ? explode(',', $_GET['id']) : '';
        if (!$id_arr) {
            show_warning('操作失败');
            exit();
        }
        
        $this->grade_model->drop(db_create_in($id_arr, 'id'));
        show_message('操作成功');
    }
}
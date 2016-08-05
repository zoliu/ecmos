<?php

class User {
    protected $user_model;
    
    function __construct() {
        $this->user_model = &m('member');
    }
    
    static function init() {
        return new User();
    }
    
    /**
     * 插入用户数据，已有则编辑（注：主要插入扩展的相关信息）
     * @param array $data
     */
    function insert_user_data($data) {
        if (!$data) {
            return 0;
        }
        
        $user_id = intval($data['user_id']);
        unset($data['user_id']);
        if ($user_id > 0) {
            $temp = $this->user_model->edit($user_id, $data);
            if (!$temp) {
                return $temp;
            }
            return $user_id;
        }
        else {
            $temp = $this->user_model->add($data);
            return $temp;
        }
    }
}

/**
 * 用户等级相关方法
 *
 * @author Mosquito
 * @link www.360cd.cn
 */
class UserGrade {
    protected $grade_model;
    
    function __construct() {
        $this->grade_model = &m('user_grade');
    }
    
    static function init() {
        return new UserGrade();
    }
    
    /**
     * 检查等级数据是否合法
     * @param array $data
     * @param string $type = 'add', 'edit'
     */
    function check_grade_data($data, $type) {
        if ($data['grade_name']) {
            if ($type == 'add') {
                $temp = $this->grade_model->get("grade_name = '{$data['grade_name']}'");
                if ($temp) {
                    return false;
                }
            }
        }
        else {
            return false;
        }
        if ($data['priority'] > 0) {
            if ($type == 'add') {
                $temp = $this->grade_model->get("priority = {$data['priority']}");
                if ($temp) {
                    return false;
                }
            }
        }
        else {
            return false;
        }
        
        if ($data['upgrade']['buy'] < 0) {
            return false;
        }
        
        if ($data['other']['buy_tc'] < 0 || $data['other']['buy_tc'] > 1) {
            return false;
        }
        
        if ($data['other']['sell_tc'] < 0  || $data['other']['sell_tc'] > 1) {
            return false;
        }
        
        return true;
    }
    
    /**
     * 获取最大优先级值
     */
    function get_max_priority($offset = 0) {
        $temp = $this->grade_model->get(array(
            'fields' => 'MAX(priority) AS priority',
        ));
        return intval($temp['priority']) + $offset;
    }
    
    /**
     * 获取初始等级
     */
    function get_init_grade() {
        $grade_info = $this->grade_model->get(array(
            'order' => 'priority',
        ));
        return $grade_info;
    }
    
    /**
     * 获取等级选择项
     */
    function get_grade_options() {
        $grade_list = $this->grade_model->find(array(
            'order' => 'priority',
        ));
        $grade_options = array();
        foreach ($grade_list as $v) {
            $grade_options[$v['id']] = $v['grade_name'];
        }
        return $grade_options;
    }
    
    /**
     * 获取指定等级的下一个等级信息
     * @param unknown $grade_id
     * @return unknown
     */
    function get_next_grade($grade_id) {
        $grade_info = $this->grade_model->get($grade_id);
        if (!$grade_info) {
            return array();
        }
        $next_grade_info = $this->grade_model->get(array(
            'conditions' => "priority > {$grade_info['priority']}",
            'order' => 'priority',
        ));
        
        return $next_grade_info;
    }
    
    /**
     * 更新等级
     */
    function update_grade($user_id) {
        
        //需要更新用户的信息
        $member_model = &m('member');
        $member_info = $member_model->get($user_id);
        if (!$member_info) {
            return 0;
        }
        
        //得到用户下一等级级信息
        $next_grade_info = $this->get_next_grade($member_info['grade_id']);
        if (!$next_grade_info) {
            return 0;
        }
        $next_grade_info['upgrade'] = unserialize($next_grade_info['upgrade']);
        //$next_grade_info['other'] = unserialize($next_grade_info['other']);
        
        //验证是否已达到升级条件
        //推荐会员累计购物
        $total_buy = $member_model->get(array(
            'fields' => 'SUM(total_buy) AS buy',
            'conditions' => "parent_id = {$member_info['user_id']}",
        ));
        
        if ($total_buy['buy'] < $next_grade_info['upgrade']['buy']) {
            return -1;
        }
        
        //达到条件，更新等级
        $member_model->edit($member_info['user_id'], "grade_id = {$next_grade_info['id']}");
        
        return 1;
    }
}
<?php

class User {
    protected $user_model;
    
    function __construct() {
        $this->user_model = &m('member');
    }
    
    static function init() {
        if (!$instance['User']) {
            static $instance = array();
            $instance['User'] = new User();
        }
        return $instance['User'];
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
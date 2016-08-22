<?php

/* 文章 article */
class Point_logsModel extends BaseModel
{
    public $table     = 'point_logs';
    public $prikey    = 'id';
    public $_name     = 'point_logs';
    public $_relation = array(
        // 一个会员拥有多个收货地址
    );

    /**
     *修改积分信息
     *$user_id=用户ID
     *$point=积分数
     *$time=时间
     *$type=积分类型
     *$remark=备注
     */
    public function change_point($user_id, $point, $time, $type, $remark = '')
    {
        $types = $this->getPointSet($type);
        if (trim($types['method']) == 'add') {
            $this->_change_user_point($user_id, $point);
            $remark = " 得到" . $point . "积分 " . $remark;
        } else {
            $this->_change_user_point($user_id, $point, 0);
            $remark = " 消费" . $point . "积分 " . $remark;
        }
        $remark = $types['label'] . '--' . $remark;
        return $this->_change_point_logs($user_id, $point, $time, $type, $remark);
    }

    /**
     * 修改用户积分
     * $user_id= 用户ID
     * $point= 积分数
     * $type=修改类型，1为增加积分，0为减少积分
     */

    public function _change_user_point($user_id, $point, $type = 1)
    {
        $user_ext  = &m('member_ext');
        $user_data = $user_ext->get(' user_id=' . $user_id);
        if (!$user_data) {
            $user_ext->add(array('user_id' => $user_id));
            $user_data = array('integral' => 0);
        }
        if ($type) {
            $user_ext->edit(' user_id=' . $user_id, "total_integral = total_integral + {$point}");
            $point = $user_data['integral'] + $point;
        } else {
            $point = $user_data['integral'] - $point;
        }
        /*$level_id=$this->getLevelByPoint($point);*/
        $user_ext->edit(' user_id=' . $user_id, array('integral' => $point/*,'user_level_id'=>$level_id*/));
        if ($user_ext->has_error()) {
            return 0;
        }
        return 1;
    }
    /**
     *修改积分消费记录
     *$user_id=用户ID
     *$point=积分数
     *$time=消费时间
     *$type=消费类型
     *$remark=备注说明
     */
    public function _change_point_logs($user_id, $point, $time, $type, $remark)
    {
        $user_mod   = &m('member');
        $user_data  = $user_mod->get_info($user_id);
        $point_logs = &m('point_logs');
        if (!$user_data) {
            return 0;
        }
        $data              = array();
        $data['user_id']   = $user_id;
        $data['user_name'] = $user_data['user_name'];
        $data['point']     = $point;
        $data['addtime']   = $time;
        $data['type']      = $type;
        $data['remark']    = $remark;
        $id                = $point_logs->add($data);
        $this->sendMsg($user_id, $remark, $remark);
        if (!$id) {
            return 0;
        }
        return $id;
    }

    /**
     *得到配置的积分信息
     */
    public function getPointSet($type)
    {
        $path = ROOT_PATH . "/external/modules/point/point_set.config.php";
        if (!file_exists($path)) {
            return;

        }
        $data = include $path;
        if (!(isset($data) && is_array($data) && count($data) > 0)) {
            return;
        }

        return $data[$type];
    }

    public function getTypeList()
    {
        $path = ROOT_PATH . "/external/modules/point/point_set.config.php";
        if (!file_exists($path)) {
            return;

        }
        $data = include $path;
        if (!(isset($data) && is_array($data) && count($data) > 0)) {
            return;
        }
        $list = array();
        foreach ($data as $d) {
            $list[$d['name']] = $d['label'];
        }
        return $list;
    }

    public function getLevelByPoint($point)
    {
        $level_mod  = &m("member_grade");
        $level_info = $level_mod->get(array(
            'conditions' => "total_integral <= {$point}",
            'order' => 'priority DESC, grade_id DESC',
        ));
        if ($level_info) {
            return $level_info['grade_id'];
        }
        return 0;
    }

    //发送站内短消息
    public function sendMsg($user_id, $title, $content)
    {
        $msg_mod = &m('message');
        return $msg_mod->send(MSG_SYSTEM, $user_id, $title, $content);
    }
}

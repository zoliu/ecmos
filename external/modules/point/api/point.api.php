<?php
class PointApi
{
    public function change_point($user_id, $point, $time, $type, $reamrk)
    {
        $types = $this->getPointSet($type);
        if ($types['method'] == 'add') {
            $this->_change_user_point($user_id, $point);
            $remark = " 得到" . $point . "积分";
        } else {
            $this->_change_user_point($user_id, $point, 1);
            $remark = " 消费" . $point . "积分";
        }
        $remark = $types['label'] . '--' . $remark;
        return $this->_change_point_logs($user_id, $point, $time, $type, $remark);
    }

    public function _change_user_point($user_id, $point, $type = 1)
    {
        $user_ext  = &m('member_ext');
        $user_data = $user_ext->get(' user_id=' . $user_id);
        if (!$user_data) {
            $user_ext->add(array('user_id' => $user_id));
            $user_data = array('user_point' => 0);
        }
        if ($type) {
            $point = $user_data['user_point'] + $point;
        } else {
            $point = $user_data['user_point'] - $point;
        }
        $user_ext->edit(' user_id=' . $user_id, array('user_point' => $point));
        if ($user_ext->has_error()) {
            return 0;
        }
        return 1;
    }

    public function _change_point_logs($user_id, $point, $time, $type, $remark)
    {
        $user_mod  = &m('member');
        $user_data = $user_mod->get_info($user_id);
        $point     = &m('point_logs');
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
        $id                = $point->add($data);
        if (!$id) {
            return 0;
        }
        return $id;
    }

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
}

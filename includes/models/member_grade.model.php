<?php

class Member_gradeModel extends BaseModel
{
    public $table  = 'member_grade';
    public $prikey = 'grade_id';
    public $_name  = 'member_grade';

    public $_relation = array(
        'has_member_level_ext' => array(
            'model'       => 'member_ext',
            'type'        => HAS_MANY,
            'foreign_key' => 'grade_id',
            'dependent'   => true,
        ),
    );

    /**
     * 获取最大优先级值
     */
    public function getMaxPriority($offset = 0)
    {
        $temp = $this->get(array(
            'fields' => 'MAX(priority) AS priority',
        ));
        return intval($temp['priority']) + $offset;
    }

    /**
     * 获取初始等级
     */
    public function getInitGrade()
    {
        $temp = $this->get(array(
            'order' => 'priority, grade_id',
        ));
        return $temp;
    }

    /**
     * 获取等级选择项
     */
    public function getOptions()
    {
        $grade_list = $this->find(array(
            'order' => 'priority, grade_id',
        ));
        $grade_options = array();
        foreach ($grade_list as $value) {
            $grade_options[$value['grade_id']] = $value['grade_name'];
        }
        return $grade_options;
    }

    /**
     * 获取指定等级的下一个等级信息
     * @param unknown $grade_id
     * @return unknown
     */
    public function getNextGrade($grade_id)
    {
        $grade_info = $this->get("grade_id = {$grade_id}");
        if (!$grade_info) {
            return array();
        }

        $next_grade_info = $this->get(array(
            'conditions' => "priority > {$grade_info['priority']}",
            'order'      => 'priority, grade_id',
        ));
        return $next_grade_info;
    }

    /**
     * 获取升级类型选择项
     * @return [array]
     */
    public function getUpgradeTypeOptions() {
    	return array(
    		1 => '累计购物模式',
    		2 => '累计积分模式',
		);
    }

    /**
     * 根据user_id获取等级信息
     */
    public function getGradeinfo($user_id) {
    	$member_ext_model = &m('member_ext');
    	$joinstr = $member_ext_model->parseJoin('grade_id', 'grade_id', 'member_grade');
    	$temp = $member_ext_model->get(array(
    		'joinstr' => $joinstr,
            'fields' => 'user_id, member_grade.*',
            'conditions' => "user_id = {$user_id}",
		));
		
    	return $temp;
    }

    /**
     * 更新等级
     */
    public function updateGrade($user_id)
    {
        //判断升级类型
        import('zllib/methods.lib');
        $filename = ROOT_PATH . '/data/member_level.inc.php';
        $upgrade_type = Methods::load_config($filename, 'upgrade_type');

        switch ($upgrade_type) {
        	case 1:
        		//需要更新用户的信息
		        $member_ext_model = &m('member_ext');
		        $member_info  = $member_ext_model->get("user_id = {$user_id}");
		        if (!$member_info) {
		            return 0;
		        }

		        $member_info  = $member_ext_model->get("user_id = {$member_info['parent_id']}");
		        if (!$member_info) {
		            return 0;
		        }

		        //得到用户下一等级级信息
		        $next_grade_info = $this->getNextGrade($member_info['grade_id']);
		        if (!$next_grade_info) {
		            return 0;
		        }

		        //推荐累计购物模式
		        $joinstr = $member_ext_model->parseJoin('user_id', 'user_id', 'member');
		        $total_buy = $member_ext_model->get(array(
		        	'joinstr' => $joinstr,
		            'fields'     => 'SUM(total_buy) AS buy',
		            'conditions' => "parent_id = {$member_info['user_id']}",
		        ));

		        if ($total_buy['buy'] < $next_grade_info['upgrade_buy']) {
		            return -1;
		        }

		        //达到条件，更新等级
        		$member_ext_model->edit($member_info['user_id'], "grade_id = {$next_grade_info['grade_id']}");
        		break;
    		case 2:
    			//需要更新用户的信息
		        $member_ext_model = &m('member_ext');
		        $member_info  = $member_ext_model->get("user_id = {$user_id}");
		        if (!$member_info) {
		            return 0;
		        }

		        //得到用户下一等级级信息
		        $next_grade_info = $this->getNextGrade($member_info['grade_id']);
		        if (!$next_grade_info) {
		            return 0;
		        }

    			//累计积分模式
    			$joinstr = $member_ext_model->parseJoin('user_id', 'user_id', 'member');
		        $total_buy = $member_ext_model->get(array(
		        	'joinstr' => $joinstr,
		            'fields'     => 'SUM(total_integral) AS integral',
		            'conditions' => "member_ext.user_id = {$member_info['user_id']}",
		        ));

		        if ($total_buy['integral'] < $next_grade_info['upgrade_integral']) {
		            return -1;
		        }

		        //达到条件，更新等级
        		$member_ext_model->edit($member_info['user_id'], "grade_id = {$next_grade_info['grade_id']}");
        		break;
        	default:
        		return 0;
        		break;
        }

        return 1;
    }
}

<?php

/**
 *    管理商城权限
 *
 *    @author    Garbin
 *    @usage    none
 */
class UserprivModel extends BaseModel
{
    var $table = 'user_priv';
    var $prikey= 'user_id';
    var $_name = 'userpriv';
    var $_relation = array(
        'mall_be_manage' => array(
            'model'     => 'member',
            'type'      => BELONGS_TO,
            'reverse'   => 'manage_mall',
        )
    );
    /*
     * 判断是否是管理员
     */
    function check_admin($user_id)
    {
        $conditions = "user_id in (" . $user_id . ")";
        $user_id && $conditions .= " AND store_id = '0'";
        return count($this->find(array('conditions' => $conditions))) == 0;
    }

    /*
     * 判断是否是初始管理员
     */
    function check_system_manager($user_id)
    {
        $conditions = "user_id in (" . $user_id . ")";
        $user_id && $conditions .= " AND store_id = '0'";
        $res = $this->find(array('conditions' => $conditions,
            'fields' => 'privs'));
        foreach ($res as $key => $val)
        {
            if ($val['privs'] == 'all')
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }
        /*
     * 取得管理员ID
     */
     function get_admin_id()
    {
        $conditions = ' AND store_id = 0';
        //更新排序
        $sort  = 'user_id';
        $order = 'asc';
        $user_id = $this->find(array(
            'conditions' => '1=1' . $conditions,
            'limit' => $limit,
            'order' => "$sort $order",
            'count' => true,
        ));
        return $user_id;
     }
}
?>
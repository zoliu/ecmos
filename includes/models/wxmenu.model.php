<?php

/**
 *    微信公众平台信息菜单模型
 *
 *    @author    hzj1216000
 *    @usage    none
 */
class WxmenuModel extends BaseModel {

    var $table = 'wxmenu';
    var $prikey = 'id';
    var $_name = 'wxmenu';
    /* 与其它模型之间的关系 */
    var $_relation = array(
    );

    function name_exists($name, $pid, $id = 0) {
        $where = "name='" . $name . "' AND pid='" . $pid . "' AND id<>'" . $id . "'";
        $result = $this->getOne("select count(id) from {$this->table} where " . $where);
        $result = !empty($result) ? $result : 0;
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

}

?>
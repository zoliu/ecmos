<?php

/* 微公众平台商家接口 wxconfig */

class WxconfigModel extends BaseModel {

    var $table = 'wxconfig';
    var $prikey = 'w_id';
    var $_name = 'wxconfig';

    /*
     * 判断名称是否唯一
     */

    function unique($user_id = 0) {
        return $this->getOne("select w_id from {$this->table} where user_id='$user_id'");
    }

    function get_info_user($user_id = 0) {
        return $this->get(' user_id='.$user_id);
    }

    

}

?>
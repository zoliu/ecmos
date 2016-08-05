<?php

/* 商家微信公众平台信息keyword */

class WxkeywordModel extends BaseModel {

    var $table = 'wxkeyword';
    var $prikey = 'kid';
    var $_name = 'wxkeyword';

    function get_follow_id($user_id = 0) {
        if ($user_id != 0) {
            $sql = "SELECT kid FROM {$this->table} WHERE isfollow = 1 AND user_id ='" . $user_id . "'";
        } else {
            $sql = "SELECT kid FROM {$this->table} WHERE isfollow = 1 AND user_id ='" . $_SESSION['user_info']['user_id'] . "'";
        }
        return $this->getOne($sql);
    }

    function get_mess_id($user_id = 0) {
        if ($user_id != 0) {
            $sql = "SELECT kid FROM {$this->table} WHERE ismess = 1 AND user_id ='" . $user_id . "'";
        } else {
            $sql = "SELECT kid FROM {$this->table} WHERE ismess = 1 AND user_id ='" . $_SESSION['user_info']['user_id'] . "'";
        }
        return $this->getOne($sql);
    }

    function get_kword($str, $user_id) {
        if($data=$this->_get_wxkeyword($str,$user_id))
        {
            return $data;
        }
       
    }


    function _get_wxkeyword($str,$user_id)
    {
          $sql = "SELECT kename, kyword, kecontent, type, titles, imageinfo, linkinfo FROM {$this->table} WHERE user_id ='" . $user_id . "'" . " AND  kyword like '%" . $str . "%'";
        return $this->getRow($sql);

    }



}

?>
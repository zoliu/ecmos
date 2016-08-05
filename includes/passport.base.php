<?php

/**
 *    用户中心连接接口基础类
 *
 *    @author    Garbin
 *    @usage    none
 */
class BasePassport extends Object
{
    var $_name = '';
    var $user = null;
    var $pm = null;
    var $friend = null;
    var $feed = null;
    function __construct()
    {
        $this->BasePassport();
    }
    function BasePassport()
    {
        $user_class_name = ucfirst($this->_name) . 'PassportUser';
        $pm_class_name = ucfirst($this->_name) . 'PassportPM';
        $friend_class_name = ucfirst($this->_name) . 'PassportFriend';
        $feed_class_name = ucfirst($this->_name) . 'PassportFeed';
        $this->user     = new $user_class_name();
        $this->pm       = new $pm_class_name();
        $this->friend   = new $friend_class_name();
        $this->feed     = new $feed_class_name();
    }
    function tag_get($tag)
    {
        return array();
    }
}
/**
 *    用户接口基础类
 *
 *    @author    Garbin
 *    @usage    none
 */
class BasePassportUser extends Object
{
    /**
     *    注册用户
     *
     *    @author    Garbin
     *    @param     string $user_name  欲注册的用户名
     *    @param     string $password   密码
     *    @param     string $email      电子邮件
     *    @return    int        用户ID
     */
    function register($user_name, $password, $email)
    {
        return true;
    }

    /**
     *    修改用户信息
     *
     *    @author    Garbin
     *    @param     int    $user_id    用户ID
     *    @param     string $old_password  原始密码
     *    @param     array  $items      要修改的项目
     *    @param     bool   $force      强制修改
     *    @return    bool
     */
    function edit($user_id, $old_password, $items, $force = false)
    {
        return true;
    }

    /**
     *    删除用户
     *
     *    @author    Garbin
     *    @param     int $user_id       用户ID
     *    @return    bool
     */
    function drop($user_id)
    {
        return true;
    }

    /**
     *    获取用户信息，用户表时刻与第三方用户中心保持一致
     *
     *    @author    Garbin
     *    @param     int $flag  用户ID string $flag 用户名
     *    @param     bool $is_name  是否是用户名
     *    @return    array
     */
    function get($flag, $is_name = false)
    {
        # 必须返回标准的数组
    }

    /**
     *    验证用户登录
     *
     *    @author    Garbin
     *    @param     $string $user_name
     *    @param     $string $password
     *    @return    int    用户ID
     */
    function auth($user_name, $password)
    {
        #TODO
    }

    /**
     *    同步登录
     *
     *    @author    Garbin
     *    @param     int $user_id
     *    @return    string
     */
    function synlogin($user_id)
    {
        return '';
    }

    /**
     *    同步退出
     *
     *    @author    Garbin
     *    @return    string
     */
    function synlogout()
    {
        return '';
    }

    /**
     *    检查电子邮件是否唯一
     *
     *    @author    Garbin
     *    @param     string $email
     *    @return    bool
     */
    function check_email($email)
    {
        #TODO
    }

    /**
     *    检查用户名是否唯一
     *
     *    @author    Garbin
     *    @param     string $user_name
     *    @return    bool
     */
    function check_username($user_name)
    {
        #TODO
    }

    /**
     *    设置头像
     *
     *    @author    Garbin
     *    @param     int $user_id
     *    @return    string
     */
    function set_avatar($user_id)
    {
        #TODO
    }

    /**
     *    删除头像
     *
     *    @author    Garbin
     *    @param     int $user_id
     *    @return    bool
     */
    function drop_avatar($user_id)
    {
        #TODO
    }

    function _local_add($data)
    {
        $model_member =& m('member');
        $user_id = $model_member->add($data);
        if (!$user_id)
        {
            $this->_errors = $model_member->get_error();
            return 0;
        }
        return $user_id;
    }

    function _local_edit($user_id, $data)
    {
        $model_member =& m('member');
        $model_member->edit($user_id, $data);

        return true;
    }
    function _local_drop($user_id)
    {
        $model_member =& m('member');
        $drop_nums = $model_member->drop($user_id);
        if ($model_member->has_error())
        {
            $this->_errors = $model_member->get_error();

            return 0;
        }

        return $drop_nums;
    }
    function _local_get($conditions)
    {
        $model_member =& m('member');

        return $model_member->get($conditions);
    }
    function _local_sync($user_id, $user_name, $email)
    {
        /* 本地保持同步 */
        $local_info = $this->_local_get($user_id);
        if (empty($local_info))
        {
            /* 有可能在用户中心有而本地没有，这时要将其加上 */
            $data = array(
                'user_id'   => $user_id,
                'user_name' => $user_name,
                'password'  => md5(time() . rand(100000, 999999)),
                'email'     => $email,
                'reg_time'  => gmtime()
            );
            $this->_local_add($data);
        }
    }
}

/**
 *    短消息接口基础类
 *
 *    @author    Garbin
 *    @usage    none
 */
class BasePassportPM extends Object
{
    /**
     *    发送短消息
     *
     *    @author    Garbin
     *    @param     int $sender
     *    @param     int $recipient
     *    @param     string $subject
     *    @param     string $message
     *    @param     int $replyto
     *    @return    bool
     */
    function send($sender, $recipient, $subject, $message, $replyto = 0)
    {

    }

    /**
     *    获取短消息内容
     *
     *    @author    Garbin
     *    @param     int  $user_id  拥有者
     *    @param     int  $pm_id    短消息标识
     *    @param     bool $full     是否包括回复 false:不包括 true包括
     *    @return    false:没有消息 array:消息内容
     */
    function get($user_id, $pm_id, $full = false)
    {

    }

    /**
     *    获取消息列表
     *
     *    @author    Garbin
     *    @param     int    $user_id
     *    @param     string $limit
     *    @return    array:消息列表
     */
    function get_list($user_id, $limit = '0, 10', $folder = 'inbox')
    {
        #TODO
    }

    /**
     *    检查是否有短消息
     *
     *    @author    Garbin
     *    @param     int $user_id
     *    @return    false:无新短消息 ture:有新短消息
     */
    function check_new($user_id)
    {
        #TODO
    }

    /**
     *    删除短消息
     *
     *    @author    Garbin
     *    @param     int        $user_id 短消息拥有者
     *    @param     array      $pm_ids  欲删除的短消息
     *    @return    false:失败   true:成功
     */
    function drop($user_id, $pm_ids)
    {
        #TODO
    }

    /**
     *    标记阅读状态
     *
     *    @author    Garbin
     *    @param     int   $user_id   短消息拥有者
     *    @param     array $pm_ids    欲标记的短消息ID数组
     *    @param     int   $status    标记成的状态，0为已读，1为未读
     *    @return    false:标记失败  true:标记成功
     */
    function mark($user_id, $pm_ids, $status = 0)
    {
        #TODO
    }
}

/**
 *    好友接口基础类
 *
 *    @author    Garbin
 *    @usage    none
 */
class BasePassportFriend extends Object
{
    /**
     *    新增一个好友
     *
     *    @author    Garbin
     *    @param     int $user_id       好友拥有者
     *    @param     int $friend_id     好友
     *    @return    false:失败 true:成功
     */
    function add($user_id, $friend_id)
    {
        #TODO
    }

    /**
     *    删除一个好友
     *
     *    @author    Garbin
     *    @param     int $user_id       好友拥有者
     *    @param     array $friend_id     好友
     *    @return    false:失败   true:成功
     */
    function drop($user_id, $friend_ids)
    {
        #TODO
    }

    /**
     *    获取好友总数
     *
     *    @author    Garbin
     *    @param     int $user_id       好友拥有者
     *    @return    int    好友总数
     */
    function get_count($user_id)
    {
        #TODO
    }

    /**
     *    获取好友列表
     *
     *    @author    Garbin
     *    @param     int $user_id       好友拥有者
     *    @return    array  好友列表
     */
    function get_list($user_id, $limit = '0, 10')
    {
        #TODO
    }
}

/**
 *    事件接口基础类
 *
 *    @author    Garbin
 *    @usage    none
 */
class BasePassportFeed extends Object
{
    /**
     *    添加事件
     *
     *    @author    Garbin
     *    @param     array $feed    事件
     *    @return    false:失败   true:成功
     */
    function add($feed)
    {
        #TODO
    }

    /**
     *    获取事件
     *
     *    @author    Garbin
     *    @param     int $limit     条数
     *    @return    array
     */
    function get($limit)
    {
        #TODO
    }

    /**
     * Feed是否启用
     *
     * @author Garbin
     * @return bool
     **/
    function feed_enabled()
    {
        return false;
    }
}

function limit_page_info($limit)
{
    list($start, $size) = explode(',', $limit);
    $start = intval($start);
    $size  = intval($size);
    $page = $start / $size + 1;

    return array($page, $size);
}
?>
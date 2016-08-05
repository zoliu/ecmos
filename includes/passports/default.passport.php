<?php

/**
 *    内置用户中心连接接口
 *
 *    @author    Garbin
 *    @usage    none
 */
class DefaultPassport extends BasePassport
{
    var $_name = 'default';
}

/**
 *    内置用户中心的用户操作
 *
 *    @author    Garbin
 *    @usage    none
 */
class DefaultPassportUser extends BasePassportUser
{

    /* 注册 */
    function register($user_name, $password, $email, $local_data = array())
    {
        if (!$this->check_username($user_name))
        {
            return false;
        }

        if (!$this->check_email($email))
        {
            return false;
        }

        $local_data['user_name']    = $user_name;
        $local_data['password']     = md5($password);
        $local_data['email']        = $email;
        $local_data['reg_time']     = gmtime();

        /* 添加到用户系统 */
        $user_id = $this->_local_add($local_data);

        return $user_id;
    }
    /* 编辑用户数据 */
    function edit($user_id, $old_password, $items, $force = false)
    {
        if (!$force)
        {
            $info = $this->get($user_id);
            if (md5($old_password) != $info['password'])
            {
                $this->_error('auth_failed');

                return false;
            }
        }
        $edit_data = array();
        if (isset($items['password']))
        {
            $edit_data['password']  = md5($items['password']);
        }
        if (isset($items['email']))
        {
            $edit_data['email'] = $items['email'];
        }

        if (empty($edit_data))
        {
            return false;
        }
        //编辑本地数据
        $this->_local_edit($user_id, $edit_data);

        return true;
    }

    /* 删除用户 */
    function drop($user_id)
    {
        return $this->_local_drop($user_id);
    }

    /* 获取用户信息 */
    function get($flag, $is_name = false)
    {
        if ($is_name)
        {
            $conditions = "user_name='{$flag}'";
        }
        else
        {
            $conditions = intval($flag);
        }

        return $this->_local_get($conditions);
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
        $info = $this->get($user_name, true);
        if ($info['password'] != md5($password))
        {
            $this->_error('auth_failed');

            return 0;
        }

        return $info['user_id'];
    }

    /**
     *    同步登录
     *
     *    @author    Garbin
     *    @param     int $user_id
     *    @return    string
     */
    function synlogin($user_id) {}

    /**
     *    同步退出
     *
     *    @author    Garbin
     *    @return    string
     */
    function synlogout() {}

    /**
     *    检查电子邮件是否唯一
     *
     *    @author    Garbin
     *    @param     string $email
     *    @return    bool
     */
    function check_email($email)
    {
        /* 暂时无此设置 */
        return true;

        $model_member =& m('member');
        $info = $model_member->get("email='{$email}'");
        if (!empty($info))
        {
            $this->_error('email_exists');

            return false;
        }

        return true;
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
        $model_member =& m('member');
        $info = $model_member->get("user_name='{$user_name}'");
        if (!empty($info))
        {
            $this->_error('user_exists');

            return false;
        }//用户名已存在返回false

        return true;
    }

    /**
     *    设置头像
     *
     *    @author    Garbin
     *    @param     int $user_id
     *    @return    string
     */
    function set_avatar($user_id = 0)
    {
        return false;
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
        $model_member =& m('member');
        $info = $model_member->get($user_id);

        if ($info['portrait'])
        {
            return _at('unlink', ROOT_PATH . '/' . $info['portrait']);
        }

        return true;
    }
}

/**
 *    内置用户中心的短信操作
 *
 *    @author    Garbin
 *    @usage    none
 */
class DefaultPassportPM extends BasePassportPM
{
    var $show_announce;
    function __construct()
    {
        $this->UcPassportPM();
    }
    function UcPassportPM()
    {
        $this->show_announce = false;
        Lang::load(ROOT_PATH . '/includes/passports/' . MEMBER_TYPE . '/' . LANG . '/common.lang.php');
        if (file_exists(ROOT_PATH . '/data/msg.lang.php'))
        {
            Lang::load(ROOT_PATH . '/data/msg.lang.php');
        }
    }
    /**
     *    发送短消息
     *
     *    @author    Garbin
     *    @param     int $sender        发送者
     *    @param     array $recipient     接收者
     *    @param     string $subject    标题
     *    @param     string $message    内容
     *    @param     int $replyto       回复主题
     *    @return    false:失败   true:成功
     */
    function send($sender, $recipient, $subject, $message, $replyto = 0)
    {
        $model_message =& m('message');
        $msg_id = $model_message->send($sender, $recipient, '', $message, $replyto);
        if (!$msg_id)
        {
            $this->_errors = $model_message->get_error();

            return 0;
        }

        return $msg_id;
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
        $model_message =& m('message');
        $topic = $model_message->get(array(
            'fields'     => 'this.*',
            'conditions' => 'msg_id=' . $pm_id . ' AND parent_id=0 AND ((status IN (1,3) AND to_id = ' . $user_id . ') OR (status IN (2,3) AND from_id = ' . $user_id . '))',
        ));
        if (empty($topic))
        {
            return array();
        }
        if ($topic['from_id'] == MSG_SYSTEM)
        {
            $topic['user_name'] = Lang::get('system_message');
            $topic['system'] = 1;
        }
        $topic['new'] = (($topic['from_id'] == $user_id && $topic['new'] == 2)||($topic['to_id'] == $user_id && $topic['new'] == 1 )) ? 1 : 0;
        $topic['portrait'] = portrait($topic['from_id'], $topic['portrait']);
        if ($full)
        {
            $replies = $model_message->find(array(
                'fields'     => 'this.*',
                'conditions' => 'parent_id=' . $pm_id,
            ));
        }

        return array(
            'topic' => $topic,
            'replies' => $replies
        );
    }

    /**
     *    获取消息列表
     *
     *    @author    Garbin
     *    @param     int    $user_id
     *    @param     string $limit
     *    @param     string $folder 可选值:inbox, outbox
     *    @return    array:消息列表
     */
    function get_list($user_id, $page, $folder = 'privatepm')
    {
        $limit = $page['limit'];
        $condition = '';
        switch ($folder)
        {
            case 'privatepm':
                $condition = '((from_id = ' . $user_id . ' AND status IN (2,3)) OR (to_id = ' . $user_id . ' AND status IN (1,3)) AND from_id > 0)';
            break;
            case 'systempm':
                $condition = 'from_id = ' . MSG_SYSTEM . ' AND to_id = ' . $user_id;
            break;
            case 'announcepm':
                $condition = 'from_id = 0 AND to_id = 0';
            break;
            default:
                $condition = '((new = 1 AND status IN (1,3) AND to_id = ' . $user_id . ') OR (new =2 AND status IN (2,3) AND from_id = ' . $user_id . '))';
            break;
        }
        $model_message =& m('message');
        $messages = $model_message->find(array(
            'fields'        =>'this.*',
            'conditions'    =>  $condition .' AND parent_id=0 ',
            'count'         => true,
            'limit'         => $limit,
            'order'         => 'last_update DESC',
        ));
        $subject = '';
        if (!empty($messages))
        {
            foreach ($messages as $key => $message)
            {
                $messages[$key]['new'] = (($message['from_id'] == $user_id && $message['new'] == 2)||($message['to_id'] == $user_id && $message['new'] == 1 )) ? 1 : 0; //判断是否是新消息
                $subject = $this->removecode($messages[$key]['content']);
                $messages[$key]['content'] = htmlspecialchars($subject);
                $message['from_id'] == MSG_SYSTEM && $messages[$key]['user_name'] = Lang::get('system_message'); //判断是否是系统消息
            }
        }
        return array(
            'count' => $model_message->getCount(),
            'data' => $messages
        );
    }
    
    function removecode($str) {
        $rs = trim(preg_replace(array(
            "/\[(img)=?.*\].*?\[\/(img)\]/siU",
            "/\[\/?(url)=?.*\]/siU",
            "/\r\n/",
            ), '', $str));
        return $rs;
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
        $model_message =& m('message');
        
        $new = $model_message->check_new($user_id);
        return $new['total'];
    }

    /**
     *    删除短消息
     *
     *    @author    Garbin
     *    @param     int        $user_id 短消息拥有者
     *    @param     array      $pm_ids  欲删除的短消息
     *    @param     string     $foloder    可选值:inbox,outbox
     *    @return    false:失败   true:成功
     */
    function drop($user_id, $pm_ids)
    {
        $model_message =& m('message');
        if (!$model_message->msg_drop($pm_ids, $user_id))
        {
            $this->_errors = $model_message->get_error();

            return false;
        }

        return true;
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
        
        $model_message =& m('message');
        $model_message->edit($pm_ids, array(
            'new'   => $status,
        ));

        return (!$model_message->has_error());
    }
    
    /**
     *  短消息过滤
     *
     *  @return string 
     */
    function msg_filter($message)
    {
        $message = str_replace('&amp;', '&', $message); // 防止URL中的&被重复转义
        $message = htmlspecialchars($message);
        if(strpos($message, '[/url]') !== FALSE)
        {
            $message = preg_replace("/\[url(=((https?|ftp|gopher|news|telnet|rtsp|mms|callto|bctp|ed2k|thunder|synacast){1}:\/\/|www\.)([^\[\"']+?))?\](.+?)\[\/url\]/ies", "\$this->parseurl('\\1', '\\5')", $message);
        }
        if(strpos($message, '[/img]') !== FALSE)
        {
            $message = preg_replace(array(
                "/\[img\]\s*([^\[\<\r\n]+?)\s*\[\/img\]/ies",
                "/\[img=(\d{1,4})[x|\,](\d{1,4})\]\s*([^\[\<\r\n]+?)\s*\[\/img\]/ies"
                ), array(
                "\$this->bbcodeurl('\\1', '<img src=\"%s\" border=\"0\" alt=\"\" />')",
                "\$this->bbcodeurl('\\3', '<img width=\"\\1\" height=\"\\2\" src=\"%s\" border=\"0\" alt=\"\" />')"),
                $message);
        }
        return nl2br(str_replace(array("\t", '   ', '  '), array('&nbsp; &nbsp; &nbsp; &nbsp; ', '&nbsp; &nbsp;', '&nbsp;&nbsp;'), $message));
    }
         
    function bbcodeurl($url, $tags) 
    {
        if(!preg_match("/<.+?>/s", $url)) 
        {
            if(!in_array(strtolower(substr($url, 0, 6)), array('http:/', 'https:', 'ftp://', 'rtsp:/', 'mms://'))) 
            {
                $url = 'http://'.$url;
            }
            return str_replace(array('submit', 'logging.php'), array('', ''), sprintf($tags, $url, addslashes($url)));
        } 
        else 
        {
            return '&nbsp;'.$url;
        }
    }
    
    function parseurl($url, $text) 
    {
        if(!$url && preg_match("/((https?|ftp|gopher|news|telnet|rtsp|mms|callto|bctp|ed2k|thunder|synacast){1}:\/\/|www\.)[^\[\"']+/i", trim($text), $matches))
        {
            $url = $matches[0];
            $length = 65;
            if(strlen($url) > $length)
            {
                $text = substr($url, 0, intval($length * 0.5)).' ... '.substr($url, - intval($length * 0.3));
            }
            return '<a href="'.(substr(strtolower($url), 0, 4) == 'www.' ? 'http://'.$url : $url).'" target="_blank">'.$text.'</a>';
        }
        else
        {
            $url = substr($url, 1);
            if(substr(strtolower($url), 0, 4) == 'www.')
            {
                $url = 'http://'.$url;
            }
            return '<a href="'.$url.'" target="_blank">'.$text.'</a>';
        }
    }
}

/**
 *    内置用户中心的好友操作
 *
 *    @author    Garbin
 *    @usage    none
 */
class DefaultPassportFriend extends BasePassportFriend
{
    /**
     *    新增一个好友
     *
     *    @author    Garbin
     *    @param     int $user_id       好友拥有者
     *    @param     array $friend_ids    好友
     *    @return    false:失败 true:成功
     */
    function add($user_id, $friend_ids)
    {
        $model_member =& m('member');
        $user_data = array();
        foreach ($friend_ids as $friend_id)
        {
            if ($friend_id == $user_id)
            {
                $this->_error('cannot_add_myself');

                return false;
            }
            $user_data[$friend_id] = array(
                'add_time'  => gmtime()
            );
        }

        return $model_member->createRelation('has_friend', $user_id ,$user_data);
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
        $model_member =& m('member');

        return $model_member->unlinkRelation('has_friend', $user_id ,$friend_ids);
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
        $model_member =& m('member');

        return count($model_member->getRelatedData('has_friend', array($user_id)));
    }

    /**
     *    获取好友列表
     *
     *    @author    Garbin
     *    @param     int $user_id       好友拥有者
     *    @param     string $limit      条数
     *    @return    array  好友列表
     */
    function get_list($user_id, $limit = '0, 10')
    {
        $model_member =& m('member');
        $friends = $model_member->getRelatedData('has_friend', array($user_id), array(
            'limit' => $limit,
            'order' => 'add_time DESC',
        ));
        if (empty($friends))
        {
            $friends = array();
        }
        else
        {
            foreach ($friends as $_k => $f)
            {
                $friends[$_k]['portrait'] = portrait($f['user_id'], $f['portrait']);
            }
        }

        return $friends;
    }
    
}

/**
 *    内置用户中心的事件操作
 *
 *    @author    Garbin
 *    @usage    none
 */
class DefaultPassportFeed extends BasePassportFeed
{
    /**
     *    添加事件
     *
     *    @author    Garbin
     *    @param     array $feed    事件
     *    @return    false:失败   true:成功
     */
    function add($feed) {}

    /**
     *    获取事件
     *
     *    @author    Garbin
     *    @param     int $limit     条数
     *    @return    array
     */
    function get($limit) {}
}

?>
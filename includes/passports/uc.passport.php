<?php

include (ROOT_PATH . '/uc_client/client.php');

/**
 *    UCenter连接接口
 *
 *    @author    Garbin
 *    @usage    none
 */
class UcPassport extends BasePassport
{
    var $_name = 'uc';
    function tag_get($tag)
    {
        $cache_server = &cache_server();
        $cache_key    = 'uc_app_list';
        $uc_app_list  = $cache_server->get($cache_key);
        if ($uc_app_list === false)
        {
            $uc_app_list = outer_call('uc_app_ls');
            $cache_server->set($cache_key, $uc_app_list, 86400);
        }
        $nums = array();
        $related_info = array('count' => 0);
        foreach ($uc_app_list as $app_id => $app_info)
        {
            $nums[$app_id] = 10;
            $related_info['list'][$app_id] = array(
                'app_name' => $app_info['name'],
                'app_type' => $app_info['type'],
                'app_url'  => $app_info['url'],
                'data'     => array(),
            );
        }
        $data_list = outer_call('uc_tag_get', array($tag, $nums));
        if ($data_list)
        {
            foreach ($data_list as $_data_app_id => $data)
            {
                foreach ($data['data'] as $value)
                {
                    $data_key = array_keys($value);
                    array_walk($data_key, create_function('&$item, $key', '$item=\'{\' . $item . \'}\';'));
                    $item = str_replace($data_key, $value, $uc_app_list[$_data_app_id]['tagtemplates']['template']);
                    $related_info['count']++;
                    $related_info['list'][$_data_app_id]['data'][] = $item;
                }
            }
        }

        return $related_info;
    }    
}

/**
 *    UCenter的用户操作
 *
 *    @author    Garbin
 *    @usage    none
 */
class UcPassportUser extends BasePassportUser
{

    /* 注册 */
    function register($user_name, $password, $email, $local_data = array())
    {
        /* 到UCenter注册 */
        $user_id = outer_call('uc_user_register', array($user_name, $password, $email));
        if ($user_id < 0)
        {
            switch ($user_id)
            {
                case -1:
                    $this->_error('invalid_user_name');
                    break;
                case -2:
                    $this->_error('blocked_user_name');
                    break;
                case -3:
                    $this->_error('user_exists');
                    break;
                case -4:
                    $this->_error('email_error');
                    break;
                case -5:
                    $this->_error('blocked_email');
                    break;
                case -6:
                    $this->_error('email_exists');
                    break;
            }

            return false;
        }

        /* 同步到本地 */
        $local_data['user_name']    = $user_name;
        $local_data['password']     = md5(time() . rand(100000, 999999));
        $local_data['email']        = $email;
        $local_data['reg_time']     = gmtime();
        $local_data['user_id']      = $user_id;

        /* 添加到用户系统 */
        $this->_local_add($local_data);

        return $user_id;
    }

    /* 编辑用户数据 */
    function edit($user_id, $old_password, $items, $force = false)
    {
        $new_pwd = $new_email = '';
        if (isset($items['password']))
        {
            $new_pwd  = $items['password'];
        }
        if (isset($items['email']))
        {
            $new_email = $items['email'];
        }
        $info = $this->get($user_id);
        if (empty($info))
        {
            $this->_error('no_such_user');

            return false;
        }

        /* 先到UCenter修改 */
        $result = outer_call('uc_user_edit', array($info['user_name'], $old_password, $new_pwd, $new_email, $force));
        if ($result != 1)
        {
            switch ($result)
            {
                case 0:
                case -7:
                    return true;
                    break;
                case -1:
                    $this->_error('auth_failed');

                    return false;
                    break;
                case -4:
                    $this->_error('email_error');

                    return false;
                    break;
                case -5:
                    $this->_error('blocked_email');

                    return false;
                    break;
                case -6:
                    $this->_error('email_exists');

                    return false;
                    break;
                case -8:
                    $this->_error('user_protected');

                    return false;
                    break;
                default:
                    $this->_error('unknow_error');

                    return false;
                    break;
            }
        }

        /* 成功后编辑本地数据 */
        $local_data = array();
        if ($new_pwd)
        {
            $local_data['password'] = md5(time() .  rand(100000, 999999));
        }
        if ($new_email)
        {
            $local_data['email']    = $new_email;
        }

        //编辑本地数据
        $this->_local_edit($user_id, $local_data);

        return true;
    }

    /* 删除用户 */
    function drop($user_id)
    {
        if (empty($user_id))
        {
            $this->_error('no_such_user');

            return false;
        }

        /* 先到UCenter中删除 */
        /*$result = outer_call('uc_user_delete', array($user_id));
        outer_call('uc_user_deleteavatar', array($user_id));
        if (!$result)
        {
            $this->_error('uc_drop_user_failed');

            return false;
        }*/

        /* 再删除本地的 */
        return $this->_local_drop($user_id);
    }

    /* 获取用户信息 */
    function get($flag, $is_name = false)
    {
        /* 至UCenter取用户 */
        $user_info = outer_call('uc_get_user', array($flag, !$is_name));
        if (empty($user_info))
        {
            $this->_error('no_such_user');

            return false;
        }
        list($user_id, $user_name, $email) = $user_info;

        /* 同步至本地 */
        $this->_local_sync($user_id, $user_name, $email);

        return array(
                'user_id'   =>  $user_id,
                'user_name' =>  $user_name,
                'email'     =>  $email,
                'portrait'  =>  portrait($user_id, '')
                );
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
        register_shutdown_function('restore_error_handler'); // 恢复PHP系统默认的错误处理
        $result = outer_call('uc_user_login', array($user_name, $password));
        if ($result[0] < 0)
        {
            switch ($result[0])
            {
                case -1:
                    $this->_error('no_such_user');
                    break;
                case -2:
                    $this->_error('password_error');
                    break;
                case -3:
                    $this->_error('answer_error');
                    break;
                default:
                    $this->_error('unknow_error');
                    break;
            }

            return false;
        }

        /* 同步到本地 */
        $this->_local_sync($result[0], $result[1], $result[3]);

        /* 返回用户ID */
        return $result[0];
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
        return outer_call('uc_user_synlogin', array($user_id));
    }

    /**
     *    同步退出
     *
     *    @author    Garbin
     *    @return    string
     */
    function synlogout()
    {
        return outer_call('uc_user_synlogout');
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
        $result = outer_call('uc_user_checkemail', array($email));
        if ($result < 0)
        {
            switch ($result)
            {
                case -4:
                    $this->_error('email_error');
                    break;
                case -5:
                    $this->_error('blocked_email');
                    break;
                case -6:
                    $this->_error('email_exists');
                    break;
                default:
                    $this->_error('unknow_error');
                    break;
            }

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
        $result = outer_call('uc_user_checkname', array($user_name));
        if ($result < 0)
        {
            switch ($result)
            {
                case -1:
                    $this->_error('invalid_user_name');
                    break;
                case -2:
                    $this->_error('blocked_user_name');
                    break;
                case -3:
                    $this->_error('user_exists');
                    break;
                default:
                    $this->_error('unknow_error');
                    break;
            }
            return false;
        }

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
        return outer_call('uc_avatar', array($user_id));
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
        return outer_call('uc_user_deleteavatar', array($user_id));
    }
}

/**
 *    内置用户中心的短信操作
 *
 *    @author    Garbin
 *    @usage    none
 */
class UcPassportPM extends BasePassportPM
{
    var $show_announce;
    function __construct()
    {
        $this->UcPassportPM();
    }
    function UcPassportPM()
    {
        $this->show_announce = true;
        Lang::load(ROOT_PATH . '/includes/passports/' . MEMBER_TYPE . '/' . LANG . '/common.lang.php');
        if (file_exists(ROOT_PATH . '/data/msg.lang.php'))
        {
            Lang::load(ROOT_PATH . '/data/msg.lang.php');
        }
    }
    /**
     * 发送短消息
     *
     * @param int $sender   发送者
     * @param array $recipient  接收者
     * @param string $subject   短消息标题
     * @param string $message   短消息内容
     * @param int $replyto      0：发送新的短消息 大于0：回复短消息
     * @return 大于 0:发送成功的最后一条消息 ID
                0:发送失败
                -1:超出了24小时最大允许发送短消息数目
                -2:不满足两次发送短消息最短间隔
                -3:不能给非好友批量发送短消息
                -4:目前还不能使用发送短消息功能（注册多少日后才可以使用发短消息限制）
     */
    function send($sender, $recipient, $subject, $message, $replyto = 0)
    { 
        $recipient = is_array($recipient) ? implode(',', $recipient) : $recipient;
        return  outer_call('uc_pm_send',array($sender, $recipient, '',$message, 1, 0, 0));
    }
    /**
     * 取得与特定会员的所有会话
     *
     * @param int $user_id  拥有者
     * @param int $pm_id
     * @return array 包含主题和回复的短消息（UC没有主题和回复的区分，只将第一条作为主题，其他的作为回复）
     */
    function get($user_id, $pm_id, $full = false)
    {

        $message = outer_call('uc_pm_viewnode', array($user_id, 0, $pm_id));
        if (empty($message))
        {
            return array();
        }
        $uid = ($user_id == $message['msgfromid']) ? $message['msgtoid'] : $message['msgfromid'];
        if ($message['msgfromid'] == MSG_SYSTEM)
        {
            $uid = 0;
        }
        $rs = outer_call('uc_pm_view', array($user_id, '', $uid, 5));
        $new = 0;
        if (empty($uid))
        {
            $rs = array($message);   
        }
        $result = array();
        foreach ($rs as $value)
        {
            $result[$value['pmid']]['from_id'] = $value['msgfromid'];
            $result[$value['pmid']]['to_id'] = $value['msgtoid'];
            $result[$value['pmid']]['new'] = $value['new'];
            $result[$value['pmid']]['add_time'] = $value['dateline'];
            $tmp = '';
            $result[$value['pmid']]['content'] = $value['message'];
            $result[$value['pmid']]['msg_id'] = $value['pmid'];
            if (empty($new) && $value['new'])
            {
                $new = 1;
            }
        }  
        $topic['new'] = $new;
        $topic = current(array_slice($result, 0, 1));
        
        $replies = array_slice($result, 1);
        return array('topic' => $topic, 'replies' => $replies);
    }
    /**
     * 取得未读短消息、私人短信、系统短信、公告短信
     *
     * @param int $user_id
     * @param array $page 包含limit,curr_page,pageper
     * @param string $folder
     * @return array  包含 data count 的数组
     */
    function get_list($user_id, $page, $folder = 'newpm')
    {
        $result = array();
        $rs = array();
        
        switch ($folder)
        {
            case 'newpm':
                $rs = outer_call('uc_pm_list', array($user_id, $page['curr_page'], $page['pageper'], 'inbox', 'newpm', 200));
                break;
            case 'privatepm':
                $rs = outer_call('uc_pm_list', array($user_id, $page['curr_page'], $page['pageper'], 'inbox', 'privatepm', 200));
                break;
            case 'systempm':
                $rs = outer_call('uc_pm_list', array($user_id, $page['curr_page'], $page['pageper'], 'inbox', 'systempm', 200));
                break;
            case 'announcepm':
                $rs = outer_call('uc_pm_list', array($user_id, $page['curr_page'], $page['pageper'], 'inbox', 'announcepm', 200));
                break;
        }
        $new = 0;
        $tmp = '';
        !isset($rs['data']) && $rs['data'] = array();
        // 将取出的数据格式化成本地的数据
        foreach ($rs['data'] as $value)
        {
            $result[$value['pmid']]['from_id'] = $value['msgfromid'];
            $result[$value['pmid']]['to_id'] = $value['msgtoid'];
            $result[$value['pmid']]['new'] = $value['new'];
            $result[$value['pmid']]['last_update'] = $value['dateline'];
            $result[$value['pmid']]['msg_id'] = $value['pmid'];    

            $result[$value['pmid']]['content'] = $value['subject'];
        }
        return array('data' => $result, 'count' => $rs['count']);
    }

    /**
     *    检查是否有短消息
     *
     *    @param     int $user_id
     *    @return    int 新消息的数量
     * 
     * */
    function check_new($user_id)
    {
        $rs = outer_call('uc_pm_checknew', array($user_id, 4));
        return $rs['newpm'];
    }

    /**
     *    删除短消息
     *
     *    @author    Garbin
     *    @param     int        $user_id 短消息拥有者
     *    @param     array      $pm_ids  欲删除的短消息
     *    @param     string     $foloder    可选值:inbox,outbox
     *    @return    int        删除的短消息数量
     */
    function drop($user_id, $pm_ids, $folder = 'inbox', $relate = false)
    { 
        $pm_ids = is_array($pm_ids) ? $pm_ids : array($pm_ids);
        if ($relate)
        {
            $pm_id = $pm_ids[0];
            $message = outer_call('uc_pm_viewnode', array($user_id, 0, $pm_id));
            $uid = ($user_id == $message['msgfromid']) ? $message['msgtoid'] : $message['msgfromid'];
            return outer_call('uc_pm_deleteuser',array($user_id, array($uid)));
        }
        return outer_call('uc_pm_delete', array($user_id, $folder, $pm_ids));
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

        $uids = array();
        
        foreach ($pm_ids as $id)
        {
            $message = outer_call('uc_pm_viewnode', array($user_id, 0, $id));
            if ($message['msgfromid'] == 0 && $message['msgtoid'] != 0)
            {
                $uids = array();
                break;
            }
            if ($user_id == $message['msgtoid'])
            {
                $uids[] = $message['msgfromid'];
            }
            else 
            {
                $uids[] = $message['msgtoid'];
            }
        }
        return outer_call('uc_pm_readstatus', array($user_id, $uids, $pm_ids));
    }
    
    /**
     *  短消息显示过滤
     *  @return string
     * 
     */
    function msg_filter($message)
    {
        return str_replace('&amp;', '&', $message); // 防止URL中的&被重复转义;
    }
}

/**
 *    内置用户中心的好友操作
 *
 *    @author    Garbin
 *    @usage    none
 */
class UcPassportFriend extends BasePassportFriend
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
 *    UCenter的事件操作
 *
 *    @author    Garbin
 *    @usage    none
 */
class UcPassportFeed extends BasePassportFeed
{
    /**
     *    添加事件
     *
     *    @author    Garbin
     *    @param     array $feed    事件
     *    @return    false:失败   true:成功
     */
    function add($event, $data)
    {
        $feed_info = $this->_get_feed_info($event, $data);
        return outer_call('uc_feed_add', array($feed_info['icon'], $feed_info['user_id'], $feed_info['user_name'], $feed_info['title']['template'], $feed_info['title']['data'], $feed_info['body']['template'], $feed_info['body']['data'], $feed_info['body_general'], $feed_info['target_ids'], $feed_info['images']));
    }

    /**
     * 通过事件和数据获取feed详细内容
     *
     * @author Garbin
     * @param
     * @return void
     **/
    function _get_feed_info($event, $data)
    {
        $mall_name = '<a href="' . SITE_URL . '">' . Conf::get('site_name') . '</a>';
        switch ($event)
        {
            case 'order_created':
                $feed = array(
                    'icon'  => 'goods',
                    'user_id'  => $data['user_id'],
                    'user_name'  => $data['user_name'],
                    'title'  => array(
                        'template'  => Lang::get('feed_order_created.title'),
                        'data'      => array(
                            'store'    => '<a href="' . $data['store_url'] . '">' . $data['seller_name'] . '</a>',
                            ),
                        ),
                    'body'  => array(
                        'template'  => Lang::get('feed_order_created.body'),
                        ),
                    'images' => $data['images'],
                );
                break;
            case 'store_created':
                $feed = array(
                    'icon'  => 'profile',
                    'user_id' => $data['user_id'],
                    'user_name' => $data['user_name'],
                    'title' => array(
                        'template' => Lang::get('feed_store_created.title'),
                        'data' => array(
                            'mall_name' => $mall_name,
                            'store' => '<a href="' . $data['store_url'] . '">' . $data['seller_name'] . '</a>',

                        ),
                    ),
                    'body'  => array(
                        'template'  => Lang::get('feed_store_created.body'),
                        'data' => array(),
                    ),
                );
                break;
            case 'goods_created':
                $feed = array(
                    'icon' => 'goods',
                    'user_id' => $data['user_id'],
                    'user_name' => $data['user_name'],
                    'title' => array(
                        'template' => Lang::get('feed_goods_created.title'),
                        'data' => array(
                            'goods' => '<a href="' . $data['goods_url'] . '">' . $data['goods_name'] . '</a>'
                        ),
                    ),
                    'body' => array(
                        'template' => Lang::get('feed_goods_created.body'),
                        'data' => array(),
                    ),
                    'images' => $data['images']
                );
                break;
            case 'groupbuy_created':
                $feed = array(
                    'icon' => 'goods',
                    'user_id' => $data['user_id'],
                    'user_name' => $data['user_name'],
                    'title' => array(
                        'template' => Lang::get('feed_groupbuy_created.title'),
                        'data' => array(
                            'groupbuy' => '<a href="' . $data['groupbuy_url'] . '">' . $data['groupbuy_name'] . '</a>'
                        ),
                    ),
                    'body' => array(
                        'template' => Lang::get('feed_groupbuy_created.body'),
                        'data' => array(
                            'groupbuy_message' => $data['message']
                        ),
                    ),
                    'images' => $data['images']
                );
                break;
            case 'goods_collected':
                $feed = array(
                    'icon' => 'goods',
                    'user_id' => $data['user_id'],
                    'user_name' => $data['user_name'],
                    'title' => array(
                        'template' => Lang::get('feed_goods_collected.title'),
                        'data' => array(
                            'goods' => '<a href="' . $data['goods_url'] . '">' . $data['goods_name'] . '</a>'
                        ),
                    ),
                    'body' => array(
                        'template' => Lang::get('feed_goods_collected.body'),
                        'data' => array(),
                    ),
                    'images' => $data['images']
                );
                break;
            case 'store_collected':
                $feed = array(
                    'icon' => 'goods',
                    'user_id' => $data['user_id'],
                    'user_name' => $data['user_name'],
                    'title' => array(
                        'template' => Lang::get('feed_store_collected.title'),
                        'data' => array(
                            'store' => '<a href="' . $data['store_url'] . '">' . $data['store_name'] . '</a>'
                        ),
                    ),
                    'body' => array(
                        'template' => Lang::get('feed_store_collected.body'),
                        'data' => array(),
                    ),
                    'images' => $data['images']
                );
                break;
            case 'goods_evaluated':
                $feed = array(
                    'icon' => 'goods',
                    'user_id' => $data['user_id'],
                    'user_name' => $data['user_name'],
                    'title' => array(
                        'template' => Lang::get('feed_goods_evaluated.title'),
                        'data' => array(
                            'goods' => '<a href="' . $data['goods_url'] . '">' . $data['goods_name'] . '</a>',
                            'evaluation' => $data['evaluation'],
                        ),
                    ),
                    'body' => array(
                        'template' => Lang::get('feed_goods_evaluated.body'),
                        'data' => array(
                            'comment' => $data['comment'],
                        ),
                    ),
                    'images' => $data['images']
                );
                break;
            case 'groupbuy_joined':
                $feed = array(
                    'icon' => 'goods',
                    'user_id' => $data['user_id'],
                    'user_name' => $data['user_name'],
                    'title' => array(
                        'template' => Lang::get('feed_groupbuy_joined.title'),
                        'data' => array(
                            'groupbuy' => '<a href="' . $data['groupbuy_url'] . '">' . $data['groupbuy_name'] . '</a>'
                        ),
                    ),
                    'body' => array(
                        'template' => Lang::get('feed_groupbuy_joined.body'),
                        'data' => array(),
                    ),
                    'images' => $data['images']
                );
                break;
        }

        return $feed;
    }

    /**
     *    获取事件
     *
     *    @author    Garbin
     *    @param     int $limit     条数
     *    @return    array
     */
    function get($limit) {}

    /**
     * 判断feed是否启用
     *
     * @author Garbin
     * @return bool
     **/
    function feed_enabled()
    {
        $feed_enabled = null;
        if ($feed_enabled === null)
        {
            $cache_server =& cache_server();
            $cache_key = 'feed_enabled';
            $feed_enabled = $cache_server->get($cache_key);
            if ($feed_enabled === false)
            {
                $feed_enabled = 0;
                $app_list = outer_call('uc_app_ls');
                if ($app_list)
                {
                    foreach ($app_list as $app)
                    {
                        if ($app['type'] == 'UCHOME')
                        {
                            $feed_enabled = $app;
                        }
                    }
                }
                $cache_server->set($cache_key, $feed_enabled, 86400);
            }
        }

        return $feed_enabled;
    }
}

?>
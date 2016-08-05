<?php

define('CHECK_PM_INTEVAL', 600); // 检查新消息的时间间隔（单位：秒）

/* 短消息 message */
class MessageModel extends BaseModel
{
    var $table  = 'message';
    var $prikey = 'msg_id';
    var $_name  = 'message';

    /* 与其它模型之间的关系 */
    var $_relation = array(
        // 一条收到的短信属于一个用户
        'received_belongs_to_member' => array(
            'model'             => 'member',
            'type'              => BELONGS_TO,
            'foreign_key'       => 'to_id',
            'reverse'           => 'has_received_message',
        ),
        // 一条发出去的短信属于一个用户
        'sent_belongs_to_member' => array(
            'model'             => 'member',
            'type'              => BELONGS_TO,
            'foreign_key'       => 'from_id',
            'reverse'           => 'has_sent_message',
        ),
        // 一条短信有多条回复短信
        'has_reply' => array(
            'model'         => 'message',
            'type'          => HAS_MANY,
            'foreign_key'   => 'parent_id',
            'dependent' => true
        ),
    );

     /* 添加编辑时自动验证 */
    var $_autov = array(
    /*    'from_id' => array(
            'required'  => true,
            'type'      => 'int',
            'filter'    => 'trim',
        ),*/
        'to_id' => array(
            'required'  => true,
            'type'      => 'int',
            'filter'    => 'trim',
        ),
        'content' => array(
            'required'  => true,
            'filter'    => 'trim',
        ),
    );

    /**
     * 发送短消息
     *
     * @author Hyber
     * @param int $from_id
     * @param mixed $to_id  发送给哪些user_id  可以是逗号分割 可以是数组
     * @param string $title 短信标题
     * @param string $content 短信内容
     * @param int $parent_id 如果是回复则需要主题msg_id
     * @return mixed
     */
    function send($from_id, $to_id, $title='', $content, $parent_id=0)
    {
        $to_ids = is_array($to_id) ? $to_id : explode(',', $to_id);
        foreach ($to_ids as $k => $to_id)
        {
            if ($from_id == $to_id)
            {
                $this->_error('cannot_sent_to_myself');
                return false; //不能发给自己
            }
            $data[$k] = array(
                'from_id'   => $from_id,
                'to_id'     => $to_id,
                'title'     => $title,
                'content'   => $content,
                'parent_id' => $parent_id,
                'add_time'  => gmtime(),
            );
            if ($parent_id>0) //回复
            {
                if ($k==0)//只执行一次
                {
                    $message = $this->get_info($parent_id);
                    $edit_data =array(
                        'last_update'   => gmtime(), //修改主题更新时间
                        'status'        => 3, //主题双方未删除
                    );
                    $edit_data['new'] = $from_id == $message['from_id'] ? 1 : 2; //如果回复自己发送的主题时
                    //unset($this->_autov['title']['required']); //允许标题为空
                }
            }else //新主题
            {
                $data[$k]['new']         = 1; //收件方新消息
                $data[$k]['status']      = 3; //双方未删除
                $data[$k]['last_update'] = gmtime(); //更新时间
            }
        }//dump($data);
        $msg_ids = $this->add($data);
        $edit_data && $msg_ids && $this->edit($parent_id, $edit_data);
        return $msg_ids;
    }

    /**
     * 删除短消息
     *
     * @author Hyber
     * @param mix $msg_id 可以是逗号分割 可以是数组
     * @param integer $user_id 当前用户
     * @return integer
     */
    function msg_drop($msg_id, $user_id)
    {
        $msg_ids = is_array($msg_id) ? $msg_id : explode(',', $msg_id);
        if (!$msg_ids)
        {
            $this->_error('no_such_message');
            return false;
        }
        if (!$user_id)
        {
            $this->_error('no_such_user');
            return false;
        }
        foreach ($msg_ids as $msg_id)
        {
            $message = $this->get_info($msg_id);
            if ($message['from_id'] == MSG_SYSTEM && $message['to_id'] == $user_id)
            {
                $drop_ids[] = $msg_id; // 删除系统发给自己的消息
            }
            elseif ($user_id==$message['to_id']) //收件箱
            {
                if ($message['status']==2 || $message['status']==3)
                {
                    $this->edit($msg_id, array('status' => 2));
                }else
                {
                    $drop_ids[] = $msg_id; //记录需要删除记录的msg_id
                }
            }
            elseif ($user_id==$message['from_id']) //发件箱
            {
                if ($message['status']==1 || $message['status']==3)
                {
                    $this->edit($msg_id, array('status' => 1));
                }else
                {
                    $drop_ids[] = $msg_id; //记录需要删除记录的msg_id
                }
            }
            else
            {
                $this->_error('no_drop_permission');
                return false;//没有删除权限
            }
        }
        if ($drop_ids)
        {
            return $this->drop($drop_ids);
        }
        else
        {
            return count($msg_ids);
        }
    }

    function check_new($user_id)
    {
        if (!$user_id)
        {
            $this->_error('no_such_user');
            return false;
        }

        $cache_server =& cache_server();
        $key = 'new_pm_of_user_' . $user_id;
        $new = $cache_server->get($key);
        if ($new === false)
        {
            $new = array();

            /* 统计收件箱新消息 */
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE to_id = '$user_id' AND parent_id = 0 AND status IN(1,3) AND new = 1";
            $new['inbox'] = $this->getOne($sql);
    
            /* 统计发件箱新消息 */
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE from_id = '$user_id' AND parent_id = 0 AND status IN(2,3) AND new = 2";
            $new['outbox'] = $this->getOne($sql);
    
            /* 统计全部新消息 */
            $new['total'] = $new['inbox'] + $new['outbox'];

            $cache_server->set($key, $new, CHECK_PM_INTEVAL);
        }
        
        return $new;
    }
}

?>
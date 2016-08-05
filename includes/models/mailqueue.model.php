<?php

/**
 *    邮件队列模型
 *
 *    @author    Garbin
 *    @usage    none
 */
class MailqueueModel extends BaseModel
{
    var $table  = 'mail_queue';
    var $prikey = 'queue_id';
    var $_name  = 'mailqueue';

    /**
     *    清除发送N次错误和过期的邮件
     *
     *    @author    Garbin
     *    @return    void
     */
    function clear()
    {
        return $this->drop("err_num > 3 OR add_time < " . (gmtime() - 259200));
    }

    /**
     *    发送邮件
     *
     *    @author    Garbin
     *    @param     int $limit
     *    @return    void
     */
    function send($limit = 5)
    {
        /* 清除不能发送的邮件 */
        $this->clear();

        /* 获取待发送的邮件，按发送时间，优先及除序，错误次数升序 */
        $gmtime = gmtime();

        /* 取出所有未锁定的 */
        $mails  = $this->find(array(
            'conditions'    =>  "lock_expiry < {$gmtime}",
            'order'         =>  'add_time DESC, priority DESC, err_num ASC',
            'limit'         =>  "0, {$limit}",
        ));
        if (!$mails)
        {
            /* 没有邮件，不需要发送 */
            return 0;
        }

        /* 锁定待发送邮件 */
        $queue_ids = array_keys($mails);
        $lock_expiry = $gmtime + 30;    //锁定30秒
        $this->edit($queue_ids, "err_num = err_num + 1, lock_expiry = {$lock_expiry}");

        /* 获取邮件发送接口 */
        $mailer =& get_mailer();
        $mail_count = count($queue_ids);
        $error_count= 0;
        $error      = '';

        /* 逐条发送 */
        for ($i = 0; $i < $mail_count; $i++)
        {
            $mail = $mails[$queue_ids[$i]];
            $result = $mailer->send($mail['mail_to'], $mail['mail_subject'], $mail['mail_body'], $mail['mail_encoding'], 1);
            if ($result)
            {
                /* 发送成功，从队列中删除 */
                $this->drop($queue_ids[$i]);
            }
            else
            {
                $error_count++;
            }
        }

        return array('mail_count' => $mail_count, 'error_count' => $error_count, 'error' => $mailer->errors);

    }
}

?>
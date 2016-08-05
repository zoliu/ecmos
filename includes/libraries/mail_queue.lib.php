<?php
/**
 * ECMALL: 邮件队列类
 * ============================================================================
 * 版权所有 (C) 2005-2008 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.shopex.cn
 * -------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Id: mail_queue.lib.php 7634 2009-04-30 03:25:46Z yelin $
 */

if (!defined('IN_ECM'))
{
    trigger_error('Hacking attempt', E_USER_ERROR);
}

import('mailer.lib');

class MailQueue
{
    var $db, $mailer;
    var $_expire    = 259200;    // 默认过期的时间;
    var $_errnum    = 3;    // 默认出错次数
    var $_lock_expire = 30; //锁定时间

    function __construct($sender, $from, $protocol, $host, $port, $username, $password)
    {
        $this->MailQueue($sender, $from, $protocol, $host, $port, $username, $password);
    }

    function MailQueue($sender, $from, $protocol, $host, $port, $username, $password)
    {
        $this->db       =& db();
        $this->mailer   = new Mailer($sender, $from, $protocol, $host, $port, $username, $password);
    }

    /**
     * 将邮件加入队列
     *
     * @author   wj
     * @param  string   $mail_to
     * @param  string   $mail_encoding
     * @param  string   $mail_subject
     * @param  string   $mail_body
     * @param  int      $mail_type  0: text; 1: html
     * @param  int      $priority
     *
     * @return  boolean
     */
    function add($mail_to, $mail_encoding, $mail_subject, $mail_body, $priority)
    {
        $mail_subject = addslashes($mail_subject);
        $mail_body    = addslashes($mail_body);
        $sql = "INSERT INTO `ecm_mail_queue` (mail_to, mail_encoding, mail_subject, mail_body, priority, add_time) ".
                "VALUES('$mail_to', '$mail_encoding', '$mail_subject', '$mail_body', '$priority', " .gmtime(). ")";

        return $this->db->query($sql);
    }

    /**
     * 测试邮件服务器配置
     *
     * @author  wj
     * @param string $mail_to
     * @param string $mail_subject
     * @param string $mail_body
     * @return boolen
     */
    function test_send($mail_to,  $mail_subject, $mail_body)
    {
        return $this->mailer->send($mail_to, $mail_subject, $mail_body, CHARSET, true);
    }
    /**
     * 清除队列中错误超过N次的记录以及过期的记录
     *
     * @return  void
     */
    function clear()
    {
        $sql = "DELETE FROM `ecm_mail_queue` WHERE err_num > $this->_errnum OR add_time < " . (gmtime()-$this->_expire);

        $this->db->query($sql);
    }


    /**
     * 发送邮件
     *
     * @author  wj
     * @param  int      $num
     *
     * @return  void
     */
    function send($limit = 5)
    {
        $this->clear();

        $gmtime = gmtime();
        $lock_expiry = $gmtime + $this->_lock_expire;
        //获取符合条件mail
        $sql = "SELECT queue_id, mail_to, mail_encoding, mail_subject, mail_body".
                   " FROM `ecm_mail_queue`".
                   " WHERE  lock_expiry < $gmtime ".
                   " ORDER BY add_time DESC, priority DESC,  err_num ASC ".
                   " LIMIT $limit ";
        $mail_count = 0;
        $error_count = 0;

        if ($mail_list = ($this->db->getAll($sql)))
        {
            $mail_count = count($mail_list);
            $mail_ids = array();
            for ($i=0; $i < $mail_count; $i++)
            {
                $mail_ids[] = $mail_list[$i]['queue_id'];
            }
             //锁定
            $sql = "UPDATE `ecm_mail_queue` SET err_num = err_num + 1, lock_expiry = $lock_expiry WHERE queue_id " . db_create_in($mail_ids);
            $this->db->query($sql);

            for ($i=0; $i < $mail_count; $i ++)
            {
                $res = $this->mailer->send($mail_list[$i]['mail_to'], $mail_list[$i]['mail_subject'], $mail_list[$i]['mail_body'], $mail_list[$i]['mail_encoding'], 1);
                if ($res)
                {
                    $this->db->query("DELETE FROM `ecm_mail_queue` WHERE queue_id='" . $mail_list[$i]['queue_id'] . '\'');
                }
                else
                {
                    $error_count ++;
                }
            }
        }

        return array('mail_count'=>$mail_count, 'error_count'=>$error_count, 'error'=>$this->mailer->errors);
    }
};

?>
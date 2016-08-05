<?php
/* 网站通知 */
// 发送通知的会员类型
define('SPEC', 1);
define('ALL', 2);
define('SGRADE', 3);
define('STORE', 4);
// 发送方式 
define('MESSAGE', 1);
define('EMAIL', 2);
class NoticeApp extends BackendApp 
{
      var $_user_mod ;
      var $_sgrade_mod;
      function __construct()
      {
            $this->NoticeApp();
      }

      function NoticeApp()
      {
            parent::__construct();
            $this->_user_mod =& m('member');
            $this->_sgrade_mod =& m('sgrade');
      }
      function index()
      {   
            if (!IS_POST)
            {
                  $sgrade_mod =& m('sgrade');
                  $sgrades_tmp = $sgrade_mod->find("1=1");
                  $sgrades = array();
                  foreach ($sgrades_tmp as $val)
                  {
                      $sgrades[$val['grade_id']] = $val['grade_name'];
                  }
                  $this->import_resource(array(
                         'script' => 'jquery.plugins/jquery.validate.js'
                  ));
                  $ms = &ms();
                  //$ms->pm();
                  $this->assign('sgrades', $sgrades);
                  $this->assign('send_type', Lang::get('send_type'));
                  $this->assign('send_mode', Lang::get('send_mode'));
                  $this->assign('build_editor', $this->_build_editor(array('name' => 'content')));
                  $this->display('notice.form.html');
            }
            else 
            {
                if (empty($_POST['send_type']) && empty($_POST['send_mode']))
                {
                    $this->show_warning('type_mode_required');
                    exit;
                }
                if ((empty($_POST['content']) && trim($_POST['send_mode']) == 2) || (empty($_POST['content1']) && trim($_POST['send_mode'] == 1)))
                {
                    $this->show_warning('no_content');
                    exit;
                }
                if (trim($_POST['send_mode'] == 2) && empty($_POST['title']))
                {
                    $this->show_warning('title_empty');
                    exit;
                }
                $title = trim($_POST['send_mode']) == 2 ? trim($_POST['title']) : '';
                $content = trim($_POST['send_mode']) == 1 ? trim($_POST['content1']) : trim($_POST['content']);
                $result = array();
                $count = 0;
                switch (trim($_POST['send_type']))
                {
                    case SPEC : 
                        if (!isset($_POST['user_name']) || empty($_POST['user_name']))
                        {
                            $this->show_warning("no_user");
                            exit;
                        }
                        $user_name = trim($_POST['user_name']);
                        $user_name = str_replace(array("\r","\r\n"), "\n", $user_name);
                        $user_name = explode("\n", $user_name);
                        $result = $this->_user_mod->find(array(
                            'fields' => 'user_id,email',
                            'conditions' => 'user_name ' . db_create_in($user_name),
                            'count' => true,));
                        $count = $this->_user_mod->getCount();
                        break;
                    case ALL :
                        $result = $this->_user_mod->find(array(
                            'fields' => 'user_id,email',
                            'count' => true,    
                        ));
                        $count = $this->_user_mod->getCount();
                        break;
                    case SGRADE : 
                        $sgrade_id = $_POST['sgrade'];
                        $store_mod =& m('store');
                        $result = $store_mod->find(array(
                            'fields' => 'member.user_id,member.email',
                            'join' => 'belongs_to_user',
                            'conditions' => 's.sgrade ' . db_create_in($sgrade_id),
                            'count' => true,
                        ));
                        $count = $store_mod->getCount();
                        break;
                    case STORE :
                        $store_mod =& m('store');
                        $result = $store_mod->find(array(
                            'fields' => 'member.user_id,member.email',
                            'join' => 'belongs_to_user',
                            'count' => true,    
                            ));
                        $count = $store_mod->getCount();
                        break;       
                }
                $users = array();
                foreach ($result as $val)
                {
                    $users[$val['user_id']] = $val['email'];                    
                }
                if (empty($users))
                {
                    $this->show_warning("no_users");
                    exit;
                }
                $admin_id = $this->visitor->get('user_id');
                if (isset($users[$admin_id]))
                {
                    unset($users[$admin_id]);
                    $count = $count - 1;
                }
                $amount = empty($_POST['amount']) ? 20 : intval(trim($_POST['amount']));
                $parts = ceil($count/$amount);
                $this->write_session(array('type' => trim($_POST['send_mode']), 'to_users' => $users, 'count' => $count, 'amount' => $amount, 'parts' => $parts,'title' => $title, 'content' => $content));
                $this->send();
            }
      }
      
      function send()
      {
            if (!isset($_SESSION['notice_param']))
            {
                  $this->show_warning("request_error");
                  exit;
            }
            $current_part = isset($_GET['current_part']) ? intval($_GET['current_part']) : 1;
            if ($current_part > $_SESSION['notice_param']['parts'])
            {
                  $this->show_warning("request_error");
                  exit;
            }
            $offset = ($current_part - 1) * $_SESSION['notice_param']['amount'];
            $to_users = array_slice($_SESSION['notice_param']['to_users'], $offset, $_SESSION['notice_param']['amount'], true);
            
            switch ($_SESSION['notice_param']['type'])
            {
                  case MESSAGE:
                         $ms =& ms();
                         $msg_id = $ms->pm->send(MSG_SYSTEM, array_keys($to_users), '', $_SESSION['notice_param']['content']);
                         if (!$msg_id)
                         {
                               $rs = $ms->pm->get_error();
                               $msg = current($rs);
                               $this->show_warning($msg['msg']);
                               return;
                         }
                         break;
                  case EMAIL:
                         $content = $_SESSION['notice_param']['content'];
                         $title = $_SESSION['notice_param']['title'];
                         $qid = array();
                         $qid = $this->_mailto($to_users, $title, $content);
                         $this->_sendmail($qid); 
                         unset($qid);
                         break;   
            }
            if ($current_part < $_SESSION['notice_param']['parts'])
            {
                  $auto_link = "index.php?app=notice&act=send&current_part=".($current_part+1);
                  $this->assign('auto_redirect', 1);
                  $this->assign('auto_msg', sprintf(Lang::get('special_msg')));
                  $this->assign('auto_link', $auto_link);
            }
            else 
            {
                  unset($_SESSION['notice_param']);
                  $this->assign('auto_msg', sprintf(Lang::get('common_msg')));
                  $this->assign('auto_redirect', 2);
                  $this->assign('auto_link', "index.php?app=notice");      
            }
            $this->display("notice.message.html");
      }
      
      function write_session($data)
      {
            $_SESSION['notice_param'] = $data;
      }
      
      function _mailto($to, $subject, $message, $priority = MAIL_PRIORITY_MID)
      {
            $model_mailqueue =& m('mailqueue');
            $mails = array();
            $to_emails = is_array($to) ? $to : array($to);
            foreach ($to_emails as $_to)
            {
                $mails[] = array(
                    'mail_to'       => $_to,
                    'mail_encoding' => CHARSET,
                    'mail_subject'  => $subject,
                    'mail_body'     => $message,
                    'priority'      => $priority,
                    'add_time'      => gmtime(),
                );
            }
        
            return $model_mailqueue->add($mails);
      }
      
        function _sendmail($qid)
        {
            unset($_SESSION['ASYNC_SENDMAIL']);
            $model_mailqueue =& m('mailqueue');
            $gmtime = time();
            $mails =  $model_mailqueue->find(array(
                    'conditions' => db_create_in($qid, 'queue_id'),
                    'count' => true,
                ));
            $count = $model_mailqueue->getCount();
            $rs = $model_mailqueue->send($count);
            /*if ($rs['error_count'] > 0)
            {
                $this->_sendmail($qid);
            }*/
        }      
      
}

?>
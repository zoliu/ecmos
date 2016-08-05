<?php

/**
 *    通知模板管理控制器
 *
 *    @author    Hyber
 *    @usage    none
 */
class MailtemplateApp extends BackendApp
{
    var $_type;
    var $_m;

    function __construct()
    {
        $this->MailtemplateApp();
    }

    function MailtemplateApp()
    {
        parent::__construct();
        $this->_m = $this->_af();
        $this->assign('notice_mail', NOTICE_MAIL);
        $this->assign('notice_msg', NOTICE_MSG);
        $this->assign('type', $this->_type);
    }

    /**
     *    邮件模板索引
     *
     *    @author    Hyber
     *    @return    void
     */
    function index()
    {
        $noticetemplates = $this->_m->getAll(); //获取所有通知模板<br>
        $this->assign('noticetemplates', $noticetemplates);
        $this->display('noticetemplate.index.html');
    }
    /**
     *    编辑邮件模板
     *
     *    @author    Hyber
     *    @return    void
     */
    function mail()
    {
        $code = isset($_GET['code']) ? trim($_GET['code']) : '';
        if (!$code)
        {
            $this->show_warning('no_such_noticetemplate');
        }
        if (!IS_POST)
        {
            $mailtemplate = $this->_m->getOne($code); //获取所有邮件模板
            if (!$mailtemplate)
            {
                $this->show_warning('no_such_noticetemplate');
                return;
            }
            $this->assign('mailtemplate', $mailtemplate);
            $this->assign('build_editor', $this->_build_editor(array('name' => 'content')));
            $this->display('noticetemplate.mail.html');
        }
        else
        {
            /* 由于var_export会自动对保存的字符串进行转义，因此为了避免多次转义引起的问题，这里要先去除GPC的转义 */
            $data = stripslashes_deep(array(
                'version'   => $_POST['version'],
                'subject'   => $_POST['subject'],
                'content'   => $_POST['content'],
            ));
            $this->_m->_filename = $this->_m->_mail_user_dir . $code . '.php';
            $this->_m->setAll($data);
            $this->show_message('update_noticetemplate_successed',
                'back_list',        'index.php?app=mailtemplate' . '&type=' . $this->_type,
                'edit_again',    'index.php?app=mailtemplate&amp;act=mail&amp;code=' . $code . '&type=' . $this->_type);
        }
    }

    /**
     *    编辑消息模板
     *
     *    @author    Hyber
     *    @return    void
     */
    function msg()
    {
        $code = isset($_GET['code']) ? trim($_GET['code']) : '';
        if (!$code)
        {
            $this->show_warning('no_such_noticetemplate');
        }
        if (!IS_POST)
        {
            $ms = &ms();
            $msgtemplate = $this->_m->getOne($code);
            $this->assign('msgtemplate', $msgtemplate);
            $this->display('noticetemplate.msg.html');
        }
        else
        {
            $this->_m->_filename = $this->_m->_msg_user_file;
            $this->_m->setAll(stripslashes_deep(array($code => $_POST['msgtemplate'])));
            $this->show_message('update_noticetemplate_successed',
                'back_list',        'index.php?app=mailtemplate' . '&type=' . $this->_type,
                'edit_again',    'index.php?app=mailtemplate&amp;act=msg&amp;code=' . $code . '&type=' . $this->_type);
        }
    }

    function _af()
    {
        $m = null;
        empty($_GET['type']) && $_GET['type'] = NOTICE_MAIL;
        $this->_type = $_GET['type'] == NOTICE_MAIL ? NOTICE_MAIL : NOTICE_MSG;
        switch ($this->_type)
        {
            case NOTICE_MAIL: $m = &af('mailtemplate');
            break;
            case NOTICE_MSG:  $m = &af('msgtemplate');
            break;
            default: $m = &af('mailtemplate');
        }
        return $m;
    }
}

?>
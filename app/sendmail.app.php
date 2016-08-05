<?php

/**
 *    Sendmail发送邮件
 *
 *    @author    Garbin
 *    @usage    none
 */
class SendmailApp extends FrontendApp
{
    function index()
    {
        $send_result     = $this->_sendmail(true);

        echo 'var send_result=' . ecm_json_encode($send_result) . ';';
    }
}

?>
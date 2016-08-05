<?php

/**
 * 开店成功后给店主发邮件通知
 *
 * @return  array
 */
class Open_emailPlugin extends BasePlugin
{
    var $_store_id = null;
    var $_config = array();
    
    function __construct($data, $plugin_info)
    {
        $this->Open_emailPlugin($data, $plugin_info);
    }
    function Open_emailPlugin($data, $plugin_info)
    {
        $this->_store_id = $data['user_id'];
        $this->_config = $plugin_info;
        parent::__construct($data, $plugin_info);
    }
    function execute()
    {
        $model_member =& m('member');
        $seller_info   = $model_member->get($this->_store_id);
        $model_mailqueue =& m('mailqueue');
        $time = gmtime();
        $site_name = Conf::get('site_name');
        $mail = array(
            'mail_to'       => $seller_info['email'],
            'mail_encoding' => CHARSET,
            'mail_subject'  => $this->_config['subject'],
            'mail_body'     => $this->_config['content'],
            'add_time'      => $time,
        );
        $model_mailqueue->add($mail);
        $model_mailqueue->send(5);
    }
}

?>
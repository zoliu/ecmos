<?php

return array(
    'id' => 'open_email',
    'hook' => 'after_opening',
    'name' => '开店邮件通知',
    'desc' => '开店成功后给店主发邮件通知',
    'author' => 'ECMall Team',
    'version' => '1.0',
    'config' => array(
        'subject' => array(
            'type' => 'text',
            'text' => '邮件标题'
        ),
        'content' => array(
            'type' => 'textarea',
            'text' => '邮件内容'
        )
    )
);

?>
<?php

return array(
    'id' => 'unable_keyword',
    'hook' => 'on_run_action',
    'name' => '禁用词过滤',
    'desc' => '禁用词过滤',
    'author' => 'ECMall Team',
    'version' => '1.0',
    'config' => array(
        'content' => array(
            'type' => 'textarea',
            'text' => '关键词列表,以","号分开'
        )
    )
);

?>
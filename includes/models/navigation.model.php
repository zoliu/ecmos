<?php

/* 导航 navigation */
class NavigationModel extends BaseModel
{
    var $table  = 'navigation';
    var $prikey = 'nav_id';
    var $_name  = 'navigation';

     /* 添加编辑时自动验证 */
    var $_autov = array(
        'title' => array(
            'required'  => true,    //必填
            'min'       => 1,       //最短1个字符
            'max'       => 100,     //最长100个字符
            'filter'    => 'trim',
        ),
        'link'  => array(
            'required'  => true,    //必填
            'min'       => 1,       //最短1个字符
            'max'       => 255,     //最长255个字符
            'filter'    => 'trim',
        ),
        'sort_order'    => array(
            'filter'    => 'trim,intval',//过滤
            'max'       => 3,     //最长3个字符
        ),
        'open_new'      => array(
            'filter'    => 'intval',
        ),
        'type'      => array(
            'required'    => true,
        )
    );

    var $_relation  = array(
    );
}

?>
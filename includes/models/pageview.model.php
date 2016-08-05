<?php

/* 页面访问 pageview */
class PageviewModel extends BaseModel
{
    var $table  = 'pageview';
    var $prikey = 'rec_id';//
    var $_name  = 'pageview';

    var $_relation  =   array(
        // 一条页面访问记录只能属于一个店铺
        'belongs_to_store' => array(
            'model'         => 'store',
            'type'          => BELONGS_TO,
            'foreign_key'   => 'store_id',
            'reverse'       => 'has_pageview',
        ),
    );
}

?>
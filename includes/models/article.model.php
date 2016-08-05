<?php

/* 文章 article */
class ArticleModel extends BaseModel
{
    var $table  = 'article';
    var $prikey = 'article_id';
    var $_name  = 'article';

    /* 添加编辑时自动验证 */
    var $_autov = array(
        'title' => array(
            'required'  => true,    //必填
            'min'       => 1,       //最短1个字符
            'max'       => 100,     //最长100个字符
            'filter'    => 'trim',
        ),
        'sort_order'  => array(
            'filter'    => 'intval',
        ),
        'cate_id'  => array(
            'min'       => -1,
            'required'  => true,    //必填
        ),
        'link'  => array(
            'filter'    => 'trim',
            'max'       => 255,     //最长100个字符
        ),
    );

    var $_relation = array(
        // 一篇文章只能属于一个店铺
        'belongs_to_store' => array(
            'model'             => 'store',
            'type'              => BELONGS_TO,
            'foreign_key'       => 'store_id',
            'reverse'           => 'has_article',
        ),
        // 一篇文章只能属于一个文章分类
        'belongs_to_acategory' => array(
            'model'             => 'acategory',
            'type'              => BELONGS_TO,
            'foreign_key'       => 'cate_id',
            'reverse'           => 'has_article',
        ),
         //一个文章对应多个上传文件
        'has_uploadedfile' => array(
            'model'             => 'uploadedfile',
            'type'              => HAS_MANY,
            'foreign_key' => 'item_id',
            'ext_limit' => array('belong' => BELONG_ARTICLE),
            'dependent' => true
        ),
    );

}

?>
<?php

/* 上传文件 uploadedfile */
/**
alter table `ecm_uploaded_file` add column `item_id` int(10) unsigned not null default 0 after `file_path`;
alter table `ecm_uploaded_file` add column `belong` tinyint(2) unsigned not null default 0 after `item_id`;
*/
class UploadedfileModel extends BaseModel
{
    var $table  = 'uploaded_file';
    var $prikey = 'file_id';
    var $alias  = 'f';
    var $_name  = 'uploadedfile';
    var $_relation  =   array(
        // 一个文件只能属于一个商品图片
        'belongs_to_goodsimage' => array(
            'model'         => 'goodsimage',
            'type'          => BELONGS_TO,
            'foreign_key'   => 'file_id',
            'reverse'       => 'has_uploadedfile',
            'dependent'     => true
        ),
        // 一个文件只能属于一个商品
        'belong_to_goods'     => array(
            'model'         => 'goods',
            'type'          => BELONGS_TO,
            'foreign_key'   => 'goods_id',
            'refer_key'     => 'item_id',
            'reverse'       => 'has_uploadedfile',
        ),
        // 一个上传文件只能属于一个文章
        'belongs_to_article' => array(
            'model'         => 'article',
            'type'          => BELONGS_TO,
            'foreign_key'   => 'article_id',
            'refer_key'     => 'item_id',
            'reverse'       => 'has_uploadedfile',
        ),
        // 一个文件只能属于一个店铺
        'belongs_to_store' => array(
            'model'         => 'store',
            'type'          => BELONGS_TO,
            'foreign_key'   => 'store_id',
            'refer_key'     => 'item_id',
            'reverse'       => 'has_uploadedfile',
        ),
    );

    /* 统计某店铺已使用空间（单位：字节） */
    function get_file_size($store_id)
    {
        $sql = "SELECT SUM(file_size) FROM {$this->table} WHERE store_id = '$store_id'";

        return $this->db->getOne($sql);
    }

    /* 自动删除附件表中的文件 */
    function drop($file_id)
    {
        $files = $this->find($file_id);
        foreach ($files as $file)
        {
            $file_path = ROOT_PATH . '/' . $file['file_path'];
            file_exists($file_path) && unlink($file_path);
        }

        return parent::drop($file_id);
    }
}

?>
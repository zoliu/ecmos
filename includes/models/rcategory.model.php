<?php

/* 商品分类 gcategory */
class RcategoryModel extends BaseModel
{
    var $table  = 'rcategory';
    var $prikey = 'cate_id';
    var $_name  = 'rcategory';

    function get_rcate(){
    	
        $rcate =$this->find('if_show=1');
        foreach ($rcate as $key => $value) {
            $rcate_data[$value['cate_id']]=$value['cate_name'];
        
        }
        return $rcate_data;
    }
}

?>
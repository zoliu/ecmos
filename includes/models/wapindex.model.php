<?php

/* 手机首页 wapindex */
class WapindexModel extends BaseModel
{
    var $table  = 'wap_index';
    var $prikey = 'id';
    var $_name  = 'wap_index';

    function get_cate_name(){
        return array(
            1=>'幻灯片',
            2=>'推荐地址',
            3=>'推荐分类',
            );
    }
    function get_recom_name(){
        $recommend=&m("recommend");
        $recom_data=$recommend->find();
        foreach ($recom_data as $key => $value) {
            $recom_name[$value['recom_id']]=$value['recom_name'];
        }
        return $recom_name;
    }
    function get_gcategory(){
        $gcategory=&m("gcategory");
        $gcate_data=$gcategory->find('store_id=0 and parent_id=0 and if_show=1');
        foreach ($gcate_data as $key => $value) {
            $gcategory_name[$value['cate_id']]=$value['cate_name'];
        }
        return $gcategory_name;

    }
}

?>
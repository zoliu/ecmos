<?php

/* 商品分类 gcategory */
class RecommendationModel extends BaseModel
{
    var $table  = 'recommendation';
    var $prikey = 'id';
    var $_name  = 'recommendation';


    function get_type_image(){
        return $type = array(
            'image_1' => '幻灯片(1600*420)',
            'image_2' => '左边广告(260*430)',
            'image_3' => '下边广告(939*90)',
            'image_5' => '分类广告(194*70)',
            'image_4' => '文字导航',
        );
    }
    function get_type_goods(){
        return $type = array(
            'goods_1' => '限时促销(239*239)',
            'goods_2' => '团购(100*100)',
            'goods_3' => '推荐店铺(225*280)',
            'goods_4' => '文字促销',
        );
    }
    function get_type_brand(){
        return $type = array(
            'brand_1' => '大图(218*112)',
            'brand_2' => '中图(125*112)',
            'brand_3' => '小图(90*45)',
        );
    }
    function get_type_recommend(){
        return $type = array(
            'recommend_1' => '推荐分类1',
        );
    }
    function get_type_name(){
        return $type = array(
            'image_1' => '幻灯片(1600*420)',
            'image_2' => '左边广告(260*430)',
            'image_3' => '下边广告(939*90)',
            'image_4' => '文字导航',
            'image_5' => '分类广告(194*70)',
            'goods_1' => '限时促销(239*239)',
            'goods_2' => '团购(100*100)',
            'goods_3' => '推荐店铺(225*280)',
            'brand_1' => '幻灯片(218*112)',
            'brand_2' => '左边广告(125*112)',
            'brand_3' => '下边广告(90*45)',
            'recommend_1' => '推荐分类1',
        );
    }
    //得到商品一级分类
    function get_gcategory(){
        $gcategory_mob=&m("gcategory");
        $gcategory_data=$gcategory_mob->find('parent_id=0 and store_id=0');

        foreach ($gcategory_data as $key => $value) {
            $gcategory_data[$value['cate_id']]=$value['cate_name'];
        
        }
        return $gcategory_data;
    }
}

?>
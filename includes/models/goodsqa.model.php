<?php

/* 产品咨询 */
class GoodsQaModel extends BaseModel
{
    var $table  = 'goods_qa';
    var $prikey = 'ques_id';
    var $_name  = 'goodsqa';

    /* 与其它模型之间的关系 */
    var $_relation = array(
        // 一条咨询属于一个商品
        'belongs_to_goods' => array(
            'model'       => 'goods',       //模型的名称
            'type'        => BELONGS_TO,       //关系类型
            'foreign_key' => 'goods_id',    //外键名
            'refer_key'     => 'item_id',
            'reverse' => 'be_questioned',
        ),
        //一条咨询属于一个团购
        'belong_to_groupbuy' => array(
            'model' => 'groupbuy',
            'type' => BELONGS_TO,
            'foreign_key' => 'group_id',
            'refer_key' => 'item_id',
            'reverse' => 'has_consulting',
        ),
          //一条咨询属于一个会员
        'belongs_to_user' => array(
            'model' => 'member',
            'type' => BELONGS_TO,
            'foreign_key' => 'user_id',
            'reverse' => 'user_question',
        ),
          //一条咨询属于一个店铺
        'belongs_to_store' => array(
            'model' => 'store',
            'type' =>BELONGS_TO,
            'foreign_key'   => 'store_id',
            'dependent' =>false,
            'reverse' => 'has_question'
        ),
    );
    var $_autov = array(
        'question_content' => array(
            'required'  => true,
            'filter'    => 'trim',
        ),
        'item_id' => array(
            'required' => true,
            'filter'   => 'trim',
            'type'    => 'int',
        ),
           'store_id' => array(
            'required' => true,
            'filter'    => 'trim',
            'type'    => 'int',
        ),
    );
}
?>
<?php

/**
 *    虚拟商品
 */
class VirtualGoods extends BaseGoods
{
    function __construct($param)
    {
        $this->VirtualGoods($param);
    }
    function VirtualGoods($param)
    {
        /* 初始化 */
        $param['_is_material']  = false;
        $param['_name']         = 'virtual';
        $param['_order_type']   = 'normal';

        parent::__construct($param);
    }
}

?>
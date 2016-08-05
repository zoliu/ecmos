<?php

!defined('ROOT_PATH') && exit('Forbidden');

/**
 *    商品类型基类
 *
 *    @author    Garbin
 *    @usage    none
 */
class BaseGoods extends Object
{
    var $_is_material;  // 是否实体商品，支付接口可能需要用到
    var $_name;         // 商品类型的名称
    var $_order_type;   // 对应的订单类型

    function __construct($params)
    {
        $this->BaseGoods($params);
    }
    function BaseGoods($params)
    {
        if (!empty($params))
        {
            foreach ($params as $key => $value)
            {
                $this->$key = $value;
            }
        }
    }

    /**
     *    获取对应订单类型实例
     *
     *    @author    Garbin
     *    @param     array $params
     *    @return    void
     */
    function get_order_type()
    {
        return $this->_order_type;
    }

    /**
     *    获取类型名称
     *
     *    @author    Garbin
     *    @param    none
     *    @return    void
     */
    function get_name()
    {
        return $this->_name;
    }

    /**
     *    是否是实体商品
     *
     *    @author    Garbin
     *    @return    void
     */
    function is_material()
    {
        return $this->_is_material;
    }
}


?>
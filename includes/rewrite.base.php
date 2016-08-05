<?php

/* 通过函数获得规则名称 */
define('REWRITE_RULE_FN', '[FN]');

/**
 *    基础Rewrite引擎，本类是一个抽象类，您需要继承并实现相应属性和方法后才能使用
 *
 *    @author    Garbin
 *    @usage    none
 */
class BaseRewrite extends Object
{
    /* Rewrite规则地图，记录参数对应的rule名称 */
    var $_rewrite_maps  = array();

    /* Rewrite rules，记录各规则信息 */
    var $_rewrite_rules = array();

    /**
     *    获取重写的URL
     *
     *    @author    Garbin
     *    @param     mixed  $query
     *    @return    string
     */
    function get($query, $rewrite_name = null)
    {
        $rewrite  = '';

        if (empty($query))
        {
            return '';
        }

        /* 获取参数列表 */
        $url_params = is_array($query) ? $query : $this->_get_params($query);
        $rewrite_name = empty($rewrite_name) ? $this->_get_rule_by_param($url_params) : $rewrite_name;
        $rewrite_rule = $this->_get_rule($rewrite_name);

        if (!empty($rewrite_rule))
        {
            $pattern = $this->_get_replace_pattern($url_params);
            $rewrite = str_replace($pattern, $url_params, $rewrite_rule['rewrite']);
        }
        else
        {
            return false;
        }

        return $rewrite;
    }

    /**
     *    查询字符串转换成数组
     *
     *    @author    Garbin
     *    @param     string $query_string
     *    @return    array
     */
    function _get_params($query_string)
    {
        $return = array();
        if (!empty($query_string))
        {
            $tmp = explode('&', $query_string);
            foreach ($tmp as $tmp_item)
            {
                $q = explode('=', $tmp_item);
                $return[$q[0]] = $q[1];
            }
        }

        return $return;
    }

    /**
     *    获取规则信息
     *
     *    @author    Garbin
     *    @param     string $rule_name
     *    @return    array
     */
    function _get_rule($rule_name)
    {
        return isset($this->_rewrite_rules[$rule_name]) ? $this->_rewrite_rules[$rule_name] : null;
    }

    /**
     *    通过规则地图获取规则名称
     *
     *    @author    Garbin
     *    @param     array $url_params
     *    @return    string
     */
    function _get_rule_by_param($url_params)
    {
        $key = $this->_get_mapkey($url_params);

        return $this->_get_rule_by_mapkey($key, $url_params);
    }

    function _get_mapkey($url_params)
    {
        $key = '';
        $app = isset($url_params['app']) ? $url_params['app'] : null;
        $query = '';
        unset($url_params['app']);
        $query_keys = array_keys($url_params);
        if (!empty($query_keys))
        {
            sort($query_keys);
            $query = implode('_', $query_keys);
        }
        if ($app)
        {
            $key = $app;
            $key .= ($query) ? '_' . $query : '';
        }
        else
        {
            $key = $query;
        }

        return $key;
    }

    function _get_rule_by_mapkey($key, $url_params = array())
    {
        $rule_name = isset($this->_rewrite_maps[$key]) ? $this->_rewrite_maps[$key] : '';
        if ($rule_name == REWRITE_RULE_FN)
        {
            $method_name = 'rule_' . $key;
            $rule_name = $this->$method_name($url_params);
        }

        return $rule_name;
    }

    /**
     *    获取规则项目
     *
     *    @author    Garbin
     *    @param     array $url_params
     *    @return    array
     */
    function _get_replace_pattern($url_params)
    {
        $return = array();
        if (!empty($url_params))
        {
            foreach ($url_params as $key => $value)
            {
                $return[] = '%' . $key . '%';
            }
        }

        return $return;
    }
}

?>
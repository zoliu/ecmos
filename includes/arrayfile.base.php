<?php

!defined('ROOT_PATH') && exit('Forbidden');

/* 数组文件基类 */
class BaseArrayfile extends Object
{
    var $_filename; // 文件名

    function __construct($params)
    {
        $this->BaseArrayfile($params);
    }

    function BaseArrayfile($params)
    {
        if (!empty($params))
        {
            foreach ($params as $key => $value)
            {
                $this->$key = $value;
            }
        }
    }

    function getAll()
    {
        $default_data = $this->get_default();
        $data = $this->_loadfromfile();
        foreach ($data as $key => $value)
        {
            isset($value) && $default_data[$key] = $value; //如果配置文件有这项的值则用之
        }
        return $default_data;
    }

    function getOne($key)
    {
        $data = $this->getAll();
        return isset($data[$key]) ? $data[$key] : NULL;
    }

    function add($data)
    {
        /*数据校验*/
        $data = $this->_valid($data);
        if (!$data)
        {
            $this->_error('no_valid_data');
            return false;
        }
        /*获取旧数据*/
        $old_data = $this->getAll();
        $old_num = count($old_data);

        /*数据合并*/
        !empty($old_data)? $old_data[] = $data : $old_data[1] = $data; //插入新元素 让数组键名从1开始

        /*返回结果*/
        if ($this->_savetofile($old_data))
        {
            $new_data = $this->_loadfromfile();
            $new_num = count($new_data);
            return $new_num == $old_num+1 ? max(array_keys($new_data)) : false;
        }
        else
        {
            return false;
        }
    }

    function setAll($data)
    {
        if ($data===false)
        {
            return false;
        }
        $old_data = $this->_loadfromfile();
        foreach ($data as $key => $value)
        {
            isset($value) && $old_data[$key] = $value;
        }
        return $this->_savetofile($old_data);
    }

    function setOne($key, $value)
    {
        $value = $this->_valid($value);
        if (!$value)
        {
            $this->_error('no_valid_data');
            return false;
        }
        return $this->setAll(array($key => $value));
    }

    function drop($key)
    {
        $data = $this->getAll();
        unset($data[$key]);
        return $this->_savetofile($data);
    }

    function get_default()
    {
        return array();
    }

    function _loadfromfile()
    {
        return file_exists($this->_filename) ? include($this->_filename) : array();
    }

    function _savetofile($data)
    {
        return file_put_contents($this->_filename, "<?php \nreturn " . var_export($data , true) . ";\n?>");
    }

        /**
     *  验证数据合法性，当只验证vrule中指定的字段，并且只当$data中设置了其值时才验证
     *
     *  @author Garbin
     *  @param  array $data
     *  @return mixed
     */
    function _valid($data)
    {
        if (empty($this->_autov) || empty($data) || !is_array($data))
        {
            return $data;
        }
        $max = $filter = $reg = $default = $valid = '';
        reset($data);
        $is_multi = (key($data) == 0 && is_array($data[0]));
        if (!$is_multi)
        {
            $data = array($data);
        }
        foreach ($this->_autov as $_k => $_v)
        {
            if (is_array($_v))
            {
                $required = (isset($_v['required']) && $_v['required']) ? true : false;
                $type  = isset($this->_autov[$_k]['type']) ? $this->_autov[$_k]['type'] : 'string';
                $min  = isset($this->_autov[$_k]['min']) ? $this->_autov[$_k]['min'] : 0;
                $max  = isset($this->_autov[$_k]['max']) ? $this->_autov[$_k]['max'] : 0;
                $filter = isset($this->_autov[$_k]['filter']) ? $this->_autov[$_k]['filter'] : '';
                $valid= isset($this->_autov[$_k]['valid']) ? $this->_autov[$_k]['valid'] : '';
                $reg  = isset($this->_autov[$_k]['reg']) ? $this->_autov[$_k]['reg'] : '';
                $default = isset($this->_autov[$_k]['default']) ? $this->_autov[$_k]['default'] : '';
            }
            else
            {
                preg_match_all('/([a-z]+)(\((\d+),(\d+)\))?/', $_v, $result);
                $type = $result[1];
                $min  = $result[3];
                $max  = $result[4];
            }
            foreach ($data as $_sk => $_sd)
            {
                $has_set = isset($data[$_sk][$_k]);
                if (!$has_set)
                {
                    continue;
                }

                if ($required && $data[$_sk][$_k] == '')
                {
                    $this->_error("required_field", $_k);

                    return false;
                }

                /* 运行到此，说明该字段不是必填项可以为空 */

                $value = $data[$_sk][$_k];

                /* 默认值 */
                if (!$value && $default)
                {
                    $data[$_sk][$_k] = function_exists($default) ? $default() : $default;
                    continue;
                }

                /* 若还是空值，则没必要往下验证长度，正则，自定义和过滤，因为其已经是一个空值了 */
                if (!$value)
                {
                    continue;
                }

                /* 大小|长度限制 */
                if ($type == 'string')
                {
                    $strlen = strlen($value);
                    if ($min != 0 && $strlen < $min)
                    {
                        $this->_error('autov_length_lt_min', $_k);

                        return false;
                    }
                    if ($max != 0 && $strlen > $max)
                    {
                        $this->_error('autov_length_gt_max', $_k);

                        return false;
                    }
                }
                else
                {
                    if ($min != 0 && $value < $min)
                    {
                        $this->_error('autov_value_lt_min', $_k);

                        return false;
                    }
                    if ($max != 0 && $value > $max)
                    {
                        $this->_error('autov_value_gt_max', $_k);

                        return false;
                    }
                }

                /* 正则 */
                if ($reg)
                {
                    if (!preg_match($reg, $value))
                    {
                        $this->_error('check_match_error', $_k);
                        return false;
                    }
                }

                /* 自定义验证 */
                if ($valid && function_exists($valid))
                {
                    $result = $valid($value);
                    if ($result !== true)
                    {
                        $this->_error($result);

                        return false;
                    }
                }

                /* 过滤 */
                if ($filter)
                {
                    $funs    = explode(',', $filter);
                    foreach ($funs as $fun)
                    {
                        function_exists($fun) && $value = $fun($value);
                    }
                    $data[$_sk][$_k] = $value;
                }
            }
        }
        if (!$is_multi)
        {
            $data = $data[0];
        }

        return $data;
    }
}

?>
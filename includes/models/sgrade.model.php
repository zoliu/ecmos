<?php

/* 店铺等级 sgrade */
class SgradeModel extends BaseModel
{
    var $table  = 'sgrade';
    var $prikey = 'grade_id';
    var $_name  = 'sgrade';
    var $_relation  =   array(
        // 一个店铺等级有多个店铺
        'has_store' => array(
            'model'         => 'store',
            'type'          => HAS_MANY,
            'foreign_key' => 'sgrade',
        ),
    );

    var $_autov = array(
        'grade_name' => array(
            'required'  => true,
            'filter'    => 'trim',
        ),
    );

    /*
     * 判断名称是否唯一
     */
    function unique($grade_name, $grade_id = 0)
    {
        $conditions = "grade_name = '" . $grade_name . "'";
        $grade_id && $conditions .= " AND grade_id <> '" . $grade_id . "'";
        return count($this->find(array('conditions' => $conditions))) == 0;
    }

    function get_options()
    {
        $cache_server =& cache_server();
        $key     = 'sgrade_options';
        $options = $cache_server->get($key);
        if ($options === false)
        {
            $options = array();
            $sgrades = $this->find();
            foreach ($sgrades as $sgrade)
            {
                $options[$sgrade['grade_id']] = $sgrade['grade_name'];
            }
            $cache_server->set($key, $options);
        }

        return $options;
    }

    function add($data, $compatible = false)
    {
        $this->clear_cache();

        return parent::add($data, $compatible);
    }

    function edit($conditions, $edit_data)
    {
        $this->clear_cache();

        return parent::edit($conditions, $edit_data);
    }

    function drop($conditions, $fields = '')
    {
        $this->clear_cache();

        return parent::drop($conditions, $fields);
    }

    /**
     * 清除缓存（更新数据时调用）
     *
     */
    function clear_cache()
    {
        $cache_server =& cache_server();
        $keys = array('sgrade_options');
        foreach ($keys as $key)
        {
            $cache_server->delete($key);
        }
    }
}

?>
<?php

/* 品牌 brand */
class BrandModel extends BaseModel
{
    var $table  = 'brand';
    var $prikey = 'brand_id';
    var $_name  = 'brand';

    /* 添加编辑时自动验证 */
    var $_autov = array(
        'brand_name' => array(
            'required'  => true,    //必填
            'min'       => 1,       //最短1个字符
            'max'       => 100,     //最长100个字符
            'filter'    => 'trim',
        ),
        'sort_order'  => array(
            'filter'    => 'intval',
        )
    );

    /**
     *    删除商品品牌
     *
     *    @author    Hyber
     *    @param     string $conditions
     *    @param     string $fields
     *    @return    void
     */
    function drop($conditions, $fields = 'brand_logo')
    {
        $droped_rows = parent::drop($conditions, $fields);
        if ($droped_rows)
        {
            restore_error_handler();
            $droped_data = $this->getDroppedData();
            foreach ($droped_data as $key => $value)
            {
                if ($value['brand_logo'])
                {
                    @unlink(ROOT_PATH . '/' . $value['brand_logo']);  //删除Logo文件
                }
            }
            reset_error_handler();
        }

        return $droped_rows;
    }

        /*
     * 判断名称是否唯一
     */
    function unique($brand_name, $brand_id = 0)
    {
        $conditions = "brand_name = '" . $brand_name . "' AND brand_id != ".$brand_id."";
        //dump($conditions);
        return count($this->find(array('conditions' => $conditions))) == 0;
    }
    
    /* 按标签分类取得所有的品牌   */
    
    function getAllBrands()
    {
        $sql = "SELECT group_concat(brand_id) as brand_ids,COUNT(*) as count,tag FROM {$this->table} WHERE if_show = 1 GROUP BY tag ORDER BY count DESC";
        return $this->db->getAll($sql);
    }
}

?>
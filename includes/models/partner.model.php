<?php

/* 友情链接 partner */
class PartnerModel extends BaseModel
{
    var $table  = 'partner';
    var $prikey = 'partner_id';
    var $_name  = 'partner';

    /* 添加编辑时自动验证 */
    var $_autov = array(
        'title' => array(
            'required'  => true,    //必填
            'min'       => 1,       //最短1个字符
            'max'       => 100,     //最长100个字符
            'filter'    => 'trim',
        ),
        'link'  => array(
            'required'  => true,    //必填
            'filter'    => 'trim',
        ),
        'sort_order'    => array(
            'filter'    => 'intval',//过滤
        ),
    );
    var $_relation = array(
        // 一个友情链接只能被一个店铺拥有
        'belongs_to_store' => array(
            'model'       => 'store',
            'type'        => BELONGS_TO,
            'foreign_key' => 'store_id',
            'reverse'     => 'has_partner',
        ),
    );

    /**
     *    删除友情链接
     *
     *    @author    Garbin
     *    @param     string $conditions
     *    @param     string $fields
     *    @return    void
     */
    function drop($conditions, $fields = 'logo')
    {
        $droped_rows = parent::drop($conditions, $fields);
        if ($droped_rows)
        {
            restore_error_handler();
            $droped_data = $this->getDroppedData();
            foreach ($droped_data as $key => $value)
            {
                if ($value['logo'])
                {
                    @unlink(ROOT_PATH . '/' . $value['logo']);  //删除Logo文件
                }
            }
            reset_error_handler();
        }

        return $droped_rows;
    }
}

?>
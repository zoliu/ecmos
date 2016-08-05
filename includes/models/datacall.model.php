<?php
class DataCallModel extends BaseModel
{
    var $table  = 'data_call';
    var $prikey = 'call_id';
    var $_name  = 'datacall';

    /* 添加编辑时自动验证 */
    var $_autov = array(
        'description' => array(
            'required'  => true,
            'min'       => 1,
            'max'       => 100,
            'filter'    => 'trim',
        ),
        'cache_time'  => array(
            'filter'    => 'intval',
        ),
        'amount'  => array(
             'filter'    => 'intval',
        ),
        'name_length'  => array(
            'filter'    => 'intval',
        ),
        'template'  => array(
            'filter'    => 'trim',
        ),
        'spe_data'  => array(
            'filter'    => 'trim',
        ),
    );
}
?>
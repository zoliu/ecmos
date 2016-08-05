<?php
class DatacallArrayfile extends BaseArrayfile 
{
/*    var $_autov = array(
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
    );*/
    function __construct()
    {
        $this->DatacallArrayfile();
    }
    
    function DatacallArrayfile()
    {
        $this->_filename = ROOT_PATH . '/data/datacall.inc.php';
    }
    
}
?>
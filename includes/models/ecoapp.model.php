<?php

class ecoappModel extends BaseModel
{
    var $table  = 'app';
    var $prikey = 'id';
    var $_name  = 'app';



    //新功能必备360cd.cn,此功能由卓流应用网提供360 cd.cn
    var $_joinstr;

    function _getConditions($conditions, $if_add_alias = false)
    {
    	$where=parent::_getConditions($conditions,$if_add_alias);
    	if(!empty($this->_joinstr))
    	{
    		$where=$this->_joinstr." ".$where;
    		$this->_joinstr=null;
    	}
    	return $where;
    }

    function _initFindParams($params)
    {
    	$this->_joinstr=is_array($params) && isset($params['joinstr'])?$params['joinstr']:'';
    	return parent::_initFindParams($params);
    }

    function parseJoin($from_k,$to_k,$to_t,$from_t='',$type='left',$to_alias='')
	{
	    $model= &m($to_t);
	    if($type=='left')
	    {
	       $str=" LEFT JOIN [B] [b] on [b].[fk]=[a].[pk] ";
	     }else{
	        $str=" RIGHT JOIN [B] [b] on [b].[fk]=[a].[pk] ";
	     }
	    if(!empty($from_t))
	    {
	        $fmodel=&m($from_t);
	    }else{
	    	$fmodel=$this;
	    }
	    $alias=!empty($to_alias)?$to_alias:$model->alias;
	    $str=str_replace('[B]', $model->table, $str);
	    $str=str_replace('[b]', $alias, $str);
	    $str=str_replace('[fk]', $to_k, $str);
	    $str=str_replace('[a]', $fmodel->alias, $str);
	    $str=str_replace('[pk]', $from_k, $str);
	    return $str;        
	}
    //新功能必备360cd.cn,此功能由卓流应用网提供360 cd.cn

}
?>
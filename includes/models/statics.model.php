<?php

class staticsModel extends BaseModel
{
    var $table  = 'all_statistics';
    var $prikey = 'id';
    var $_name  = 'statics';
 	 			 
 	 	//options选项值
	function get_options_stype()
	{
		  return array(
            '1'=>'按日统计',
            '2'=>'按周统计',
            '3'=>'按月统计',
            '4'=>'按季统计',
            '5'=>'按年统计',
            );
	}

	/*  
	`sales` int(11) DEFAULT NULL,
	`collects` int(11) DEFAULT NULL,
	`carts` int(11) DEFAULT NULL,
	`visits` int(11) DEFAULT NULL,
	`cancels` int(11) DEFAULT NULL,
	`comments` int(11) DEFAULT NULL,
	`goodcomments` int(11) DEFAULT NULL,
	`normalcomments` int(11) DEFAULT NULL,
	`badcomments` int(11) DEFAULT NULL,
	`refunds` int(11) DEFAULT NULL,
	`moneys` decimal(20,0) DEFAULT NULL,
	*/

	function update($store_id,$fname,$fval,$is_add=1)
	{
		import('zllib/extime.lib');
		$extime=new extime();
		$day=$extime->check_time(1);
		$this->_update_statics($day,$store_id,1,$fname,$fval,$is_add);
		$week=$extime->check_time(2);
		$this->_update_statics($week,$store_id,2,$fname,$fval,$is_add);
		$month=$extime->check_time(3);
		$this->_update_statics($month,$store_id,3,$fname,$fval,$is_add);
		$jidu=$extime->check_time(4);
		$this->_update_statics($jidu,$store_id,4,$fname,$fval,$is_add);
		$year=$extime->check_time(5);
		$this->_update_statics($year,$store_id,5,$fname,$fval,$is_add);
	}

	function _update_statics($sumdate,$store_id,$type,$fname,$fval,$is_add=1)
	{
		$store_info=$this->_get_store_name($store_id);
		$where=" sumdate='".$sumdate."' and store_id=".$store_id." and stype=".$type;
		$data=$this->get($where);
		if($data)
		{
			$fval=$is_add?$data[$fname]+$fval:$data[$fname]-$fval;
			$fdata=array(
				'store_name'=>$store_info['store_name'],
				'update_time'=>gmtime(),
			);
			$fdata[$fname]=$fval;
			$this->edit($where,$fdata);
		}else{
			$fdata=array(
				'sumdate'=>$sumdate,
				'store_id'=>$store_id,
				'store_name'=>$store_info['store_name'],
				'stype'=>$type,
				'add_time'=>gmtime(),
				'update_time'=>gmtime(),
			);
			$fval=$is_add?$fval:0;
			$fdata[$fname]=$fval;
			$this->add($fdata);
		}
	}

	function _get_store_name($store_id)
	{
		//360cd.cn
		$store_model=&m('store');
		$where=$store_id;
		$store_data=$store_model->get($where);
		if(!$store_data)
		{
			//此处填写数据不存在内容
			return 0;
		}
		//360cd.cn
		return $store_data;
	}

	function update_order($order_id,$fname,$fval='',$is_add=1)
	{
		//360cd.cn
		$order_model=&m('order');
		$where=$order_id;
		$order_data=$order_model->get($where);
		if(!$order_data)
		{
			//此处填写数据不存在内容
			return 0;
		}
		if($fname=='moneys')
		{
			$fval=$order_data['order_amount'];
		}
		//360cd.cn
		return $this->update($order_data['seller_id'],$fname,$fval,$is_add);
	}

	function export_csv($filename,$data)   
	{   
	    header("Content-type:text/csv");   
	    header("Content-Disposition:attachment;filename=".$filename);   
	    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');   
	    header('Expires:0');   
	    header('Pragma:public');   
	    echo $data;   
	}  
	

}
?>
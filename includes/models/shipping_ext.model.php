<?php

class Shipping_extModel extends BaseModel

{

    var $table  = 'shipping';

    var $prikey = 'shipping_id';

    var $_name  = 'shipping';

	

	function get_shipping_list()

	{

	    $path=ROOT_PATH."/data/shipping.inc.php";

        return file_exists($path)?include($path):null;	

	}

	function get_shipping_config()

	{

	    $path=ROOT_PATH."/data/shipping_config.inc.php";

        return file_exists($path)?include($path):null;	

	}



	function get_shipping_info($order_id,$invoice_no)

	{

	   $extm_model=&m ('orderextm');  

       $extm_info=$extm_model->get($order_id);  

       $shipping_model=&m ('shipping');  

       return $this->get_shipping_detail($extm_info['shipping_name'],$invoice_no);

	}



	function get_shipping_detail($shipping_code,$order_sn)

	{

		$cfg=$this->get_shipping_config();

		if(!$cfg)return 0;

		$url="http://api.ickd.cn/?id={$cfg['api_id']}&secret={$cfg['api_key']}&com={$shipping_code}&nu={$order_sn}&type=json&encode=utf8";

		$data=ecm_fopen($url);

		return $data;



	}



}

?>
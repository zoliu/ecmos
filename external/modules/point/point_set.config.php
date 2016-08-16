<?php
/**
 'name'=>'reg_point', //积分类型代码   必须

 'label'=>'注删赠送积分',//积分类型中文名称  必须

 'type'=>'text',//积分表单类型   必须

 'method'=>'add',//积分操作类型   必须
 
 'enable_config'=>1,//是否可配置   必须
 
 'percent'=>0,//是否百分比计算  必须
 
 'desc'=>'',//描述 可选
 **/

return array(

	 'reg_point'=>array(

		 'name'=>'reg_point',

		 'label'=>'注删赠送积分',

		 'type'=>'text',

		 'method'=>'add',
		 
		 'enable_config'=>1,
		 
		 'percent'=>0,
		 
		 'desc'=>'',

	 ),'login_point'=>array(

		 'name'=>'login_point',

		 'label'=>'登陆赠送积分',

		 'type'=>'text',
		 
		 'enable_config'=>1,

		 'method'=>'add',
		 
		 'percent'=>0,
		 
		 'desc'=>'',

	 ),'system_add_point'=>array(

		 'name'=>'system_add_point',

		 'label'=>'管理员增加积分',

		 'type'=>'text',
		 
		 'enable_config'=>0,

		 'method'=>'add',
		 
		 'percent'=>0,
		 
		 'desc'=>'',

	 ),'system_subtract_point'=>array(

		 'name'=>'system_subtract_point',

		 'label'=>'管理员减少积分',

		 'type'=>'text',
		 
		 'enable_config'=>0,
		 
		 'percent'=>0,

		 'method'=>'subtract',
		 
		 'desc'=>'',

	 ),'buy_get_point'=>array(

		 'name'=>'buy_get_point',

		 'label'=>'购物赠送积分',

		 'type'=>'text',	
		 
		 'enable_config'=>1,		 

		 'method'=>'add',
		 
		 'percent'=>1,
		 
		 'desc'=>'购物赠送积分此处为比例，表示1元订单金额，可兑换的积分数，购物积分=订单总金额*此处设置积分比例',

	 ),'recharge_to_money'=>array(

		 'name'=>'recharge_to_money',

		 'label'=>'积分兑换现金',

		 'type'=>'text',	
		 
		 'enable_config'=>0,		 

		 'method'=>'subtract',
		 
		 'percent'=>1,
		 
		 'desc'=>'积分兑换现金,此处为比例，表示1积分可兑换现金额 ，兑换现金=总积分*此处设置兑换比例',

	 ),'buyer_to_point'=>array(

		 'name'=>'buyer_to_point',

		 'label'=>'积分消费',

		 'type'=>'text',	
		 
		 'enable_config'=>0,		 

		 'method'=>'subtract',
		 
		 'percent'=>1,
		 
		 'desc'=>'积分消费用于如积分商城兑换等',

	 ),



);

?>
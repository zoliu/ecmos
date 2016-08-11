<?php
return array(
	'keyword'    => array(
		'text'  => '搜索热词',
        'desc'  => '显示在首页搜索框下的搜索热词',
		'model'  => 'keywords',
		'config' => array(
			'keywords' => array(
				'text'  => '关键字',
        		'desc'  => '多个关键字用空格隔开',
				'type'  => 'textarea',
			)
		),
	),
	'slides'    => array(
		'text'  => '通栏幻灯片',
        'desc'  => '幻灯片为通栏宽度，建议宽度最小为640px,宽度241px',
		'model'  => 'image',
		'config' => array(
			'ad1_image_url'   => array(  
				'text'  => '第一张幻灯图',  
				'desc'  => '',  
				'type'  => 'file',
			),
			'ad1_link_url' => array(
				'text'  => '图片链接地址',
        		'desc'  => '',
				'type'  => 'text',
			),
			'ad2_image_url'   => array( 
				'text'  => '第二张幻灯图',  
				'desc'  => '',         
				'type'  => 'file',
			),
			'ad2_link_url' => array(
				'text'  => '图片链接地址',
        		'desc'  => '',
				'type'  => 'text',
			),
			'ad3_image_url'   => array( 
				'text'  => '第三张幻灯图',  
				'desc'  => '',         
				'type'  => 'file',
			),
			'ad3_link_url' => array(
				'text'  => '图片链接地址',
        		'desc'  => '',
				'type'  => 'text',
			),
			'ad4_image_url'   => array( 
				'text'  => '第四张幻灯图',  
				'desc'  => '',         
				'type'  => 'file',
			),
			'ad4_link_url' => array(
				'text'  => '图片链接地址',
        		'desc'  => '',
				'type'  => 'text',
			),
			'ad5_image_url'   => array( 
				'text'  => '第五张幻灯图',  
				'desc'  => '',         
				'type'  => 'file',
			),
			'ad5_link_url' => array(
				'text'  => '图片链接地址',
        		'desc'  => '',
				'type'  => 'text',
			),
			'ad6_image_url'   => array( 
				'text'  => '第六张幻灯图',  
				'desc'  => '',         
				'type'  => 'file',
			),
			'ad6_link_url' => array(
				'text'  => '图片链接地址',
        		'desc'  => '',
				'type'  => 'text',
			),
			'ad7_image_url'   => array( 
				'text'  => '第七张幻灯图',  
				'desc'  => '',         
				'type'  => 'file',
			),
			'ad7_link_url' => array(
				'text'  => '图片链接地址',
        		'desc'  => '',
				'type'  => 'text',
			),
			'ad8_image_url'   => array( 
				'text'  => '第八张幻灯图',  
				'desc'  => '',         
				'type'  => 'file',
			),
			'ad8_link_url' => array(
				'text'  => '图片链接地址',
        		'desc'  => '',
				'type'  => 'text',
			),
		)
    ),
	'floor_1'    => array(
		'text'  => '楼层（一）',
        'desc'  => '三张广告图，关键字',
		'model'  => 'floor',
		'config' => array(
			'ad1_image_url'   => array(  
				'text'  => '第一张幻灯图',  
				'desc'  => '规格为319*346',  
				'type'  => 'file',
			),
			'ad1_link_url' => array(
				'text'  => '图片链接地址',
        		'desc'  => '',
				'type'  => 'text',
			),
			'ad2_image_url'   => array( 
				'text'  => '第二张幻灯图',  
				'desc'  => '规格为319*172',         
				'type'  => 'file',
			),
			'ad2_link_url' => array(
				'text'  => '图片链接地址',
        		'desc'  => '',
				'type'  => 'text',
			),
			'ad3_image_url'   => array( 
				'text'  => '第三张幻灯图',  
				'desc'  => '规格为319*173',         
				'type'  => 'file',
			),
			'ad3_link_url' => array(
				'text'  => '图片链接地址',
        		'desc'  => '',
				'type'  => 'text',
			),
			'keywords' => array(
				'text'  => '关键字',
        		'desc'  => '多个关键字用空格隔开',
				'type'  => 'textarea',
			),
			'link' => array(
				'text'  => '关键字连接',
        		'desc'  => '多个关键字用空格隔开，个数与关键字相同',
				'type'  => 'textarea',
			),
			/*
			'time' => array(
				'text' => '倒计时',
				'desc' => '促销到期时间设置<b class="gray" style="font-weight:normal;font-size:12px">(格式: 2015-9-16 17:16:40)</b>',
				'type' => 'text',
			),*/
		),
	),
	'floor_2'    => array(
		'text'  => '楼层（二）',
        'desc'  => '楼层名称，三张广告图，关键字',
		'model'  => 'floor',
		'config' => array(
			'model_name'   => array(  
				'text'  => '楼层名称',  
				'desc'  => '',  
				'type'  => 'text',
			),
			'ad1_image_url'   => array(  
				'text'  => '第一张幻灯图',  
				'desc'  => '规格为319*346',  
				'type'  => 'file',
			),
			'ad1_link_url' => array(
				'text'  => '图片链接地址',
        		'desc'  => '',
				'type'  => 'text',
			),
			'ad2_image_url'   => array( 
				'text'  => '第二张幻灯图',  
				'desc'  => '规格为319*172',         
				'type'  => 'file',
			),
			'ad2_link_url' => array(
				'text'  => '图片链接地址',
        		'desc'  => '',
				'type'  => 'text',
			),
			'ad3_image_url'   => array( 
				'text'  => '第三张幻灯图',  
				'desc'  => '规格为319*173',         
				'type'  => 'file',
			),
			'ad3_link_url' => array(
				'text'  => '图片链接地址',
        		'desc'  => '',
				'type'  => 'text',
			),
			'keywords' => array(
				'text'  => '关键字',
        		'desc'  => '多个关键字用空格隔开',
				'type'  => 'textarea',
			),
			'link' => array(
				'text'  => '关键字连接',
        		'desc'  => '多个关键字用空格隔开，个数与关键字相同',
				'type'  => 'textarea',
			),
		),
	),
	'floor_3'    => array(
		'text'  => '楼层（三）',
        'desc'  => '楼层名称，三张广告图，关键字',
		'model'  => 'floor',
		'config' => array(
			'model_name'   => array(  
				'text'  => '楼层名称',  
				'desc'  => '',  
				'type'  => 'text',
			),
			'ad1_image_url'   => array(  
				'text'  => '第一张幻灯图',  
				'desc'  => '规格为319*172',  
				'type'  => 'file',
			),
			'ad1_link_url' => array(
				'text'  => '图片链接地址',
        		'desc'  => '',
				'type'  => 'text',
			),
			'ad2_image_url'   => array( 
				'text'  => '第二张幻灯图',  
				'desc'  => '规格为319*173',         
				'type'  => 'file',
			),
			'ad2_link_url' => array(
				'text'  => '图片链接地址',
        		'desc'  => '',
				'type'  => 'text',
			),
			'ad3_image_url'   => array( 
				'text'  => '第三张幻灯图',  
				'desc'  => '规格为319*346',         
				'type'  => 'file',
			),
			'ad3_link_url' => array(
				'text'  => '图片链接地址',
        		'desc'  => '',
				'type'  => 'text',
			),
			'keywords' => array(
				'text'  => '关键字',
        		'desc'  => '多个关键字用空格隔开',
				'type'  => 'textarea',
			),
			'link' => array(
				'text'  => '关键字连接',
        		'desc'  => '多个关键字用空格隔开，个数与关键字相同',
				'type'  => 'textarea',
			),
		),
	),
	'floor_4'    => array(
		'text'  => '楼层（四）',
        'desc'  => '楼层名称，三张广告图，关键字',
		'model'  => 'floor',
		'config' => array(
			'model_name'   => array(  
				'text'  => '楼层名称',  
				'desc'  => '',  
				'type'  => 'text',
			),
			'ad1_image_url'   => array(  
				'text'  => '第一张幻灯图',  
				'desc'  => '规格为319*346',  
				'type'  => 'file',
			),
			'ad1_link_url' => array(
				'text'  => '图片链接地址',
        		'desc'  => '',
				'type'  => 'text',
			),
			'ad2_image_url'   => array( 
				'text'  => '第二张幻灯图',  
				'desc'  => '规格为319*172',         
				'type'  => 'file',
			),
			'ad2_link_url' => array(
				'text'  => '图片链接地址',
        		'desc'  => '',
				'type'  => 'text',
			),
			'ad3_image_url'   => array( 
				'text'  => '第三张幻灯图',  
				'desc'  => '规格为319*172',         
				'type'  => 'file',
			),
			'ad3_link_url' => array(
				'text'  => '图片链接地址',
        		'desc'  => '',
				'type'  => 'text',
			),
			'keywords' => array(
				'text'  => '关键字',
        		'desc'  => '多个关键字用空格隔开',
				'type'  => 'textarea',
			),
			'link' => array(
				'text'  => '关键字连接',
        		'desc'  => '多个关键字用空格隔开，个数与关键字相同',
				'type'  => 'textarea',
			),
		),
	),
	'floor_5'    => array(
		'text'  => '楼层（五）',
        'desc'  => '楼层名称，三张广告图，关键字',
		'model'  => 'floor',
		'config' => array(
			'model_name'   => array(  
				'text'  => '楼层名称',  
				'desc'  => '',  
				'type'  => 'text',
			),
			'ad1_image_url'   => array(  
				'text'  => '第一张幻灯图',  
				'desc'  => '规格为319*346',  
				'type'  => 'file',
			),
			'ad1_link_url' => array(
				'text'  => '图片链接地址',
        		'desc'  => '',
				'type'  => 'text',
			),
			'ad2_image_url'   => array( 
				'text'  => '第二张幻灯图',  
				'desc'  => '规格为319*172',         
				'type'  => 'file',
			),
			'ad2_link_url' => array(
				'text'  => '图片链接地址',
        		'desc'  => '',
				'type'  => 'text',
			),
			'ad3_image_url'   => array( 
				'text'  => '第三张幻灯图',  
				'desc'  => '规格为319*173',         
				'type'  => 'file',
			),
			'ad3_link_url' => array(
				'text'  => '图片链接地址',
        		'desc'  => '',
				'type'  => 'text',
			),
			'keywords' => array(
				'text'  => '关键字',
        		'desc'  => '多个关键字用空格隔开',
				'type'  => 'textarea',
			),
			'link' => array(
				'text'  => '关键字连接',
        		'desc'  => '多个关键字用空格隔开，个数与关键字相同',
				'type'  => 'textarea',
			),
		),
	),
	'six_images'    => array(
		'text'  => '6张广告图片',
        'desc'  => '图片规格306px*134px',
		'model'  => 'image',
		'config' => array(
			'ad1_image_url'   => array(  
				'text'  => '第一张幻灯图',  
				'desc'  => '',  
				'type'  => 'file',
			),
			'ad1_link_url' => array(
				'text'  => '图片链接地址',
        		'desc'  => '',
				'type'  => 'text',
			),
			'ad2_image_url'   => array( 
				'text'  => '第二张幻灯图',  
				'desc'  => '',         
				'type'  => 'file',
			),
			'ad2_link_url' => array(
				'text'  => '图片链接地址',
        		'desc'  => '',
				'type'  => 'text',
			),
			'ad3_image_url'   => array( 
				'text'  => '第三张幻灯图',  
				'desc'  => '',         
				'type'  => 'file',
			),
			'ad3_link_url' => array(
				'text'  => '图片链接地址',
        		'desc'  => '',
				'type'  => 'text',
			),
			'ad4_image_url'   => array( 
				'text'  => '第四张幻灯图',  
				'desc'  => '',         
				'type'  => 'file',
			),
			'ad4_link_url' => array(
				'text'  => '图片链接地址',
        		'desc'  => '',
				'type'  => 'text',
			),
			'ad5_image_url'   => array( 
				'text'  => '第五张幻灯图',  
				'desc'  => '',         
				'type'  => 'file',
			),
			'ad5_link_url' => array(
				'text'  => '图片链接地址',
        		'desc'  => '',
				'type'  => 'text',
			),
			'ad6_image_url'   => array( 
				'text'  => '第六张幻灯图',  
				'desc'  => '',         
				'type'  => 'file',
			),
			'ad6_link_url' => array(
				'text'  => '图片链接地址',
        		'desc'  => '',
				'type'  => 'text',
			),
		),
	),
	'banner'    => array(
		'text'  => '底部一张广告图片',
        'desc'  => '宽度为640px',
		'model'  => 'image',
		'config' => array(
			'ad1_image_url'   => array( 
				'text'  => '广告图片',  
				'desc'  => '',         
				'type'  => 'file',
			),
			'ad1_link_url' => array(
				'text'  => '链接地址',
        		'desc'  => '',
				'type'  => 'text',
			),
		)
	)
)
?>
<?php
return array(
	'ad1'    => array(
		'text'  => '店铺banner',
        'desc'  => '',
		'model'  => 'image',
		'config' => array(
			'ad1_image_url'   => array( 
				'text'  => '广告图片',  
				'desc'  => '',         
				'type'  => 'file',
			),
			'ad1_link_url' => array(
				'text'  => '图片链接地址',
        		'desc'  => '',
				'type'  => 'text',
			),
		)
	),
	'slides'    => array(
		'text'  => '通栏幻灯片',
        'desc'  => '',
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
			)
		)
    )
)
?>
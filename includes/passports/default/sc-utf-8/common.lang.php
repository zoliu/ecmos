<?php

return array(
	'toseller_store_closed_notify' => '您的店铺已被关闭，原因是：{$reason}',
	'toseller_store_opened_notify' => '您的店铺已开通',
	'toseller_store_expired_closed_notify' => '您的店铺已被关闭，原因是：店铺已到期',
	'toseller_groupbuy_end_notify' => '请尽快到“已结束的团购”完成该团购活动，以便买家可以完成交易，如结束后{$cancel_days}天未确认完成，该活动将被自动取消,查看[url={$site_url}/index.php?app=seller_groupbuy&state=end]已结束的团购[/url]',
	'tobuyer_groupbuy_cancel_notify' => '团购活动被卖家取消,原因如下：\r\n{$reason}\r\n[url={$url}]查看详情[/url]',
	'tobuyer_group_auto_cancel_notify' => '团购活动结束{$cancel_days}天后卖家未确认完成，活动自动取消，[url={$url}]查看详情[/url]',
	'touser_send_coupon' => '您收到了 “{$store_name}” 发送来的优惠券 \r\n 优惠金额：{$price} \r\n有效期：{$start_time} 至{$end_time} \r\n优惠券号码：{$coupon_sn} \r\n使用条件：购物满 {$min_amount} 即可使用 \r\n店铺地址：[url={$url}]{$store_name}[/url]',
	'tobuyer_groupbuy_finished_notify' => '“{$group_name}”活动成功完成，请尽快购买活动商品。[url={$site_url}/index.php?app=order&goods=groupbuy&group_id={$id}]点此购买[/url]',
	'toseller_goods_droped_notify' => '管理员删除了您的商品：{$goods_name}\r\n原因是：{$reason}',
	'toseller_brand_passed_notify' => '恭喜！您申请的品牌 {$brand_name} 已通过审核。',
	'toseller_brand_refused_notify' => '抱歉，您申请的品牌 {$brand_name} 已被拒绝，原因如下：\r\n{$reason}',
	'toseller_store_droped_notify' => '您的店铺已被删除',
	'toseller_store_passed_notify' => '恭喜，您的店铺已开通，赶快来用户中心发布商品吧。',
	'toseller_store_refused_notify' => '抱歉，您的开店申请已被拒绝，原因如下： {$reason}',

	'code_example' => "图片标签：[img]http://360cd.cn/images/logo.gif[/img]<br/>超链接标签：[url=http://360cd.cn]网站[/url]",
);

?>
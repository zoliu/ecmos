<?php
return array (
  'version' => '1.0',
  'subject' => '{$site_name}提醒:店铺{$order.seller_name}确认收到了您的货款，交易完成！',
  'content' => '<p>尊敬的{$order.buyer_name}:</p>
<p style="padding-left: 30px;">与您交易的店铺{$order.seller_name}已经确认收到了您的货到付款订单{$order.order_sn}的付款，交易完成！您可以到用户中心-&gt;我的订单中对该交易进行评价。</p>
<p style="padding-left: 30px;">查看订单详细信息请点击以下链接</p>
<p style="padding-left: 30px;"><a href="{$site_url}/index.php?app=buyer_order&amp;act=view&amp;order_id={$order.order_id}">{$site_url}/index.php?app=buyer_order&amp;act=view&amp;order_id={$order.order_id}</a></p>
<p style="padding-left: 30px;">查看我的订单列表请点击以下链接</p>
<p style="padding-left: 30px;"><a href="{$site_url}/index.php?app=buyer_order">{$site_url}/index.php?app=buyer_order</a></p>
<p style="text-align: right;">{$site_name}</p>
<p style="text-align: right;">{$mail_send_time}</p>',
);
?>
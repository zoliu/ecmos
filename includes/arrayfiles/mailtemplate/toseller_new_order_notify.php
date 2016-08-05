<?php
return array (
  'version' => '1.0',
  'subject' => '{$site_name}提醒:您有一个新订单需要处理',
  'content' => '<p>尊敬的{$order.seller_name}:</p>
<p style="padding-left: 30px;">您有一个新的订单需要处理，订单号{$order.order_sn}，请尽快处理。</p>
<p style="padding-left: 30px;">查看订单详细信息请点击以下链接</p>
<p style="padding-left: 30px;"><a href="{$site_url}/index.php?app=seller_order&amp;act=view&amp;order_id={$order.order_id}">{$site_url}/index.php?app=seller_order&amp;act=view&amp;order_id={$order.order_id}</a></p>
<p style="padding-left: 30px;">查看您的订单列表管理页请点击以下链接</p>
<p style="padding-left: 30px;"><a href="{$site_url}/index.php?app=seller_order">{$site_url}/index.php?app=seller_order</a></p>
<p style="text-align: right;">{$site_name}</p>
<p style="text-align: right;">{$mail_send_time}</p>',
);
?>
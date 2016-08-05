<?php
return array (
  'version' => '1.0',
  'subject' => '{$site_name}提醒:{$user.user_name}您的{$type}咨询已得到回复',
  'content' => '<p>尊敬的用户:</p>
<p style="padding-left: 30px;">您好, 您在 {$site_name} 中的“{$item_name}”咨询已得到回复，请点击下面的链接查看：</p>
<p style="padding-left: 30px;"><a href="{$url}">{$url}</a></p>
<p style="padding-left: 30px;"> 如果以上链接无法点击，请将它拷贝到浏览器(例如IE)的地址栏中。</p>
<p style="text-align: right;">{$site_name}</p>
<p style="text-align: right;">{$mail_send_time}</p>',
);
?>
<?php
return array (
  'version' => '1.0',
  'subject' => '{$site_name}提醒:{$user.user_name}修改密码设置',
  'content' => '<p>尊敬的{$user.user_name}:</p>
<p style="padding-left: 30px;">您好, 您刚才在 {$site_name} 申请了重置密码，请点击下面的链接进行重置：</p>
<p style="padding-left: 30px;"><a href="{$site_url}/index.php?app=find_password&act=set_password&id={$user.user_id}&activation={$word}">{$site_url}/index.php?app=find_password&act=set_password&id={$user.user_id}&activation={$word}</a></p>
<p style="padding-left: 30px;">此链接只能使用一次, 如果失效请重新申请. 如果以上链接无法点击，请将它拷贝到浏览器(例如IE)的地址栏中。</p>
<p style="text-align: right;">{$site_name}</p>
<p style="text-align: right;">{$mail_send_time}</p>',
);
?>
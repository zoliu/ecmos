# ECMOS安装
###使用install安装
执行路径http://you web site/install
一路安装下去就可以

### 2017-4-5 更新日志
1. 增加了系统提示的屏蔽。隐藏使用信息。
2. 修改了Nginx后台的兼容性
3. 修改了语言文件jslang调用的错误

### 2017-03-25 更新日志
1. 增加in_goods_id在ecm_goods表用于库存
2. 修改了表导出的引挚出错TYPE=MYISAM问题 
3. 修改了后台编辑模板时的调用出错问题 
4. 修复了微信调用的远程路径
5. 删除了/includes/libraries/Http.lib.php
6. 增加了云库功能，平台可以为指定店铺分配指定商品
7. 管理员可以添加商品，修改商品，以及修改商铺商品的如规格，价格之类的
8. 修复了PHP版本升级的FrontendApp的脚本错误。
9. 修复后台管理员界面jquery调用层级出错
10. 手动修改SQL：ALTER TABLE `ecm_goods` ADD COLUMN `in_goods_id` INT NULL ; 


### 2017-03-14 更新日志
1. 增加了链式数据库写法详细 写法，可以参考/external/ds/下边的所有的文件
如：LM('navigation')->where("type='middle'")->orderBy('sort_order')->limit($params['num'])->find();

2. 增加了新的模板引挚，为下一步的模板做准备，标签可以在任意页面中调用。
如：
{ds name=article type=view article_id=?}
{ds name=article type=new num=? cate_id=? code=? store_id=?}
{ds name=goods type=view goods_id=? }
3. 去除了外部接口的调试语句
4. 调整了短信发送的接口

### 2017-03-13 更新日志
1. 修改了上一次更新的外部API调用的BUG，并且关闭了调试模式
2. 增加了百度编辑器
3. 云存储目前还在迁移过程中，待完成再升级
4. 去除了很多无用的功能。

### 2017-02-20更新日志
1. 增加了外部登陆调用接口
2. 增加了外部注册调用接口
3. 修改了导入已有类的兼容性问题
4. 登陆接口:格式如/?app=api&appid=[管理员后台填写的登陆appid]&appkey=[管理员后台填写的登陆appkey]&uid=[用户的email|用户的mobile|用户的code|用户的username]&act=[userLogin|emailLogin|codeLogin|mobileLogin]
5. 注册接口：接口为/?app=api&appid=[管理员后台填写的登陆appid]&appkey=[管理员后台填写的登陆appkey]&username=&email=&mobile=&password=&act=register
 * 其中username,email,mobile,不能全为空

### 2016-12-04更新日志
1. 修改了调试模式的开启条件需要config.inc.php中的DEBUG_MODE
- 增加了新的调用方式SL(),与LM()是为了与新的模块兼容，SL()默认调用/includes/libraries/zllib/下的文件夹或目录，如SL('member'),将调用/includes/libraries/zllib/member.lib.php或/includes/libraries/zllib/member/member.lib.php系统采用自动载入，LM()与&m('');载入效果是一样的，不过，LM()可以直接使用比如LM('goods')->find($where);


### 2016-12-04紧急更新日志
1. 增加了防注入插件
2. 修改了团购的注入漏洞
3. 防注入插件需要在商城后台插件处开启
4. 修改了商品发布处的因为高度限制产生的部分信息被隐藏

### 2016-10-13 更新记录
1. 修改了短信调用接口,短信0.055元一条。


### 2016-09-17 更新记录
1. 后台管理员界面index.js错误引起的更新弹层不显示。
2. 修改了升级功能，调用了系统说明，以及当前版本的功能说明显示。
3. 增加网银支付接口
4. 增加银联支付接口
5. 增加首页按区域显示，或筛选功能。
6. 修复SQL安装之后文件未删除引起的后续安装出错

### 2016-09-16 更新记录
1. 验证码不能显示的BUG，由于宽高数值的错误引起的。
- 修改了双核浏览器的跨内核登录兼容功能。
- 修改了后台在低版本IE的不兼容问题，去掉了部分ICON，改成文字，以便兼容低版本IE
- 修改了首页在低版本IE的兼容问题。
- 增加注册关键词过滤插件，可以过滤注册的关键词，不允许某些词语被注册。需要在后台/扩展/插件处开启
- 增加了匿名登陆功能
- 修复了手机版登陆时grade表错误


### 2016-09-02更新记录
1. 更新了升级功能不能自动连接服务器的bug
2. 增加了管理员独立密码功能，后台管理员密码使用新的算法，以及不同于前台的密码体系，让ECMALL本身的因为管理员与用户帐号存与一起造成的可能密码泄漏或易猜测问题，得到解决

###2016-8-28修复内容
1. 修复了安装后，安装时输入的密码无效的bug。
- 修复了未安装情况下的不能自动跳转到安装路径。
- 增加了两套店铺模板
- 增加在线升级功能，用户可以在线直接进行升级。

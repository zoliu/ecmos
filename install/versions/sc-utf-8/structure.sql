--
-- 表的结构 `ecm_acategory`
--

DROP TABLE IF EXISTS `ecm_acategory`;
CREATE TABLE `ecm_acategory` (
  `cate_id` int(10) unsigned NOT NULL auto_increment,
  `cate_name` varchar(100) NOT NULL default '',
  `parent_id` int(10) unsigned NOT NULL default '0',
  `sort_order` tinyint(3) unsigned NOT NULL default '255',
  `code` varchar(10) default NULL,
  PRIMARY KEY  (`cate_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_address`
--

DROP TABLE IF EXISTS `ecm_address`;
CREATE TABLE `ecm_address` (
  `addr_id` int(10) unsigned NOT NULL auto_increment,
  `user_id` int(10) unsigned NOT NULL default '0',
  `consignee` varchar(60) NOT NULL default '',
  `region_id` int(10) unsigned default NULL,
  `region_name` varchar(255) default NULL,
  `address` varchar(255) default NULL,
  `zipcode` varchar(20) default NULL,
  `phone_tel` varchar(60) default NULL,
  `phone_mob` varchar(60) default NULL,
  PRIMARY KEY  (`addr_id`),
  KEY `user_id` (`user_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_article`
--

DROP TABLE IF EXISTS `ecm_article`;
CREATE TABLE `ecm_article` (
  `article_id` int(10) unsigned NOT NULL auto_increment,
  `code` varchar(20) NOT NULL default '',
  `title` varchar(100) NOT NULL default '',
  `cate_id` int(10) NOT NULL default '0',
  `store_id` int(10) unsigned NOT NULL default '0',
  `link` varchar(255) default NULL,
  `content` text,
  `sort_order` tinyint(3) unsigned NOT NULL default '255',
  `if_show` tinyint(3) unsigned NOT NULL default '1',
  `add_time` int(10) unsigned default NULL,
  PRIMARY KEY  (`article_id`),
  KEY `code` (`code`),
  KEY `cate_id` (`cate_id`),
  KEY `store_id` (`store_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_attribute`
--

DROP TABLE IF EXISTS `ecm_attribute`;
CREATE TABLE `ecm_attribute` (
  `attr_id` int(10) unsigned NOT NULL auto_increment,
  `attr_name` varchar(60) NOT NULL default '',
  `input_mode` varchar(10) NOT NULL default 'text',
  `def_value` varchar(255) default NULL,
  PRIMARY KEY  (`attr_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_brand`
--

DROP TABLE IF EXISTS `ecm_brand`;
CREATE TABLE `ecm_brand` (
  `brand_id` int(10) unsigned NOT NULL auto_increment,
  `brand_name` varchar(100) NOT NULL default '',
  `brand_logo` varchar(255) default NULL,
  `sort_order` tinyint(3) unsigned NOT NULL default '255',
  `recommended` tinyint(3) unsigned NOT NULL default '0',
  `store_id` int(10) unsigned NOT NULL default '0',
  `if_show` tinyint(2) unsigned NOT NULL default '1',
  `tag` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`brand_id`),
  KEY `tag` (`tag`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_cart`
--

DROP TABLE IF EXISTS `ecm_cart`;
CREATE TABLE `ecm_cart` (
  `rec_id` int(10) unsigned NOT NULL auto_increment,
  `user_id` int(10) unsigned NOT NULL default '0',
  `session_id` varchar(32) NOT NULL default '',
  `store_id` int(10) unsigned NOT NULL default '0',
  `goods_id` int(10) unsigned NOT NULL default '0',
  `goods_name` varchar(255) NOT NULL default '',
  `spec_id` int(10) unsigned NOT NULL default '0',
  `specification` varchar(255) default NULL,
  `price` decimal(10,2) unsigned NOT NULL default '0.00',
  `quantity` int(10) unsigned NOT NULL default '1',
  `goods_image` varchar(255) default NULL,
  PRIMARY KEY  (`rec_id`),
  KEY `session_id` (`session_id`),
  KEY `user_id` (`user_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_category_goods`
--

DROP TABLE IF EXISTS `ecm_category_goods`;
CREATE TABLE `ecm_category_goods` (
  `cate_id` int(10) unsigned NOT NULL default '0',
  `goods_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`cate_id`,`goods_id`),
  KEY `goods_id` (`goods_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_category_store`
--

DROP TABLE IF EXISTS `ecm_category_store`;
CREATE TABLE `ecm_category_store` (
  `cate_id` int(10) unsigned NOT NULL default '0',
  `store_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`cate_id`,`store_id`),
  KEY `store_id` (`store_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_collect`
--

DROP TABLE IF EXISTS `ecm_collect`;
CREATE TABLE `ecm_collect` (
  `user_id` int(10) unsigned NOT NULL default '0',
  `type` varchar(10) NOT NULL default 'goods',
  `item_id` int(10) unsigned NOT NULL default '0',
  `keyword` varchar(60) default NULL,
  `add_time` int(10) unsigned default NULL,
  PRIMARY KEY  (`user_id`,`type`,`item_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_coupon`
--

DROP TABLE IF EXISTS `ecm_coupon`;
CREATE TABLE `ecm_coupon` (
  `coupon_id` int(10) unsigned NOT NULL auto_increment,
  `store_id` int(10) unsigned NOT NULL default '0',
  `coupon_name` varchar(100) NOT NULL default '',
  `coupon_value` decimal(10,2) unsigned NOT NULL default '0.00',
  `use_times` int(10) unsigned NOT NULL default '0',
  `start_time` int(10) unsigned NOT NULL default '0',
  `end_time` int(10) unsigned NOT NULL default '0',
  `min_amount` decimal(10,2) unsigned NOT NULL default '0.00',
  `if_issue` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`coupon_id`),
  KEY `store_id` (`store_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_coupon_sn`
--

DROP TABLE IF EXISTS `ecm_coupon_sn`;
CREATE TABLE `ecm_coupon_sn` (
  `coupon_sn` varchar(20) NOT NULL,
  `coupon_id` int(10) unsigned NOT NULL default '0',
  `remain_times` int(10) NOT NULL default '-1',
  PRIMARY KEY  (`coupon_sn`),
  KEY `coupon_id` (`coupon_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_friend`
--

DROP TABLE IF EXISTS `ecm_friend`;
CREATE TABLE `ecm_friend` (
  `owner_id` int(10) unsigned NOT NULL default '0',
  `friend_id` int(10) unsigned NOT NULL default '0',
  `add_time` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`owner_id`,`friend_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_function`
--

DROP TABLE IF EXISTS `ecm_function`;
CREATE TABLE `ecm_function` (
  `func_code` varchar(20) NOT NULL default '',
  `func_name` varchar(60) NOT NULL default '',
  `privileges` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`func_code`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_gcategory`
--

DROP TABLE IF EXISTS `ecm_gcategory`;
CREATE TABLE `ecm_gcategory` (
  `cate_id` int(10) unsigned NOT NULL auto_increment,
  `store_id` int(10) unsigned NOT NULL default '0',
  `cate_name` varchar(100) NOT NULL default '',
  `parent_id` int(10) unsigned NOT NULL default '0',
  `sort_order` tinyint(3) unsigned NOT NULL default '255',
  `if_show` tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`cate_id`),
  KEY `store_id` (`store_id`,`parent_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_goods`
--

DROP TABLE IF EXISTS `ecm_goods`;
CREATE TABLE `ecm_goods` (
  `goods_id` int(10) unsigned NOT NULL auto_increment,
  `store_id` int(10) unsigned NOT NULL default '0',
  `type` varchar(10) NOT NULL default 'material',
  `goods_name` varchar(255) NOT NULL default '',
  `description` text,
  `cate_id` int(10) unsigned NOT NULL default '0',
  `cate_name` varchar(255) NOT NULL default '',
  `brand` varchar(100) NOT NULL,
  `spec_qty` tinyint(4) unsigned NOT NULL default '0',
  `spec_name_1` varchar(60) NOT NULL default '',
  `spec_name_2` varchar(60) NOT NULL default '',
  `if_show` tinyint(3) unsigned NOT NULL default '1',
  `closed` tinyint(3) unsigned NOT NULL default '0',
  `close_reason` varchar(255) default NULL,
  `add_time` int(10) unsigned NOT NULL default '0',
  `last_update` int(10) unsigned NOT NULL default '0',
  `default_spec` int(11) unsigned NOT NULL default '0',
  `default_image` varchar(255) NOT NULL default '',
  `recommended` tinyint(4) unsigned NOT NULL default '0',
  `cate_id_1` int(10) unsigned NOT NULL default '0',
  `cate_id_2` int(10) unsigned NOT NULL default '0',
  `cate_id_3` int(10) unsigned NOT NULL default '0',
  `cate_id_4` int(10) unsigned NOT NULL default '0',
  `price` decimal(10,2) NOT NULL default '0.00',
  `tags` varchar(102) NOT NULL,
  PRIMARY KEY  (`goods_id`),
  KEY `store_id` (`store_id`),
  KEY `cate_id` (`cate_id`),
  KEY `cate_id_1` (`cate_id_1`),
  KEY `cate_id_2` (`cate_id_2`),
  KEY `cate_id_3` (`cate_id_3`),
  KEY `cate_id_4` (`cate_id_4`),
  KEY `brand` (`brand`(10)),
  KEY `tags` (`tags`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_goods_attr`
--

DROP TABLE IF EXISTS `ecm_goods_attr`;
CREATE TABLE `ecm_goods_attr` (
  `gattr_id` int(10) unsigned NOT NULL auto_increment,
  `goods_id` int(10) unsigned NOT NULL default '0',
  `attr_name` varchar(60) NOT NULL default '',
  `attr_value` varchar(255) NOT NULL default '',
  `attr_id` int(10) unsigned default NULL,
  `sort_order` tinyint(3) unsigned default NULL,
  PRIMARY KEY  (`gattr_id`),
  KEY `goods_id` (`goods_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_goods_image`
--

DROP TABLE IF EXISTS `ecm_goods_image`;
CREATE TABLE `ecm_goods_image` (
  `image_id` int(10) unsigned NOT NULL auto_increment,
  `goods_id` int(10) unsigned NOT NULL default '0',
  `image_url` varchar(255) NOT NULL default '',
  `thumbnail` varchar(255) NOT NULL default '',
  `sort_order` tinyint(4) unsigned NOT NULL default '0',
  `file_id` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`image_id`),
  KEY `goods_id` (`goods_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_goods_qa`
--

DROP TABLE IF EXISTS `ecm_goods_qa`;
CREATE TABLE `ecm_goods_qa` (
  `ques_id` int(10) unsigned NOT NULL auto_increment,
  `question_content` varchar(255) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `store_id` int(10) unsigned NOT NULL,
  `email` varchar(60) NOT NULL,
  `item_id` int(10) unsigned NOT NULL default '0',
  `item_name` varchar(255) NOT NULL default '',
  `reply_content` varchar(255) NOT NULL,
  `time_post` int(10) unsigned NOT NULL,
  `time_reply` int(10) unsigned NOT NULL,
  `if_new` tinyint(3) unsigned NOT NULL default '1',
  `type` varchar(10) NOT NULL default 'goods',
  PRIMARY KEY  (`ques_id`),
  KEY `user_id` (`user_id`),
  KEY `goods_id` (`item_id`),
  KEY `store_id` (`store_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_goods_spec`
--

DROP TABLE IF EXISTS `ecm_goods_spec`;
CREATE TABLE `ecm_goods_spec` (
  `spec_id` int(10) unsigned NOT NULL auto_increment,
  `goods_id` int(10) unsigned NOT NULL default '0',
  `spec_1` varchar(60) NOT NULL default '',
  `spec_2` varchar(60) NOT NULL default '',
  `color_rgb` varchar(7) NOT NULL default '',
  `price` decimal(10,2) NOT NULL default '0.00',
  `stock` int(11) NOT NULL default '0',
  `sku` varchar(60) NOT NULL default '',
  PRIMARY KEY  (`spec_id`),
  KEY `goods_id` (`goods_id`),
  KEY `price` (`price`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_goods_statistics`
--

DROP TABLE IF EXISTS `ecm_goods_statistics`;
CREATE TABLE `ecm_goods_statistics` (
  `goods_id` int(10) unsigned NOT NULL default '0',
  `views` int(10) unsigned NOT NULL default '0',
  `collects` int(10) unsigned NOT NULL default '0',
  `carts` int(10) unsigned NOT NULL default '0',
  `orders` int(10) unsigned NOT NULL default '0',
  `sales` int(10) unsigned NOT NULL default '0',
  `comments` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`goods_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_groupbuy`
--

DROP TABLE IF EXISTS `ecm_groupbuy`;
CREATE TABLE `ecm_groupbuy` (
  `group_id` int(10) unsigned NOT NULL auto_increment,
  `group_name` varchar(255) NOT NULL default '',
  `group_desc` varchar(255) NOT NULL default '',
  `start_time` int(10) unsigned NOT NULL default '0',
  `end_time` int(10) unsigned NOT NULL default '0',
  `goods_id` int(10) unsigned NOT NULL default '0',
  `store_id` int(10) unsigned NOT NULL default '0',
  `spec_price` text NOT NULL,
  `min_quantity` smallint(5) unsigned NOT NULL default '0',
  `max_per_user` smallint(5) unsigned NOT NULL default '0',
  `state` tinyint(3) unsigned NOT NULL default '0',
  `recommended` tinyint(3) unsigned NOT NULL default '0',
  `views` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`group_id`),
  KEY `goods_id` (`goods_id`),
  KEY `store_id` (`store_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_groupbuy_log`
--

DROP TABLE IF EXISTS `ecm_groupbuy_log`;
CREATE TABLE `ecm_groupbuy_log` (
  `group_id` int(10) unsigned NOT NULL default '0',
  `user_id` int(10) unsigned NOT NULL default '0',
  `user_name` varchar(60) NOT NULL default '',
  `quantity` smallint(5) unsigned NOT NULL default '0',
  `spec_quantity` text NOT NULL,
  `linkman` varchar(60) NOT NULL default '',
  `tel` varchar(60) NOT NULL default '',
  `order_id` int(10) unsigned NOT NULL default '0',
  `add_time` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`group_id`,`user_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_mail_queue`
--

DROP TABLE IF EXISTS `ecm_mail_queue`;
CREATE TABLE `ecm_mail_queue` (
  `queue_id` int(11) unsigned NOT NULL auto_increment,
  `mail_to` varchar(150) NOT NULL default '',
  `mail_encoding` varchar(50) NOT NULL default '',
  `mail_subject` varchar(255) NOT NULL default '',
  `mail_body` text NOT NULL,
  `priority` tinyint(1) unsigned NOT NULL default '2',
  `err_num` tinyint(1) unsigned NOT NULL default '0',
  `add_time` int(11) NOT NULL default '0',
  `lock_expiry` int(11) NOT NULL default '0',
  PRIMARY KEY  (`queue_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_member`
--

DROP TABLE IF EXISTS `ecm_member`;
CREATE TABLE `ecm_member` (
  `user_id` int(10) unsigned NOT NULL auto_increment,
  `user_name` varchar(60) NOT NULL default '',
  `email` varchar(60) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `real_name` varchar(60) default NULL,
  `gender` tinyint(3) unsigned NOT NULL default '0',
  `birthday` date default NULL,
  `phone_tel` varchar(60) default NULL,
  `phone_mob` varchar(60) default NULL,
  `im_qq` varchar(60) default NULL,
  `im_msn` varchar(60) default NULL,
  `im_skype` varchar(60) default NULL,
  `im_yahoo` varchar(60) default NULL,
  `im_aliww` varchar(60) default NULL,
  `reg_time` int(10) unsigned default '0',
  `last_login` int(10) unsigned default NULL,
  `last_ip` varchar(15) default NULL,
  `logins` int(10) unsigned NOT NULL default '0',
  `ugrade` tinyint(3) unsigned NOT NULL default '0',
  `portrait` varchar(255) default NULL,
  `outer_id` int(10) unsigned NOT NULL default '0',
  `activation` varchar(60) default NULL,
  `feed_config` text NOT NULL,
  PRIMARY KEY  (`user_id`),
  KEY `user_name` (`user_name`),
  KEY `email` (`email`),
  KEY `outer_id` (`outer_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_message`
--

DROP TABLE IF EXISTS `ecm_message`;
CREATE TABLE `ecm_message` (
  `msg_id` int(10) unsigned NOT NULL auto_increment,
  `from_id` int(10) unsigned NOT NULL default '0',
  `to_id` int(10) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `content` text NOT NULL,
  `add_time` int(10) unsigned NOT NULL default '0',
  `last_update` int(10) unsigned NOT NULL default '0',
  `new` tinyint(3) unsigned NOT NULL default '0',
  `parent_id` int(10) unsigned NOT NULL default '0',
  `status` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`msg_id`),
  KEY `from_id` (`from_id`),
  KEY `to_id` (`to_id`),
  KEY `parent_id` (`parent_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_module`
--

DROP TABLE IF EXISTS `ecm_module`;
CREATE TABLE `ecm_module` (
  `module_id` varchar(30) NOT NULL default '',
  `module_name` varchar(100) NOT NULL default '',
  `module_version` varchar(5) NOT NULL default '',
  `module_desc` text NOT NULL,
  `module_config` text NOT NULL,
  `enabled` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`module_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_navigation`
--

DROP TABLE IF EXISTS `ecm_navigation`;
CREATE TABLE `ecm_navigation` (
  `nav_id` int(10) unsigned NOT NULL auto_increment,
  `type` varchar(10) NOT NULL default '',
  `title` varchar(60) NOT NULL default '',
  `link` varchar(255) NOT NULL default '',
  `sort_order` tinyint(3) unsigned NOT NULL default '255',
  `open_new` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`nav_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_order`
--

DROP TABLE IF EXISTS `ecm_order`;
CREATE TABLE `ecm_order` (
  `order_id` int(10) unsigned NOT NULL auto_increment,
  `order_sn` varchar(20) NOT NULL default '',
  `type` varchar(10) NOT NULL default 'material',
  `extension` varchar(10) NOT NULL default '',
  `seller_id` int(10) unsigned NOT NULL default '0',
  `seller_name` varchar(100) default NULL,
  `buyer_id` int(10) unsigned NOT NULL default '0',
  `buyer_name` varchar(100) default NULL,
  `buyer_email` varchar(60) NOT NULL default '',
  `status` tinyint(3) unsigned NOT NULL default '0',
  `add_time` int(10) unsigned NOT NULL default '0',
  `payment_id` int(10) unsigned default NULL,
  `payment_name` varchar(100) default NULL,
  `payment_code` varchar(20) NOT NULL default '',
  `out_trade_sn` varchar(20) NOT NULL default '',
  `pay_time` int(10) unsigned default NULL,
  `pay_message` varchar(255) NOT NULL default '',
  `ship_time` int(10) unsigned default NULL,
  `invoice_no` varchar(255) default NULL,
  `finished_time` int(10) unsigned NOT NULL default '0',
  `goods_amount` decimal(10,2) unsigned NOT NULL default '0.00',
  `discount` decimal(10,2) unsigned NOT NULL default '0.00',
  `order_amount` decimal(10,2) unsigned NOT NULL default '0.00',
  `evaluation_status` tinyint(1) unsigned NOT NULL default '0',
  `evaluation_time` int(10) unsigned NOT NULL default '0',
  `anonymous` tinyint(3) unsigned NOT NULL default '0',
  `postscript` varchar(255) NOT NULL default '',
  `pay_alter` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`order_id`),
  KEY `order_sn` (`order_sn`,`seller_id`),
  KEY `seller_name` (`seller_name`),
  KEY `buyer_name` (`buyer_name`),
  KEY `add_time` (`add_time`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_order_extm`
--

DROP TABLE IF EXISTS `ecm_order_extm`;
CREATE TABLE `ecm_order_extm` (
  `order_id` int(10) unsigned NOT NULL default '0',
  `consignee` varchar(60) NOT NULL default '',
  `region_id` int(10) unsigned default NULL,
  `region_name` varchar(255) default NULL,
  `address` varchar(255) default NULL,
  `zipcode` varchar(20) default NULL,
  `phone_tel` varchar(60) default NULL,
  `phone_mob` varchar(60) default NULL,
  `shipping_id` int(10) unsigned default NULL,
  `shipping_name` varchar(100) default NULL,
  `shipping_fee` decimal(10,2) NOT NULL default '0.00',
  PRIMARY KEY  (`order_id`),
  KEY `consignee` (`consignee`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_order_goods`
--

DROP TABLE IF EXISTS `ecm_order_goods`;
CREATE TABLE `ecm_order_goods` (
  `rec_id` int(10) unsigned NOT NULL auto_increment,
  `order_id` int(10) unsigned NOT NULL default '0',
  `goods_id` int(10) unsigned NOT NULL default '0',
  `goods_name` varchar(255) NOT NULL default '',
  `spec_id` int(10) unsigned NOT NULL default '0',
  `specification` varchar(255) default NULL,
  `price` decimal(10,2) unsigned NOT NULL default '0.00',
  `quantity` int(10) unsigned NOT NULL default '1',
  `goods_image` varchar(255) default NULL,
  `evaluation` tinyint(1) unsigned NOT NULL default '0',
  `comment` varchar(255) NOT NULL default '',
  `credit_value` tinyint(1) NOT NULL default '0',
  `is_valid` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`rec_id`),
  KEY `order_id` (`order_id`,`goods_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_order_log`
--

DROP TABLE IF EXISTS `ecm_order_log`;
CREATE TABLE `ecm_order_log` (
  `log_id` int(10) unsigned NOT NULL auto_increment,
  `order_id` int(10) unsigned NOT NULL default '0',
  `operator` varchar(60) NOT NULL default '',
  `order_status` varchar(60) NOT NULL default '',
  `changed_status` varchar(60) NOT NULL default '',
  `remark` varchar(255) default NULL,
  `log_time` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`log_id`),
  KEY `order_id` (`order_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_pageview`
--

DROP TABLE IF EXISTS `ecm_pageview`;
CREATE TABLE `ecm_pageview` (
  `rec_id` int(10) unsigned NOT NULL auto_increment,
  `store_id` int(10) unsigned NOT NULL default '0',
  `view_date` date NOT NULL default '0000-00-00',
  `view_times` int(10) unsigned NOT NULL default '1',
  PRIMARY KEY  (`rec_id`),
  UNIQUE KEY `storedate` (`store_id`,`view_date`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_partner`
--

DROP TABLE IF EXISTS `ecm_partner`;
CREATE TABLE `ecm_partner` (
  `partner_id` int(10) unsigned NOT NULL auto_increment,
  `store_id` int(10) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `link` varchar(255) NOT NULL default '',
  `logo` varchar(255) default NULL,
  `sort_order` tinyint(3) unsigned NOT NULL default '255',
  PRIMARY KEY  (`partner_id`),
  KEY `store_id` (`store_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_payment`
--

DROP TABLE IF EXISTS `ecm_payment`;
CREATE TABLE `ecm_payment` (
  `payment_id` int(10) unsigned NOT NULL auto_increment,
  `store_id` int(10) unsigned NOT NULL default '0',
  `payment_code` varchar(20) NOT NULL default '',
  `payment_name` varchar(100) NOT NULL default '',
  `payment_desc` varchar(255) default NULL,
  `config` text,
  `is_online` tinyint(3) unsigned NOT NULL default '1',
  `enabled` tinyint(3) unsigned NOT NULL default '1',
  `sort_order` tinyint(3) unsigned NOT NULL default '255',
  PRIMARY KEY  (`payment_id`),
  KEY `store_id` (`store_id`),
  KEY `payment_code` (`payment_code`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_privilege`
--

DROP TABLE IF EXISTS `ecm_privilege`;
CREATE TABLE `ecm_privilege` (
  `priv_code` varchar(20) NOT NULL default '',
  `priv_name` varchar(60) NOT NULL default '',
  `parent_code` varchar(20) default NULL,
  `owner` varchar(10) NOT NULL default 'mall',
  PRIMARY KEY  (`priv_code`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_recommend`
--

DROP TABLE IF EXISTS `ecm_recommend`;
CREATE TABLE `ecm_recommend` (
  `recom_id` int(10) unsigned NOT NULL auto_increment,
  `recom_name` varchar(100) NOT NULL default '',
  `store_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`recom_id`),
  KEY `store_id` (`store_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_recommended_goods`
--

DROP TABLE IF EXISTS `ecm_recommended_goods`;
CREATE TABLE `ecm_recommended_goods` (
  `recom_id` int(10) unsigned NOT NULL default '0',
  `goods_id` int(10) unsigned NOT NULL default '0',
  `sort_order` tinyint(3) unsigned NOT NULL default '255',
  PRIMARY KEY  (`recom_id`,`goods_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_region`
--

DROP TABLE IF EXISTS `ecm_region`;
CREATE TABLE `ecm_region` (
  `region_id` int(10) unsigned NOT NULL auto_increment,
  `region_name` varchar(100) NOT NULL default '',
  `parent_id` int(10) unsigned NOT NULL default '0',
  `sort_order` tinyint(3) unsigned NOT NULL default '255',
  PRIMARY KEY  (`region_id`),
  KEY `parent_id` (`parent_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_scategory`
--

DROP TABLE IF EXISTS `ecm_scategory`;
CREATE TABLE `ecm_scategory` (
  `cate_id` int(10) unsigned NOT NULL auto_increment,
  `cate_name` varchar(100) NOT NULL default '',
  `parent_id` int(10) unsigned NOT NULL default '0',
  `sort_order` tinyint(3) unsigned NOT NULL default '255',
  PRIMARY KEY  (`cate_id`),
  KEY `parent_id` (`parent_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_sessions`
--

DROP TABLE IF EXISTS `ecm_sessions`;
CREATE TABLE `ecm_sessions` (
  `sesskey` char(32) NOT NULL default '',
  `expiry` int(11) NOT NULL default '0',
  `userid` int(11) NOT NULL default '0',
  `adminid` int(11) NOT NULL default '0',
  `ip` char(15) NOT NULL default '',
  `data` char(255) NOT NULL default '',
  `is_overflow` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`sesskey`),
  KEY `expiry` (`expiry`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_sessions_data`
--

DROP TABLE IF EXISTS `ecm_sessions_data`;
CREATE TABLE `ecm_sessions_data` (
  `sesskey` varchar(32) NOT NULL default '',
  `expiry` int(11) NOT NULL default '0',
  `data` longtext NOT NULL,
  PRIMARY KEY  (`sesskey`),
  KEY `expiry` (`expiry`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_sgrade`
--

DROP TABLE IF EXISTS `ecm_sgrade`;
CREATE TABLE `ecm_sgrade` (
  `grade_id` tinyint(3) unsigned NOT NULL auto_increment,
  `grade_name` varchar(60) NOT NULL default '',
  `goods_limit` int(10) unsigned NOT NULL default '0',
  `space_limit` int(10) unsigned NOT NULL default '0',
  `skin_limit` int(10) unsigned NOT NULL default '0',
  `charge` varchar(100) NOT NULL default '',
  `need_confirm` tinyint(3) unsigned NOT NULL default '0',
  `description` varchar(255) NOT NULL default '',
  `functions` varchar(255) default NULL,
  `skins` text NOT NULL,
  `sort_order` tinyint(4) unsigned NOT NULL default '0',
  PRIMARY KEY  (`grade_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_shipping`
--

DROP TABLE IF EXISTS `ecm_shipping`;
CREATE TABLE `ecm_shipping` (
  `shipping_id` int(10) unsigned NOT NULL auto_increment,
  `store_id` int(10) unsigned NOT NULL default '0',
  `shipping_name` varchar(100) NOT NULL default '',
  `shipping_desc` varchar(255) default NULL,
  `first_price` decimal(10,2) NOT NULL default '0.00',
  `step_price` decimal(10,2) NOT NULL default '0.00',
  `cod_regions` text,
  `enabled` tinyint(3) unsigned NOT NULL default '1',
  `sort_order` tinyint(3) unsigned NOT NULL default '255',
  PRIMARY KEY  (`shipping_id`),
  KEY `store_id` (`store_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_store`
--

DROP TABLE IF EXISTS `ecm_store`;
CREATE TABLE `ecm_store` (
  `store_id` int(10) unsigned NOT NULL default '0',
  `store_name` varchar(100) NOT NULL default '',
  `owner_name` varchar(60) NOT NULL default '',
  `owner_card` varchar(60) NOT NULL default '',
  `region_id` int(10) unsigned default NULL,
  `region_name` varchar(100) default NULL,
  `address` varchar(255) NOT NULL default '',
  `zipcode` varchar(20) NOT NULL default '',
  `tel` varchar(60) NOT NULL default '',
  `sgrade` tinyint(3) unsigned NOT NULL default '0',
  `apply_remark` varchar(255) NOT NULL default '',
  `credit_value` int(10) NOT NULL default '0',
  `praise_rate` decimal(5,2) unsigned NOT NULL default '0.00',
  `domain` varchar(60) default NULL,
  `state` tinyint(3) unsigned NOT NULL default '0',
  `close_reason` varchar(255) NOT NULL default '',
  `add_time` int(10) unsigned default NULL,
  `end_time` int(10) unsigned NOT NULL default '0',
  `certification` varchar(255) default NULL,
  `sort_order` smallint(5) unsigned NOT NULL default '0',
  `recommended` tinyint(4) NOT NULL default '0',
  `theme` varchar(60) NOT NULL default '',
  `store_banner` varchar(255) default NULL,
  `store_logo` varchar(255) default NULL,
  `description` text,
  `image_1` varchar(255) NOT NULL default '',
  `image_2` varchar(255) NOT NULL default '',
  `image_3` varchar(255) NOT NULL default '',
  `im_qq` varchar(60) NOT NULL default '',
  `im_ww` varchar(60) NOT NULL default '',
  `im_msn` varchar(60) NOT NULL default '',
  `enable_groupbuy` tinyint(1) UNSIGNED NOT NULL default '0',
  `enable_radar` tinyint(1) UNSIGNED NOT NULL default '1',
  PRIMARY KEY  (`store_id`),
  KEY `store_name` (`store_name`),
  KEY `owner_name` (`owner_name`),
  KEY `region_id` (`region_id`),
  KEY `domain` (`domain`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_uploaded_file`
--

DROP TABLE IF EXISTS `ecm_uploaded_file`;
CREATE TABLE `ecm_uploaded_file` (
  `file_id` int(10) unsigned NOT NULL auto_increment,
  `store_id` int(10) unsigned NOT NULL default '0',
  `file_type` varchar(60) NOT NULL default '',
  `file_size` int(10) unsigned NOT NULL default '0',
  `file_name` varchar(255) NOT NULL default '',
  `file_path` varchar(255) NOT NULL default '',
  `add_time` int(10) unsigned NOT NULL default '0',
  `belong` tinyint(3) unsigned NOT NULL default '0',
  `item_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`file_id`),
  KEY `store_id` (`store_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_user_coupon`
--

DROP TABLE IF EXISTS `ecm_user_coupon`;
CREATE TABLE `ecm_user_coupon` (
  `user_id` int(10) unsigned NOT NULL,
  `coupon_sn` varchar(20) NOT NULL,
  PRIMARY KEY  (`user_id`,`coupon_sn`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- 表的结构 `ecm_user_priv`
--

DROP TABLE IF EXISTS `ecm_user_priv`;
CREATE TABLE `ecm_user_priv` (
  `user_id` int(10) unsigned NOT NULL default '0',
  `store_id` int(10) unsigned NOT NULL default '0',
  `privs` text NOT NULL,
  PRIMARY KEY  (`user_id`,`store_id`)
) TYPE=MyISAM;

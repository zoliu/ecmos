/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 5.5.49-0+deb7u1 : Database - ecmos
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `ecm_acategory` */

DROP TABLE IF EXISTS `ecm_acategory`;

CREATE TABLE `ecm_acategory` (
  `cate_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cate_name` varchar(100) NOT NULL DEFAULT '',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '255',
  `code` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`cate_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_address` */

DROP TABLE IF EXISTS `ecm_address`;

CREATE TABLE `ecm_address` (
  `addr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `consignee` varchar(60) NOT NULL DEFAULT '',
  `region_id` int(10) unsigned DEFAULT NULL,
  `region_name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `zipcode` varchar(20) DEFAULT NULL,
  `phone_tel` varchar(60) DEFAULT NULL,
  `phone_mob` varchar(60) DEFAULT NULL,
  `set_default` int(5) DEFAULT '0',
  `setdefault` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`addr_id`),
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_all_statistics` */

DROP TABLE IF EXISTS `ecm_all_statistics`;

CREATE TABLE `ecm_all_statistics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales` int(11) DEFAULT NULL,
  `collects` int(11) DEFAULT NULL,
  `carts` int(11) DEFAULT NULL,
  `visits` int(11) DEFAULT NULL,
  `cancels` int(11) DEFAULT NULL,
  `comments` int(11) DEFAULT NULL,
  `goodcomments` int(11) DEFAULT NULL,
  `normalcomments` int(11) DEFAULT NULL,
  `badcomments` int(11) DEFAULT NULL,
  `refunds` int(11) DEFAULT NULL,
  `moneys` decimal(20,0) DEFAULT NULL,
  `stype` tinyint(4) DEFAULT NULL,
  `sumdate` char(60) DEFAULT NULL,
  `add_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `store_name` char(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_app` */

DROP TABLE IF EXISTS `ecm_app`;

CREATE TABLE `ecm_app` (
  `app_name` char(100) DEFAULT NULL,
  `id` int(11) DEFAULT NULL,
  `app_tags` char(200) DEFAULT NULL,
  `app_desc` char(250) DEFAULT NULL,
  `app_content` text,
  `default_image` char(250) DEFAULT NULL,
  `install_num` int(11) DEFAULT NULL,
  `view_num` int(11) DEFAULT NULL,
  `update_num` int(11) DEFAULT NULL,
  `app_author` char(100) DEFAULT NULL,
  `add_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `app_type` tinyint(4) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `curr_version` char(100) DEFAULT NULL,
  `old_version` char(250) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_article` */

DROP TABLE IF EXISTS `ecm_article`;

CREATE TABLE `ecm_article` (
  `article_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL DEFAULT '',
  `title` varchar(100) NOT NULL DEFAULT '',
  `cate_id` int(10) NOT NULL DEFAULT '0',
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `link` varchar(255) DEFAULT NULL,
  `content` text,
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '255',
  `if_show` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `add_time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`article_id`),
  KEY `code` (`code`) USING BTREE,
  KEY `cate_id` (`cate_id`) USING BTREE,
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_attribute` */

DROP TABLE IF EXISTS `ecm_attribute`;

CREATE TABLE `ecm_attribute` (
  `attr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `attr_name` varchar(60) NOT NULL DEFAULT '',
  `input_mode` varchar(10) NOT NULL DEFAULT 'text',
  `def_value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`attr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_bank` */

DROP TABLE IF EXISTS `ecm_bank`;

CREATE TABLE `ecm_bank` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `card_number` char(32) NOT NULL,
  `cardholder` char(32) NOT NULL,
  `bank_name` char(64) NOT NULL,
  `bank_address` varchar(255) NOT NULL,
  `add_time` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_brand` */

DROP TABLE IF EXISTS `ecm_brand`;

CREATE TABLE `ecm_brand` (
  `brand_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(100) NOT NULL DEFAULT '',
  `brand_logo` varchar(255) DEFAULT NULL,
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '255',
  `recommended` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `if_show` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `tag` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`brand_id`),
  KEY `tag` (`tag`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_cart` */

DROP TABLE IF EXISTS `ecm_cart`;

CREATE TABLE `ecm_cart` (
  `rec_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `session_id` varchar(32) NOT NULL DEFAULT '',
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_name` varchar(255) NOT NULL DEFAULT '',
  `spec_id` int(10) unsigned NOT NULL DEFAULT '0',
  `specification` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `quantity` int(10) unsigned NOT NULL DEFAULT '1',
  `goods_image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`rec_id`),
  KEY `session_id` (`session_id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=113 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_cate_pvs` */

DROP TABLE IF EXISTS `ecm_cate_pvs`;

CREATE TABLE `ecm_cate_pvs` (
  `cate_id` int(11) NOT NULL,
  `pvs` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_category_goods` */

DROP TABLE IF EXISTS `ecm_category_goods`;

CREATE TABLE `ecm_category_goods` (
  `cate_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`cate_id`,`goods_id`),
  KEY `goods_id` (`goods_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_category_store` */

DROP TABLE IF EXISTS `ecm_category_store`;

CREATE TABLE `ecm_category_store` (
  `cate_id` int(10) unsigned NOT NULL DEFAULT '0',
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`cate_id`,`store_id`),
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_collect` */

DROP TABLE IF EXISTS `ecm_collect`;

CREATE TABLE `ecm_collect` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `type` varchar(10) NOT NULL DEFAULT 'goods',
  `item_id` int(10) unsigned NOT NULL DEFAULT '0',
  `keyword` varchar(60) DEFAULT NULL,
  `add_time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`user_id`,`type`,`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_coupon` */

DROP TABLE IF EXISTS `ecm_coupon`;

CREATE TABLE `ecm_coupon` (
  `coupon_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `coupon_name` varchar(100) NOT NULL DEFAULT '',
  `coupon_value` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `use_times` int(10) unsigned NOT NULL DEFAULT '0',
  `start_time` int(10) unsigned NOT NULL DEFAULT '0',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0',
  `min_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `if_issue` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`coupon_id`),
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_coupon_sn` */

DROP TABLE IF EXISTS `ecm_coupon_sn`;

CREATE TABLE `ecm_coupon_sn` (
  `coupon_sn` varchar(20) NOT NULL,
  `coupon_id` int(10) unsigned NOT NULL DEFAULT '0',
  `remain_times` int(10) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`coupon_sn`),
  KEY `coupon_id` (`coupon_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_delivery_template` */

DROP TABLE IF EXISTS `ecm_delivery_template`;

CREATE TABLE `ecm_delivery_template` (
  `template_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `store_id` int(10) NOT NULL,
  `template_types` text NOT NULL,
  `template_dests` text NOT NULL,
  `template_start_standards` text NOT NULL,
  `template_start_fees` text NOT NULL,
  `template_add_standards` text NOT NULL,
  `template_add_fees` text NOT NULL,
  `created` int(10) NOT NULL,
  PRIMARY KEY (`template_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_discus` */

DROP TABLE IF EXISTS `ecm_discus`;

CREATE TABLE `ecm_discus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `buyer_type` char(20) DEFAULT NULL,
  `buyer_remark` varchar(250) DEFAULT NULL,
  `buyer_addtime` int(11) DEFAULT NULL,
  `seller_type` char(20) DEFAULT NULL,
  `seller_remark` varchar(250) DEFAULT NULL,
  `seller_addtime` int(11) DEFAULT NULL,
  `admin_remark` varchar(250) DEFAULT NULL,
  `admin_addtime` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `buyer_id` int(11) DEFAULT NULL,
  `buyer_name` char(200) DEFAULT NULL,
  `seller_id` int(11) DEFAULT NULL,
  `seller_name` char(200) DEFAULT NULL,
  `is_pay` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_friend` */

DROP TABLE IF EXISTS `ecm_friend`;

CREATE TABLE `ecm_friend` (
  `owner_id` int(10) unsigned NOT NULL DEFAULT '0',
  `friend_id` int(10) unsigned NOT NULL DEFAULT '0',
  `add_time` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`owner_id`,`friend_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_function` */

DROP TABLE IF EXISTS `ecm_function`;

CREATE TABLE `ecm_function` (
  `func_code` varchar(20) NOT NULL DEFAULT '',
  `func_name` varchar(60) NOT NULL DEFAULT '',
  `privileges` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`func_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_gcategory` */

DROP TABLE IF EXISTS `ecm_gcategory`;

CREATE TABLE `ecm_gcategory` (
  `cate_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cate_name` varchar(100) NOT NULL DEFAULT '',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '255',
  `if_show` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `logo` char(250) DEFAULT NULL,
  PRIMARY KEY (`cate_id`),
  KEY `store_id` (`store_id`,`parent_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1254 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_goods` */

DROP TABLE IF EXISTS `ecm_goods`;

CREATE TABLE `ecm_goods` (
  `goods_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `type` varchar(10) NOT NULL DEFAULT 'material',
  `goods_name` varchar(255) NOT NULL DEFAULT '',
  `description` text,
  `cate_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cate_name` varchar(255) NOT NULL DEFAULT '',
  `brand` varchar(100) NOT NULL,
  `spec_qty` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `spec_name_1` varchar(60) NOT NULL DEFAULT '',
  `spec_name_2` varchar(60) NOT NULL DEFAULT '',
  `if_show` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `closed` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `close_reason` varchar(255) DEFAULT NULL,
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `last_update` int(10) unsigned NOT NULL DEFAULT '0',
  `default_spec` int(11) unsigned NOT NULL DEFAULT '0',
  `default_image` varchar(255) NOT NULL DEFAULT '',
  `recommended` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `cate_id_1` int(10) unsigned NOT NULL DEFAULT '0',
  `cate_id_2` int(10) unsigned NOT NULL DEFAULT '0',
  `cate_id_3` int(10) unsigned NOT NULL DEFAULT '0',
  `cate_id_4` int(10) unsigned NOT NULL DEFAULT '0',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tags` varchar(102) NOT NULL,
  `market_price` decimal(10,2) DEFAULT NULL,
  `delivery_template_id` int(11) NOT NULL,
  PRIMARY KEY (`goods_id`),
  KEY `store_id` (`store_id`) USING BTREE,
  KEY `cate_id` (`cate_id`) USING BTREE,
  KEY `cate_id_1` (`cate_id_1`) USING BTREE,
  KEY `cate_id_2` (`cate_id_2`) USING BTREE,
  KEY `cate_id_3` (`cate_id_3`) USING BTREE,
  KEY `cate_id_4` (`cate_id_4`) USING BTREE,
  KEY `brand` (`brand`(10)) USING BTREE,
  KEY `tags` (`tags`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_goods_attr` */

DROP TABLE IF EXISTS `ecm_goods_attr`;

CREATE TABLE `ecm_goods_attr` (
  `gattr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `attr_name` varchar(60) NOT NULL DEFAULT '',
  `attr_value` varchar(255) NOT NULL DEFAULT '',
  `attr_id` int(10) unsigned DEFAULT NULL,
  `sort_order` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`gattr_id`),
  KEY `goods_id` (`goods_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_goods_image` */

DROP TABLE IF EXISTS `ecm_goods_image`;

CREATE TABLE `ecm_goods_image` (
  `image_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `image_url` varchar(255) NOT NULL DEFAULT '',
  `thumbnail` varchar(255) NOT NULL DEFAULT '',
  `sort_order` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `file_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`image_id`),
  KEY `goods_id` (`goods_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=107 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_goods_integral` */

DROP TABLE IF EXISTS `ecm_goods_integral`;

CREATE TABLE `ecm_goods_integral` (
  `goods_id` int(11) NOT NULL,
  `max_exchange` int(11) NOT NULL,
  PRIMARY KEY (`goods_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_goods_prop` */

DROP TABLE IF EXISTS `ecm_goods_prop`;

CREATE TABLE `ecm_goods_prop` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `status` int(1) NOT NULL,
  `sort_order` int(11) NOT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_goods_prop_value` */

DROP TABLE IF EXISTS `ecm_goods_prop_value`;

CREATE TABLE `ecm_goods_prop_value` (
  `vid` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `prop_value` varchar(255) NOT NULL,
  `status` int(1) NOT NULL,
  `sort_order` int(11) NOT NULL,
  PRIMARY KEY (`vid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_goods_pvs` */

DROP TABLE IF EXISTS `ecm_goods_pvs`;

CREATE TABLE `ecm_goods_pvs` (
  `goods_id` int(11) NOT NULL,
  `pvs` text NOT NULL,
  PRIMARY KEY (`goods_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_goods_qa` */

DROP TABLE IF EXISTS `ecm_goods_qa`;

CREATE TABLE `ecm_goods_qa` (
  `ques_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `question_content` varchar(255) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `store_id` int(10) unsigned NOT NULL,
  `email` varchar(60) NOT NULL,
  `item_id` int(10) unsigned NOT NULL DEFAULT '0',
  `item_name` varchar(255) NOT NULL DEFAULT '',
  `reply_content` varchar(255) NOT NULL,
  `time_post` int(10) unsigned NOT NULL,
  `time_reply` int(10) unsigned NOT NULL,
  `if_new` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `type` varchar(10) NOT NULL DEFAULT 'goods',
  PRIMARY KEY (`ques_id`),
  KEY `user_id` (`user_id`) USING BTREE,
  KEY `goods_id` (`item_id`) USING BTREE,
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_goods_spec` */

DROP TABLE IF EXISTS `ecm_goods_spec`;

CREATE TABLE `ecm_goods_spec` (
  `spec_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `spec_1` varchar(60) NOT NULL DEFAULT '',
  `spec_2` varchar(60) NOT NULL DEFAULT '',
  `color_rgb` varchar(7) NOT NULL DEFAULT '',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `stock` int(11) NOT NULL DEFAULT '0',
  `sku` varchar(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`spec_id`),
  KEY `goods_id` (`goods_id`) USING BTREE,
  KEY `price` (`price`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=93 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_goods_statistics` */

DROP TABLE IF EXISTS `ecm_goods_statistics`;

CREATE TABLE `ecm_goods_statistics` (
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `views` int(10) unsigned NOT NULL DEFAULT '0',
  `collects` int(10) unsigned NOT NULL DEFAULT '0',
  `carts` int(10) unsigned NOT NULL DEFAULT '0',
  `orders` int(10) unsigned NOT NULL DEFAULT '0',
  `sales` int(10) unsigned NOT NULL DEFAULT '0',
  `comments` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`goods_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_groupbuy` */

DROP TABLE IF EXISTS `ecm_groupbuy`;

CREATE TABLE `ecm_groupbuy` (
  `group_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(255) NOT NULL DEFAULT '',
  `group_image` varchar(255) NOT NULL,
  `group_desc` varchar(255) NOT NULL DEFAULT '',
  `start_time` int(10) unsigned NOT NULL DEFAULT '0',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `spec_price` text NOT NULL,
  `min_quantity` smallint(5) unsigned NOT NULL DEFAULT '0',
  `max_per_user` smallint(5) unsigned NOT NULL DEFAULT '0',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `recommended` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `views` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`group_id`),
  KEY `goods_id` (`goods_id`) USING BTREE,
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_groupbuy_log` */

DROP TABLE IF EXISTS `ecm_groupbuy_log`;

CREATE TABLE `ecm_groupbuy_log` (
  `group_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_name` varchar(60) NOT NULL DEFAULT '',
  `quantity` smallint(5) unsigned NOT NULL DEFAULT '0',
  `spec_quantity` text NOT NULL,
  `linkman` varchar(60) NOT NULL DEFAULT '',
  `tel` varchar(60) NOT NULL DEFAULT '',
  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`group_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_integral` */

DROP TABLE IF EXISTS `ecm_integral`;

CREATE TABLE `ecm_integral` (
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_integral_log` */

DROP TABLE IF EXISTS `ecm_integral_log`;

CREATE TABLE `ecm_integral_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `order_id` int(10) NOT NULL DEFAULT '0',
  `order_sn` varchar(20) NOT NULL,
  `changes` decimal(25,2) NOT NULL,
  `balance` decimal(25,2) NOT NULL,
  `type` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `flag` varchar(255) NOT NULL,
  `add_time` int(11) NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_mail_queue` */

DROP TABLE IF EXISTS `ecm_mail_queue`;

CREATE TABLE `ecm_mail_queue` (
  `queue_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mail_to` varchar(150) NOT NULL DEFAULT '',
  `mail_encoding` varchar(50) NOT NULL DEFAULT '',
  `mail_subject` varchar(255) NOT NULL DEFAULT '',
  `mail_body` text NOT NULL,
  `priority` tinyint(1) unsigned NOT NULL DEFAULT '2',
  `err_num` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `add_time` int(11) NOT NULL DEFAULT '0',
  `lock_expiry` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`queue_id`)
) ENGINE=MyISAM AUTO_INCREMENT=219 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_member` */

DROP TABLE IF EXISTS `ecm_member`;

CREATE TABLE `ecm_member` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(60) NOT NULL DEFAULT '',
  `email` varchar(60) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `real_name` varchar(60) DEFAULT NULL,
  `gender` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `birthday` date DEFAULT NULL,
  `phone_tel` varchar(60) DEFAULT NULL,
  `phone_mob` varchar(60) DEFAULT NULL,
  `im_qq` varchar(60) DEFAULT NULL,
  `im_msn` varchar(60) DEFAULT NULL,
  `im_skype` varchar(60) DEFAULT NULL,
  `im_yahoo` varchar(60) DEFAULT NULL,
  `im_aliww` varchar(60) DEFAULT NULL,
  `reg_time` int(10) unsigned DEFAULT '0',
  `last_login` int(10) unsigned DEFAULT NULL,
  `last_ip` varchar(15) DEFAULT NULL,
  `logins` int(10) unsigned NOT NULL DEFAULT '0',
  `ugrade` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `portrait` varchar(255) DEFAULT NULL,
  `outer_id` int(10) unsigned NOT NULL DEFAULT '0',
  `activation` varchar(60) DEFAULT NULL,
  `feed_config` text NOT NULL,
  `valid_code` varchar(40) NOT NULL,
  `valid_status` tinyint(4) NOT NULL DEFAULT '0',
  `expire_time` int(11) NOT NULL,
  `parent_id` bigint(20) unsigned NOT NULL COMMENT '直接上级用户',
  `parent_path` varchar(2000) NOT NULL DEFAULT ',0' COMMENT '所有上级用户',
  `locked` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  KEY `user_name` (`user_name`) USING BTREE,
  KEY `email` (`email`) USING BTREE,
  KEY `outer_id` (`outer_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_member_ext` */

DROP TABLE IF EXISTS `ecm_member_ext`;

CREATE TABLE `ecm_member_ext` (
  `user_id` int(11) unsigned NOT NULL,
  `grade_id` int(11) unsigned DEFAULT NULL,
  `integral` int(10) unsigned DEFAULT NULL,
  `total_integral` int(10) unsigned DEFAULT NULL,
  `total_buy` decimal(20,4) unsigned DEFAULT NULL,
  `update_time` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_member_grade` */

DROP TABLE IF EXISTS `ecm_member_grade`;

CREATE TABLE `ecm_member_grade` (
  `grade_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `grade_name` char(32) DEFAULT NULL,
  `priority` int(10) unsigned DEFAULT NULL,
  `upgrade_buy` decimal(20,4) unsigned DEFAULT NULL,
  `upgrade_integral` int(10) unsigned DEFAULT NULL,
  `buy_tc` decimal(10,4) unsigned DEFAULT NULL,
  `sell_tc` decimal(10,4) unsigned DEFAULT NULL,
  `discount` decimal(10,4) unsigned DEFAULT NULL,
  PRIMARY KEY (`grade_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_message` */

DROP TABLE IF EXISTS `ecm_message`;

CREATE TABLE `ecm_message` (
  `msg_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from_id` int(10) unsigned NOT NULL DEFAULT '0',
  `to_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `last_update` int(10) unsigned NOT NULL DEFAULT '0',
  `new` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`msg_id`),
  KEY `from_id` (`from_id`) USING BTREE,
  KEY `to_id` (`to_id`) USING BTREE,
  KEY `parent_id` (`parent_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_module` */

DROP TABLE IF EXISTS `ecm_module`;

CREATE TABLE `ecm_module` (
  `module_id` varchar(30) NOT NULL DEFAULT '',
  `module_name` varchar(100) NOT NULL DEFAULT '',
  `module_version` varchar(5) NOT NULL DEFAULT '',
  `module_desc` text NOT NULL,
  `module_config` text NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`module_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_money` */

DROP TABLE IF EXISTS `ecm_money`;

CREATE TABLE `ecm_money` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `password` char(32) NOT NULL,
  `money` decimal(20,2) unsigned NOT NULL DEFAULT '0.00',
  `money_dj` decimal(20,2) unsigned NOT NULL DEFAULT '0.00',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `add_time` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_money_log` */

DROP TABLE IF EXISTS `ecm_money_log`;

CREATE TABLE `ecm_money_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `party_id` bigint(20) unsigned NOT NULL,
  `money` decimal(20,2) unsigned NOT NULL DEFAULT '0.00',
  `flow` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `remark` varchar(255) NOT NULL,
  `order_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `bank_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `pay_id` int(10) unsigned NOT NULL DEFAULT '0',
  `add_time` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=267 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_msg` */

DROP TABLE IF EXISTS `ecm_msg`;

CREATE TABLE `ecm_msg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_name` varchar(100) DEFAULT NULL,
  `mobile` varchar(100) DEFAULT NULL,
  `num` int(10) unsigned NOT NULL DEFAULT '0',
  `functions` varchar(255) DEFAULT NULL,
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_msglog` */

DROP TABLE IF EXISTS `ecm_msglog`;

CREATE TABLE `ecm_msglog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_name` varchar(100) DEFAULT NULL,
  `to_mobile` varchar(100) DEFAULT NULL,
  `content` text,
  `state` varchar(100) DEFAULT NULL,
  `type` int(10) unsigned DEFAULT '0',
  `time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_navigation` */

DROP TABLE IF EXISTS `ecm_navigation`;

CREATE TABLE `ecm_navigation` (
  `nav_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(10) NOT NULL DEFAULT '',
  `title` varchar(60) NOT NULL DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '255',
  `open_new` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `hot` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nav_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_order` */

DROP TABLE IF EXISTS `ecm_order`;

CREATE TABLE `ecm_order` (
  `order_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_sn` varchar(20) NOT NULL DEFAULT '',
  `type` varchar(10) NOT NULL DEFAULT 'material',
  `extension` varchar(10) NOT NULL DEFAULT '',
  `seller_id` int(10) unsigned NOT NULL DEFAULT '0',
  `seller_name` varchar(100) DEFAULT NULL,
  `buyer_id` int(10) unsigned NOT NULL DEFAULT '0',
  `buyer_name` varchar(100) DEFAULT NULL,
  `buyer_email` varchar(60) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `payment_id` int(10) unsigned DEFAULT NULL,
  `payment_name` varchar(100) DEFAULT NULL,
  `payment_code` varchar(20) NOT NULL DEFAULT '',
  `out_trade_sn` varchar(20) NOT NULL DEFAULT '',
  `pay_time` int(10) unsigned DEFAULT NULL,
  `pay_message` varchar(255) NOT NULL DEFAULT '',
  `ship_time` int(10) unsigned DEFAULT NULL,
  `invoice_no` varchar(255) DEFAULT NULL,
  `express_company` varchar(50) NOT NULL,
  `finished_time` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `discount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `pay_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `order_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `evaluation_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `evaluation_time` int(10) unsigned NOT NULL DEFAULT '0',
  `anonymous` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `postscript` varchar(255) NOT NULL DEFAULT '',
  `pay_alter` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `trans_id` int(11) DEFAULT '0',
  `order_merge` smallint(5) DEFAULT NULL,
  `order_sns` varchar(266) DEFAULT NULL,
  `flag` int(1) NOT NULL,
  `memo` varchar(255) NOT NULL,
  PRIMARY KEY (`order_id`),
  KEY `order_sn` (`order_sn`,`seller_id`) USING BTREE,
  KEY `seller_name` (`seller_name`) USING BTREE,
  KEY `buyer_name` (`buyer_name`) USING BTREE,
  KEY `add_time` (`add_time`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_order_extm` */

DROP TABLE IF EXISTS `ecm_order_extm`;

CREATE TABLE `ecm_order_extm` (
  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
  `consignee` varchar(60) NOT NULL DEFAULT '',
  `region_id` int(10) unsigned DEFAULT NULL,
  `region_name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `zipcode` varchar(20) DEFAULT NULL,
  `phone_tel` varchar(60) DEFAULT NULL,
  `phone_mob` varchar(60) DEFAULT NULL,
  `shipping_id` int(10) unsigned DEFAULT NULL,
  `shipping_name` varchar(100) DEFAULT NULL,
  `shipping_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`order_id`),
  KEY `consignee` (`consignee`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_order_goods` */

DROP TABLE IF EXISTS `ecm_order_goods`;

CREATE TABLE `ecm_order_goods` (
  `rec_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_name` varchar(255) NOT NULL DEFAULT '',
  `spec_id` int(10) unsigned NOT NULL DEFAULT '0',
  `specification` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `quantity` int(10) unsigned NOT NULL DEFAULT '1',
  `goods_image` varchar(255) DEFAULT NULL,
  `evaluation` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `comment` varchar(255) NOT NULL DEFAULT '',
  `credit_value` tinyint(1) NOT NULL DEFAULT '0',
  `is_valid` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `reply_content` text NOT NULL,
  `reply_time` int(10) NOT NULL,
  `shipped_evaluation` decimal(4,2) NOT NULL,
  `service_evaluation` decimal(4,2) NOT NULL,
  `goods_evaluation` decimal(4,2) NOT NULL,
  `status` varchar(50) NOT NULL,
  PRIMARY KEY (`rec_id`),
  KEY `order_id` (`order_id`,`goods_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_order_integral` */

DROP TABLE IF EXISTS `ecm_order_integral`;

CREATE TABLE `ecm_order_integral` (
  `order_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `frozen_integral` decimal(10,2) NOT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_order_log` */

DROP TABLE IF EXISTS `ecm_order_log`;

CREATE TABLE `ecm_order_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
  `operator` varchar(60) NOT NULL DEFAULT '',
  `order_status` varchar(60) NOT NULL DEFAULT '',
  `changed_status` varchar(60) NOT NULL DEFAULT '',
  `remark` varchar(255) DEFAULT NULL,
  `log_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`log_id`),
  KEY `order_id` (`order_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_pageview` */

DROP TABLE IF EXISTS `ecm_pageview`;

CREATE TABLE `ecm_pageview` (
  `rec_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `view_date` date NOT NULL DEFAULT '0000-00-00',
  `view_times` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`rec_id`),
  UNIQUE KEY `storedate` (`store_id`,`view_date`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_partner` */

DROP TABLE IF EXISTS `ecm_partner`;

CREATE TABLE `ecm_partner` (
  `partner_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `logo` varchar(255) DEFAULT NULL,
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '255',
  PRIMARY KEY (`partner_id`),
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_payment` */

DROP TABLE IF EXISTS `ecm_payment`;

CREATE TABLE `ecm_payment` (
  `payment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `payment_code` varchar(20) NOT NULL DEFAULT '',
  `payment_name` varchar(100) NOT NULL DEFAULT '',
  `payment_desc` varchar(255) DEFAULT NULL,
  `config` text,
  `is_online` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `enabled` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '255',
  `cod_regions` text NOT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `store_id` (`store_id`) USING BTREE,
  KEY `payment_code` (`payment_code`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_point_goods` */

DROP TABLE IF EXISTS `ecm_point_goods`;

CREATE TABLE `ecm_point_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_name` char(200) DEFAULT NULL,
  `goods_desc` char(250) DEFAULT NULL,
  `goods_content` text,
  `need_point` int(11) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `enabled` tinyint(4) DEFAULT NULL,
  `default_image` varchar(500) DEFAULT NULL,
  `used_stock` int(11) DEFAULT NULL,
  `goods_price` decimal(10,2) DEFAULT NULL,
  `max_num` int(11) DEFAULT NULL,
  `point_type` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_point_goods_log` */

DROP TABLE IF EXISTS `ecm_point_goods_log`;

CREATE TABLE `ecm_point_goods_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) DEFAULT NULL,
  `goods_name` char(250) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `status` char(15) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_name` char(100) DEFAULT NULL,
  `goods_num` int(11) DEFAULT NULL,
  `total_point` int(11) DEFAULT NULL,
  `log_sn` char(100) DEFAULT NULL,
  `valid_code` char(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_point_logs` */

DROP TABLE IF EXISTS `ecm_point_logs`;

CREATE TABLE `ecm_point_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(50) DEFAULT NULL,
  `point` int(11) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `remark` varchar(100) DEFAULT NULL,
  `type` char(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_point_set` */

DROP TABLE IF EXISTS `ecm_point_set`;

CREATE TABLE `ecm_point_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `config` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_privilege` */

DROP TABLE IF EXISTS `ecm_privilege`;

CREATE TABLE `ecm_privilege` (
  `priv_code` varchar(20) NOT NULL DEFAULT '',
  `priv_name` varchar(60) NOT NULL DEFAULT '',
  `parent_code` varchar(20) DEFAULT NULL,
  `owner` varchar(10) NOT NULL DEFAULT 'mall',
  PRIMARY KEY (`priv_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_prize` */

DROP TABLE IF EXISTS `ecm_prize`;

CREATE TABLE `ecm_prize` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prize_name` char(50) DEFAULT NULL,
  `prize_price` decimal(10,2) DEFAULT NULL,
  `prize_desc` char(250) DEFAULT NULL,
  `prize_enabled` tinyint(4) DEFAULT NULL,
  `prize_num` int(11) DEFAULT NULL,
  `prize_tags` char(250) DEFAULT NULL,
  `prize_priority` int(11) DEFAULT NULL,
  `required` float DEFAULT NULL,
  `default_image` char(250) DEFAULT NULL,
  `chance` int(11) DEFAULT NULL,
  `add_time` int(11) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `wheel_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_prize_log` */

DROP TABLE IF EXISTS `ecm_prize_log`;

CREATE TABLE `ecm_prize_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prize_id` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `add_time` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `is_win` tinyint(4) DEFAULT NULL,
  `end_time` int(11) DEFAULT NULL,
  `remark` char(250) DEFAULT NULL,
  `wheel_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_promotion` */

DROP TABLE IF EXISTS `ecm_promotion`;

CREATE TABLE `ecm_promotion` (
  `pro_id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL,
  `pro_name` varchar(50) NOT NULL,
  `pro_desc` varchar(255) NOT NULL,
  `start_time` int(11) NOT NULL,
  `end_time` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `spec_price` text NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`pro_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_rcategory` */

DROP TABLE IF EXISTS `ecm_rcategory`;

CREATE TABLE `ecm_rcategory` (
  `cate_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `cate_name` varchar(255) NOT NULL,
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '255',
  `if_show` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `cate_desc` varchar(255) NOT NULL,
  `text` varchar(255) DEFAULT NULL,
  `key_words` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`cate_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_recommend` */

DROP TABLE IF EXISTS `ecm_recommend`;

CREATE TABLE `ecm_recommend` (
  `recom_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `recom_name` varchar(100) NOT NULL DEFAULT '',
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`recom_id`),
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_recommendation` */

DROP TABLE IF EXISTS `ecm_recommendation`;

CREATE TABLE `ecm_recommendation` (
  `id` smallint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `key_words` varchar(255) DEFAULT NULL,
  `text` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `cate_id` smallint(4) NOT NULL,
  `o_price` int(10) unsigned DEFAULT NULL,
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '255',
  `if_show` tinyint(3) unsigned DEFAULT '1',
  `r_type` varchar(255) NOT NULL,
  `n_price` int(10) unsigned DEFAULT NULL,
  `store_name` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `gcategory_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=123 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_recommended_goods` */

DROP TABLE IF EXISTS `ecm_recommended_goods`;

CREATE TABLE `ecm_recommended_goods` (
  `recom_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '255',
  PRIMARY KEY (`recom_id`,`goods_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_refund` */

DROP TABLE IF EXISTS `ecm_refund`;

CREATE TABLE `ecm_refund` (
  `refund_id` int(11) NOT NULL AUTO_INCREMENT,
  `refund_sn` varchar(50) NOT NULL,
  `order_id` int(10) NOT NULL,
  `refund_reason` varchar(50) NOT NULL,
  `refund_desc` varchar(255) NOT NULL,
  `total_fee` decimal(10,2) NOT NULL,
  `goods_fee` decimal(10,2) NOT NULL,
  `shipping_fee` decimal(10,2) NOT NULL,
  `refund_goods_fee` decimal(10,2) NOT NULL,
  `refund_shipping_fee` decimal(10,2) NOT NULL,
  `buyer_id` int(10) NOT NULL,
  `seller_id` int(10) NOT NULL,
  `status` varchar(100) NOT NULL DEFAULT '',
  `shipped` int(11) NOT NULL,
  `ask_customer` int(1) NOT NULL DEFAULT '0',
  `created` int(11) NOT NULL,
  `end_time` int(11) NOT NULL,
  PRIMARY KEY (`refund_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_refund_message` */

DROP TABLE IF EXISTS `ecm_refund_message`;

CREATE TABLE `ecm_refund_message` (
  `rm_id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) NOT NULL,
  `owner_role` varchar(10) NOT NULL,
  `refund_id` int(11) NOT NULL,
  `content` varchar(255) DEFAULT NULL,
  `pic_url` varchar(255) DEFAULT NULL,
  `created` int(11) NOT NULL,
  PRIMARY KEY (`rm_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_region` */

DROP TABLE IF EXISTS `ecm_region`;

CREATE TABLE `ecm_region` (
  `region_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `region_name` varchar(100) NOT NULL DEFAULT '',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '255',
  PRIMARY KEY (`region_id`),
  KEY `parent_id` (`parent_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=477 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_scategory` */

DROP TABLE IF EXISTS `ecm_scategory`;

CREATE TABLE `ecm_scategory` (
  `cate_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cate_name` varchar(100) NOT NULL DEFAULT '',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '255',
  PRIMARY KEY (`cate_id`),
  KEY `parent_id` (`parent_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=67 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_sessions` */

DROP TABLE IF EXISTS `ecm_sessions`;

CREATE TABLE `ecm_sessions` (
  `sesskey` char(32) NOT NULL DEFAULT '',
  `expiry` int(11) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL DEFAULT '0',
  `adminid` int(11) NOT NULL DEFAULT '0',
  `ip` char(15) NOT NULL DEFAULT '',
  `data` char(255) NOT NULL DEFAULT '',
  `is_overflow` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sesskey`),
  KEY `expiry` (`expiry`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_sessions_data` */

DROP TABLE IF EXISTS `ecm_sessions_data`;

CREATE TABLE `ecm_sessions_data` (
  `sesskey` varchar(32) NOT NULL DEFAULT '',
  `expiry` int(11) NOT NULL DEFAULT '0',
  `data` longtext NOT NULL,
  PRIMARY KEY (`sesskey`),
  KEY `expiry` (`expiry`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_sgrade` */

DROP TABLE IF EXISTS `ecm_sgrade`;

CREATE TABLE `ecm_sgrade` (
  `grade_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `grade_name` varchar(60) NOT NULL DEFAULT '',
  `goods_limit` int(10) unsigned NOT NULL DEFAULT '0',
  `space_limit` int(10) unsigned NOT NULL DEFAULT '0',
  `skin_limit` int(10) unsigned NOT NULL DEFAULT '0',
  `wap_skin_limit` int(3) NOT NULL,
  `charge` varchar(100) NOT NULL DEFAULT '',
  `need_confirm` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `description` varchar(255) NOT NULL DEFAULT '',
  `functions` varchar(255) DEFAULT NULL,
  `skins` text NOT NULL,
  `wap_skins` varchar(255) NOT NULL,
  `sort_order` tinyint(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`grade_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_shipping` */

DROP TABLE IF EXISTS `ecm_shipping`;

CREATE TABLE `ecm_shipping` (
  `shipping_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `shipping_name` varchar(100) NOT NULL DEFAULT '',
  `shipping_desc` varchar(255) DEFAULT NULL,
  `first_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `step_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cod_regions` text,
  `enabled` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '255',
  PRIMARY KEY (`shipping_id`),
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_store` */

DROP TABLE IF EXISTS `ecm_store`;

CREATE TABLE `ecm_store` (
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `store_name` varchar(100) NOT NULL DEFAULT '',
  `owner_name` varchar(60) NOT NULL DEFAULT '',
  `owner_card` varchar(60) NOT NULL DEFAULT '',
  `region_id` int(10) unsigned DEFAULT NULL,
  `region_name` varchar(100) DEFAULT NULL,
  `address` varchar(255) NOT NULL DEFAULT '',
  `zipcode` varchar(20) NOT NULL DEFAULT '',
  `tel` varchar(60) NOT NULL DEFAULT '',
  `sgrade` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `apply_remark` varchar(255) NOT NULL DEFAULT '',
  `credit_value` int(10) NOT NULL DEFAULT '0',
  `praise_rate` decimal(5,2) unsigned NOT NULL DEFAULT '0.00',
  `domain` varchar(60) DEFAULT NULL,
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `close_reason` varchar(255) NOT NULL DEFAULT '',
  `add_time` int(10) unsigned DEFAULT NULL,
  `end_time` int(10) unsigned NOT NULL DEFAULT '0',
  `certification` varchar(255) DEFAULT NULL,
  `sort_order` smallint(5) unsigned NOT NULL DEFAULT '0',
  `recommended` tinyint(4) NOT NULL DEFAULT '0',
  `theme` varchar(60) NOT NULL DEFAULT '',
  `wap_theme` varchar(255) NOT NULL,
  `store_banner` varchar(255) DEFAULT NULL,
  `store_logo` varchar(255) DEFAULT NULL,
  `description` text,
  `image_1` varchar(255) NOT NULL DEFAULT '',
  `image_2` varchar(255) NOT NULL DEFAULT '',
  `image_3` varchar(255) NOT NULL DEFAULT '',
  `im_qq` varchar(60) NOT NULL DEFAULT '',
  `im_ww` varchar(60) NOT NULL DEFAULT '',
  `im_msn` varchar(60) NOT NULL DEFAULT '',
  `hot_search` varchar(255) NOT NULL,
  `business_scope` varchar(50) NOT NULL,
  `online_service` varchar(255) NOT NULL,
  `hotline` varchar(255) NOT NULL,
  `pic_slides` text NOT NULL,
  `enable_groupbuy` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `enable_radar` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `is_open_pay` tinyint(3) NOT NULL DEFAULT '0',
  `avg_goods_evaluation` decimal(8,2) NOT NULL,
  `avg_service_evaluation` decimal(8,2) NOT NULL,
  `avg_shipped_evaluation` decimal(8,2) NOT NULL,
  `latlng` varchar(100) NOT NULL,
  PRIMARY KEY (`store_id`),
  KEY `store_name` (`store_name`) USING BTREE,
  KEY `owner_name` (`owner_name`) USING BTREE,
  KEY `region_id` (`region_id`) USING BTREE,
  KEY `domain` (`domain`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_trans` */

DROP TABLE IF EXISTS `ecm_trans`;

CREATE TABLE `ecm_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` char(100) DEFAULT NULL,
  `apply_money` decimal(10,2) DEFAULT NULL,
  `trans_money` decimal(10,2) DEFAULT NULL,
  `apply_num` int(11) DEFAULT NULL,
  `enabled` tinyint(4) DEFAULT NULL,
  `apply_type` tinyint(4) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `region_id` int(11) DEFAULT NULL,
  `region_name` char(100) DEFAULT NULL,
  `add_time` int(11) DEFAULT NULL,
  `rules` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_ultimate_store` */

DROP TABLE IF EXISTS `ecm_ultimate_store`;

CREATE TABLE `ecm_ultimate_store` (
  `ultimate_id` int(255) NOT NULL AUTO_INCREMENT,
  `brand_id` int(50) NOT NULL,
  `keyword` varchar(20) NOT NULL,
  `cate_id` int(50) NOT NULL,
  `store_id` int(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ultimate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_uploaded_file` */

DROP TABLE IF EXISTS `ecm_uploaded_file`;

CREATE TABLE `ecm_uploaded_file` (
  `file_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `file_type` varchar(60) NOT NULL DEFAULT '',
  `file_size` int(10) unsigned NOT NULL DEFAULT '0',
  `file_name` varchar(255) NOT NULL DEFAULT '',
  `file_path` varchar(255) NOT NULL DEFAULT '',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `belong` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `item_id` int(10) unsigned NOT NULL DEFAULT '0',
  `link_url` varchar(100) NOT NULL,
  PRIMARY KEY (`file_id`),
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=112 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_user_coupon` */

DROP TABLE IF EXISTS `ecm_user_coupon`;

CREATE TABLE `ecm_user_coupon` (
  `user_id` int(10) unsigned NOT NULL,
  `coupon_sn` varchar(20) NOT NULL,
  PRIMARY KEY (`user_id`,`coupon_sn`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_user_grade` */

DROP TABLE IF EXISTS `ecm_user_grade`;

CREATE TABLE `ecm_user_grade` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `grade_name` char(32) NOT NULL,
  `priority` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '等级优先级',
  `upgrade` varchar(1000) NOT NULL COMMENT '升级配置',
  `other` varchar(1000) NOT NULL COMMENT '其他相关项，如等级提成配置',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_user_priv` */

DROP TABLE IF EXISTS `ecm_user_priv`;

CREATE TABLE `ecm_user_priv` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `privs` text NOT NULL,
  PRIMARY KEY (`user_id`,`store_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_wap_index` */

DROP TABLE IF EXISTS `ecm_wap_index`;

CREATE TABLE `ecm_wap_index` (
  `id` smallint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `cate_id` smallint(4) NOT NULL,
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '255',
  `if_show` tinyint(3) unsigned DEFAULT '1',
  `add_time` int(12) DEFAULT NULL,
  `recom_id` int(10) DEFAULT NULL,
  `gcategory_id` int(10) DEFAULT NULL,
  `num` tinyint(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_wheel` */

DROP TABLE IF EXISTS `ecm_wheel`;

CREATE TABLE `ecm_wheel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` char(50) DEFAULT NULL,
  `tags` char(250) DEFAULT NULL,
  `description` char(250) DEFAULT NULL,
  `content` text,
  `add_time` int(11) DEFAULT NULL,
  `point` int(11) DEFAULT NULL,
  `start_time` int(11) DEFAULT NULL,
  `end_time` int(11) DEFAULT NULL,
  `enabled` tinyint(4) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `max_num` int(11) DEFAULT NULL,
  `everper` int(11) DEFAULT NULL,
  `wheel_type` tinyint(4) DEFAULT NULL,
  `default_image` char(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_wxconfig` */

DROP TABLE IF EXISTS `ecm_wxconfig`;

CREATE TABLE `ecm_wxconfig` (
  `w_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `appid` varchar(255) DEFAULT NULL,
  `appsecret` varchar(255) DEFAULT NULL,
  `access_token` varchar(512) DEFAULT NULL,
  `access_expire` int(11) DEFAULT NULL,
  `refresh_token` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`w_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_wxfile` */

DROP TABLE IF EXISTS `ecm_wxfile`;

CREATE TABLE `ecm_wxfile` (
  `file_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `file_type` varchar(60) NOT NULL,
  `file_size` int(10) NOT NULL DEFAULT '0',
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  PRIMARY KEY (`file_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_wxkeyword` */

DROP TABLE IF EXISTS `ecm_wxkeyword`;

CREATE TABLE `ecm_wxkeyword` (
  `kid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `kename` varchar(300) DEFAULT NULL,
  `kecontent` varchar(500) DEFAULT NULL,
  `type` tinyint(1) NOT NULL COMMENT '1:文本 2：图文',
  `kyword` varchar(255) DEFAULT NULL,
  `titles` varchar(1000) DEFAULT NULL,
  `imageinfo` varchar(1000) DEFAULT NULL,
  `linkinfo` varchar(1000) DEFAULT NULL,
  `ismess` tinyint(1) DEFAULT NULL,
  `isfollow` tinyint(1) DEFAULT NULL,
  `iskey` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`kid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `ecm_wxmenu` */

DROP TABLE IF EXISTS `ecm_wxmenu`;

CREATE TABLE `ecm_wxmenu` (
  `id` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL,
  `tags` varchar(50) DEFAULT NULL,
  `pid` smallint(4) unsigned NOT NULL DEFAULT '0',
  `spid` varchar(50) DEFAULT NULL,
  `add_time` int(10) NOT NULL DEFAULT '0',
  `items` int(10) unsigned NOT NULL DEFAULT '0',
  `likes` varchar(100) DEFAULT NULL,
  `weixin_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0:click 1:viwe',
  `ordid` tinyint(3) unsigned NOT NULL DEFAULT '255',
  `weixin_status` tinyint(1) NOT NULL DEFAULT '0',
  `weixin_keyword` varchar(255) DEFAULT NULL COMMENT '关键词',
  `weixin_key` varchar(255) DEFAULT NULL COMMENT 'key值',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

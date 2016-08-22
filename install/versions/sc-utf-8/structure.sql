SET FOREIGN_KEY_CHECKS=0;

CREATE TABLE `ecm_acategory` (
`cate_id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`cate_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`parent_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`sort_order`  tinyint(3) UNSIGNED NOT NULL DEFAULT 255 ,
`code`  varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`cate_id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_address` (
`addr_id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`user_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`consignee`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`region_id`  int(10) UNSIGNED NULL DEFAULT NULL ,
`region_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`address`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`zipcode`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`phone_tel`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`phone_mob`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`set_default`  int(5) NULL DEFAULT 0 ,
`setdefault`  tinyint(3) NOT NULL DEFAULT 0 ,
PRIMARY KEY (`addr_id`),
INDEX `user_id` (`user_id`) USING BTREE 
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_app` (
`app_name`  char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`id`  int(11) NULL DEFAULT NULL ,
`app_tags`  char(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`app_desc`  char(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`app_content`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`default_image`  char(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`install_num`  int(11) NULL DEFAULT NULL ,
`view_num`  int(11) NULL DEFAULT NULL ,
`update_num`  int(11) NULL DEFAULT NULL ,
`app_author`  char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`add_time`  int(11) NULL DEFAULT NULL ,
`update_time`  int(11) NULL DEFAULT NULL ,
`app_type`  tinyint(4) NULL DEFAULT NULL ,
`sort`  int(11) NULL DEFAULT NULL ,
`curr_version`  char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`old_version`  char(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL 
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_article` (
`article_id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`code`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`title`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`cate_id`  int(10) NOT NULL DEFAULT 0 ,
`store_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`link`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`content`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`sort_order`  tinyint(3) UNSIGNED NOT NULL DEFAULT 255 ,
`if_show`  tinyint(3) UNSIGNED NOT NULL DEFAULT 1 ,
`add_time`  int(10) UNSIGNED NULL DEFAULT NULL ,
PRIMARY KEY (`article_id`),
INDEX `code` (`code`) USING BTREE ,
INDEX `cate_id` (`cate_id`) USING BTREE ,
INDEX `store_id` (`store_id`) USING BTREE 
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_attribute` (
`attr_id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`attr_name`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`input_mode`  varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'text' ,
`def_value`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`attr_id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_bank` (
`id`  bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
`user_id`  bigint(20) UNSIGNED NOT NULL ,
`card_number`  char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`cardholder`  char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`bank_name`  char(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`bank_address`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`add_time`  bigint(20) UNSIGNED NOT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_brand` (
`brand_id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`brand_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`brand_logo`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`sort_order`  tinyint(3) UNSIGNED NOT NULL DEFAULT 255 ,
`recommended`  tinyint(3) UNSIGNED NOT NULL DEFAULT 0 ,
`store_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`if_show`  tinyint(2) UNSIGNED NOT NULL DEFAULT 1 ,
`tag`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
PRIMARY KEY (`brand_id`),
INDEX `tag` (`tag`) USING BTREE 
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_cart` (
`rec_id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`user_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`session_id`  varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`store_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`goods_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`goods_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`spec_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`specification`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`price`  decimal(10,2) UNSIGNED NOT NULL DEFAULT 0.00 ,
`quantity`  int(10) UNSIGNED NOT NULL DEFAULT 1 ,
`goods_image`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`rec_id`),
INDEX `session_id` (`session_id`) USING BTREE ,
INDEX `user_id` (`user_id`) USING BTREE 
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_category_goods` (
`cate_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`goods_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
PRIMARY KEY (`cate_id`, `goods_id`),
INDEX `goods_id` (`goods_id`) USING BTREE 
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Fixed
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_category_store` (
`cate_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`store_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
PRIMARY KEY (`cate_id`, `store_id`),
INDEX `store_id` (`store_id`) USING BTREE 
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Fixed
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_cate_pvs` (
`cate_id`  int(11) NOT NULL ,
`pvs`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL 
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_collect` (
`user_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`type`  varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'goods' ,
`item_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`keyword`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`add_time`  int(10) UNSIGNED NULL DEFAULT NULL ,
PRIMARY KEY (`user_id`, `type`, `item_id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_coupon` (
`coupon_id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`store_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`coupon_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`coupon_value`  decimal(10,2) UNSIGNED NOT NULL DEFAULT 0.00 ,
`use_times`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`start_time`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`end_time`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`min_amount`  decimal(10,2) UNSIGNED NOT NULL DEFAULT 0.00 ,
`if_issue`  tinyint(3) UNSIGNED NOT NULL DEFAULT 0 ,
PRIMARY KEY (`coupon_id`),
INDEX `store_id` (`store_id`) USING BTREE 
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_coupon_sn` (
`coupon_sn`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`coupon_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`remain_times`  int(10) NOT NULL DEFAULT '-1' ,
PRIMARY KEY (`coupon_sn`),
INDEX `coupon_id` (`coupon_id`) USING BTREE 
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_delivery_template` (
`template_id`  int(11) NOT NULL AUTO_INCREMENT ,
`name`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`store_id`  int(10) NOT NULL ,
`template_types`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`template_dests`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`template_start_standards`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`template_start_fees`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`template_add_standards`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`template_add_fees`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`created`  int(10) NOT NULL ,
PRIMARY KEY (`template_id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_discus` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`order_id`  int(11) NULL DEFAULT NULL ,
`sort`  int(11) NULL DEFAULT NULL ,
`buyer_type`  char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`buyer_remark`  varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`buyer_addtime`  int(11) NULL DEFAULT NULL ,
`seller_type`  char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`seller_remark`  varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`seller_addtime`  int(11) NULL DEFAULT NULL ,
`admin_remark`  varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`admin_addtime`  int(11) NULL DEFAULT NULL ,
`status`  int(11) NULL DEFAULT NULL ,
`buyer_id`  int(11) NULL DEFAULT NULL ,
`buyer_name`  char(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`seller_id`  int(11) NULL DEFAULT NULL ,
`seller_name`  char(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`is_pay`  tinyint(4) NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_friend` (
`owner_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`friend_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`add_time`  varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
PRIMARY KEY (`owner_id`, `friend_id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_function` (
`func_code`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`func_name`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`privileges`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
PRIMARY KEY (`func_code`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_gcategory` (
`cate_id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`store_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`cate_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`parent_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`sort_order`  tinyint(3) UNSIGNED NOT NULL DEFAULT 255 ,
`if_show`  tinyint(3) UNSIGNED NOT NULL DEFAULT 1 ,
`logo`  char(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`cate_id`),
INDEX `store_id` (`store_id`, `parent_id`) USING BTREE 
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_goods` (
`goods_id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`store_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`type`  varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'material' ,
`goods_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`description`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`cate_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`cate_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`brand`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`spec_qty`  tinyint(4) UNSIGNED NOT NULL DEFAULT 0 ,
`spec_name_1`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`spec_name_2`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`if_show`  tinyint(3) UNSIGNED NOT NULL DEFAULT 1 ,
`closed`  tinyint(3) UNSIGNED NOT NULL DEFAULT 0 ,
`close_reason`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`add_time`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`last_update`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`default_spec`  int(11) UNSIGNED NOT NULL DEFAULT 0 ,
`default_image`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`recommended`  tinyint(4) UNSIGNED NOT NULL DEFAULT 0 ,
`cate_id_1`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`cate_id_2`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`cate_id_3`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`cate_id_4`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`price`  decimal(10,2) NOT NULL DEFAULT 0.00 ,
`tags`  varchar(102) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`market_price`  decimal(10,2) NULL DEFAULT NULL ,
`delivery_template_id`  int(11) NOT NULL ,
PRIMARY KEY (`goods_id`),
INDEX `store_id` (`store_id`) USING BTREE ,
INDEX `cate_id` (`cate_id`) USING BTREE ,
INDEX `cate_id_1` (`cate_id_1`) USING BTREE ,
INDEX `cate_id_2` (`cate_id_2`) USING BTREE ,
INDEX `cate_id_3` (`cate_id_3`) USING BTREE ,
INDEX `cate_id_4` (`cate_id_4`) USING BTREE ,
INDEX `brand` (`brand`(10)) USING BTREE ,
INDEX `tags` (`tags`) USING BTREE 
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_goods_attr` (
`gattr_id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`goods_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`attr_name`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`attr_value`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`attr_id`  int(10) UNSIGNED NULL DEFAULT NULL ,
`sort_order`  tinyint(3) UNSIGNED NULL DEFAULT NULL ,
PRIMARY KEY (`gattr_id`),
INDEX `goods_id` (`goods_id`) USING BTREE 
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_goods_image` (
`image_id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`goods_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`image_url`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`thumbnail`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`sort_order`  tinyint(4) UNSIGNED NOT NULL DEFAULT 0 ,
`file_id`  int(11) UNSIGNED NOT NULL DEFAULT 0 ,
PRIMARY KEY (`image_id`),
INDEX `goods_id` (`goods_id`) USING BTREE 
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_goods_integral` (
`goods_id`  int(11) NOT NULL ,
`max_exchange`  int(11) NOT NULL ,
PRIMARY KEY (`goods_id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Fixed
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_goods_prop` (
`pid`  int(11) NOT NULL AUTO_INCREMENT ,
`name`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`status`  int(1) NOT NULL ,
`sort_order`  int(11) NOT NULL ,
PRIMARY KEY (`pid`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_goods_prop_value` (
`vid`  int(11) NOT NULL AUTO_INCREMENT ,
`pid`  int(11) NOT NULL ,
`prop_value`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`status`  int(1) NOT NULL ,
`sort_order`  int(11) NOT NULL ,
PRIMARY KEY (`vid`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_goods_pvs` (
`goods_id`  int(11) NOT NULL ,
`pvs`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
PRIMARY KEY (`goods_id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_goods_qa` (
`ques_id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`question_content`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`user_id`  int(10) UNSIGNED NOT NULL ,
`store_id`  int(10) UNSIGNED NOT NULL ,
`email`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`item_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`item_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`reply_content`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`time_post`  int(10) UNSIGNED NOT NULL ,
`time_reply`  int(10) UNSIGNED NOT NULL ,
`if_new`  tinyint(3) UNSIGNED NOT NULL DEFAULT 1 ,
`type`  varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'goods' ,
PRIMARY KEY (`ques_id`),
INDEX `user_id` (`user_id`) USING BTREE ,
INDEX `goods_id` (`item_id`) USING BTREE ,
INDEX `store_id` (`store_id`) USING BTREE 
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_goods_spec` (
`spec_id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`goods_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`spec_1`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`spec_2`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`color_rgb`  varchar(7) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`price`  decimal(10,2) NOT NULL DEFAULT 0.00 ,
`stock`  int(11) NOT NULL DEFAULT 0 ,
`sku`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
PRIMARY KEY (`spec_id`),
INDEX `goods_id` (`goods_id`) USING BTREE ,
INDEX `price` (`price`) USING BTREE 
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_goods_statistics` (
`goods_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`views`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`collects`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`carts`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`orders`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`sales`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`comments`  int(11) UNSIGNED NOT NULL DEFAULT 0 ,
PRIMARY KEY (`goods_id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Fixed
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_groupbuy` (
`group_id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`group_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`group_image`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`group_desc`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`start_time`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`end_time`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`goods_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`store_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`spec_price`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`min_quantity`  smallint(5) UNSIGNED NOT NULL DEFAULT 0 ,
`max_per_user`  smallint(5) UNSIGNED NOT NULL DEFAULT 0 ,
`state`  tinyint(3) UNSIGNED NOT NULL DEFAULT 0 ,
`recommended`  tinyint(3) UNSIGNED NOT NULL DEFAULT 0 ,
`views`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
PRIMARY KEY (`group_id`),
INDEX `goods_id` (`goods_id`) USING BTREE ,
INDEX `store_id` (`store_id`) USING BTREE 
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_groupbuy_log` (
`group_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`user_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`user_name`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`quantity`  smallint(5) UNSIGNED NOT NULL DEFAULT 0 ,
`spec_quantity`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`linkman`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`tel`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`order_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`add_time`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
PRIMARY KEY (`group_id`, `user_id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_integral` (
`user_id`  int(11) NOT NULL ,
`amount`  decimal(10,2) NOT NULL ,
PRIMARY KEY (`user_id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Fixed
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_integral_log` (
`log_id`  int(11) NOT NULL AUTO_INCREMENT ,
`user_id`  int(10) NOT NULL ,
`order_id`  int(10) NOT NULL DEFAULT 0 ,
`order_sn`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`changes`  decimal(25,2) NOT NULL ,
`balance`  decimal(25,2) NOT NULL ,
`type`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`state`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`flag`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`add_time`  int(11) NOT NULL ,
PRIMARY KEY (`log_id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_mail_queue` (
`queue_id`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`mail_to`  varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`mail_encoding`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`mail_subject`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`mail_body`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`priority`  tinyint(1) UNSIGNED NOT NULL DEFAULT 2 ,
`err_num`  tinyint(1) UNSIGNED NOT NULL DEFAULT 0 ,
`add_time`  int(11) NOT NULL DEFAULT 0 ,
`lock_expiry`  int(11) NOT NULL DEFAULT 0 ,
PRIMARY KEY (`queue_id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_member` (
`user_id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`user_name`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`email`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`password`  varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`real_name`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`gender`  tinyint(3) UNSIGNED NOT NULL DEFAULT 0 ,
`birthday`  date NULL DEFAULT NULL ,
`phone_tel`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`phone_mob`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`im_qq`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`im_msn`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`im_skype`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`im_yahoo`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`im_aliww`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`reg_time`  int(10) UNSIGNED NULL DEFAULT 0 ,
`last_login`  int(10) UNSIGNED NULL DEFAULT NULL ,
`last_ip`  varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`logins`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`ugrade`  tinyint(3) UNSIGNED NOT NULL DEFAULT 0 ,
`portrait`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`outer_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`activation`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`feed_config`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`valid_code`  varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`valid_status`  tinyint(4) NOT NULL DEFAULT 0 ,
`expire_time`  int(11) NOT NULL ,
`parent_id`  bigint(20) UNSIGNED NOT NULL COMMENT '直接上级用户' ,
`parent_path`  varchar(2000) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ',0' COMMENT '所有上级用户' ,
`locked`  int(1) NOT NULL DEFAULT 0 ,
PRIMARY KEY (`user_id`),
INDEX `user_name` (`user_name`) USING BTREE ,
INDEX `email` (`email`) USING BTREE ,
INDEX `outer_id` (`outer_id`) USING BTREE 
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_member_ext` (
`user_id`  int(11) UNSIGNED NOT NULL ,
`grade_id`  int(11) UNSIGNED NULL DEFAULT NULL ,
`integral`  int(10) UNSIGNED NULL DEFAULT NULL ,
`total_integral`  int(10) UNSIGNED NULL DEFAULT NULL ,
`total_buy`  decimal(20,4) UNSIGNED NULL DEFAULT NULL ,
`update_time`  bigint(20) UNSIGNED NULL DEFAULT NULL ,
PRIMARY KEY (`user_id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Fixed
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_member_grade` (
`grade_id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`grade_name`  char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`priority`  int(10) UNSIGNED NULL DEFAULT NULL ,
`upgrade_buy`  decimal(20,4) UNSIGNED NULL DEFAULT NULL ,
`upgrade_integral`  int(10) UNSIGNED NULL DEFAULT NULL ,
`buy_tc`  decimal(10,4) UNSIGNED NULL DEFAULT NULL ,
`sell_tc`  decimal(10,4) UNSIGNED NULL DEFAULT NULL ,
`discount`  decimal(10,4) UNSIGNED NULL DEFAULT NULL ,
PRIMARY KEY (`grade_id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Fixed
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_message` (
`msg_id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`from_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`to_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`title`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`content`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`add_time`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`last_update`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`new`  tinyint(3) UNSIGNED NOT NULL DEFAULT 0 ,
`parent_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`status`  tinyint(3) UNSIGNED NOT NULL DEFAULT 0 ,
PRIMARY KEY (`msg_id`),
INDEX `from_id` (`from_id`) USING BTREE ,
INDEX `to_id` (`to_id`) USING BTREE ,
INDEX `parent_id` (`parent_id`) USING BTREE 
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_module` (
`module_id`  varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`module_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`module_version`  varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`module_desc`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`module_config`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`enabled`  tinyint(1) NOT NULL DEFAULT 0 ,
PRIMARY KEY (`module_id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_money` (
`id`  bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
`user_id`  bigint(20) UNSIGNED NOT NULL ,
`password`  char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`money`  decimal(20,2) UNSIGNED NOT NULL DEFAULT 0.00 ,
`money_dj`  decimal(20,2) UNSIGNED NOT NULL DEFAULT 0.00 ,
`status`  tinyint(3) UNSIGNED NOT NULL DEFAULT 0 ,
`add_time`  bigint(20) UNSIGNED NOT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Fixed
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_money_log` (
`id`  bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
`user_id`  bigint(20) UNSIGNED NOT NULL ,
`party_id`  bigint(20) UNSIGNED NOT NULL ,
`money`  decimal(20,2) UNSIGNED NOT NULL DEFAULT 0.00 ,
`flow`  tinyint(3) UNSIGNED NOT NULL DEFAULT 0 ,
`status`  tinyint(3) UNSIGNED NOT NULL DEFAULT 0 ,
`type`  tinyint(3) UNSIGNED NOT NULL DEFAULT 0 ,
`remark`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`order_id`  bigint(20) UNSIGNED NOT NULL DEFAULT 0 ,
`bank_id`  bigint(20) UNSIGNED NOT NULL DEFAULT 0 ,
`pay_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`add_time`  bigint(20) UNSIGNED NOT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_msg` (
`id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`user_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`user_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`mobile`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`num`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`functions`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`state`  tinyint(3) UNSIGNED NOT NULL DEFAULT 0 ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_msglog` (
`id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`user_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`user_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`to_mobile`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`content`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`state`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`type`  int(10) UNSIGNED NULL DEFAULT 0 ,
`time`  int(10) UNSIGNED NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_navigation` (
`nav_id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`type`  varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`title`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`link`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`sort_order`  tinyint(3) UNSIGNED NOT NULL DEFAULT 255 ,
`open_new`  tinyint(3) UNSIGNED NOT NULL DEFAULT 0 ,
`hot`  tinyint(3) NOT NULL DEFAULT 0 ,
PRIMARY KEY (`nav_id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_order` (
`order_id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`order_sn`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`type`  varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'material' ,
`extension`  varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`seller_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`seller_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`buyer_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`buyer_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`buyer_email`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`status`  tinyint(3) UNSIGNED NOT NULL DEFAULT 0 ,
`add_time`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`payment_id`  int(10) UNSIGNED NULL DEFAULT NULL ,
`payment_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`payment_code`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`out_trade_sn`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`pay_time`  int(10) UNSIGNED NULL DEFAULT NULL ,
`pay_message`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`ship_time`  int(10) UNSIGNED NULL DEFAULT NULL ,
`invoice_no`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`express_company`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`finished_time`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`goods_amount`  decimal(10,2) UNSIGNED NOT NULL DEFAULT 0.00 ,
`discount`  decimal(10,2) UNSIGNED NOT NULL DEFAULT 0.00 ,
`pay_money`  decimal(10,2) UNSIGNED NOT NULL DEFAULT 0.00 ,
`order_amount`  decimal(10,2) UNSIGNED NOT NULL DEFAULT 0.00 ,
`evaluation_status`  tinyint(1) UNSIGNED NOT NULL DEFAULT 0 ,
`evaluation_time`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`anonymous`  tinyint(3) UNSIGNED NOT NULL DEFAULT 0 ,
`postscript`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`pay_alter`  tinyint(1) UNSIGNED NOT NULL DEFAULT 0 ,
`trans_id`  int(11) NULL DEFAULT 0 ,
`order_merge`  smallint(5) NULL DEFAULT NULL ,
`order_sns`  varchar(266) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`flag`  int(1) NOT NULL ,
`memo`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
PRIMARY KEY (`order_id`),
INDEX `order_sn` (`order_sn`, `seller_id`) USING BTREE ,
INDEX `seller_name` (`seller_name`) USING BTREE ,
INDEX `buyer_name` (`buyer_name`) USING BTREE ,
INDEX `add_time` (`add_time`) USING BTREE 
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_order_extm` (
`order_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`consignee`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`region_id`  int(10) UNSIGNED NULL DEFAULT NULL ,
`region_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`address`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`zipcode`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`phone_tel`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`phone_mob`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`shipping_id`  int(10) UNSIGNED NULL DEFAULT NULL ,
`shipping_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`shipping_fee`  decimal(10,2) NOT NULL DEFAULT 0.00 ,
PRIMARY KEY (`order_id`),
INDEX `consignee` (`consignee`) USING BTREE 
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_order_goods` (
`rec_id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`order_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`goods_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`goods_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`spec_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`specification`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`price`  decimal(10,2) UNSIGNED NOT NULL DEFAULT 0.00 ,
`quantity`  int(10) UNSIGNED NOT NULL DEFAULT 1 ,
`goods_image`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`evaluation`  tinyint(1) UNSIGNED NOT NULL DEFAULT 0 ,
`comment`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`credit_value`  tinyint(1) NOT NULL DEFAULT 0 ,
`is_valid`  tinyint(1) UNSIGNED NOT NULL DEFAULT 1 ,
`reply_content`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`reply_time`  int(10) NOT NULL ,
`shipped_evaluation`  decimal(4,2) NOT NULL ,
`service_evaluation`  decimal(4,2) NOT NULL ,
`goods_evaluation`  decimal(4,2) NOT NULL ,
`status`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
PRIMARY KEY (`rec_id`),
INDEX `order_id` (`order_id`, `goods_id`) USING BTREE 
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_order_integral` (
`order_id`  int(11) NOT NULL ,
`buyer_id`  int(11) NOT NULL ,
`frozen_integral`  decimal(10,2) NOT NULL ,
PRIMARY KEY (`order_id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Fixed
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_order_log` (
`log_id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`order_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`operator`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`order_status`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`changed_status`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`remark`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`log_time`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
PRIMARY KEY (`log_id`),
INDEX `order_id` (`order_id`) USING BTREE 
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_pageview` (
`rec_id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`store_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`view_date`  date NOT NULL DEFAULT '0000-00-00' ,
`view_times`  int(10) UNSIGNED NOT NULL DEFAULT 1 ,
PRIMARY KEY (`rec_id`),
UNIQUE INDEX `storedate` (`store_id`, `view_date`) USING BTREE 
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Fixed
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_partner` (
`partner_id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`store_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`title`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`link`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`logo`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`sort_order`  tinyint(3) UNSIGNED NOT NULL DEFAULT 255 ,
PRIMARY KEY (`partner_id`),
INDEX `store_id` (`store_id`) USING BTREE 
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_payment` (
`payment_id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`store_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`payment_code`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`payment_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`payment_desc`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`config`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`is_online`  tinyint(3) UNSIGNED NOT NULL DEFAULT 1 ,
`enabled`  tinyint(3) UNSIGNED NOT NULL DEFAULT 1 ,
`sort_order`  tinyint(3) UNSIGNED NOT NULL DEFAULT 255 ,
`cod_regions`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
PRIMARY KEY (`payment_id`),
INDEX `store_id` (`store_id`) USING BTREE ,
INDEX `payment_code` (`payment_code`) USING BTREE 
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_point_goods` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`goods_name`  char(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`goods_desc`  char(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`goods_content`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`need_point`  int(11) NULL DEFAULT NULL ,
`stock`  int(11) NULL DEFAULT NULL ,
`addtime`  int(11) NULL DEFAULT NULL ,
`sort`  int(11) NULL DEFAULT NULL ,
`enabled`  tinyint(4) NULL DEFAULT NULL ,
`default_image`  varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`used_stock`  int(11) NULL DEFAULT NULL ,
`goods_price`  decimal(10,2) NULL DEFAULT NULL ,
`max_num`  int(11) NULL DEFAULT NULL ,
`point_type`  tinyint(4) NULL DEFAULT 0 ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_point_goods_log` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`goods_id`  int(11) NULL DEFAULT NULL ,
`goods_name`  char(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`addtime`  int(11) NULL DEFAULT NULL ,
`status`  char(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`user_id`  int(11) NULL DEFAULT NULL ,
`user_name`  char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`goods_num`  int(11) NULL DEFAULT NULL ,
`total_point`  int(11) NULL DEFAULT NULL ,
`log_sn`  char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`valid_code`  char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Fixed
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_point_logs` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`user_id`  int(11) NOT NULL ,
`user_name`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`point`  int(11) NULL DEFAULT NULL ,
`addtime`  int(11) NULL DEFAULT NULL ,
`remark`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`type`  char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_point_set` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`config`  varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_privilege` (
`priv_code`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`priv_name`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`parent_code`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`owner`  varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'mall' ,
PRIMARY KEY (`priv_code`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_prize` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`prize_name`  char(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`prize_price`  decimal(10,2) NULL DEFAULT NULL ,
`prize_desc`  char(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`prize_enabled`  tinyint(4) NULL DEFAULT NULL ,
`prize_num`  int(11) NULL DEFAULT NULL ,
`prize_tags`  char(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`prize_priority`  int(11) NULL DEFAULT NULL ,
`required`  float NULL DEFAULT NULL ,
`default_image`  char(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`chance`  int(11) NULL DEFAULT NULL ,
`add_time`  int(11) NULL DEFAULT NULL ,
`sort`  int(11) NULL DEFAULT NULL ,
`wheel_id`  int(11) NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Fixed
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_prize_log` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`prize_id`  int(11) NULL DEFAULT NULL ,
`status`  tinyint(4) NULL DEFAULT NULL ,
`add_time`  int(11) NULL DEFAULT NULL ,
`user_id`  int(11) NULL DEFAULT NULL ,
`is_win`  tinyint(4) NULL DEFAULT NULL ,
`end_time`  int(11) NULL DEFAULT NULL ,
`remark`  char(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`wheel_id`  int(11) NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Fixed
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_promotion` (
`pro_id`  int(11) NOT NULL AUTO_INCREMENT ,
`goods_id`  int(11) NOT NULL ,
`pro_name`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`pro_desc`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`start_time`  int(11) NOT NULL ,
`end_time`  int(11) NOT NULL ,
`store_id`  int(11) NOT NULL ,
`spec_price`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`image`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
PRIMARY KEY (`pro_id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_rcategory` (
`cate_id`  tinyint(4) NOT NULL AUTO_INCREMENT ,
`cate_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`sort_order`  tinyint(3) UNSIGNED NOT NULL DEFAULT 255 ,
`if_show`  tinyint(3) UNSIGNED NOT NULL DEFAULT 1 ,
`cate_desc`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`text`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`key_words`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`cate_id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_recommend` (
`recom_id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`recom_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`store_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
PRIMARY KEY (`recom_id`),
INDEX `store_id` (`store_id`) USING BTREE 
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_recommendation` (
`id`  smallint(4) NOT NULL AUTO_INCREMENT ,
`name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`key_words`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`text`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`logo`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`url`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`cate_id`  smallint(4) NOT NULL ,
`o_price`  int(10) UNSIGNED NULL DEFAULT NULL ,
`sort_order`  tinyint(3) UNSIGNED NOT NULL DEFAULT 255 ,
`if_show`  tinyint(3) UNSIGNED NULL DEFAULT 1 ,
`r_type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`n_price`  int(10) UNSIGNED NULL DEFAULT NULL ,
`store_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`type`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`title`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`gcategory_id`  int(10) NOT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_recommended_goods` (
`recom_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`goods_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`sort_order`  tinyint(3) UNSIGNED NOT NULL DEFAULT 255 ,
PRIMARY KEY (`recom_id`, `goods_id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Fixed
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_refund` (
`refund_id`  int(11) NOT NULL AUTO_INCREMENT ,
`refund_sn`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`order_id`  int(10) NOT NULL ,
`refund_reason`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`refund_desc`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`total_fee`  decimal(10,2) NOT NULL ,
`goods_fee`  decimal(10,2) NOT NULL ,
`shipping_fee`  decimal(10,2) NOT NULL ,
`refund_goods_fee`  decimal(10,2) NOT NULL ,
`refund_shipping_fee`  decimal(10,2) NOT NULL ,
`buyer_id`  int(10) NOT NULL ,
`seller_id`  int(10) NOT NULL ,
`status`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`shipped`  int(11) NOT NULL ,
`ask_customer`  int(1) NOT NULL DEFAULT 0 ,
`created`  int(11) NOT NULL ,
`end_time`  int(11) NOT NULL ,
PRIMARY KEY (`refund_id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_refund_message` (
`rm_id`  int(11) NOT NULL AUTO_INCREMENT ,
`owner_id`  int(11) NOT NULL ,
`owner_role`  varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`refund_id`  int(11) NOT NULL ,
`content`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`pic_url`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`created`  int(11) NOT NULL ,
PRIMARY KEY (`rm_id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_region` (
`region_id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`region_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`parent_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`sort_order`  tinyint(3) UNSIGNED NOT NULL DEFAULT 255 ,
PRIMARY KEY (`region_id`),
INDEX `parent_id` (`parent_id`) USING BTREE 
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_scategory` (
`cate_id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`cate_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`parent_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`sort_order`  tinyint(3) UNSIGNED NOT NULL DEFAULT 255 ,
PRIMARY KEY (`cate_id`),
INDEX `parent_id` (`parent_id`) USING BTREE 
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_sessions` (
`sesskey`  char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`expiry`  int(11) NOT NULL DEFAULT 0 ,
`userid`  int(11) NOT NULL DEFAULT 0 ,
`adminid`  int(11) NOT NULL DEFAULT 0 ,
`ip`  char(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`data`  char(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`is_overflow`  tinyint(4) NOT NULL DEFAULT 0 ,
PRIMARY KEY (`sesskey`),
INDEX `expiry` (`expiry`) USING BTREE 
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Fixed
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_sessions_data` (
`sesskey`  varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`expiry`  int(11) NOT NULL DEFAULT 0 ,
`data`  longtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
PRIMARY KEY (`sesskey`),
INDEX `expiry` (`expiry`) USING BTREE 
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_sgrade` (
`grade_id`  tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT ,
`grade_name`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`goods_limit`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`space_limit`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`skin_limit`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`wap_skin_limit`  int(3) NOT NULL ,
`charge`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`need_confirm`  tinyint(3) UNSIGNED NOT NULL DEFAULT 0 ,
`description`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`functions`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`skins`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`wap_skins`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`sort_order`  tinyint(4) UNSIGNED NOT NULL DEFAULT 0 ,
PRIMARY KEY (`grade_id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_shipping` (
`shipping_id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`store_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`shipping_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`shipping_desc`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`first_price`  decimal(10,2) NOT NULL DEFAULT 0.00 ,
`step_price`  decimal(10,2) NOT NULL DEFAULT 0.00 ,
`cod_regions`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`enabled`  tinyint(3) UNSIGNED NOT NULL DEFAULT 1 ,
`sort_order`  tinyint(3) UNSIGNED NOT NULL DEFAULT 255 ,
PRIMARY KEY (`shipping_id`),
INDEX `store_id` (`store_id`) USING BTREE 
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_store` (
`store_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`store_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`owner_name`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`owner_card`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`region_id`  int(10) UNSIGNED NULL DEFAULT NULL ,
`region_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`address`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`zipcode`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`tel`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`sgrade`  tinyint(3) UNSIGNED NOT NULL DEFAULT 0 ,
`apply_remark`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`credit_value`  int(10) NOT NULL DEFAULT 0 ,
`praise_rate`  decimal(5,2) UNSIGNED NOT NULL DEFAULT 0.00 ,
`domain`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`state`  tinyint(3) UNSIGNED NOT NULL DEFAULT 0 ,
`close_reason`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`add_time`  int(10) UNSIGNED NULL DEFAULT NULL ,
`end_time`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`certification`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`sort_order`  smallint(5) UNSIGNED NOT NULL DEFAULT 0 ,
`recommended`  tinyint(4) NOT NULL DEFAULT 0 ,
`theme`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`wap_theme`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`store_banner`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`store_logo`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`description`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`image_1`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`image_2`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`image_3`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`im_qq`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`im_ww`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`im_msn`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`hot_search`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`business_scope`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`online_service`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`hotline`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`pic_slides`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`enable_groupbuy`  tinyint(1) UNSIGNED NOT NULL DEFAULT 0 ,
`enable_radar`  tinyint(1) UNSIGNED NOT NULL DEFAULT 1 ,
`is_open_pay`  tinyint(3) NOT NULL DEFAULT 0 ,
`avg_goods_evaluation`  decimal(8,2) NOT NULL ,
`avg_service_evaluation`  decimal(8,2) NOT NULL ,
`avg_shipped_evaluation`  decimal(8,2) NOT NULL ,
`latlng`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
PRIMARY KEY (`store_id`),
INDEX `store_name` (`store_name`) USING BTREE ,
INDEX `owner_name` (`owner_name`) USING BTREE ,
INDEX `region_id` (`region_id`) USING BTREE ,
INDEX `domain` (`domain`) USING BTREE 
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_trans` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`title`  char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`apply_money`  decimal(10,2) NULL DEFAULT NULL ,
`trans_money`  decimal(10,2) NULL DEFAULT NULL ,
`apply_num`  int(11) NULL DEFAULT NULL ,
`enabled`  tinyint(4) NULL DEFAULT NULL ,
`apply_type`  tinyint(4) NULL DEFAULT NULL ,
`store_id`  int(11) NULL DEFAULT NULL ,
`region_id`  int(11) NULL DEFAULT NULL ,
`region_name`  char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`add_time`  int(11) NULL DEFAULT NULL ,
`rules`  varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_ultimate_store` (
`ultimate_id`  int(255) NOT NULL AUTO_INCREMENT ,
`brand_id`  int(50) NOT NULL ,
`keyword`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`cate_id`  int(50) NOT NULL ,
`store_id`  int(50) NOT NULL ,
`status`  tinyint(1) NOT NULL DEFAULT 0 ,
`description`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`ultimate_id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_uploaded_file` (
`file_id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`store_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`file_type`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`file_size`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`file_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`file_path`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`add_time`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`belong`  tinyint(3) UNSIGNED NOT NULL DEFAULT 0 ,
`item_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`link_url`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
PRIMARY KEY (`file_id`),
INDEX `store_id` (`store_id`) USING BTREE 
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_user_coupon` (
`user_id`  int(10) UNSIGNED NOT NULL ,
`coupon_sn`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
PRIMARY KEY (`user_id`, `coupon_sn`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_user_grade` (
`id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`grade_name`  char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`priority`  int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '等级优先级' ,
`upgrade`  varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '升级配置' ,
`other`  varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '其他相关项，如等级提成配置' ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_user_priv` (
`user_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`store_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`privs`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
PRIMARY KEY (`user_id`, `store_id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_wap_index` (
`id`  smallint(4) NOT NULL AUTO_INCREMENT ,
`name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`logo`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`url`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`cate_id`  smallint(4) NOT NULL ,
`sort_order`  tinyint(3) UNSIGNED NOT NULL DEFAULT 255 ,
`if_show`  tinyint(3) UNSIGNED NULL DEFAULT 1 ,
`add_time`  int(12) NULL DEFAULT NULL ,
`recom_id`  int(10) NULL DEFAULT NULL ,
`gcategory_id`  int(10) NULL DEFAULT NULL ,
`num`  tinyint(5) NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_wheel` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`title`  char(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`tags`  char(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`description`  char(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`content`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`add_time`  int(11) NULL DEFAULT NULL ,
`point`  int(11) NULL DEFAULT NULL ,
`start_time`  int(11) NULL DEFAULT NULL ,
`end_time`  int(11) NULL DEFAULT NULL ,
`enabled`  tinyint(4) NULL DEFAULT NULL ,
`status`  tinyint(4) NULL DEFAULT NULL ,
`max_num`  int(11) NULL DEFAULT NULL ,
`everper`  int(11) NULL DEFAULT NULL ,
`wheel_type`  tinyint(4) NULL DEFAULT NULL ,
`default_image`  char(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_wxconfig` (
`w_id`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`user_id`  int(11) NOT NULL ,
`url`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`token`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`appid`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`appsecret`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`access_token`  varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`access_expire`  int(11) NULL DEFAULT NULL ,
`refresh_token`  varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`w_id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_wxfile` (
`file_id`  int(11) NOT NULL AUTO_INCREMENT ,
`user_id`  int(11) NOT NULL ,
`file_type`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`file_size`  int(10) NOT NULL DEFAULT 0 ,
`file_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`file_path`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
PRIMARY KEY (`file_id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_wxkeyword` (
`kid`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`user_id`  int(11) NOT NULL ,
`kename`  varchar(300) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`kecontent`  varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`type`  tinyint(1) NOT NULL COMMENT '1:文本 2：图文' ,
`kyword`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`titles`  varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`imageinfo`  varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`linkinfo`  varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`ismess`  tinyint(1) NULL DEFAULT NULL ,
`isfollow`  tinyint(1) NULL DEFAULT NULL ,
`iskey`  tinyint(1) NULL DEFAULT NULL ,
PRIMARY KEY (`kid`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `ecm_wxmenu` (
`id`  smallint(4) UNSIGNED NOT NULL AUTO_INCREMENT ,
`user_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`name`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`tags`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`pid`  smallint(4) UNSIGNED NOT NULL DEFAULT 0 ,
`spid`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`add_time`  int(10) NOT NULL DEFAULT 0 ,
`items`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
`likes`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`weixin_type`  tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0:click 1:viwe' ,
`ordid`  tinyint(3) UNSIGNED NOT NULL DEFAULT 255 ,
`weixin_status`  tinyint(1) NOT NULL DEFAULT 0 ,
`weixin_keyword`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '关键词' ,
`weixin_key`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'key值' ,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

SET FOREIGN_KEY_CHECKS=1;


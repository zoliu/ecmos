<?php

if (!Conf::get('moolau') || Conf::get('moolau') == '') {
    $mod = &m('privilege');
    $result = $mod->db->getAll('SHOW COLUMNS FROM ' . DB_PREFIX . 'store');
    $fields = array();
    foreach ($result as $v) {
        $fields[] = $v['Field'];
    }
    if (!in_array('pic_slides', $fields)) {
        $sql = 'ALTER TABLE `' . DB_PREFIX . 'store` ADD `pic_slides` TEXT NOT NULL AFTER `im_msn`';
        $mod->db->query($sql);
    }
    if (!in_array('hotline', $fields)) {
        $sql = 'ALTER TABLE `' . DB_PREFIX . 'store` ADD `hotline` VARCHAR( 255 ) NOT NULL AFTER `im_msn`';
        $mod->db->query($sql);
    }
    if (!in_array('online_service', $fields)) {
        $sql = 'ALTER TABLE `' . DB_PREFIX . 'store` ADD `online_service` VARCHAR( 255 ) NOT NULL AFTER `im_msn`';
        $mod->db->query($sql);
    }
    if (!in_array('hot_search', $fields)) {
        $sql = 'ALTER TABLE `' . DB_PREFIX . 'store` ADD `hot_search` VARCHAR( 255 ) NOT NULL AFTER `im_msn`';
        $mod->db->query($sql);
    }
    if (!in_array('business_scope', $fields)) {
        $sql = 'ALTER TABLE `' . DB_PREFIX . 'store` ADD `business_scope` VARCHAR( 50 ) NOT NULL AFTER `hot_search`';
        $mod->db->query($sql);
    }
    $result = $mod->db->getAll('SHOW COLUMNS FROM ' . DB_PREFIX . 'groupbuy');
    $fields = array();
    foreach ($result as $v) {
        $fields[] = $v['Field'];
    }
    if (!in_array('group_image', $fields)) {
        $sql = 'ALTER TABLE `' . DB_PREFIX . 'groupbuy` ADD `group_image` VARCHAR( 255 ) NOT NULL AFTER `group_name` ';
        $mod->db->query($sql);
    }
    $result = $mod->db->getAll('SHOW COLUMNS FROM ' . DB_PREFIX . 'navigation');
    $fields = array();
    foreach ($result as $v) {
        $fields[] = $v['Field'];
    }
    if (!in_array('hot', $fields)) {
        $sql = "ALTER TABLE `" . DB_PREFIX . "navigation` ADD  `hot` TINYINT( 3 ) NOT NULL DEFAULT  '0' ";
        $mod->db->query($sql);
    }
    $sql = " CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ultimate_store` (
	  `ultimate_id` int(255) NOT NULL AUTO_INCREMENT,
	  `brand_id` int(50) NOT NULL,
	  `keyword` varchar(20) NOT NULL,
	  `cate_id` int(50) NOT NULL,
	  `store_id` int(50) NOT NULL,
	  `status` tinyint(1) NOT NULL DEFAULT '0',
	  `description` varchar(255) DEFAULT NULL,
	  PRIMARY KEY (`ultimate_id`)
	) ENGINE = MYISAM DEFAULT CHARSET=" . str_replace('-', '', CHARSET) . ";";
    $mod->db->query($sql);
    $sql = 'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'cate_pvs` (
  		`cate_id` int(11) NOT NULL,
  		`pvs` text NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=' . str_replace('-', '', CHARSET) . ';';
    $mod->db->query($sql);
    $sql = 'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'goods_prop` (
  		`pid` int(11) NOT NULL auto_increment,
  		`name` varchar(50) NOT NULL,
  		`status` int(1) NOT NULL,
  		`sort_order` int(11) NOT NULL,
  		PRIMARY KEY  (`pid`)
	) ENGINE=MyISAM  DEFAULT CHARSET=' . str_replace('-', '', CHARSET) . ';';
    $mod->db->query($sql);
    $sql = 'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'goods_prop_value` (
  		`vid` int(11) NOT NULL auto_increment,
  		`pid` int(11) NOT NULL,
  		`prop_value` varchar(255) NOT NULL,
  		`status` int(1) NOT NULL,
  		`sort_order` int(11) NOT NULL,
  		PRIMARY KEY  (`vid`)
	) ENGINE=MyISAM  DEFAULT CHARSET=' . str_replace('-', '', CHARSET) . ';';
    $mod->db->query($sql);
    $sql = 'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'goods_pvs` (
  		`goods_id` int(11) NOT NULL,
  		`pvs` text NOT NULL,
  		PRIMARY KEY  (`goods_id`)
	) ENGINE=MyISAM DEFAULT CHARSET=' . str_replace('-', '', CHARSET) . ';';
    $mod->db->query($sql);
}

class Init_FrontendApp {

    function _get_carts_top($sess_id, $user_id = 0) {
        $where_user_id = $user_id ? " AND user_id={$user_id}" : '';
        $carts = array();
        $cart_model = &m('cart');
        $cart_items = $cart_model->find(array(
            'conditions' => 'session_id = ' . "'" . $sess_id . "'" . $where_user_id,
            'fields' => '',
        ));
        $total = 0;
        foreach ($cart_items as $key => $val) {
            $total += $val['price'] * $val['quantity'];
        }
        return array('cart_items' => $cart_items, 'total' => $total);
    }

    function _get_header_gcategories($amount, $position, $brand_is_recommend = 1) {
        $gcategory_mod = &bm('gcategory', array('_store_id' => 0));
        $gcategories = array();
        if (!$amount) {
            $gcategories = $gcategory_mod->get_list(-1, true);
        } else {
            $gcategory = $gcategory_mod->get_list(0, true);
            $gcategories = $gcategory;
            foreach ($gcategory as $val) {
                $result = $gcategory_mod->get_list($val['cate_id'], true);
                $result = array_slice($result, 0, $amount);
                $gcategories = array_merge($gcategories, $result);
            }
        }

            $ogcates=$gcategories;

          
        import('tree.lib');
        $tree = new Tree();
        $tree->setTree($gcategories, 'cate_id', 'parent_id', 'cate_name');
        $gcategory_list = $tree->getArrayList(0);
         foreach ($gcategory_list as $key => $value) {
              $gcategory_list[$key]['logo']=$ogcates[$value['id']]['logo'];
              $gcategory_list[$key]['recom_logo']=$this->get_recom_logo($value['id']);
            }
        $i = 0;
        $brand_mod = &m('brand');
        foreach ($gcategory_list as $k => $v) {
            $gcategory_list[$k]['top'] = isset($position[$i]) ? $position[$i] : '0px';
            $i++;
            $gcategory_list[$k]['brands'] = $brand_mod->find(array(
                'conditions' => "tag = '" . $v['value'] . "' AND recommended=" . $brand_is_recommend,
                'order' => 'sort_order asc,brand_id desc'
            ));
        }
        return array('gcategories' => $gcategory_list);
    }

    //得到分类下推荐的广告图片
    function get_recom_logo($gcategory_id){

        $mod= &m('recommendation');

        $cate_mod= &m('rcategory');

        $cate_list=$cate_mod->get('sort_order=12');
        $conditions .=' and cate_id='.$cate_list['cate_id'];
        $conditions .=$r_type?' and r_type="image_5"':'';
        $conditions .=$gcategory_id?' and gcategory_id='.$gcategory_id:'';

        $recom_list=$mod->find(array(
            'conditions'=>'1=1'.$conditions,
            'order'=>'sort_order asc',
            'limit'=>3,
        ));
        return $recom_list;
    }

}

class Init_SearchApp {

    function _get_group_by_info_by_brands($by_brands, $param) {
        if (!empty($param["brand"])) {
            unset($by_brands[$param['brand']]);
        }
        return $by_brands;
    }

    function _get_group_by_info_by_region($sql, $param) {
        $goods_mod = &m('goods');
        $by_regions = $goods_mod->getAll($sql);
        if (!empty($param["region_id"])) {
            foreach ($by_regions as $k => $v) {
                if ($v["region_id"] == $param["region_id"]) {
                    unset($by_regions[$k]);
                }
            }
        }
        return $by_regions;
    }

    function _get_ultimate_store($conditions, $brand) {
        $store = array();
        $us_mod = &m('ultimate_store');
        $store_mod = &m('store');
        $ultimate_store = $us_mod->get(array('conditions' => 'status=1 ' . $conditions, 'fields' => 'store_id,description'));
        if ($ultimate_store) {
            $store = $store_mod->get(array('conditions' => 'store_id=' . $ultimate_store['store_id'], 'fields' => 'store_logo,store_name'));
            empty($store['store_logo']) && $store['store_logo'] = Conf::get('default_store_logo');
            if ($brand && !empty($brand['brand_logo'])) {
                $store['store_logo'] = $brand['brand_logo'];
            }
            $store = array(array_merge($ultimate_store, $store));
        }
        return $store;
    }

}

class Init_OrderApp {

    function get_available_coupon($store_id) {
        $time = gmtime();
        $model_cart = &m('cart');
        $item_info = $model_cart->find("store_id={$store_id} AND session_id='" . SESS_ID . "'");
        $price = 0;
        foreach ($item_info as $val) {
            $price = $price + $val['price'] * $val['quantity'];
        }
        $coupon = $model_cart->getAll("SELECT *FROM " . DB_PREFIX . "coupon_sn couponsn " .
                "LEFT JOIN " . DB_PREFIX . "coupon coupon ON couponsn.coupon_id=coupon.coupon_id " .
                "LEFT JOIN " . DB_PREFIX . "user_coupon user_coupon ON user_coupon.coupon_sn=couponsn.coupon_sn " .
                "WHERE coupon.store_id = " . $store_id . " AND couponsn.remain_times >=1 " .
                "AND user_coupon.user_id=" . $this->visitor->get('user_id') . " " .
                "AND coupon.start_time <= " . $time . " AND coupon.end_time >= " . $time . " AND coupon.min_amount <= " . $price
        );
        return $coupon;
    }

}

class Init_Taocz_articleWidget {

    var $options = null;

    function _get_data($i) {
        $acategory_mod = &m('acategory');
        $cate_ids = $acategory_mod->get_descendant($this->options['cate_id_' . $i]);
        if ($cate_ids) {
            $conditions = ' AND cate_id ' . db_create_in($cate_ids);
        } else {
            $conditions = '';
        }
        return $conditions;
    }

}

class Init_Taocz_floorWidget {

    function _get_data($options = array()) {
        $recom_mod = &m('recommend');
        $goods_list = $recom_mod->get_recommended_goods($options['img_recom_id'], 10, true, $options['img_cate_id']);
        return $goods_list;
    }

}

class Init_Taocz_four_tabWidget {

    function _get_data($options = array(), $amount) {
        $recom_mod = &m('recommend');
        $data = array();
        for ($i = 1; $i <= $amount; $i++) {
            $data['goods_list'][] = $recom_mod->get_recommended_goods($options['img_recom_id_' . $i], 3, true, $options['img_cate_id_' . $i]);
            $data['tabs'][] = $options['tab_' . $i];
        }
        return $data;
    }

}

define('LOCK_FILE', ROOT_PATH . '/data/init.lock');
if (!file_exists(LOCK_FILE)) {
	if (!defined('CHARSET')) {
		define('CHARSET', substr(LANG, 3));
	}
	$pamb = new Psmb_init();
	$pamb->create_table();
	file_put_contents(LOCK_FILE, 1);
}

class Psmb_init
{
	public function create_table()
	{
		$result = db()->getAll('SHOW COLUMNS FROM ' . DB_PREFIX . 'uploaded_file');
		$fields = array();
		foreach ($result as $v) {
			$fields[] = $v['Field'];
		}
		if (!in_array('link_url', $fields)) {
			$sql = 'ALTER TABLE `' . DB_PREFIX . 'uploaded_file` ADD `link_url` VARCHAR( 100 ) NOT NULL ';
			db()->query($sql);
		}
		$result = db()->getAll('SHOW COLUMNS FROM ' . DB_PREFIX . 'store');
		$fields = array();
		foreach ($result as $v) {
			$fields[] = $v['Field'];
		}
		if (!in_array('pic_slides', $fields)) {
			$sql = 'ALTER TABLE `' . DB_PREFIX . 'store` ADD `pic_slides` TEXT NOT NULL AFTER `im_msn`';
			db()->query($sql);
		}
		if (!in_array('business_scope', $fields)) {
			$sql = 'ALTER TABLE `' . DB_PREFIX . 'store` ADD `business_scope` VARCHAR( 50 ) NOT NULL ';
			db()->query($sql);
		}
		if (!in_array('avg_goods_evaluation', $fields)) {
			$sql = 'ALTER TABLE `' . DB_PREFIX . 'store` ADD `avg_goods_evaluation` decimal(8,2)  NOT NULL';
			db()->query($sql);
		}
		if (!in_array('avg_service_evaluation', $fields)) {
			$sql = 'ALTER TABLE `' . DB_PREFIX . 'store` ADD `avg_service_evaluation` decimal(8,2) NOT NULL';
			db()->query($sql);
		}
		if (!in_array('avg_shipped_evaluation', $fields)) {
			$sql = 'ALTER TABLE `' . DB_PREFIX . 'store` ADD `avg_shipped_evaluation` decimal(8,2) NOT NULL';
			db()->query($sql);
		}
		if (!in_array('latlng', $fields)) {
			$sql = 'ALTER TABLE `' . DB_PREFIX . 'store` ADD `latlng` varchar(100) NOT NULL';
			db()->query($sql);
		}
		$result = db()->getAll('SHOW COLUMNS FROM ' . DB_PREFIX . 'order_goods');
		$fields = array();
		foreach ($result as $v) {
			$fields[] = $v['Field'];
		}
		if (!in_array('reply_content', $fields)) {
			$sql = 'ALTER TABLE `' . DB_PREFIX . 'order_goods` ADD `reply_content` TEXT NOT NULL ';
			db()->query($sql);
		}
		if (!in_array('reply_time', $fields)) {
			$sql = 'ALTER TABLE `' . DB_PREFIX . 'order_goods` ADD `reply_time` INT(10) NOT NULL ';
			db()->query($sql);
		}
		if (!in_array('shipped_evaluation', $fields)) {
			$sql = 'ALTER TABLE `' . DB_PREFIX . 'order_goods` ADD `shipped_evaluation` decimal(4,2) NOT NULL ';
			db()->query($sql);
		}
		if (!in_array('service_evaluation', $fields)) {
			$sql = 'ALTER TABLE `' . DB_PREFIX . 'order_goods` ADD `service_evaluation` decimal(4,2) NOT NULL ';
			db()->query($sql);
		}
		if (!in_array('goods_evaluation', $fields)) {
			$sql = 'ALTER TABLE `' . DB_PREFIX . 'order_goods` ADD `goods_evaluation` decimal(4,2) NOT NULL ';
			db()->query($sql);
		}
		$result = db()->getAll('SHOW COLUMNS FROM ' . DB_PREFIX . 'groupbuy');
		$fields = array();
		foreach ($result as $v) {
			$fields[] = $v['Field'];
		}
		if (!in_array('group_image', $fields)) {
			$sql = 'ALTER TABLE `' . DB_PREFIX . 'groupbuy` ADD `group_image` VARCHAR( 255 ) NOT NULL AFTER `group_name` ';
			db()->query($sql);
		}
		$result = db()->getAll('SHOW COLUMNS FROM ' . DB_PREFIX . 'order');
		$fields = array();
		foreach ($result as $v) {
			$fields[] = $v['Field'];
		}
		if (!in_array('express_company', $fields)) {
			$sql = 'ALTER TABLE `' . DB_PREFIX . 'order` ADD `express_company` VARCHAR( 50 ) NOT NULL AFTER `invoice_no` ';
			db()->query($sql);
		}
		if (!in_array('flag', $fields)) {
			$sql = 'ALTER TABLE `' . DB_PREFIX . 'order` ADD `flag` int( 1 ) NOT NULL ';
			db()->query($sql);
		}
		if (!in_array('memo', $fields)) {
			$sql = 'ALTER TABLE `' . DB_PREFIX . 'order` ADD `memo` varchar( 255 ) NOT NULL ';
			db()->query($sql);
		}
		$sql = ' CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'ultimate_store` (
	  		`ultimate_id` int(255) NOT NULL AUTO_INCREMENT,
	  		`brand_id` int(50) NOT NULL,
	  		`keyword` varchar(20) NOT NULL,
	  		`cate_id` int(50) NOT NULL,
	  		`store_id` int(50) NOT NULL,
	  		`status` tinyint(1) NOT NULL DEFAULT \'0\',
	  		`description` varchar(255) DEFAULT NULL,
	 		 PRIMARY KEY (`ultimate_id`)
		) ENGINE = MYISAM DEFAULT CHARSET=' . str_replace('-', '', CHARSET) . ';';
		db()->query($sql);
		$sql = 'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'promotion` (
  			`pro_id` int(11) NOT NULL auto_increment,
  			`goods_id` int(11) NOT NULL,
  			`pro_name` varchar(50) NOT NULL,
  			`pro_desc` varchar(255) NOT NULL,
  			`start_time` int(11) NOT NULL,
  			`end_time` int(11) NOT NULL,
  			`store_id` int(11) NOT NULL,
  			`spec_price` text NOT NULL,
			`image` VARCHAR( 255 ) NOT NULL,
  			PRIMARY KEY  (`pro_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=' . str_replace('-', '', CHARSET) . ';';
		db()->query($sql);
		$sql = 'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'member_bind` (
  			`openid` varchar(255) NOT NULL,
  			`user_id` int(11) NOT NULL,
  			`app` varchar(50) NOT NULL
		) ENGINE = MYISAM DEFAULT CHARSET=' . str_replace('-', '', CHARSET) . ';';
		db()->query($sql);
		$sql = 'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'cate_pvs` (
  			`cate_id` int(11) NOT NULL,
  			`pvs` text NOT NULL
		) ENGINE=MyISAM DEFAULT CHARSET=' . str_replace('-', '', CHARSET) . ';';
		db()->query($sql);
		$sql = 'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'goods_prop` (
  			`pid` int(11) NOT NULL auto_increment,
  			`name` varchar(50) NOT NULL,
			`prop_type` VARCHAR( 20 ) NOT NULL DEFAULT \'select\',
			`is_color_prop` INT NOT NULL DEFAULT \'0\',
  			`status` int(1) NOT NULL,
  			`sort_order` int(11) NOT NULL,
  			PRIMARY KEY  (`pid`)
		) ENGINE=MyISAM  DEFAULT CHARSET=' . str_replace('-', '', CHARSET) . ';';
		db()->query($sql);
		$sql = 'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'goods_prop_value` (
  			`vid` int(11) NOT NULL auto_increment,
  			`pid` int(11) NOT NULL,
  			`prop_value` varchar(255) NOT NULL,
			`color_value` VARCHAR( 255 ) NOT NULL,
  			`status` int(1) NOT NULL,
  			`sort_order` int(11) NOT NULL,
  			PRIMARY KEY  (`vid`)
		) ENGINE=MyISAM  DEFAULT CHARSET=' . str_replace('-', '', CHARSET) . ';';
		db()->query($sql);
		$sql = 'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'goods_pvs` (
  			`goods_id` int(11) NOT NULL,
  			`pvs` text NOT NULL,
  			PRIMARY KEY  (`goods_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=' . str_replace('-', '', CHARSET) . ';';
		db()->query($sql);
		$sql = 'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'deposit_account` (
  			`account_id` int(11) NOT NULL AUTO_INCREMENT,
  			`user_id` int(11) NOT NULL,
  			`account` varchar(100) NOT NULL,
  			`password` varchar(255) NOT NULL,
  			`money` decimal(10,2) NOT NULL,
  			`frozen` decimal(10,2) NOT NULL,
  			`real_name` varchar(30) NOT NULL,
  			`pay_status` varchar(3) NOT NULL DEFAULT \'off\',
  			`add_time` int(11) NOT NULL,
  			`last_update` int(11) NOT NULL,
  			PRIMARY KEY (`account_id`)
		) ENGINE = MYISAM DEFAULT CHARSET=' . str_replace('-', '', CHARSET) . ';';
		db()->query($sql);
		$sql = 'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'deposit_recharge` (
  			`recharge_id` int(11) NOT NULL AUTO_INCREMENT,
  			`tradesn` varchar(30) NOT NULL,
  			`user_id` int(11) NOT NULL,
			`examine` varchar(100) NOT NULL,
  			`amount` decimal(10,2) NOT NULL,
  			`status` varchar(30) NOT NULL,
  			`is_online` int(1) NOT NULL,
  			`extra` text NOT NULL,
  			`add_time` int(11) NOT NULL,
  			`pay_time` int(11) NOT NULL,
  			`end_time` int(11) NOT NULL,
  			PRIMARY KEY (`recharge_id`)
		) ENGINE = MYISAM DEFAULT CHARSET=' . str_replace('-', '', CHARSET) . ';';
		db()->query($sql);
		$sql = 'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'deposit_record` (
 			`record_id` int(11) NOT NULL AUTO_INCREMENT,
  			`tradesn` varchar(30) NOT NULL,
  			`order_sn` varchar(20) NOT NULL,
  			`user_id` int(11) NOT NULL COMMENT \'交易发起方\',
  			`party_id` int(11) NOT NULL COMMENT \'交易的对方\',
  			`amount` decimal(10,2) NOT NULL COMMENT \'收支金额\',
  			`balance` decimal(10,2) NOT NULL COMMENT \'账户余额\',
  			`flow` varchar(10) NOT NULL COMMENT \'资金流向\',
  			`purpose` varchar(20) NOT NULL COMMENT \'用途\',
  			`status` varchar(30) NOT NULL,
  			`payway` varchar(100) NOT NULL COMMENT \'资金渠道\',
  			`name` varchar(100) NOT NULL COMMENT \'名称\',
  			`remark` varchar(255) NOT NULL COMMENT \'备注\',
  			`add_time` int(11) NOT NULL,
  			`pay_time` int(11) NOT NULL,
  			`end_time` int(11) NOT NULL,
  			PRIMARY KEY (`record_id`)
		) ENGINE = MYISAM DEFAULT CHARSET=' . str_replace('-', '', CHARSET) . ';';
		db()->query($sql);
		$sql = 'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'deposit_refund` (
  			`refund_id` int(11) NOT NULL AUTO_INCREMENT,
  			`record_id` int(11) NOT NULL,
  			`user_id` int(11) NOT NULL COMMENT \'获得退款的用户ID\',
  			`amount` decimal(10,2) NOT NULL,
  			`status` varchar(30) NOT NULL,
  			`remark` varchar(255) NOT NULL,
  			PRIMARY KEY (`refund_id`)
	 	) ENGINE = MYISAM DEFAULT CHARSET=' . str_replace('-', '', CHARSET) . ';';
		db()->query($sql);
		$sql = 'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'deposit_setting` (
  			`setting_id` int(11) NOT NULL AUTO_INCREMENT,
  			`user_id` int(11) NOT NULL,
  			`trade_rate` decimal(10,3) NOT NULL COMMENT \'交易手续费\',
  			`transfer_rate` decimal(10,3) NOT NULL,
			`auto_create_account` int(1)  NOT NULL,
			`config_account_captcha` int(1)  NOT NULL,
  			PRIMARY KEY (`setting_id`)
		) ENGINE = MYISAM DEFAULT CHARSET=' . str_replace('-', '', CHARSET) . ';';
		db()->query($sql);
		$sql = 'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'deposit_withdraw` (
  			`withdraw_id` int(11) NOT NULL AUTO_INCREMENT,
  			`record_id` int(11) NOT NULL,
  			`tradesn` varchar(30) NOT NULL,
  			`user_id` int(11) NOT NULL,
  			`amount` decimal(10,2) NOT NULL,
  			`status` varchar(30) NOT NULL,
  			`card_info` text NOT NULL,
  			`add_time` int(11) NOT NULL,
  			`pay_time` int(11) NOT NULL,
  			`end_time` int(11) NOT NULL,
  			PRIMARY KEY (`withdraw_id`)
		) ENGINE = MYISAM DEFAULT CHARSET=' . str_replace('-', '', CHARSET) . ';';
		db()->query($sql);
		$sql = 'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'bank` (
  			`bid` int(11) NOT NULL AUTO_INCREMENT,
  			`user_id` int(11) NOT NULL,
  			`bank_name` varchar(100) NOT NULL,
  			`short_name` varchar(20) NOT NULL,
  			`account_name` varchar(20) NOT NULL,
  			`open_bank` varchar(100) NOT NULL,
  			`type` varchar(10) NOT NULL,
  			`num` varchar(50) NOT NULL,
			PRIMARY KEY (`bid`)
		) ENGINE = MYISAM DEFAULT CHARSET=' . str_replace('-', '', CHARSET) . ';';
		db()->query($sql);
		$result = db()->getAll('SHOW COLUMNS FROM ' . DB_PREFIX . 'order_goods');
		$fields = array();
		foreach ($result as $v) {
			$fields[] = $v['Field'];
		}
		if (!in_array('status', $fields)) {
			$sql = 'ALTER TABLE `' . DB_PREFIX . 'order_goods` ADD  `status` varchar(50) NOT NULL ';
			db()->query($sql);
		}
		$sql = 'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'refund` (
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
  			`status` varchar(100) NOT NULL DEFAULT \'\',
  			`shipped` int(11) NOT NULL,
  			`ask_customer` int(1) NOT NULL DEFAULT \'0\',
  			`created` int(11) NOT NULL,
  			`end_time` int(11) NOT NULL,
  			PRIMARY KEY (`refund_id`)
		) ENGINE = MYISAM DEFAULT CHARSET=' . str_replace('-', '', CHARSET) . ';';
		db()->query($sql);
		$sql = 'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'refund_message` (
  			`rm_id` int(11) NOT NULL AUTO_INCREMENT,
  			`owner_id` int(11) NOT NULL,
  			`owner_role` varchar(10) NOT NULL,
  			`refund_id` int(11) NOT NULL,
  			`content` varchar(255) DEFAULT NULL,
  			`pic_url` varchar(255) DEFAULT NULL,
  			`created` int(11) NOT NULL,
  			PRIMARY KEY (`rm_id`)
		) ENGINE = MYISAM DEFAULT CHARSET=' . str_replace('-', '', CHARSET) . ';';
		db()->query($sql);
		$result = db()->getAll('SHOW COLUMNS FROM ' . DB_PREFIX . 'goods');
		$fields = array();
		foreach ($result as $v) {
			$fields[] = $v['Field'];
		}
		if (!in_array('delivery_template_id', $fields)) {
			$sql = 'ALTER TABLE `' . DB_PREFIX . 'goods` ADD `delivery_template_id` INT (11) NOT NULL ';
			db()->query($sql);
		}
		$sql = 'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'delivery_template` (
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
		) ENGINE = MYISAM DEFAULT CHARSET=' . str_replace('-', '', CHARSET) . ';';
		db()->query($sql);
		$result = db()->getAll('SHOW COLUMNS FROM ' . DB_PREFIX . 'payment');
		$fields = array();
		foreach ($result as $v) {
			$fields[] = $v['Field'];
		}
		if (!in_array('cod_regions', $fields)) {
			$sql = 'ALTER TABLE `' . DB_PREFIX . 'payment` ADD `cod_regions` TEXT NOT NULL ';
			db()->query($sql);
		}
		$sql = 'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'goods_integral` (
  		`goods_id` int(11) NOT NULL,
  		`max_exchange` int(11) NOT NULL,
  		PRIMARY KEY  (`goods_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=' . str_replace('-', '', CHARSET) . ';';
		db()->query($sql);
		$sql = 'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'integral` (
			`user_id` int(11) NOT NULL,
			`amount` decimal(10,2) NOT NULL,
			PRIMARY KEY  (`user_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=' . str_replace('-', '', CHARSET) . ';';
		db()->query($sql);
		$sql = 'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'integral_log` (
			`log_id` int(11) NOT NULL AUTO_INCREMENT,
			`user_id` int(10) NOT NULL,
			`order_id` int(10) NOT NULL DEFAULT \'0\',
			`order_sn` varchar(20) NOT NULL,
			`changes` decimal(25,2) NOT NULL,
			`balance` decimal(25,2) NOT NULL,
			`type` varchar(50) NOT NULL,
			`state` varchar(50) NOT NULL,
			`flag` varchar(255) NOT NULL ,
			`add_time` int(11) NOT NULL,
			PRIMARY KEY (`log_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=' . str_replace('-', '', CHARSET) . ';';
		db()->query($sql);
		$sql = 'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'order_integral` (
			`order_id` int(11) NOT NULL,
			`buyer_id` int(11) NOT NULL,
			`frozen_integral` decimal(10,2) NOT NULL,
			PRIMARY KEY  (`order_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=' . str_replace('-', '', CHARSET) . ';';
		db()->query($sql);
		$result = db()->getAll('SHOW COLUMNS FROM ' . DB_PREFIX . 'address');
		$fields = array();
		foreach ($result as $v) {
			$fields[] = $v['Field'];
		}
		if (!in_array('setdefault', $fields)) {
			$sql = 'ALTER TABLE `' . DB_PREFIX . 'address` ADD  `setdefault` tinyint(3) NOT NULL DEFAULT \'0\' ';
			db()->query($sql);
		}
		$result = db()->getAll('SHOW COLUMNS FROM ' . DB_PREFIX . 'sgrade');
		$fields = array();
		foreach ($result as $v) {
			$fields[] = $v['Field'];
		}
		if (!in_array('wap_skins', $fields)) {
			$sql = 'ALTER TABLE `' . DB_PREFIX . 'sgrade` ADD `wap_skins` VARCHAR(255) NOT NULL  AFTER `skins`';
			db()->query($sql);
		}
		if (!in_array('wap_skin_limit', $fields)) {
			$sql = 'ALTER TABLE `' . DB_PREFIX . 'sgrade` ADD `wap_skin_limit` INT(3) NOT NULL  AFTER `skin_limit`';
			db()->query($sql);
		}
		$result = db()->getAll('SHOW COLUMNS FROM ' . DB_PREFIX . 'store');
		$fields = array();
		foreach ($result as $v) {
			$fields[] = $v['Field'];
		}
		if (!in_array('wap_theme', $fields)) {
			$sql = 'ALTER TABLE `' . DB_PREFIX . 'store` ADD `wap_theme` VARCHAR(255) NOT NULL  AFTER `theme`';
			db()->query($sql);
		}
		$result = db()->getAll('SHOW COLUMNS FROM ' . DB_PREFIX . 'member');
		$fields = array();
		foreach ($result as $v) {
			$fields[] = $v['Field'];
		}
		if (!in_array('locked', $fields)) {
			$sql = 'ALTER TABLE `' . DB_PREFIX . 'member` ADD  `locked` int(1) NOT NULL default 0';
			db()->query($sql);
		}
	}
	public function check_view_device($path = 'mobile', $return = false)
	{

		$result = false;

		$wap=isset($_GET['wap'])?intval($_GET['wap']):0;
		if($wap)
		{
			$result= true;
		}
		if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
			$result = true;
		}
		if (isset($_SERVER['HTTP_VIA']) && stristr($_SERVER['HTTP_VIA'], 'wap')) {
			$result = true;
		}
		if (isset($_SERVER['HTTP_USER_AGENT'])) {
			$clientkeywords = array('nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile');
			if (preg_match('/(' . implode('|', $clientkeywords) . ')/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
				$result = true;
			}
		}
		if (isset($_SERVER['HTTP_ACCEPT'])) {
			if (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))) {
				$result = true;
			}
		}
		if ($result === true) {
			if (!$return) {
				$query_string = '';
				if (!empty($_SERVER['QUERY_STRING'])) {
					$query_string = '?' . $_SERVER['QUERY_STRING'];
				}
				// header('Location:' . SITE_URL . '/' . $path . '/index.php' . $query_string);
				// die;
			}
			return $result;
		}
	}
	public function check_template_editable($themes, $mobile = false)
	{
		$type = $mobile ? '/mobile' : '';
		foreach ($themes as $key => $theme) {
			$file = ROOT_PATH . $type . '/themes/store/' . $theme['template_name'] . '/form.info.php';
			if (is_file($file) && file_exists($file)) {
				$themes[$key]['editable'] = 1;
			}
		}
		return $themes;
	}
	public function _check_express_plugin()
	{
		$plugin_inc_file = ROOT_PATH . '/data/plugins.inc.php';
		if (is_file($plugin_inc_file)) {
			$plugins = (include $plugin_inc_file);
			return isset($plugins['on_query_express']['kuaidi100']);
		}
		return false;
	}
	public function deal_config_data($model, $data)
	{
		$result = array();
		switch ($model) {
			case 'image':
				$count = count($data) / 2;
				for ($i = 1; $i <= $count; $i++) {
					$result[$i]['ad_image_url'] = $data['ad' . $i . '_image_url'];
					$result[$i]['ad_link_url'] = $data['ad' . $i . '_link_url'];
				}
				if ($count == 1) {
					$result = current($result);
				}
				break;
			case 'im':
				$im = array();
				$im1 = explode('@', current($data));
				foreach ($im1 as $key => $val) {
					$im2 = explode(' ', $val);
					foreach ($im2 as $k => $v) {
						$im3 = explode(',', $v);
						$im[$key][$k]['number'] = $im3[0];
						$im[$key][$k]['name'] = $im3[1];
					}
				}
				$result['qq'] = $im[0];
				$result['wangwang'] = $im[1];
				break;
			case 'keywords':
				$result = explode(' ', current($data));
				break;
			case 'floor':
				$result['model_name'] = $data['model_name'];
				$title = explode(' ', $data['keywords']);
				$link = explode(' ', $data['link']);
				if (count($title) > 0) {
					for ($k = 0; $k < count($title); $k++) {
						$result['keywords'][$k] = array('title' => $title[$k], 'link' => $link[$k]);
					}
				}
				for ($i = 1; $i <= 3; $i++) {
					$result['ads'][$i]['ad_image_url'] = $data['ad' . $i . '_image_url'];
					$result['ads'][$i]['ad_link_url'] = $data['ad' . $i . '_link_url'];
				}
				if ($data['time']) {
					$time = strtotime($data['time']);
					$result['lefttime'] = $this->lefttime($time);
				}
				break;
			case 'goods_list':
				$amount = $data['amount'] ? $data['amount'] : 10;
				$recom_mod =& m('recommend');
				$goods_list = $recom_mod->get_recommended_goods($data['recommand_id'], $amount, true, '');
				$result = array_chunk($goods_list, 2);
				break;
			default:
				$result = current($data);
		}
		return $result;
	}
	public function get_carts_top($sess_id, $user_id = 0)
	{
		$where_user_id = $user_id ? " AND user_id={$user_id}" : '';
		$cart_items = array();
		$total_count = 0;
		$total_amount = 0;
		$cart_model =& m('cart');
		$cart_items = $cart_model->find(array('conditions' => 'session_id = ' . '\'' . $sess_id . '\'' . $where_user_id, 'fields' => ''));
		foreach ($cart_items as $key => $val) {
			$total_count += $val['quantity'];
			$total_amount += round($val['price'] * $val['quantity'], 2);
		}
		return array('cart_items' => $cart_items, 'total_count' => $total_count, 'total_amount' => $total_amount);
	}
	public function get_header_gcategories($amount, $position, $brand_is_recommend = 1)
	{
		$gcategory_mod =& bm('gcategory', array('_store_id' => 0));
		$gcategories = array();
		if (!$amount) {
			$gcategories = $gcategory_mod->get_list(-1, true);
		} else {
			$gcategory = $gcategory_mod->get_list(0, true);
			$gcategories = $gcategory;
			foreach ($gcategory as $val) {
				$result = $gcategory_mod->get_list($val['cate_id'], true);
				$result = array_slice($result, 0, $amount);
				$gcategories = array_merge($gcategories, $result);
			}
		}
		import('tree.lib');
		$tree = new Tree();
		$tree->setTree($gcategories, 'cate_id', 'parent_id', 'cate_name');
		$gcategory_list = $tree->getArrayList(0);
		$i = 0;
		$brand_mod =& m('brand');
		$uploadedfile_mod =& m('uploadedfile');
		foreach ($gcategory_list as $k => $v) {
			$gcategory_list[$k]['top'] = isset($position[$i]) ? $position[$i] : '0px';
			$i++;
			$gcategory_list[$k]['brands'] = $brand_mod->find(array('conditions' => 'tag = \'' . $v['value'] . '\' AND recommended=' . $brand_is_recommend, 'order' => 'sort_order asc,brand_id desc'));
			$gcategory_list[$k]['gads'] = $uploadedfile_mod->find(array('conditions' => 'store_id = 0 AND belong = ' . BELONG_GCATEGORY . ' AND item_id=' . $v['id'], 'fields' => 'this.file_id, this.file_name, this.file_path,this.link_url', 'order' => 'add_time DESC'));
		}
		return array('gcategories' => $gcategory_list);
	}
	public function get_group_by_info_by_brands($by_brands = array(), $param)
	{
		if (!empty($param['brand'])) {
			unset($by_brands[$param['brand']]);
		}
		$brand_mod =& m('brand');
		foreach ($by_brands as $key => $val) {
			$brand = $brand_mod->get(array('conditions' => 'brand_name=\'' . $val['brand'] . '\'', 'fields' => 'brand_logo'));
			$by_brands[$key]['brand_logo'] = $brand['brand_logo'];
		}
		return $by_brands;
	}
	public function get_group_by_info_by_region($sql, $param)
	{
		$goods_mod =& m('goods');
		$by_regions = $goods_mod->getAll($sql);
		if (!empty($param['region_id'])) {
			foreach ($by_regions as $k => $v) {
				if ($v['region_id'] == $param['region_id']) {
					unset($by_regions[$k]);
				}
			}
		}
		return $by_regions;
	}
	public function get_ultimate_store($conditions, $brand)
	{
		$store = array();
		$us_mod =& m('ultimate_store');
		$store_mod =& m('store');
		$ultimate_store = $us_mod->get(array('conditions' => 'status=1 ' . $conditions, 'fields' => 'store_id,description'));
		if ($ultimate_store) {
			$store = $store_mod->get(array('conditions' => 'store_id=' . $ultimate_store['store_id'], 'fields' => 'store_logo,store_name'));
			empty($store['store_logo']) && ($store['store_logo'] = Conf::get('default_store_logo'));
			if ($brand && !empty($brand['brand_logo'])) {
				$store['store_logo'] = $brand['brand_logo'];
			}
			$store = array(array_merge($ultimate_store, $store));
		}
		return $store;
	}
	public function get_available_coupon($store_id, $user_id)
	{
		$time = gmtime();
		$model_cart =& m('cart');
		$item_info = $model_cart->find("store_id={$store_id} AND session_id='" . SESS_ID . '\'');
		$price = 0;
		foreach ($item_info as $val) {
			$price = $price + $val['price'] * $val['quantity'];
		}
		$coupon = $model_cart->getAll('SELECT *FROM ' . DB_PREFIX . 'coupon_sn couponsn ' . 'LEFT JOIN ' . DB_PREFIX . 'coupon coupon ON couponsn.coupon_id=coupon.coupon_id ' . 'LEFT JOIN ' . DB_PREFIX . 'user_coupon user_coupon ON user_coupon.coupon_sn=couponsn.coupon_sn ' . 'WHERE coupon.store_id = ' . $store_id . ' AND couponsn.remain_times >=1 ' . 'AND user_coupon.user_id=' . $user_id . ' ' . 'AND coupon.start_time <= ' . $time . ' AND coupon.end_time >= ' . $time . ' AND coupon.min_amount <= ' . $price);
		return $coupon;
	}
	public function get_industry_avg_evaluation($store_id)
	{
		$store_mod =& m('store');
		$store_data = $store_mod->get(array('conditions' => 's.store_id=' . $store_id, 'join' => 'has_scategory'));
		if ($store_data['cate_id'] > 0) {
			$scategory_mod =& m('scategory');
			$condition = ' AND cate_id  ' . db_create_in($scategory_mod->get_descendant($store_data['cate_id'])) . ' ';
		}
		$data = $store_mod->find(array('conditions' => 'state = 1 AND avg_shipped_evaluation > 0 AND avg_service_evaluation > 0 AND avg_goods_evaluation > 0 ' . $condition, 'join' => 'has_scategory', 'fields' => 'avg_goods_evaluation,avg_service_evaluation,avg_shipped_evaluation'));
		$result = array();
		$result['total_count'] = $result['total_avg_gevaluation'] = $result['total_avg_shevaluation'] = $result['total_avg_sevaluation'] = 0;
		if (!empty($data)) {
			$result['total_count'] = count($data);
			foreach ($data as $key => $val) {
				$result['total_avg_gevaluation'] = $result['total_avg_gevaluation'] + $val['avg_goods_evaluation'];
				$result['total_avg_shevaluation'] = $result['total_avg_shevaluation'] + $val['avg_shipped_evaluation'];
				$result['total_avg_sevaluation'] = $result['total_avg_sevaluation'] + $val['avg_service_evaluation'];
			}
		}
		return $this->calculate_evaluation($result, $store_data);
	}
	public function calculate_evaluation($industy_data, $store_data)
	{
		$industy_avgs = array();
		if ($industy_data['total_count'] > 0) {
			$industy_avgs_goods = $industy_data['total_avg_gevaluation'] / $industy_data['total_count'];
			$industy_avgs_service = $industy_data['total_avg_sevaluation'] / $industy_data['total_count'];
			$industy_avgs_shipped = $industy_data['total_avg_shevaluation'] / $industy_data['total_count'];
			$goods_compare = round(($store_data['avg_goods_evaluation'] - $industy_avgs_goods) / $industy_avgs_goods, 4) * 100;
			$service_compare = round(($store_data['avg_service_evaluation'] - $industy_avgs_service) / $industy_avgs_service, 4) * 100;
			$shipped_compare = round(($store_data['avg_shipped_evaluation'] - $industy_avgs_shipped) / $industy_avgs_shipped, 4) * 100;
		}
		$industy_avgs['goods_compare'] = $this->attribute_class($goods_compare);
		$industy_avgs['service_compare'] = $this->attribute_class($service_compare);
		$industy_avgs['shipped_compare'] = $this->attribute_class($shipped_compare);
		return $industy_avgs;
	}
	public function attribute_class($value)
	{
		$class = '';
		$name = '';
		if ($value > 0) {
			$class = 'high';
			$name = Lang::get('high');
		} elseif ($value < 0) {
			$class = 'low';
			$value = abs($value);
			$name = Lang::get('low');
		} else {
			$class = 'equal';
			$name = Lang::get('equal');
		}
		return array('value' => $value, 'class' => $class, 'name' => $name);
	}
	public function update_dynamic_evaluation($type = 'goods_evaluation', $store_id)
	{
		$ordergoods_mod =& m('ordergoods');
		$info = $ordergoods_mod->find(array('join' => 'belongs_to_order', 'conditions' => "seller_id={$store_id} AND evaluation_status=1 AND is_valid=1", 'fields' => $type));
		$order_count = count($info);
		$total_evaluation = 0;
		if (!empty($info)) {
			foreach ($info as $key => $val) {
				$total_evaluation = $total_evaluation + $val[$type];
			}
		}
		$order_count > 0 && ($avg_evaluation = round($total_evaluation / $order_count, 2));
		return $avg_evaluation ? $avg_evaluation : 0;
	}
	public function get_order_relative_info($goods_id, $condition, $count = false, $limit = '')
	{
		$order_mod =& m('order');
		$member_mod =& m('member');
		$ordergoods_mod =& m('ordergoods');
		if ($limit) {
			$lm = ' LIMIT ' . $limit;
		}
		$comments = $ordergoods_mod->getAll("SELECT buyer_id, buyer_name, anonymous, evaluation_time, comment, evaluation,goods_evaluation,reply_content,reply_time,portrait FROM {$ordergoods_mod->table} AS og LEFT JOIN {$order_mod->table} AS ord ON og.order_id=ord.order_id LEFT JOIN {$member_mod->table} AS m ON ord.buyer_id=m.user_id WHERE goods_id = '{$goods_id}' AND evaluation_status = '1'" . $condition . ' ORDER BY evaluation_time desc ' . $lm);
		if ($count) {
			return count($comments);
		} else {
			return $comments;
		}
	}
	public function Jd_widget_get_goods_list($options, $num = 1, $amount = 10)
	{
		$goods_list = array();
		$recom_mod =& m('recommend');
		for ($i = 1; $i <= $num; $i++) {
			$goods_list[$i] = $recom_mod->get_recommended_goods($options['img_recom_id_' . $i], $amount, true, $options['img_cate_id_' . $i], array(), $options['sort_by_' . $i]);
		}
		return $goods_list;
	}
	public function Jd_widget_get_tabs_goods($tabs = array(), $num = 10)
	{
		if (empty($tabs)) {
			return;
		}
		$goods_list = array();
		$recom_mod =& m('recommend');
		foreach ($tabs as $key => $tab) {
			$goods_list[$key]['tab_name'] = $tab['tab_name'];
			$goods_list[$key]['goods'] = $recom_mod->get_recommended_goods($tab['img_recom_id'], $num, true, $tab['img_cate_id'], array(), $tab['sort_by']);
		}
		return $goods_list;
	}
	public function Jd_widget_get_ads($options, $num = 6)
	{
		$ads = array();
		$slides_pos = $options['slides_pos'] && in_array($options['slides_pos'], array(1, 2, 3, 4)) ? $options['slides_pos'] : 2;
		for ($i = 1; $i <= $num; $i++) {
			$ads[$i]['ad_image_url'] = $options['ad' . $i . '_image_url'];
			$ads[$i]['ad_link_url'] = $options['ad' . $i . '_link_url'];
			if ($slides_pos == $i || $slides_pos + 3 == $i) {
				$ads[$i]['pos'] = 1;
			}
		}
		return $ads;
	}
	public function Jd_widget_get_words($words_str = '')
	{
		if (empty($words_str)) {
			return;
		}
		$data = array();
		$words = explode(';', str_replace('；', ';', $words_str));
		foreach ($words as $key => $word) {
			$temp = explode('|', $word);
			$data[$key] = array('name' => $temp[0], 'link' => $temp[1]);
		}
		return $data;
	}
	public function Jd_widget_get_brand_list($tag, $amount = 10)
	{
		$amount = !empty($amount) ? intval($amount) : 10;
		$brand_list = array();
		$brand_mod =& m('brand');
		$tag && ($conditions = 'tag= \'' . $tag . '\' AND ');
		$brand_list = $brand_mod->find(array('conditions' => $conditions . '  if_show = 1 AND recommended= 1 ', 'limit' => $amount));
		return $brand_list;
	}
	public function Jd_article_get_data($options)
	{
		$acategory_mod =& m('acategory');
		$cate_ids = $acategory_mod->get_descendant($options['cate_id']);
		if ($cate_ids) {
			$conditions = ' AND cate_id ' . db_create_in($cate_ids);
		} else {
			$conditions = '';
		}
		return $conditions;
	}
	public function Jd_share_get_comment()
	{
		$order_mod =& m('order');
		$ordergoods =& m('ordergoods');
		$goods_list = $ordergoods->find(array('conditions' => 'comment != \'\' ', 'limit' => 10, 'order' => 'order_id desc', 'fields' => 'order_id,goods_id,goods_name,comment,goods_image'));
		if ($goods_list) {
			foreach ($goods_list as $key => $val) {
				empty($val['goods_image']) && ($goods_list[$key]['goods_image'] = Conf::get('default_goods_image'));
				$order_info = $order_mod->get(array('conditions' => $val['order_id'], 'join' => 'belongs_to_user', 'fields' => 'buyer_id,buyer_name,portrait'));
				$goods_list[$key]['buyer_name'] = cut_str($order_info['buyer_name']);
				$goods_list[$key]['portrait'] = portrait($val['buyer_id'], $order_info['portrait'], 'middle');
			}
		}
		return $goods_list;
	}
	public function dpt($flow, $type, $params = array(), $is_new = false)
	{
		static $depopay_type = array();
		$hash = md5($flow . $type . var_export($params, true));
		if ($is_new || empty($depopay_type) || !isset($depopay_type[$hash])) {
			$base_file = ROOT_PATH . '/includes/depopay.base.php';
			$flow_file = ROOT_PATH . '/includes/depopaytypes/' . $flow . '.depopay.php';
			$type_file = ROOT_PATH . '/includes/depopaytypes/' . $type . '.' . $flow . '.php';
			if (!is_file($base_file) || !is_file($flow_file) || !is_file($type_file)) {
				return false;
			}
			include_once $base_file;
			include_once $flow_file;
			include_once $type_file;
			$class_name = ucfirst($type) . ucfirst($flow);
			$depopay_type[$hash] = new $class_name($params);
		}
		return $depopay_type[$hash];
	}
	public function _get_platform_payment($code, $payment_info)
	{
		include_once ROOT_PATH . '/includes/payment.base.php';
		include ROOT_PATH . '/includes/platform_payments/' . $code . '/' . $code . '.payment.php';
		$class_name = ucfirst($code) . 'Payment';
		return new $class_name($payment_info);
	}
	public function get_order_adjust_rate($order_info)
	{
		$goods_amount_after_adjust = $order_info['goods_amount'];
		$goods_amount_before_adjust = $adjust_fee = 0;
		$ordergoods_mod =& m('ordergoods');
		$ordergoods = $ordergoods_mod->find(array('conditions' => 'order_id=' . $order_info['order_id'], 'fields' => 'price,quantity'));
		foreach ($ordergoods as $goods) {
			$goods_amount_before_adjust += $goods['price'] * $goods['quantity'];
		}
		$adjust_fee = $goods_amount_before_adjust - $goods_amount_after_adjust;
		if ($adjust_fee != 0) {
			if ($goods_amount_before_adjust > 0) {
				$adjust_rate = 1 - round($adjust_fee / $goods_amount_before_adjust, 6);
			} else {
				$adjust_rate = -1;
			}
		} else {
			$adjust_rate = 1;
		}
		return $adjust_rate;
	}
	public function _handle_order_integral_return($order_info, $refund)
	{
		$integral_mod =& m('integral');
		$order_integral_mod =& m('order_integral');
		if ($order_integral = $order_integral_mod->get($order_info['order_id'])) {
			if ($order_integral['frozen_integral'] > 0) {
				if ($refund['goods_fee'] == $refund['refund_goods_fee']) {
					$integral_mod->update_integral($order_info['buyer_id'], '', $order_integral['frozen_integral'], false);
				} else {
					$integral_mod->add_integral_log($order_info['buyer_id'], $order_info['order_id'], BUYING_GIVE_INTEGRAL, -$order_integral['frozen_integral']);
					$integral_mod->update_integral($order_info['seller_id'], BUYING_EXCHANGE_INTEGRAL, $order_integral['frozen_integral'], true, $order_info['order_id']);
				}
				$order_integral_mod->drop($order_info['order_id']);
			}
		}
		if ($refund['goods_fee'] > $refund['refund_goods_fee']) {
			$chajia = $refund['goods_fee'] - $refund['refund_goods_fee'];
			$sgrade_integral_mod =& m('sgrade_integral');
			$store_mod =& m('store');
			$store_integral_rate = array();
			$store = $sgrade_integral_mod->getAll("SELECT si.buy_integral FROM {$sgrade_integral_mod->table} si LEFT JOIN {$store_mod->table} s ON si.sgrade_id=s.sgrade WHERE store_id=" . $order_info['seller_id']);
			$store_integral_rate = current($store);
			$buy_integral = round($chajia * $store_integral_rate['buy_integral'] / 100, 2);
			if ($buy_integral) {
				$integral_mod->update_integral($order_info['buyer_id'], BUYING_GET_INTEGRAL, $buy_integral, true, $order_info['order_id']);
			}
		}
	}
	public function DepositApp_downloadbill($month)
	{
		$month_times = gmstr2time($month);
		$monthdays = local_date('t', $month_times);
		$dayInMonth = local_date('j', $month_times);
		$begin_month = $month_times;
		$end_month = $month_times + ($monthdays - $dayInMonth) * 24 * 3600;
		return array($begin_month, $end_month);
	}
	public function DepositApp_recharge($payment_code, $payment_info, $data)
	{
		$deposit_record_mod =& m('deposit_record');
		$tradesn = $deposit_record_mod->_gen_trade_sn();
		$payment = $this->_get_platform_payment($payment_code, $payment_info);
		$data += array('tradesn' => $tradesn);
		$payment_form = $payment->get_payform($data);
		return array($payment_form, $tradesn, $payment);
	}
	public function Delivery_templateModel_format_template($region_mod, $delivery_template, $need_dest_ids = false)
	{
		if (!is_array($delivery_template)) {
			return array();
		}
		$data = $deliverys = array();
		foreach ($delivery_template as $template) {
			$data = array();
			$data['template_id'] = $template['template_id'];
			$data['name'] = $template['name'];
			$data['created'] = $template['created'];
			$template_types = explode(';', $template['template_types']);
			$template_dests = explode(';', $template['template_dests']);
			$template_start_standards = explode(';', $template['template_start_standards']);
			$template_start_fees = explode(';', $template['template_start_fees']);
			$template_add_standards = explode(';', $template['template_add_standards']);
			$template_add_fees = explode(';', $template['template_add_fees']);
			$i = 0;
			foreach ($template_types as $key => $type) {
				$dests = explode(',', $template_dests[$key]);
				$start_standards = explode(',', $template_start_standards[$key]);
				$start_fees = explode(',', $template_start_fees[$key]);
				$add_standards = explode(',', $template_add_standards[$key]);
				$add_fees = explode(',', $template_add_fees[$key]);
				foreach ($dests as $k => $v) {
					//---www.360cd.cn  Mosquito---
					$data['area_fee'][$i] = array('type' => $type, 'dests' => $region_mod->get_region_name($v, false, false), 'start_standards' => $start_standards[$k], 'start_fees' => $start_fees[$k], 'add_standards' => $add_standards[$k], 'add_fees' => $add_fees[$k]);
					if ($need_dest_ids) {
						$data['area_fee'][$i]['dest_ids'] = $v;
					}
					$i++;
				}
			}
			$deliverys[] = $data;
		}
		return $deliverys;
	}
	public function Delivery_templateModel_format_template_foredit($delivery_template, $region_mod)
	{
		$data[] = $delivery_template;
		$delivery = $this->Delivery_templateModel_format_template($region_mod, $data, true);
		$delivery = current($delivery);
		$area_fee_list = array();
		foreach ($delivery['area_fee'] as $key => $val) {
			$type = $val['type'];
			$area_fee_list[$type][] = $val;
		}
		$delivery['area_fee'] = $area_fee_list;
		foreach ($delivery['area_fee'] as $key => $val) {
			$default_fee = true;
			foreach ($val as $k => $v) {
				if ($default_fee) {
					$delivery['area_fee'][$key]['default_fee'] = $v;
					$default_fee = false;
				} else {
					$delivery['area_fee'][$key]['other_fee'][] = $v;
				}
				unset($delivery['area_fee'][$key][$k]);
			}
		}
		return $delivery;
	}
	public function lefttime($time)
	{
		$lefttime = $time - gmtime();
		if (empty($time) || $lefttime <= 0) {
			return array();
		}
		$d = intval($lefttime / 86400);
		$lefttime -= $d * 86400;
		$h = intval($lefttime / 3600);
		$lefttime -= $h * 3600;
		$m = intval($lefttime / 60);
		$lefttime -= $m * 60;
		$s = $lefttime;
		return array('d' => $d, 'h' => $h, 'm' => $m, 's' => $s);
	}
}
// $domain = new Limit_domain();
// if (isset($_GET['psmb_command']) && $_GET['psmb_command'] == 'show_domain') {
// echo 'ORDER_ID:' . $domain->order_id . '<br>';
// echo 'current_domain:<br>';
// print_r($domain->get_current_domain());
// echo '<br>remote_domain:<br>';
// print_r($domain->get_remote_domain());
// die;
// }
// if (isset($_GET['psmb_command']) && $_GET['psmb_command'] == 'show_license') {
// $domain->show_license();
// die;
// }
// if (isset($_GET['psmb_command']) && $_GET['psmb_command'] == 'create_license') {
// $domain->create_license();
// die;
// }
// if ($domain->check_domain === true) {
// $domain->check_domain_allow();
// }
class Limit_domain
{
	public $gateway = 'http://www.baidu.com';
	public $notice = 'If you see this page, Means that your license file has expired! ';
	public $license_key = 'apbscmdbe&&*^%^&*jhkio^%&**({})----()';
	public $license_url = '';
	public $license_file = '';
	public $check_domain = true;
	public $order_id = '644092197';
	public function __construct()
	{
		$this->license_url = $this->gateway . '/license.php?id=' . $this->order_id;
		$this->license_key = $this->license_key . $this->order_id;
		$this->license_file = ROOT_PATH . '/data/license.lock';
	}
	public function check_domain_allow()
	{
		$find = true;
		if (!file_exists($this->license_file)) {
			if ($this->create_license_file()) {
				$find = true;
			}
		} else {
			$license = file_get_contents($this->license_file);
			$array = explode(md5($this->license_key), $license);
			if ($array[0] != md5(date('YmdH') . $this->license_key)) {
				if ($this->create_license_file()) {
					$find = true;
				}
			} else {
				unset($array[0], $array[1]);
				$authorize_domain = array_values($array);
				$current_domain_list = $this->get_current_domain();
				$disable = $allow = array();
				if (!empty($authorize_domain[0])) {
					$disable = explode(md5($this->license_key . 'domain'), $authorize_domain[0]);
				}
				if (!empty($authorize_domain[1])) {
					$allow = explode(md5($this->license_key . 'domain'), $authorize_domain[1]);
				}
				foreach ($current_domain_list as $key => $val) {
					$current_domain = md5($val . $this->license_key);
					if (in_array($current_domain, $allow) && !in_array($current_domain, $disable)) {
						$find = true;
						break;
					}
				}
			}
		}
		if ($find === false) {
			die($this->notice . 'error_code:' . $this->order_id);
		}
	}
	public function get_current_domain()
	{
		$address = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
		$parsed_url = parse_url($address);
		if (isset($parsed_url['host'])) {
			$check = $this->esip($parsed_url['host']);
			$host = $parsed_url['host'];
		} else {
			$check = $this->esip($address);
			$host = $address;
		}
		$domain = array();
		if ($check == FALSE) {
			if ($host != '') {
				$domain[] = $this->domain($host);
				$domain[] = $this->domain_second($host);
			} else {
				$domain[] = $this->domain($address);
				$domain[] = $this->domain_second($address);
			}
		} else {
			$domain[] = $host;
		}
		$domain[] = $this->domain_three();
		$current_domain = array_values(array_unique($domain));
		return $current_domain;
	}
	public function get_remote_domain()
	{
		$license_txt = $this->get_url_contents($this->license_url);
		if ($license_txt == '') {
			return array();
		}
		$data = @unserialize($license_txt);
		return $data;
	}
	public function domain_second($address)
	{
		preg_match('@^(?:http://)?([^/]+)@i', $address, $matches);
		$host = $matches[1];
		preg_match('/[^.]+\\.[^.]+$/', $host, $matches);
		return $matches[0];
	}
	public function domain_three()
	{
		$site_url = SITE_URL;
		if (empty($site_url)) {
			$site_url = site_url();
		}
		$domain = str_replace('https://', '', str_replace('http://', '', $site_url));
		$domain = explode('/', $domain);
		return $domain[0];
	}
	public function create_license_file()
	{
		$license = $this->get_license();
		return file_put_contents($this->license_file, $license);
	}
	public function show_license()
	{
		$license = $this->get_license();
		echo $license;
	}
	public function create_license()
	{
		if ($this->create_license_file()) {
			echo 'create ok';
		} else {
			echo 'create fail';
		}
	}
	public function get_license()
	{
		$license_txt = $this->get_url_contents($this->license_url);
		if ($license_txt == '') {
			return true;
			die;
		}
		$data = @unserialize($license_txt);
		if (!is_array($data) || !isset($data['allow'])) {
			return true;
			die;
		}
		$allow_domain = $disable_domain = $orderId = '';
		if (isset($data['allow']) && !empty($data['allow'])) {
			$allow = explode(',', $data['allow']);
			foreach ($allow as $key => $val) {
				$allow_domain .= md5($this->license_key . 'domain') . md5($val . $this->license_key);
			}
		}
		if (isset($data['disable']) && !empty($data['disable'])) {
			$disable = explode(',', $data['disable']);
			foreach ($disable as $key => $val) {
				$disable_domain .= md5($this->license_key . 'domain') . md5($val . $this->license_key);
			}
		}
		$limit_time = md5(date('YmdH') . $this->license_key);
		$orderId = md5($this->license_key) . md5($this->order_id);
		$allow_domain = md5($this->license_key) . substr($allow_domain, 32);
		$disable_domain = md5($this->license_key) . substr($disable_domain, 32);
		$new_license = $limit_time . $orderId . $disable_domain . $allow_domain;
		return $new_license;
	}
	public function esip($ip_addr)
	{
		if (preg_match('/^(\\d{1,3})\\.(\\d{1,3})\\.(\\d{1,3})\\.(\\d{1,3})$/', $ip_addr)) {
			$parts = explode('.', $ip_addr);
			foreach ($parts as $ip_parts) {
				if (intval($ip_parts) > 255 || intval($ip_parts) < 0) {
					return FALSE;
				}
			}
			return TRUE;
		} else {
			return FALSE;
		}
	}
	public function domain($domainb)
	{
		$bits = explode('/', $domainb);
		if ($bits[0] == 'http:' || $bits[0] == 'https:') {
			$domainb = $bits[2];
		} else {
			$domainb = $bits[0];
		}
		unset($bits);
		$bits = explode('.', $domainb);
		$idz = count($bits);
		$idz -= 3;
		if (strlen($bits[$idz + 2]) == 2) {
			$url = $bits[$idz] . '.' . $bits[$idz + 1] . '.' . $bits[$idz + 2];
		} else {
			if (strlen($bits[$idz + 2]) == 0) {
				$url = $bits[$idz] . '.' . $bits[$idz + 1];
			} else {
				$url = $bits[$idz + 1] . '.' . $bits[$idz + 2];
			}
		}
		return $url;
	}
	public function get_url_contents($url)
	{
		if (function_exists('file_get_contents')) {
			if (ini_get('allow_url_fopen') == '1') {
				return @file_get_contents($url);
			}
		}
		$result = ecm_fopen($url);
		return $result;
	}
}

?>
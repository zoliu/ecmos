<?php

/**
 *
 * 下级管理中心
 *
 * @author Mosquito
 * @link www.360cd.cn
 */
class My_childApp extends MemberbaseApp {
    
    protected $user_id;
    protected $user_model;
    protected $store_model;
    
    function __construct() {
        parent::__construct();
        
        $this->user_id = $this->visitor->get('user_id');
        $this->assign('user_id', $this->user_id);
        $this->user_model = &m('member');
        $this->store_model = &m('store');
    }
    
    /**
     * 总览
     * {@inheritDoc}
     * @see BaseApp::index()
     */
    function index() {
        
        $this->_curlocal(
                LANG::get('member_center'), url('app=member'),
                LANG::get('my_child'), url('app=my_child'),
                LANG::get('child_index')
                );
        $this->_curitem('my_child');
        $this->_curmenu('child_index');
        
        $this->display('my_child.index.html');
    }
    
    /**
     * 下级会员
     */
    function user() {
        
        $query = array();
        $conditions = '1 = 1';
        if (trim($_GET['user_name'])) {
            $query['user_name'] = trim($_GET['user_name']);
            $conditions .= " AND member.user_name LIKE '%{$query['user_name']}%'";
        }
        if (trim($_GET['phone_mob'])) {
            $query['phone_mob'] = trim($_GET['phone_mob']);
            $conditions .= " AND member.phone_mob = '{$query['phone_mob']}'";
        }
        if ($query) {
            $this->assign('filter', true);
        }
        $this->assign('query', $query);
        
        $conditions .= " AND member.parent_id = {$this->user_id}";
        
        $page = $this->_get_page();
        $user_list = $this->user_model->find(array(
            'fields' => 'member.user_id, member.user_name, member.real_name, member.phone_mob, member.reg_time',
            'conditions' => $conditions,
            'limit' => $page['limit'],
            'count' => true,
        ));
        $this->assign('user_list', $user_list);
        
        $page['item_count'] = $this->user_model->getCount();
        $this->_format_page($page);
        $this->assign('page_info', $page);
        
        $this->_curlocal(
                LANG::get('member_center'), url('app=member'),
                LANG::get('my_child'), url('app=my_child'),
                LANG::get('user')
                );
        $this->_curitem('my_child');
        $this->_curmenu('user');
        
        /* //
        $this->import_resource(array(
            'script' => array(
                array(
                    'path' => 'jquery.ui/jquery.ui.js','attr' => ''
                ),array(
                    'path' => 'jquery.ui/i18n/' . i18n_code() . '.js','attr' => ''
                )
            ),'style' => 'jquery.ui/themes/ui-lightness/jquery.ui.css'
        )); */
        
        $this->display('my_child.user.html');
    }
    
    /**
     * 下级会员订单
     */
    function user_order() {
    	
    	$query = array();
    	$conditions = '1 = 1';
    	if (trim($_GET['order_sn'])) {
    		$query['order_sn'] = trim($_GET['order_sn']);
    		$conditions .= " AND order_alias.order_sn LIKE '%{$query['order_sn']}%'";
    	}
    	if ($query) {
    		$this->assign('filter', true);
    	}
    	$this->assign('query', $query);
    	
    	$conditions .= " AND buyer_m.parent_id = {$this->user_id} AND order_alias.status = " . ORDER_FINISHED;
    	
    	$page = $this->_get_page();
    	$order_model = &m('order');
    	$joinstr = $order_model->parseJoin('seller_id', 'user_id', 'member', 'order', 'left', 'seller_m');
    	$joinstr .= $order_model->parseJoin('buyer_id', 'user_id', 'member', 'order', 'left', 'buyer_m');
    	$order_list = $order_model->find(array(
    		'joinstr' => $joinstr,
    		'fields' => 'this.*, seller_m.user_name AS seller_name, seller_m.real_name AS seller_real_name, buyer_m.user_name AS buyer_name, buyer_m.real_name AS buyer_real_name',
    		'conditions' => $conditions,
    		'order' => 'finished_time DESC',
    		'limit' => $page['limit'],
    		'count' => true,
    	));
    	
    	$order_goods_model = &m('ordergoods');
    	foreach ($order_list as $k => $order) {
    		$goods_list = $order_goods_model->find(array(
    			'conditions' => "order_id = {$order['order_id']}",
    		));
    		$order_list[$k]['goods_list'] = $goods_list;
    	}
    	
    	$this->assign('order_list', $order_list);
    	
    	$page['item_count'] = $order_model->getCount();
    	$this->_format_page($page);
    	$this->assign('page_info', $page);
        
        $this->_curlocal(
                LANG::get('member_center'), url('app=member'),
                LANG::get('my_child'), url('app=my_child'),
                LANG::get('user_order')
                );
        $this->_curitem('my_child');
        $this->_curmenu('user_order');
        
        $this->display('my_child.user_order.html');
    }
    
    /**
     * 下级会员提成
     */
    function user_tc() {
        
        $this->_curlocal(
                LANG::get('member_center'), url('app=member'),
                LANG::get('my_child'), url('app=my_child'),
                LANG::get('user_tc')
                );
        $this->_curitem('my_child');
        $this->_curmenu('user_tc');
        
        $this->display('my_child.user_tc.html');
    }
    
    /**
     * 下级商家
     */
    function store() {
        
        $query = array();
        $conditions = '1 = 1';
        if (trim($_GET['store_name'])) {
            $query['store_name'] = trim($_GET['store_name']);
            $conditions .= " AND s.store_name LIKE '%{$query['store_name']}%'";
        }
        if (trim($_GET['tel'])) {
            $query['tel'] = trim($_GET['tel']);
            $conditions .= " AND s.tel = '{$query['tel']}'";
        }
        if ($query) {
            $this->assign('filter', true);
        }
        $this->assign('query', $query);
        
        $conditions .= " AND member.parent_id = {$this->user_id}";
        
        $page = $this->_get_page();
        $joinstr = $this->store_model->parseJoin('store_id', 'user_id', 'member');
        $store_list = $this->store_model->find(array(
            'joinstr' => $joinstr,
            'fields' => 'store.store_id, store.store_name, store.owner_name, store.tel, store.add_time, store.region_name, store.address, member.user_id, member.user_name, member.real_name, member.phone_mob, member.reg_time',
            'conditions' => $conditions,
            'limit' => $page['limit'],
            'count' => true,
        ));
        $this->assign('store_list', $store_list);
        
        $page['item_count'] = $this->user_model->getCount();
        $this->_format_page($page);
        $this->assign('page_info', $page);
        
        $this->_curlocal(
                LANG::get('member_center'), url('app=member'),
                LANG::get('my_child'), url('app=my_child'),
                LANG::get('store')
                );
        $this->_curitem('my_child');
        $this->_curmenu('store');
        
        $this->display('my_child.store.html');
    }
    
    /**
     * 下级商家订单
     */
    function store_order() {
    	
    	$query = array();
    	$conditions = '1 = 1';
    	if (trim($_GET['order_sn'])) {
    		$query['order_sn'] = trim($_GET['order_sn']);
    		$conditions .= " AND order_alias.order_sn LIKE '%{$query['order_sn']}%'";
    	}
    	if ($query) {
    		$this->assign('filter', true);
    	}
    	$this->assign('query', $query);
    	 
    	$conditions .= " AND seller_m.parent_id = {$this->user_id} AND order_alias.status = " . ORDER_FINISHED;
    	 
    	$page = $this->_get_page();
    	$order_model = &m('order');
    	$joinstr = $order_model->parseJoin('seller_id', 'user_id', 'member', 'order', 'left', 'seller_m');
    	$joinstr .= $order_model->parseJoin('buyer_id', 'user_id', 'member', 'order', 'left', 'buyer_m');
    	$order_list = $order_model->find(array(
    		'joinstr' => $joinstr,
    		'fields' => 'this.*, seller_m.user_name AS seller_name, seller_m.real_name AS seller_real_name, buyer_m.user_name AS buyer_name, buyer_m.real_name AS buyer_real_name',
    		'conditions' => $conditions,
    		'order' => 'finished_time DESC',
    		'limit' => $page['limit'],
    		'count' => true,
    	));
    	
    	$order_goods_model = &m('ordergoods');
    	foreach ($order_list as $k => $order) {
    		$goods_list = $order_goods_model->find(array(
    			'conditions' => "order_id = {$order['order_id']}",
    		));
    		$order_list[$k]['goods_list'] = $goods_list;
    	}
    	 
    	$this->assign('order_list', $order_list);
    	 
    	$page['item_count'] = $order_model->getCount();
    	$this->_format_page($page);
    	$this->assign('page_info', $page);
        
        $this->_curlocal(
                LANG::get('member_center'), url('app=member'),
                LANG::get('my_child'), url('app=my_child'),
                LANG::get('store_order')
                );
        $this->_curitem('my_child');
        $this->_curmenu('store_order');
        
        $this->display('my_child.store_order.html');
    }
    
    /**
     * 下级商家提成
     */
    function store_tc() {
        
        $this->_curlocal(
                LANG::get('member_center'), url('app=member'),
                LANG::get('my_child'), url('app=my_child'),
                LANG::get('store_tc')
                );
        $this->_curitem('my_child');
        $this->_curmenu('store_tc');
        
        $this->display('my_child.store_tc.html');
    }
    
    /**
     * 订单详情
     */
    function order_view() {
    	$order_id = intval($_GET['order_id']);
    	
    	echo '暂不需要';
    	exit();
    	
    	/* $order_model = &m('order');
    	$joinstr = $order_model->parseJoin('seller_id', 'store_id', 'store');
    	$joinstr .= $order_model->parseJoin('seller_id', 'user_id', 'member', 'order', 'left', 'seller_m');
    	$joinstr .= $order_model->parseJoin('buyer_id', 'user_id', 'member', 'order', 'left', 'buy_m');
    	$order_info = $order_model->get(array(
    		'joinstr' => $joinstr,
    		'fields' => 'this.*, store.store_name, store.tel, store.region_name, store.address, seller_m.user_name AS seller_name, buy_m.user_name AS buyer_name',
    		'conditions' => "order_id = {$order_id}",
    	));
    	$this->assign('order_info', $order_info);
    	
    	$this->display('my_child.order_view.html'); */
    }
    
    function _get_member_submenu() {
		$menus = array(
			array(
				'name' => 'child_index',
				'url' => '?app=my_child',
			),
			array(
				'name' => 'user',
				'url' => '?app=my_child&act=user',
			),
		    array(
		        'name' => 'user_order',
		        'url' => '?app=my_child&act=user_order',
		    ),
		    /* array(
		        'name' => 'user_tc',
		        'url' => '?app=my_child&act=user_tc',
		    ), */
			array(
				'name' => 'store',
				'url' => '?app=my_child&act=store',
			),
		    array(
		        'name' => 'store_order',
		        'url' => '?app=my_child&act=store_order',
		    ),
		    /* array(
		        'name' => 'store_tc',
		        'url' => '?app=my_child&act=store_tc',
		    ), */
		);
		
		return $menus;
	}
}
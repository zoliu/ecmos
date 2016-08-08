<?php

class Wap_defaultApp extends MallbaseApp {

	function index() {
		$goods_list = $this->get_recommend();
		
		$this->assign('goods_list', $goods_list);
		// var_dump($goods_list);exit();
		$this->assign('nav_list', $this->get_navs());
		$this->assign('nav_ads', $this->nav_ads());
		
		$this->display('index.html');
	}
	
	// 得到分类
	function get_recommend() {
		$this->_wapindex_mod = &m('wapindex');
		$this->_recommend_mod = &m('recommend');
		$recomm = $this->_wapindex_mod->find('cate_id=3 and if_show=1');
		foreach ( $recomm as $key => $value ) {
			$recommend[] = $this->_recommend_mod->_get_recomm_goods($value['recom_id'], $value['gcategory_id'], $value['num']);
		}
		
		return $recommend;
	}
	
	// 得到推荐地址
	function get_navs() {
		$this->_wapindex_mod = &m('wapindex');
		$navs = $this->_wapindex_mod->find('cate_id=2 and if_show=1');
		return $navs;
	}
	
	// 得到幻灯片
	function nav_ads() {
		$this->_wapindex_mod = &m('wapindex');
		$ads = $this->_wapindex_mod->find('cate_id=1 and if_show=1');
		return $ads;
	}
}
?>
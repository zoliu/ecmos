<?php

/**
 * 分佣管理
 * @author Mosquito
 * @link www.360cd.cn
 */
class CommissionApp extends BackendApp {
	
	protected $config;
	protected $filename;
	
	function __construct() {
		parent::__construct();
		
		import('zllib/methods.lib');
		$this->filename = ROOT_PATH . '/data/commission.inc.php';
		$this->config = Methods::load_config($this->filename);
		if (!$this->config) {
			$this->config = array(
				'mall_c_rate' => 0.2,
				'tc_layer' => 3,
			);
			Methods::save_config($this->filename, $this->config);
		}
	}
	
	function index() {
		$this->setting();
	}
	
	function setting() {
		
		if (IS_POST) {
			$data = array();
			$data['mall_c_rate'] = round(floatval($_POST['mall_c_rate']), 4);
			$data['tc_layer'] = intval($_POST['tc_layer']);
			$data['gcate'] = $_POST['gcate'];
			$data['sgrade'] = $_POST['sgrade'];
			
			if ($data['mall_c_rate'] < 0 || $data['mall_c_rate'] > 1) {
				show_warning('商城默认提成错误');
				exit();
			}
			if ($data['tc_layer'] < 0) {
				show_warning('提成层级错误');
				exit();
			}
			foreach ($data['gcate'] as $k => $v) {
				$v = round(floatval($v), 4);
				$data['gcate'][$k] = $v;
				if ($v < 0 || $v > 1) {
					show_warning('商品分类提成错误');
					exit();
				}
			}
			foreach ($data['sgrade'] as $k => $v) {
				$v = round(floatval($v), 4);
				$data['sgrade'][$k] = $v;
				if ($v < 0 || $v > 1) {
					show_warning('店铺等级抵扣错误');
					exit();
				}
			}
			
			$this->config = array_merge($this->config, $data);
			Methods::save_config($this->filename, $this->config);
			
			show_message('操作成功');
		}
		else {
			$this->assign('config', $this->config);
			
			$gcategory_model = &m('gcategory');
			$this->assign('gcate_options', $gcategory_model->get_options(0, false, 'store_id = 0'));
			
			$sgrade_model = &m('sgrade');
			$this->assign('sgrade_options', $sgrade_model->get_options());
			
			$this->display('commission.setting.html');
		}
	}
}
<?php
class baseDs extends FrontendApp {
	function __construct() {
		# code...
	}
	private $_var = array();
	private $debug = false;

	var $_ttl = 3600;

	function init($params = array()) {
		if (!$this->checkArrayEmpty($params)) {
			return;
		}
		$key = $this->getCacheKey($params);
		$this->debug($params, 1); //开启调试检测
		$data = $this->getByKey($key);
		if ($data === false) {
			$data = $this->run($params);
			$this->setByKey($key, $data);
		}
		if (isset($params['return']) && !empty($params['return'])) {
			if (!empty($data)) {
				$this->assign($params['return'], $data);
			}
		}
		$this->debug($params, 0); //关闭调试检测
	}
	function debug($params, $state = 0) {
		if (isset($params['debug'])) {
			$this->debug = $state;
			if ($params['debug'] == 'sql') {
				global $debug_db;
				$debug_db = $state;
			}
		}
	}
	/**
	 * 生成缓存key
	 * @param  array $params 传入数据
	 * @return [type]         [description]
	 */
	function getCacheKey($params) {
		$keys = array();
		if (is_array($params) && count($params) > 0) {
			foreach ($params as $k => $v) {
				if (!is_array($v) && !is_object($v)) {
					$keys[] = $v;
				}
			}
		}
		return count($keys) > 0 ? $keys = implode('-', $keys) : '';
	}

	function run($params = array()) {
		$this->_init($params);
		if (isset($params['type']) && !empty($params['type'])) {
			$act = 'ds' . ucfirst($params['type']);
			return method_exists($this, $act) ? $this->$act($params) : null;
		}
		return $this->index($params);
	}

	function _init($params) {

	}

	function index($params) {

	}

	function assign($var, $val) {
		$this->_var[$var] = $val;
	}

	function getVar() {
		return $this->_var;
	}

	function getByKey($key) {
		if ($this->debug) {
			return false;
		}
		$cache_server = &cache_server();
		return $data = $cache_server->get($key);
	}

	function setByKey($key, $data, $_ttl = 0) {
		$_ttl == 0 ? $_ttl = $this->_ttl : null;
		$cache_server = &cache_server();
		$cache_server->set($key, $data, $_ttl);
	}
	function checkArrayEmpty($arr) {
		if (is_array($arr) && count($arr) > 0) {
			return true;
		}
		return false;
	}

}

?>
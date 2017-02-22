<?php
function _array_keys($collection) {
	if (!is_object($collection) && !is_array($collection)) {
		throw new Exception('Invalid object');
	}
	return array_keys((array) $collection);
}
//得到从0开始的指定长度的数组内容
function _array_first($collection, $n = null) {
	$item = array_splice($collection, 0, $n, true);
	return is_null($n) ? current($item) : $item;
}
//遍历数组
function _array_map($data = null, $iterator = null) {
	$newdata = array();
	if (is_array($data) && count($data) > 0) {
		foreach ($data as $k => $v) {
			if (is_callable($iterator)) {
				$newdata[] = call_user_func($iterator, $v, $k, $data);
			}
		}
	}
	return $newdata;
}

//360cd.cn
function isNotEmpty($var) {
	return isset($var) && !empty($var);
}

function arrayIsNotEmpty($value) {
	return isset($value) && is_array($value) && count($value) > 0;
}

function get($name) {
	return isNotEmpty($_GET[$name]) ? $_GET[$name] : null;
}

function post($name) {
	return isNotEmpty($_POST[$name]) ? $_POST[$name] : null;
}

function isWeiXin() {
	if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
		return true;
	}
	return false;

}

//从数组中提取出select,options专用数据
function array_to_options($data, $key, $val) {
	$items = array();
	if (is_array($data) && count($data) > 0) {
		foreach ($data as $k => $v) {
			$items[$v[$key]] = $v[$val];
		}
	}
	return $items;
}

function uploadImage($form_el, $tpath = '', $file_name = '') {
	import('uploader.lib');
	$file = $_FILES[$form_el];
	if ($file['error'] == UPLOAD_ERR_OK) {
		$uploader = new Uploader();
		$uploader->allowed_type(IMAGE_FILE_TYPE);
		$uploader->addFile($file);

		$uploader->root_dir(ROOT_PATH);

		if (class_exists('FrontendApp') || !empty($_GET['module'])) {
			$pre_path = '';
		} else {
			$pre_path = '../';
		}
		$path = 'data/files/mall/common';
		if (!empty($tpath)) {
			$dir = $path . '/' . $tpath;
			if (!file_exists($dir)) {
				ecm_mkdir($dir);
			}
			$path = $dir;
		}
		$file_name = empty($file_name) ? $uploader->random_filename() : $file_name;
		$newpath = $uploader->save($path, $file_name);

		return $pre_path . $newpath;

	}
	return '';
}

function show_time($code = '') {
	!empty($code) ? $code = $code . '----' : '';
	echo $code . ecm_microtime() . "<br>";
}

function uploadFile($form_el, $file_exts = 'doc|xls|docx|txt', $tpath = '', $file_name = '') {
	import('uploader.lib');
	$file = $_FILES[$form_el];
	if ($file['error'] == UPLOAD_ERR_OK) {
		$uploader = new Uploader();
		$uploader->allowed_type($file_exts);
		$uploader->addFile($file);

		$uploader->root_dir(ROOT_PATH);

		if (class_exists('FrontendApp') || !empty($_GET['module'])) {
			$pre_path = '';
		} else {
			$pre_path = '../';
		}
		$path = 'data/files/mall/common';
		if (!empty($tpath)) {
			$dir = $path . '/' . $tpath;
			if (!file_exists($dir)) {
				ecm_mkdir($dir);
			}
			$path = $dir;
		}
		$file_name = empty($file_name) ? $uploader->random_filename() : $file_name;
		$newpath = $uploader->save($path, $file_name);

		return $pre_path . $newpath;

	}
	return '';
}
//导入数组文件
function include_array($path) {
	return file_exists($path) ? include $path : null;
}

//导出数组到文件
function save_array($path, $array) {
	file_put_contents($path, "<?php\nreturn " . var_export($array, 1) . ";\n?>");
}

function rand_code($length) {
	$pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
	for ($i = 0; $i < $length; $i++) {
		$key .= $pattern{mt_rand(0, 35)}; //生成php随机数
	}
	return $key;
}

if (!function_exists('getRawPostData')) {
	function getRawPostData() {
		return $GLOBALS["HTTP_RAW_POST_DATA"];
	}
}

if (!function_exists('write_log')) {
	function write_log($data, $is_append = 1) {
		$path = 'log.txt';
		$str = chr(9) . chr(10) . chr(9) . chr(10) . date('Y-m-d H:i:s') . '----------------------------------------------------' . chr(9) . chr(10);
		$str .= var_export($data, 1);
		$str .= chr(9) . chr(10) . date('Y-m-d H:i:s') . '----------------------------------------------------' . chr(9) . chr(10);
		if ($is_append) {
			file_put_contents($path, $str, FILE_APPEND);
		} else {
			file_put_contents($path, $str);
		}

	}
}

function get_order_sn($prefix = 'o') {
	$order_prefix = $prefix;
	return $order_prefix . date('YmdHis') . mt_rand(100, 999);
}

function replaceLog($msg, $params = array(), $sign = '#') {
	if (is_array($params) && count($params)) {
		foreach ($params as $k => $v) {
			$msg = str_replace($sign . $k . $sign, $v, $msg);
		}
	}
	return $msg;
}

function errorLog($state, $params = array()) {
	return array_key_exists($state, $params) ? $params[$state] : $state;
}

//发送站内短消息
function sendMsg($user_id, $title, $content) {
	$msg_mod = &m('message');
	return $msg_mod->send(MSG_SYSTEM, $user_id, $title, $content);
}

/**
 * 需要在config.inc.php中开启debug_mod设定值为１
 */
if (isset($_GET['debug']) && DEBUG_MODE) {
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
}
/**
 * 调用系统现成的类文件zllib/下的文件或目录
 * @param string $name 类文件与文件名
 */
function SL($name) {
	static $libs = array();
	if (array_key_exists($name, $libs)) {
		return $libs[$name];
	} else {
		if (class_exists(ucfirst($name))) {
			$name = ucfirst($name);
		}
		return $libs[$name] = new $name();
	}
}

/**
 * 对系统MODEL的调用，是为了将来兼容
 * @param string $name model名称
 */
function LM($name) {
	$model = &m($name);
	return $model;
}

/**
 * 自动载入类用于载入系统类
 * @param  string $classname 类名称
 * @return void            无返回，仅仅载入
 */
function __autoload($classname) {
	$load = array(
		'zllib/' . strtolower($classname) . '/' . $classname . '.lib.php',
		'zllib/' . $classname . '.lib.php',
	);
	$path = ROOT_PATH . '/includes/libraries/';
	foreach ($load as $key => $value) {
		$file = $path . strtolower($value);
		if (file_exists($file)) {
			require_once $file;
			return;
		}
	}
}

/**
 * 得到GUID
 * @param  string $glue 可控参数
 * @return string       GUID
 */
function get_guid($glue = '') {
	$charid = strtoupper(md5(uniqid(mt_rand(), true)));
	$hyphen = $glue;
	$guid = substr($charid, 6, 2) . substr($charid, 4, 2) . substr($charid, 2, 2) . substr($charid, 0, 2) . $hyphen . substr($charid, 10, 2) . substr($charid, 8, 2) . $hyphen . substr($charid, 14, 2) . substr($charid, 12, 2) . $hyphen . substr($charid, 16, 4) . $hyphen . substr($charid, 20, 12);
	return $guid;
}
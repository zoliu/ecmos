<?php

/**
 *    控制器基础类
 *
 *    @author    Garbin
 *    @usage    none
 */
class BaseApp extends Object {
	/* 建立到视图的链接 */
	var $_view = null;

	function __construct() {
		$this->BaseApp();
	}

	function BaseApp() {
		/* 初始化Session */
		$this->_init_session();
	}

	/**
	 *    运行指定的动作
	 *
	 *    @author    Garbin
	 *    @param    none
	 *    @return    void
	 */
	function do_action($action) {
		if ($action && $action{0} != '_' && method_exists($this, $action)) {
			$this->_curr_action = $action;
			$this->_run_action(); //运行动作
		} else {
			exit('missing_action');
		}
	}
	function index() {
		exit('HACKER ATTEPMT!');
	}

	/**
	 *    给视图传递变量
	 *
	 *    @author    Garbin
	 *    @param     string $k
	 *    @param     mixed  $v
	 *    @return    void
	 */
	function assign($k, $v = null) {
		$this->_init_view();
		if (is_array($k)) {
			$args = func_get_args();
			foreach ($args as $arg) //遍历参数
			{
				foreach ($arg as $key => $value) //遍历数据并传给视图
				{
					$this->_view->assign($key, $value);
				}
			}
		} else {
			$this->_view->assign($k, $v);
		}
	}

	/**
	 *    显示视图
	 *
	 *    @author    Garbin
	 *    @param     string $n
	 *    @return    void
	 */
	function display($n) {
		$this->_init_view();
		$this->_view->display($n);
	}

	function fetch($n) {
		$this->_init_view();
		return $this->_view->fetch($n);
	}
	/**
	 *    初始化视图连接
	 *
	 *    @author    Garbin
	 *    @param    none
	 *    @return    void
	 */
	function _init_view() {
		if ($this->_view === null) {
			$this->_view = &v();
			$this->_config_view(); //配置
		}
	}

	/**
	 *    配置视图
	 *
	 *    @author    Garbin
	 *    @return    void
	 */
	function _config_view() {
		# code...
	}

	/**
	 *    运行动作
	 *
	 *    @author    Garbin
	 *    @param    none
	 *    @return    void
	 */
	function _run_action() {
		$action = $this->_curr_action;
		$this->$action();
	}

	/**
	 *    初始化Session
	 *
	 *    @author    Garbin
	 *    @param    none
	 *    @return    void
	 */
	function _init_session() {
		import('session.lib');
		$db = &db();
		$this->_session = new SessionProcessor($db, '`ecm_sessions`', '`ecm_sessions_data`', 'ECM_ID');
		define('SESS_ID', $this->_session->get_session_id());
		$this->_session->my_session_start();
	}

	/**
	 *    获取程序运行时间
	 *
	 *    @author:    Garbin
	 *    @param:     int $precision
	 *    @return:    float
	 */
	function _get_run_time($precision = 5) {
		return round(ecm_microtime() - START_TIME, $precision);
	}

	/**
	 *  控制器结束运行后执行
	 *
	 *  @author Garbin
	 *  @return void
	 */
	function destruct() {
	}

	/**
	 * 从csv文件导入
	 *
	 * @param string $filename 文件名
	 * @param bool $header 是否有标题行，如果有标题行，从第二行开始读数据
	 * @param string $from_charset 源编码
	 * @param string $to_charset 目标编码
	 * @param string $delimiter 分隔符
	 * @return array
	 */
	function import_from_csv($filename, $header = true, $from_charset = '', $to_charset = '', $delimiter = ',') {
		if ($from_charset && $to_charset && $from_charset != $to_charset) {
			$need_convert = true;
			import('iconv.lib');
			$iconv = new Chinese(ROOT_PATH . '/');
		} else {
			$need_convert = false;
		}

		$data = array();
		setlocale(LC_ALL, array('zh_CN.gbk', 'zh_CN.gb2312', 'zh_CN.gb18030')); // 解决linux系统fgetcsv解析GBK文件时可能产生乱码的bug
		$handle = fopen($filename, "r");
		while (($row = fgetcsv($handle, 100000, $delimiter)) !== FALSE) {
			if ($need_convert) {
				foreach ($row as $key => $col) {
					$row[$key] = $iconv->Convert($from_charset, $to_charset, $col);
				}
			}
			$data[] = $row;
		}
		fclose($handle);

		if ($header && $data) {
			array_shift($data);
		}

		return addslashes_deep($data);
	}

	/**
	 * 导出csv文件
	 *
	 * @param array $data 数据（如果需要，列标题也包含在这里）
	 * @param string $filename 文件名（不含扩展名）
	 * @param string $to_charset 目标编码
	 */
	function export_to_csv($data, $filename, $to_charset = '') {
		if ($to_charset && $to_charset != 'utf-8') {
			$need_convert = true;
			import('iconv.lib');
			$iconv = new Chinese(ROOT_PATH . '/');
		} else {
			$need_convert = false;
		}

		header("Content-type: application/unknown");
		header("Content-Disposition: attachment; filename={$filename}.csv");
		foreach ($data as $row) {
			foreach ($row as $key => $col) {

				if ($need_convert) {
					$col = $iconv->Convert('utf-8', $to_charset, $col);
				}
				$row[$key] = $this->_replace_special_char($col);

			}
			echo join(',', $row) . "\r\n";
		}
	}

	/**
	 * 替换影响csv文件的字符
	 *
	 * @param $str string 处理字符串
	 */
	function _replace_special_char($str, $replace = true) {
		$str = str_replace("\r\n", "", $str);
		$str = str_replace("\t", "    ", $str);
		$str = str_replace("\n", "", $str);
		if ($replace == true) {
			$str = '"' . str_replace('"', '""', $str) . '"';
		}
		return $str;
	}

}

?>
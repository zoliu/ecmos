<?php

/**
 * ECMALL: 消息控制器
 * ============================================================================
 * 版权所有 (C) 2005-2008 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.shopex.cn
 * -------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Id: message.base.php 12127 2010-10-18 09:58:37Z huibiaoli $
 */
if (!defined('IN_ECM')) {
	trigger_error('Hacking attempt', E_USER_ERROR);
}

/* 设置消息接收 */
set_error_handler('exception_handler');

function _trigger_message($arr) {
	if (count($arr) < 2) {
		$arr[] = Lang::get('go_back');
	}
	if (count($arr) < 3) {
		$arr[] = 'javascript:history.back()';
	}
	$m = '';
	if (!empty($arr[0])) {
		if (is_array($arr[0])) {
			$m = Lang::get('has_error');
			foreach ($arr[0] as $key => $err) {
				$m .= Lang::get($err['msg']) . ($err['obj'] ? '[' . Lang::get($err['obj']) . ']' : '') . '<br />';
			}
		} else {
			$m = Lang::get($arr[0]);
		}
	}
	$a = array('content' => $m, 'links' => array());
	$n = count($arr);
	for ($i = 1; $i < $n; $i += 2) {
		$href = (($i + 1) >= $n) ? 'javascript:history.back()' : $arr[$i + 1];
		//$redirect = (($i + 2) >= $n) ? false : $arr[$i + 2];
		$a['links'][] = array('href' => $href, 'text' => Lang::get($arr[$i]));
	}

	return $a;
}
/**
 * send a system notice message
 *
 * @author wj
 * @param string $msg
 * @return void
 */
function show_message($msg) {
	$a = _trigger_message(func_get_args());

	_message(serialize($a), E_USER_NOTICE);
}

/**
 * send a system warning message
 *
 * @param string $msg
 */
function show_warning($msg) {
	$a = _trigger_message(func_get_args());

	_message(serialize($a), E_USER_WARNING);
}

/**
 * send a system message
 *
 * @author  weberliu
 * @param   string  $msg
 * @param   int     $type
 */
function _message($msg, $type) {
	$msg = new Message($msg, $type);
	$msg->display();
}
/**
 * Exception Handler
 *
 * error:   中断程序，提示信息中出现报告bug的链接以及记录到日志文件
 * warning: 中断程序，提示信息中出现报告bug的链接。
 * notice:  不中断程序，错误信息输出到页面的注释中
 *---------------------------------------
 * 用户级的错误处理方式：
 *
 * error:   中断程序，提示信息中出现报告bug的链接以及记录到日志文件
 * warning: 中断程序，提示信息中不包括出错文件及行号等信息
 * notice:  中断程序，提示信息中不包括出错文件及行号等信息。 如何处理链接地址呢？
 *---------------------------------------
 *
 * @param   number  $errno
 * @param   string  $errstr
 * @param   string  $errfile
 * @param   string  $errline
 *
 * @return  void
 */
function exception_handler($errno, $errstr, $errfile, $errline) {

	if ($errno == 2048 || (($errno & error_reporting()) != $errno)) {
		//不再需要通过_at方法来抵制错误
		//错误被屏蔽时就不抛出异常，该处理就允许你在代码中照常使用error_reporting来控制错误报告
		return true;
	}

	if ($errno != E_NOTICE) {
		$msg = new Message($errstr, $errno);
		$errfile = str_replace(ROOT_PATH, '', $errfile);

		if ($errno != E_USER_WARNING && $errno != E_USER_NOTICE) {
			$msg->err_file = $errfile;
			$msg->err_line = $errline;
		}

		/* add report link */
		if ($errno == E_USER_ERROR || $errno == E_ERROR || $errno == E_PARSE || $errno == E_WARNING) {
			$msg->report_link($errno, $errstr, $errfile, $errline);

			put_log($errno, $errstr, $errfile, $errline); // 写入错误日志

			$msg->display();
			exit;
		} else {
			$msg->display();
		}
	} else if ($errno == E_NOTICE && (defined('DEBUG_MODE') && DEBUG_MODE > 0)) {
		echo "<div style='font: 14px verdana'><b>Notice:</b> $errstr<br/><b>Error File:</b> $errfile: [$errline]</div>";
	}

	return true;
}

/**
 * 写入 log 文件
 *
 * @param   string  $msg
 * @param   string  $file
 * @param   string  $line
 */
function put_log($err, $msg, $file, $line) {
	$filename = ROOT_PATH . "/temp/logs/" . date("Ym") . ".log";

	if (!is_dir('temp/logs')) {
		ecm_mkdir(ROOT_PATH . '/' . 'temp/logs');
	}

	$handler = null;

	if (($handler = fopen($filename, 'ab+')) !== false) {
		fwrite($handler, date('r') . "\t[$err]$msg\t$file\t$line\n");
		fclose($handler);
	}
}

class Message extends MessageBase {
	var $visitor = null;
	var $caption = '';
	var $icon = '';
	var $links = array();
	var $redirect = '';
	var $err_line = '';
	var $err_file = '';

	function __construct($str = '', $errno = null) {
		$this->Message($str, $errno);
	}
	function Message($str, $errno = null) {
		if ($errno == E_USER_ERROR || $errno == E_ERROR || $errno == E_WARNING) {
			$this->icon = "error";
		} else if ($errno == E_USER_WARNING) {
			$this->icon = "warning";
		} else {
			$this->icon = "notice";
		}

		$this->handle_message($str);
		$this->visitor = &env('visitor');
		$this->_session = &env('session');
	}
	function handle_message($msg) {
		/* decode message */
		$arr = @unserialize($msg);

		if ($arr === false) {
			$this->message = nl2br($msg);
		} else {
			foreach ($arr['links'] AS $key => $val) {
				$this->add_link($val['text'], $val['href']);
			}
			$this->message = nl2br($arr['content']);
		}
	}
	/**
	 * 生成bug报告链接
	 *
	 * @author wj
	 * @param string $err  错误类型
	 * @param string $msg 错误信息
	 * @param string $file   出错文件
	 * @param string $line   出错行号
	 * @return  void
	 */
	function report_link($err, $msg, $file, $line) {
		if (strncmp($msg, 'MySQL Error[', 12) == 0) {
			$tmp_arr = explode("\n", $msg, 2);
			$tmp_param = strtr($tmp_arr[0], array('MySQL Error[' => 'dberrno=', ']: ' => '&dberror='));
			parse_str($tmp_param, $tmp_arr);
			$url = 'http:///help/faq.php?type=mysql&dberrno=' . $tmp_arr['dberrno'] . '&dberror=' . urlencode($tmp_arr['dberror']);

			$this->add_link(Lang::get('mysql_error_report'), $url);
		} else {
			$arr_report = array('err' => $err, 'msg' => $msg, 'file' => $file, 'line' => $line, 'query_string' => $_SERVER['QUERY_STRING'], 'occur_date' => local_date('Y-m-d H:i:s'));
			foreach ($arr_report as $k => $v) {
				$arr_report[$k] = $k . chr(9) . $v;
			}
			$str_report = str_replace('=', '', base64_encode(implode(chr(8), $arr_report)));
			$url = 'index.php?app=issue&data=' . $str_report . '&amp;sign=' . md5($str_report . ECM_KEY);

			$this->add_link(Lang::get('report_issue'), $url);
		}

		$this->add_link(Lang::get('go_back'));
	}

	/**
	 * 添加一个链接到消息页面
	 *
	 * @author  weberliu
	 * @param   string  $text
	 * @param   string  $href
	 * @return  void
	 */
	function add_link($text, $href = 'javascript:history.back()') {
		$this->links[] = array('text' => $text, 'href' => $href);

		if ($this->icon == 'notice' && $this->redirect == '') {
			$this->redirect = (strstr($href, 'javascript:') !== false) ? $href : "location.href='{$href}'";
		}
	}

	/**
	 * 显示消息页面
	 *
	 * @author  wj
	 * @return  void
	 */
	function display($f = '') {
		$this->message = str_replace(ROOT_PATH, '', $this->message);

		if (defined('IS_AJAX') && IS_AJAX) {
			$error_line = empty($this->err_file[$this->err_line]) ? '' : "\n\nFile: $this->err_file[$this->err_line]";
			if ($this->icon == "notice") {
				$this->json_result('', $this->message . $error_line);
				return;
			} else {
				$this->json_error($this->message . $error_line);
				return;
			}
		} else {
			if ($this->redirect) {
				$this->redirect = str_replace('&amp;', '&', $this->redirect); //$this->redirect 是给js使用的,不能包含&amp;
			}
			$this->_config_seo('title', Lang::get('ecmall_sysmsg') . '');
			$this->assign('message', $this->message);
			$this->assign('links', $this->links);
			$this->assign('icon', $this->icon);
			$this->assign('err_line', $this->err_line);
			$this->assign('err_file', $this->err_file);
			$this->assign('redirect', $this->redirect);
			restore_error_handler(); //错误提示时将错误捕捉关掉,以免display出错时出现死循环
			parent::display('message.html');
		}
	}
}
?>
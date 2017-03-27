<?php

/* 挂件基础类 */
include ROOT_PATH . '/includes/widget.base.php';

/**
 *    模板编辑控制器
 *
 *    @author    Garbin
 *    @usage    none
 */
class TemplateApp extends BackendApp {
	/* 可编辑的页面列表 */
	function index() {
		$this->assign('pages', $this->_get_editable_pages());
		$this->display('template.index.html');
	}

	/**
	 *    编辑页面
	 *
	 *    @author    Garbin
	 *    @return    void
	 */
	function edit() {
		/* 当前所编辑的页面 */
		$page = !empty($_GET['page']) ? trim($_GET['page']) : null;
		if (!$page) {
			$this->show_warning('no_such_page');

			return;
		}

		/* 注意，通过这种方式获取的页面中跟用户相关的数据都是游客，这样就保证了统一性，所见即所得编辑不会因为您是否已登录而出现不同 */
		$html = $this->_get_page_html($page);
		if (!$html) {
			$this->show_warning('no_such_page');

			return;
		}
		/* 让页面可编辑 */
		$html = $this->_make_editable($page, $html);

		echo $html;
	}

	/**
	 *    保存编辑的页面
	 *
	 *    @author    Garbin
	 *    @return    void
	 */
	function save() {
		/* 初始化变量 */
		/* 页面中所有的挂件id=>name */
		$widgets = !empty($_POST['widgets']) ? $_POST['widgets'] : array();

		/* 页面中所有挂件的位置配置数据 */
		$config = !empty($_POST['config']) ? $_POST['config'] : array();

		/* 当前所编辑的页面 */
		$page = !empty($_GET['page']) ? trim($_GET['page']) : null;
		if (!$page) {
			$this->json_error('no_such_page');

			return;
		}
		$editable_pages = $this->_get_editable_pages();
		if (empty($editable_pages[$page])) {
			$this->json_error('no_such_page');

			return;
		}

		$page_config = get_widget_config(Conf::get('template_name'), $page);

		/* 写入位置配置信息 */
		$page_config['config'] = $config;

		/* 原始挂件信息 */
		$old_widgets = $page_config['widgets'];

		/* 清空原始挂件信息 */
		$page_config['widgets'] = array();

		/* 写入挂件信息,指明挂件ID是哪个挂件以及相关配置 */
		foreach ($widgets as $widget_id => $widget_name) {
			/* 写入新的挂件信息 */
			$page_config['widgets'][$widget_id]['name'] = $widget_name;
			$page_config['widgets'][$widget_id]['options'] = array();

			/* 如果进行了新的配置，则写入 */
			if (isset($page_config['tmp'][$widget_id])) {
				$page_config['widgets'][$widget_id]['options'] = $page_config['tmp'][$widget_id]['options'];

				continue;
			}

			/* 写入旧的配置信息 */
			$page_config['widgets'][$widget_id]['options'] = $old_widgets[$widget_id]['options'];
		}

		/* 清除临时的配置信息 */
		unset($page_config['tmp']);

		/* 保存配置 */
		$this->_save_page_config(Conf::get('template_name'), $page, $page_config);
		$this->json_result('', 'save_successed');
	}

	/**
	 *    获取编辑器面板
	 *
	 *    @author    Garbin
	 *    @return    void
	 */
	function get_editor_panel() {
		/* 获取挂件列表 */
		$widgets = list_widget();
		header('Content-Type:text/html;charset=' . CHARSET);
		$this->assign('widgets', ecm_json_encode($widgets));
		$this->assign('site_url', SITE_URL);
		$this->display('template.panel.html');
	}

	/**
	 *    添加挂件到页面中
	 *
	 *    @author    Garbin
	 *    @return    void
	 */
	function add_widget() {
		$name = !empty($_GET['name']) ? trim($_GET['name']) : null;
		/* 当前所编辑的页面 */
		$page = !empty($_GET['page']) ? trim($_GET['page']) : null;
		if (!$name || !$page) {
			$this->json_error('no_such_widget');

			return;
		}
		$page_config = get_widget_config(Conf::get('template_name'), $page);
		$id = $this->_get_unique_id($page_config);
		$widget = &widget($id, $name, array());
		$contents = $widget->get_contents();
		$this->json_result(array('contents' => $contents, 'widget_id' => $id));
	}

	function _get_unique_id($page_config) {
		$id = '_widget_' . rand(100, 999);
		if (array_key_exists($id, $page_config['widgets'])) {
			return $this->_get_unique_id($page_config);
		}

		return $id;
	}

	/**
	 *    获取挂件的配置表单
	 *
	 *    @author    Garbin
	 *    @return    void
	 */
	function get_widget_config_form() {
		$name = !empty($_GET['name']) ? trim($_GET['name']) : null;
		$id = !empty($_GET['id']) ? trim($_GET['id']) : null;
		/* 当前所编辑的页面 */
		$page = !empty($_GET['page']) ? trim($_GET['page']) : null;
		if (!$name || !$id || !$page) {
			$this->json_error('no_such_widget');

			return;
		}
		$page_config = get_widget_config(Conf::get('template_name'), $page);
		$options = empty($page_config['tmp'][$id]['options']) ? $page_config['widgets'][$id]['options'] : $page_config['tmp'][$id]['options'];
		$widget = &widget($id, $name, $options);
		header('Content-Type:text/html;charset=' . CHARSET);
		$widget->display_config();
	}

	/**
	 *    配置挂件
	 *
	 *    @author    Garbin
	 *    @param    none
	 *    @return    void
	 */
	function config_widget() {
		if (!IS_POST) {
			return;
		}
		$name = !empty($_GET['name']) ? trim($_GET['name']) : null;
		$id = !empty($_GET['id']) ? trim($_GET['id']) : null;
		/* 当前所编辑的页面 */
		$page = !empty($_GET['page']) ? trim($_GET['page']) : null;
		if (!$name || !$id || !$page) {
			$this->_config_respond('_d.setTitle("' . Lang::get('no_such_widget') . '");_d.setContents("message", {text:"' . Lang::get('no_such_widget') . '"});');

			return;
		}
		$page_config = get_widget_config(Conf::get('template_name'), $page);
		$widget = &widget($id, $name, $page_config['widgets'][$id]['options']);
		$options = $widget->parse_config($_POST);
		if ($options === false) {
			$this->json_error($widget->get_error());

			return;
		}
		$page_config['tmp'][$id]['options'] = $options;

		/* 保存配置信息 */
		$this->_save_page_config(Conf::get('template_name'), $page, $page_config);

		/* 返回即时更新的数据 */
		$widget->set_options($options);
		$contents = $widget->get_contents();
		$this->_config_respond('DialogManager.close("config_dialog");parent.disableLink(parent.$(document.body));parent.$("#' . $id . '").replaceWith(document.getElementById("' . $id . '").parentNode.innerHTML);parent.init_widget("#' . $id . '");', $contents);
	}

	/**
	 *    响应配置请求
	 *
	 *    @author    Garbin
	 *    @param    none
	 *    @return    void
	 */
	function _config_respond($js, $widget = '') {
		header('Content-Type:text/html;charset=' . CHARSET);
		echo '<div>' . $widget . '</div>' . '<script type="text/javascript">var DialogManager = parent.DialogManager;var _d = DialogManager.get("config_widget");' . $js . '</script>';
	}

	/**
	 *    保存页面配置文件
	 *
	 *    @author    Garbin
	 *    @param     string $template_name
	 *    @param     string $page
	 *    @param     array  $page_config
	 *    @return    void
	 */
	function _save_page_config($template_name, $page, $page_config) {
		$page_config_file = ROOT_PATH . '/data/page_config/' . $template_name . '.' . $page . '.config.php';
		$php_data = "<?php\n\nreturn " . var_export($page_config, true) . ";\n\n?>";

		return file_put_contents($page_config_file, $php_data, LOCK_EX);
	}

	/**
	 *    获取欲编辑的页面的HTML
	 *
	 *    @author    Garbin
	 *    @param     string $page
	 *    @return    string
	 */
	function _get_page_html($page) {
		$pages = $this->_get_editable_pages();
		if (empty($pages[$page])) {
			return false;
		}

		import('zllib/http.lib');

		return Http::pushUri($pages[$page]);
	}

	/**
	 *    让页面具有编辑功能
	 *
	 *    @author    Garbin
	 *    @param     string $html
	 *    @return    string
	 */
	function _make_editable($page, $html) {
		$real_backend_url = site_url();
		$editmode = '<script type="text/javascript" src="' . $real_backend_url . '/index.php?act=jslang"></script><script type="text/javascript">__PAGE__ = "' . $page . '"; REAL_BACKEND_URL = "' . $real_backend_url . '";</script><script type="text/javascript" src="' . SITE_URL . '/includes/libraries/javascript/jquery.ui/jquery.ui.js"></script><script type="text/javascript" charset="utf-8" src="' . SITE_URL . '/includes/libraries/javascript/jquery.ui/i18n/' . i18n_code() . '.js"></script><script id="dialog_js" type="text/javascript" src="' . SITE_URL . '/includes/libraries/javascript/dialog/dialog.js"></script><script id="template_editor_js" type="text/javascript" src="' . $real_backend_url . '/includes/javascript/template_panel.js"></script><link id="template_editor_css" href="' . $real_backend_url . '/templates/style/template_panel.css" rel="stylesheet" type="text/css" /><link rel="stylesheet" href="' . SITE_URL . '/includes/libraries/javascript/jquery.ui/themes/ui-lightness/jquery.ui.css" type="text/css" media="screen" /><link rel="stylesheet" href="' . SITE_URL . '/includes/libraries/javascript/hack.css" type="text/css" media="screen" />';

		return str_replace('<!--<editmode></editmode>-->', $editmode, $html);
	}

	/**
	 *    获取可以编辑的页面列表
	 *
	 *    @author    Garbin
	 *    @param    none
	 *    @return    void
	 */
	function _get_editable_pages() {
		$real_site_url = dirname(site_url());
		return array(
			'index' => $real_site_url . '/index.php',
			'gcategory' => $real_site_url . '/index.php?app=category',
		);
	}
}

?>
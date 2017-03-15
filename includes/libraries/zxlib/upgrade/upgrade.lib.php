<?php
set_time_limit(0);
import('zxlib/public/curl.lib');
import('zxlib/public/pclzip.lib');
import('zxlib/upgrade/version.lib');
class Upgrade {
	protected $baseUri = "http://ecmcc.360cd.cn/";
	protected $remoteUri = "?app=upgrade&act=down";
	protected $is_upgrade = 0;
	protected $conf;
	protected $upgrade_path;
	protected $upgrade_backup;
	protected $ver;

	function __construct() {
		$this->remoteUri = $this->baseUri . $this->remoteUri;
		$this->ver = new Version($this->baseUri);
		$this->is_upgrade = $this->ver->checkVersion();
		$this->conf = $this->ver->getConf();
		$this->upgrade_path = ROOT_PATH . "/temp/upgrade/";
		$this->upgrade_backup = ROOT_PATH . "/temp/backup/";
	}

	function hasUpgrade() {
		return $this->is_upgrade;
	}

	function currentVersion() {
		return $this->ver->getCurrentVersion();
	}

	function nextVersion() {
		return $this->conf['next_version'];
	}

	function currentSystem() {
		return strtoupper($this->conf['system']);
	}

	function versionRemark() {
		return $this->ver->readVersionRemark();
	}
	function systemRemark() {
		return $this->ver->readSystemRemark();
	}

	function getBaseUri() {
		return $this->baseUri;
	}

	function get_system_info() {
		$upgrade = array(
			'upgrade' => $this->hasUpgrade(),
			'nextVersion' => $this->nextVersion(),
			'remark' => $this->versionRemark(),
			'currentVersion' => $this->currentVersion(), //360cd.cn
			'system' => $this->currentSystem(), //360cd.cn
			'system_remark' => $this->systemRemark(), //360cd.cn
		);
		return $upgrade;
	}

	function down() {
		if ($this->is_upgrade) {
			return $this->install();
		}

		return 0;
	}

	function install() {
		$downUrl = $this->remoteUri . "&appid=" . $this->conf['appid'] . '&appkey=' . $this->conf['appkey'] . '&version=' . $this->conf['system'];
		$data = getUri($downUrl);
		$data = json_decode($data, 1);
		return $data;
	}

	function down_file($url) {
		set_time_limit(0);
		$fp = fopen($url, "r");
		$filename = isDir($this->upgrade_path, ROOT_PATH) . 'update.zip';
		if (file_exists($filename)) {
			@unlink($filename);
		}
		$handle = fopen($filename, "a");
		while (!feof($fp)) {
//测试文件指针是否到了文件结束的位置
			$content = fread($fp, 1024);
			fwrite($handle, $content);
		}
		fclose($fp);
		fclose($handle);

		$zip = new PclZip($filename);
		$zip->extract(PCLZIP_OPT_PATH, ROOT_PATH);
		if ($zip->errorCode() == 0) {
			$this->ver->saveVersion($this->nextVersion());
			$this->install_sql();
			return 1;
		} else {
			return -2; // $zip->errorInfo();
		}
	}

	function install_sql() {
		$path = ROOT_PATH . '/data/upgrade.sql';
		if (!file_exists($path)) {
			return 0;
		}
		$sqls = get_sql($path);
		@unlink($path);
		$db = db();
		if (is_array($sqls) && count($sqls) > 0) {
			foreach ($sqls as $k => $v) {
				$db->query($v);
			}
		}
		@unlink($path);
	}

	function backup() {

		$backup_list = array(
			'admin',
			'api',
			'app',
			'eccore',
			'external',
			'includes',
			'install',
			'languages',
			'themes',
			'admin.php',
			'htaccess.txt',
			'index.php',
		);

		$filename = isDir($this->upgrade_backup) . 'backup.zip';
		if (is_file($filename)) {
			return -1; //备份文件已存在
		}

		$zip = new PclZip($filename);
		$zip->delete();
		foreach ($backup_list as $value) {
			$file = ROOT_PATH . '/' . $value;
			if (is_file($file) || is_dir($file)) {
				$zip->add($file, PCLZIP_OPT_REMOVE_PATH, ROOT_PATH);
			}
		}

		if ($zip->errorCode() == 0) {
			return 1;
		} else {
			return -2; //$zip->errorInfo()
		}
	}

	function restore() {
		$filename = $this->upgrade_backup . 'backup.zip';
		if (!is_file($filename)) {
			return -1; //备份不存在;
		}

		$zip = new PclZip($filename);
		$file_list = $zip->listContent();
		foreach ($file_list as $file) {
			$filename = ROOT_PATH . '/' . $file['filename'];
			if (is_file($filename)) {
				unlink($filename);
			} else if (is_dir($filename)) {
				delDir($filename);
			}
		}
		$zip->extract(PCLZIP_OPT_PATH, ROOT_PATH);
		if ($zip->errorCode() == 0) {
			return 1;
		} else {
			return -2;
		}
	}

}
//读取sql文件到数组;
function get_sql($file) {
	$contents = file_get_contents($file);
	$contents = str_replace("\r\n", "\n", $contents);
	$contents = trim(str_replace("\r", "\n", $contents));
	$return_items = $items = array();
	$items = explode(";\n", $contents);
	foreach ($items as $item) {
		$return_item = '';
		$item = trim($item);
		$lines = explode("\n", $item);
		foreach ($lines as $line) {
			if (isset($line[0]) && $line[0] == '#') {
				continue;
			}
			if (isset($line[1]) && $line[0] . $line[1] == '--') {
				continue;
			}

			$return_item .= $line;
		}
		if ($return_item) {
			$return_items[] = $return_item;
		}
	}

	return $return_items;
}

/**
 * 是否存在目录，不存在则创建
 *
 * @param string $path
 * @return string $path
 *
 * @author Mosquito
 * @link www.360cd.cn
 */
function isDir($path, $parent_path = '') {
	$path_arr = explode('/', str_replace($parent_path, '', $path));
	$path_str = array_shift($path_arr);
	foreach ($path_arr as $k => $v) {
		$path_str .= '/' . $v;
		if (!is_dir($path_str)) {
			@mkdir($parent_path . $path_str);
		}
	}
	return $path;
}

/**
 * 获取目录及子目录下所有文件名
 * @param string $path
 * @param array &$file_list
 * @param string $remove_path
 */
function getDirFile($path, &$file_list, $remove_path = '') {
	if (is_dir($path)) {
		$dp = dir($path);
		while ($file = $dp->read()) {
			if ($file != '.' && $file != '..') {
				getDirFile($path . '/' . $file, $file_list, $remove_path);
			}
		}
		$dp->close();
	} else if (is_file($path)) {
		$file_list[] = str_replace($remove_path, '', $path);
	}
}

/**
 * 删除目录以及目录下所有文件
 *
 * @param string $dir
 */
function delDir($dir) {
	$dh = opendir($dir);
	while ($file = readdir($dh)) {
		if ($file != "." && $file != "..") {
			$fullpath = $dir . "/" . $file;
			if (!is_dir($fullpath)) {
				@unlink($fullpath);
			} else {
				delDir($fullpath);
			}
		}
	}
	@closedir($dh);
	return @rmdir($dir);
}

/**
 * 删除空的目录及子目录
 * @param string $path
 */
function delEmptyDir($path) {
	if (is_dir($path) && ($handle = opendir($path)) != false) {
		while (($file = readdir($handle)) != false) {
			if ($file != '.' && $file != '..') {
				$curfile = $path . '/' . $file;
				if (is_dir($curfile)) {
					delEmptyDir($curfile);
					if (count(scandir($curfile)) == 2) {
						@rmdir($curfile);
					}
				}
			}
		}
		@closedir($handle);
	}
}

/**
 * 拷贝目录文件
 * @param string $source
 * @param string $dest
 */
function copyDir($source, $dest) {
	$dir = opendir($source);

	//目录不存在则创建
	$path_arr = explode('/', $dest);
	$path_str = array_shift($path_arr);
	foreach ($path_arr as $k => $v) {
		$path_str .= '/' . $v;
		if (!is_dir($path_str)) {
			@mkdir($path_str);
		}
	}

	//
	while (false != ($file = readdir($dir))) {
		if (($file != '.') && ($file != '..')) {
			if (is_dir($source . '/' . $file)) {
				copyDir($source . '/' . $file, $dest . '/' . $file);
				continue;
			} else {
				@copy($source . '/' . $file, $dest . '/' . $file);
			}
		}
	}
	@closedir($dir);
}

?>
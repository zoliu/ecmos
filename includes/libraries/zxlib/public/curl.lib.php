<?php
/**
 *
 * curl远程交互资源
 *
 * @param string $url        	
 * @param string $action        	
 * @param array $data        	
 *
 * @author born
 * @link www.360cd.cn
 */
function getUri($url, $action = 'GET', $data = array()) {
	ini_set('open_basedir', '');
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $action);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
	if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off')) {
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	}
	curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
	$action = strtoupper($action);
	$action == 'POST' ? curl_setopt($ch, CURLOPT_POSTFIELDS, $data) : null;
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$tmpInfo = curl_exec($ch);
	if (curl_errno($ch)) {
		echo curl_error($ch);
		return null;
	}
	curl_close($ch);
	$json_data = $tmpInfo;
	
	return $json_data;
}

?>
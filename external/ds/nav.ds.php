<?php
/**
 * {ds name=nav type=header|middle|footer num=? }
 * 导航类
 */
class NavDs extends baseDs {
	function dsHeader($params) {
		return LM('navigation')->where("type='header'")->orderBy('sort_order')->limit($params['num'])->find();
	}
	function dsMiddle($params) {
		return LM('navigation')->where("type='middle'")->orderBy('sort_order')->limit($params['num'])->find();
	}
	function dsFooter($params) {
		return LM('navigation')->where("type='footer'")->orderBy('sort_order')->limit($params['num'])->find();
	}
}

?>
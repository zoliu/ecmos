<?php
class Member {

	/**
	 * 修改用户信息如果信息不存在则添加
	 * @param  array $data 数组
	 * @return intval       返回值
	 */
	function modify_user($data) {
		if (!$data) {
			return 0;
		}
		$user_id = intval($data['user_id']);
		unset($data['user_id']);
		if ($user_id > 0) {
			$temp = LM('member')->edit($user_id, $data);
			if (!$temp) {
				return $temp;
			}
			return $user_id;
		} else {
			$temp = LM('member')->add($data);
			return $temp;
		}
	}
	//手机号中间星号
	function phoneHide($phone) {
		if (!empty($phone)) {
			$phone = substr_replace($phone, '*****', 3, 5);
		}
		return $phone;
	}
	//邮箱中间星号
	function emailHide($email) {
		if (!empty($email)) {
			$arr = explode('@', $email);
			if (is_array($arr)) {
				$rest = substr($arr[0], 3, -2);
				$arr[0] = str_replace($rest, str_repeat('*', strlen($rest)), $arr[0]);
				$email = implode('@', $arr);
			}
		}
		return $email;
	}
	/**
	 * 用户*号
	 * @param  string $user_name 用户名
	 * @return string            *号处理后的用户名
	 */
	function userHide($user_name) {
		$len = strlen($user_name) / 2;
		return substr_replace($user_name, str_repeat('*', $len), ceil(($len) / 2), $len);
	}
	//得到用户信息
	function get_user($where) {
		return LM('member')->get($where);
	}
}
?>

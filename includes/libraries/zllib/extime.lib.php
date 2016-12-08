<?php

class extime {
	/**
	 * 得到当前时间所属的年季月周日字符串
	 * @param  integer $type 传入转换类型
	 * @return string        返回当前时间所属的年-季-月-周-日
	 */
	function check_time($type = 1) {
		$date_y = date("Y");
		$date_q = $this->date_q();
		$date_m = date("m");
		$date_w = $this->current_week();
		$date_d = date("d");
		switch ($type) {
		case '1':
			$date = $date_y . "-" . $date_q . "-" . $date_m . "-" . $date_w . "-" . $date_d;
			break;

		case '2':
			$date = $date_y . "-" . $date_q . "-" . $date_m . "-" . $date_w;
			break;

		case '3':
			$date = $date_y . "-" . $date_q . "-" . $date_m;
			break;

		case '4':
			$date = $date_y . "-" . $date_q;
			break;

		case '5':
			$date = $date_y;
			break;

		default:
			$date = $date_y . "-" . $date_q . "-" . $date_m . "-" . $date_w . "-" . $date_d;
			break;
		}
		return $date;
	}

	/**
	 * 将check_time得到的字符串转换为可读性更好的字符串
	 * @param  string $sum_date 年-季-月-周-日
	 * @param  string $type     类型为1,2,3,4,5表示转换成特定的类型字符串
	 * @return string           返回友好的字符串
	 */
	function get_sum_date($sum_date, $type = '') {
		$date_y = substr($sum_date, 0, 4);
		$date_q = substr($sum_date, 5, 1);
		$date_m = substr($sum_date, 7, 2);
		$date_w = substr($sum_date, 10, 1);
		$date_d = substr($sum_date, 12, 2);
		if ($type == '') {
			$type = 1;
			if (empty($date_d)) {
				$type = 2;
			}
			if (empty($date_w) && empty($date_d)) {
				$type = 3;
			}
			if (empty($date_m) && empty($date_w) && empty($date_d)) {
				$type = 4;
			}
			if (empty($date_q) && empty($date_m) && empty($date_w) && empty($date_d)) {
				$type = 5;
			}

		}
		switch ($type) {
		case 1:
			$date = $date_y . "年" . $date_q . "季度" . $date_m . "月" . $date_w . "周" . $date_d . "日";
			break;
		case 2:
			$date = $date_y . "年" . $date_q . "季度" . $date_m . "月" . $date_w . "周";
			break;
		case 3:
			$date = $date_y . "年" . $date_q . "季度" . $date_m . "月";
			break;
		case 4:
			$date = $date_y . "年" . $date_q . "季度";
			break;
		case 5:
			$date = $date_y . "年";
			break;

		default:
			$date = "";
			break;
		}
		return $date;
	}

	//判断统计类型
	function get_sum_type($type) {
		switch ($type) {
		//每日统计
		case 1:
			$date = date("Y-m-d");
			$date = $date . " 00:00:00";
			$time_from_sum = gmstr2time($date);
			break;
		//每周统计

		case 2:
			$date = date("Y-m-d");
			$date_w = date("w");
			$date = $date . " 00:00:00";
			$time_from = gmstr2time($date);
			$time = 24 * 3600 * ($date_w - 1);
			$time_from_sum = $time_from - $time;
			break;
		//每月统计

		case 3:
			$date = date("Y-m");
			$date = $date . "-01 00:00:00";
			$time_from_sum = gmstr2time($date);
			break;
		//每季度统计

		case 4:
			$time_from_sum = $this->quarter();
			break;
		//每年统计

		case 5:
			$date = date("Y");
			$date = $date . "-01-01 00:00:00";
			$time_from_sum = gmstr2time($date);
			break;

		default:
			break;
		}
		return $time_from_sum;
	}
	//得到季度最早时间戳
	function quarter() {
		$date_m = date("m");
		$date_y = date("Y");
		if ($date_m >= 1 && $date_m <= 3) {
			$date_m = 1;
		} else if ($date_m >= 4 && $date_m <= 6) {
			$date_m = 4;
		} else if ($date_m >= 7 && $date_m <= 9) {
			$date_m = 7;
		} else if ($date_m >= 10 && $date_m <= 12) {
			$date_m = 10;
		}
		$date_month = $date_y . "-" . $date_m . "-01 00:00:00";
		return $time_from_quarter = gmstr2time($date_month);
	}
	//返回当前是第几季度
	function date_q() {
		$month = date("m");
		if ($month >= 1 && $month <= 3) {
			$date_q = 1;
		} elseif ($month >= 4 && $month <= 6) {
			$date_q = 2;
		} elseif ($month >= 7 && $month <= 9) {
			$date_q = 3;
		} elseif ($month >= 10 && $month <= 12) {
			$date_q = 4;
		}
		return $date_q;
	}
	//返回当前是本月的第几周
	function current_week() {
		//本月第一天的时间戳
		$date_month = date("Y-m");
		$date_of_firstday = $date_month . "-01";
		$year = substr($date_of_firstday, 0, 4);
		$month = substr($date_of_firstday, 5, 2);
		$day = substr($date_of_firstday, 8, 2);
		$time_chuo_of_first_day = mktime(0, 0, 0, $month, $day, $year);
		//今天的时间戳
		$month = date('n'); //获取月 n
		$day = date('d'); //获取日 d
		$year = date('Y'); //获取年 Y
		$time_chuo_of_current_day = mktime(0, 0, 0, $month, $day, $year);
		$cha = ($time_chuo_of_current_day - $time_chuo_of_first_day) / 60 / 60 / 24;
		$week = (int) (($cha) / 7 + 1);
		return $week;
	}
	function get_options_type() {
		return array(
			'1' => '按日统计',
			'2' => '按周统计',
			'3' => '按月统计',
			'4' => '按季度统计',
			'5' => '按年统计',
		);
	}

	/**
	 * 根据时间戳获取当天的开始时间戳与结束时间戳
	 *
	 * @param bigint $timestamp
	 * @param bool $zone
	 *            是否计算时区，默认启用
	 */
	function get_day_timestamp($timestamp, $zone = true) {
		$timezone = $zone ? date('Z') : 0;
		$Y_m_d = date('Y-m-d', $timestamp + $timezone);

		$day = array();
		$day['start'] = strtotime($Y_m_d) - $timezone;
		$day['end'] = $day[0] + 86400;
		return $day;
	}

	/**
	 * 根据时间戳获取当前周的开始时间戳与结束时间戳
	 *
	 * @param bigint $timestamp
	 * @param bool $zone
	 *            是否计算时区，默认启用
	 */
	function get_week_timestamp($timestamp, $zone = true) {
		$timezone = $zone ? date('Z') : 0;
		$date = getdate($timestamp + $timezone);

		$Y_m_d = date('Y-m-d', $timestamp + $timezone);
		$day = strtotime($Y_m_d) - $timezone;

		$week = array();
		$week['start'] = $day - $date['wday'] * 86400;
		$week['end'] = $week[0] + 7 * 86400;

		return $week;
	}

}
?>

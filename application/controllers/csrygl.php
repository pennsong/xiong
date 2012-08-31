<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Csrygl extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->_init();
	}

	private function _init()
	{
	}

	private function _checkDateFormat($date)
	{
		//match the format of the date
		if (preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $date, $parts))
		{
			//check weather the date is valid of not
			if (checkdate($parts[2], $parts[3], $parts[1]))
				return true;
			else
				return false;
		}
		else
			return false;
	}

	private function _checkTime($time)
	{
		$pattern = '/[\d]{4}-[\d]{1,2}-[\d]{1,2}\s[\d]{1,2}:[\d]{1,2}:[\d]{1,2}/';
		return preg_match($pattern, $time);
	}

	function index($tester, $timeFrom = null, $timeTo = null)
	{
		//处理where条件
		$sqlTimeFrom = "";
		$sqlTimeTo = "";
		if ($timeFrom != null)
		{
			if ($this->_checkTime($timeFrom))
			{
				$sqlTimeFrom = " AND testTime >= '$timeFrom'";
			}
			else
			{
				$sqlTimeFrom = " AND 0";
			}
		}
		if ($timeTo != null)
		{
			if ($this->_checkTime($timeTo))
			{
				$sqlTimeTo = " AND testTime <= '$timeTo'";
			}
			else
			{
				$sqlTimeTo = " AND 0";
			}
		}
		$tmpRes = $this->db->query("SELECT count(*) NUM,  DATE(testTime) dateValue FROM productTestInfo WHERE tester = ?".$sqlTimeFrom.$sqlTimeTo." GROUP BY dateValue ORDER BY dateValue", array($tester));
		$tmpArray = $tmpRes->result_array();
		print_r($tmpArray);
	}

	function testerWorkLoad($tester, $dateFrom = null, $dateTo = null)
	{
		//处理where条件
		$sqlDateFrom = "";
		$sqlDateTo = "";
		if ($dateFrom != null)
		{
			if ($this->_checkDateFormat($dateFrom))
			{
				$sqlDateFrom = " AND testTime >= '$dateFrom'";
			}
			else
			{
				$sqlDateFrom = " AND 0";
			}
		}
		if ($dateTo != null)
		{
			if ($this->_checkDateFormat($dateTo))
			{
				$sqlDateTo = " AND testTime <= '$dateTo'";
			}
			else
			{
				$sqlDateTo = " AND 0";
			}
		}
		$tmpRes = $this->db->query("SELECT CONCAT(LPAD(HOUR(MAX(testTime)), 2, '0'),':',LPAD(MINUTE(MAX(testTime)), 2, '0')) maxT, CONCAT(LPAD(HOUR(MIN(testTime)), 2, '0'),':',LPAD(MINUTE(MIN(testTime)), 2, '0')) minT, DATE(testTime) dateValue FROM productTestInfo WHERE tester = ?".$sqlDateFrom.$sqlDateTo." GROUP BY dateValue ORDER BY dateValue", array($tester));
		$tmpArray = $tmpRes->result_array();
		print_r($tmpArray);
	}

}

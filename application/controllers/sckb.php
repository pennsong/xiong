<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Sckb extends CW_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->_init();
	}

	private function _init()
	{
		//取得测试站点
		$tmpRes = $this->db->query("SELECT * FROM testStation");
		$testStationArray = $tmpRes->result_array();
		$this->smarty->assign('testStationList', $testStationArray);
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

	function index($date = null, $testStation = null)
	{
		if (!($this->_checkDateFormat($date)))
		{
			$date = date("Y-m-d");
		}
		$sqlTestStation = '';
		if ($testStation != '')
		{
			$sqlTestStation = "AND testStation = '".$testStation."'";
		}
		$tmpRes = $this->db->query("SELECT count(*) num, HOUR(testTime) hour FROM `productTestInfo` WHERE testTime >= '".$date." 00:00:00' AND testTime <= '".$date." 23:59:59'".$sqlTestStation." GROUP BY hour");
		$totalNumArray = $tmpRes->result_array();
		//添加时间标签
		$totalNumList = array();
		for ($i = 0; $i < 24; $i++)
		{
			$totalNumList[$i] = 0;
		}
		foreach ($totalNumArray as $item)
		{
			$totalNumList[$item['hour']] = $item['num'];
		}
		$tmpRes = $this->db->query("SELECT count(*) num, HOUR(testTime) hour FROM `productTestInfo` WHERE result = 'TRUE' AND testTime >= '".$date." 00:00:00' AND testTime <= '".$date." 23:59:59'".$sqlTestStation." GROUP BY hour");
		$okNumArray = $tmpRes->result_array();
		//添加时间标签
		$okNumList = array();
		for ($i = 0; $i < 24; $i++)
		{
			$okNumList[$i] = 0;
		}
		foreach ($okNumArray as $item)
		{
			$okNumList[$item['hour']] = $item['num'];
		}
		$this->smarty->assign('totalNumList', $totalNumList);
		$this->smarty->assign('okNumList', $okNumList);
		$this->smarty->display('sckb.tpl');
	}

}

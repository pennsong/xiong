<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Cssj extends CW_Controller
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

	function index()
	{
		$param = $this->uri->uri_to_assoc();
		$timeFrom = isset($param['timeFrom']) ? $param['timeFrom'] : null;
		$timeTo = isset($param['timeTo']) ? $param['timeTo'] : null;
		$testResult = isset($param['testResult']) ? $param['testResult'] : null;
		$testStationName = urldecode(isset($param['testStationName']) ? $param['testStationName'] : null);
		$productTypeName = urldecode(isset($param['productTypeName']) ? $param['productTypeName'] : null);
		$employeeId = isset($param['employeeId']) ? $param['employeeId'] : null;
		$sn = isset($param['sn']) ? $param['sn'] : null;
		//处理where条件
		$sqlTimeFrom = "";
		$sqlTimeTo = "";
		$sqlTestResult = "";
		$sqlTestStationName = "";
		$sqlProductTypeName = "";
		$sqlEmployeeId = "";
		$sqlSn = "";
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
		if ($testResult != null)
		{
			if ($testResult == 0 || $testResult == 1)
			{
				$sqlTestResult = " AND result = '$testResult'";
			}
			else
			{
				$sqlTestResult = " AND 0";
			}
		}
		if ($testStationName != null)
		{
			$sqlTestStationName = " AND b.name = '$testStationName'";
		}
		if ($productTypeName != null)
		{
			$sqlProductTypeName = " AND d.name = '$productTypeName'";
		}
		if ($employeeId != null)
		{
			$sqlEmployeeId = " AND employeeId = '$employeeId'";
		}
		if ($sn != null)
		{
			$sqlSn = " AND sn = '$sn'";
		}
		$tmpRes = $this->db->query("SELECT a.id, a.testTime, a.testStation, a.tester, a.productType, a.sn, a.result, b.name testStationName, c.employeeId, d.name productTypeName FROM productTestInfo a JOIN testStation b ON a.testStation = b.id JOIN tester c ON a.tester = c.id JOIN productType d ON a.productType = d.id WHERE 1".$sqlTimeFrom.$sqlTimeTo.$sqlTestResult.$sqlTestStationName.$sqlProductTypeName.$sqlEmployeeId.$sqlSn);
		$tmpArray = $tmpRes->result_array();
		$this->smarty->assign('productTestList', $tmpArray);
		$this->smarty->display('cssj.tpl');
	}

	public function getTestItemResult($productTestInfo)
	{
		$tmpRes = $this->db->query("SELECT a.id, a.img, b.name testItemName FROM testItemResult a JOIN testItem b ON a.testItem = b.id WHERE productTestInfo = ?", $productTestInfo);
		$testItemResultArray = $tmpRes->result_array();
		foreach ($testItemResultArray as &$item)
		{
			$tmpRes = $this->db->query("SELECT * FROM testItemMarkValue WHERE testItemResult = ?", $item['id']);
			$testItemMarkValueArray = $tmpRes->result_array();
			$item['testItemMarkValueArray'] = $testItemMarkValueArray;
		}
		$this->smarty->assign('productTestInfo', $productTestInfo);
		$this->smarty->assign('testItemResultList', $testItemResultArray);
		$this->smarty->display('cssj_testItem.tpl');
	}

}

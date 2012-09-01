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

	function index($offset = 0, $limit = 10)
	{
		$timeFrom = emptyToNull($this->input->post('timeFrom'));
		$timeTo = emptyToNull($this->input->post('timeTo'));
		$testResult = emptyToNull($this->input->post('testResult'));
		$testStationName = emptyToNull($this->input->post('testStationName'));
		$productTypeName = emptyToNull($this->input->post('productTypeName'));
		$employeeId = emptyToNull($this->input->post('employeeId'));
		$sn = emptyToNull($this->input->post('sn'));
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
			if ($this->_checkTime($timeFrom." 00:00:00"))
			{
				$sqlTimeFrom = " AND testTime >= '$timeFrom"." "."'";
			}
			else
			{
				$sqlTimeFrom = " AND 0";
			}
		}
		if ($timeTo != null)
		{
			if ($this->_checkTime($timeTo." 23:59:59"))
			{
				$sqlTimeTo = " AND testTime <= '$timeTo"." "."23:59:59'";
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
		//处理分页
		$this->load->library('pagination');
		$config['base_url'] = site_url('cssj/index/');
		$config['uri_segment'] = 3;
		//取得符合条件人才信息条数
		$tmpRes = $this->db->query("SELECT COUNT(*) num FROM productTestInfo a JOIN testStation b ON a.testStation = b.id JOIN tester c ON a.tester = c.id JOIN productType d ON a.productType = d.id WHERE 1".$sqlTimeFrom.$sqlTimeTo.$sqlTestResult.$sqlTestStationName.$sqlProductTypeName.$sqlEmployeeId.$sqlSn);
		$config['total_rows'] = $tmpRes->first_row()->num;
		$config['per_page'] = '10';
		$this->pagination->initialize($config);
		$tmpRes = $this->db->query("SELECT a.id, a.testTime, a.testStation, a.tester, a.productType, a.sn, a.result, b.name testStationName, c.employeeId, d.name productTypeName FROM productTestInfo a JOIN testStation b ON a.testStation = b.id JOIN tester c ON a.tester = c.id JOIN productType d ON a.productType = d.id WHERE 1".$sqlTimeFrom.$sqlTimeTo.$sqlTestResult.$sqlTestStationName.$sqlProductTypeName.$sqlEmployeeId.$sqlSn." LIMIT ?, ?", array(
			(int)$offset,
			(int)$limit
		));
		$tmpArray = $tmpRes->result_array();
		$this->smarty->assign('productTestList', $tmpArray);
		$this->smarty->assign('title', '测试数据查询');
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

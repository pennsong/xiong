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
		$testResultList = array(
			''=>'所有',
			'0'=>'FAIL',
			'1'=>'PASS'
		);
		$this->smarty->assign('testResultList', $testResultList);
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

	function index($offset = 0, $limit = 30)
	{
		$timeFrom1 = emptyToNull($this->input->post('timeFrom1'));
		if ($timeFrom1 == null)
		{
			$timeFrom1 = 1900;
		}
		$timeFrom2 = emptyToNull($this->input->post('timeFrom2'));
		if ($timeFrom2 == null)
		{
			$timeFrom2 = 1;
		}
		$timeFrom3 = emptyToNull($this->input->post('timeFrom3'));
		if ($timeFrom3 == null)
		{
			$timeFrom3 = 1;
		}
		$timeFrom4 = emptyToNull($this->input->post('timeFrom4'));
		if ($timeFrom4 == null)
		{
			$timeFrom4 = 0;
		}
		$timeFrom5 = emptyToNull($this->input->post('timeFrom5'));
		if ($timeFrom5 == null)
		{
			$timeFrom5 = 0;
		}
		$timeFrom = $timeFrom1."-".$timeFrom2."-".$timeFrom3." ".$timeFrom4.":".$timeFrom5;
		$timeTo1 = emptyToNull($this->input->post('timeTo1'));
		if ($timeTo1 == null)
		{
			$timeTo1 = 2050;
		}
		$timeTo2 = emptyToNull($this->input->post('timeTo2'));
		if ($timeTo2 == null)
		{
			$timeTo2 = 1;
		}
		$timeTo3 = emptyToNull($this->input->post('timeTo3'));
		if ($timeTo3 == null)
		{
			$timeTo3 = 1;
		}
		$timeTo4 = emptyToNull($this->input->post('timeTo4'));
		if ($timeTo4 == null)
		{
			$timeTo4 = 23;
		}
		$timeTo5 = emptyToNull($this->input->post('timeTo5'));
		if ($timeTo5 == null)
		{
			$timeTo5 = 59;
		}
		$timeTo = $timeTo1."-".$timeTo2."-".$timeTo3." ".$timeTo4.":".$timeTo5;
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
			if ($this->_checkTime($timeFrom.":00"))
			{
				$sqlTimeFrom = " AND testTime >= '$timeFrom".":00"."'";
			}
			else
			{
				$sqlTimeFrom = " AND 0";
			}
		}
		if ($timeTo != null)
		{
			if ($this->_checkTime($timeTo.":59"))
			{
				$sqlTimeTo = " AND testTime <= '$timeTo".":59'";
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
			$sqlTestStationName = " AND b.name like '%$testStationName%'";
		}
		if ($productTypeName != null)
		{
			$sqlProductTypeName = " AND d.name like '%$productTypeName%'";
		}
		if ($employeeId != null)
		{
			$sqlEmployeeId = " AND employeeId like '%$employeeId%'";
		}
		if ($sn != null)
		{
			$sqlSn = " AND sn like '%$sn%'";
		}
		//处理分页
		$this->load->library('pagination');
		$config['full_tag_open'] = '<div class="locPage">';
		$config['full_tag_close'] = '</div>';
		$config['base_url'] = '';
		$config['uri_segment'] = 3;
		//取得符合条件信息条数
		$tmpRes = $this->db->query("SELECT COUNT(*) num FROM productTestInfo a JOIN testStation b ON a.testStation = b.id JOIN tester c ON a.tester = c.id JOIN productType d ON a.productType = d.id WHERE 1".$sqlTimeFrom.$sqlTimeTo.$sqlTestResult.$sqlTestStationName.$sqlProductTypeName.$sqlEmployeeId.$sqlSn);
		$config['total_rows'] = $tmpRes->first_row()->num;
		$config['per_page'] = $limit;
		$this->pagination->initialize($config);
		$tmpRes = $this->db->query("SELECT a.id, a.testTime, a.testStation, a.tester, a.productType, a.sn, a.result, b.name testStationName, c.employeeId, d.name productTypeName FROM productTestInfo a JOIN testStation b ON a.testStation = b.id JOIN tester c ON a.tester = c.id JOIN productType d ON a.productType = d.id WHERE 1".$sqlTimeFrom.$sqlTimeTo.$sqlTestResult.$sqlTestStationName.$sqlProductTypeName.$sqlEmployeeId.$sqlSn." ORDER BY a.testTime DESC LIMIT ?, ?", array(
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

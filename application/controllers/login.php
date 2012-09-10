<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Login extends CW_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('cookie');
	}

	public function index()
	{
		$this->session->sess_destroy();
		$this->smarty->display('login.tpl');
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url()."index.php/login");
	}

	public function login2($userName = null, $password = null)
	{
		$this->session->sess_destroy();
		$_POST['userName'] = $userName;
		$_POST['password'] = $password;
		$this->validateLogin();
	}

	public function validateLogin()
	{
		$var = '';
		if ($this->_authenticate($var))
		{
			//登录成功
			$this->input->set_cookie('type', $this->input->post('type'), 3600 * 24 * 30);
			redirect(base_url().'index.php/firstPage');
		}
		else
		{
			//登录失败
			$this->smarty->assign('loginErrorInfo', $var);
			$this->index();
		}
	}

	private function _checkDataFormat(&$result)
	{
		$this->load->library('form_validation');
		$config = array(
			array(
				'field'=>'userName',
				'label'=>'用户名',
				'rules'=>'required|callback_checkUsername1|callback_checkUsername2|callback_checkUsername3'
			),
			array(
				'field'=>'password',
				'label'=>'密码',
				'rules'=>'required|alpha_numeric|min_length[6]|max_length[20]'
			)
		);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('*', '<br>');
		if ($this->form_validation->run() == FALSE)
		{
			$result = validation_errors();
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	public function checkUsername1($str)
	{
		$r1 = preg_match("/^[\w\.]{6,15}$/", $str);
		if ($r1 == 0)
		{
			$this->form_validation->set_message('checkUsername1', '%s 只能包含英文字母，数字，下划线和点,长度为6-15.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	public function checkUsername2($str)
	{
		$docNum = substr_count($str, '.');
		$lineNum = substr_count($str, '_');
		if ($docNum + $lineNum > 1)
		{
			$this->form_validation->set_message('checkUsername2', '%s 只能包含一个下划线或点.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	public function checkUsername3($str)
	{
		$r1 = preg_match("/^\..*/", $str);
		$r2 = preg_match("/^_.*/", $str);
		$r3 = preg_match("/.*\.$/", $str);
		$r4 = preg_match("/.*_$/", $str);
		if ($r1 || $r2 || $r3 || $r4)
		{
			$this->form_validation->set_message('checkUsername3', '%s 不能以下划线或点开始或结束.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	private function _authenticate(&$var)
	{
		$this->lang->load('form_validation', 'chinese');
		//check data format
		if (!($this->_checkDataFormat($result)))
		{
			$var = $result;
			return FALSE;
		}
		else
		{
			$tmpRes = $this->db->query('SELECT * FROM user WHERE userName = ?', strtolower($this->input->post('userName')));
			if ($tmpRes)
			{
				if ($tmpRes->num_rows() > 0)
				{
					$tmpArr = $tmpRes->first_row('array');
					if ($tmpArr['password'] == strtolower($this->input->post('password')))
					{
						$this->session->set_userdata('username', strtolower($this->input->post('userName')));
						$this->session->set_userdata('userId', $tmpArr['id']);
						return TRUE;
					}
					else
					{
						//密码错误
						$var = "*密码错误，请仔细检查";
						return FALSE;
					}
				}
				else
				{
					//用户名不存在
					$var = "*无此用户,请重新输入";
					return FALSE;
				}
			}
			else
			{
				//查询失败
				$var = "*系统繁忙，请稍后尝试进入";
				return FALSE;
			}
		}
	}

	public function clientLogin($username = null, $password = null, $equipmentSn = null)
	{
		$this->load->helper('xml');
		$dom = xml_dom();
		//检查用户名密码
		if ($tmpArray = $this->_checkTestUser($username, $password))
		{
			//取得测试员姓名,员工号,权限
			$username = xml_add_child($dom, 'username');
			xml_add_child($username, 'result', 'true');
			xml_add_child($username, 'name', $tmpArray['testerName']);
			xml_add_child($username, 'id', $tmpArray['testerId']);
			xml_add_child($username, 'test_right_id', $tmpArray['testRightId']);
			xml_add_child($username, 'test_right_name', $tmpArray['testRightName']);
			//根据$equipmentSn取得站点id,站点名称
			$tmpRes = $this->db->query('SELECT * FROM testStation WHERE equipmentSn = ?', array($equipmentSn));
			if ($tmpRes->num_rows() > 0)
			{
				$testStation = xml_add_child($dom, 'test_station');
				xml_add_child($testStation, 'result', 'true');
				xml_add_child($testStation, 'id', $tmpRes->first_row()->id);
				xml_add_child($testStation, 'name', $tmpRes->first_row()->name);
			}
			else
			{
				//没有查到站点
				$testStation = xml_add_child($dom, 'test_station');
				xml_add_child($testStation, 'result', 'false');
			}
			//取得产品类型列表
			$tmpRes = $this->db->query('SELECT * FROM productType ORDER BY id');
			if ($tmpRes->num_rows() > 0)
			{
				$productTestCase = xml_add_child($dom, 'product_test_case');
				xml_add_child($productTestCase, 'result', 'true');
				$tmpProductTypeArray = $tmpRes->result_array();
				foreach ($tmpProductTypeArray as $productTypeItem)
				{
					$productType = xml_add_child($productTestCase, 'product_type');
					xml_add_child($productType, 'id', $productTypeItem['id']);
					xml_add_child($productType, 'name', $productTypeItem['name']);
					//取得产品测试案例内容
					$tmpRes = $this->db->query('SELECT a.testItem, b.name testItemName, a.stateFile FROM productTypeTestCase a JOIN testItem b ON a.testItem = b.id WHERE a.productType = ? ORDER BY a.testItem', array($productTypeItem['id']));
					if ($tmpRes->num_rows() > 0)
					{
						$tmpTestItemArray = $tmpRes->result_array();
						$testItem = xml_add_child($productType, 'test_item');
						xml_add_child($testItem, 'result', 'true');
						foreach ($tmpTestItemArray as $testItemItem)
						{
							xml_add_child($testItem, 'id', $testItemItem['testItem']);
							xml_add_child($testItem, 'name', $testItemItem['testItemName']);
							xml_add_child($testItem, 'state_file', $testItemItem['stateFile']);
						}
					}
					else
					{
						$testItem = xml_add_child($productType, 'test_item');
						xml_add_child($testItem, 'result', 'false');
					}
				}
			}
			else
			{
				$productTestCase = xml_add_child($dom, 'product_test_case');
				xml_add_child($productTestCase, 'result', 'false');
			}
		}
		else
		{
			$username = xml_add_child($dom, 'username');
			xml_add_child($username, 'result', 'false');
		}
		xml_print($dom);
	}

	private function _checkTestUser($username, $password)
	{
		//检查用户名密码
		$tmpRes = $this->db->query('SELECT a.id testerId, a.name testerName, a.employeeId, b.id testRightId, b.name testRightName FROM tester a JOIN testRight b ON a.testRight = b.id WHERE a.employeeId = ? AND password = ?', array(
			$username,
			$password
		));
		if ($tmpRes->num_rows() > 0)
		{
			return $tmpRes->first_row('array');
		}
		else
		{
			return FALSE;
		}
	}

	public function uploadFile($username = null, $password = null)
	{
		if (PHP_OS == 'WINNT')
		{
			$uploadRoot = "D:\\wwwroot\\xiong\\assets\\uploadedSource";
			$slash = "\\";
		}
		else if (PHP_OS == 'Darwin')
		{
			$uploadRoot = "/Library/WebServer/Documents/aptana/xiong/assets/uploadedSource";
			$slash = "/";
		}
		else
		{
			$this->_returnUploadFailed("错误的服务器操作系统");
			return;
		}
		if ($this->_checkTestUser($username, $password) === FALSE)
		{
			$this->_returnUploadFailed("错误的用户名密码");
			return;
		}
		else
		{
			//保存上传文件
			$file_temp = $_FILES['file']['tmp_name'];
			date_default_timezone_set('Asia/Shanghai');
			$dateStamp = date("Y_m_d");
			$dateStampFolder = $uploadRoot.$slash.$dateStamp;
			if (file_exists($dateStampFolder) && is_dir($dateStampFolder))
			{
				//do nothing
			}
			else
			{
				echo $dateStampFolder;
				if (mkdir($dateStampFolder))
				{
				}
				else
				{
					$this->_returnUploadFailed("日期目录创建失败");
					return;
				}
			}
			$file_name = $dateStamp.$slash.$_FILES['file']['name'];
			//complete upload
			$filestatus = move_uploaded_file($file_temp, $uploadRoot.$slash.$file_name);
			if (!$filestatus)
			{
				$this->_returnUploadFailed("文件:".$_FILES['file']['name']."上传失败");
				return;
			}
			//解压缩文件
			if (PHP_OS == 'WINNT')
			{
				exec('C:\Progra~1\7-Zip\7z.exe x '.$uploadRoot.$slash.$file_name.' -o'.$uploadRoot.$slash.$dateStamp.' -y', $info);
			}
			else if (PHP_OS == 'Darwin')
			{
				$zip = new ZipArchive;
				if ($zip->open($uploadRoot.$slash.$file_name) === TRUE)
				{
					$zip->extractTo($uploadRoot.$slash.$dateStamp.$slash);
					$zip->close();
					//关闭处理的zip文件
				}
				else
				{
					$this->_returnUploadFailed("文件:".$_FILES['file']['name']."打开失败");
					return;
				}
			}
			//解析文件并插入数据库
			$this->db->trans_start();
			//解析General.csv
			if ($handle = fopen($uploadRoot.$slash.$dateStamp.$slash.substr($_FILES['file']['name'], 0, -4).$slash.'General.csv', "r"))
			{
				$i = 0;
				while (($buffer = fgets($handle)) !== false)
				{
					$i = $i + 1;
					if ($i == 1)
					{
						$tmpArray = explode(",", $buffer);
						continue;
					}
					$tmpArray = explode(",", $buffer);
					//取得测试时间
					$testTime = $tmpArray[0];
					//取得测试站号
					$tmpRes = $this->db->query("SELECT id FROM testStation WHERE name = ?", array($tmpArray[1]));
					if ($tmpRes->num_rows() == 0)
					{
						$this->db->trans_rollback();
						$this->_returnUploadFailed("文件:".$_FILES['file']['name']."中General.csv中(".$buffer.")对应测试站点没有找到");
						return;
					}
					else
					{
						$testStation = $tmpRes->first_row()->id;
					}
					//取得设备序列号
					$equipmentSn = $tmpArray[2];
					//取得测试者id
					$tmpRes = $this->db->query("SELECT id FROM tester WHERE employeeId = ?", array($tmpArray[3]));
					if ($tmpRes->num_rows() == 0)
					{
						$this->db->trans_rollback();
						$this->_returnUploadFailed("文件:".$_FILES['file']['name']."中General.csv中(".$buffer.")对应测试者没有找到");
						return;
					}
					else
					{
						$tester = $tmpRes->first_row()->id;
					}
					//取得产品类型
					$tmpRes = $this->db->query("SELECT id FROM producttype WHERE name = ?", array($tmpArray[4]));
					if ($tmpRes->num_rows() == 0)
					{
						$this->db->trans_rollback();
						$this->_returnUploadFailed("文件:".$_FILES['file']['name']."中General.csv中(".$buffer.")对应产品类型没有找到");
						return;
					}
					else
					{
						$productType = $tmpRes->first_row()->id;
					}
					//取得产品SN
					$sn = $tmpArray[5];
					//处理测试结果
					if ($tmpArray[6] == 'PASS')
					{
						$testResult = 1;
					}
					else
					{
						$testResult = 0;
					}
					//处理客户化数据
					$cus1 = $tmpArray[7];
					$cus2 = $tmpArray[8];
					$cus3 = $tmpArray[9];
					$cus4 = $tmpArray[10];
					$cus5 = $tmpArray[11];
					//插入producttestinfo
					$tmpSql = "INSERT INTO `producttestinfo`(`sn`, `equipmentSn`, `testTime`, `testStation`, `tester`, `productType`, `result`, `column1`, `column2`, `column3`, `column4`, `column5`, `column6`, `column7`, `column8`, `column9`, `column10`) ";
					$tmpSql .= "VALUES ('$sn','$equipmentSn','$testTime'+ INTERVAL 0 SECOND,$testStation,$tester,$productType,$testResult,'$cus1','$cus2','$cus3','$cus4','$cus5',null,null,null,null,null)";
					$tmpRes = $this->db->query($tmpSql);
					if ($tmpRes === TRUE)
					{
						//取得producttestinfo id
						$productTestInfo = $this->db->insert_id();
						//取得测试项名称
						$testItemList = $this->_getDirFiles($uploadRoot.$slash.$dateStamp.$slash.substr($_FILES['file']['name'], 0, -4).$slash, 'csv', 'General.csv');
						foreach ($testItemList as $testItemItem)
						{
							//插入testitemresult
							//转换csv文件名
							if (PHP_OS == 'WINNT')
							{
								$fileName = $testItemItem;
							}
							else if (PHP_OS == 'Darwin')
							{
								$fileName = urldecode($testItemItem);
							}
							//取得测试项目名称
							$tmpArray = preg_split("[-|\.]", $fileName);
							$testItemName = $tmpArray[0];
							//取得测试项目id
							$tmpRes = $this->db->query("SELECT id FROM testitem WHERE name = ?", array(iconv('GB2312', 'UTF-8', $testItemName)));
							if ($tmpRes->num_rows() > 0)
							{
								$testItem = $tmpRes->first_row()->id;
							}
							else
							{
								$this->db->trans_rollback();
								$this->_returnUploadFailed("文件:".$_FILES['file']['name']."中没有找到对应测试项目名称:".iconv('GB2312', 'UTF-8', $testItemName));
								return;
							}
							$testResult = $tmpArray[1] == 'PASS' ? 1 : 0;
							//取得图片文件名称
							if (PHP_OS == 'WINNT')
							{
								$imgFile = iconv('GB2312', 'UTF-8', substr($testItemItem, 0, -9)."-img.png");
							}
							else if (PHP_OS == 'Darwin')
								$imgFile = substr($testItemItem, 0, -9)."-img.png";
							{
							}
							$testItemImg = $dateStamp.$slash.substr($_FILES['file']['name'], 0, -4).$slash.$imgFile;
							//插入testitemresult
							$tmpRes = $this->db->query("INSERT INTO `testitemresult`(`productTestInfo`, `testItem`, `testResult`, `img`) VALUES ($productTestInfo, $testItem, $testResult, ?)", array($testItemImg));
							if ($tmpRes === TRUE)
							{
								//取得testitemresult id
								$testItemResult = $this->db->insert_id();
								//处理testItem文件
								if ($handle2 = fopen($uploadRoot.$slash.$dateStamp.$slash.substr($_FILES['file']['name'], 0, -4).$slash.$testItemItem, "r"))
								{
									$i2 = 0;
									while (($buffer2 = fgets($handle2)) !== false)
									{
										$i2 = $i2 + 1;
										if ($i2 == 1)
										{
											$tmpArray2 = explode(",", $buffer2);
											continue;
										}
										$tmpArray2 = explode(",", $buffer2);
										//取得testResult
										$singleTestResult = $tmpArray2[0];
										//取得fmark
										$singleTextFmark = $tmpArray2[1];
										//取得tmark
										$singleTextTmark = isset($tmpArray2[3]) ? $tmpArray2[3] : null;
										$tmpRes = $this->db->query("INSERT INTO `testitemmarkvalue`(`testItemResult`, `value`, `markF`, `markT`) VALUES (?, ?, ?, ?)", array(
											$testItemResult,
											$singleTestResult,
											$singleTextFmark,
											$singleTextTmark
										));
										if ($tmpRes === TRUE)
										{
											//do nothing
										}
										else
										{
											$this->db->trans_rollback();
											$this->_returnUploadFailed("文件:".$_FILES['file']['name']."中".iconv('GB2312', 'UTF-8', $testItemName).":$buffer2 插入失败");
											return;
										}
									}
									fclose($handle2);
								}
								else
								{
									$this->_returnUploadFailed("文件:$fileName 打开失败");
									return;
								}
							}
							else
							{
								$this->db->trans_rollback();
								$this->_returnUploadFailed("文件:".$_FILES['file']['name']."中$testItemItem 插入testitemresult失败");
								return;
							}
						}
					}
					else
					{
						$this->db->trans_rollback();
						$this->_returnUploadFailed("文件:".$_FILES['file']['name']."中General.csv中(".$buffer.")插入producttestinfo失败");
						return;
					}
				}
				fclose($handle);
			}
			else
			{
				$this->_returnUploadFailed("文件:General.csv 打开失败");
				return;
			}
		}
		$this->_returnUploadOk();
		return;
	}

	private function _returnUploadOK()
	{
		$this->db->trans_commit();
		$this->load->helper('xml');
		$dom = xml_dom();
		$uploadResult = xml_add_child($dom, 'uploadResult');
		xml_add_child($uploadResult, 'result', 'true');
		xml_add_child($uploadResult, 'info', 'success');
		xml_print($dom);
	}

	private function _returnUploadFailed($err)
	{
		$this->load->helper('xml');
		$dom = xml_dom();
		$uploadResult = xml_add_child($dom, 'uploadResult');
		xml_add_child($uploadResult, 'result', 'false');
		xml_add_child($uploadResult, 'info', $err);
		xml_print($dom);
	}

	private function _getDirFiles($dir, $extension, $except)
	{
		if ($handle = opendir($dir))
		{
			$files = array();
			/* Because the return type could be false or other equivalent type(like 0),
			 this is the correct way to loop over the directory. */
			while (false !== ($file = readdir($handle)))
			{
				if (($file != 'General.csv') && substr($file, strrpos($file, '.') + 1) == $extension)
				{
					$files[] = $file;
				}
			}
			closedir($handle);
			return $files;
		}
		else
		{
			return FALSE;
		}
	}

}

/*end*/

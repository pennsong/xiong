<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class firstPage extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('grocery_CRUD');
	}

	function _example_output($output = null)
	{
		$this->load->view('example.php', $output);
	}

	public function department()
	{
		$crud = new grocery_CRUD();
		$crud->required_fields('name');
		$crud->display_as('name', '名称');
		$output = $crud->render();
		$this->_example_output($output);
	}

	public function productType()
	{
		$crud = new grocery_CRUD();
		$crud->required_fields('name', 'img');
		$crud->display_as('name', '名称')->display_as('img', '图纸链接');
		$crud->set_field_upload('img', 'assets/uploads/files');
		$output = $crud->render();
		$this->_example_output($output);
	}

	public function skillLevel()
	{
		$crud = new grocery_CRUD();
		$crud->required_fields('name');
		$crud->display_as('name', '名称');
		$output = $crud->render();
		$this->_example_output($output);
	}

	public function team()
	{
		$crud = new grocery_CRUD();
		$crud->required_fields('name');
		$crud->display_as('name', '名称');
		$output = $crud->render();
		$this->_example_output($output);
	}

	public function tester()
	{
		$crud = new grocery_CRUD();
		$crud->required_fields('employeeId', 'testRight', 'skillLevel', 'department');
		$crud->display_as('employeeId', '员工号')->display_as('testRight', '测试权限')->display_as('skillLevel', '技能级别')->display_as('department', '所属部门');
		$crud->set_relation('testRight', 'testRight', 'name');
		$crud->set_relation('skillLevel', 'skillLevel', 'name');
		$crud->set_relation('department', 'department', 'name');
		$output = $crud->render();
		$this->_example_output($output);
	}

	public function testItem()
	{
		$crud = new grocery_CRUD();
		$crud->required_fields('name');
		$crud->display_as('name', '名称');
		$output = $crud->render();
		$this->_example_output($output);
	}

	public function testRight()
	{
		$crud = new grocery_CRUD();
		$crud->required_fields('name');
		$crud->display_as('name', '名称');
		$output = $crud->render();
		$this->_example_output($output);
	}

	public function testStation()
	{
		$crud = new grocery_CRUD();
		$crud->required_fields('name');
		$crud->display_as('name', '名称');
		$output = $crud->render();
		$this->_example_output($output);
	}

	public function productTestInfo()
	{
		$crud = new grocery_CRUD();
		$crud->required_fields('sn', 'equipmentSn', 'testTime', 'testStation', 'tester', 'productType', 'result');
		$crud->display_as('sn', '序列号')->display_as('equipmentSn', '设备序列号')->display_as('testTime', '测试时间')->display_as('testStation', '测试站点')->display_as('tester', '测试员')->display_as('productType', '产品类型')->display_as('result', '测试结果');
		$crud->set_relation('testStation', 'testStation', 'name');
		$crud->set_relation('tester', 'tester', 'employeeId');
		$crud->set_relation('productType', 'productType', 'name');
		$output = $crud->render();
		$this->_example_output($output);
	}

	public function productTypeTestCase()
	{
		$crud = new grocery_CRUD();
		$crud->required_fields('productType', 'testItem', 'stateFile');
		$crud->display_as('productType', '产品类型')->display_as('testItem', '测试项')->display_as('stateFile', '状态文件');
		$crud->set_relation('productType', 'productType', 'name');
		$crud->set_relation('testItem', 'testItem', 'name');
		$output = $crud->render();
		$this->_example_output($output);
	}

	public function testItemMarkValue()
	{
		$crud = new grocery_CRUD();
		$crud->required_fields('testItemResult', 'value');
		$crud->display_as('testItemResult', '测试项目')->display_as('value', '结果值')->display_as('markF', '频率标记')->display_as('markT', '时间标记');
		$crud->set_relation('testItemResult', 'testItemResult', 'id');
		$output = $crud->render();
		$this->_example_output($output);
	}

	public function testItemResult()
	{
		$crud = new grocery_CRUD();
		$crud->required_fields('productTestInfo', 'testItem', 'img');
		$crud->display_as('productTestInfo', '产品测试信息')->display_as('testItem', '测试项目')->display_as('img', '测试图路径');
		$crud->set_relation('productTestInfo', 'productTestInfo', 'id');
		$crud->set_relation('testItem', 'testItem', 'name');
		$output = $crud->render();
		$this->_example_output($output);
	}

	public function user()
	{
		$crud = new grocery_CRUD();
		$crud->required_fields('username', 'password', 'team');
		$crud->display_as('username', '用户名')->display_as('password', '密码')->display_as('team', '组');
		$crud->set_relation('team', 'team', 'name');
		$output = $crud->render();
		$this->_example_output($output);
	}

	function index()
	{
		$this->_example_output((object) array(
			'output'=>'',
			'js_files'=> array(),
			'css_files'=> array()
		));
	}

}

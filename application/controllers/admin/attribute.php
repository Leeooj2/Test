<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//商品属性控制器

class Attribute extends Admin_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->model('goodstype_model');
		$this->load->model('attribute_model');
	}

	public function index(){
		$data['attrs'] = $this->attribute_model->list_attrs();
		$this->load->view('attribute_list.html',$data);
	}

	public function add(){
		#获取商品类型信息
		$data['goodstypes'] = $this->goodstype_model->list_goodstype();
		$this->load->view('attribute_add.html',$data);
	}

	public function edit(){
		$this->load->view('attribute_edit.html');
	}

	public function insert(){
		$data['attr_name'] = $this->input->post('attr_name');
		$data['type_id'] = $this->input->post('type_id');
		$data['attr_type'] = $this->input->post('attr_type');
		$data['attr_input_type'] = $this->input->post('attr_input_type');
		$data['attr_value'] = $this->input->post('attr_value');
		$data['sort_order'] = $this->input->post('sort_order');

		if($this->attribute_model->add_attrs($data)){
			#ok
			$data['message'] = '添加属性成功';
			$data['url'] = site_url('admin/attribute/index');
			$data['wait'] = 3;
			$this->load->view('message.html',$data);
		}else{
			$data['message'] = '添加属性失败';
			$data['url'] = site_url('admin/attribute/add');
			$data['wait'] = 3;
			$this->load->view('message.html',$data);
		}
	}
}
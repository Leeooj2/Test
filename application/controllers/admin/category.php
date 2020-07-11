<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 商品类别控制器
 */

class Category extends Admin_Controller{


	public function __construct(){
		parent::__construct();
		$this->load->model('category_model');
		$this->load->library('form_validation');
	}
	//显示分类信息
	public function index(){
		$data['cates'] = $this->category_model->list_cate();
		$this->load->view('cat_list.html',$data);
	}

	//显示添加表单
	public function add(){
		$data['cates'] = $this->category_model->list_cate();
		$this->load->view('cat_add.html',$data);
	}

	//显示编辑表单
	public function edit($cat_id){
		$data['cates'] = $this->category_model->list_cate();
		$data['current_cat'] = $this->category_model->get_cate($cat_id);
		$this->load->view('cat_edit.html',$data);
	}

	#添加分类商品
	public function insert(){
		#设置验证规则
		$this->form_validation->set_rules('cat_name','分类名称','trim|required');
		if($this->form_validation->run() == false){
			#未通过验证
			$data['message'] = validation_errors();
			$data['wait'] = 3;
			$data['url'] = site_url('admin/category/add');
			$this->load->view('message.html',$data);
		}else{
			#通过验证
			$data['cat_name'] = $this->input->post('cat_name',true);
			$data['parent_id'] = $this->input->post('parent_id');
			$data['unit'] = $this->input->post('unit',true);
			$data['sort_order'] = $this->input->post('sort_order',true);
			$data['cat_desc'] = $this->input->post('cat_desc',true);
			$data['is_show'] = $this->input->post('is_show');

			#调用model方法完成插入
			if($this->category_model->add_category($data)){
				#插入成功
				$data['message'] = '添加商品类别成功';
				$data['wait'] = 2;
				$data['url'] = site_url('admin/category/index');
				$this->load->view('message.html',$data);				
			}else{
				#插入失败
				$data['message'] = '添加商品类别失败';
				$data['wait'] = 3;
				$data['url'] = site_url('admin/category/add');
				$this->load->view('message.html',$data);				
			}
		}

	}
	#更新操作
	public function update(){
		$cat_id = $this->input->post('cat_id');
		//var_dump($cat_id);
		#获取该cat_id分类下所有后代分类
		$sub_cates = $this->category_model->list_cate($cat_id);
		//var_dump($sub_cates);
		#获取后代分类的cat_id
		$sub_ids = array();
		foreach ($sub_cates as $v) {
			$sub_ids[] = $v['cat_id'];
		}
		$parent_id = $this->input->post('parent_id');
		//var_dump($sub_ids);
		#判断所选的父分类是否为当前分类或其后代分类
		if ($parent_id == $cat_id || in_array($parent_id, $sub_ids)){
			$data['message'] = '不能将分类放置到当前分类或其子分类';
			$data['wait'] = 3;
			$data['url'] = site_url('admin/category/edit') . '/' .$cat_id;
			$this->load->view('message.html',$data);
		} else {
			# 进行更新
			$this->form_validation->set_rules('cat_name','分类名称','trim|required');

			if ($this->form_validation->run() == false) {
				# 未通过验证
				$data['message'] = validation_errors(); 
				$data['wait'] = 3;
				$data['url'] = site_url('admin/category/add');
				$this->load->view('message.html',$data);
			} else {
				# 通过验证
				$data['cat_name'] = $this->input->post('cat_name',true);
				$data['parent_id'] = $this->input->post('parent_id');
				$data['unit'] = $this->input->post('unit',true);
				$data['sort_order'] = $this->input->post('sort_order',true);
				$data['cat_desc'] = $this->input->post('cat_desc',true);
				$data['is_show'] = $this->input->post('is_show');

				if ($this->category_model->update_cate($data,$cat_id)) {
					$data['message'] = '更新成功'; 
					$data['wait'] = 3;
					$data['url'] = site_url('admin/category/index');
					$this->load->view('message.html',$data);
				} else {
					$data['message'] = '更新失败'; 
					$data['wait'] = 3;
					$data['url'] = site_url('admin/category/edit') . '/'.$cat_id;
					$this->load->view('message.html',$data);
				}
				
			}
			
		}
		

	}
}
<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Goodstype extends Admin_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('goodstype_model');
		$this->load->library('pagination');
	}

	public function index($offset =''){
		#配置分页信息
		$config['base_url'] = site_url('admin/goodstype/index');
		$config['total_rows'] = $this->goodstype_model->count_goodstype();
		$config['per_page'] = 2;
		$config['uri_segment'] =4;
		//ci默认的偏移量uri_segment为3，而子文件夹admin存在
		//偏移量为4，需要自定义uri_segment为4，确定偏移量为uri的第四段

		#自定义分页链接
		$config['first_link'] = '首页';
		$config['last_link'] = '尾页';
		$config['prev_link'] = '上一页';
		$config['next_link'] = '下一页';

		#初始化分页类
		$this->pagination->initialize($config);
		#生成分页信息
		$data['pageinfo'] = $this->pagination->create_links();
		
		$limit = $config['per_page'];

		$data['goodstypes'] = $this->goodstype_model->page_goodstype($limit,$offset);
		$this->load->view('goods_type_list.html',$data);

	}
	public function add(){
		$this->load->view('goods_type_add.html');

	}
	public function edit(){
		$this->load->view('goods_type_edit.html');
	}

	#添加商品类型
	public function insert(){
		#设置验证规则
		$this->form_validation->set_rules('type_name','商品类型名称','required');
		if($this->form_validation->run() == false){
			$data['message'] = validation_errors();
			$data['wait'] = 3;
			$data['url'] = site_url('admin/goodstype/add');
			$this->load->view('message.html',$data);
		}else{
			$data['type_name'] = $this->input->post('type_name',true);
			if($this->goodstype_model->add_goodstype($data)){
				$data['message'] = '添加商品类型成功';
				$data['wait'] = 3;
				$data['url'] = site_url('admin/goodstype/index');
				$this->load->view('message.html',$data);
			}else{
				$data['message'] = '添加商品类型失败';
				$data['wait'] = 3;
				$data['url'] = site_url('admin/goodstype/add');
				$this->load->view('message.html',$data);
			}
		}
	}
}
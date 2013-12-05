<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class user extends CI_Controller {

	// num of records per page
	private $limit = 10;
	
	function __construct()
	{
		parent::__construct();
		
		// load library
		$this->load->library(array('table','form_validation'));
		
		// load helper
		$this->load->helper('url');
		
		// load model
		$this->load->model('user_model','',TRUE);
	}
	
	function index($offset = 0)
	{
		// offset
		$uri_segment = 3;
		$offset = $this->uri->segment($uri_segment);
		
		// load data
		$users = $this->user_model->get_paged_list($this->limit, $offset)->result();
		
		// generate pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url('user/index/');
 		$config['total_rows'] = $this->user_model->count_all();
 		$config['per_page'] = $this->limit;
		$config['uri_segment'] = $uri_segment;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		// generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('C&eacute;dula', 'Contrase&Ntildea', 'Tipo', 'Status', 'Acci&oacute;n');
		$i = 0 + $offset;
		foreach ($users as $user)
		{
			$this->table->add_row(++$i, $user->name, strtoupper($user->gender)=='M'? 'Hombre':'Female', date('d-m-Y',strtotime($user->dob)), 
				anchor('user/view/'.$user->id,'view',array('class'=>'view')).' '.
				anchor('user/update/'.$user->id,'update',array('class'=>'update')).' '.
				anchor('user/delete/'.$user->id,'delete',array('class'=>'delete','onclick'=>"return confirm('Are you sure want to delete this user?')"))
			);
		}
		$data['table'] = $this->table->generate();
		
		// load view
		$this->load->view('userList', $data);
	}
	
	function add()
	{
		// set empty default form field values
		$this->_set_fields();
		// set validation properties
		$this->_set_rules();
		
		// set common properties
		$data['title'] = 'Add new user';
		$data['message'] = '';
		$data['action'] = site_url('user/adduser');
		$data['link_back'] = anchor('user/index/','Back to list of users',array('class'=>'back'));
	
		// load view
		$this->load->view('userEdit', $data);
	}
	
	function adduser()
	{
		// set common properties
		$data['title'] = 'Add new user';
		$data['action'] = site_url('user/adduser');
		$data['link_back'] = anchor('user/index/','Back to list of users',array('class'=>'back'));
		
		// set empty default form field values
		$this->_set_fields();
		// set validation properties
		$this->_set_rules();
		
		// run validation
		if ($this->form_validation->run() == FALSE)
		{
			$data['message'] = '';
		}
		else
		{
			// save data
			$user = array('name' => $this->input->post('name'),
							'gender' => $this->input->post('gender'),
							'dob' => date('Y-m-d', strtotime($this->input->post('dob'))));
			$id = $this->user_model->save($user);
			
			// set user message
			$data['message'] = '<div class="success">add new user success</div>';
		}
		
		// load view
		$this->load->view('userEdit', $data);
	}
	}
?>
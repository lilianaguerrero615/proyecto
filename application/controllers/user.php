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
		$this->table->set_heading('Id','Cedula', 'Contrasena', 'Tipo', 'Status','Administrador','Creado','Modificado', 'Accion');
		$i = 0 + $offset;
		foreach ($users as $user)
		{
			$this->table->add_row($user->id,$user->document_number, $user->password,$user->role,$user->status,$user->admin,$user->created_at,$user->updated_at, 
				anchor('user/view/'.$user->id,'Ver',array('class'=>'view')).' '.
				anchor('user/update/'.$user->id,'Actualizar',array('class'=>'update')).' '.
				anchor('user/delete/'.$user->id,'Borrar',array('class'=>'delete','onclick'=>"return confirm('Desea eliminar el usuario?')"))
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
		$data['title'] = 'Agregar usuario';
		$data['message'] = '';
		$data['action'] = site_url('user/adduser');
		$data['link_back'] = anchor('user/index/','Volver a la lista',array('class'=>'back'));
	
		// load view
		$this->load->view('userEdit', $data);
	}
	
	function adduser()
	{
		// set common properties
		$data['title'] = 'Agregar nuevo usuario';
		$data['action'] = site_url('user/adduser');
		$data['link_back'] = anchor('user/index/','Volver a la lista',array('class'=>'back'));
		
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
			$user = array('id' => $this->input->post('id'),
							'document_number' => $this->input->post('document_number'),
							'password' => $this->input->post('password'),
							'role' => $this->input->post('role'),
							'status' => $this->input->post('status'),
							'created_at' => date("Y-m-d H:i:s", time()),
							'updated_at' => date("Y-m-d H:i:s", time()));
							//'dob' => date('Y-m-d', strtotime($this->input->post('dob'))));
			$id = $this->user_model->save($user);
			
			// set user message
			$data['message'] = '<div class="success">usuario agregado</div>';
		}
		
		// load view
		$this->load->view('userEdit', $data);
	}
	
	function view($id)
	{
		// set common properties
		$data['title'] = 'Detalles';
		$data['link_back'] = anchor('user/index/','Volver a la lista',array('class'=>'back'));
		
		// get user details
		$data['user'] = $this->user_model->get_by_id($id)->row();
		
		// load view
		$this->load->view('userView', $data);
	}
	
	function update($id)
	{
		// set validation properties
		$this->_set_rules();
		
		// prefill form values
		$user = $this->user_model->get_by_id($id)->row();
		$this->form_data = new stdClass();
		$this->form_data->id = $user->id;
		$this->form_data->document_number = $user->document_number;
		$this->form_data->password = $user->password;
		$this->form_data->role = $user->role;
		$this->form_data->status = $user->status;
		$this->form_data->admin = $user->admin;
		//$this->form_data->dob = date('d-m-Y',strtotime($user->dob));
		
		// set common properties
		$data['title'] = 'Actualizar';
		$data['message'] = '';
		$data['action'] = site_url('user/updateuser');
		$data['link_back'] = anchor('user/index/','Volver a la lista',array('class'=>'back'));
	
		// load view
		$this->load->view('userEdit', $data);
	}
	
	function updateuser()
	{
		// set common properties
		$data['title'] = 'Actualizar';
		$data['action'] = site_url('user/updateuser');
		$data['link_back'] = anchor('user/index/','Volver a la lista',array('class'=>'back'));
		
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
			$id = $this->input->post('id');
			$now=date("Y-m-d H:i:s", time());
			$user = array('id' => $this->input->post('id'),
							'document_number' => $this->input->post('document_number'),
							'password' => $this->input->post('password'),
							'role' => $this->input->post('role'),
							'status' => $this->input->post('status'),
							'admin' => $this->input->post('admin'),
							'updated_at' => $now);
			$this->user_model->update($id,$user);
			
			// set user message
			$data['message'] = '<div class="success">usuario actualizado</div>';
		}
		
		// load view
		$this->load->view('userEdit', $data);
	}
	
	function delete($id)
	{
		// delete user
		$this->user_model->delete($id);
		
		// redirect to user list page
		redirect('user/index/','refresh');
	}
	
	// set empty default form field values

	function _set_fields()
	{
		$this->form_data = new stdClass();
		$this->form_data->id = '';
		$this->form_data->document_number = '';
		$this->form_data->password = '';
		$this->form_data->role = '';
		$this->form_data->status = '';
		$this->form_data->admin = '';
		//$this->form_data->created_at = '';
	}
	
	// validation rules
	function _set_rules()
	{
		$this->form_validation->set_rules('document_number', 'document_number', 'trim|required');
		$this->form_validation->set_rules('password', 'password', 'trim|required');
		$this->form_validation->set_rules('role', 'role', 'trim|required');
		$this->form_validation->set_rules('status', 'status', 'trim|required');
		$this->form_validation->set_rules('admin', 'admin', 'trim|required');
		//$this->form_validation->set_rules('dob', 'DoB', 'trim|required|callback_valid_date');
		
		$this->form_validation->set_message('required', '* requerido');
		$this->form_validation->set_message('isset', '* requerido');
		$this->form_validation->set_message('valid_date', 'date format is not valid. dd-mm-yyyy');
		$this->form_validation->set_error_delimiters('<p class="error">', '</p>');
	}
	
	// date_validation callback
	function valid_date($str)
	{
		//match the format of the date
		if (preg_match ("/^([0-9]{2})-([0-9]{2})-([0-9]{4})$/", $str, $parts))
		{
			//check weather the date is valid of not
			if(checkdate($parts[2],$parts[1],$parts[3]))
				return true;
			else
				return false;
		}
		else
			return false;
	}
}
?>
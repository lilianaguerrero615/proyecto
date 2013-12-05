<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class professor extends CI_Controller {

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
		$this->load->model('professor_model','',TRUE);
	}
	
	function index($offset = 0)
	{
		// offset
		$uri_segment = 3;
		$offset = $this->uri->segment($uri_segment);
		
		// load data
		$professors = $this->professor_model->get_paged_list($this->limit, $offset)->result();
		
		// generate pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url('professor/index/');
 		$config['total_rows'] = $this->professor_model->count_all();
 		$config['per_page'] = $this->limit;
		$config['uri_segment'] = $uri_segment;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		// generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('Id','Nombre', 'Apellido', 'Cedula', 'Correo','Creado','Modificado', 'Accion');
		$i = 0 + $offset;
		foreach ($professors as $professor)
		{
			$this->table->add_row($professor->id,$professor->first_name, $professor->last_name,$professor->document_number,$professor->email,$professor->created_at,$professor->updated_at, 
				anchor('professor/view/'.$professor->id,'Ver',array('class'=>'view')).' '.
				anchor('professor/update/'.$professor->id,'Actualizar',array('class'=>'update')).' '.
				anchor('professor/delete/'.$professor->id,'Borrar',array('class'=>'delete','onclick'=>"return confirm('Desea eliminar el profesor?')"))
			);
		}
		$data['table'] = $this->table->generate();
		
		// load view
		$this->load->view('professorList', $data);
	}
	
	function add()
	{
		// set empty default form field values
		$this->_set_fields();
		// set validation properties
		$this->_set_rules();
		
		// set common properties
		$data['title'] = 'Agregar Profesor';
		$data['message'] = '';
		$data['action'] = site_url('professor/addprofessor');
		$data['link_back'] = anchor('professor/index/','Volver a la lista',array('class'=>'back'));
	
		// load view
		$this->load->view('professorEdit', $data);
	}
	
	function addprofessor()
	{
		// set common properties
		$data['title'] = 'Agregar nuevo profesor';
		$data['action'] = site_url('professor/addprofessor');
		$data['link_back'] = anchor('professor/index/','Volver a la lista',array('class'=>'back'));
		
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
			$professor = array('id' => $this->input->post('id'),
							'first_name' => $this->input->post('first_name'),
							'last_name' => $this->input->post('last_name'),
							'document_number' => $this->input->post('document_number'),
							'email' => $this->input->post('email'),
							'created_at' => date("Y-m-d H:i:s", time()),
							'updated_at' => date("Y-m-d H:i:s", time()));
							//'dob' => date('Y-m-d', strtotime($this->input->post('dob'))));
			$id = $this->professor_model->save($professor);
			
			// set professor message
			$data['message'] = '<div class="success">Profesor agregado</div>';
		}
		
		// load view
		$this->load->view('professorEdit', $data);
	}
	
	function view($id)
	{
		// set common properties
		$data['title'] = 'Detalles';
		$data['link_back'] = anchor('professor/index/','Volver a la lista',array('class'=>'back'));
		
		// get professor details
		$data['professor'] = $this->professor_model->get_by_id($id)->row();
		
		// load view
		$this->load->view('professorView', $data);
	}
	
	function update($id)
	{
		// set validation properties
		$this->_set_rules();
		
		// prefill form values
		$professor = $this->professor_model->get_by_id($id)->row();
		$this->form_data = new stdClass();
		$this->form_data->id = $professor->id;
		$this->form_data->first_name = $professor->first_name;
		$this->form_data->last_name = $professor->last_name;
		$this->form_data->document_number = $professor->document_number;
		$this->form_data->email = $professor->email;
		//$this->form_data->dob = date('d-m-Y',strtotime($professor->dob));
		
		// set common properties
		$data['title'] = 'Actualizar';
		$data['message'] = '';
		$data['action'] = site_url('professor/updateprofessor');
		$data['link_back'] = anchor('professor/index/','Volver a la lista',array('class'=>'back'));
	
		// load view
		$this->load->view('professorEdit', $data);
	}
	
	function updateprofessor()
	{
		// set common properties
		$data['title'] = 'Actualizar';
		$data['action'] = site_url('professor/updateprofessor');
		$data['link_back'] = anchor('professor/index/','Volver a la lista',array('class'=>'back'));
		
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
			$professor = array('id' => $this->input->post('id'),
							'first_name' => $this->input->post('first_name'),
							'last_name' => $this->input->post('last_name'),
							'document_number' => $this->input->post('document_number'),
							'email' => $this->input->post('email'),
							'updated_at' => $now);
			$this->professor_model->update($id,$professor);
			
			// set professor message
			$data['message'] = '<div class="success">Profesor actualizado</div>';
		}
		
		// load view
		$this->load->view('professorEdit', $data);
	}
	
	function delete($id)
	{
		// delete professor
		$this->professor_model->delete($id);
		
		// redirect to professor list page
		redirect('professor/index/','refresh');
	}
	
	// set empty default form field values
	function _set_fields()
	{
		$this->form_data = new stdClass();
		$this->form_data->id = '';
		$this->form_data->first_name = '';
		$this->form_data->last_name = '';
		$this->form_data->document_number = '';
		$this->form_data->email = '';
		//$this->form_data->created_at = '';
	}
	
	// validation rules
	function _set_rules()
	{
		$this->form_validation->set_rules('first_name', 'Nombre', 'trim|required');
		$this->form_validation->set_rules('last_name', 'Apellido', 'trim|required');
		$this->form_validation->set_rules('document_number', 'Cedula', 'trim|required');
		$this->form_validation->set_rules('email', 'Correo', 'trim|required');
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
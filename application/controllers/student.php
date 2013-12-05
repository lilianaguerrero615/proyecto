<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class student extends CI_Controller {

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
		$this->load->model('student_model','',TRUE);
	}
	
	function index($offset = 0)
	{
		// offset
		$uri_segment = 3;
		$offset = $this->uri->segment($uri_segment);
		
		// load data
		$students = $this->student_model->get_paged_list($this->limit, $offset)->result();
		
		// generate pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url('student/index/');
 		$config['total_rows'] = $this->student_model->count_all();
 		$config['per_page'] = $this->limit;
		$config['uri_segment'] = $uri_segment;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		// generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('Id','Nombre', 'Apellido', 'Cedula', 'Correo','Creado','Modificado', 'Accion');
		$i = 0 + $offset;
		foreach ($students as $student)
		{
			$this->table->add_row($student->id,$student->first_name, $student->last_name,$student->document_number,$student->email,$student->created_at,$student->updated_at, 
				anchor('student/view/'.$student->id,'Ver',array('class'=>'view')).' '.
				anchor('student/update/'.$student->id,'Actualizar',array('class'=>'update')).' '.
				anchor('student/delete/'.$student->id,'Borrar',array('class'=>'delete','onclick'=>"return confirm('Desea eliminar el estudiante?')"))
			);
		}
		$data['table'] = $this->table->generate();
		
		// load view
		$this->load->view('studentList', $data);
	}
	
	function add()
	{
		// set empty default form field values
		$this->_set_fields();
		// set validation properties
		$this->_set_rules();
		
		// set common properties
		$data['title'] = 'Agregar Estudiante';
		$data['message'] = '';
		$data['action'] = site_url('student/addstudent');
		$data['link_back'] = anchor('student/index/','Volver a la lista',array('class'=>'back'));
	
		// load view
		$this->load->view('studentEdit', $data);
	}
	
	function addstudent()
	{
		// set common properties
		$data['title'] = 'Agregar nuevo estudiante';
		$data['action'] = site_url('student/addstudent');
		$data['link_back'] = anchor('student/index/','Volver a la lista',array('class'=>'back'));
		
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
			$student = array('id' => $this->input->post('id'),
							'first_name' => $this->input->post('first_name'),
							'last_name' => $this->input->post('last_name'),
							'document_number' => $this->input->post('document_number'),
							'email' => $this->input->post('email'),
							'created_at' => date("Y-m-d H:i:s", time()),
							'updated_at' => date("Y-m-d H:i:s", time()));
							//'dob' => date('Y-m-d', strtotime($this->input->post('dob'))));
			$id = $this->student_model->save($student);
			
			// set student message
			$data['message'] = '<div class="success">Estudiante agregado</div>';
		}
		
		// load view
		$this->load->view('studentEdit', $data);
	}
	
	function view($id)
	{
		// set common properties
		$data['title'] = 'Detalles';
		$data['link_back'] = anchor('student/index/','Volver a la lista',array('class'=>'back'));
		
		// get student details
		$data['student'] = $this->student_model->get_by_id($id)->row();
		
		// load view
		$this->load->view('studentView', $data);
	}
	
	function update($id)
	{
		// set validation properties
		$this->_set_rules();
		
		// prefill form values
		$student = $this->student_model->get_by_id($id)->row();
		$this->form_data = new stdClass();
		$this->form_data->id = $student->id;
		$this->form_data->first_name = $student->first_name;
		$this->form_data->last_name = $student->last_name;
		$this->form_data->document_number = $student->document_number;
		$this->form_data->email = $student->email;
		//$this->form_data->dob = date('d-m-Y',strtotime($student->dob));
		
		// set common properties
		$data['title'] = 'Actualizar';
		$data['message'] = '';
		$data['action'] = site_url('student/updatestudent');
		$data['link_back'] = anchor('student/index/','Volver a la lista',array('class'=>'back'));
	
		// load view
		$this->load->view('studentEdit', $data);
	}
	
	function updatestudent()
	{
		// set common properties
		$data['title'] = 'Actualizar';
		$data['action'] = site_url('student/updatestudent');
		$data['link_back'] = anchor('student/index/','Volver a la lista',array('class'=>'back'));
		
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
			$student = array('id' => $this->input->post('id'),
							'first_name' => $this->input->post('first_name'),
							'last_name' => $this->input->post('last_name'),
							'document_number' => $this->input->post('document_number'),
							'email' => $this->input->post('email'),
							'updated_at' => $now);
			$this->student_model->update($id,$student);
			
			// set student message
			$data['message'] = '<div class="success">Estudiante actualizado</div>';
		}
		
		// load view
		$this->load->view('studentEdit', $data);
	}
	
	function delete($id)
	{
		// delete student
		$this->student_model->delete($id);
		
		// redirect to student list page
		redirect('student/index/','refresh');
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
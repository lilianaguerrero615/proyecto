<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class course extends CI_Controller {

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
		$this->load->model('course_model','',TRUE);
	}
	
	function index($offset = 0)
	{
		// offset
		$uri_segment = 3;
		$offset = $this->uri->segment($uri_segment);
		
		// load data
		$courses = $this->course_model->get_paged_list($this->limit, $offset)->result();
		
		// generate pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url('course/index/');
 		$config['total_rows'] = $this->course_model->count_all();
 		$config['per_page'] = $this->limit;
		$config['uri_segment'] = $uri_segment;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		// generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('Id','Codigo', 'Nombre', 'Descripcion','Creado','Modificado', 'Accion');
		$i = 0 + $offset;
		foreach ($courses as $course)
		{
			$this->table->add_row($course->id,$course->code, $course->name,$course->description,$course->created_at,$course->updated_at, 
				anchor('course/view/'.$course->id,'Ver',array('class'=>'view')).' '.
				anchor('course/update/'.$course->id,'Actualizar',array('class'=>'update')).' '.
				anchor('course/delete/'.$course->id,'Borrar',array('class'=>'delete','onclick'=>"return confirm('Desea eliminar el curso?')"))
			);
		}
		$data['table'] = $this->table->generate();
		
		// load view
		$this->load->view('courseList', $data);
	}
	
	function add()
	{
		// set empty default form field values
		$this->_set_fields();
		// set validation properties
		$this->_set_rules();
		
		// set common properties
		$data['title'] = 'Agregar Curso';
		$data['message'] = '';
		$data['action'] = site_url('course/addcourse');
		$data['link_back'] = anchor('course/index/','Volver a la lista',array('class'=>'back'));
	
		// load view
		$this->load->view('courseEdit', $data);
	}
	
	function addcourse()
	{
		// set common properties
		$data['title'] = 'Agregar nuevo curso';
		$data['action'] = site_url('course/addcourse');
		$data['link_back'] = anchor('course/index/','Volver a la lista',array('class'=>'back'));
		
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
			$course = array('id' => $this->input->post('id'),
							'code' => $this->input->post('code'),
							'name' => $this->input->post('name'),
							'description' => $this->input->post('description'),
							'created_at' => date("Y-m-d H:i:s", time()),
							'updated_at' => date("Y-m-d H:i:s", time()));
							//'dob' => date('Y-m-d', strtotime($this->input->post('dob'))));
			$id = $this->course_model->save($course);
			
			// set course message
			$data['message'] = '<div class="success">Curso agregado</div>';
		}
		
		// load view
		$this->load->view('courseEdit', $data);
	}
	
	function view($id)
	{
		// set common properties
		$data['title'] = 'Detalles';
		$data['link_back'] = anchor('course/index/','Volver a la lista',array('class'=>'back'));
		
		// get course details
		$data['course'] = $this->course_model->get_by_id($id)->row();
		
		// load view
		$this->load->view('courseView', $data);
	}
	
	function update($id)
	{
		// set validation properties
		$this->_set_rules();
		
		// prefill form values
		$course = $this->course_model->get_by_id($id)->row();
		$this->form_data = new stdClass();
		$this->form_data->id = $course->id;
		$this->form_data->code = $course->code;
		$this->form_data->name = $course->name;
		$this->form_data->description = $course->description;
		//$this->form_data->dob = date('d-m-Y',strtotime($course->dob));
		
		// set common properties
		$data['title'] = 'Actualizar';
		$data['message'] = '';
		$data['action'] = site_url('course/updatecourse');
		$data['link_back'] = anchor('course/index/','Volver a la lista',array('class'=>'back'));
	
		// load view
		$this->load->view('courseEdit', $data);
	}
	
	function updatecourse()
	{
		// set common properties
		$data['title'] = 'Actualizar';
		$data['action'] = site_url('course/updatecourse');
		$data['link_back'] = anchor('course/index/','Volver a la lista',array('class'=>'back'));
		
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
			$course = array('id' => $this->input->post('id'),
							'code' => $this->input->post('code'),
							'name' => $this->input->post('name'),
							'description' => $this->input->post('description'),
							'updated_at' => $now);
			$this->course_model->update($id,$course);
			
			// set course message
			$data['message'] = '<div class="success">Curso actualizado</div>';
		}
		
		// load view
		$this->load->view('courseEdit', $data);
	}
	
	function delete($id)
	{
		// delete course
		$this->course_model->delete($id);
		
		// redirect to course list page
		redirect('course/index/','refresh');
	}
	
	// set empty default form field values
	function _set_fields()
	{
		$this->form_data = new stdClass();
		$this->form_data->id = '';
		$this->form_data->code = '';
		$this->form_data->name = '';
		$this->form_data->description = '';
		//$this->form_data->created_at = '';
	}
	
	// validation rules
	function _set_rules()
	{
		$this->form_validation->set_rules('code', 'Codigo', 'trim|required');
		$this->form_validation->set_rules('name', 'Nombre', 'trim|required');
		$this->form_validation->set_rules('description', 'Descripcion', 'trim|required');
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
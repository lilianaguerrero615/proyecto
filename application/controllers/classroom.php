<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class classroom extends CI_Controller {

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
		$this->load->model('classroom_model','',TRUE);
	}
	
	function index($offset = 0)
	{
		// offset
		$uri_segment = 3;
		$offset = $this->uri->segment($uri_segment);
		
		// load data
		$classrooms = $this->classroom_model->get_paged_list($this->limit, $offset)->result();
		
		// generate pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url('classroom/index/');
 		$config['total_rows'] = $this->classroom_model->count_all();
 		$config['per_page'] = $this->limit;
		$config['uri_segment'] = $uri_segment;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		// generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('Id','Nombre', 'Descripcion','Creado','Modificado', 'Accion');
		$i = 0 + $offset;
		foreach ($classrooms as $classroom)
		{
			$this->table->add_row($classroom->id,$classroom->name, $classroom->description,$classroom->created_at,$classroom->updated_at, 
				anchor('classroom/view/'.$classroom->id,'Ver',array('class'=>'view')).' '.
				anchor('classroom/update/'.$classroom->id,'Actualizar',array('class'=>'update')).' '.
				anchor('classroom/delete/'.$classroom->id,'Borrar',array('class'=>'delete','onclick'=>"return confirm('Desea eliminar el aula?')"))
			);
		}
		$data['table'] = $this->table->generate();
		
		// load view
		$this->load->view('classroomList', $data);
	}
	
	function add()
	{
		// set empty default form field values
		$this->_set_fields();
		// set validation properties
		$this->_set_rules();
		
		// set common properties
		$data['title'] = 'Agregar Aula';
		$data['message'] = '';
		$data['action'] = site_url('classroom/addclassroom');
		$data['link_back'] = anchor('classroom/index/','Volver a la lista',array('class'=>'back'));
	
		// load view
		$this->load->view('classroomEdit', $data);
	}
	
	function addclassroom()
	{
		// set common properties
		$data['title'] = 'Agregar aula';
		$data['action'] = site_url('classroom/addclassroom');
		$data['link_back'] = anchor('classroom/index/','Volver a la lista',array('class'=>'back'));
		
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
			$classroom = array('id' => $this->input->post('id'),
							'name' => $this->input->post('name'),
							'description' => $this->input->post('description'),
							'created_at' => date("Y-m-d H:i:s", time()),
							'updated_at' => date("Y-m-d H:i:s", time()));
							//'dob' => date('Y-m-d', strtotime($this->input->post('dob'))));
			$id = $this->classroom_model->save($classroom);
			
			// set classroom message
			$data['message'] = '<div class="success">Aula agregada</div>';
		}
		
		// load view
		$this->load->view('classroomEdit', $data);
	}
	
	function view($id)
	{
		// set common properties
		$data['title'] = 'Detalles';
		$data['link_back'] = anchor('classroom/index/','Volver a la lista',array('class'=>'back'));
		
		// get classroom details
		$data['classroom'] = $this->classroom_model->get_by_id($id)->row();
		
		// load view
		$this->load->view('classroomView', $data);
	}
	
	function update($id)
	{
		// set validation properties
		$this->_set_rules();
		
		// prefill form values
		$classroom = $this->classroom_model->get_by_id($id)->row();
		$this->form_data = new stdClass();
		$this->form_data->id = $classroom->id;
		$this->form_data->name = $classroom->name;
		$this->form_data->description = $classroom->description;
		//$this->form_data->dob = date('d-m-Y',strtotime($classroom->dob));
		
		// set common properties
		$data['title'] = 'Actualizar';
		$data['message'] = '';
		$data['action'] = site_url('classroom/updateclassroom');
		$data['link_back'] = anchor('classroom/index/','Volver a la lista',array('class'=>'back'));
	
		// load view
		$this->load->view('classroomEdit', $data);
	}
	
	function updateclassroom()
	{
		// set common properties
		$data['title'] = 'Actualizar';
		$data['action'] = site_url('classroom/updateclassroom');
		$data['link_back'] = anchor('classroom/index/','Volver a la lista',array('class'=>'back'));
		
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
			$classroom = array('id' => $this->input->post('id'),
							'name' => $this->input->post('name'),
							'updated_at' => $now);
			$this->classroom_model->update($id,$classroom);
			
			// set classroom message
			$data['message'] = '<div class="success">Aula actualizada</div>';
		}
		
		// load view
		$this->load->view('classroomEdit', $data);
	}
	
	function delete($id)
	{
		// delete classroom
		$this->classroom_model->delete($id);
		
		// redirect to classroom list page
		redirect('classroom/index/','refresh');
	}
	
	// set empty default form field values
	function _set_fields()
	{
		$this->form_data = new stdClass();
		$this->form_data->id = '';
		$this->form_data->name = '';
		$this->form_data->description = '';
		//$this->form_data->created_at = '';
	}
	
	// validation rules
	function _set_rules()
	{
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
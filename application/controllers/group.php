<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class group extends CI_Controller {

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
		$this->load->model('group_model','',TRUE);
	}
	
	function index($offset = 0)
	{
		// offset
		$uri_segment = 3;
		$offset = $this->uri->segment($uri_segment);
		
		// load data
		$groups = $this->group_model->get_paged_list($this->limit, $offset)->result();
		
		// generate pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url('group/index/');
 		$config['total_rows'] = $this->group_model->count_all();
 		$config['per_page'] = $this->limit;
		$config['uri_segment'] = $uri_segment;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		// generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('Id','Curso ID','Quarter', 'Profesor ID', 'Grupo numero', 'Disponible','Creado','Modificado', 'Accion');
		$i = 0 + $offset;
		foreach ($groups as $group)
		{
			$this->table->add_row($group->id,$group->course_id, $group->quarter,$group->professor_id,$group->group_number,$group->enabled,$group->created_at,$group->updated_at, 
				anchor('group/view/'.$group->id,'Ver',array('class'=>'view')).' '.
				anchor('group/update/'.$group->id,'Actualizar',array('class'=>'update')).' '.
				anchor('group/delete/'.$group->id,'Borrar',array('class'=>'delete','onclick'=>"return confirm('Desea eliminar el Grupo?')"))
			);
		}
		$data['table'] = $this->table->generate();
		
		// load view
		$this->load->view('groupList', $data);
	}
	
	function add()
	{
		// set empty default form field values
		$this->_set_fields();
		// set validation properties
		$this->_set_rules();
		
		// set common properties
		$data['title'] = 'Agregar Grupo';
		$data['message'] = '';
		$data['action'] = site_url('group/addgroup');
		$data['link_back'] = anchor('group/index/','Volver a la lista',array('class'=>'back'));
	
		// load view
		$this->load->view('groupEdit', $data);
	}
	
	function addgroup()
	{
		// set common properties
		$data['title'] = 'Agregar nuevo Grupo';
		$data['action'] = site_url('group/addgroup');
		$data['link_back'] = anchor('group/index/','Volver a la lista',array('class'=>'back'));
		
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
			$group = array('id' => $this->input->post('id'),
							'course_id' => $this->input->post('course_id'),
							'quarter' => $this->input->post('quarter'),
							'professor_id' => $this->input->post('professor_id'),
							'group_number' => $this->input->post('group_number'),
							'enabled' => $this->input->post('enabled'),
							'created_at' => date("Y-m-d H:i:s", time()),
							'updated_at' => date("Y-m-d H:i:s", time()),
							'enabled' => 1);
							//'dob' => date('Y-m-d', strtotime($this->input->post('dob'))));
			$id = $this->group_model->save($group);
			
			// set group message
			$data['message'] = '<div class="success">Grupo agregado</div>';
		}
		
		// load view
		$this->load->view('groupEdit', $data);
	}
	
	function view($id)
	{
		// set common properties
		$data['title'] = 'Detalles';
		$data['link_back'] = anchor('group/index/','Volver a la lista',array('class'=>'back'));
		
		// get group details
		$data['group'] = $this->group_model->get_by_id($id)->row();
		
		// load view
		$this->load->view('groupView', $data);
	}
	
	function update($id)
	{
		// set validation properties
		$this->_set_rules();
		
		// prefill form values
		$group = $this->group_model->get_by_id($id)->row();
		$this->form_data = new stdClass();
		$this->form_data->id = $group->id;
		$this->form_data->course_id = $group->course_id;
		$this->form_data->quarter = $group->quarter;
		$this->form_data->professor_id = $group->professor_id;
		$this->form_data->group_number = $group->group_number;
		$this->form_data->enabled = $group->enabled;
		//$this->form_data->dob = date('d-m-Y',strtotime($group->dob));
		
		// set common properties
		$data['title'] = 'Actualizar';
		$data['message'] = '';
		$data['action'] = site_url('group/updategroup');
		$data['link_back'] = anchor('group/index/','Volver a la lista',array('class'=>'back'));
	
		// load view
		$this->load->view('groupEdit', $data);
	}
	
	function updategroup()
	{
		// set common properties
		$data['title'] = 'Actualizar';
		$data['action'] = site_url('group/updategroup');
		$data['link_back'] = anchor('group/index/','Volver a la lista',array('class'=>'back'));
		
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
			$group = array('id' => $this->input->post('id'),
							'course_id' => $this->input->post('course_id'),
							'quarter' => $this->input->post('quarter'),
							'professor_id' => $this->input->post('professor_id'),
							'group_number' => $this->input->post('group_number'),
							'enabled' => $this->input->post('enabled'),
							'updated_at' => $now);
			$this->group_model->update($id,$group);
			
			// set group message
			$data['message'] = '<div class="success">Grupo actualizado</div>';
		}
		
		// load view
		$this->load->view('groupEdit', $data);
	}
	
	function delete($id)
	{
		// delete group
		$this->group_model->delete($id);
		
		// redirect to group list page
		redirect('group/index/','refresh');
	}
	
	// set empty default form field values
	function _set_fields()
	{
		$this->form_data = new stdClass();
		$this->form_data->id = '';
		$this->form_data->course_id = '';
		$this->form_data->quarter = '';
		$this->form_data->professor_id = '';
		$this->form_data->group_number = '';
		$this->form_data->enabled = '';
		//$this->form_data->created_at = '';
	}
	
	// validation rules
	function _set_rules()
	{
		$this->form_validation->set_rules('course_id', 'Curso ID', 'trim|required');
		$this->form_validation->set_rules('quarter', 'Quarter', 'trim|required');
		$this->form_validation->set_rules('professor_id', 'Profesor ID', 'trim|required');
		$this->form_validation->set_rules('group_number', 'N° Grupo', 'trim|required');
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
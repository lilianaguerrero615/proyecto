<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class dashboard extends CI_Controller {
	
	function index($offset = 0)
	{
		$this->load->view('dashboard', null);
	}

}
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class login extends CI_Controller {
	
	function index($offset = 0)
	{
		$this->load->view('login', null);
	}

}
?>
<?php
class student_model extends CI_Model {
	
	private $student= 'student';
	
	function __construct(){
		parent::__construct();
	}
	
	function list_all(){
		$this->db->order_by('id','asc');
		return $this->db->get($student);
	}
	
	function count_all(){
		return $this->db->count_all($this->student);
	}
	
	function get_paged_list($limit = 10, $offset = 0){
		$this->db->order_by('id','asc');
		return $this->db->get($this->student, $limit, $offset);
	}
	
	function get_by_id($id){
		$this->db->where('id', $id);
		return $this->db->get($this->student);
	}
	
	function save($student){
		$this->db->insert($this->student, $student);
		return $this->db->insert_id();
	}
	
	function update($id, $student){
		$this->db->where('id', $id);
		$this->db->update($this->student, $student);
	}
	
	function delete($id){
		$this->db->where('id', $id);
		$this->db->delete($this->student);
	}
}
?>
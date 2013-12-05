<?php
class course_model extends CI_Model {
	
	private $course= 'course';
	
	function __construct(){
		parent::__construct();
	}
	
	function list_all(){
		$this->db->order_by('id','asc');
		return $this->db->get($course);
	}
	
	function count_all(){
		return $this->db->count_all($this->course);
	}
	
	function get_paged_list($limit = 10, $offset = 0){
		$this->db->order_by('id','asc');
		return $this->db->get($this->course, $limit, $offset);
	}
	
	function get_by_id($id){
		$this->db->where('id', $id);
		return $this->db->get($this->course);
	}
	
	function save($course){
		$this->db->insert($this->course, $course);
		return $this->db->insert_id();
	}
	
	function update($id, $course){
		$this->db->where('id', $id);
		$this->db->update($this->course, $course);
	}
	
	function delete($id){
		$this->db->where('id', $id);
		$this->db->delete($this->course);
	}
}
?>
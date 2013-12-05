<?php
class classroom_model extends CI_Model {
	
	private $classroom= 'classroom';
	
	function __construct(){
		parent::__construct();
	}
	
	function list_all(){
		$this->db->order_by('id','asc');
		return $this->db->get($classroom);
	}
	
	function count_all(){
		return $this->db->count_all($this->classroom);
	}
	
	function get_paged_list($limit = 10, $offset = 0){
		$this->db->order_by('id','asc');
		return $this->db->get($this->classroom, $limit, $offset);
	}
	
	function get_by_id($id){
		$this->db->where('id', $id);
		return $this->db->get($this->classroom);
	}
	
	function save($classroom){
		$this->db->insert($this->classroom, $classroom);
		return $this->db->insert_id();
	}
	
	function update($id, $classroom){
		$this->db->where('id', $id);
		$this->db->update($this->classroom, $classroom);
	}
	
	function delete($id){
		$this->db->where('id', $id);
		$this->db->delete($this->classroom);
	}
}
?>
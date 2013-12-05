<?php
class professor_model extends CI_Model {
	
	private $professor= 'professor';
	
	function __construct(){
		parent::__construct();
	}
	
	function list_all(){
		$this->db->order_by('id','asc');
		return $this->db->get($professor);
	}
	
	function count_all(){
		return $this->db->count_all($this->professor);
	}
	
	function get_paged_list($limit = 10, $offset = 0){
		$this->db->order_by('id','asc');
		return $this->db->get($this->professor, $limit, $offset);
	}
	
	function get_by_id($id){
		$this->db->where('id', $id);
		return $this->db->get($this->professor);
	}
	
	function save($person){
		$this->db->insert($this->professor, $person);
		return $this->db->insert_id();
	}
	
	function update($id, $person){
		$this->db->where('id', $id);
		$this->db->update($this->professor, $person);
	}
	
	function delete($id){
		$this->db->where('id', $id);
		$this->db->delete($this->professor);
	}
}
?>
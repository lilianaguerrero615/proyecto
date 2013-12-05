<?php
class group_model extends CI_Model {
	
	private $group= '_group';
	
	function __construct(){
		parent::__construct();
	}
	
	function list_all(){
		$this->db->order_by('id','asc');
		return $this->db->get($group);
	}
	
	function count_all(){
		return $this->db->count_all($this->group);
	}
	
	function get_paged_list($limit = 10, $offset = 0){
		$this->db->order_by('id','asc');
		return $this->db->get($this->group, $limit, $offset);
	}
	
	function get_by_id($id){
		$this->db->where('id', $id);
		return $this->db->get($this->group);
	}
	
	function save($group){
		$this->db->insert($this->group, $group);
		return $this->db->insert_id();
	}
	
	function update($id, $group){
		$this->db->where('id', $id);
		$this->db->update($this->group, $group);
	}
	
	function delete($id){
		$this->db->where('id', $id);
		$this->db->delete($this->group);
	}
}
?>
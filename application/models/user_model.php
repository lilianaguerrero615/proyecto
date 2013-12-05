<?php
class user_model extends CI_Model {
	
	private $user= 'user';
	
	function __construct(){
		parent::__construct();
	}
	
	function list_all(){
		$this->db->order_by('id','asc');
		return $this->db->get($user);
	}
	
	function count_all(){
		return $this->db->count_all($this->user);
	}
	
	function get_paged_list($limit = 10, $offset = 0){
		$this->db->order_by('id','asc');
		return $this->db->get($this->user, $limit, $offset);
	}
	
	function get_by_id($id){
		$this->db->where('id', $id);
		return $this->db->get($this->user);
	}
	
	function save($user){
		$this->db->insert($this->user, $user);
		return $this->db->insert_id();
	}
	
	function update($id, $user){
		$this->db->where('id', $id);
		$this->db->update($this->user, $user);
	}
	
	function delete($id){
		$this->db->where('id', $id);
		$this->db->delete($this->user);
	}
}
?>
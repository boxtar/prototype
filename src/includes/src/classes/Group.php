<?php 	# Group class extending Base_Account class for base functionality
		# Specific Groups will extend from this

class Group extends Base_Account{

	/*	FIELDS	*/
	private $_member_list;

	public function __construct($group=null){
		parent::__construct();
		if($group){
			$table = 'groups';
			if($this->find($group, $table, ['id', 'prof_link'])){
				$this->populate_member_list();
			}
		}
	}
	
	public function populate_member_list(){
		if($this->exists() && !$this->get_member_list()){
			$table = 'groups';
			$sql = "SELECT users.prof_link FROM ".GROUPS_TABLE." AS group_table INNER JOIN ".USERS_TO_GROUPS_INTERMEDIARY." AS users_group_match ON (group_table.id=users_group_match.am_id) INNER JOIN users ON (users_group_match.u_id=users.id) WHERE group_table.id=? AND users_group_match.active!=0";
			if(empty($this->_db->query($sql, [$this->data()->id])->errors())){
				$query_results=$this->_db->results();
				if(!empty($query_results)){
					foreach($query_results as $result)
						$this->_member_list[]=new User($result->prof_link);
					return true;
				}
			}
		}
		return false;
	}
	
	public function get_member_list(){
		return !empty($this->_member_list) ? $this->_member_list : false;
	}
	
	public function get_genre(){
		if($this->exists()){
			$sql = "SELECT genres.genre FROM groups INNER JOIN genres ON (groups.genre=genres.id) WHERE groups.id=?";
			if( empty($this->_db->query($sql, [$this->data()->id])->errors()) && $this->_db->num_rows()==1 ){
				return $this->_db->first_result()->genre;
			}
		}
		return false;
	}
	
	public function group_type(){
		if($this->exists()){
			return $this->data()->group_type;
		}
	}
	
	public function active(){
		if($this->exists())
			return $this->data()->active>0 ? true : false;
		return false;
	}
}

?>

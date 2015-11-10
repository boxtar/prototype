<?php

class Group_Manager{
	/* This single instance */
	private static $_instance = null;
	/* DB Connection */
	private $_db;
	/* Columns in the intermediary table that can be updated */
	private $_updateable_fields = ['active', 'status_flag', 'group_type'];
	/* Constants to represent group types: */
	const MUSIC = 1, DANCE = 2 , COMEDY = 3;
	
	/*
	 * SINGLETON PATTERN
	 */
	private function __construct(){
		$this->_db = DB::getInstance();
	}
	public static function getInstance(){
		if(!isset(self::$_instance)){
			self::$_instance = new Group_Manager();
		}
		return self::$_instance;
	}

	public function create_new_group($user, $fields=[]){
		$sql="INSERT INTO groups (name, genre, prof_link, group_type, date_reg) VALUES (?, ?, ?, ?, NOW())";
		$values=["{$fields['name']}", "{$fields['genre']}", preg_replace('/[ \')(&-.]/', '', trim(strtolower($fields['name']), " ")).'.'.uniqid(rand()), $fields['type']];
		// Insert record to groups
		if(!empty($this->_db->query($sql, $values)->errors()) && $this->_db->num_rows()==0){
			return false;
		}
		// newly created groups ID:
		$new_id = (int)$this->_db->last_insert_id();
		if($this->insert_into_intermediary_table($user->data()->id, $new_id, OWNER)){
			$group = new Music_Group($new_id);
			if($this->create_new_group_directories($group, $fields['type']))
				return $group;
			else
				return false;
		}
		else{
			// Delete entry from groups since insertion into intermediary failed:
			$this->_db->query("DELETE FROM groups WHERE id=?", [$new_id]);
		}
		return false;
	}
	
	/*
	 *	Insert record into USERS_TO_GROUPS_INTERMEDIARY table (Add a user to a group)
	 *	Separated the actual query to a private function so it can be reused
	 */
	private function insert_into_intermediary_table($user_id, $group_id, $status=NO_PERMISSIONS){
		$sql="INSERT INTO ".USERS_TO_GROUPS_INTERMEDIARY." (am_id, u_id, status_flag, date_reg) VALUES (?, ?, ?, NOW())";
		if(empty($this->_db->query($sql, [$group_id, $user_id, $status])->errors()) && $this->_db->num_rows()==1)
			return true;
		return false;
	}
	
	/**
	 * Private function.
	 * This functions sets the active field to 0 on the selected row within the intermediary.
	 * It doesn't actually delete anything from the table. This allows reactivation and historical reporting.
	 *
	 * @param User $user_id The user to be removed from the provided group.
	 * @param Group $group The group to remove the provided user from.
	 *
	 * @return bool True indicates successful removal, false otherwise.
	 */
	 private function remove_from_intermediary_table($user_id, $group_id){
	 	// Fetch row from intermediary table that is to be deactivated
	 	$sql="SELECT id FROM ".USERS_TO_GROUPS_INTERMEDIARY." WHERE u_id=? AND am_id=?";
	 	if(empty($this->_db->query($sql, [$user_id, $group_id])->errors()) && $this->_db->num_rows()==1){
	 		$intermediary_id = $this->_db->first_result()->id;
	 		$sql="UPDATE users_artist_music SET active = 0, date_closed = NOW() WHERE id=? LIMIT 1";
			if(empty($this->_db->query($sql, [$intermediary_id])->errors()))
				return true;
	 	}
	 	return false;
	 }
	
	private function create_new_group_directories($group, $group_type){
		// CREATE NEW GROUPS DIRECTORIES AND COPY DEFAULT FILES
		$group_name = $group->data()->prof_link;
		// Pre-set directory names:
		switch($group_type){
			case self::MUSIC:
				$group_type_upload_dir	=	MUSIC_GROUP_UPLOADS;
				$default_ava			=	'music_default.png';
				break;
			case self::DANCE:
				$group_type_upload_dir	=	DANCE_GROUP_UPLOADS;
				$default_ava			=	'boxtar.png';
				break;
			case self::COMEDY:
				$group_type_upload_dir	=	COMEDY_GROUP_UPLOADS;
				$default_ava			=	'boxtar.png';
				break;
			default:
				$group_type_upload_dir	=	MUSIC_GROUP_UPLOADS;
				$default_ava			=	'music_default.png';
				break;
		}
		
		$new_group_root_dir	=	$group_type_upload_dir.$group_name;
		$new_group_img_dir	=	$group_type_upload_dir.$group_name.'/img/prof'; // Recursive
		$new_group_aud_dir	=	$group_type_upload_dir.$group_name.'/aud';
		$new_group_vid_dir	=	$group_type_upload_dir.$group_name.'/vid';
		
		// CHECK NEW GROUP DIRECTORY DOES NOT EXIST:
		if(!is_dir($new_group_root_dir)){
			mkdir($new_group_root_dir, 0777);
			mkdir($new_group_img_dir, 0777, true); // @param-3: true enables recursive mode
			mkdir($new_group_aud_dir, 0777);
			mkdir($new_group_vid_dir, 0777);
			
			// Check everything went ok on the directory creating front then copy over default files:
			if(is_dir($new_group_root_dir) && is_dir($new_group_img_dir) && is_dir($new_group_aud_dir) && is_dir($new_group_vid_dir)){
				
				return copy( UPLOADS_DIR.$default_ava, $new_group_img_dir.'/default.png');
			}
		}
		return false;
	}
	
	/**
	 * Add the provided user to the provided group with the provided permissions
	 *
	 * @param User $user_to_add The user being added
	 * @param Group $group The group the user is being added to
	 * @param int $status Indicates the permissions to give the user
	 *
	 * @return mixed[string] 'status'=>bool indicating success or failure. 'msg'=>string Message that can be displayed
	 */
	public function add_user_to_group($user_to_add, $group, $status=NO_PERMISSIONS){
		// Create object for user invoking the script:
		$curr_user = new User();
		// Ensure all parties involved actually exists:
		if($curr_user->exists() && $user_to_add->exists() && $group->exists()){
			// Check the user trying to add a member is actually affiliated with and the owner of the group:
			// This check also ensures the group provided is active so don't need to check for that
			if($this->user_access_level($curr_user, $group) < OWNER  || !$this->is_user_active_member($curr_user, $group))
				return ['status'=>false, 'msg'=>'You do not have sufficient Access Rights for '.$group->name()];
				
			// Make sure current user isn't trying to add them-self to the group:
			if($curr_user->data()->id == $user_to_add->data()->id)
				return ['status'=>false, 'msg'=>'You cannot add yourself to a group you already own'];
			
			// Ensure user being added isn't already a member of the group:
			if($this->is_user_active_member($user_to_add, $group))
				return ['status'=>false, 'msg'=>$user_to_add->name().' is already a member of '.$group->name()];

			// Add the user to the group or reinstate them if they are a historical member:
			if($this->is_user_historical_member($user_to_add, $group)){
				// update USERS_TO_GROUPS_INTERMEDIARY table to reactivate user
				if($this->reactivate_user_with_group($user_to_add, $group))
					return ['status'=>true, 'msg'=>$user_to_add->name().' has rejoined '.$group->name()];
				else
					return ['status'=>false, 'msg'=>$user_to_add->name().' could not be reactivated with '.$group->name().'. Please try again or contact us for assistance'];
			}
			else{
				// add user to USERS_TO_GROUPS_INTERMEDIARY table
				if($this->insert_into_intermediary_table($user_to_add->data()->id, $group->data()->id, $status))
					return ['status'=>true, 'msg'=>$user_to_add->name().' has been added to '.$group->name()];
				else
					return ['status'=>false, 'msg'=>$user_to_add->name().' could not be added to '.$group->name().'. Please try again or contact us for assistance'];
			}
		}
		return ['status'=>false, 'msg'=>'Unable to add '.($user_to_add->exists() ? $user_to_add->name() : 'Invalid User').' to '.($group->exists() ? $group->name() : 'Invalid Group')];
	}
	
	/**
	 * Removes the provided user from the provided group.
	 *
	 * @param User $user_to_remove The user to be removed.
	 * @param Group $group The group to remove the user from.
	 *
	 * @return mixed[string] 'status'=>bool indicating success or failure. 'msg'=>string Message that can be displayed
	 */
	public function remove_user_from_group($user_to_remove, $group){
		// Create object for user invoking the script:
		$curr_user = new User();
		// Ensure all parties involved actually exists:
		if($curr_user->exists() && $user_to_remove->exists() && $group->exists()){
			// If current user is trying to remove themself then redirect to leave group:
			if($curr_user->profile_link()===$user_to_remove->profile_link())
				return ['status'=>false, 'msg'=>'Cannot remove yourself from a group'];
				
			// Ensure current user has sufficient access rights:
			if($this->user_access_level($curr_user, $group) < OWNER)
				return ['status'=>false, 'msg'=>'You do not have sufficient Access Rights for '.$group->name()];
				
			if(!$this->is_user_active_member($user_to_remove, $group))
				return ['status'=>false, 'msg'=>$user_to_remove->name().' is not a member of '.$group->name()];
			
			if($this->remove_from_intermediary_table($user_to_remove->data()->id, $group->data()->id))
				return ['status'=>true, 'msg'=>'User removed'];
			else
				return ['status'=>false, 'msg'=>'Could not remove'];
		}
		return ['status'=>false, 'msg'=>'Unable to remove '.$user_to_remove->exists() ? $user_to_remove->name() : 'Invalid User'.' from '.$group->exists() ? $group->name() : 'Invalid Group'];
	}
	
	/*
	 *	Reactivate a user with one of their historical groups
	 */
	public function reactivate_user_with_group($user, $group, $status=NO_PERMISSIONS){
		if($user->exists() && $group->exists()){
			if($this->is_user_historical_member($user, $group)){
				$sql="UPDATE ".USERS_TO_GROUPS_INTERMEDIARY." SET active=1, date_closed=NULL, status_flag=? WHERE am_id=? AND u_id=? LIMIT 1";
				if(empty($this->_db->query($sql, [$status, $group->data()->id, $user->data()->id])->errors())){
					if($this->_db->num_rows()==1)
						return true;
				}
			}
		}
		return false;
	}
	 
	 /*
	  *	Update a users group status
	  */
	public function update_user_group_status($user, $group, $status){
		return false;
	}
	
	/*
	 *	Get a given users access level for a given group
	 */
	public function user_access_level($user, $group){
		if($user->exists() && $group->exists()){
			$sql = "SELECT user_group_match.status_flag FROM ". USERS_TO_GROUPS_INTERMEDIARY ." AS user_group_match INNER JOIN groups AS `group` ON (user_group_match.am_id = `group`.id) WHERE (user_group_match.u_id=? AND `group`.id=? AND `group`.active!=0)";
			if(empty($this->_db->query($sql, [$user->data()->id, $group->data()->id])->errors())){
				if($this->_db->num_rows()==1)
					return $this->_db->first_result()->status_flag;
			}
		}
		return false;
	}
	
	/*
	 *	Check if a user is an active member of a group
	 */
	public function is_user_active_member($user, $group){
		if($user->exists() && $group->exists()){
			// Populate the users groups if not already done:
			if(!$user->are_groups_populated()){
				$user->populate_groups();
			}
			// Retrieve groups user is currently active with:
			$users_active_groups = $user->get_groups();
			// If users has no active groups then return false:
			if(!$user->get_groups()){
				return false;
			}
			// Got this far? There's at least one group to check then:
			foreach($users_active_groups as $active_group){
				if($active_group->data()->id == $group->data()->id)
					return true;
			}
		}
		return false;
	}
	
	/*
	 *	Check if a user used to be a member of a group
	 */
	public function is_user_historical_member($user, $group){
		if($user->exists() && $group->exists()){
			// Populate the users groups if not already done:
			if(!$user->are_groups_populated()){
				$user->populate_groups();
			}
			// Retrieve groups user is currently active with:
			$users_historical_groups = $user->get_historical_groups();
			// If users has no active groups then return false:
			if(!$user->get_historical_groups()){
				return false;
			}
			// Got this far? There's at least one group to check then:
			foreach($users_historical_groups as $historical_group){
				if($historical_group->data()->id == $group->data()->id)
					return true;
			}
		}
		return false;
	}
}

?>

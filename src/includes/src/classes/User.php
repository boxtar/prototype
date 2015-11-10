<?php 	# User class extending Base_Account class for base functionality

class User extends Base_Account{

	/* FIELDS */
	
	// Logged in flag:
	private $_logged_in;
	// Flag that is set to true once groups populated:
	private $_groups_populated;
	// List of groups user is an active member of:
	private $_groups;
	// List of groups user used to be a member of (for historical viewing):
	private $_historical_groups;
	
	/* METHODS */
	
	public function __construct($user=null){
		parent::__construct();
		$this->_logged_in=false;
		$this->_groups_populated=false;
		$this->_groups=[];
		$this->_historical_groups=[];
		
		if(!$user){
			// add remember me functionality. Need to be aware of hackers sniffing the cookie, though.
			if(Session::exists('id')){
				$user = Session::get('id');
				if($this->find($user)){
					$this->_logged_in = true;
					$this->populate_groups();
				}
				else{
					$this->logout();
				}
			}
		}
		else{
			$this->find($user, 'users', ['email', 'prof_link']);
		}
	}
	
	
	
	/* USER MANAGEMENT METHODS */
	
	public function register($fields=[]){
		// CHECK EMAIL IS UNIQUE:
		if(!$this->find($fields['email'], 'users', ['email'])){
			// if find() returned false then either of the following is true:
			// - Email address provided is unique and we're good to go
			// - Or there were errors querying the DB...
			if(empty($this->_db->errors())){
				// Set activation code depending on site status:
				$fields['active'] = LIVE ? md5(uniqid(rand(), true)) : NULL;
				// Create new user in DB:
				if(empty($this->_db->insert('users', $fields)->errors())){
					// Setup the new users directories and default files in file system:
					if(!$this->create_new_user_dir($fields['prof_link'])){
						trigger_error("Error registering new user: Failed to create new users directories in file system");
						print_err('<br/><center><h5 class="red uppercase">You have not been registered</h5></center><br/><br/>');
						include FOOTER;
						exit();
					}
					
					if(LIVE){
						// CONSTRUCT ACTIVATION EMAIL:
						$body = "Thank you for creating an account with Boxtar UK. To activate your account please follow this link:\n\n";
						// Add URL to activation script with required vars
						$body .= BASE_URL . 'activate_acc.php?_x004a=' . urlencode($fields['email']) . '&_y0030='.$fields['active'];
						// send activation email
						mail($fields['email'], 'Boxtar UK - Activate Your Box', $body , 'From: donotreply@boxtar.uk');
						// Thank user
						echo '<div class="md-container"><h5>Thank you for creating an account with BOXTAR UK&nbsp;&copy;</h5>
							<p><br/><br/>An email has been sent to the provided email address. Please follow the link
							in that email to activate your account</p></div">';
					}
					else{
						echo <<<EOT
<div class="md-container"><h5>Thank you for creating an account with BOXTAR UK&nbsp;&copy;</h5></div">
EOT;
					}
					// Close page and kill script (form wont be re-shown)
					echo '</div><!-- boxtar-content -->';
					include FOOTER;
					exit();
				}
				else{
					trigger_error("Error inserting new user into DB in ".trim($_SERVER['SCRIPT_FILENAME'], '/')." (register function)<br/>DB Errors: ".implode('<br/>', $this->_db->errors()));
					print_err('<br/><center><h5 class="red uppercase">You have not been registered</h5></center><br/><br/>');
					include FOOTER;
					exit();
				}
			}
			else{
				trigger_error("Error registering new user in ".trim($_SERVER['SCRIPT_FILENAME'], '/')." (find function returned false)<br/>DB Errors: ".implode('<br/>', $this->_db->errors()));
				print_err('<br/><center><h5 class="red uppercase">You have not been registered</h5></center><br/><br/>');
				include FOOTER;
				exit();
			}
		}
		else{
			// find() returned true so a match was found
			print_err('<center><p class="red">The provided e-mail address has already been registered<br/><i><a href="pass_retrieval.php">(Forgotten your password?)</a></i></p></center>');
		}
	}
	
	private function create_new_user_dir($user){
		// CREATE NEW USERS DIRECTORIES AND COPY DEFAULT FILES
		// Pre-set directory names:
		$new_user_root_dir	=	USER_UPLOADS.$user;
		$new_user_img_dir	=	USER_UPLOADS.$user.'/img/prof'; // Recursive
		$new_user_aud_dir	=	USER_UPLOADS.$user.'/aud';
		$new_usr_vid_dir	=	USER_UPLOADS.$user.'/vid';
		
		// CHECK NEW USER DIRECTORY DOES NOT EXIST:
		if(!is_dir($new_user_root_dir)){
			mkdir($new_user_root_dir, 0777);
			mkdir($new_user_img_dir, 0777, true); // @param-3: true enables recursive mode
			mkdir($new_user_aud_dir, 0777);
			mkdir($new_usr_vid_dir, 0777);
			
			// Check everything went ok on the directory creating front then copy over default files:
			if(is_dir($new_user_root_dir) && is_dir($new_user_img_dir) && is_dir($new_user_aud_dir) && is_dir($new_usr_vid_dir)){
				return copy( UPLOADS_DIR.'default.jpg', $new_user_img_dir.'/default.jpg');
			}
		}
		return false;
	}
	
	public function login($user = null, $password = null){
		$status=[];
		if($this->find($user, 'users', ['email', 'prof_link'])){
			// Check passwords match:
			if($this->data()->password === $password){
				// Check account has been activated:
				if($this->data()->active === null){
					// Create Session Info and redirect user to home page:
					$_SESSION['id']				=	$this->data()->id;
					$_SESSION['first_name']		=	$this->data()->first_name;
					$_SESSION['last_name']		=	$this->data()->last_name;
					$_SESSION['prof_link']		=	$this->data()->prof_link;
					$_SESSION['access_level']	=	$this->data()->access_level;
					$this->_logged_in			=	true;
					return $status=['status'=>true, 'msg'=>'Successfully logged in'];
				}
				else
					return $status=['status'=>false, 'msg'=>'Account requires activation'];
			}
			else
				return $status=['status'=>false, 'msg'=>'Incorrect password'];
		}
		return $status=['status'=>false, 'msg'=>'User not found'];
	}
	
	public function logged_in(){
		return $this->_logged_in;
	}
	
	public function logout(){
		$_SESSION = array(); // Remove data from session file
		session_destroy(); // Destroy the actual session
		setcookie(session_name(), '', time()-400); // Delete session id cookie
	}
	
	public function update($fields=[], $id=null){
		$status=[];
		// Allowing a user id to be provided for updating provides functionality for admins to update other users:
		if(!$id && $this->logged_in()){
			$id = $this->data()->id;	
		}
		elseif($id && $id!==$this->data()->id && !$this->is_admin()){
			// A User ID has been provided
			// It isn't the same as the current Users ID (trying to update another user)
			// And the current User is NOT an admin - not allowed - return false:
			return $status=['status'=>false, 'msg'=>'You cannot change details of another User'];
		}
		
		if(!empty($fields)){
			if(isset($fields['email']) && $this->_db->query("SELECT id FROM users WHERE email=? AND id!=?", [$fields['email'], $id])->num_rows()!=0)
				return $status=['status'=>false, 'msg'=>'The provided email address is already in use'];
			if(isset($fields['prof_link']) && $this->_db->query("SELECT id FROM users WHERE prof_link=? AND id!=?", [$fields['prof_link'], $id])->num_rows()!=0)
				return $status=['status'=>false, 'msg'=>'The provided profile link is already in use'];				
			if(empty($this->_db->update('users', ['id' => $id], $fields)->errors())){
				if(isset($fields['first_name'])) Session::set('first_name', $fields['first_name']);
				if(isset($fields['last_name'])) Session::set('last_name', $fields['last_name']);
				if(isset($fields['prof_link']) && $fields['prof_link'] != $this->data()->prof_link){
					// Rename Users Upload Directory:
					$old_dir	=	USER_UPLOADS.Session::get('prof_link');
					$new_dir	=	USER_UPLOADS.$fields['prof_link'];
					// Now safe to update prof_link session
					Session::set('prof_link', $fields['prof_link']);
					if(!rename($old_dir, $new_dir)){
						include HEADER;
						include UTILITIES.'brand_img.inc.php';
						trigger_error('Error in User class update function: Failed to rename user directory ('.$old_dir.' to '.$new_dir.')');
						include FOOTER;
						exit();
					}
				}
				return $status=['status'=>true, 'msg'=>'Profile Updated'];
			}
			else{
				trigger_error("Error updating user in ".trim($_SERVER['SCRIPT_FILENAME'], '/')." (update function)<br/>DB Errors: ".implode('<br/>', $this->_db->errors()));
				echo '<br/><center><h5 class="red uppercase">System error: Details not updated</h5></center><br/><br/>';
				include FOOTER;
				exit();
			}
		}
		else
			return $status=['status'=>false, 'msg'=>'No fields provided to update'];
	}
	
	/**
	 * Returns the path of the users avatar relative to the offsite location
	 *
	 * @returns string
	 */
	public function avatar(){
		return $this->exists() ? 'user/'.$this->profile_link().'/img/prof/'.$this->data()->avatar_link : false;
	}
	
	public function type(){
		return 'user';
	}
	
	public function is_admin(){
		return ( $this->exists() ? ( $this->data()->access_level>0 ? true : false ) : false );
	}
	
	/****************************/
	/****************************/
	/* GROUP MANAGEMENT METHODS */
	/****************************/
	/****************************/
	
	public function populate_groups(){
		if($this->exists() && !$this->are_groups_populated()){
			$sql = "SELECT `group`.id, `group`.group_type, user_group_match.active AS user_active FROM users AS user INNER JOIN ".USERS_TO_GROUPS_INTERMEDIARY." AS user_group_match ON (user.id = user_group_match.u_id) INNER JOIN groups AS `group` ON (user_group_match.am_id = `group`.id) WHERE (user.id=? AND `group`.active != 0)";
			if(empty($this->_db->query($sql, [$this->data()->id])->errors())){
				$this->_groups_populated = true;
				if(count($this->_db->results())){
					$groups = $this->_db->results();
					foreach($groups as $group){
						switch($group->group_type){
							case Group_Manager::MUSIC:
								$group->user_active ? $this->_groups[] = new Music_Group($group->id) : $this->_historical_groups[] = new Music_Group($group->id);
								break;
							case Group_Manager::DANCE:
								$group->user_active ? $this->_groups[] = new Dance_Group($group->id) : $this->_historical_groups[] = new Dance_Group($group->id);
								break;
							case Group_Manager::COMEDY:
								$group->user_active ? $this->_groups[] = new Comedy_Group($group->id) : $this->_historical_groups[] = new Comedy_Group($group->id);
								break;
							default:
								$group->user_active ? $this->_groups[] = new Music_Group($group->id) : $this->_historical_groups[] = new Music_Group($group->id);
								break;
						}
					}
					return true;
				}
			}
		}
		return false;
	}
	
	public function are_groups_populated(){
		return $this->_groups_populated;
	}
	
	public function get_groups(){
		return !empty($this->_groups) ? $this->_groups : false;
	}
	
	public function get_group($group){
		$users_groups = $this->get_groups();
		if($users_groups){
			foreach($users_groups as $user_group){
				if($user_group->data()->id == $group || $user_group->data()->prof_link === $group)
					return $user_group;
			}
		}
		return false;
	}
	
	public function get_historical_groups(){
		return !empty($this->_historical_groups) ? $this->_historical_groups : false;
	}
	
	public function get_historical_group($group){
		$users_groups = $this->get_historical_group();
		if($users_groups){
			foreach($users_groups as $user_group){
				if($user_group->data()->id == $group || $user_group->data()->prof_link === $group)
					return $user_group;
			}
		}
		return false;
	}
	
	public function group_access_level(&$group=null){
		// $group can only be a group ID atm - can expand if needed
		if($group){
			$sql = "SELECT user_group_match.status_flag FROM ". USERS_TO_GROUPS_INTERMEDIARY ." AS user_group_match INNER JOIN groups AS `group` ON (user_group_match.am_id = `group`.id) WHERE (user_group_match.u_id=? AND `group`.id=? AND `group`.active!=0)";
			if(empty($this->_db->query($sql, [$this->data()->id, $group->data()->id])->errors())){
				if($this->_db->num_rows()==1)
					return $this->_db->first_result()->status_flag;
			}
		}
		return false;
	}
}

?>

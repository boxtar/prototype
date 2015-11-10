<?php
/**
 * Class to manage posts
 */
 
 class Post_Manager{
 	/* This single instance */
	private static $_instance = null;
	/* DB Connection */
	private $_db;
	
	/*
	 * SINGLETON PATTERN
	 */
	private function __construct(){
		$this->_db = DB::getInstance();
	}
	public static function getInstance(){
		if(!isset(self::$_instance)){
			self::$_instance = new Post_Manager();
		}
		return self::$_instance;
	}
	
	/**
	 *
	 */
	public function create_post($user, $target, $target_type, $post){
		
		if($user->exists() && $target->exists()){
			$insert_values = [
				'owner_id' => $user->id(),
				'target_id' => $target->id(),
				'target_type' => $target_type,
				'post_data' => $post
			];
			
			if(empty($this->_db->insert('posts', $insert_values)->errors())){
				return ['id' => $this->_db->last_insert_id()];
			}
		}
		return false;
	}
 	
 	/**
 	 * Retrieve all posts to display on a specific profile page
 	 *
 	 * @param Object $target The target to retrieve posts for
	 *
 	 * @return array[array[string]]string Returns a 2D array with each internal array holding the relevant post data
 	 */
 	public function get_posts($target){
 		if($target->exists()){
 			$sql = "SELECT id, owner_id, post_data FROM posts WHERE target_id=? AND (target_type=?) ORDER BY created DESC LIMIT 10";
 			if(empty($this->_db->query($sql, [$target->id(), $target->type()])->errors())){
 				if(!empty($this->_db->results())){
	 				$posts_info = array();
	 				foreach($this->_db->results() as $result){
	 					$user = new User;
	 					$user->find($result->owner_id, 'users', ['id']);
	 					$posts_info[] = [
	 						'id' => $result->id,
	 						'content' => $result->post_data,
	 						'user' => $user->name(),
	 						'avatar' => $user->avatar()
	 					];
	 				}
	 				return $posts_info;
 				}
 			}
 		}
 		return false;
 	}
 }
 
 ?>

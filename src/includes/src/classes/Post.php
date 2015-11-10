<?php

/**
 * This script defines the Post class.
 *
 * @author Johnpaul McMahon <jai@boxtar.uk>
 * @copyright 2015
 */
 
/**
 * The Post class holds the information for a single post and provides functionality for managing the post in the database.
 */
class Post{
	// holds information from the DB related to the post:
	protected $_data;
	
	public function __construct($post=null){
		if($post)
			$this->find($post);
	}
	
}
?>

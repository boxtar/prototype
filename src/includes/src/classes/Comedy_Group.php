<?php 	# Comedy Group class extending Group class for base group functionality

class Comedy_Group extends Group{

	public function __construct($group=null){
		parent::__construct($group);
	}
	
	/**
	 * Returns the path of the users avatar relative to the offsite location
	 *
	 * @returns string
	 */
	public function avatar(){
		return $this->exists() ? 'comedy/'.$this->profile_link().'/img/prof/'.$this->data()->avatar_link : false;
	}
	
	public function type(){
		return 'comedy';
	}
	
}

?>

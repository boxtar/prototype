<?php 	# Music Group class extending Group class for base group functionality

class Music_Group extends Group{

	public function __construct($group=null){
		parent::__construct($group);
	}
	
	/**
	 * Returns the path of the music groups avatar relative to the offsite location
	 *
	 * @returns string
	 */
	public function avatar(){
		return $this->exists() ? 'music/'.$this->profile_link().'/img/prof/'.$this->data()->avatar_link : false;
	}
	
	public function type(){
		return 'music';
	}
}

?>

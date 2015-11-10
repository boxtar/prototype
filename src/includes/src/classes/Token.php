<?php # Static functions to generate unique tokens for forms to protect against CSRF

class Token{
	
	// Create unique string of characters and put into session:
	public static function generate(){
		return Session::set('token', md5(uniqid()));
	}
	
	public static function check($token){
		if(Session::exists('token') && $token === Session::get('token')){
			Session::delete('token');
			return true;
		}
		return false;
	}
}
?>
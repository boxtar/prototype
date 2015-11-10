<?php
class Session{
	
	public static function exists($name){
		return isset($_SESSION[$name]);
	}
	public static function set($name, $value){
		return $_SESSION[$name] = $value;
	}
	
	public static function get($name){
		return (self::exists($name) ? $_SESSION[$name] : false);
	}
	
	public static function delete($name){
		if(self::exists($name))
			unset($_SESSION[$name]);
	}
	
	public static function flash($name, $value=''){
		if(self::exists($name)){
			$message = self::get($name);
			self::delete($name);
			return $message;
		}
		else{
			self::set($name, $value);
		}
	}
}
?>

<?php
		
/**
 * This script defines the Base_Account class.
 *
 * @author Johnpaul McMahon <jai@boxtar.uk>
 * @copyright 2015
 */
 
/**
 * The Base_Account class provides default fields and methods for dealing with an entity comprised of database information
 *
 * User and Group classes derive from this class as they both have fields and methods in common but will also require
 * their own specific fields, methods and method overriding.
 */
class Base_Account{
	
	/* FIELDS */
	 
	// DB connection:
	protected $_db;
	// Account data:
	protected $_data;
	// Fields for searching accounts in DB:
	protected static $_search_fields=['id', 'email', 'prof_link', 'name'];
	
	public function __construct(){
		$this->_db=DB::getInstance();
	}
	
	/* METHODS */
	
	public function find($account=null, $table='users', $columns=[]){
		/* This function will be used to ensure the account being targeted exists */
		if($account){
			// This just repeats the $account parameter the necessary amount of times to satisfy PDO token binding.
			// i.e. (id = ?, email = ?) means $account will be added to this array twice as 2 tokens need to be bound.
			$bindings=[];
			// This string holds the WHERE clause with binding tokens (?)
			$columns_to_query='(';
			
			if(!empty($columns)){
				$x=1;
				foreach($columns as $column){
					if(in_array($column, self::$_search_fields)){
						if($x > 1)
							$columns_to_query .= ' OR ';
						$columns_to_query .= $column.'=?';
						$bindings[] = $account;
						$x++;
					}
				}
				$columns_to_query .= ')';
			}
			else{
				$columns_to_query = '(id=?)';
				$bindings[]=$account;
			}
			
			$sql = "SELECT * FROM {$table} WHERE {$columns_to_query}";
			
			if(empty($this->_db->query($sql, $bindings)->errors())){
				if($this->_db->num_rows() == 1){
					$this->_data = $this->_db->first_result();
					return true;
				}
			}
		}
		return false;
	}
	
	protected function update($info=[]){
		/* update database - (edit profile, basically) */
	}
	
	public function fetch_info($info=[]){
		/* get details for account from database */
	}
	
	public function data(){
		// Benefit of having a function for this is so we can add further functionality or limitations
		// E.G: if($key==='id') don't include in returned array...
		return $this->_data;
	}
	
	public function exists(){
		return !(empty($this->data()));
	}
	
	public function id(){
		return $this->exists() ? $this->data()->id : false;
	}
	
	public function name(){
		$data = (null !== $this->data()) ? $this->data() : false;
		if($data){
			if(isset($data->first_name) && isset($data->last_name))
				return $data->first_name.' '.$data->last_name;
			else
				return $data->name;
		}
		return false;
	}
	
	public function profile_link(){
		return $this->exists() ? $this->data()->prof_link : false;
	}
	
	public function avatar(){
		return $this->exists() ? $this->profile_link().'/img/prof/'.$this->data()->avatar_link : false;
	}
}		

?>

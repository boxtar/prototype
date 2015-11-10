<?php
		
/**
 * This script defines the Base_Object class.
 *
 * @author Johnpaul McMahon <jai@boxtar.uk>
 * @copyright 2015
 */
 
/**
 * The Base_Object class defines common functionality for Boxtar Objects created from Database records
 */
class Base_Object{
	// DB connection:
	protected $_db;
	// Objects data:
	protected $_data;
	// Fields for searching in DB:
	protected $_search_fields;
	// Needles in the Haystack - This limits the useable search fields:
	private static $_allowable_search_fields=['id', 'email', 'prof_link', 'name'];
	
	/**
	 * Base_Object constructor sets up the database connection and search_fields
	 */
	public function __construct($search_fields=[]){
		$this->_db=DB::getInstance();
		
		if(!empty($search_fields)){
			foreach($search_fields as $fields){
				if(in_array($fields, self::$_allowable_search_fields))
					$this->_search_fields[] = $field;
			}
		}
		
		if(isset($this->_search_fields) && empty($this->_search_fields))
			$this->_search_fields = ['id'];
	}
	
	/**
	 * The find function will search the provided table for the provided search term in the provided columns
	 */
	public function find($search_term=null, $table='users', $columns=[]){
		if($search_term){
			// This just repeats the $search_term parameter the necessary amount of times to satisfy PDO token binding.
			// i.e. (id = ?, email = ?) means $search_term will be added to this array twice as 2 tokens need to be bound.
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
						$bindings[] = $search_term;
						$x++;
					}
				}
				$columns_to_query .= ')';
			}
			else{
				$columns_to_query = '(id=?)';
				$bindings[]=$search_term;
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
}
?>

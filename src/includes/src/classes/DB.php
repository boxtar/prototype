<?php 
/*
 * DATABASE HANDLER CLASS - SINGLETON PATTERN
 */
class DB{
	/*
	 *	This single instance
	 */
	private static $_instance = null;
	/*
	 *	Private members for DB management and interaction
	 */
	private $_pdo,
			$_query,
			$_errors=[],
			$_results,
			$_count=0;
	
	/*
	 * SINGLETON PATTERN
	 */
	private function __construct(){
		try{
			require(DB_INFO);
			$this->_pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
		}
		catch(PDOException $e){
			die($e->getMessage());
		}
	}
	
	/*
	 *
	 */
	public static function getInstance(){
		if(!isset(self::$_instance)){
			self::$_instance = new DB();
		}
		return self::$_instance;
	}
	
	/*
	 *	DATABASE QUERY FUNCTION
	 */
	public function query($sql, $params = []){
		// New query - reset errors
		$this->_errors = [];
		// Prepare query and assign to private handler _query
		if($this->_query = $this->_pdo->prepare($sql)){
			/* If params exist, bind them */
			if(count($params)){
				$x=1;
				foreach($params as $param){
					$this->_query->bindValue($x, $param);
					$x++;
				}
			}
			if($this->_query->execute()){
				$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
				$this->_count = $this->_query->rowCount();
			}
			else{
				$this->_errors[]="Failed to execute query in function DB::query";
			}
		}
		else{
			$this->_errors[] = "Failed to prepare query in function DB::query";
		}
		return $this; // To enable chaining
	}
	
	/*
	 *
	 */
	public function action($action, $table, $where = []){
		 /*	$where clause should consist of 3 bits:
		 	field (i.e. username), operator (i.e. = or > etc), value (i.e. 'Alex' - the value to lookup) */
		if(count($where)===3){
			/* Define the bits of the WHERE clause */
			$field		= $where[0];
			$operator	= $where[1];
			$value		= [$where[2]]; /* This has to be an array so that the foreach in query() works */
			
			/* Allowable operators in WHERE clause */
			$operators = ['=', '<', '>', '<=', '>='];
			
			/* Make sure the provided operator is in the above approved list */
			if(in_array($operator, $operators)){
				/*
				 * Build the query - '?' used for value bit of WHERE clause as this will be bound when executing
				 */
				$sql = "{$action} {$table} WHERE {$field} {$operator} ?";
				
				$this->query($sql, $value);
			}
			else{
				$this->_errors[]="Error in function DB::action - Invalid operator in WHERE clause";
			}
		}
		else{
			$sql = "{$action} {$table}";
			$this->query($sql);
		}
		return $this;
	}
	/*
	 *
	 */
	 public function get($table, $where=[]){
		return $this->action('SELECT * FROM', $table, $where);
	 }
	 
	/*
	 *
	 */
	 public function delete($table, $where){
		/* return $this->action('DELETE FROM', $table, $where); */
	 }
	 
	/*
	 *	INSERT
	 */
	 public function insert($table, $fields = []){
		if(count($fields)){
			$keys = array_keys($fields);
			$values = '';
			// Counter to check for last key so as not to add an ', ' at the end of key list
			$x = 1;
			foreach($keys as $key){
				$values .= '?';
				if($x < count($keys))
					$values .= ', ';
				$x++;
			}
			
			$sql = "INSERT INTO {$table} (" . implode(', ', $keys) . ") VALUES ({$values})";
			$this->query($sql, $fields);
		}
		else{
			$this->_errors[]="Error in function DB::insert - No fields provided";
		}
		return $this;
	 }
	 
	/*
	 *	UPDATE
	 */
	 public function update($table, $id, $fields){
		$set = '';
		
		$x = 1;
		foreach($fields as $key => $value){
			$set .= "{$key}=?";
			if($x < count($fields)){
				$set .= ', ';
			}
			$x++;
		}
		
		// current(array) returns the currently pointed to element.
		// this will always be index 0 in our case as we don't traverse it
		$fields['id']=current($id);
		
		/* $sql = "UPDATE {$table} SET {$set} WHERE user_id = {$id} LIMIT 1"; */
		// key(array) returns the value of the currently pointed elements key
		$sql = "UPDATE {$table} SET {$set} WHERE ".key($id)." = ? LIMIT 1";
		
		/* $e = implode('<br/>', $fields);
		die($sql.'<br/>'.$e); */
		
		$this->query($sql, $fields);
		return $this;
	 }
	 
	/*
	 *	RESULTS
	 */
	 public function results(){
		return $this->_results;
	 }
	 
	/*
	 *	FIRST RESULT
	 */
	 public function first_result(){
		return $this->results()[0];
	 }
	 
	/*
	 *	ID OF LAST INSERTED RECORD
	 */
	 public function last_insert_id(){
		return $this->_pdo->lastInsertID();
	 }
	 
	/*
	 *	
	 */
	public function errors(){
		return $this->_errors;
	}
	
	/*
	 *
	 */
	public function num_rows(){
		return $this->_count;
	}
}	
?>
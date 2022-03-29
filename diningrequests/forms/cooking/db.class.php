<?php
// namespace Core;

class db{

	private static $database = 'sucs';
	private static $host = 'localhost';
	private static $user = 'sucsweb';
	private static $pass = 'W3bZ!pp3d';
	// private static $defaults = array(
	// 		'database' = '',
	// 		'host'
	// 	);

	private static $conn;
	private static $instance;

	private static $where = array(
									'clause'   =>   'WHERE',
									'values'   =>   '',
									'types'    =>   ''
								);
	private static $defaults = array(
									'table'    =>  '',
									'columns'  =>  '',
									'values'   =>  array(),
									'types'    =>  '',
									'?s'       =>  '',
									'query'    =>  '',
									'orderby'  =>  array(),
									'limit'    =>  0
								);

	private static $insertMore = array(
									'?s'       =>  ''
								);
	private static $join = array(
									'join'    =>   ''
								);


	public function __construct(){
		self::connect();
	}

	private function reset(){
    	// self::$instance = new self();
    	self::$instance;
		self::$where = array(
						'clause' => 'WHERE',
						'values' => '',
						'types' => ''
					);
		self::$defaults = array(
								'table'    =>  '',
								'columns'  =>  '',
								'values'   =>  [],
								'types'    =>  '',
								'?s'       =>  '',
								'query'    =>  '',
								'orderby'  =>  array(),
								'limit'    =>  0
							);
		self::$insertMore = array(
									'?s'       =>  ''
								);
    }

	public function connect(){
		$conn = &self::$conn;
		$conn = new mysqli(self::$host,self::$user,self::$pass,self::$database);
        if($conn->connect_errno){
            echo "Failed".htmlspecialchars($conn->error);
        }
        self::$conn = $conn;
	}

	public function getCon(){
		return self::$conn;
	}

	public function closeConnection($conn){
		mysqli_close($conn);
	}
	
	public function setDatabase($database){
		self::$database = $database;
	}

	private function getInstance() {
        if( !self::$instance ) {
            self::$instance = new self(); 
        }
        return self::$instance;
    }

    public function table($table){
    	self::$defaults['table'] = $table;
    	$instance = self::getInstance();
    	return $instance;
    }

    public function select($table){
    	self::table($table);// = $table;
    	$instance = self::getInstance();
    	return $instance;
    }

    public function all(){
    	$conn = self::$conn;
    	$query = 'SELECT * FROM '.self::$defaults['table'];
    	if ($stmt = $conn->prepare($query)){
    		$stmt->execute();
    		$result = $stmt->get_result();
    		$retArr = array();
    		$retArr[] = $result->fetch_array(MYSQLI_ASSOC);
    		while ($row = $result->fetch_array(MYSQLI_ASSOC))
	        {
	        	// $retArr[] = $row;
	        	array_push($retArr, $row);
	        }
	        return $retArr;
    	}
    }

    public function col(){
    	return self::$instance;
    }

    public function where($val1,$val2='',$val3=''){
    	// $val1 = mysqli_real_escape_string(self::$conn,$val1);
    	$val2 = mysqli_real_escape_string(self::$conn,$val2);
    	$val3 = mysqli_real_escape_string(self::$conn,$val3);
    	$where = &self::$where['clause'];
    	$where = 'WHERE';
    	$questionValues = &self::$where['values'];
    	$questionValues = array();
    	$val_type = &self::$where['types'];
    	$val_type = '';
    	// if not array, then only one where value.
    	if (!is_array($val1)){
    		if ($val3!=''){
    			$where .= ' '.$val1.' '.$val2.' '.$val3;
    		}else{
    			$where .= ' '.$val1.' '.$val2;
    		}
    	}else{
    		foreach ($val1 as $rkey => $r) {
    			if(is_array($r)){
    				foreach ($r as $key=>$value) {
	    				if($where=='WHERE'){
	    					$where = ' '.$where.' '.$value;
	    				}else{
	    					if ($key==0){
	    						$where = $where.' AND '.$value;
	    					}elseif ($key==1){
	    						$where = $where.' '.$value.'';
	    					}else{
	    						$where = $where.' ? ';
	    						$type = gettype($value);
	    						switch ($type) {
	    							case 'string':
	    								$val_type .= 's';
	    								break;
	    							case 'integer':
	    								$val_type .= 'i';
	    								break;
	    							case 'double':
	    								$val_type .= 'd';
	    								break;
	    							default:
	    								# code...
	    								break;
	    						}
	    						array_push($questionValues,$value);
	    					}
	    				}
	    			}
    			}else{
    				if ($rkey == 2){
    					$where = $where.'?';
    					array_push($questionValues,$r);
    					$type = gettype($r);
						switch ($type) {
							case 'string':
								$val_type .= 's';
								break;
							case 'integer':
								$val_type .= 'i';
								break;
							case 'double':
								$val_type .= 'd';
								break;
							default:
								# code...
								break;
						}
    				}else{
    					$where = $where.' '.$r;
    				}
    			}
    		}
    	}
    	return self::$instance;
    }

    public function orderby(string $colName, $desc = ''){
    	$colName = mysqli_real_escape_string($colName);
    	$orderby = & self::$defaults['orderby'];
    	if ($orderby == ''){
    		$orderby = 'ORDER By '.$colName;
    	}else {
    		$orderby .= ' ,'.$colName;
    	}

    	if ($desc != ''){
    		$orderby .= ' DESC';
    	}
    }

    public function limit($int){
    	if (True === is_int($int)){
    		self::$defaults['limit'] = $int;
    		return self::$instance;
    	}else{
    		return false;
    	}
    }

    public function desc(){

    }

    public function inner($table,$left,$right){
    	
    }

    public function join(){

    }

    public function get(){
    	$conn = self::$conn;
    	if ($conn){

    		// Build query
    		$columns = self::$defaults['columns'];
    		$table = self::$defaults['table'];
    		$where = &self::$where;
    		$query = &self::$defaults['query'];
    		if ($columns == ''){
    			// no column selected then get all cols
    			$query = 'SELECT * FROM '.$table;
    		}else{
    			$query = 'SELECT'.$columns.'FROM '.$table;
    		}

    		if ($where != 'WHERE'){
    			$query .= ' '.$where['clause'];
    		}

    		if (count(self::$defaults['orderby']) > 0){
    			$query .= ' '.self::$defaults['orderby'];
    		}

    		if (self::$defaults['limit'] > 0) {
    			$query .= ' LIMIT '.self::$defaults['limit'];
    		}
    		
    		// printf($query.'<br>');
    		// echo $query;

	    	if ($stmt = $conn->prepare($query)){
	    		$questionValues = $where['values'];
	    		$params[] = $where['types'];
	    		$n = count($questionValues);
	    		// var_dump($questionValues);
	    		if ($n > 0){
	    			for ($i=0; $i < $n; $i++) { 
		    			$params[] = & $questionValues[$i];
		    		}
		    		// var_dump($params);
		    		call_user_func_array(array($stmt, 'bind_param'), $params);
	    		}

	    		$stmt->execute();
	    		$result = $stmt->get_result();
	    		$retArr = array();
	    		while ($row = $result->fetch_array(MYSQLI_ASSOC))
		        {
		        	$retArr[] = $row;
		        }
		        $stmt->close();
		        self::reset();
		        return $retArr;
	    	}else{
	    		// query error
	    		return htmlspecialchars($conn->error);
	    	}


    	}else{
    		// Connection error
    		return htmlspecialchars($conn->connect_errno);
    	}


    	// echo self::$where;
    }


    /* INSERT */

   	public function insert($table){
   		self::$defaults['table'] = $table;
   		return self::getInstance();
   	}
   	public function into($col,$val,$pos=''){
   		if ( strpos( self::$defaults['columns'], $col ) === false ){
	    	self::$defaults['columns'] .= $col.',';
	    	$val = mysqli_real_escape_string(self::$conn,$val);
	    	array_push(self::$defaults['values'], $val);

	    	$type = gettype($val);
	    	$val_type = ' ';
			switch ($type) {
				case 'string':
					$val_type = 's';
					break;
				case 'integer':
					$val_type = 'i';
					break;
				case 'double':
					$val_type = 'd';
					break;
				default:
					break;
			}
			self::$defaults['types'] .= $val_type;

			self::$defaults['?s'] .= '?,';
		}else{
			self::insertMore($val,$pos);
		}
    	return self::$instance;
    }

    private function insertMore($val,$pos=''){
    	// escape value and store in the array
    	$val = mysqli_real_escape_string(self::$conn,$val);
    	array_push(self::$defaults['values'], $val);

    	$type = gettype($val);
    	$val_type = ' ';
		switch ($type) {
			case 'string':
				$val_type = 's';
				break;
			case 'integer':
				$val_type = 'i';
				break;
			case 'double':
				$val_type = 'd';
				break;
			default:
				break;
		}
		self::$defaults['types'] .= $val_type;

		if ($pos=='start'){
			self::$insertMore['?s'] .= ',(?';
		}
		else if ($pos=='end'){
			self::$insertMore['?s'] .= ',?) ';
		}
		else {
			self::$insertMore['?s'] .= ',?';
		}

    	// return self::$instance;
    }

    public function save(){
    	$conn = self::$conn;
    	if ($conn){
			// Build query
			$query = 'INSERT INTO '.self::$defaults['table'].'('.rtrim(self::$defaults['columns'],',').') VALUES ('.rtrim(self::$defaults['?s'],',').')';
			$query .= self::$insertMore['?s'];
			// echo $query;
			if ($stmt = $conn->prepare($query)){
				$values = & self::$defaults['values'];
				$params[] = & rtrim(self::$defaults['types'],',');
				$n = count($values);
				if ($n > 0){
					for ($i=0; $i < $n; $i++) { 
		    			$params[] = & $values[$i];
		    		}
		    		// var_dump($params);
		    		call_user_func_array(array($stmt, 'bind_param'), $params);
				}

				if ($stmt->execute()){
					self::reset();

					// Btw it will return object
					return 'success';
				}else{
					// execute error
	    			return htmlspecialchars($conn->error);
				}

			}else{
				// query error
	    		return htmlspecialchars($conn->error);
			}
    	}else{
    		// Connection error
    		return htmlspecialchars($conn->connect_errno);			
    	}
    }

    public function id(){
    	return mysqli_insert_id(self::$conn);
    }


    /* UPDATE */
    public function update($table){
    	self::$defaults['table'] = $table;
    	return self::getInstance();
    }

    public function set($col,$val){
    	self::$defaults['columns'] .= $col.' = ?,';
    	$val = mysqli_real_escape_string(self::$conn,$val);
    	array_push(self::$defaults['values'], $val);

    	$type = gettype($val);
    	$val_type = ' ';
		switch ($type) {
			case 'string':
				$val_type = 's';
				break;
			case 'integer':
				$val_type = 'i';
				break;
			case 'double':
				$val_type = 'd';
				break;
			default:
				break;
		}
		self::$defaults['types'] .= $val_type;

		self::$defaults['?s'] .= '?,';

    	return self::$instance;
    }

    public function load(){
    	$conn = self::$conn;
    	if ($conn){
			// Build query
			$query = 'UPDATE '.self::$defaults['table'].' SET '.rtrim(self::$defaults['columns'],',').' '.self::$where['clause'];
			// echo $query;
			if ($stmt = $conn->prepare($query)){
				$values = & array_merge(self::$defaults['values'],self::$where['values']);
				$types = self::$defaults['types'].self::$where['types'];
				$params[] = & $types;
				$n = count($values);
				if ($n > 0){
					for ($i=0; $i < $n; $i++) { 
		    			$params[] = & $values[$i];
		    		}
		    		call_user_func_array(array($stmt, 'bind_param'), $params);
				}

				if ($stmt->execute()){
					// echo 'executed<br>';
					self::reset();
					return mysqli_affected_rows($conn);
				}else{
					// execute error
	    			return htmlspecialchars($conn->error);
				}

			}else{
				// query error
				echo $conn->error;
	    		return htmlspecialchars($conn->error);
			}
    	}else{
    		// Connection error
    		return htmlspecialchars($conn->connect_errno);			
    	}
    }


    /* DELETE */
    public function delete(){
    	$conn = self::$conn;
    	$query = 'DELETE FROM '.self::$defaults['table'].' '.self::$where['clause'];
    	if ($stmt = $conn->prepare($query)){
				$values = & array_merge(self::$defaults['values'],self::$where['values']);
				$types = self::$defaults['types'].self::$where['types'];
				$params[] = & $types;
				// var_dump($values);
				// echo $query;
				$n = count($values);
				if ($n > 0){
					for ($i=0; $i < $n; $i++) { 
		    			$params[] = & $values[$i];
		    		}
		    		call_user_func_array(array($stmt, 'bind_param'), $params);
				}

				if ($stmt->execute()){
					// echo 'executed';
					self::reset();
					return true;
				}else{
					// execute error
	    			return htmlspecialchars($conn->error);
				}

			}else{
				// query error
				echo $conn->error;
	    		return htmlspecialchars($conn->error);
			}

    }

}

?>
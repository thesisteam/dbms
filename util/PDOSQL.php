<?php

/**
 * Static class for PDO-MySQL implementations
 */
final class PDOSQL {
    
    public $query = "";
    public $lastprocess = "";
    public $rows_affected = null;
    
    /**
     * Connects to MySQL via PDO
     * @param String $supplementalpath (Optional) The parent path where this PHP class was called, include /.
     * @return \PDO
     */
    public static function Connect($supplementalpath = '') {
        $pdo_config = parse_ini_file($supplementalpath . 'config/database.ini');
        $dsn = 'mysql:host=' . $pdo_config['host'] . ';dbname=' . $pdo_config['db'] . ';port=' . $pdo_config['port'];
        $pdo = new PDO($dsn, $pdo_config['user'], $pdo_config['password']);
        return $pdo;
    }
    
    public static function __GetDriverInfo() {
        return pdo_drivers();
    }
    
    public function __construct() {
        $this->query = "";
        $this->lastprocess = null;
        $this->rows_affected = null;
    }
    
    /**
     * Execute the constructed query
     * @return \PDOSQL
     */
    public function Execute() {
        $this->rows_affected = $PDO->exec($this->query);
        return $this;
    }
    
    /**
     * From what table will the query be executed
     * @param String $tablename The table namme
     */
    public function From($tablename) {
        $this->query .= 'FROM ' . $tablename . ' ';
    }
    
    /**
     * Insert function
     * @param String $tablename The name of table
     * @param String $a_fields The table fields to be specified
     * @return \PDOSQL
     */
    public function InsertInto($tablename, $a_fields = array()) {
        $tablename = strtolower($tablename);
        $this->query = 'INSERT INTO ' . $tablename . (count($a_fields) > 0 ? '(' : ' ');
        
        for ($x=0; $x<count($a_fields); $x++) {
            $this->query .= $a_fields[$x] . ($x < count($a_fields)-1 ? ',':') ');
        }
        return $this;
    }
    
    public function Query() {
        $pdoobj = self::Connect();
        $statement = new PDOStatement();
        $statement = $pdoobj->query($this->query);
        $this->rows_affected = $statement->rowCount();
        // YOU'RE HERE
    }
    
    /**
     * Select a series (optional) of table fields
     * @param Array $a_fields Array of fields to be selected
     * @return \PDOSQL
     */
    public function Select($a_fields = array()) {
        $tablename = strtolower($tablename);
        $this->query = 'SELECT ' . (count($a_fields)==0 ? '* ' : '');
        
        for ($x=0; $x < count($a_fields); $x++) {
            $this->query .= $a_fields[$x] . ($x < count($a_fields)-1 ? ',':' ');
        }
        return $this;
    }
    
    public function Values($a_values) {
        $this->query .= 'VALUES(';
        for ($x=0; $x<count($a_values); $x++) {
            $this->query .= $a_values[$x] . ($x < count($a_values)-1 ? ', ': ' );');
        }
        $PDO = self::Connect();
        $this->rows_affected = $PDO->exec($this->query);
        return $this;
    }
    
    /**
     * Conditional function WHERE
     * @param String $condition A string containing the condition for this query
     */
    public function Where($condition) {
        $this->query .= 'WHERE ' . $condition;
    }
    
}

?>
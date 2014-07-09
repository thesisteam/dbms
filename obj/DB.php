<?php

/**
 * Static class for MySQLI implementations
 */
final class DB {
    
    public $query = "";
    public $lasterror = "";
    public $lasterrno = null;
    public $lastprocess = "";
    /**
     *
     * @var mysqli
     */
    public $mysqli;
    public $rows_affected = null;
    
    /**
     * Connects to MySQL via PDO
     * @param String $supplementalpath (Optional) The parent path where this PHP class was called, include /.
     * @return mysqli
     */
    public function Connect($supplementalpath = '') {
        $config = parse_ini_file($supplementalpath . 'config/database.ini');
        $this->mysqli = new mysqli($config['host'], $config['user'], $config['password'], $config['db'], $config['port']);
        return $this->mysqli;
    }
    
    /**
     * Returns the PDO driver data in associative array form
     * @return Array(Assoc)
     */
    public static function __GetDriverInfo() {
        return pdo_drivers();
    }
    
    /**
     * Gets the number of rows processed after the last executed query.
     * @return int
     */
    public function __GetAffectedRows() {
        return $this->mysqli->affected_rows;
    }
    
    /**
     * Gets the current query of this instance
     * @return String The current query in this instance.
     */
    public function __GetQuery() {
        return $this->query;
    }
    
    /**
     * Checks for existence of certain condition in MySQL
     * @param String $table Name of the table
     * @param Array(Assoc) $a_fields_values Assoc-array containing fields and values to be checked
     * @return boolean
     */
    public function __checkPositive($table, $a_fields_values) {
        $built_condition = '';
        if (count($a_fields_values) > 0) {
            $x = 0;
            do {
                $built_condition .= '`'.key($a_fields_values)
                        .'`="'.current($a_fields_values).'"'
                        .($x<count($a_fields_values)-1 ? ' AND ':'');
                $x++;
            } while(next($a_fields_values));
        }
        $result = $this->Select()
                ->From($table)
                ->Where($built_condition)
                ->Query();
        return count($result) > 0;
    }
    
    /**
     * Cleans the query from basic injections
     * @return String
     */
    public function __cleanQuery() {
        # Trims whitespaces on start and end portion of query
        $this->query = trim($this->query);
        
        /** SQL query escaping will soon be fixed
         * 
         * # Escapes the SQL query
         * $this->query = \mysql_real_escape_string($this->query);
         */
        return $this->query;
    }
    
    /**
     * 
     */
    public function __construct() {
        $this->query = "";
        $this->lastprocess = null;
        $this->rows_affected = null;
        $this->Connect();
    }
    
    /**
     * Cleans current query from pre-spacing and ensures that the ending query has 
     * space at the end for future query concatenation
     */
    public function __trailspaceQuery() {
        $this->query = trim($this->query);
        $this->query .= ' ';
    }
    
    /**
     * DELETE syntax with specific target table
     * @param String $tablename The target table name
     * @return \DB
     */
    public function DeleteFrom($tablename) {
        $this->query = "DELETE FROM " . $tablename . ' ';
        $this->__trailspaceQuery();
        return $this;
    }
    
    /**
     * Execute the constructed query
     * @return \DB
     */
    public function Execute($query = null) {
        if (!is_null($query)) {
            $this->query = $query;
        }
        $this->__cleanQuery();
        if (!$this->mysqli->query($this->query)) {
            $this->lasterror = $this->mysqli->error;
            $this->lasterrno = $this->mysqli->errno;
        }
        $this->rows_affected = $this->__GetAffectedRows();
        return $this;
    }
    
    /**
     * From what table will the query be executed
     * @param String $tablename The table namme
     * @return \DB
     */
    public function From($tablename) {
        $this->query .= 'FROM ' . $tablename . ' ';
        $this->__trailspaceQuery();
        return $this;
    }
    
    /**
     * Insert function
     * @param String $tablename The name of table
     * @param String $a_fields The table fields to be specified
     * @return \DB
     */
    public function InsertInto($tablename, $a_fields = array()) {
        $tablename = strtolower($tablename);
        $this->query = 'INSERT INTO ' . $tablename . (count($a_fields) > 0 ? '(' : ' ');
        
        for ($x=0; $x<count($a_fields); $x++) {
            $this->query .= $a_fields[$x] . ($x < count($a_fields)-1 ? ',':') ');
        }
        $this->__trailspaceQuery();
        return $this;
    }
    
    /**
     * Runs the current query or (optional) a specified query.
     * @return Array(Assoc) The assoc-array containing the returned rows
     */
    public function Query($query = null) {
        $result_object = array();
        if (!is_null($query)) {
            $this->query = $query;
        }
        
        $result = $this->mysqli->query($this->query);
        if ($result) {
            # SUCCESS : Process the result to an array stack
            while ($row = mysqli_fetch_assoc($result)) {
                array_push($result_object, $row);
            }
        } else {
            # FAILURE : Log the last error
            $this->lasterror = $this->mysqli->error;
            $this->lasterrno = $this->mysqli->errno;
        }
        return $result_object;
    }
    
    /**
     * Select a series (optional) of table fields
     * @param Array $a_fields Array of fields to be selected
     * @return \DB
     */
    public function Select($a_fields = array()) {
        # $tablename = strtolower($tablename);
        $this->query = 'SELECT ' . (count($a_fields)==0 ? '* ' : '');
        
        for ($x=0; $x < count($a_fields); $x++) {
            $this->query .= $a_fields[$x] . ($x < count($a_fields)-1 ? ',':' ');
        }
        $this->__trailspaceQuery();
        return $this;
    }
    
    /**
     * Sets assignments to column fields, specifically for UPDATE command
     * @param Array(Assoc) $a_assignments Assoc-array of column field values assignment
     * @return \DB
     */
    public function Set($a_assignments = array()) {
        if (count($a_assignments) > 0) {
            do {
                $this->query .= key($a_assignments) .' = '. current($a_assignments);
            } while(next($a_assignments));
            $this->query .= ' ';
        }
        TrailspaceQuery();
        return $this;
    }
    
    /**
     * UPDATE table function
     * @param String $tablename The target table name
     * @return \DB
     */
    public function Update($tablename) {
        $this->query('UPDATE ' . $tablename . ' ');
        TrailspaceQuery();
        return $this;
    }
    
    /**
     * Values function, specifically used for INSERT command
     * @param Array $a_values Array of values to be inserted
     * @return \DB
     */
    public function Values($a_values) {
        $this->query .= 'VALUES(';
        for ($x=0; $x<count($a_values); $x++) {
            $this->query .= $a_values[$x] . ($x < count($a_values)-1 ? ', ': ' );');
        }
        $this->__trailspaceQuery();
        return $this;
    }
    
    /**
     * Conditional function WHERE
     * @param String $condition A string containing the condition(s) for this query
     */
    public function Where($condition) {
        $this->query .= 'WHERE ' . $condition . ' ';
        $this->__trailspaceQuery();
        return $this;
    }
    
}

?>
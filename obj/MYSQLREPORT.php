<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Class for generating reports from MySQL database
 * @author Allen
 */
class MYSQLREPORT {
    
    # Properties ---------------------------------------------------------------
    
    /**
     * Data containing query results from DB object
     * @var Array(assoc)
     */
    public $Queryresultdata;
    
    /**
     * Row data for Table data rendering inputs
     * @var Array(2-dime) 
     */
    public $Rowdata;
    
    /**
     * Array of Column headers of this report
     * @var Array
     */
    public $Reportheaders;
    
    /**
     * Child DB object for executing queries
     * @var DB
     */
    public $Db;
    
    
    # Methods ------------------------------------------------------------------
    
    public function __construct(array $a_headers, array $query_result_data = array()) {
        $this->Reportheaders = $a_headers;
        $this->Queryresultdata = $query_result_data;
    }
    
    public function __columnCount() {
        // Put a procedure here that will return column count of `Resultdata`
    }
    
    public function loadDb(DB $db) {
        $this->Db = $db;
        return $this;
    }
    
    public function loadResultdata($query_result_data) {
        $this->Queryresultdata = $query_result_data;
        return $this;
    }
    
    public function renderReport($a_tableoptions=array()) {
        
    }
    
}

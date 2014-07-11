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
    
    # Properties
    
    /**
     * Array of Column headers of this report
     * @var Array
     */
    public $Reportheaders;
    
    /**
     * Data containing query results from DB object
     * @var Array(assoc)
     */
    public $Resultdata;
    
    /**
     * Child DB object for executing queries
     * @var DB
     */
    public $Db;
    
    
    # Methods
    
    public function __construct(array $a_headers, array $query_result_data = array()) {
        $this->Reportheaders = $a_headers;
        $this->Resultdata = $query_result_data;
    }
    
    public function loadDb(DB $db) {
        $this->Db = $db;
    }
    
    public function loadResultdata($query_result_data) {
        $this->Resultdata = $query_result_data;
    }
    
    public function renderReport() {
        
    }
    
}

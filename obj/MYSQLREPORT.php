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
    
    public function __columnCount() {
        // Put a procedure here that will return column count of `Resultdata`
    }
    
    public function loadDb(DB $db) {
        $this->Db = $db;
    }
    
    public function loadResultdata($query_result_data) {
        $this->Resultdata = $query_result_data;
    }
    
    public function renderReport($a_tableoptions=array()) {
        # ECHO <table ... >
        $str_tableoptions = '';
        if (count($a_tableoptions) > 0) {
            do {
                $str_tableoptions .= key($a_tableoptions) . '="' . current($a_tableoptions) . '" ';
            } while(next($a_tableoptions));
        }
        $str_tableoptions = trim($str_tableoptions);
        echo '<table ' . $str_tableoptions . '>';
        
        # ECHO { Column headers }
        
        
        echo '</table>';
        # ECHO </table>
    }
    
}

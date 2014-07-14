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
     * {TABLE} CSS properties of this report
     * @var Array(assoc)
     */
    public $ReportCSSproperties;
    
    /**
     * {TD} HTML template properties for corresponding column fields
     * @var Array(assoc)
     */
    public $ReportCellstemplate;
    
    /**
     * Array of Column headers of this report
     * @var Array(assoc)
     */
    public $Reportheaders;
    
    /**
     * {TABLE} HTML properties of this report
     * @var Array(assoc)
     */
    public $Reportproperties;
    
    /**
     * Child DB object for executing queries
     * @var DB
     */
    public $DB;
    public $TABLE;
    
    
    # Methods ------------------------------------------------------------------
    
    public function __construct(array $a_headers = array(), array $query_result_data = array()) {
        $this->Reportheaders = $a_headers;
        $this->Reportproperties = array();
        $this->Queryresultdata = $query_result_data;
        $this->Rowdata = array();
        $this->DB = null;
        $this->TABLE = null;
    }
    
    public function __columnCount() {
        // Put a procedure here that will return column count of `Resultdata`
    }
    
    public function loadDb(DB $db) {
        $this->DB = $db;
        return $this;
    }
    
    public function loadResultdata($query_result_data, $is_autoheader=false) {
        $this->Queryresultdata = $query_result_data;
        if (count($this->Queryresultdata) > 0 && $is_autoheader) {
            $reportKeys = array_keys($this->Queryresultdata[0]);
            $new_columnHeaders = array();
            foreach($reportKeys as $key) {
                array_push($new_columnHeaders, array(
                    'CAPTION' => $key
                ));
            }
            $this->Reportheaders = $new_columnHeaders;
        }
        // Process Query result into ROW DATA
        if (count($this->Queryresultdata) > 0) {
            $reportKeys = array_keys($this->Queryresultdata[0]);
            
            # Truncate Rowdata
            $this->Rowdata = array(); 
            
            foreach($this->Queryresultdata as $resultrow) {
                # Initialize a container for processed row data
                $row_to_push = array();
                foreach($reportKeys as $key) {
                    array_push($row_to_push, $resultrow[$key]);
                }
                array_push($this->Rowdata, $row_to_push);
            }
        }
        // END of processing Query result into ROW DATA
        
        return $this;
    }
    
    public function renderReport($a_tableoptions=array()) {
        $this->TABLE = new TABLE();
        
        # Initializing TABLE report properties
        $this->TABLE->setColumnHeaders($this->Reportheaders)
                ->setHTMLproperties($this->Reportproperties)
                ->setCellstemplate($this->ReportCellstemplate)
                ->setCSS($this->ReportCSSproperties);
        
        foreach($this->Rowdata as $row) {
            $this->TABLE->addRow($row);
        }
        
        $this->TABLE->Render();
    }
    
    public function setReportCSS($a_cssproperties) {
        $this->ReportCSSproperties = $a_cssproperties;
        return $this;
    }
    
    public function setReportCellstemplate($a_cellstemplate) {
        $this->ReportCellstemplate = $a_cellstemplate;
        return $this;
    }
    
    public function setReportProperties($a_htmlproperties) {
        $this->Reportproperties = $a_htmlproperties;
        return $this;
    }
    
    public function setReportHeaders($a_headers) {
        $this->Reportheaders = $a_headers;
        return $this;
    }
    
}

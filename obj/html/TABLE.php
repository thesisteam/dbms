<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Class for rendering Table elements<br><br>
 * <b>Instructions of use: </b><br>
 * <ol type="1">
 * <li>Initialize this instance</li>
 * <li>setColumnheaders, setHTMLproperties, setCSS</li>
 * <li>addRow</li>
 * </ol>
 *
 * @author Allen
 */
class TABLE {

    public $Columnheaders;
    # [0] => { CAPTION, WIDTH, ALIGN, (...) }
    
    /**
     * Assoc-array of row values/data
     * @var Array(assoc)
     */
    public $Rowdata;
    
    /**
     * Assoc-array of HTML properties of this {TABLE} element
     * @var Array(assoc)
     */
    public $CellsHTMLtemplate;
    
    public $HTMLcss;
    public $HTMLproperties;

    /**
     * Create new instance of this object.
     */
    public function __construct() {
        $this->Columnheaders = array();
        $this->Rowdata = array();
        $this->HTMLcss = array();
        $this->HTMLproperties = array();
    }

    /**
     * Adds new row data to the table.
     * @param Array $a_rowdata Array of data within this row
     * @return TABLE New instance
     */
    public function addRow($a_rowdata) {
        if (count($a_rowdata) > count($this->Columnheaders)) {
            ERROR::PromptError('Column count of "row_data" (' . count($a_rowdata) . ') exceeded the count of column headers (' . count($this->Columnheaders) . ').', 'TABLE.php', 48);
        } else {
            array_push($this->Rowdata, $a_rowdata);
        }
        return $this;
    }

    /**
     * Adds a group of cell to endpoint
     * @param type $a_rowdata
     * @param Boolean $is_trailingcell (Optional) Boolean value if these/this cell(s)
     *      should be added to the right trailing part of each row, otherwise, will be placed
     *      at left trailing parts.
     * @return TABLE|boolean
     */
    public function addCommonCells($a_rowdata, $is_trailingcell = true) {
        if (count($this->Rowdata) == 0) {
            ERROR::PromptError('<b>addCommonCells():</b> No existing row data yet!', 'TABLE.php');
            return false;
        }
        for ($x = 0; $x < count($this->Rowdata); $x++) {
            if ($is_trailingcell) {
                foreach ($a_rowdata as $rowcell) {
                    array_push($this->Rowdata[$x], $rowcell);
                }
            } 
            else {
                $new_rowdata = $a_rowdata;
                foreach($this->Rowdata[$x] as $rowdata_cell) {
                    array_push($new_rowdata, $rowdata_cell);
                }
                $this->Rowdata[$x] = $new_rowdata;
            }
        }
        return $this;
    }

    /**
     * Render this whole table
     * @param Boolean $is_boldheaders Boolean value if headers should be BOLDED
     */
    public function Render($is_boldheaders = true) {
        # ECHO <table (...properties...) 
        // Rendering <table>-properties
        $str_htmlprops = '';
        if (count($this->HTMLproperties) > 0) {
            do {
                if (strtoupper(key($this->HTMLproperties)) != 'STYLE') {
                    $str_htmlprops .= key($this->HTMLproperties) . '="' . current($this->HTMLproperties) . '" ';
                }
            } while (next($this->HTMLproperties));
        }
        $str_htmlprops = trim($str_htmlprops);
        echo '<table ' . $str_htmlprops;
        // Rendering <table>-styles
        $str_htmlcss = '';
        if (count($this->HTMLcss) > 0) {
            $str_htmlcss .= ' style="';
            do {
                $str_htmlcss .= str_replace(' ', '-', strtolower(key($this->HTMLcss))) . ':' . current($this->HTMLcss) . '; ';
            } while (next($this->HTMLcss));
            $str_htmlcss = strtolower(trim($str_htmlcss));
            $str_htmlcss .= '"';
            echo $str_htmlcss;
        }
        echo '>';

        $this->renderHeaders($is_boldheaders);
        $this->renderBody();

        echo '</table>';
    }
    
    public function renderBody() {
        echo '<tbody>' . PHP_EOL;
        # ECHO <tr><td> { Row data } </td></tr>
        foreach ($this->Rowdata as $row) {
            echo '<tr>';
            if (count($this->CellsHTMLtemplate) >= count($row)) {
                # If HTML cell templates count is greater than or equal to
                #       the number of cells for this row?
                # Looping CELLs per ROW
                $x = 0;
                foreach ($row as $cellvalue) {

                    // Preparing <td> HTML options
                    $td_options = '';
                    if (count($this->CellsHTMLtemplate) > 0) {
                        do {
                            $td_options .= strtolower(trim(key($this->CellsHTMLtemplate[$x])))
                                    . '="' . trim(current($this->CellsHTMLtemplate[$x])) . '" ';
                        } while (next($this->CellsHTMLtemplate[$x]));
                        reset($this->CellsHTMLtemplate[$x]);
                    }
                    $td_options = trim($td_options);

                    // Rendering current cell
                    echo '<td ' . $td_options . '>';
                    echo $cellvalue;
                    echo '</td>';
                    $x++;
                }
            } else {
                # If HTML Cell templates is less than the number of cells for this row
                ERROR::PromptError('Error at rendering Table body when number of cells for this '
                        . 'row is greater than the number of Cell templates!' . count($this->CellsHTMLtemplate)
                        . count($row), 'TABLE.php', 110);
            }
            echo '</tr>';
        }
        echo '</tbody>';
    }

    public function renderHeaders($is_bold = true) {
        # ECHO <tr><td> { Column Headers } </td></tr>
        if (count($this->Columnheaders) > 0) {
            echo '<thead><tr>';
            foreach ($this->Columnheaders as $columnHeader) {
                $td_properties = '';
                echo '<td ';
                if (count($columnHeader) > 0) {
                    do {
                        if (strtoupper(key($columnHeader))=='CAPTION') {
                            continue;
                        }
                        $td_properties .= key($columnHeader) . '="' . current($columnHeader) . '" ';
                    } while (next($columnHeader));
                }
                echo strtolower(trim($td_properties)) . '>';
                echo array_key_exists('CAPTION', $columnHeader) ?
                        ($is_bold ? '<b>' : '') . $columnHeader['CAPTION'] . ($is_bold ? '</b>' : '') : '';
                echo '</td>';
            }
            echo '</tr></thead>';
        }
    }

    /**
     * Set the Column headers of this table
     * @param Array(Indexed-assoc) $a_headers Indexed Assoc-array of column headers of this table
     *  with format:<br>
     * { <br>
     * <b>"CAPTION" =>"Caption1"</b>, <br>
     * <b>"WIDTH" => "10%"</b>, <br>
     * "ALIGN" = "left"<br>
     * }
     * @return TABLE New instance
     */
    public function setColumnHeaders($a_headers) {
        $this->Columnheaders = $a_headers;
        return $this;
    }

    /**
     * Set the CSS styles of this {TABLE}
     * @param Array(assoc) $a_cssprops Array(assoc) of CSS styles/properties to be applied
     * @return TABLE
     */
    public function setCSS($a_cssprops) {
        $this->HTMLcss = $a_cssprops;
        return $this;
    }

    /**
     * 
     * @param type $a_cellstemplate
     * @return \TABLE
     */
    public function setCellstemplate($a_cellstemplate) {
        $this->CellsHTMLtemplate = $a_cellstemplate;
        return $this;
    }

    /**
     * Set the HTML properties of this {table}
     * @param Array $a_htmlprops HTML {table} element properties
     * @return TABLE
     */
    public function setHTMLproperties($a_htmlprops) {
        $this->HTMLproperties = $a_htmlprops;
        return $this;
    }

}

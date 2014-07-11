<?php

/**
 * Class for CONFIG file (ini) management and processing engine
 */
final class CONFIG {
    
    public $path;
    public $configdata = array();
    public $headers = array();
    
    public function __construct($path = null) {
        $this->path = $path;
        $this->configdata = array();
    }
    
    /**
     * Creates an INI file (virtually)
     * @param String $path The path of the INI file to be created
     */
    public function Create($path) {
        $this->path = $path;
    }
    
    public function Exists() {
        return file_exists($this->path);
    }
    
    public function Load($configdata) {
        $this->configdata = $configdata;
    }
    
    public function Read() {
        if (!$this->Exists()) {
            return false;
        }
        return parse_ini_file($this->path);
    }
    
    public function SetHeader($arr_headers) {
        $this->headers = $arr_headers;
    }
    
    /**
     * Writes the content of this instance to the set path
     * @param Array(assoc) $configdata Assoc-array --> [key] = [value]
     */
    public function Write($configdata = null) {
        if ($configdata !== null) {
            $this->configdata = $configdata;
        }
        $handle = fopen($this->path, 'w');
        
        foreach($this->headers as $headerline) {
            fwrite($handle, '; ' . $headerline . '
');
        }
        fwrite($handle,  '
');
        $i = 0;
        do {
            fwrite($handle, key($this->configdata) . ' = ' . current($this->configdata) . '
');
        next($this->configdata);
        $i++;
        } while($i < count($this->configdata));
    }
    
}

?>
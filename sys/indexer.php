<?php

/**
 * Class as an Index page helper. Useful for index page manipulations.
 */
final class Index {
    
    public function __construct() {
        // error reporting - all errors for development (ensure you have display_errors = On in your php.ini file)
        error_reporting(E_ALL | E_STRICT);
        mb_internal_encoding('UTF-8');
        // set_exception_handler(array($this, 'handleException'));
        spl_autoload_register(array($this, 'LoadClass'));
        // session
        session_start();
        
        # Determine value of Rootpath
        $WebLocation = parse_ini_file(__DIR__ . '\..\config\app.ini')['WEB_LOCATION'];
        DIR::$ROOT = $_SERVER['SERVER_ADDR'] . '/' . $WebLocation;
        echo DIR::$ROOT;
    }
    
    public function LoadClass($classname) {
        $_assoc_Autoload = array(
            'DIR' => __DIR__ . '\..\constant\DIR.php'
        );
        if (!array_key_exists($classname, $_assoc_Autoload)) {
            echo '<br><b>Class <i>"' . $classname . '"</i> not found!</b><br>';
            return;
        }
        include $_assoc_Autoload[$classname];
        return;
    }
    
    public function __GetPage() {
        if (!array_key_exists('p', $_GET)) {
            return null;
        }
        $CurrentPage = $_GET['p'];
    }
    
    public function Render() {
        $CurrentPage = $this->__GetPage();
        if ($CurrentPage == null) {
            
        }
    }
    
    
}
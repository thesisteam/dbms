<?php

/**
 * Class as an Index page helper. Useful for index page manipulations.
 */
final class Index {
   
    static public $DEFAULT_PAGE = 'home';
    static public $FLASHES = array();
    
    public function __construct() {
        # INDEX ENTITIES INITIALIZED HERE
        
        // error reporting - all errors for development (ensure you have display_errors = On in your php.ini file)
        error_reporting(E_ALL | E_STRICT);
        mb_internal_encoding('UTF-8');
        // set_exception_handler(array($this, 'handleException'));
        spl_autoload_register(array($this, 'LoadClass'));
        // session
        session_start();
        
        # Connect to database
        $PDO = PDOSQL::Connect();
        
        # Determine value of Rootpath
        $WebLocation = parse_ini_file('app.ini')['WEB_LOCATION'];
        
        # Assign constants DIR( $ROOT, $PAGE )
        DIR::$ROOT = '/';
        DIR::$CONFIG = 'config/';
        DIR::$PAGE = 'page/';
        DIR::$HEADER = DIR::$PAGE . 'header/';
        DIR::$FOOTER = DIR::$PAGE . 'footer/';
        DIR::$SYSTEM = DIR::$SYSTEM . 'sys/';
        SYS::$PAGES = parse_ini_file(DIR::$CONFIG . 'pages.ini');
        
        # Validate the current page action call
        $this->ValidatePage();
    }
    
    public function LoadClass($classname) {
        $WebLocation = parse_ini_file('app.ini')['WEB_LOCATION'];
        $_assoc_Autoload = parse_ini_file('config/autoload.ini');
        if (!array_key_exists($classname, $_assoc_Autoload)) {
            echo '<br><b>Class <i>"' . $classname . '"</i> not found!</b><br>';
            return;
        }
        include $_assoc_Autoload[$classname];
        return;
    }
    
    public function __GetPage() {
        if (!array_key_exists('page', $_GET)) {
            return null;
        }
        return $_GET['page'];
    }
    
    /**
     * Returns the path to the .PHTML file of $page, otherwise, null.
     * @param String $page
     * @return String
     */
    public static function __GetPagefile($page) {
        return (self::__HasPage($page) ?
                SYS::$PAGES[$page] . '.phtml' 
            :   null
        );
    }
    /**
     * Returns the path to the .PHP file of $page, otherwise, null.
     * @param String $page
     * @return String
     */
    public static function __GetScriptfile($page) {
        return (self::__HasScript($page) ?
                SYS::$PAGES[$page] . '.php'
            :   null
        );
    }
    
    /**
     * Returns if page has page file (.phtml)
     * @param String $page
     * @return boolean
     */
    public static function __HasPage($page) {
        $pages = parse_ini_file(DIR::$CONFIG . 'pages.ini');
        if (array_key_exists($page, $pages)) {
            return file_exists($pages[$page] . '.phtml');
        } else {
            return false;
        }
    }
    
    /**
     * Returns if page has script file (.php)
     * @param String $page
     * @return boolean
     */
    public static function __HasScript($page) {
        return file_exists(DIR::$PAGE . $page . '.php');
    }
    
    /**
     * Checks if $_POST has content during the load of this page
     * @param string $datakey (Optional) The key of the post data to be extracted
     * @return boolean
     */
    public static function __HasPostData($datakey = null) {
        if (is_null($datakey)) {
            return count($_POST) > 0;
        } else {
            return array_key_exists($datakey, $_POST);
        }
    }
    
    /**
     * Renders the page by default parameters
     */
    public function Run() {
        $this->RenderPage($this->__GetPage());
    }
    
    /**
     * Renders the page with specifiable parameters
     * @param type $page Specific page to be rendered
     * @param type $header Specific header page name to be rendered
     * @param type $footer Specific footer page name to be rendered
     */
    public function RenderPage(
            $page = null,
            $header = 'header',
            $footer = 'footer') {
        $page = (is_null($page) ? self::$DEFAULT_PAGE : $page);
        
        // Render HEADER
        include DIR::$HEADER . $header . '.php';
        include DIR::$HEADER . $header . '.phtml';
        
        // Render BODY
        include self::__GetScriptfile($page);
        # Include flashes
        FLASH::Initialize();
        include self::__GetPagefile($page);
        
        // Render FOOTER
        include DIR::$FOOTER . $footer . '.php';
        include DIR::$FOOTER . $footer . '.phtml';
    }
    
    private function ValidatePage($page = null) {
        if ($page == null) {
            $page = $this->__GetPage() != null ?
                    $this->__GetPage()
                :   self::$DEFAULT_PAGE;
        }
        if (!preg_match('/^[a-z0-9-]+$/i', $page)) {
            // TODO log attempt, redirect attacker, ...
            //throw new Exception('Unsafe page "' . $page . '" requested');
            
            header('location:/?page=404&target=' . $page . '&malicious=yes');
        }
        if (!self::__HasPage($page) /* && !$this->__HasScript($page) */) {
            // TODO log attempt, redirect attacker, ...
            // throw new Exception('Page "' . $page . '" not found');
            header('location:?page=404&target=' . $page);
        }
        return true;
    }
    
    
}
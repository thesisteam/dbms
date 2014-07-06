<?php

/**
 * Class for applicational interface layer managements
 */
final class UI {
            
    /**
     * Generates URL of a page
     * @param String $page
     * @return String The generated URL of the specified page
     */
    public static function GetPageUrl($page) {
        if (Index::__HasPage($page)) {
            return '?page=' . str_replace(" ", "-", $page);
        }
        else {
            return '?page=NO-PAGE';
        }
    }
    
    /**
     * Prints an HTML horizontal line (hr) element
     */
    public static function HorizontalLine() {
        echo '<hr>';
    }
    
    /*
     * Prints an HTML Line break (br) element
     */
    public static function NewLine() {
        echo '<br>';
    }
    
    /**
     * Redirects the user to certain page in this site
     * @param String $pagename The name of the page where the user will be redirected
     * @param Array(Assoc) Assoc-array containing additional GET values in redirection URL
     */
    public static function RedirectTo($pagename, $addons = array()) {
        $redir_string = 'location:?page=' . str_replace(' ', '-', trim(strtolower($pagename)));
        if (count($addons) > 0) {
            do {
                $redir_string .= '&' . str_replace(' ', '', (trim(key($addons)))) . '=' . urlencode(current($addons));
            } while(next($addons));
        }
        header($redir_string);
    }
    
}

?>
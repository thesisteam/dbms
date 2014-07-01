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
    
}

?>
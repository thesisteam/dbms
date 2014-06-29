<?php

final class UI {
            
    /**
     * Generates URL of a page, should be ECHOed
     * @param type $page
     */
    public static function GetPageUrl($page) {
        if (Index::__HasPage($page)) {
            return '?page=' . str_replace(" ", "-", $page);
        }
        else {
            return '?page=NO-PAGE';
        }
    }
    
    public static function HorizontalLine() {
        echo '<hr>';
    }
    
    public static function NewLine() {
        echo '<br>';
    }
    
}

?>
<?php

/**
 * Class for applicational interface layer managements<br>
 * This includes:<br>
 * <ul>
 * <li>Page redirection</li>
 * <li>Page URL generator</li>
 * </ul><br>
 * <br>
 * HTML rendering features are:<br>
 * <ul>
 * <li>Break lines</li>
 * <li>Buttons</li>
 * <li>Horizontal lines</li>
 * <li>Lists</li>
 * </ul>
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
        } else {
            return '?page=no-page';
        }
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
            } while (next($addons));
        }
        header($redir_string);
    }

    
    
    
    # HTML UI Methods ----------------------------------------------------------

    /**
     * Render an HTML Button
     * @param String $str_caption Button caption
     * @param String $str_type Type of button
     * @param String $class CSS class properties for this button
     * @param type $str_clickhref
     */
    public static function Button($str_caption, $str_type="button", $class=null, $str_clickhref=null) {
        $strStream = '<input type="' . $str_type . '" value="' . $str_caption . '"';
        if (is_string($class)) {
            $strStream .= ' class="' . $class . '"';
        }
        if (is_string($str_clickhref)) {
            $strStream .= ' onclick="window.location=\'' . $str_clickhref . '\'"';
        }
        $strStream = trim($strStream) . '>' . PHP_EOL;
        echo $strStream;
    }
    
    /**
     * Creates LIST element
     * @param Array $a_items Array of list values
     * @param Boolean $is_orderedlist (Optional) Boolean value if list should
     *      be ordered or not
     * @param String $str_type (Optional) If $is_orderedlist, then specify the
     *      type of this list
     */
    public static function CreateList($a_items, $is_orderedlist = false, $str_type = '1') {
        echo ($is_orderedlist ? '<ol>' : '<ul type="' . $str_type . '">') . PHP_EOL;
        foreach ($a_items as $item) {
            echo '<li>' . $item . '</li>' . PHP_EOL;
        }
        echo ($is_orderedlist ? '</ol>' : '</ul>') . PHP_EOL;
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
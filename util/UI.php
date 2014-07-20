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
    public static function GetPageUrl($page, $addons=array()) {
        $return_url = "";
        if (Index::__HasPage($page)) {
            $return_url = '?page=' . str_replace(" ", "-", $page);
        } else {
            $return_url = '?page=no-page';
        }
        if (count($addons)>0) {
            do {
                $return_url .= '&' . trim(key($addons)) . '=' . trim(current($addons));
            } while(next($addons));
        }
        return $return_url;
    }
    
    public static function makeNavigationLink($text, $url, $str_disable_onpage='', $starttag='<b>', $endtag='</b>') {
        echo PHP_EOL;
        echo strtolower(Index::__GetPage())!=strtolower($str_disable_onpage) ?
            '<a href="' . $url . '">'
          : $starttag;
        echo $text;
        echo strtoupper(Index::__GetPage())!=strtoupper($str_disable_onpage) ?
            '</a>'
          : $endtag;
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
     * @param String $str_clickhref The target link for this button
     * @param String $str_disable_on_page The current URL through UI::GetPageUrl() 
     * @return String The rendered input button as HTML String code
     */
    public static function Button($str_caption, $str_type="button", $class=null, $str_clickhref=null, $is_render=true, $str_disable_on_page=null) {
        $strStream = '<input type="' . $str_type . '" value="' . $str_caption . '"';
        if (is_string($class)) {
            $strStream .= ' class="' . $class . '"';
        }
        if (is_string($str_clickhref)) {
            $strStream .= ' onclick="window.location=\'' . $str_clickhref . '\'"';
        }
        if (is_string($str_disable_on_page) && strtolower($str_disable_on_page)==strtolower(Index::__GetPage())) {
            $strStream .= ' disabled';
        }
        $strStream = trim($strStream) . '>' . PHP_EOL;
        if ($is_render) {
            echo $strStream;
            return $strStream;
        } else {
            return $strStream;
        }
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
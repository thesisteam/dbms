<?php

/**
 * Static utility class for String management and checkings
 */
final class STR {
    
    /**
     * Checks if the string contains spaces
     * @param String $string The string to be validated
     * @return boolean
     */
    public static function __HasSpaces($string) {
        return ctype_space($string);
    }
    
    /**
     * Checks if the string contains punctuation marks and/or spaces
     * @param String $string The string to be validated
     * @param Boolean $is_includespaces (Optional) Boolean value if spaces should also be restricted
     * @return boolean
     */
    public static function __HasPunct($string, $is_includespaces=false) {
        return ctype_punct($string) 
            && ($is_includespaces ? self::__HasSpaces($string) : true);
    }
    
    /**
     * Checks if the string is a valid numeric value
     * @param String $string The string to be validated
     * @return boolean
     */
    public static function __IsNumericOnly($string) {
        return ctype_digit($string);
    }
    
    /**
     * Checks if the string is a valid username value
     * @param String $string The username string to be validated
     * @return boolean
     */
    public static function __IsValidUsername($string) {
        return self::__HasPunct($string, true);
    }
    
}

?>
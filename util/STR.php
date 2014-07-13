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
    public static function __HasPunct($string, $is_includespaces = false) {
        return ctype_punct($string) && ($is_includespaces ? self::__HasSpaces($string) : true);
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

    /**
     * Format a name 'FirstNAME MiddleINITIAL. LastNAME' into certain formats
     * @param String $name The name to be processed
     * @param String $formatmask Use the following:<br>
     * <ul>
     * <li></li>
     * </ul>
     * @return String The newly masked name
     */
    public static function FormatName($name, $formatmask) {
        $name = self::RemoveExcessiveSpacing($name);

        $fname = '';
        $mname = '';
        $lname = '';

        $mode = 'FIRSTNAME'; # FIRSTNAME|| LASTNAME

        $a_name = explode(' ', $name);
        foreach ($a_name as $name) {
            $has_dot = $name[strlen($name) - 1] == '.';

            # For `fname`
            if ($mode == 'FIRSTNAME' && !$has_dot) {
                $fname .= $name . ' ';
            }
            # For `mname`
            else if ($mode == 'FIRSTNAME' && $has_dot) {
                $mname = str_replace('.', '', $name) . ' ';
                $mode = 'LASTNAME';
            }
            # For `lname`
            else if ($mode == 'LASTNAME' && !$has_dot) {
                $lname .= $name . ' ';
            }
        }
        
        // String cleanup
        $fname = trim($fname);
        $mname = trim($mname);
        $lname = trim($lname);

        // Masking phase
        $result = $formatmask;
        $result = str_replace('%F', $fname, $result);
        $result = str_replace('%M', $mname, $result);
        $result = str_replace('%L', $lname, $result);

        return $result;
    }

    /**
     * Removes excessive usage of spaces in certain string
     * @param String $str_specimen The specimen to be processed
     * @param String The processed string
     */
    public static function RemoveExcessiveSpacing($str_specimen) {
        # Trim out trailing left-right spaces
        $str_specimen = trim($str_specimen);
        $str_processed = '';

        # Start doing this job
        $a_chars = str_split($str_specimen);

        $has_last_space = false; // If there's a space char that currently exist
        $ctr = 0; // Counter/pointer
        foreach ($a_chars as $char) {
            if ($char == ' ' && $has_last_space) {
                $ctr++;
                continue;
            }

            // Notify the secretary
            if ($char == ' ' && !$has_last_space) {
                $has_last_space = true;
            } else if ($char != ' ' && $has_last_space) {
                $has_last_space = false;
            }

            $str_processed .= $char;
            $ctr++;
        }
        return $str_processed;
    }

}

?>